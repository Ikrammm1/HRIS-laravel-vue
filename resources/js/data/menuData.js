// Dummy Menu Data untuk HR Portal
export const menuState = {
    isSidebarMinimized: false,
    currentExpandedMenuItem: null,
    mainMenu: [
        {
            name: 'Dashboard',
            icon: 'home',
            to: '/panel/dashboard',
            showDesktop: true,
            showMobile: true,
        },
        {
            name: 'Users',
            icon: 'users',
            showDesktop: true,
            showMobile: true,
            children: [
                {
                    name: 'All Users',
                    to: '/panel/users',
                    showDesktop: true,
                    showMobile: true,
                },
                {
                    name: 'Add User',
                    to: '/panel/users/create',
                    showDesktop: true,
                    showMobile: true,
                },
                {
                    name: 'User Roles',
                    to: '/panel/users/roles',
                    showDesktop: true,
                    showMobile: true,
                },
            ]
        },
        {
            name: 'Employees',
            icon: 'user-tie',
            showDesktop: true,
            showMobile: true,
            children: [
                {
                    name: 'Employee List',
                    to: '/panel/employees',
                    showDesktop: true,
                    showMobile: true,
                },
                {
                    name: 'Add Employee',
                    to: '/panel/employees/create',
                    showDesktop: true,
                    showMobile: true,
                },
                {
                    name: 'Departments',
                    to: '/panel/departments',
                    showDesktop: true,
                    showMobile: true,
                },
                {
                    name: 'Positions',
                    to: '/panel/positions',
                    showDesktop: true,
                    showMobile: true,
                },
            ]
        },
        {
            name: 'Attendance',
            icon: 'calendar-check',
            showDesktop: true,
            showMobile: true,
            children: [
                {
                    name: 'Daily Attendance',
                    to: '/panel/attendance',
                    showDesktop: true,
                    showMobile: true,
                },
                {
                    name: 'Attendance Report',
                    to: '/panel/attendance/report',
                    showDesktop: true,
                    showMobile: true,
                },
                {
                    name: 'Leave Requests',
                    to: '/panel/leave-requests',
                    showDesktop: true,
                    showMobile: true,
                    label: '3', // Badge untuk notifikasi
                },
            ]
        },
        {
            name: 'Payroll',
            icon: 'wallet',
            showDesktop: true,
            showMobile: true,
            children: [
                {
                    name: 'Salary Management',
                    to: '/panel/payroll/salary',
                    showDesktop: true,
                    showMobile: true,
                },
                {
                    name: 'Payslips',
                    to: '/panel/payroll/payslips',
                    showDesktop: true,
                    showMobile: true,
                },
                {
                    name: 'Bonuses',
                    to: '/panel/payroll/bonuses',
                    showDesktop: true,
                    showMobile: true,
                },
            ]
        },
        {
            name: 'Recruitment',
            icon: 'briefcase',
            to: '/panel/recruitment',
            showDesktop: true,
            showMobile: true,
            label: '5',
        },
        {
            name: 'Performance',
            icon: 'chart-line',
            showDesktop: true,
            showMobile: true,
            children: [
                {
                    name: 'Performance Review',
                    to: '/panel/performance/review',
                    showDesktop: true,
                    showMobile: true,
                },
                {
                    name: 'Goals & KPI',
                    to: '/panel/performance/goals',
                    showDesktop: true,
                    showMobile: true,
                },
            ]
        },
        {
            name: 'Reports',
            icon: 'file-chart',
            to: '/panel/reports',
            showDesktop: true,
            showMobile: true,
        },
        {
            name: 'Settings',
            icon: 'cog',
            showDesktop: true,
            showMobile: true,
            children: [
                {
                    name: 'General Settings',
                    to: '/panel/settings/general',
                    showDesktop: true,
                    showMobile: true,
                },
                {
                    name: 'Company Profile',
                    to: '/panel/settings/company',
                    showDesktop: true,
                    showMobile: true,
                },
                {
                    name: 'Notifications',
                    to: '/panel/settings/notifications',
                    showDesktop: true,
                    showMobile: true,
                },
            ]
        },
    ],
    footerLeftLink: {
        name: 'Documentation',
        icon: 'file-text',
        to: '/panel/documentation',
    }
};