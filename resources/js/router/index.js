import { createRouter, createWebHistory } from 'vue-router';
import store from '@/store';

// Import views
const Login = () => import('@/Pages/Auth/Login.vue');
const Register = () => import('@/Pages/Auth/Register.vue');
const ForgotPassword = () => import('@/Pages/Auth/ForgotPassword.vue');
const ResetPassword = () => import('@/Pages/Auth/ResetPassword.vue');
const Dashboard = () => import('@/Pages/Dashboard.vue');
const Profile = () => import('@/Pages/Profile/Edit.vue');
const Menus = () => import('@/Pages/Menu/index.vue')
const Users = () => import('@/Pages/User/index.vue')
const AuthGroup = () => import('@/Pages/AuthorizationGroup/index.vue')
const Employe = () => import('@/Pages/Employe/index.vue')
const Division = () => import('@/Pages/Organization/Division/index.vue')
const Department = () => import('@/Pages/Organization/Department/index.vue')
const Shift = () => import('@/Pages/Setup/Shift/index.vue')
const WorkSchedule = () => import('@/Pages/Setup/WorkSchedule/index.vue')
const HolidayCalendar = () => import('@/Pages/Setup/HolidayCalendar/index.vue')
const LeaveType = () => import('@/Pages/Setup/LeaveType/index.vue')
// const EmployeesIndex = () => import('@/views/employees/Index.vue');
// const NotFound = () => import('@/NotFound.vue');

const routes = [
    {
        path: '/',
        redirect: { name: 'login' },
    },
    
    // Auth Routes
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            guest: true,
            title: 'Login',
            layout: 'auth',
        },
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            guest: true,
            title: 'Register',
            layout: 'auth',
        },
    },
    {
        path: '/forgot-password',
        name: 'forgot-password',
        component: ForgotPassword,
        meta: {
            guest: true,
            title: 'Forgot Password',
            layout: 'auth',
        },
    },
    {
        path: '/reset-password/:token',
        name: 'reset-password',
        component: ResetPassword,
        meta: {
            guest: true,
            title: 'Reset Password',
            layout: 'auth',
        },
    },
    
    // Protected Routes
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            requiresAuth: true,
            title: 'Dashboard',
            layout: 'default',
        },
    },
    {
        path: '/profile',
        name: 'profile',
        component: Profile,
        meta: {
            requiresAuth: true,
            title: 'Profile',
            layout: 'default',
        },
    },
    {
        path: '/settings/menu',
        name: 'menu',
        component: Menus ,
        meta: {
            requiresAuth: true,
            title: 'Menu Management',
            layout: 'default',
        },
    },
    {
        path: '/settings/user',
        name: 'user',
        component: Users ,
        meta: {
            requiresAuth: true,
            title: 'user Management',
            layout: 'default',
        },
    },
    {
        path: '/settings/autherization-group',
        name: 'autherization-group',
        component: AuthGroup ,
        meta: {
            requiresAuth: true,
            title: 'Autherization Group',
            layout: 'default',
        },
    },
    {
        path: '/employe',
        name: 'employe',
        component: Employe ,
        meta: {
            requiresAuth: true,
            title: 'Employe Management',
            layout: 'default',
        },
    },

    // Organization Routes
    {
        path: '/organization/divisions',
        name: 'Organization-divisions',
        component: Division,    
        meta: {
            requiresAuth: true,
            title: 'Divisions',
            layout: 'default',
        },
    },
    {
        path: '/organization/departments',
        name: 'Organization-departments',
        component: Department,
        meta: {
            requiresAuth: true,
            title: 'Departments',
            layout: 'default',
        },
    },

    //==========================================================
    // Setup Routes
    //==========================================================
    {
        path: '/setup/shift',
        name: 'Setup-shifts',
        component: Shift,
        meta: {
            requiresAuth: true,
            title: 'Shifts',
            layout: 'default',
        },
    },
    {
        path: '/setup/work-schedule',
        name: 'Setup-work-schedules',
        component: WorkSchedule,
        meta: {
            requiresAuth: true,
            title: 'Work Schedules',
            layout: 'default',
        },
    },
    {
        path: '/setup/holiday-calendar',
        name: 'Setup-holiday-calendars',
        component: HolidayCalendar,
        meta: {
            requiresAuth: true,
            title: 'Holiday Calendars',
            layout: 'default',
        },
    },
    {
        path: '/setup/leave-types',
        name: 'Setup-leave-types',
        component: LeaveType,
        meta: {
            requiresAuth: true,
            title: 'Leave Types',
            layout: 'default',
        },
    },

    
    
    // // Employees Routes
    // {
    //     path: '/employees',
    //     name: 'employees',
    //     component: () => import('@/views/employees/Index.vue'),
    //     meta: {
    //         requiresAuth: true,
    //         title: 'Employees',
    //         layout: 'default',
    //     },
    // },
    // {
    //     path: '/employees/create',
    //     name: 'employees-create',
    //     component: () => import('@/views/employees/Create.vue'),
    //     meta: {
    //         requiresAuth: true,
    //         title: 'Create Employee',
    //         layout: 'default',
    //     },
    // },
    // {
    //     path: '/employees/:id',
    //     name: 'employees-show',
    //     component: () => import('@/views/employees/Show.vue'),
    //     meta: {
    //         requiresAuth: true,
    //         title: 'Employee Detail',
    //         layout: 'default',
    //     },
    // },
    // {
    //     path: '/employees/:id/edit',
    //     name: 'employees-edit',
    //     component: () => import('@/views/employees/Edit.vue'),
    //     meta: {
    //         requiresAuth: true,
    //         title: 'Edit Employee',
    //         layout: 'default',
    //     },
    // },
    
    // // Attendance Routes
    // {
    //     path: '/attendance',
    //     name: 'attendance',
    //     component: () => import('@/views/attendance/Index.vue'),
    //     meta: {
    //         requiresAuth: true,
    //         title: 'Attendance',
    //         layout: 'default',
    //     },
    // },
    
    // // Leave Management Routes
    // {
    //     path: '/leaves',
    //     name: 'leaves',
    //     component: () => import('@/views/leaves/Index.vue'),
    //     meta: {
    //         requiresAuth: true,
    //         title: 'Leave Management',
    //         layout: 'default',
    //     },
    // },
    // {
    //     path: '/leaves/request',
    //     name: 'leaves-request',
    //     component: () => import('@/views/leaves/Request.vue'),
    //     meta: {
    //         requiresAuth: true,
    //         title: 'Request Leave',
    //         layout: 'default',
    //     },
    // },
    
    // // Payroll Routes
    // {
    //     path: '/payroll',
    //     name: 'payroll',
    //     component: () => import('@/views/payroll/Index.vue'),
    //     meta: {
    //         requiresAuth: true,
    //         title: 'Payroll',
    //         layout: 'default',
    //     },
    // },
    
    // // Reports Routes
    // {
    //     path: '/reports',
    //     name: 'reports',
    //     component: () => import('@/views/reports/Index.vue'),
    //     meta: {
    //         requiresAuth: true,
    //         title: 'Reports',
    //         layout: 'default',
    //     },
    // },
    

    
    // // 404 Not Found
    // {
    //     path: '/:pathMatch(.*)*',
    //     name: 'not-found',
    //     component: NotFound,
    //     meta: {
    //         title: '404 - Not Found',
    //         layout: 'error',
    //     },
    // },
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { top: 0, behavior: 'smooth' };
        }
    },
});

// Global before guards
router.beforeEach(async (to, from, next) => {
    // Set document title
    const defaultTitle = 'HR Portal';
    document.title = to.meta.title 
        ? `${to.meta.title} - ${defaultTitle}` 
        : defaultTitle;
    
    // Show loading indicator
    store.commit('SET_LOADING', true);
    
    const token = localStorage.getItem('token');
    const isAuthenticated = store.getters['auth/isAuthenticated'];
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    const isGuestRoute = to.matched.some(record => record.meta.guest);
    
    // If route requires auth and user is not authenticated
    if (requiresAuth && !isAuthenticated) {
        store.commit('SET_LOADING', false);
        return next({
            name: 'login',
            query: { redirect: to.fullPath },
        });
    }
    
    // If user is authenticated but accessing guest routes
    if (isGuestRoute && isAuthenticated) {
        store.commit('SET_LOADING', false);
        return next({ name: 'dashboard' });
    }
    
    // If authenticated but user data not loaded yet
    if (isAuthenticated && !store.state.auth.user && token) {
        try {
            await store.dispatch('auth/fetchUser');
        } catch (error) {
            console.error('Failed to fetch user:', error);
            store.commit('auth/LOGOUT');
            store.commit('SET_LOADING', false);
            return next({ 
                name: 'login',
                query: { 
                    redirect: to.fullPath,
                    error: 'session_expired' 
                }
            });
        }
    }
    
    store.commit('SET_LOADING', false);
    next();
});

// Global after guards
router.afterEach((to, from) => {
    // Hide loading indicator
    store.commit('SET_LOADING', false);
    
    // Log navigation for analytics (optional)
    if (process.env.NODE_ENV === 'production') {
        // Add your analytics tracking here
        // analytics.logPageView(to.path);
    }
});

// Handle navigation errors
router.onError((error) => {
    console.error('Router error:', error);
    
    // Handle chunk loading errors (lazy loading)
    if (error.message.includes('Failed to fetch dynamically imported module')) {
        window.location.reload();
    }
});

export default router;