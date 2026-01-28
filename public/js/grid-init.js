/**
 * Student Management Grid Initialization
 * Syncfusion EJ2 Grid with Material 3 themes, templates, and cell customization
 */

// Mock student data for demonstration
const mockStudentData = [
    {
        StudentId: 1001,
        StudentName: 'Alice Johnson',
        Email: 'alice@university.edu',
        EnrollmentDate: new Date(2023, 8, 15),
        GPA: 3.95,
        AttendancePercentage: 0.98,
        Status: 'Active',
        Major: 'Computer Science'
    },
    {
        StudentId: 1002,
        StudentName: 'Bob Smith',
        Email: 'bob@university.edu',
        EnrollmentDate: new Date(2022, 3, 10),
        GPA: 3.45,
        AttendancePercentage: 0.92,
        Status: 'Active',
        Major: 'Business Administration'
    },
    {
        StudentId: 1003,
        StudentName: 'Carol White',
        Email: 'carol@university.edu',
        EnrollmentDate: new Date(2023, 9, 5),
        GPA: 3.75,
        AttendancePercentage: 0.88,
        Status: 'Active',
        Major: 'Engineering'
    },
    {
        StudentId: 1004,
        StudentName: 'David Chen',
        Email: 'david@university.edu',
        EnrollmentDate: new Date(2023, 0, 20),
        GPA: 3.20,
        AttendancePercentage: 0.82,
        Status: 'OnLeave',
        Major: 'Mathematics'
    },
    {
        StudentId: 1005,
        StudentName: 'Emma Davis',
        Email: 'emma@university.edu',
        EnrollmentDate: new Date(2021, 8, 1),
        GPA: 3.90,
        AttendancePercentage: 0.95,
        Status: 'Active',
        Major: 'Chemistry'
    },
    {
        StudentId: 1006,
        StudentName: 'Frank Miller',
        Email: 'frank@university.edu',
        EnrollmentDate: new Date(2022, 6, 15),
        GPA: 2.95,
        AttendancePercentage: 0.78,
        Status: 'Inactive',
        Major: 'Physics'
    },
    {
        StudentId: 1007,
        StudentName: 'Grace Martinez',
        Email: 'grace@university.edu',
        EnrollmentDate: new Date(2023, 7, 10),
        GPA: 3.65,
        AttendancePercentage: 0.91,
        Status: 'Active',
        Major: 'Biology'
    },
    {
        StudentId: 1008,
        StudentName: 'Henry Wilson',
        Email: 'henry@university.edu',
        EnrollmentDate: new Date(2023, 2, 25),
        GPA: 3.55,
        AttendancePercentage: 0.87,
        Status: 'Active',
        Major: 'Economics'
    }
];

// Track if Campus column has been added
let campusColumnAdded = false;

// Initialize grid when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Create grid instance
        const grid = new ej.grids.Grid({
            dataSource: mockStudentData,
            allowPaging: true,
            allowSorting: true,
            allowFiltering: true,
            enableHover: false,
            allowSelection: false,
            height: '400px',
            pageSettings: { pageSize: 8 },
            filterSettings: { type: 'Excel' },
            columns: [
                {
                    field: 'StudentId',
                    headerText: 'Student ID',
                    width: 110,
                    textAlign: 'Right',
                    allowSorting: true
                },
                {
                    field: 'StudentName',
                    headerText: 'Student Name',
                    width: 160,
                    allowSorting: true
                },
                {
                    field: 'Email',
                    headerText: 'Email',
                    width: 180,
                    allowSorting: true
                },
                {
                    field: 'Major',
                    headerText: 'Major',
                    width: 140,
                    allowSorting: true
                },
                {
                    field: 'GPA',
                    headerText: 'GPA',
                    headerTemplate: '#gpaHeaderTemplate',
                    width: 100,
                    textAlign: 'Center',
                    allowSorting: true,
                    format: 'N2'
                },
                {
                    field: 'AttendancePercentage',
                    headerText: 'Attendance',
                    headerTemplate: '#attendanceHeaderTemplate',
                    width: 140,
                    textAlign: 'Center',
                    allowSorting: true,
                    format: 'P0'
                },
                {
                    field: 'Status',
                    headerText: 'Status',
                    headerTemplate: '#statusHeaderTemplate',
                    template: '#statusColumnTemplate',
                    width: 130,
                    textAlign: 'Center',
                    allowSorting: true
                }
            ],
            queryCellInfo: queryCellInfoHandler,
            dataBound: onGridDataBound,
            actionComplete: onGridActionComplete
        });

        grid.appendTo('#Grid');

        // Store grid reference globally for Add Column functionality
        window.studentGrid = grid;

        // Theme toggle functionality
        setupThemeToggle();

        // Add Column button functionality
        setupAddColumnButton(grid);

    } catch (error) {
        document.getElementById('Grid').innerHTML = 
            '<div style="color: red; padding: 20px;">Error loading grid: ' + error.message + '</div>';
    }
});

/**
 * QueryCellInfo handler: Fires for every cell render
 * Applies CSS classes based on cell value and column field
 */
function queryCellInfoHandler(args) {
    const field = args.column.field;
    const data = args.data;
    const cell = args.cell;

    // GPA Rating Classes
    if (field === 'GPA' && data.GPA) {
        const gpa = data.GPA;
        if (gpa >= 3.80) {
            cell.classList.add('gpa-excellent');
        } else if (gpa >= 3.50) {
            cell.classList.add('gpa-good');
        } else if (gpa >= 3.0) {
            cell.classList.add('gpa-average');
        } else {
            cell.classList.add('gpa-poor');
        }
        cell.classList.add('cell-centered');
    }

    // Attendance Percentage Classes
    if (field === 'AttendancePercentage' && data.AttendancePercentage !== undefined) {
        const attendance = data.AttendancePercentage;
        if (attendance >= 0.95) {
            cell.classList.add('attendance-excellent');
        } else if (attendance >= 0.90) {
            cell.classList.add('attendance-good');
        } else if (attendance >= 0.80) {
            cell.classList.add('attendance-fair');
        } else {
            cell.classList.add('attendance-poor');
        }
        cell.classList.add('cell-centered');
    }

    // Status Classes
    if (field === 'Status' && data.Status) {
        const status = data.Status;
        if (status === 'Active') {
            cell.classList.add('status-active');
        } else if (status === 'OnLeave') {
            cell.classList.add('status-on-leave');
        } else if (status === 'Inactive') {
            cell.classList.add('status-inactive');
        } else if (status === 'Graduated') {
            cell.classList.add('status-graduated');
        }
        cell.classList.add('cell-centered');
    }

    // Campus Classes (for dynamically added column)
    if (field === 'Campus' && data.Campus) {
        const campus = data.Campus;
        if (campus === 'Main') {
            cell.classList.add('campus-main');
        } else if (campus === 'Downtown') {
            cell.classList.add('campus-downtown');
        } else if (campus === 'North') {
            cell.classList.add('campus-north');
        } else if (campus === 'South') {
            cell.classList.add('campus-south');
        }
        cell.classList.add('cell-centered');
    }
}

/**
 * DataBound event: Fired when grid data binding is complete
 * Initialize dropdown controls if they exist
 */
function onGridDataBound(args) {
    initializeDropdowns();
}

/**
 * ActionComplete event: Fired after grid action completes
 */
function onGridActionComplete(args) {
    if (args.requestType === 'paging' || args.requestType === 'sorting' || args.requestType === 'filtering') {
        initializeDropdowns();
    }
}

/**
 * Initialize EJ2 DropDownList controls in cells
 * Looks for elements with class 'status-dropdown' and 'campus-dropdown'
 */
function initializeDropdowns() {
    const statusDropdowns = document.querySelectorAll('.status-dropdown:not(.e-ddl)');
    statusDropdowns.forEach(function(dropdown) {
        if (!dropdown.classList.contains('e-ddl')) {
            new ej.dropdowns.DropDownList({
                dataSource: ['Active', 'Inactive', 'OnLeave', 'Graduated'],
                fields: { text: 'text', value: 'text' },
                value: dropdown.value
            }, dropdown);
        }
    });

    const campusDropdowns = document.querySelectorAll('.campus-dropdown:not(.e-ddl)');
    campusDropdowns.forEach(function(dropdown) {
        if (!dropdown.classList.contains('e-ddl')) {
            new ej.dropdowns.DropDownList({
                dataSource: ['Main', 'Downtown', 'North', 'South'],
                fields: { text: 'text', value: 'text' },
                value: dropdown.value
            }, dropdown);
        }
    });
}

/**
 * Theme Toggle Functionality
 * Swaps Material 3 theme between Light and Dark
 */
function setupThemeToggle() {
    const themeToggle = document.getElementById('themeToggle');
    const themeLink = document.getElementById('ej2-theme');

    const lightThemeUrl = 'https://cdn.syncfusion.com/ej2/32.1.24/material3-lite.css';
    const darkThemeUrl = 'https://cdn.syncfusion.com/ej2/32.1.24/material3-dark.css';

    // Check localStorage for saved theme preference
    const savedTheme = localStorage.getItem('studentGridTheme') || 'light';
    if (savedTheme === 'dark') {
        applyDarkTheme();
    } else {
        applyLightTheme();
    }

    themeToggle.addEventListener('click', function() {
        const body = document.body;
        if (body.classList.contains('dark-theme')) {
            applyLightTheme();
        } else {
            applyDarkTheme();
        }
    });

    function applyDarkTheme() {
        themeLink.href = darkThemeUrl;
        document.body.classList.add('dark-theme');
        themeToggle.textContent = '☀️ Light Mode';
        localStorage.setItem('studentGridTheme', 'dark');
    }

    function applyLightTheme() {
        themeLink.href = lightThemeUrl;
        document.body.classList.remove('dark-theme');
        themeToggle.textContent = '🌙 Dark Mode';
        localStorage.setItem('studentGridTheme', 'light');
    }
}

/**
 * Add Column Button Functionality
 * Dynamically adds a Campus column with headerTemplate and template
 */
function setupAddColumnButton(grid) {
    const addColumnBtn = document.getElementById('addColumnBtn');

    addColumnBtn.addEventListener('click', function() {
        if (campusColumnAdded) {
            alert('Campus column already added. Refresh to reset.');
            return;
        }

        // Add Campus data to existing mock data
        mockStudentData.forEach(function(student) {
            student.Campus = ['Main', 'Downtown', 'North', 'South'][Math.floor(Math.random() * 4)];
        });

        // Create new column definition
        const newColumn = {
            field: 'Campus',
            headerText: 'Campus',
            headerTemplate: '#dynamicHeaderTemplate',
            template: '#dynamicColumnTemplate',
            width: 140,
            textAlign: 'Center',
            allowSorting: true
        };

        // Push column to grid
        grid.columns.push(newColumn);

        // Refresh grid columns (this will re-render the grid with new column)
        grid.refreshColumns();

        // Reinitialize dropdowns after column is added
        setTimeout(function() {
            initializeDropdowns();
        }, 200);

        campusColumnAdded = true;
        addColumnBtn.disabled = true;
        addColumnBtn.style.opacity = '0.5';
        addColumnBtn.style.cursor = 'not-allowed';
        alert('Campus column added successfully!');
    });
}
