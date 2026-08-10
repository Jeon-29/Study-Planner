@extends('layouts.app')

@section('content')
    <div class="px-4 pt-10 pb-28 max-w-sm mx-auto relative">

        <!-- Top Header -->
        <div class="flex justify-between items-center mb-6 px-1">
            <h2 class="text-2xl font-black text-[#1C1917] tracking-tight">Schedule</h2>

            <div class="flex items-center gap-2">
                <!-- Jump to Today Floating Pill -->
                <button id="jump-today-btn" type="button" onclick="jumpToToday()"
                    class="hidden bg-[#22D3EE]/20 hover:bg-[#22D3EE]/30 border border-[#22D3EE]/50 text-[#087783] text-[11px] font-extrabold px-3 py-2 rounded-full shadow-xs transition active:scale-95 flex items-center gap-1 backdrop-blur-md">
                    <span class="material-icons-round text-xs">today</span>
                    <span>Today</span>
                </button>

                <!-- Manage Schedules Button -->
                <button type="button" onclick="openManageModal()" title="Manage Schedules"
                    class="w-10 h-10 rounded-full bg-white/40 backdrop-blur-md flex items-center justify-center border border-white/60 shadow-sm transition hover:bg-white/60 active:scale-95 text-stone-800">
                    <span class="material-icons-round text-lg">edit_calendar</span>
                </button>
            </div>
        </div>

        <!-- Liquid Glass Calendar Card -->
        <div
            class="bg-white/45 backdrop-blur-2xl border border-white/60 p-5 rounded-[32px] shadow-[0_8px_32px_0_rgba(0,0,0,0.03)] mb-8 transition-all duration-500 ease-in-out overflow-hidden">

            <!-- 1. WEEKLY VIEW -->
            <div id="weekly-view"
                class="transition-all duration-300 ease-in-out opacity-100 transform scale-100 origin-top">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-sm font-bold text-[#1C1917]">This week</h3>
                    <button type="button" onclick="toggleCalendar(true)"
                        class="text-sm font-bold text-[#1C1917] hover:opacity-75 transition flex items-center gap-1">
                        Expand
                        <span class="material-icons-round text-xs">expand_more</span>
                    </button>
                </div>

                <div class="flex justify-between text-center">
                    @foreach (Carbon\CarbonPeriod::create(now()->startOfWeek(Carbon\Carbon::SUNDAY), now()->endOfWeek(Carbon\Carbon::SATURDAY)) as $day)
                        @php
                            $dayStr = $day->format('Y-m-d');
                            $isToday = $dayStr === now()->format('Y-m-d');
                        @endphp
                        <div class="flex flex-col gap-3 items-center">
                            <span
                                class="text-[9px] font-bold text-stone-400 uppercase tracking-wider">{{ $day->format('D') }}</span>
                            <button type="button" data-date="{{ $dayStr }}"
                                onclick="selectDate('{{ $dayStr }}')"
                                class="calendar-day-btn w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold transition-all duration-300 relative {{ $isToday ? 'bg-white/80 text-[#1C1917] border border-[#22D3EE]' : 'text-[#1C1917] hover:bg-white/30' }}">
                                {{ $day->format('d') }}
                                <span id="badge-weekly-{{ $dayStr }}"
                                    class="hidden absolute -top-1.5 -right-1.5 w-4 h-4 bg-[#EF4444] text-white text-[9px] font-black rounded-full flex items-center justify-center border border-white shadow-sm"></span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 2. FULL MONTHLY GRID VIEW -->
            <div id="monthly-view"
                class="hidden transition-all duration-300 ease-in-out opacity-0 transform scale-95 origin-top">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <h3 id="calendar-month-label" class="text-sm font-bold text-[#1C1917]"></h3>
                        <div class="flex gap-1">
                            <button type="button" onclick="navigateMonth(-1)"
                                class="w-6 h-6 rounded-full bg-white/60 hover:bg-white border border-white/80 flex items-center justify-center transition active:scale-90">
                                <span class="text-[10px] text-stone-600 font-bold">&lt;</span>
                            </button>
                            <button type="button" onclick="navigateMonth(1)"
                                class="w-6 h-6 rounded-full bg-white/60 hover:bg-white border border-white/80 flex items-center justify-center transition active:scale-90">
                                <span class="text-[10px] text-stone-600 font-bold">&gt;</span>
                            </button>
                        </div>
                    </div>
                    <button type="button" onclick="toggleCalendar(false)"
                        class="text-sm font-bold text-[#1C1917] hover:opacity-75 transition flex items-center gap-1">
                        Collapse
                        <span class="material-icons-round text-xs">expand_less</span>
                    </button>
                </div>

                <div class="grid grid-cols-7 gap-y-4 text-center text-[12px] font-semibold text-[#1C1917]">
                    @foreach (['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day)
                        <span class="font-bold text-stone-400 text-[10px] mb-2">{{ $day }}</span>
                    @endforeach
                    <div id="calendar-days-grid" class="grid grid-cols-7 col-span-7 gap-y-4"></div>
                </div>
            </div>
        </div>

        <!-- Timeline & Schedule Section -->
        <div id="timeline-schedule-container" class="space-y-0 relative pl-1">
            <!-- Dynamic Event-Based Schedule Cards -->
        </div>

        <!-- ---------------- 1. SUBJECT SUMMARY POP-UP MODAL ---------------- -->
        <div id="subject-summary-modal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/0 backdrop-blur-sm hidden transition-all duration-300"
            onclick="closeSubjectSummaryModal()">
            <div class="bg-white/85 backdrop-blur-2xl border border-white/80 p-6 rounded-[32px] w-full max-w-[320px] shadow-[0_20px_50px_rgba(0,0,0,0.1)] transform scale-95 opacity-0 transition-all duration-300"
                id="summary-modal-card" onclick="event.stopPropagation()">

                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#087783]">Class
                            Summary</span>
                        <h3 id="modal-subject-title"
                            class="text-lg font-black text-stone-900 tracking-tight leading-tight mt-0.5">Subject</h3>
                    </div>
                    <button type="button" onclick="closeSubjectSummaryModal()"
                        class="w-7 h-7 rounded-full bg-white/60 border border-white/80 flex items-center justify-center text-stone-600 hover:bg-white hover:text-stone-900 transition active:scale-95">
                        <span class="material-icons-round text-sm">close</span>
                    </button>
                </div>

                <div class="grid grid-cols-3 gap-2 text-center mb-5">
                    <div class="bg-amber-100/40 border border-amber-200/50 p-2.5 rounded-2xl">
                        <span class="block text-xl font-black text-amber-600" id="todo-pending-count">0</span>
                        <span class="text-[9px] font-bold text-stone-500 uppercase">Pending</span>
                    </div>
                    <div class="bg-emerald-100/40 border border-[#AEE8EC]/50 p-2.5 rounded-2xl">
                        <span class="block text-xl font-black text-emerald-600" id="todo-done-count">0</span>
                        <span class="text-[9px] font-bold text-stone-500 uppercase">Done</span>
                    </div>
                    <div class="bg-rose-100/40 border border-rose-200/50 p-2.5 rounded-2xl">
                        <span class="block text-xl font-black text-rose-600" id="todo-overdue-count">0</span>
                        <span class="text-[9px] font-bold text-stone-500 uppercase">Overdue</span>
                    </div>
                </div>

                <div class="space-y-2 max-h-36 overflow-y-auto mb-5 pr-1 text-left" id="modal-todo-items-list"></div>

                <button type="button" onclick="closeSubjectSummaryModal()"
                    class="w-full py-3 bg-[#AEE8EC] hover:bg-[#92d6da] active:scale-[0.98] text-[#087783] text-xs font-black rounded-2xl shadow-sm transition">
                    Close
                </button>
            </div>
        </div>

        <!-- ---------------- 2. MANAGE SCHEDULES (FULL SCREEN MODAL) ---------------- -->
        <div id="manage-schedules-modal"
            class="fixed inset-0 z-50 bg-stone-100/80 backdrop-blur-3xl hidden transition-all duration-300 flex flex-col"
            onclick="closeManageModal()">

            <div class="max-w-sm w-full mx-auto h-full flex flex-col p-5 pt-8 pb-24 overflow-hidden transform scale-95 opacity-0 transition-all duration-300"
                id="manage-modal-card" onclick="event.stopPropagation()">

                <!-- Header -->
                <div class="flex justify-between items-center mb-6 pb-2 border-b border-stone-200/60">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#087783]">Management</span>
                        <h3 class="text-2xl font-black text-stone-900 tracking-tight leading-tight">All Class Schedules</h3>
                    </div>
                    <button type="button" onclick="closeManageModal()"
                        class="w-10 h-10 rounded-full bg-white/80 border border-white/90 flex items-center justify-center text-stone-700 hover:bg-white hover:text-stone-900 shadow-sm transition active:scale-95">
                        <span class="material-icons-round text-lg">close</span>
                    </button>
                </div>

                <!-- Scrollable Schedule List -->
                <div id="manage-schedules-list" class="space-y-3.5 overflow-y-auto pr-1 flex-1 text-left">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>
        </div>

        <!-- ---------------- 3. CUSTOM DELETE CONFIRMATION MODAL ---------------- -->
        <div id="delete-confirm-modal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/0 backdrop-blur-sm hidden transition-all duration-300"
            onclick="closeDeleteModal()">
            <div class="bg-white/90 backdrop-blur-2xl border border-white/80 p-6 rounded-[32px] w-full max-w-[300px] text-center shadow-[0_20px_50px_rgba(0,0,0,0.12)] transform scale-95 opacity-0 transition-all duration-300"
                id="delete-modal-card" onclick="event.stopPropagation()">

                <h3 class="text-sm font-black text-stone-900 uppercase tracking-wider mb-1">Delete Schedule?</h3>
                <p class="text-xs text-stone-500 font-medium mb-6">
                    Remove <span id="delete-schedule-title" class="font-bold text-stone-900"></span> permanently?
                </p>

                <form id="delete-schedule-form" action="" method="POST" class="grid grid-cols-2 gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="closeDeleteModal()"
                        class="w-full py-3 bg-white border border-stone-200 hover:bg-stone-50 text-stone-700 text-xs font-bold rounded-2xl transition active:scale-95 shadow-2xs">
                        Keep It
                    </button>
                    <button type="submit"
                        class="w-full py-3 bg-[#EF4444] hover:bg-rose-600 text-white text-xs font-bold rounded-2xl transition active:scale-95 shadow-sm">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- ---------------- 4. EDIT SCHEDULE FORM MODAL ---------------- -->
        <div id="add-schedule-modal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/0 backdrop-blur-sm hidden transition-all duration-300"
            onclick="closeAddScheduleModal()">
            <div class="bg-white/85 backdrop-blur-2xl border border-white/80 p-6 rounded-[32px] w-full max-w-[340px] shadow-[0_20px_50px_rgba(0,0,0,0.1)] transform scale-95 opacity-0 transition-all duration-300"
                id="add-modal-card" onclick="event.stopPropagation()">

                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#087783]">Class
                            Schedule</span>
                        <h3 class="text-lg font-black text-stone-900 tracking-tight leading-tight mt-0.5">Edit Schedule
                        </h3>
                    </div>
                    <button type="button" onclick="closeAddScheduleModal()"
                        class="w-7 h-7 rounded-full bg-white/60 border border-white/80 flex items-center justify-center text-stone-600 hover:bg-white hover:text-stone-900 transition active:scale-95">
                        <span class="material-icons-round text-sm">close</span>
                    </button>
                </div>

                <form id="schedule-form" action="" method="POST" class="space-y-3.5 text-left">
                    @csrf
                    <input type="hidden" name="_method" id="schedule-form-method" value="PUT">

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-stone-500 mb-1 ml-1">Subject</label>
                        <select name="subject_id" id="input-subject-id" required
                            class="w-full px-4 py-3 rounded-2xl bg-white/60 border border-stone-200 text-xs font-semibold text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#22D3EE] transition">
                            <option value="" disabled selected>Select Subject</option>
                            @foreach ($subjects as $subj)
                                <option value="{{ $subj->id }}">{{ $subj->name ?? ($subj->title ?? $subj->code) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-stone-500 mb-1 ml-1">Room /
                            Location</label>
                        <input type="text" name="room" id="input-room" placeholder="e.g. Lab 3, CL-1"
                            class="w-full px-4 py-3 rounded-2xl bg-white/60 border border-stone-200 text-xs font-semibold text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#22D3EE] transition">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-stone-500 mb-1 ml-1">Day of Week</label>
                        <select name="day_of_week" id="input-day-of-week" required
                            class="w-full px-4 py-3 rounded-2xl bg-white/60 border border-stone-200 text-xs font-semibold text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#22D3EE] transition">
                            <option value="Sunday">Sunday</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <!-- Class Type Dropdown -->
                        <div>
                            <label for="input-type"
                                class="block text-[10px] font-bold uppercase text-stone-500 mb-1 ml-1">Class Type</label>
                            <select name="type" id="input-type" required onchange="handleTypeChange()"
                                class="w-full px-4 py-3 rounded-2xl bg-white/60 border border-stone-200 text-xs font-semibold text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#22D3EE] transition">
                                <option value="Lecture" {{ old('type') == 'Lecture' ? 'selected' : '' }}>Lecture</option>
                                <option value="Laboratory" {{ old('type') == 'Laboratory' ? 'selected' : '' }}>Laboratory
                                </option>
                                <option value="Prelims" {{ old('type') == 'Prelims' ? 'selected' : '' }}>Prelims</option>
                                <option value="MidTerms" {{ old('type') == 'MidTerms' ? 'selected' : '' }}>MidTerms
                                </option>
                                <option value="Finals" {{ old('type') == 'Finals' ? 'selected' : '' }}>Finals</option>
                            </select>
                        </div>

                        <!-- Recurring Checkbox (Controlled by JS) -->
                        <div id="recurring-container" class="flex items-center justify-start mt-5">
                            <label class="flex items-center cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" name="is_recurring" id="input-recurring" value="1"
                                        checked class="sr-only">
                                    <div
                                        class="block w-10 h-6 bg-stone-300 rounded-full transition-colors peer-checked:bg-[#22D3EE]">
                                    </div>
                                    <div
                                        class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform">
                                    </div>
                                </div>
                                <span
                                    class="ml-2 text-[10px] font-bold uppercase text-stone-500 group-hover:text-stone-700 transition">Repeats
                                    Weekly</span>
                            </label>
                        </div>
                    </div>

                    <style>
                        /* Add this anywhere in your CSS/Style block */
                        input:checked~.dot {
                            transform: translateX(100%);
                        }

                        input:checked~.block {
                            background-color: #22D3EE;
                        }
                    </style>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-stone-500 mb-1 ml-1">Start
                                Time</label>
                            <input type="time" name="start_time" id="input-start-time" required
                                class="w-full px-4 py-3 rounded-2xl bg-white/60 border border-stone-200 text-xs font-semibold text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#22D3EE] transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-stone-500 mb-1 ml-1">End Time</label>
                            <input type="time" name="end_time" id="input-end-time" required
                                class="w-full px-4 py-3 rounded-2xl bg-white/60 border border-stone-200 text-xs font-semibold text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#22D3EE] transition">
                        </div>
                    </div>

                    <button type="submit" id="form-submit-btn"
                        class="w-full py-3 mt-1 bg-[#AEE8EC] hover:bg-[#92d6da] active:scale-[0.98] text-[#087783] text-xs font-black rounded-2xl shadow-sm transition">
                        Update Schedule
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- JavaScript Logic -->
    <script>
        const todayDate = "{{ now()->format('Y-m-d') }}";
        let selectedDate = todayDate;

        let currentYear = new Date().getFullYear();
        let currentMonth = new Date().getMonth();

        let scheduleDatabase = @json($schedules ?? []);
        let rawSchedulesList = @json($rawSchedules ?? []);
        let subjectTodoDatabase = @json($todos ?? []);

        /**
         * Dynamic Subject Style Generator
         * Note for later: When you revise your color picker choices,
         * update the 'colorMap' object right here!
         */
        function getSubjectStyle(colorVal) {
            let hex = '#6366F1';
            const colorMap = {
                'purple': '#A855F7',
                'violet': '#8B5CF6',
                'rose': '#F43F5E',
                'pink': '#EC4899',
                'blue': '#3B82F6',
                'cyan': '#06B6D4',
                'emerald': '#10B981',
                'green': '#22C55E',
                'amber': '#F59E0B',
                'red': '#EF4444',
                'indigo': '#6366F1'
            };

            if (colorVal && typeof colorVal === 'string') {
                if (colorVal.startsWith('#')) {
                    hex = colorVal;
                } else if (colorMap[colorVal.toLowerCase()]) {
                    hex = colorMap[colorVal.toLowerCase()];
                }
            }

            let cleanHex = hex.replace('#', '');
            if (cleanHex.length === 3) {
                cleanHex = cleanHex.split('').map(c => c + c).join('');
            }

            const r = parseInt(cleanHex.substring(0, 2), 16) || 99;
            const g = parseInt(cleanHex.substring(2, 4), 16) || 102;
            const b = parseInt(cleanHex.substring(4, 6), 16) || 241;

            return {
                cardStyle: `background-color: rgba(${r}, ${g}, ${b}, 0.14); border-color: rgba(${r}, ${g}, ${b}, 0.28);`,
                badgeStyle: `background-color: rgba(${r}, ${g}, ${b}, 0.2); color: rgb(${Math.max(0, r - 40)}, ${Math.max(0, g - 40)}, ${Math.max(0, b - 40)}); border-color: rgba(${r}, ${g}, ${b}, 0.35);`,
                dotStyle: `background-color: rgb(${r}, ${g}, ${b});`,
                timelineCardStyle: `background-color: rgba(${r}, ${g}, ${b}, 0.16); border-color: rgba(${r}, ${g}, ${b}, 0.3);`
            };
        }

        function getClassesForDate(dateString) {
            let combinedClasses = [];

            // 1. Fetch Recurring Classes (Weekly Schedule)
            const parts = dateString.split('-');
            if (parts.length === 3) {
                const parsedDate = new Date(parts[0], parts[1] - 1, parts[2]);
                const dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                const dayName = dayNames[parsedDate.getDay()];

                if (scheduleDatabase[dayName]) {
                    // Spread the recurring classes into our new array
                    combinedClasses = [...scheduleDatabase[dayName]];
                }
            }

            // 2. Fetch Specific Date Classes (Exams, Events)
            if (scheduleDatabase[dateString] && scheduleDatabase[dateString].length > 0) {
                // Merge the specific date classes with the existing recurring ones
                combinedClasses = [...combinedClasses, ...scheduleDatabase[dateString]];
            }

            // 3. Return the merged dataset to renderScheduleForDate
            return combinedClasses;
        }

        document.addEventListener("DOMContentLoaded", () => {
            renderMonthlyCalendar(currentYear, currentMonth);
            renderScheduleForDate(selectedDate);
            updateWeeklyBadges();
            updateJumpTodayButton();
        });

        function updateJumpTodayButton() {
            const btn = document.getElementById('jump-today-btn');
            if (!btn) return;
            if (selectedDate !== todayDate) {
                btn.classList.remove('hidden');
            } else {
                btn.classList.add('hidden');
            }
        }

        function jumpToToday() {
            selectDate(todayDate);
        }

        function toggleCalendar(expand) {
            const weeklyView = document.getElementById('weekly-view');
            const monthlyView = document.getElementById('monthly-view');

            if (expand) {
                weeklyView.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    weeklyView.classList.add('hidden');
                    monthlyView.classList.remove('hidden');
                    setTimeout(() => {
                        monthlyView.classList.remove('opacity-0', 'scale-95');
                        monthlyView.classList.add('opacity-100', 'scale-100');
                    }, 30);
                }, 200);
            } else {
                monthlyView.classList.remove('opacity-100', 'scale-100');
                monthlyView.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    monthlyView.classList.add('hidden');
                    weeklyView.classList.remove('hidden');
                    setTimeout(() => {
                        weeklyView.classList.remove('opacity-0', 'scale-95');
                        weeklyView.classList.add('opacity-100', 'scale-100');
                    }, 30);
                }, 200);
            }
        }

        function selectDate(dateString, collapseAfterSelection = false) {
            selectedDate = dateString;
            updateJumpTodayButton();

            document.querySelectorAll('.calendar-day-btn').forEach(btn => {
                const btnDate = btn.getAttribute('data-date');
                btn.className =
                    "calendar-day-btn w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold transition-all duration-300 relative text-[#1C1917] hover:bg-white/30";

                if (btnDate === todayDate) {
                    btn.className =
                        "calendar-day-btn w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold transition-all duration-300 relative bg-white/80 text-[#1C1917] border border-[#22D3EE]";
                }
            });

            document.querySelectorAll(`.calendar-day-btn[data-date="${dateString}"]`).forEach(btn => {
                btn.className =
                    "calendar-day-btn w-9 h-9 rounded-xl flex items-center justify-center text-xs font-extrabold transition-all duration-300 relative bg-[#AEE8EC] text-[#087783] shadow-sm";
            });

            renderScheduleForDate(dateString);

            if (collapseAfterSelection) {
                setTimeout(() => {
                    toggleCalendar(false);
                }, 150);
            }
        }

        function updateWeeklyBadges() {
            document.querySelectorAll('[id^="badge-weekly-"]').forEach(badge => {
                const dateStr = badge.id.replace('badge-weekly-', '');
                const dailyClasses = getClassesForDate(dateStr);
                if (dailyClasses.length > 0) {
                    badge.textContent = dailyClasses.length;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            });
        }

        /**
         * Event-Based Timeline Renderer with Start/End Anchors
         */
        function renderScheduleForDate(dateString) {
            const container = document.getElementById('timeline-schedule-container');
            const dayClasses = getClassesForDate(dateString);

            if (!dayClasses || !Array.isArray(dayClasses) || dayClasses.length === 0) {
                container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <span class="material-icons-round text-stone-300 text-4xl mb-2">event_available</span>
                <p class="text-xs font-bold text-stone-400">No classes scheduled for today</p>
            </div>
        `;
                return;
            }

            // ---------------------------------------------------------
            // NEW LOGIC: EXAM OVERRIDE FILTER
            // ---------------------------------------------------------
            const examTypes = ['Prelims', 'MidTerms', 'Finals'];

            const filteredClasses = dayClasses.filter((item, index, self) => {
                // If the current item is a standard class (NOT an exam)
                if (!examTypes.includes(item.type)) {

                    // Check the rest of the array for a conflicting exam
                    const hasExamOverride = self.some(otherItem =>
                        otherItem !== item && // Don't compare the item to itself
                        (otherItem.start_time || otherItem.start) === (item.start_time || item.start) &&
                        // Match start times
                        examTypes.includes(otherItem.type) // Is the overlapping item an exam?
                    );

                    // If an exam is found at this time, drop the standard class from this array
                    if (hasExamOverride) {
                        return false;
                    }
                }
                // Keep everything else (Exams, and regular classes with no exam conflict)
                return true;
            });

            // ---------------------------------------------------------
            // Sort classes chronologically by start_time (using filteredClasses now)
            // ---------------------------------------------------------
            const sortedClasses = [...filteredClasses].sort((a, b) => {
                const timeA = a.start_time || '00:00';
                const timeB = b.start_time || '00:00';
                return timeA.localeCompare(timeB);
            });

            let htmlContent = `<div class="space-y-4 py-1">`;
            let lastEndMins = null;

            sortedClasses.forEach((item) => {
                const startParts = (item.start_time || '00:00').split(':');
                const endParts = (item.end_time || '00:00').split(':');
                const startMins = parseInt(startParts[0], 10) * 60 + parseInt(startParts[1] || '0', 10);
                const endMins = parseInt(endParts[0], 10) * 60 + parseInt(endParts[1] || '0', 10);

                // Render Free Time Gap Pill
                if (lastEndMins !== null && startMins > lastEndMins) {
                    const diffMins = startMins - lastEndMins;
                    const hours = Math.floor(diffMins / 60);
                    const mins = diffMins % 60;

                    let gapText = "";
                    if (hours > 0) gapText += `${hours}h `;
                    if (mins > 0 || hours === 0) gapText += `${mins}m`;

                    htmlContent += `
                <div class="flex items-center gap-2 py-1 my-2">
                    <div class="w-14 text-right pr-1">
                        <span class="material-icons-round text-stone-300 text-xs">coffee</span>
                    </div>
                    <div class="flex-1 flex items-center gap-2">
                        <div class="h-px border-t border-dashed border-stone-300 flex-1"></div>
                        <span class="text-[9px] font-extrabold uppercase tracking-wider text-stone-400 bg-white/50 border border-white/80 px-2.5 py-0.5 rounded-full shadow-2xs backdrop-blur-md">
                            ${gapText} Free Time
                        </span>
                        <div class="h-px border-t border-dashed border-stone-300 flex-1"></div>
                    </div>
                </div>
            `;
                }

                const isOverlap = lastEndMins !== null && startMins < lastEndMins;
                const colorHex = item.color || item.color_theme || '#6366F1';
                const styleObj = getSubjectStyle(colorHex);

                const safeSubject = (item.subject || 'Untitled Subject')
                    .replace(/\\/g, '\\\\')
                    .replace(/'/g, "\\'")
                    .replace(/"/g, '&quot;');

                htmlContent += `
            <div class="relative flex gap-3 items-stretch group">
                <!-- Start & End Time Anchor Column -->
                <div class="w-14 shrink-0 flex flex-col justify-between items-end py-1 select-none">
                    <span class="text-[11px] font-black text-stone-800 tracking-tight leading-none">${item.start || item.start_time || '00:00'}</span>
                    <div class="w-0.5 flex-1 my-1.5 rounded-full bg-stone-300/70 group-hover:bg-[#22D3EE] transition-colors"></div>
                    <span class="text-[10px] font-bold text-stone-400 leading-none">${item.end || item.end_time || '00:00'}</span>
                </div>

                <!-- Timeline Visual Indicator -->
                <div class="relative flex flex-col items-center">
                    <div style="${styleObj.dotStyle}" class="w-3 h-3 rounded-full mt-0.5 ring-4 ring-white/80 shadow-2xs z-10 shrink-0"></div>
                    <div class="w-0.5 flex-1 bg-stone-200/80"></div>
                    <div class="w-2 h-2 rounded-full bg-stone-300/80 mb-0.5 shrink-0"></div>
                </div>

                <!-- Class Content Card -->
                <div onclick="openSubjectSummaryModal('${safeSubject}')"
                    style="${styleObj.timelineCardStyle}"
                    class="flex-1 rounded-2xl p-3.5 border shadow-2xs backdrop-blur-md flex flex-col justify-between cursor-pointer transition-all duration-200 hover:scale-[1.01] active:scale-[0.99] ${isOverlap ? 'border-amber-300/80 bg-amber-50/40' : ''}">

                    ${isOverlap ? `
                            <div class="flex items-center gap-1 mb-1.5 text-[9px] font-bold text-amber-600 uppercase tracking-wider">
                                <span class="material-icons-round text-xs">warning</span>
                                Concurrent / Overlapping Session
                            </div>
                        ` : ''}

                    <!-- New Badge for Overwritten Exam Sessions -->
                    ${examTypes.includes(item.type) ? `
                            <div class="flex items-center gap-1 mb-1.5 text-[9px] font-bold text-indigo-600 uppercase tracking-wider">
                                <span class="material-icons-round text-xs">assignment_late</span>
                                Replaces Regular Class
                            </div>
                        ` : ''}

                    <div class="flex items-start justify-between gap-2">
                        <div class="truncate">
                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm ${getTypeBadgeStyle(item.type)}">
                                ${item.type || 'Class'}
                            </span>
                            <h4 class="text-xs font-black text-stone-900 truncate tracking-tight">${item.subject || 'Untitled'}</h4>
                            <p class="text-[10px] font-bold text-stone-500 mt-0.5 flex items-center gap-1">
                                <span class="material-icons-round text-[12px] text-stone-400">place</span>
                                ${item.room || 'TBA'}
                            </p>
                        </div>
                        <span style="${styleObj.badgeStyle}" class="text-[9px] font-bold px-2 py-0.5 rounded-md uppercase border shrink-0">
                            ${item.code || 'Class'}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-[10px] font-semibold text-stone-600 mt-2.5 pt-2 border-t border-black/5">
                        <span class="flex items-center gap-1 text-stone-600">
                            <span class="material-icons-round text-xs text-[#087783]">schedule</span>
                            ${item.duration || 'N/A'}
                        </span>
                        <span class="text-[10px] font-extrabold text-[#087783] flex items-center gap-0.5">
                            Details
                            <span class="material-icons-round text-xs">chevron_right</span>
                        </span>
                    </div>
                </div>
            </div>
        `;

                if (lastEndMins === null || endMins > lastEndMins) {
                    lastEndMins = endMins;
                }
            });

            htmlContent += `</div>`;
            container.innerHTML = htmlContent;
        }

        /**
         * Render Monthly Calendar Grid
         */
        function renderMonthlyCalendar(year, month) {
            const label = document.getElementById('calendar-month-label');
            const grid = document.getElementById('calendar-days-grid');
            if (!grid) return;

            const date = new Date(year, month, 1);
            if (label) {
                label.textContent = date.toLocaleDateString('en-US', {
                    month: 'long',
                    year: 'numeric'
                });
            }

            const firstDayIndex = date.getDay();
            const lastDay = new Date(year, month + 1, 0).getDate();

            grid.innerHTML = '';

            for (let x = 0; x < firstDayIndex; x++) {
                grid.innerHTML += `<div></div>`;
            }

            for (let day = 1; day <= lastDay; day++) {
                const monthStr = String(month + 1).padStart(2, '0');
                const dayStr = String(day).padStart(2, '0');
                const fullDate = `${year}-${monthStr}-${dayStr}`;

                const isToday = fullDate === todayDate;
                const isSelected = fullDate === selectedDate;
                const classes = getClassesForDate(fullDate);

                let btnClass =
                    "w-8 h-8 rounded-xl mx-auto flex items-center justify-center font-bold text-xs transition relative text-stone-700 hover:bg-white/40";

                if (isSelected) {
                    btnClass =
                        "w-8 h-8 rounded-xl mx-auto flex items-center justify-center font-extrabold text-xs transition relative bg-[#AEE8EC] text-[#087783] shadow-sm";
                } else if (isToday) {
                    btnClass =
                        "w-8 h-8 rounded-xl mx-auto flex items-center justify-center font-bold text-xs transition relative bg-white/80 text-[#1C1917] border border-[#22D3EE]";
                }

                grid.innerHTML += `
                    <button type="button" data-date="${fullDate}" onclick="selectDate('${fullDate}', true)" class="${btnClass}">
                        ${day}
                        ${classes.length > 0 ? `<span class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-[#EF4444]"></span>` : ''}
                    </button>
                `;
            }
        }

        function navigateMonth(direction) {
            currentMonth += direction;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            } else if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderMonthlyCalendar(currentYear, currentMonth);
        }

        /**
         * 1. Subject Summary Modal Handler
         */
        function openSubjectSummaryModal(subjectName) {
            const modal = document.getElementById('subject-summary-modal');
            const card = document.getElementById('summary-modal-card');
            const title = document.getElementById('modal-subject-title');
            const pendingCount = document.getElementById('todo-pending-count');
            const doneCount = document.getElementById('todo-done-count');
            const overdueCount = document.getElementById('todo-overdue-count');
            const itemsList = document.getElementById('modal-todo-items-list');

            if (title) title.textContent = subjectName;

            const todoData = subjectTodoDatabase[subjectName] || {
                pending: 0,
                done: 0,
                overdue: 0,
                tasks: []
            };

            if (pendingCount) pendingCount.textContent = todoData.pending || 0;
            if (doneCount) doneCount.textContent = todoData.done || 0;
            if (overdueCount) overdueCount.textContent = todoData.overdue || 0;

            if (itemsList) {
                itemsList.innerHTML = '';
                if (todoData.tasks && todoData.tasks.length > 0) {
                    todoData.tasks.forEach(task => {
                        itemsList.innerHTML += `
                            <div class="p-2.5 rounded-xl bg-white/60 border border-stone-200/60 text-xs font-semibold text-stone-800 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>
                                <span class="truncate">${task}</span>
                            </div>
                        `;
                    });
                } else {
                    itemsList.innerHTML =
                        `<p class="text-xs text-stone-400 font-medium text-center py-2">No pending assignments</p>`;
                }
            }

            if (modal && card) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    card.classList.remove('scale-95', 'opacity-0');
                    card.classList.add('scale-100', 'opacity-100');
                }, 10);
            }
        }

        function closeSubjectSummaryModal() {
            const modal = document.getElementById('subject-summary-modal');
            const card = document.getElementById('summary-modal-card');
            if (card) {
                card.classList.remove('scale-100', 'opacity-100');
                card.classList.add('scale-95', 'opacity-0');
            }
            setTimeout(() => {
                if (modal) modal.classList.add('hidden');
            }, 300);
        }

        /**
         * 2. Manage Schedules Full-Screen Modal Handler
         */
        function openManageModal() {
            const modal = document.getElementById('manage-schedules-modal');
            const card = document.getElementById('manage-modal-card');
            const listContainer = document.getElementById('manage-schedules-list');

            if (!listContainer) return;

            listContainer.innerHTML = '';

            if (!rawSchedulesList || rawSchedulesList.length === 0) {
                listContainer.innerHTML =
                    `<p class="text-xs text-stone-500 text-center py-6 font-medium">No class schedules found.</p>`;
            } else {
                rawSchedulesList.forEach(item => {
                    const subjName = item.subject ? (item.subject.name || item.subject.code) : 'Subject';

                    const colorHex = item.subject?.color_theme || item.color_theme || item.color || '#6366F1';
                    const styleObj = getSubjectStyle(colorHex);

                    listContainer.innerHTML += `
                        <div style="${styleObj.cardStyle}" class="p-4 rounded-2xl border shadow-2xs flex items-center justify-between gap-3 transition-all duration-200">
                            <div class="truncate flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span style="${styleObj.dotStyle}" class="w-2.5 h-2.5 rounded-full shrink-0 shadow-2xs"></span>
                                    <h4 class="text-xs font-black tracking-tight truncate text-stone-900">${subjName}</h4>
                                </div>
                                <div class="flex items-center gap-2 text-[10px] font-semibold text-stone-600 pl-4">
                                    <span style="${styleObj.badgeStyle}" class="px-2 py-0.5 rounded-md border font-bold uppercase">${item.day_of_week}</span>
                                    <span>${item.start_time} - ${item.end_time}</span>
                                    <span>• ${item.room || 'TBA'}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <button type="button" onclick="editScheduleItem(${item.id})"
                                    class="w-8 h-8 rounded-xl bg-white/80 hover:bg-white border border-black/5 text-amber-700 flex items-center justify-center shadow-2xs transition active:scale-95">
                                    <span class="material-icons-round text-sm">edit</span>
                                </button>
                                <button type="button" onclick="confirmDeleteSchedule(${item.id}, '${subjName.replace(/'/g, "\\'")}')"
                                    class="w-8 h-8 rounded-xl bg-white/80 hover:bg-white border border-black/5 text-rose-700 flex items-center justify-center shadow-2xs transition active:scale-95">
                                    <span class="material-icons-round text-sm">delete</span>
                                </button>
                            </div>
                        </div>
                    `;
                });
            }

            if (modal && card) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    card.classList.remove('scale-95', 'opacity-0');
                    card.classList.add('scale-100', 'opacity-100');
                }, 10);
            }
        }

        function closeManageModal() {
            const modal = document.getElementById('manage-schedules-modal');
            const card = document.getElementById('manage-modal-card');
            if (card) {
                card.classList.remove('scale-100', 'opacity-100');
                card.classList.add('scale-95', 'opacity-0');
            }
            setTimeout(() => {
                if (modal) modal.classList.add('hidden');
            }, 300);
        }

        /**
         * 3. Edit Schedule Form Modal Handler
         */
        function editScheduleItem(id) {
            const item = rawSchedulesList.find(s => s.id === id);
            if (!item) return;

            const form = document.getElementById('schedule-form');
            const methodInput = document.getElementById('schedule-form-method');
            const subjectSelect = document.getElementById('input-subject-id');
            const roomInput = document.getElementById('input-room');
            const daySelect = document.getElementById('input-day-of-week');
            const startTimeInput = document.getElementById('input-start-time');
            const endTimeInput = document.getElementById('input-end-time');

            if (form) form.action = `/schedule/${id}`;
            if (methodInput) methodInput.value = 'PUT';

            if (subjectSelect) subjectSelect.value = item.subject_id;
            if (roomInput) roomInput.value = item.room || '';
            if (daySelect) daySelect.value = item.day_of_week;
            if (startTimeInput) startTimeInput.value = item.start_time;
            if (endTimeInput) endTimeInput.value = item.end_time;

            // This ensures the correct Class Type loads when editing,
            // and safely toggles the repeating checkbox!
            const typeSelect = document.getElementById('input-type');
            if (typeSelect) {
                typeSelect.value = item.type || 'Lecture';
                handleTypeChange();
            }

            const modal = document.getElementById('add-schedule-modal');
            const card = document.getElementById('add-modal-card');

            if (modal && card) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    card.classList.remove('scale-95', 'opacity-0');
                    card.classList.add('scale-100', 'opacity-100');
                }, 10);
            }
        }

        /**
         * Dynamic Class Type Badge Style Generator
         */
        function getTypeBadgeStyle(type) {
            switch (type) {
                case 'Lecture':
                    return 'bg-blue-100/80 text-blue-700 border border-blue-200/50';
                case 'Laboratory':
                    return 'bg-emerald-100/80 text-emerald-700 border border-emerald-200/50';
                case 'Prelims':
                case 'MidTerms':
                case 'Finals':
                    return 'bg-rose-100/80 text-rose-700 border border-rose-200/50';
                default:
                    return 'bg-stone-100/80 text-stone-700 border border-stone-200/50';
            }
        }

        /**
         * Handles dropdown changes to disable recurring for exams
         */
        function handleTypeChange() {
            const typeSelect = document.getElementById('input-type');
            const recurringContainer = document.getElementById('recurring-container');
            const recurringCheckbox = document.getElementById('input-recurring');

            const oneTimeTypes = ['Prelims', 'MidTerms', 'Finals'];

            if (oneTimeTypes.includes(typeSelect.value)) {
                recurringCheckbox.checked = false;
                recurringContainer.style.display = 'none';
            } else {
                recurringCheckbox.checked = true;
                recurringContainer.style.display = 'flex';
            }
        }

        function closeAddScheduleModal() {
            const modal = document.getElementById('add-schedule-modal');
            const card = document.getElementById('add-modal-card');
            if (card) {
                card.classList.remove('scale-100', 'opacity-100');
                card.classList.add('scale-95', 'opacity-0');
            }
            setTimeout(() => {
                if (modal) modal.classList.add('hidden');
            }, 300);
        }

        /**
         * 4. Custom Delete Confirmation Modal Handler
         */
        function confirmDeleteSchedule(id, subjectTitle) {
            const modal = document.getElementById('delete-confirm-modal');
            const card = document.getElementById('delete-modal-card');
            const titleElem = document.getElementById('delete-schedule-title');
            const form = document.getElementById('delete-schedule-form');

            if (titleElem) titleElem.textContent = subjectTitle;
            if (form) form.action = `/schedule/${id}`;

            if (modal && card) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    card.classList.remove('scale-95', 'opacity-0');
                    card.classList.add('scale-100', 'opacity-100');
                }, 10);
            }
        }

        function closeDeleteModal() {
            const modal = document.getElementById('delete-confirm-modal');
            const card = document.getElementById('delete-modal-card');
            if (card) {
                card.classList.remove('scale-100', 'opacity-100');
                card.classList.add('scale-95', 'opacity-0');
            }
            setTimeout(() => {
                if (modal) modal.classList.add('hidden');
            }, 300);
        }
    </script>
@endsection
