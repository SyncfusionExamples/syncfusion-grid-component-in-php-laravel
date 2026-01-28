<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * This is now dynamic - data is stored in database and can be managed via API
     */
    public function run(): void
    {
        // Clear existing students
        Student::truncate();

        // Generate 1000 student records dynamically
        $firstNames = ['John', 'Emily', 'Michael', 'Olivia', 'Daniel', 'Ethan', 'Madison', 'Logan', 'Chloe', 'Jackson', 'William', 'Abigail', 'Henry', 'Victoria', 'Samuel', 'Charles', 'Megan', 'Jason', 'Lily', 'Alexander', 'Jacob', 'Emma', 'Lucas', 'Amelia', 'Mason', 'Liam', 'Ava', 'Noah', 'Sophie', 'Elijah', 'Isabella', 'Benjamin', 'Harper', 'Evelyn', 'James', 'Scarlett', 'Caroline', 'Hudson', 'Paisley', 'Charlotte', 'Oliver', 'Avery', 'Julia', 'Aiden', 'Nathan', 'Sophia', 'David'];
        
        $lastNames = ['Miller', 'Johnson', 'Anderson', 'Harris', 'Thompson', 'Carter', 'Rivera', 'Brooks', 'Ward', 'Bailey', 'Clark', 'Lewis', 'Allen', 'Young', 'King', 'Green', 'Hall', 'Adams', 'Baker', 'Nelson', 'Murphy', 'Davis', 'Garcia', 'Martinez', 'Lee', 'Taylor', 'Thomas', 'Jackson', 'White', 'Martin', 'Rodriguez', 'Wilson', 'Moore', 'Williams', 'Myers', 'Kelley', 'Watts', 'Barrett', 'Shaw', 'Bennett', 'Scott', 'Pierce', 'Reed', 'Henderson'];
        
        $courses = ['Banking', 'Finance', 'Investment Banking', 'E-Commerce', 'Healthcare Management', 'Environmental Science', 'Retail Management', 'Logistics', 'Supply Chain', 'Philosophy', 'Hospitality', 'Graphic Design', 'Media & Entertainment'];
        
        $statuses = ['Active', 'Graduated', 'Inactive'];
        
        $campuses = ['Main', 'Downtown', 'North', 'South'];
        
        $areaCodes = [212, 604, 310, 415, 514, 206, 403, 617, 702, 778, 201, 416, 250, 905, 519];

        $students = [];

        for ($i = 1; $i <= 1000; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $areaCode = $areaCodes[array_rand($areaCodes)];
            $phone1 = rand(200, 999);
            $phone2 = rand(1000, 9999);
            $phone3 = rand(1000, 9999);

            $students[] = [
                'StudentID' => 'STU-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'FirstName' => $firstName,
                'LastName' => $lastName,
                'Email' => strtolower($firstName . '.' . $lastName . $i . '@example.com'),
                'Phone' => '+1 ' . $areaCode . ' ' . $phone1 . ' ' . $phone2,
                'Course' => $courses[array_rand($courses)],
                'Status' => $statuses[array_rand($statuses)],
                'Campus' => $campuses[array_rand($campuses)]
            ];
        }

        // Insert all student records into database
        foreach ($students as $student) {
            Student::create($student);
        }

        // Log completion
        $this->command->info('Successfully seeded ' . count($students) . ' students into the database.');
    }
}


