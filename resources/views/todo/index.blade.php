@extends('layouts.app')

@section('title', 'Tasks Manager')

@section('content')

    <div class="px-4 pt-8 pb-32 max-w-md mx-auto relative">

        <div class="flex justify-between items-center mb-5 px-1">
            <div>
                <h2 class="text-2xl font-black text-[#1C1917] tracking-tight">Task Hub</h2>
                <p class="text-xs text-stone-500 font-medium">Manage your assignments & exams</p>
            </div>
        </div>

        <!-- MAIN TASK HUB SWITCHER (Default: To-Do) -->
        <div class="flex p-1.5 bg-stone-100/80 rounded-full mb-6 border border-stone-200/50">
            <button id="hub-tab-todo" onclick="switchHubTab('todo')"
                class="flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 bg-white text-stone-800 shadow-xs">
                To-Do Lists
            </button>
            <button id="hub-tab-assessment" onclick="switchHubTab('assessment')"
                class="flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 text-stone-400 hover:text-stone-600">
                Quizzes & Exams
            </button>
        </div>

        <!-- TO-DO SECTION (DEFAULT VIEW) -->
        <div id="hub-section-todo" class="space-y-4">
            <!-- 1. MINIMALIST PAGE HEADER -->
            <div class="flex items-center justify-between mb-6 p-1">
                <div>
                    <h1 class="text-lg font-bold text-[#1C1917] tracking-tight">To-Do List</h1>
                    <p class="text-xs font-medium text-[#78716C] mt-0.5">Manage and track your coursework</p>
                </div>
            </div>

            <!-- 2. QUICK TASK AGGREGATE SUMMARY STRIP (FILTERABLE TABS) -->
            <div class="mb-6 grid grid-cols-3 gap-2.5">
                <button onclick="filterTasks('pending', this)" id="pill-pending"
                    class="stat-pill bg-amber-500/10 border-2 border-amber-500/30 p-2.5 rounded-full text-center transition-all transform active:scale-95 focus:outline-none ring-2 ring-amber-500/20">
                    <span class="block text-xs font-extrabold text-amber-700">{{ $pendingTodos->count() }}</span>
                    <span class="block text-[9px] font-bold uppercase tracking-wider text-amber-600 mt-0.5">Pending</span>
                </button>
                <button onclick="filterTasks('done', this)" id="pill-done"
                    class="stat-pill bg-stone-100/50 border border-stone-200 p-2.5 rounded-full text-center transition-all transform active:scale-95 focus:outline-none">
                    <span class="block text-xs font-extrabold text-stone-700">{{ $doneTodos->count() }}</span>
                    <span class="block text-[9px] font-bold uppercase tracking-wider text-stone-600 mt-0.5">Done</span>
                </button>
                <button onclick="filterTasks('overdue', this)" id="pill-overdue"
                    class="stat-pill bg-stone-100/50 border border-stone-200 p-2.5 rounded-full text-center transition-all transform active:scale-95 focus:outline-none">
                    <span class="block text-xs font-extrabold text-rose-700">{{ $overdueTodos->count() }}</span>
                    <span class="block text-[9px] font-bold uppercase tracking-wider text-rose-600 mt-0.5">Overdue</span>
                </button>
            </div>

            <!-- 3. MAIN TASK LISTS -->
            <div class="space-y-6">

                <!-- PENDING & OVERDUE CONTAINER GROUP -->
                <div id="active-tasks-section" class="space-y-3">
                    <p id="section-title" class="text-[10px] font-bold text-[#78716C] uppercase tracking-widest px-1">In
                        Progress /
                        Pending</p>

                    @php
                        $allActiveTodos = $pendingTodos->merge($overdueTodos);

                        $colorMap = [
                            'slate' => '#475569',
                            'blue' => '#2563eb',
                            'indigo' => '#4f46e5',
                            'purple' => '#7c3aed',
                            'pink' => '#db2777',
                            'rose' => '#e11d48',
                            'red' => '#dc2626',
                            'amber' => '#d97706',
                            'emerald' => '#059669',
                            'teal' => '#0d9488',
                        ];
                    @endphp

                    @forelse($allActiveTodos as $todo)
                        @php
                            $dueDate = \Carbon\Carbon::parse($todo->due_date);
                            $isToday = $dueDate->isToday();
                            $isOverdue = $dueDate->isPast() && !$isToday;
                            $isDueSoon = !$isToday && !$isOverdue && $dueDate->diffInDays(now()) <= 2;
                            $statusType = $isOverdue ? 'overdue' : 'pending';

                            // Match the parent theme color dynamically
                            $theme = $subjectColors[$todo->subject] ?? 'blue';
                            $baseHex = $colorMap[$theme] ?? '#2563eb';
                        @endphp

                        <!-- LIQUID GLASS TASK CARD WITH INHERITED COLOR -->
                        <div data-status="{{ $statusType }}"
                            style="background: linear-gradient(135deg, {{ $baseHex }}c0, {{ $baseHex }}90); border: 1px solid rgba(255, 255, 255, 0.4); box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.4), 0 4px 12px rgba(0, 0, 0, 0.03);"
                            class="todo-card relative backdrop-blur-md rounded-[24px] py-4 pl-4 pr-3 flex items-center justify-between gap-3 shadow-sm transition-all duration-150 transform hover:-translate-y-0.5">

                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                <!-- Custom Translucent Button Context -->
                                <button
                                    onclick="openCompleteConfirmModal({{ $todo->id }}, '{{ addslashes($todo->title) }}')"
                                    class="w-7 h-7 rounded-full border-2 border-white/80 bg-white/20 flex items-center justify-center shrink-0 mt-0.5 transition-transform active:scale-90 hover:bg-white/40">
                                    <div class="w-3 h-3 rounded-full bg-transparent hover:bg-white transition-colors"></div>
                                </button>

                                <div class="space-y-1 flex-1 min-w-0 pr-1">
                                    <h4 class="text-sm font-extrabold text-white tracking-tight block truncate text-shadow-sm">
                                        {{ $todo->title }}
                                    </h4>

                                    <div class="flex items-center space-x-2 text-xs font-bold">
                                        <span
                                            class="text-white/90 font-mono tracking-wide uppercase text-[10px] bg-white/20 px-2 py-0.5 rounded-md">{{ $todo->subject }}</span>
                                        @if ($todo->priority)
                                            <span class="text-white/40 font-normal">•</span>
                                            @php
                                                $priorityColors = [
                                                    'high' => 'bg-rose-500/80 text-white border border-rose-400/50',
                                                    'medium' => 'bg-amber-500/80 text-white border border-amber-400/50',
                                                    'low' => 'bg-emerald-500/80 text-white border border-emerald-400/50',
                                                ];
                                                $priorityClass =
                                                    $priorityColors[strtolower($todo->priority)] ??
                                                    'bg-white/25 text-white';
                                            @endphp
                                            <span
                                                class="text-[9px] uppercase px-2 py-0.5 rounded-full font-extrabold {{ $priorityClass }} shadow-sm">
                                                {{ $todo->priority }}
                                            </span>
                                        @endif
                                    </div>

                                    @if ($todo->description)
                                        <p class="text-[11px] text-white/85 font-medium leading-relaxed line-clamp-2 pt-0.5">
                                            {{ $todo->description }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center space-x-2 shrink-0">
                                <div class="flex flex-col items-center space-y-1">
                                    @if ($isOverdue)
                                        <span
                                            class="text-[8px] font-black uppercase px-1.5 py-0.5 bg-rose-600 text-white rounded-md tracking-wider shadow-sm">Overdue</span>
                                    @elseif($isToday)
                                        <span
                                            class="text-[8px] font-black uppercase px-1.5 py-0.5 bg-amber-500 text-white rounded-md tracking-wider shadow-sm">Due
                                            Today</span>
                                    @elseif($isDueSoon)
                                        <span
                                            class="text-[8px] font-black uppercase px-1.5 py-0.5 bg-purple-600 text-white rounded-md tracking-wider shadow-sm">Due
                                            Soon</span>
                                    @endif

                                    <div
                                        class="w-12 h-12 rounded-2xl bg-white/20 border border-white/30 overflow-hidden flex flex-col items-center text-center shadow-inner">
                                        <div
                                            class="w-full text-[9px] font-extrabold text-white uppercase py-0.5 tracking-wider {{ $isToday ? 'bg-rose-500/80' : ($isOverdue ? 'bg-stone-700/80' : 'bg-black/20') }}">
                                            {{ $isToday ? 'Today' : $dueDate->format('M') }}
                                        </div>
                                        <div
                                            class="flex-1 flex items-center justify-center text-sm font-black text-white leading-none pb-0.5">
                                            {{ $dueDate->format('d') }}
                                        </div>
                                    </div>

                                    @if ($todo->due_time)
                                        <span
                                            class="text-[9px] font-bold text-white bg-white/15 px-1.5 py-0.5 rounded-md border border-white/10">
                                            {{ \Carbon\Carbon::parse($todo->due_time)->format('g:i A') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="relative">
                                    <button onclick="toggleCardDropdown(event, 'dropdown-{{ $todo->id }}')"
                                        class="w-7 h-7 rounded-full bg-white/15 border border-white/20 flex items-center justify-center text-white/80 hover:text-white hover:bg-white/30 transition-all focus:outline-none">
                                        <span class="material-icons-round text-sm">more_horiz</span>
                                    </button>

                                    <div id="dropdown-{{ $todo->id }}"
                                        class="todo-card-dropdown hidden absolute right-0 mt-1 w-24 bg-white/95 backdrop-blur-md border border-stone-200/80 rounded-2xl shadow-lg py-1.5 z-10">
                                        <button onclick="openEditModal({{ json_encode($todo) }})"
                                            class="w-full text-left px-3 py-1 text-[11px] font-bold text-stone-700 hover:bg-stone-100 hover:text-blue-600 flex items-center space-x-1.5">
                                            <span class="material-icons-round text-xs">edit</span>
                                            <span>Edit</span>
                                        </button>
                                        <button
                                            onclick="openDeleteConfirmModal({{ $todo->id }}, '{{ addslashes($todo->title) }}')"
                                            class="w-full text-left px-3 py-1 text-[11px] font-bold text-rose-600 hover:bg-rose-50 flex items-center space-x-1.5">
                                            <span class="material-icons-round text-xs">delete</span>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div id="active-empty-state"
                            class="p-8 text-center border border-dashed border-stone-200 bg-stone-50/40 rounded-3xl">
                            <span class="material-icons-round text-xl text-stone-300">assignment</span>
                            <p class="text-[11px] text-[#78716C] font-medium mt-1">No active tasks found!</p>
                        </div>
                    @endforelse

                    <div id="filtered-empty-state"
                        class="hidden p-8 text-center border border-dashed border-stone-200 bg-stone-50/40 rounded-3xl">
                        <span class="material-icons-round text-xl text-stone-300">filter_list</span>
                        <p class="text-[11px] text-[#78716C] font-medium mt-1">No tasks match this filter category.</p>
                    </div>
                </div>

                <!-- 4. COMPLETED TASKS ACCORDION -->
                <div id="completed-section-wrapper" class="pt-2 border-t border-stone-200/60">
                    <button onclick="toggleCompletedAccordion()"
                        class="w-full flex items-center justify-between py-2 px-1 text-stone-500 hover:text-stone-800 transition-colors focus:outline-none">
                        <div class="flex items-center space-x-2">
                            <span class="material-icons-round text-sm">check_circle</span>
                            <span class="text-[10px] font-bold uppercase tracking-widest">Completed To-Dos
                                ({{ $doneTodos->count() }})</span>
                        </div>
                        <span id="accordion-arrow"
                            class="material-icons-round text-base transition-transform duration-200 rotate-180">expand_more</span>
                    </button>

                    <div id="completed-container" class="mt-3 space-y-3 transition-all duration-300">
                        @forelse($doneTodos as $todo)
                            @php
                                $theme = $subjectColors[$todo->subject] ?? 'slate';
                                $baseHex = $colorMap[$theme] ?? '#475569';
                            @endphp
                            <!-- Muted Translucent Completed Cards -->
                            <div style="background: linear-gradient(135deg, {{ $baseHex }}25, {{ $baseHex }}15); border: 1px solid rgba(0,0,0,0.05);"
                                class="rounded-[20px] py-3 px-4 flex items-center justify-between opacity-80">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <div style="color: {{ $baseHex }}; border-color: {{ $baseHex }}60; background-color: {{ $baseHex }}15;"
                                        class="w-5 h-5 rounded-full border flex items-center justify-center shrink-0">
                                        <span class="material-icons-round text-xs">check</span>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-bold text-stone-700 line-through truncate">{{ $todo->title }}
                                        </h4>
                                        <span style="color: {{ $baseHex }};"
                                            class="text-[9px] font-mono font-extrabold uppercase tracking-wide">{{ $todo->subject }}</span>
                                    </div>
                                </div>

                                <button
                                    onclick="openDeleteConfirmModal({{ $todo->id }}, '{{ addslashes($todo->title) }}')"
                                    class="w-6 h-6 rounded-full hover:bg-rose-50 text-stone-400 hover:text-rose-600 flex items-center justify-center transition-colors">
                                    <span class="material-icons-round text-xs">delete</span>
                                </button>
                            </div>
                        @empty
                            <p id="completed-empty-text" class="text-[11px] text-stone-400 italic font-medium px-1 pt-1">No
                                completed tasks recorded yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- MODAL INSERTS (COMPLETE, EDIT, DELETE) -->
            <div id="complete-confirm-modal"
                class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/20 backdrop-blur-sm">
                <div
                    class="bg-white/80 backdrop-blur-xl w-full max-w-xs rounded-3xl border border-white/60 p-5 text-center shadow-xl">
                    <h3 class="text-xs font-bold text-stone-900 uppercase tracking-wider">Complete Task?</h3>
                    <p class="text-[10px] text-stone-500 mt-1 font-medium"><span id="complete-task-title"
                            class="font-bold text-stone-800"></span></p>
                    <form id="complete-todo-form" method="POST" class="mt-4 flex items-center justify-center space-x-2.5">
                        @csrf @method('PATCH')
                        <button type="button" onclick="closeCompleteConfirmModal()"
                            class="w-1/2 py-2 text-[10px] font-bold text-stone-500 bg-stone-50 border border-stone-200 rounded-full">Not
                            Yet</button>
                        <button type="submit"
                            class="w-1/2 py-2 text-[10px] font-bold text-white bg-blue-600 rounded-full">Complete</button>
                    </form>
                </div>
            </div>

            <div id="delete-confirm-modal"
                class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/20 backdrop-blur-sm">
                <div
                    class="bg-white/80 backdrop-blur-xl w-full max-w-xs rounded-3xl border border-white/60 p-5 text-center shadow-xl">
                    <h3 class="text-xs font-bold text-stone-900 uppercase tracking-wider text-rose-600">Delete To-Do?</h3>
                    <p class="text-[10px] text-stone-500 mt-1 font-medium">Remove <span id="delete-task-title"
                            class="font-bold text-stone-800"></span> permanently?</p>
                    <form id="delete-todo-form" method="POST" class="mt-4 flex items-center justify-center space-x-2.5">
                        @csrf @method('DELETE')
                        <button type="button" onclick="closeDeleteConfirmModal()"
                            class="w-1/2 py-2 text-[10px] font-bold text-stone-500 bg-stone-50 border border-stone-200 rounded-full">Keep
                            It</button>
                        <button type="submit"
                            class="w-1/2 py-2 text-[10px] font-bold text-white bg-rose-600 rounded-full">Delete</button>
                    </form>
                </div>
            </div>

            <!-- OVERLAY B: UPGRADED LIQUID GLASS EDIT MODAL -->
            <div id="edit-todo-modal"
                class="hidden fixed inset-0 z-50 flex items-end justify-center pb-24 px-4 transition-all duration-300 opacity-0">
                <div class="absolute inset-0 bg-[#1C1917]/10 backdrop-blur-md" onclick="closeEditModal()"></div>

                <div class="relative w-full max-w-sm bg-white/85 backdrop-blur-2xl rounded-[28px] p-5 border border-white/90 shadow-[0_24px_60px_rgba(0,0,0,0.12)] transform translate-y-8 transition-all duration-300 z-10"
                    id="edit-todo-modal-card">

                    <div class="flex items-center justify-between mb-4 px-0.5">
                        <div>
                            <h3 class="text-sm font-bold text-[#1C1917] tracking-tight">Edit To-Do</h3>
                            <p class="text-[10px] text-[#78716C] font-medium mt-0.5">Modify your task specifications</p>
                        </div>
                        <button type="button" onclick="closeEditModal()"
                            class="w-7 h-7 rounded-full bg-stone-100 hover:bg-stone-200/70 flex items-center justify-center text-stone-500 transition">
                            <span class="material-icons-round text-base">close</span>
                        </button>
                    </div>

                    <form id="edit-todo-form" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label
                                class="block text-[10px] font-bold text-[#78716C] uppercase tracking-wider mb-1 px-1">Title</label>
                            <input type="text" id="edit-title" name="title" required
                                class="w-full h-10 px-3.5 bg-stone-50/50 border border-stone-200/80 rounded-xl text-xs font-medium focus:outline-none focus:border-[#DB2777] focus:bg-white transition-all">
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-bold text-[#78716C] uppercase tracking-wider mb-1 px-1">Subject</label>
                            <div class="relative">
                                <select id="edit-subject" name="subject" required
                                    class="w-full h-10 px-3.5 bg-stone-50/50 border border-stone-200/80 rounded-xl text-xs font-medium text-stone-700 appearance-none focus:outline-none focus:border-[#DB2777] focus:bg-white transition-all">
                                    @foreach ($subjectColors as $code => $color)
                                        <option value="{{ $code }}">{{ $code }}</option>
                                    @endforeach
                                </select>
                                <span
                                    class="material-icons-round text-base text-stone-400 absolute right-3.5 top-2.5 pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-bold text-[#78716C] uppercase tracking-wider mb-1 px-1">Description</label>
                            <textarea id="edit-description" name="description" rows="2"
                                class="w-full p-3 bg-stone-50/50 border border-stone-200/80 rounded-xl text-xs font-medium focus:outline-none focus:border-[#DB2777] focus:bg-white transition-all resize-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-[#78716C] uppercase tracking-wider mb-1 px-1">Due
                                Date &
                                time</label>
                            <div class="grid grid-cols-2 gap-2.5">
                                <div class="relative flex items-center">
                                    <input type="date" id="edit-due-date" name="due_date" required
                                        class="w-full h-11 pl-9 pr-3 bg-white border border-stone-200 rounded-full text-xs font-semibold text-stone-700 shadow-sm focus:outline-none focus:border-[#DB2777] transition-all">
                                    <span
                                        class="material-icons-round text-base text-stone-500 absolute left-3.5 pointer-events-none">calendar_today</span>
                                </div>
                                <div class="relative flex items-center">
                                    <input type="time" id="edit-due-time" name="due_time" required
                                        class="w-full h-11 pl-9 pr-3 bg-white border border-stone-200 rounded-full text-xs font-semibold text-stone-700 shadow-sm focus:outline-none focus:border-[#DB2777] transition-all">
                                    <span
                                        class="material-icons-round text-base text-stone-500 absolute left-3.5 pointer-events-none">access_time</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-bold text-[#78716C] uppercase tracking-wider mb-1.5 px-1">Priority</label>
                            <div class="grid grid-cols-3 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="priority" id="edit-priority-low" value="low"
                                        class="sr-only peer">
                                    <div
                                        class="h-10 rounded-full border text-xs font-bold flex items-center justify-center transition-all bg-white border-stone-200 text-stone-400 peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 shadow-sm">
                                        Low</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="priority" id="edit-priority-medium" value="medium"
                                        class="sr-only peer">
                                    <div
                                        class="h-10 rounded-full border text-xs font-bold flex items-center justify-center transition-all bg-white border-stone-200 text-stone-400 peer-checked:bg-amber-50 peer-checked:border-amber-500 peer-checked:text-amber-600 shadow-sm">
                                        Medium</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="priority" id="edit-priority-high" value="high"
                                        class="sr-only peer">
                                    <div
                                        class="h-10 rounded-full border text-xs font-bold flex items-center justify-center transition-all bg-white border-stone-200 text-stone-400 peer-checked:bg-rose-50 peer-checked:border-rose-400 peer-checked:text-rose-600 shadow-sm">
                                        High</div>
                                </label>
                            </div>
                        </div>

                        <div class="pt-2 flex items-center space-x-2">
                            <button type="button" onclick="closeEditModal()"
                                class="flex-1 h-10 rounded-xl border border-stone-200 text-xs font-bold text-stone-600 hover:bg-stone-50 transition active:scale-98">Cancel</button>
                            <button type="submit"
                                class="flex-1 h-10 rounded-xl bg-[#1C1917] hover:bg-[#2E2925] text-xs font-bold text-white shadow-md border border-stone-800 transition active:scale-98">Save
                                Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ASSESSMENTS SECTION (HIDDEN BY DEFAULT) -->
        <div id="hub-section-assessment" class="space-y-4 hidden">
            <div class="px-4 pt-8 pb-32 max-w-md mx-auto relative">

                <div class="flex justify-between items-center mb-5 px-1">
                    <div>
                        <h2 class="text-2xl font-black text-[#1C1917] tracking-tight">Assessments</h2>
                        <p class="text-xs text-stone-500 font-medium">Track your Quizzes & Exams</p>
                    </div>

                    <button type="button" onclick="openAssessmentModal()"
                        class="bg-[#DB2777] hover:bg-[#BE185D] text-white text-xs font-bold px-4 py-2.5 rounded-full shadow-lg shadow-pink-200 transition-all active:scale-95 flex items-center gap-1.5">
                        <span class="material-icons-round text-sm">add</span>
                        <span>Add New</span>
                    </button>
                </div>

                <!-- COMPACT STAT CARDS -->
                <div class="grid grid-cols-2 gap-3 mb-5">
                    <div class="relative p-4 rounded-[24px] bg-white border border-stone-100 shadow-sm overflow-hidden group">
                        <div
                            class="absolute -right-4 -top-4 w-14 h-14 bg-amber-100/80 rounded-full blur-xl group-hover:bg-amber-200 transition-colors">
                        </div>
                        <div class="relative z-10 flex flex-col">
                            <div
                                class="w-8 h-8 rounded-xl bg-amber-50 border border-amber-200/60 flex items-center justify-center text-amber-600 mb-2">
                                <span class="material-icons-round text-base">quiz</span>
                            </div>
                            <span class="text-3xl font-black text-stone-800">{{ $todayQuizzes }}</span>
                            <span class="text-[11px] font-bold text-stone-400 mt-0.5">Quizzes Today</span>
                        </div>
                    </div>

                    <div class="relative p-4 rounded-[24px] bg-white border border-stone-100 shadow-sm overflow-hidden group">
                        <div
                            class="absolute -right-4 -top-4 w-14 h-14 bg-purple-100/80 rounded-full blur-xl group-hover:bg-purple-200 transition-colors">
                        </div>
                        <div class="relative z-10 flex flex-col">
                            <div
                                class="w-8 h-8 rounded-xl bg-purple-50 border border-purple-200/60 flex items-center justify-center text-purple-600 mb-2">
                                <span class="material-icons-round text-base">school</span>
                            </div>
                            <span class="text-3xl font-black text-stone-800">{{ $todayExams }}</span>
                            <span class="text-[11px] font-bold text-stone-400 mt-0.5">Exams Today</span>
                        </div>
                    </div>
                </div>

                <div class="flex p-1.5 bg-stone-100/80 rounded-full mb-6 border border-stone-200/50">
                    <button id="main-tab-quiz" onclick="switchMainTab('quiz')"
                        class="flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 bg-white text-stone-800 shadow-xs">
                        Quizzes
                    </button>
                    <button id="main-tab-exam" onclick="switchMainTab('exam')"
                        class="flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 text-stone-400 hover:text-stone-600">
                        Exams
                    </button>
                </div>

                <div id="section-quiz" class="space-y-4">
                    <div class="flex items-center space-x-4 border-b border-stone-200 pb-2 px-1">
                        <button onclick="switchSubTab('quiz', 'upcoming')" id="subtab-quiz-upcoming"
                            class="text-xs font-bold text-[#DB2777] border-b-2 border-[#DB2777] pb-1 transition-all">
                            Upcoming
                        </button>
                        <button onclick="switchSubTab('quiz', 'finished')" id="subtab-quiz-finished"
                            class="text-xs font-bold text-stone-400 hover:text-stone-600 pb-1 transition-all">
                            Finished
                        </button>
                        <button onclick="switchSubTab('quiz', 'overdue')" id="subtab-quiz-overdue"
                            class="text-xs font-bold text-stone-400 hover:text-stone-600 pb-1 transition-all">
                            Overdue
                        </button>
                    </div>

                    @foreach (['upcoming', 'finished', 'overdue'] as $status)
                        <div id="quiz-list-{{ $status }}"
                            class="quiz-sub-list space-y-3 {{ $status !== 'upcoming' ? 'hidden' : '' }}">
                            @forelse($quizzes->get($status, []) as $quiz)
                                <div
                                    class="p-4 rounded-[24px] bg-white border border-stone-100 shadow-sm hover:shadow-md transition-all relative overflow-hidden">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <span
                                                class="text-[10px] font-black tracking-wider text-pink-600 uppercase bg-pink-50 px-2 py-0.5 rounded-full border border-pink-100">
                                                {{ $quiz->subject->name ?? 'General Subject' }}
                                            </span>
                                            <h4 class="text-sm font-black text-stone-800 mt-1">{{ $quiz->title }}</h4>
                                        </div>
                                        <span
                                            class="text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase
                                        {{ $status === 'finished' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : ($status === 'overdue' ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-amber-50 text-amber-600 border border-amber-200') }}">
                                            {{ $status }}
                                        </span>
                                    </div>

                                    <div
                                        class="flex items-center justify-between pt-2 border-t border-stone-50 text-xs text-stone-500 font-semibold">
                                        <div class="flex items-center gap-1">
                                            <span class="material-icons-round text-sm text-stone-400">schedule</span>
                                            <span>{{ $quiz->start_time ? \Carbon\Carbon::parse($quiz->start_time)->format('g:ia') : 'TBA' }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <span class="material-icons-round text-sm text-amber-500">grade</span>
                                            <span class="font-bold text-stone-700">
                                                Score:
                                                {{ $quiz->score !== null ? $quiz->score . '/' . $quiz->total_items : 'Not Graded' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="p-6 rounded-[24px] bg-stone-50 border-2 border-stone-200/60 border-dashed text-center">
                                    <span class="material-icons-round text-stone-300 text-3xl mb-1">quiz</span>
                                    <p class="text-xs text-stone-400 font-bold">No {{ $status }} quizzes found.</p>
                                </div>
                            @endforelse
                        </div>
                    @endforeach
                </div>

                <div id="section-exam" class="space-y-4 hidden">
                    <div class="flex items-center space-x-4 border-b border-stone-200 pb-2 px-1">
                        <button onclick="switchSubTab('exam', 'upcoming')" id="subtab-exam-upcoming"
                            class="text-xs font-bold text-purple-600 border-b-2 border-purple-600 pb-1 transition-all">
                            Upcoming
                        </button>
                        <button onclick="switchSubTab('exam', 'finished')" id="subtab-exam-finished"
                            class="text-xs font-bold text-stone-400 hover:text-stone-600 pb-1 transition-all">
                            Finished
                        </button>
                        <button onclick="switchSubTab('exam', 'overdue')" id="subtab-exam-overdue"
                            class="text-xs font-bold text-stone-400 hover:text-stone-600 pb-1 transition-all">
                            Overdue
                        </button>
                    </div>

                    @foreach (['upcoming', 'finished', 'overdue'] as $status)
                        <div id="exam-list-{{ $status }}"
                            class="exam-sub-list space-y-3 {{ $status !== 'upcoming' ? 'hidden' : '' }}">
                            @forelse($exams->get($status, []) as $exam)
                                <div
                                    class="p-4 rounded-[24px] bg-white border border-stone-100 shadow-sm hover:shadow-md transition-all relative">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <span
                                                class="text-[10px] font-black tracking-wider text-purple-600 uppercase bg-purple-50 px-2 py-0.5 rounded-full border border-purple-100">
                                                {{ $exam->subject->name ?? 'General Subject' }}
                                            </span>
                                            <h4 class="text-sm font-black text-stone-800 mt-1">{{ $exam->title }}</h4>
                                        </div>
                                        <span
                                            class="text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase
                                        {{ $status === 'finished' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : ($status === 'overdue' ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-purple-50 text-purple-600 border border-purple-200') }}">
                                            {{ $status }}
                                        </span>
                                    </div>

                                    <div
                                        class="grid grid-cols-3 gap-2 my-3 p-2.5 rounded-xl bg-stone-50 border border-stone-100 text-[11px]">
                                        <div class="flex flex-col">
                                            <span class="text-[9px] text-stone-400 font-bold uppercase">Date</span>
                                            <span
                                                class="font-extrabold text-stone-700">{{ \Carbon\Carbon::parse($exam->assessment_date)->format('M d, Y') }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[9px] text-stone-400 font-bold uppercase">Room</span>
                                            <span class="font-extrabold text-stone-700">{{ $exam->room ?? 'TBA' }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[9px] text-stone-400 font-bold uppercase">Total Items</span>
                                            <span class="font-extrabold text-indigo-600">{{ $exam->total_items }} Items</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between text-xs text-stone-500">
                                        <span class="text-[11px] text-stone-400 font-medium">
                                            Time:
                                            {{ $exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->format('g:ia') : 'TBA' }}
                                        </span>
                                        <span class="font-black text-stone-800">
                                            Score: <span
                                                class="text-purple-600">{{ $exam->score !== null ? $exam->score . '/' . $exam->total_items : 'Pending' }}</span>
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="p-6 rounded-[24px] bg-stone-50 border-2 border-stone-200/60 border-dashed text-center">
                                    <span class="material-icons-round text-stone-300 text-3xl mb-1">school</span>
                                    <p class="text-xs text-stone-400 font-bold">No {{ $status }} exams found.</p>
                                </div>
                            @endforelse
                        </div>
                    @endforeach
                </div>

            </div>

            <!-- FIXED MODAL POSITIONING -->
            <div id="assessment-modal"
                class="fixed inset-0 bg-stone-900/40 backdrop-blur-sm z-40 flex items-end justify-center pb-32 px-4 hidden overflow-y-auto">
                <div class="absolute inset-0 pointer-events-auto" onclick="closeAssessmentModal()"></div>
                <div id="assessment-modal-card"
                    class="relative z-50 bg-white w-full max-w-sm max-h-[75vh] overflow-y-auto rounded-[32px] p-6 shadow-2xl border border-white/80 transform transition-all scale-95 opacity-0 pointer-events-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-black text-stone-800">Add Assessment</h3>
                        <button onclick="closeAssessmentModal()"
                            class="w-8 h-8 rounded-full bg-stone-100 flex items-center justify-center text-stone-400 hover:text-stone-600">
                            <span class="material-icons-round text-sm">close</span>
                        </button>
                    </div>

                    <form action="{{ route('assessments.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Title</label>
                            <input type="text" name="title" required placeholder="e.g. Midterm Exam / Ch 1 Quiz"
                                class="w-full px-3.5 py-2.5 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800 focus:outline-none focus:border-pink-500">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Subject</label>
                            <select name="subject_id" required
                                class="w-full px-3.5 py-2.5 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800 focus:outline-none focus:border-pink-500">
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Type</label>
                                <select name="type" required
                                    class="w-full px-3 py-2.5 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800">
                                    <option value="quiz">Quiz</option>
                                    <option value="exam">Exam</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Status</label>
                                <select name="status" required
                                    class="w-full px-3 py-2.5 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800">
                                    <option value="upcoming">Upcoming</option>
                                    <option value="finished">Finished</option>
                                    <option value="overdue">Overdue</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Date</label>
                                <input type="date" name="assessment_date" required
                                    class="w-full px-3 py-2 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Time</label>
                                <input type="time" name="start_time"
                                    class="w-full px-3 py-2 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Room</label>
                                <input type="text" name="room" placeholder="e.g. 302"
                                    class="w-full px-2.5 py-2 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Total Items</label>
                                <input type="number" name="total_items" value="10"
                                    class="w-full px-2.5 py-2 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Score</label>
                                <input type="number" name="score" placeholder="Optional"
                                    class="w-full px-2.5 py-2 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800">
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full mt-2 py-3 bg-[#DB2777] hover:bg-[#BE185D] text-white text-xs font-extrabold rounded-xl shadow-lg shadow-pink-200 transition-all">
                            Save Assessment
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- UI FILTERS & ACCORDION JAVASCRIPT LAYER -->
    <script>
        function switchMainTab(type) {
            const quizSec = document.getElementById('section-quiz');
            const examSec = document.getElementById('section-exam');
            const quizBtn = document.getElementById('main-tab-quiz');
            const examBtn = document.getElementById('main-tab-exam');

            if (type === 'quiz') {
                quizSec.classList.remove('hidden');
                examSec.classList.add('hidden');
                quizBtn.className =
                    "flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 bg-white text-stone-800 shadow-xs";
                examBtn.className =
                    "flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 text-stone-400 hover:text-stone-600";
            } else {
                examSec.classList.remove('hidden');
                quizSec.classList.add('hidden');
                examBtn.className =
                    "flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 bg-white text-stone-800 shadow-xs";
                quizBtn.className =
                    "flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 text-stone-400 hover:text-stone-600";
            }
        }

        function switchSubTab(type, status) {
            document.querySelectorAll(`.${type}-sub-list`).forEach(el => el.classList.add('hidden'));
            document.getElementById(`${type}-list-${status}`).classList.remove('hidden');

            const highlightColor = type === 'quiz' ? 'text-[#DB2777] border-[#DB2777]' :
            'text-purple-600 border-purple-600';

            ['upcoming', 'finished', 'overdue'].forEach(s => {
                const btn = document.getElementById(`subtab-${type}-${s}`);
                if (s === status) {
                    btn.className = `text-xs font-bold ${highlightColor} border-b-2 pb-1 transition-all`;
                } else {
                    btn.className = "text-xs font-bold text-stone-400 hover:text-stone-600 pb-1 transition-all";
                }
            });
        }

        function openAssessmentModal() {
            const modal = document.getElementById('assessment-modal');
            const card = document.getElementById('assessment-modal-card');
            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeAssessmentModal() {
            const modal = document.getElementById('assessment-modal');
            const card = document.getElementById('assessment-modal-card');
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        function switchHubTab(tab) {
            const todoSec = document.getElementById('hub-section-todo');
            const assessmentSec = document.getElementById('hub-section-assessment');
            const todoBtn = document.getElementById('hub-tab-todo');
            const assessmentBtn = document.getElementById('hub-tab-assessment');

            if (tab === 'todo') {
                todoSec.classList.remove('hidden');
                assessmentSec.classList.add('hidden');
                todoBtn.className =
                    "flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 bg-white text-stone-800 shadow-xs";
                assessmentBtn.className =
                    "flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 text-stone-400 hover:text-stone-600";
            } else {
                assessmentSec.classList.remove('hidden');
                todoSec.classList.add('hidden');
                assessmentBtn.className =
                    "flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 bg-white text-stone-800 shadow-xs";
                todoBtn.className =
                    "flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 text-stone-400 hover:text-stone-600";
            }
        }

        function filterTasks(status, pillElement) {
            document.querySelectorAll('.stat-pill').forEach(pill => {
                pill.className =
                    "stat-pill bg-stone-100/50 border border-stone-200 p-2.5 rounded-full text-center transition-all transform active:scale-95 focus:outline-none";
                pill.style.boxShadow = "none";
            });

            if (status === 'pending') {
                pillElement.className =
                    "stat-pill bg-amber-500/10 border-2 border-amber-500/30 p-2.5 rounded-full text-center transition-all transform active:scale-95 focus:outline-none ring-2 ring-amber-500/20";
            } else if (status === 'done') {
                pillElement.className =
                    "stat-pill bg-emerald-500/10 border-2 border-emerald-500/30 p-2.5 rounded-full text-center transition-all transform active:scale-95 focus:outline-none ring-2 ring-emerald-500/20";
            } else if (status === 'overdue') {
                pillElement.className =
                    "stat-pill bg-rose-500/10 border-2 border-rose-500/30 p-2.5 rounded-full text-center transition-all transform active:scale-95 focus:outline-none ring-2 ring-rose-500/20";
            }

            const activeSection = document.getElementById('active-tasks-section');
            const completedSection = document.getElementById('completed-section-wrapper');
            const cards = document.querySelectorAll('.todo-card');
            const sectionTitle = document.getElementById('section-title');
            const filteredEmpty = document.getElementById('filtered-empty-state');
            const nativeEmpty = document.getElementById('active-empty-state');

            let visibleCardsCount = 0;

            if (status === 'done') {
                activeSection.classList.add('hidden');
                completedSection.classList.remove('hidden');

                const compContainer = document.getElementById('completed-container');
                const arrow = document.getElementById('accordion-arrow');
                compContainer.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                activeSection.classList.remove('hidden');
                filteredEmpty.classList.add('hidden');

                if (nativeEmpty) nativeEmpty.classList.add('hidden');

                cards.forEach(card => {
                    if (card.getAttribute('data-status') === status) {
                        card.classList.remove('hidden');
                        visibleCardsCount++;
                    } else {
                        card.classList.add('hidden');
                    }
                });

                sectionTitle.textContent = status === 'pending' ? 'Pending Tasks' : 'Overdue Tasks';

                if (visibleCardsCount === 0) {
                    filteredEmpty.classList.remove('hidden');
                }
            }
        }

        function toggleCompletedAccordion() {
            const container = document.getElementById('completed-container');
            const arrow = document.getElementById('accordion-arrow');

            if (container.classList.contains('hidden')) {
                container.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                container.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        }

        function toggleCardDropdown(event, id) {
            event.stopPropagation();
            document.querySelectorAll('.todo-card-dropdown').forEach(d => {
                if (d.id !== id) d.classList.add('hidden');
            });
            document.getElementById(id).classList.toggle('hidden');
        }

        document.addEventListener('click', () => {
            document.querySelectorAll('.todo-card-dropdown').forEach(d => d.classList.add('hidden'));
        });

        function openEditModal(todo) {
            document.getElementById('edit-todo-form').action = `/todo/${todo.id}`;

            document.getElementById('edit-title').value = todo.title;
            document.getElementById('edit-subject').value = todo.subject;
            document.getElementById('edit-description').value = todo.description || '';
            document.getElementById('edit-due-date').value = todo.due_date;

            if (todo.due_time) {
                document.getElementById('edit-due-time').value = todo.due_time.substring(0, 5);
            } else {
                document.getElementById('edit-due-time').value = '12:00';
            }

            const priority = (todo.priority || 'low').toLowerCase();
            document.getElementById('edit-priority-low').checked = (priority === 'low');
            document.getElementById('edit-priority-medium').checked = (priority === 'medium');
            document.getElementById('edit-priority-high').checked = (priority === 'high');

            const modal = document.getElementById('edit-todo-modal');
            const card = document.getElementById('edit-todo-modal-card');

            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.add('opacity-100');
            card.classList.remove('translate-y-8');
            card.classList.add('translate-y-0');
        }

        function closeEditModal() {
            const modal = document.getElementById('edit-todo-modal');
            const card = document.getElementById('edit-todo-modal-card');

            modal.classList.remove('opacity-100');
            card.classList.remove('translate-y-0');
            card.classList.add('translate-y-8'); // Fixed typo from card.Canvas.add

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function openDeleteConfirmModal(id, title) {
            document.getElementById('delete-task-title').textContent = title;
            document.getElementById('delete-todo-form').action = `/todo/${id}`;
            document.getElementById('delete-confirm-modal').classList.remove('hidden');
        }

        function closeDeleteConfirmModal() {
            document.getElementById('delete-confirm-modal').classList.add('hidden');
        }

        function openCompleteConfirmModal(id, title) {
            document.getElementById('complete-task-title').textContent = title;
            document.getElementById('complete-todo-form').action = `/todo/${id}/complete`;
            document.getElementById('complete-confirm-modal').classList.remove('hidden');
        }

        function closeCompleteConfirmModal() {
            document.getElementById('complete-confirm-modal').classList.add('hidden');
        }

        /* DYNAMIC SUBJECT MODAL HANDLERS */
        function openSubjectModal(subjectData) {
            const modal = document.getElementById('edit-subject-modal');
            const card = document.getElementById('edit-subject-modal-card');

            document.getElementById('edit-subject-form').action = `/subject/${subjectData.id}`;
            document.getElementById('edit-subject-code').value = subjectData.code;
            document.getElementById('edit-subject-name').value = subjectData.name;

            if (document.getElementById(`color-accent-${subjectData.color}`)) {
                document.getElementById(`color-accent-${subjectData.color}`).checked = true;
            }

            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.add('opacity-100');
            card.classList.remove('translate-y-4');
        }

        function closeSubjectModal() {
            const modal = document.getElementById('edit-subject-modal');
            const card = document.getElementById('edit-subject-modal-card');

            modal.classList.remove('opacity-100');
            card.classList.add('translate-y-4');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
@endsection
