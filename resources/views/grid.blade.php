<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management Grid</title>

    <!-- Syncfusion EJ2 Theme (Material 3 Light by default) -->
    <link id="ej2-theme" rel="stylesheet" href="https://cdn.syncfusion.com/ej2/32.1.24/material3-lite.css">

    <!-- Custom Grid Styles -->
    <link rel="stylesheet" href="{{ asset('css/grid-custom.css') }}">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">Student Management System</h1>
            <div class="controls">
                <button class="icon-button theme-toggle" id="themeToggle" aria-label="Toggle dark theme">
                    <!-- Sun Icon (shown in dark mode) -->
                    <svg class="sun-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" role="presentation">
                        <circle cx="12" cy="12" r="5"></circle>
                        <line x1="12" y1="1" x2="12" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="23"></line>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                        <line x1="1" y1="12" x2="3" y2="12"></line>
                        <line x1="21" y1="12" x2="23" y2="12"></line>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                    </svg>
                    <!-- Moon Icon (shown in light mode) -->
                    <svg class="moon-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" role="presentation">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                </button>
                <button class="icon-button add-column-btn" id="addColumnBtn" aria-label="Add Campus column to grid">
                    <svg class="sf-icon sf-icon-size" viewBox="0 0 24 24" focusable="false" aria-hidden="true"><path d="M13 2V11H22V13H13V22H11V13H2V11H11V2H13Z" fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"></path></svg>
                    <span>Add Campus</span>
                </button>
                <button class="icon-button add-column-btn" id="removeColumnBtn" aria-label="Remove Campus column from grid" style="display: none;">
                    <svg class="sf-icon sf-icon-size" viewBox="0 0 24 24" focusable="false" aria-hidden="true"><path d="M2 11H22V13H2V11Z" fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"></path></svg>
                    <span>Remove Campus</span>
                </button>
            </div>
        </div>

        <div class="grid-wrapper">
            <div id="Grid"></div>
        </div>
    </div>

    <!-- Header Template for GPA Rating -->
    <script type="text/x-template" id="gpaHeaderTemplate">
        <div class="header-icon-container">
            <span class="header-icon gpa-icon" aria-hidden="true"></span>
            <span>GPA</span>
        </div>
    </script>

    <!-- Syncfusion EJ2 Scripts -->
    <script src="https://cdn.syncfusion.com/ej2/32.1.24/dist/ej2.min.js"></script>

    <!-- Grid Initialization - Inline Script -->
    <script>
        /**
         * Student Management Grid Initialization
         * Syncfusion EJ2 Grid with Material 3 themes
         */

        let campusColumnAdded = false;

        var statuses =  [
            { status: 'Active', statusId: '1' },
            { status: 'Graduated', statusId: '2' },
            { status: 'Inactive', statusId: '3' }
        ];

        var campuses = [
            { campus: 'Main', campusId: '1' },
            { campus: 'Downtown', campusId: '2' },
            { campus: 'North', campusId: '3' },
            { campus: 'South', campusId: '4' }
        ];

        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Create DataManager for remote API
                const dataManager = new ej.data.DataManager({
                    url: "{{ url('/api/students/read') }}",
                    insertUrl: "{{ url('/api/students/create') }}",
                    updateUrl: "{{ url('/api/students/update') }}",
                    removeUrl: "{{ url('/api/students/remove') }}",
                    adaptor: new ej.data.UrlAdaptor(),
                    headers: [
                        { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    ]
                });

                // Initialize Grid
                const grid = new ej.grids.Grid({
                    dataSource: dataManager,
                    allowPaging: true,
                    allowSorting: true,
                    allowFiltering: true,
                    enableHover: false,
                    height: '100%',
                    pageSettings: { pageSize: 20 },
                    filterSettings: { type: 'Excel' },
                    editSettings: {
                        allowEditing: true,
                        allowAdding: true,
                        allowDeleting: true,
                        mode: 'Normal',
                        showDeleteConfirmDialog : true,
                        newRowPosition: 'Top'
                    },
                    toolbar: ['Add', 'Edit', 'Delete', 'Update', 'Cancel', 'Search'],
                    columns: [
                        {
                            field: 'StudentID',
                            headerText: 'Student ID',
                            width: 120,
                            textAlign: 'Right',
                            isPrimaryKey: true,
                            allowEditing: false
                        },
                        {
                            field: 'FirstName',
                            headerText: 'First Name',
                            width: 130,
                            validationRules: { required: true }
                        },
                        {
                            field: 'LastName',
                            headerText: 'Last Name',
                            width: 130
                        },
                        {
                            field: 'Email',
                            headerText: 'Email',
                            width: 200,
                            validationRules: { required: true, email: true }
                        },
                        {
                            field: 'Phone',
                            headerText: 'Phone',
                            width: 150
                        },
                        {
                            field: 'Course',
                            headerText: 'Course',
                            width: 160
                        },
                        {
                            field: 'Status',
                            headerText: 'Status',
                            editType: 'dropdownedit',
                            edit: { 
                                params: {
                                    dataSource: new ej.data.DataManager(statuses),
                                    fields: { text: 'status', value: 'status' },
                                    query:  new ej.data.Query(),
                                    actionComplete: () => false
                                },
                        },
                            width: 140,
                            textAlign: 'Center'
                        }
                    ],
                    queryCellInfo: queryCellInfoHandler,
                    actionFailure: onGridActionFailure
                });

                grid.appendTo('#Grid');
                window.studentGrid = grid;

                // Initialize theme and button handlers
                setupThemeToggle();
                setupAddColumnButton(grid);

            } catch (error) {
                document.getElementById('Grid').innerHTML = '<div class="error-message">Error loading grid: ' + error.message + '</div>';
            }
        });

        /**
         * Apply CSS classes based on cell data
         */
        function queryCellInfoHandler(args) {
            const field = args.column.field;
            const data = args.data;
            const cell = args.cell;

            if (field === 'Status' && data.Status) {
                const status = data.Status;
                cell.classList.remove('status-active', 'status-inactive', 'status-graduated');
                
                if (status === 'Active') {
                    cell.classList.add('status-active');
                } else if (status === 'Inactive') {
                    cell.classList.add('status-inactive');
                } else if (status === 'Graduated') {
                    cell.classList.add('status-graduated');
                }
            }

            if (field === 'Campus' && data.Campus) {
                const campus = data.Campus;
                cell.classList.remove('campus-main', 'campus-downtown', 'campus-north', 'campus-south');
                
                if (campus === 'Main') {
                    cell.classList.add('campus-main');
                } else if (campus === 'Downtown') {
                    cell.classList.add('campus-downtown');
                } else if (campus === 'North') {
                    cell.classList.add('campus-north');
                } else if (campus === 'South') {
                    cell.classList.add('campus-south');
                }
            }
        }

        /**
         * Handle grid action failures
         */
        function onGridActionFailure(args) {
        }

        /**
         * Theme Toggle
         */
        function setupThemeToggle() {
            const themeToggle = document.getElementById('themeToggle');
            const themeLink = document.getElementById('ej2-theme');

            const lightUrl = 'https://cdn.syncfusion.com/ej2/32.1.24/material3-lite.css';
            const darkUrl = 'https://cdn.syncfusion.com/ej2/32.1.24/material3-dark.css';

            const savedTheme = localStorage.getItem('studentGridTheme') || 'light';
            applyTheme(savedTheme);

            themeToggle.addEventListener('click', () => {
                const newTheme = document.body.classList.contains('dark-theme') ? 'light' : 'dark';
                applyTheme(newTheme);
            });

            function applyTheme(theme) {
                if (theme === 'dark') {
                    themeLink.href = darkUrl;
                    document.body.classList.add('dark-theme');
                } else {
                    themeLink.href = lightUrl;
                    document.body.classList.remove('dark-theme');
                }
                localStorage.setItem('studentGridTheme', theme);
            }
        }

        /**
         * Add/Remove Campus Column Dynamically
         */
        function setupAddColumnButton(grid) {
            const addColumnBtn = document.getElementById('addColumnBtn');
            const removeColumnBtn = document.getElementById('removeColumnBtn');

            addColumnBtn.addEventListener('click', () => {
                if (campusColumnAdded) {
                    alert('Campus column already added.');
                    return;
                }

                const newColumn = {
                    field: 'Campus',
                    headerText: 'Campus',
                    width: 140,
                    textAlign: 'Center',
                    allowSorting: true,
                    editType: 'dropdownedit',
                    edit: {
                        params: {
                            dataSource: new ej.data.DataManager(campuses),
                            fields: { text: 'campus', value: 'campus' },
                            query: new ej.data.Query(),
                            actionComplete: () => false
                        }
                    }
                };

                grid.columns.push(newColumn);
                grid.refreshColumns();

                campusColumnAdded = true;
                addColumnBtn.style.display = 'none';
                removeColumnBtn.style.display = 'flex';
            });

            removeColumnBtn.addEventListener('click', () => {
                if (!campusColumnAdded) {
                    alert('Campus column not added yet.');
                    return;
                }

                grid.columns = grid.columns.filter(col => col.field !== 'Campus');
                grid.refreshColumns();

                campusColumnAdded = false;
                addColumnBtn.style.display = 'flex';
                removeColumnBtn.style.display = 'none';
            });
        }
    </script>
</body>
</html>
