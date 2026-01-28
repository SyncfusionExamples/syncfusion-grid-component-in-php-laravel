<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class ServerController extends Controller
{
    /**
     * Read/Query handler for DataManager with filtering, sorting, paging, search
     */
    public function read(Request $request)
    {
        $dm = $request->json()->all() ?: $request->all();
        $query = Student::query();
        
        // Apply filtering
        if (!empty($dm['where'])) {
            $this->applyWhere($query, $dm['where']);
        }
        
        // Apply search
        if (!empty($dm['search'])) {
            $this->applySearch($query, $dm['search']);
        }
        
        // Get total count before paging
        $count = $query->count();
        
        // Apply sorting
        if (!empty($dm['sorted'])) {
            foreach ($dm['sorted'] as $sort) {
                $direction = strtolower($sort['direction'] ?? 'ascending') === 'descending' ? 'desc' : 'asc';
                $query->orderBy($sort['name'], $direction);
            }
        }
        
        // Apply paging
        if (isset($dm['skip']) && $dm['skip'] != 0) {
            $query->skip((int) $dm['skip']);
        }
        if (isset($dm['take']) && $dm['take'] != 0) {
            $query->take((int) $dm['take']);
        }
        
        $result = $query->get();
        
        return response()->json([
            'result' => $result,
            'count'  => $dm['requiresCounts'] ? $count : $result->count()
        ]);
    }

    /**
     * Insert handler for new records
     */
    public function insert(Request $request)
    {
        try {
            $data = $request->json()->all() ?: $request->all();
            $updatedRecord = $data['value'] ?? $data;

            // Validate required fields - FirstName is mandatory, LastName is optional
            if (empty($updatedRecord['FirstName'])) {
                return response()->json(['message' => 'FirstName is required'], 422);
            }
            
            $student = Student::create($updatedRecord);
            return response()->json($student, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Insert failed: ' . $e->getMessage()], 422);
        }
    }

    /**
     * Update handler for existing records
     */
    public function update(Request $request)
    {
        // Retrieve updated record from request
        $requestData = $request->json()->all() ?: $request->all();
        $updatedRecord = $requestData['value'] ?? $requestData;
            
        // Grid sends StudentID as the key for updates
        $studentId = $updatedRecord['StudentID'] ?? null;
            
        if (empty($studentId)) {
            return response()->json(['message' => 'Missing StudentID'], 422);
        }
            
        $student = Student::where('StudentID', $studentId)->firstOrFail();
            
        $student->fill($updatedRecord)->save();
            
        // Retrieve and return updated record
        $updatedOrder = $student->fresh();
        return response()->json($updatedOrder);
    }

    /**
     * Remove handler for deleting records
     */
    public function remove(Request $request)
    {
        $studentId = $request->json('key') ?: $request->input('key');
            
        if (!$studentId) {
            return response()->json(['message' => 'Missing StudentID'], 422);
        }
            
        Student::where('StudentID', $studentId)->firstOrFail()->delete();
        return response()->json(['deleted' => $studentId]);
    }

    /**
     * Apply WHERE filters from DataManager predicates
     */
    private function applyWhere($query, array $where)
    {
        $applyGroup = function($q, $group, $logic = 'and') use (&$applyGroup) {
            $method = strtolower($logic) === 'or' ? 'orWhere' : 'where';
            
            foreach ($group as $pred) {
                // Handle complex (nested) predicates
                if (!empty($pred['isComplex']) && !empty($pred['predicates'])) {
                    $q->$method(function($nested) use ($pred, $applyGroup) {
                        $applyGroup($nested, $pred['predicates'], $pred['condition'] ?? 'and');
                    });
                    continue;
                }
                
                // Simple predicate
                $field = $pred['field'] ?? null;
                $operator = $pred['operator'] ?? 'equal';
                $value = $pred['value'] ?? null;
                $ignoreCase = $pred['ignoreCase'] ?? true;
                
                if (!$field) continue;
                
                $this->applyOperator($q, $method, $field, $operator, $value, $ignoreCase);
            }
        };
        
        $applyGroup($query, $where, 'and');
    }

    /**
     * Apply individual operator to query
     */
    private function applyOperator($q, $method, $field, $operator, $value, $ignoreCase)
    {
        switch ($operator) {
            case 'equal':
                $q->$method($field, '=', $value);
                break;
            case 'notequal':
                $q->$method($field, '!=', $value);
                break;
            case 'greaterthan':
                $q->$method($field, '>', $value);
                break;
            case 'greaterthanorequal':
                $q->$method($field, '>=', $value);
                break;
            case 'lessthan':
                $q->$method($field, '<', $value);
                break;
            case 'lessthanorequal':
                $q->$method($field, '<=', $value);
                break;
            case 'contains':
                $this->applyLikeOperator($q, $method, $field, '%' . $value . '%', $ignoreCase);
                break;
            case 'startswith':
                $this->applyLikeOperator($q, $method, $field, $value . '%', $ignoreCase);
                break;
            case 'endswith':
                $this->applyLikeOperator($q, $method, $field, '%' . $value, $ignoreCase);
                break;
            case 'in':
                $methodName = $method . 'In';
                $q->$methodName($field, is_array($value) ? $value : [$value]);
                break;
            case 'notin':
                $methodName = $method . 'NotIn';
                $q->$methodName($field, is_array($value) ? $value : [$value]);
                break;
            case 'isnull':
                $q->$method . 'Null'($field);
                break;
            case 'isnotnull':
                $q->$method . 'NotNull'($field);
                break;
            default:
                $q->$method($field, '=', $value);
        }
    }

    /**
     * Apply LIKE operator with case sensitivity
     */
    private function applyLikeOperator($q, $method, $field, $likeVal, $ignoreCase)
    {
        if ($ignoreCase) {
            if ($method === 'orWhere') {
                $q->orWhereRaw("LOWER($field) LIKE ?", [mb_strtolower($likeVal)]);
            } else {
                $q->whereRaw("LOWER($field) LIKE ?", [mb_strtolower($likeVal)]);
            }
        } else {
            $q->$method($field, 'LIKE', $likeVal);
        }
    }

    /**
     * Apply quick SEARCH filters
     */
    private function applySearch($query, array $search)
    {
        foreach ($search as $s) {
            $fields = $s['fields'] ?? [];
            $key = $s['key'] ?? '';
            
            if (empty($fields) || !$key) continue;
            
            $operator = $s['operator'] ?? 'contains';
            $ignoreCase = $s['ignoreCase'] ?? true;
            
            $query->where(function($q) use ($fields, $key, $operator, $ignoreCase) {
                foreach ($fields as $i => $field) {
                    $method = $i === 0 ? 'where' : 'orWhere';
                    
                    if ($operator === 'contains') {
                        $this->applyLikeOperator($q, $method, $field, '%' . $key . '%', $ignoreCase);
                    } else {
                        $q->$method($field, '=', $key);
                    }
                }
            });
        }
    }
}
