<template>
    <Page id="dashboard">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Left & Center Content -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold mb-2">Welcome {{ authStore.user?.full_name || 'User' }}</h2>
                            <p class="text-teal-100 mb-4">You have {{ state.pendingTasks }} pending task, lets see what you can do today!</p>
                            <button class="bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold px-6 py-2 rounded-lg transition-colors">
                                Check Now
                            </button>
                        </div>
                        <div class="hidden md:block">
                            <img src="/assets/images/welcome-illustration.png" alt="Welcome" class="w-48 h-48 object-contain" onerror="this.style.display='none'"/>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-500 text-sm">Total Employee</span>
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800">{{ state.stats.totalEmployee }}</div>
                    </div>

                    <div class="bg-white rounded-xl p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-500 text-sm">Present</span>
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800">{{ state.stats.present }}</div>
                    </div>

                    <div class="bg-white rounded-xl p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-500 text-sm">Late</span>
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800">{{ state.stats.late }}</div>
                    </div>

                    <div class="bg-white rounded-xl p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-500 text-sm">Annual Leave</span>
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800">{{ state.stats.annualLeave }}</div>
                    </div>
                </div>

                <!-- Task Statistics & Birthday -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Task Statistics -->
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Task Statistic</h3>
                            <button class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-center mb-6">
                            <div class="relative w-48 h-48">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="96" cy="96" r="80" stroke="#e5e7eb" stroke-width="20" fill="none"/>
                                    <circle cx="96" cy="96" r="80" :stroke-dasharray="`${state.taskPercentage * 5.02} 502`" stroke="#3b82f6" stroke-width="20" fill="none" stroke-linecap="round"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-4xl font-bold text-gray-800">{{ state.taskPercentage }}%</span>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                    <span class="text-sm text-gray-600">Inprogress</span>
                                </div>
                                <span class="text-sm font-medium text-gray-800">{{ state.tasks.inprogress }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                                    <span class="text-sm text-gray-600">Onhold</span>
                                </div>
                                <span class="text-sm font-medium text-gray-800">{{ state.tasks.onhold }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                                    <span class="text-sm text-gray-600">Completed</span>
                                </div>
                                <span class="text-sm font-medium text-gray-800">{{ state.tasks.completed }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                                    <span class="text-sm text-gray-600">Rejected</span>
                                </div>
                                <span class="text-sm font-medium text-gray-800">{{ state.tasks.rejected }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Birthday -->
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Birthday</h3>
                            <select class="text-sm border-0 text-gray-600 focus:ring-0">
                                <option>This Month</option>
                                <option>Next Month</option>
                            </select>
                        </div>
                        <div class="space-y-4">
                            <div v-for="person in state.birthdays" :key="person.id" class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <img :src="person.avatar" :alt="person.name" class="w-10 h-10 rounded-full object-cover" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\' viewBox=\'0 0 40 40\'%3E%3Crect fill=\'%23e5e7eb\' width=\'40\' height=\'40\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-family=\'sans-serif\' font-size=\'16\' fill=\'%23666\'%3E?%3C/text%3E%3C/svg%3E'"/>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ person.name }}</p>
                                        <p class="text-xs text-gray-500">{{ person.position }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block bg-teal-100 text-teal-700 text-xs px-3 py-1 rounded-full font-medium">
                                        {{ person.date }}
                                    </span>
                                    <p class="text-xs text-gray-400 mt-1">{{ person.fullDate }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payments Table -->
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Payments</h3>
                        <select class="text-sm border-0 text-gray-600 focus:ring-0">
                            <option>Monthly</option>
                            <option>Weekly</option>
                        </select>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 uppercase">No</th>
                                    <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 uppercase">ID ↕</th>
                                    <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 uppercase">Invoice Date</th>
                                    <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 uppercase">Name ↕</th>
                                    <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 uppercase">Authority</th>
                                    <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 uppercase">Amount ↕</th>
                                    <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 uppercase">Status Order ↕</th>
                                    <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="payment in state.payments" :key="payment.id" class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-4 px-2 text-sm text-gray-800">{{ payment.no }}</td>
                                    <td class="py-4 px-2 text-sm text-gray-800">#{{ payment.id }}</td>
                                    <td class="py-4 px-2 text-sm text-gray-600">{{ payment.date }}</td>
                                    <td class="py-4 px-2 text-sm text-gray-800">{{ payment.name }}</td>
                                    <td class="py-4 px-2 text-sm text-gray-600">{{ payment.authority }}</td>
                                    <td class="py-4 px-2 text-sm font-medium text-gray-800">${{ payment.amount }}</td>
                                    <td class="py-4 px-2">
                                        <span :class="payment.status === 'Processed' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700'" class="text-xs px-3 py-1 rounded-full font-medium">
                                            ● {{ payment.status }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-2">
                                        <button class="text-orange-500 hover:text-orange-600">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                <!-- Calendar -->
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">{{ state.currentMonth }}</h3>
                        <div class="flex space-x-2">
                            <button @click="previousMonth" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button @click="nextMonth" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-7 gap-2 text-center mb-2">
                        <div v-for="day in ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su']" :key="day" class="text-xs font-semibold text-gray-600">
                            {{ day }}
                        </div>
                    </div>
                    <div class="grid grid-cols-7 gap-2 text-center">
                        <div v-for="date in state.calendarDates" :key="date.key" class="aspect-square flex items-center justify-center">
                            <button v-if="date.date" :class="date.isToday ? 'bg-blue-500 text-white' : date.isCurrentMonth ? 'text-gray-800 hover:bg-gray-100' : 'text-gray-300'" class="w-full h-full rounded-lg text-sm font-medium transition-colors">
                                {{ date.date }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Upcoming Events</h3>
                    <div class="space-y-4">
                        <div v-for="event in state.events" :key="event.id" class="flex items-start space-x-3">
                            <div :class="event.color" class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-2xl">{{ event.icon }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="text-sm font-semibold text-gray-800 truncate">{{ event.title }}</h4>
                                    <button class="text-gray-400 hover:text-gray-600 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500">{{ event.category }} • {{ event.time }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Meetings -->
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Meetings</h3>
                        <button class="text-sm text-blue-600 hover:text-blue-700 font-medium">Create new</button>
                    </div>
                    <div class="space-y-3">
                        <div v-for="meeting in state.meetings" :key="meeting.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="text-center">
                                    <div class="text-xs font-semibold text-gray-600">{{ meeting.day }}</div>
                                    <div class="text-lg font-bold text-gray-800">{{ meeting.date }}</div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-800">{{ meeting.title }}</h4>
                                    <p class="text-xs text-gray-500">{{ meeting.time }}</p>
                                </div>
                            </div>
                            <button class="text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Page>
</template>

<script>
import {defineComponent, reactive, onMounted} from 'vue';
import {trans} from "@/helpers/i18n";
import {useAuthStore} from "@/stores/auth";
import Page from "@/views/layouts/Page";

export default defineComponent({
    components: {
        Page
    },
    setup() {
        const authStore = useAuthStore();

        const state = reactive({
            pendingTasks: 3,
            stats: {
                totalEmployee: 550,
                present: 525,
                late: 20,
                annualLeave: 5
            },
            taskPercentage: 70,
            tasks: {
                inprogress: 45,
                onhold: 12,
                completed: 89,
                rejected: 5
            },
            birthdays: [
                {
                    id: 1,
                    name: 'Alpha Budiman',
                    position: 'Designer',
                    date: 'Congrats 🎉',
                    fullDate: '30 Jan'
                },
                {
                    id: 2,
                    name: 'Robert Yoya',
                    position: 'Photographer',
                    date: '18/03/2022',
                    fullDate: ''
                },
                {
                    id: 3,
                    name: 'Sinab Armstrong',
                    position: 'Product Director',
                    date: '25/03/2022',
                    fullDate: ''
                },
                {
                    id: 4,
                    name: 'Deo Abraham',
                    position: 'Finance Staff',
                    date: '27/03/2022',
                    fullDate: ''
                }
            ],
            payments: [
                {
                    id: 12544,
                    no: 1,
                    date: 'Dec 1, 2021',
                    name: 'Justin Hocking',
                    authority: 'Health Insurance',
                    amount: '847.89',
                    status: 'Processed'
                },
                {
                    id: 12480,
                    no: 2,
                    date: 'Nov 15, 2021',
                    name: 'Roberto Diaz',
                    authority: 'Pension Funds',
                    amount: '477.58',
                    status: 'Pending'
                },
                {
                    id: 12306,
                    no: 3,
                    date: 'Nov 02, 2021',
                    name: 'Will John',
                    authority: 'Income Tax',
                    amount: '477.14',
                    status: 'Pending'
                }
            ],
            currentMonth: 'February 2022',
            currentDate: new Date(2022, 1, 23), // February 23, 2022
            calendarDates: [],
            events: [
                {
                    id: 1,
                    title: 'Typography in UX/UI',
                    category: 'Design',
                    time: '10:00 am',
                    icon: '🎨',
                    color: 'bg-yellow-100'
                },
                {
                    id: 2,
                    title: 'Figma UI UX Design',
                    category: 'Design',
                    time: '16:06 am',
                    icon: '🎨',
                    color: 'bg-pink-100'
                }
            ],
            meetings: [
                {
                    id: 1,
                    day: 'Mon',
                    date: '10',
                    title: 'Interview',
                    time: '9:00 am - 11:30 am'
                },
                {
                    id: 2,
                    day: 'Fri',
                    date: '08',
                    title: 'Organizational meeting',
                    time: '9:00 am - 11:30 am'
                },
                {
                    id: 3,
                    day: 'Fri',
                    date: '31',
                    title: 'Meeting with the manager',
                    time: '9:00 am - 11:30 am'
                }
            ]
        });

        function generateCalendar() {
            const year = state.currentDate.getFullYear();
            const month = state.currentDate.getMonth();
            const today = new Date();
            
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const prevLastDay = new Date(year, month, 0);
            
            const firstDayIndex = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1;
            const lastDateNum = lastDay.getDate();
            const prevLastDateNum = prevLastDay.getDate();
            
            const dates = [];
            
            // Previous month dates
            for (let i = firstDayIndex; i > 0; i--) {
                dates.push({
                    key: `prev-${i}`,
                    date: prevLastDateNum - i + 1,
                    isCurrentMonth: false,
                    isToday: false
                });
            }
            
            // Current month dates
            for (let i = 1; i <= lastDateNum; i++) {
                const isToday = today.getDate() === i && 
                               today.getMonth() === month && 
                               today.getFullYear() === year;
                dates.push({
                    key: `current-${i}`,
                    date: i,
                    isCurrentMonth: true,
                    isToday
                });
            }
            
            // Next month dates
            const remainingDays = 42 - dates.length; // 6 rows * 7 days
            for (let i = 1; i <= remainingDays; i++) {
                dates.push({
                    key: `next-${i}`,
                    date: i,
                    isCurrentMonth: false,
                    isToday: false
                });
            }
            
            state.calendarDates = dates;
            
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                              'July', 'August', 'September', 'October', 'November', 'December'];
            state.currentMonth = `${monthNames[month]} ${year}`;
        }

        function previousMonth() {
            state.currentDate = new Date(state.currentDate.getFullYear(), state.currentDate.getMonth() - 1, 1);
            generateCalendar();
        }

        function nextMonth() {
            state.currentDate = new Date(state.currentDate.getFullYear(), state.currentDate.getMonth() + 1, 1);
            generateCalendar();
        }

        onMounted(() => {
            generateCalendar();
        });

        return {
            state,
            authStore,
            trans,
            previousMonth,
            nextMonth
        }
    }
});
</script>