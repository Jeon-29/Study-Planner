<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Study Planner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//[unpkg.com/alpinejs](https://unpkg.com/alpinejs)" defer></script>
    <!-- Material Design Icons Font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Clean overlay style overrides for native date/time picks */
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            background: transparent;
            bottom: 0;
            color: transparent;
            cursor: pointer;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            width: auto;
            height: auto;
        }

        /* Utility helper to hide custom scrollbars while keeping swipe actions clean */
        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-none {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-[#FDFBF7] min-h-screen pb-28 relative overflow-x-hidden">

    <!-- ================= PAGE TRANSITION LOADER ================= -->
    <div id="page-loader"
        class="fixed inset-0 z-[9999] bg-stone-900/15 backdrop-blur-md flex flex-col items-center justify-center transition-opacity duration-300 opacity-0 pointer-events-none">
        <div class="bg-white/80 backdrop-blur-2xl border border-white/60 p-6 rounded-[28px] shadow-2xl flex flex-col items-center gap-3 transform scale-95 transition-transform duration-300"
            id="loader-card">

            <!-- FIXED: Dual Ring Spinner using border-4 -->
            <div class="relative w-10 h-10 flex items-center justify-center">
                <!-- Background Ring -->
                <div class="absolute inset-0 rounded-full border-4 border-stone-200"></div>
                <!-- Spinning Accent Ring -->
                <div class="absolute inset-0 rounded-full border-4 border-[#DB2777] border-t-transparent animate-spin">
                </div>
            </div>

            <span class="text-[11px] font-extrabold text-stone-700 tracking-wider uppercase">Loading...</span>
        </div>
    </div>

    <!-- GLOBAL LIQUID-GLASS TOAST NOTIFICATION COMPONENT -->
    @if (session('success'))
        <div id="success-toast"
            class="fixed top-5 left-1/2 -translate-x-1/2 z-[100] flex items-center space-x-2.5 bg-white/80 backdrop-blur-xl border border-emerald-500/30 px-4 py-3 rounded-full shadow-[0_12px_40px_rgba(16,185,129,0.12)] transform transition-all duration-300 translate-y-[-20px] opacity-0 pointer-events-none">
            <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0">
                <span class="material-icons-round text-xs">check</span>
            </div>
            <span class="text-xs font-bold text-stone-800 tracking-tight whitespace-nowrap">
                {{ session('success') }}
            </span>
        </div>
    @endif

    <!-- Ambient Background Blobs -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div
            class="absolute -top-[10%] -right-[15%] w-[120%] sm:w-[400px] h-[300px] rounded-b-[10rem] rounded-l-[10rem] bg-[#FBCFE8] opacity-40 transform rotate-12">
        </div>
        <div
            class="absolute bottom-0 -left-[10%] w-[120%] sm:w-[450px] h-[350px] rounded-t-[12rem] rounded-r-[12rem] bg-[#FFE4E6] opacity-50 transform -rotate-6">
        </div>
    </div>

    <!-- Main Content Rendering Area -->
    <main class="relative z-10 w-full max-w-md mx-auto px-4 pt-6">
        @yield('content')
    </main>

    <!-- ========================================================================= -->
    <!-- APPLE-INSPIRED LIQUID GLASS DOCK WITH INTEGRATED FAB                       -->
    <!-- ========================================================================= -->

    <!-- 1. Modern Glass Selection Action Sheet -->
    <div id="fab-menu"
        class="fixed inset-0 z-50 pointer-events-none transition-all duration-300 opacity-0 scale-95 flex items-end justify-center pb-28 px-4">
        <div class="absolute inset-0 bg-[#1C1917]/5 backdrop-blur-[2px]" onclick="toggleFABMenu()"></div>

        <!-- Action Cards Container -->
        <div
            class="relative w-full max-w-xs bg-white/75 backdrop-blur-2xl rounded-[24px] p-3.5 border border-white/80 shadow-[0_20px_50px_rgba(0,0,0,0.1)] space-y-1.5 z-10">
            <p class="text-[9px] font-bold text-[#78716C]/80 uppercase tracking-widest px-2.5 mb-1">Create New</p>

            <!-- Option A: Add Course/Subject -->
            <button onclick="openSubjectModal()"
                class="w-full flex items-center space-x-3 p-2.5 rounded-xl hover:bg-pink-50/60 text-[#1C1917] transition-all group">
                <div
                    class="w-8 h-8 rounded-lg bg-pink-50 border border-pink-100 flex items-center justify-center text-[#DB2777]">
                    <span class="material-icons-round text-lg">auto_stories</span>
                </div>
                <span class="text-xs font-semibold tracking-wide">Course/Subject</span>
            </button>

            <!-- Option B: Add Class Schedule -->
            <button onclick="openScheduleModal()"
                class="w-full flex items-center space-x-3 p-2.5 rounded-xl hover:bg-amber-50/60 text-[#1C1917] transition-all group">
                <div
                    class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                    <span class="material-icons-round text-lg">schedule</span>
                </div>
                <span class="text-xs font-semibold tracking-wide">Class Schedule</span>
            </button>

            <!-- Option C: Add To-Do (Triggers Modal) -->
            <button onclick="openTodoModal()"
                class="w-full flex items-center space-x-3 p-2.5 rounded-xl hover:bg-purple-50/60 text-[#1C1917] transition-all group">
                <div
                    class="w-8 h-8 rounded-lg bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600">
                    <span class="material-icons-round text-lg">assignment_turned_in</span>
                </div>
                <span class="text-xs font-semibold tracking-wide">To-Do</span>
            </button>

            <button onclick="window.location.href='{{ route('assessments.index') }}'"
                class="w-full flex items-center space-x-3 p-2.5 rounded-xl hover:bg-emerald-50/60 text-[#1C1917] transition-all group">
                <div
                    class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <span class="material-icons-round text-lg">quiz</span>
                </div>
                <span class="text-xs font-semibold tracking-wide">Exam / Quiz</span>
            </button>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- DYNAMIC FORM MODAL: REGISTER NEW SUBJECT                                  -->
    <!-- ========================================================================= -->
    <div id="subject-modal"
        class="fixed inset-0 z-50 pointer-events-none transition-all duration-300 opacity-0 flex items-end justify-center pb-24 px-4">
        <div class="absolute inset-0 bg-[#1C1917]/10 backdrop-blur-md" onclick="closeSubjectModal()"></div>

        <div class="relative w-full max-w-sm bg-white/85 backdrop-blur-2xl rounded-[32px] p-6 border border-white/90 shadow-[0_24px_60px_rgba(0,0,0,0.12)] transform translate-y-8 transition-all duration-300 z-10"
            id="subject-modal-card">

            <div class="flex items-start justify-between mb-5">
                <div>
                    <h3 class="text-sm font-bold text-[#1C1917] tracking-tight">New Course Subject</h3>
                    <p class="text-[11px] text-[#78716C] font-medium mt-0.5">Add a new dynamic core tracking cluster</p>
                </div>
                <button onclick="closeSubjectModal()"
                    class="w-7 h-7 rounded-full bg-stone-100 hover:bg-stone-200/70 flex items-center justify-center text-stone-500 transition focus:outline-none">
                    <span class="material-icons-round text-base">close</span>
                </button>
            </div>

            <form action="{{ route('subject.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label
                        class="block text-[10px] font-bold text-[#78716C] uppercase tracking-wider mb-1.5 px-0.5">Course
                        Code</label>
                    <input type="text" name="code" required placeholder="E.G., IT211"
                        class="w-full h-12 px-4 bg-stone-50/40 border border-stone-200/60 rounded-2xl text-xs font-semibold tracking-wide uppercase placeholder-stone-300 focus:outline-none focus:border-stone-400 focus:bg-white transition-all">
                </div>

                <div>
                    <label
                        class="block text-[10px] font-bold text-[#78716C] uppercase tracking-wider mb-1.5 px-0.5">Subject
                        Description</label>
                    <input type="text" name="name" required placeholder="e.g., Web Development"
                        class="w-full h-12 px-4 bg-stone-50/40 border border-stone-200/60 rounded-2xl text-xs font-medium text-stone-800 placeholder-stone-300 focus:outline-none focus:border-stone-400 focus:bg-white transition-all">
                </div>

                <!-- Semester (With Unique ID & Smart Defaulting to Current Filter) -->
                <div>
                    <label
                        class="block text-[0.625rem] font-bold text-stone-500 mb-1.5 uppercase tracking-wider pl-0.5">Academic
                        Semester</label>
                    <select id="add_semester" name="semester" required
                        class="w-full h-12 px-4 bg-stone-100/40 border border-stone-200/60 rounded-2xl text-xs font-semibold text-stone-900 outline-none focus:border-indigo-400 focus:bg-white/80 transition-colors">
                        <option value="1st Sem" {{ ($currentFilter ?? '') === '2nd-sem' ? '' : 'selected' }}>1st
                            Semester</option>
                        <option value="2nd Sem" {{ ($currentFilter ?? '') === '2nd-sem' ? 'selected' : '' }}>2nd
                            Semester</option>
                    </select>
                </div>

                <!-- COMPACT HORIZONTAL COLOR STRIP LAYER -->
                <div>
                    <label class="block text-[10px] font-bold text-[#78716C] uppercase tracking-wider mb-2 px-0.5">Color
                        Theme Accent</label>
                    <div class="flex items-center gap-2.5 overflow-x-auto pb-2 pt-0.5 scrollbar-none snap-x">
                        @php
                            $availableColors = [
                                'yellow',
                                'violet',
                                'rose',
                                'pink',
                                'blue',
                                'orange',
                                'emerald',
                                'green',
                                'maroon',
                                'red',
                                'gray',
                            ];
                            $colorHexes = [
                                'yellow' => '#FEF08A',
                                'violet' => '#C4B5FD',
                                'rose' => '#FDA4AF',
                                'pink' => '#F9A8D4',
                                'blue' => '#93C5FD',
                                'orange' => '#FDBA74',
                                'emerald' => '#6EE7B7',
                                'green' => '#22C55E',
                                'maroon' => '#D98C98',
                                'red' => '#FCA5A5',
                                'gray' => '#D1D5DB',
                            ];
                        @endphp

                        @foreach ($availableColors as $colorKey)
                            @php $hex = $colorHexes[$colorKey]; @endphp
                            <label class="cursor-pointer shrink-0 snap-start">
                                <input type="radio" name="color_theme" value="{{ $colorKey }}"
                                    {{ $colorKey === 'blue' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-12 h-12 rounded-2xl border border-stone-200/60 bg-stone-50/20 flex items-center justify-center transition-all peer-checked:bg-white peer-checked:shadow-[0_2px_8px_rgba(0,0,0,0.04)] peer-checked:ring-2"
                                    style="--tw-ring-color: {{ $hex }};">
                                    <div style="background-color: {{ $hex }};"
                                        class="w-6 h-6 rounded-full shadow-inner"></div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-2 grid grid-cols-2 gap-3">
                    <button type="button" onclick="closeSubjectModal()"
                        class="h-12 rounded-2xl border border-stone-200 text-xs font-bold text-stone-600 bg-white hover:bg-stone-50 transition transform active:scale-98 focus:outline-none">
                        Cancel
                    </button>
                    <button type="submit"
                        class="h-12 rounded-2xl bg-[#1C1917] hover:bg-[#2E2925] text-xs font-bold text-white shadow-sm transition transform active:scale-98 focus:outline-none">
                        Save Subject
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- DYNAMIC FORM MODAL: ADD NEW TO-DO TASK                                     -->
    <!-- ========================================================================= -->
    <div id="todo-modal"
        class="fixed inset-0 z-50 pointer-events-none transition-all duration-300 opacity-0 flex items-end justify-center pb-24 px-4">
        <div class="absolute inset-0 bg-[#1C1917]/10 backdrop-blur-md" onclick="closeTodoModal()"></div>

        <div class="relative w-full max-w-sm bg-white/85 backdrop-blur-2xl rounded-[28px] p-5 border border-white/90 shadow-[0_24px_60px_rgba(0,0,0,0.12)] transform translate-y-8 transition-all duration-300 z-10"
            id="todo-modal-card">

            <div class="flex items-center justify-between mb-4 px-0.5">
                <div>
                    <h3 class="text-sm font-bold text-[#1C1917] tracking-tight">New Assignment</h3>
                    <p class="text-[10px] text-[#78716C] font-medium mt-0.5">Fill out your task specifications</p>
                </div>
                <button onclick="closeTodoModal()"
                    class="w-7 h-7 rounded-full bg-stone-100 hover:bg-stone-200/70 flex items-center justify-center text-stone-500 transition">
                    <span class="material-icons-round text-base">close</span>
                </button>
            </div>

            <form action="{{ route('todo.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label
                        class="block text-[10px] font-bold text-[#78716C] uppercase tracking-wider mb-1 px-1">Title</label>
                    <input type="text" name="title" required placeholder="e.g., Database Normalization Essay"
                        class="w-full h-10 px-3.5 bg-stone-50/50 border border-stone-200/80 rounded-xl text-xs font-medium placeholder-stone-400 focus:outline-none focus:border-[#DB2777] focus:bg-white transition-all">
                </div>

                <div>
                    <label
                        class="block text-[10px] font-bold text-[#78716C] uppercase tracking-wider mb-1 px-1">Subject</label>
                    <div class="relative">
                        <select name="subject" required
                            class="w-full h-10 px-3.5 bg-stone-50/50 border border-stone-200/80 rounded-xl text-xs font-medium text-stone-700 appearance-none focus:outline-none focus:border-[#DB2777] focus:bg-white transition-all">
                            <option value="" disabled selected hidden>Select relevant course...</option>
                            @forelse($globalSubjects as $sub)
                                <option value="{{ $sub->code }}">{{ $sub->code }} - {{ $sub->name }}
                                </option>
                            @empty
                                <option value="" disabled>No subjects registered yet. Create one first!</option>
                            @endforelse
                        </select>
                        <span
                            class="material-icons-round text-base text-stone-400 absolute right-3.5 top-2.5 pointer-events-none">expand_more</span>
                    </div>
                </div>

                <div>
                    <label
                        class="block text-[10px] font-bold text-[#78716C] uppercase tracking-wider mb-1 px-1">Description</label>
                    <textarea name="description" rows="2"
                        placeholder="Write down instructions, references, or specific deliverables..."
                        class="w-full p-3 bg-stone-50/50 border border-stone-200/80 rounded-xl text-xs font-medium placeholder-stone-400 focus:outline-none focus:border-[#DB2777] focus:bg-white transition-all resize-none"></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-[#78716C] uppercase tracking-wider mb-1 px-1">Due
                        Date & time</label>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div class="relative flex items-center">
                            <input type="date" name="due_date" required
                                class="w-full h-11 pl-9 pr-3 bg-white border border-stone-200 rounded-full text-xs font-semibold text-stone-700 shadow-sm focus:outline-none focus:border-[#DB2777] transition-all">
                            <span
                                class="material-icons-round text-base text-stone-500 absolute left-3.5 pointer-events-none">calendar_today</span>
                        </div>
                        <div class="relative flex items-center">
                            <input type="time" name="due_time" required
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
                            <input type="radio" name="priority" value="low" checked class="sr-only peer">
                            <div
                                class="h-10 rounded-full border text-xs font-bold flex items-center justify-center transition-all bg-white border-stone-200 text-stone-400 peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 shadow-sm">
                                Low</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="medium" class="sr-only peer">
                            <div
                                class="h-10 rounded-full border text-xs font-bold flex items-center justify-center transition-all bg-white border-stone-200 text-stone-400 peer-checked:bg-amber-50 peer-checked:border-amber-500 peer-checked:text-amber-600 shadow-sm">
                                Medium</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="high" class="sr-only peer">
                            <div
                                class="h-10 rounded-full border text-xs font-bold flex items-center justify-center transition-all bg-white border-stone-200 text-stone-400 peer-checked:bg-rose-50 peer-checked:border-rose-400 peer-checked:text-rose-600 shadow-sm">
                                High</div>
                        </label>
                    </div>
                </div>

                <div class="pt-2 flex items-center space-x-2">
                    <button type="button" onclick="closeTodoModal()"
                        class="flex-1 h-10 rounded-xl border border-stone-200 text-xs font-bold text-stone-600 hover:bg-stone-50 transition active:scale-98">Cancel</button>
                    <button type="submit"
                        class="flex-1 h-10 rounded-xl bg-[#1C1917] hover:bg-[#2E2925] text-xs font-bold text-white shadow-md border border-stone-800 transition active:scale-98">Save
                        Assignment</button>
                </div>
            </form>
        </div>
    </div>

    <!-- DYNAMIC FORM MODAL: ADD CLASS SCHEDULE -->
    <div id="schedule-modal"
        class="fixed inset-0 z-50 pointer-events-none transition-all duration-300 opacity-0 flex items-end justify-center pb-24 px-4">
        <div class="absolute inset-0 bg-[#1C1917]/10 backdrop-blur-md" onclick="closeScheduleModal()"></div>

        <div class="relative w-full max-w-sm bg-white/85 backdrop-blur-2xl rounded-[32px] p-6 border border-white/90 shadow-[0_24px_60px_rgba(0,0,0,0.12)] transform translate-y-8 transition-all duration-300 z-10"
            id="schedule-modal-card">

            <div class="flex items-start justify-between mb-5">
                <div>
                    <h3 class="text-sm font-bold text-[#1C1917] tracking-tight">New Class Schedule</h3>
                    <p class="text-[11px] text-[#78716C] font-medium mt-0.5">Set room and recurring timing</p>
                </div>
                <button onclick="closeScheduleModal()"
                    class="w-7 h-7 rounded-full bg-stone-100 hover:bg-stone-200/70 flex items-center justify-center text-stone-500 transition">
                    <span class="material-icons-round text-base">close</span>
                </button>
            </div>

            <form action="{{ route('schedule.store') }}" method="POST" class="space-y-4">
                @csrf
                <!-- Subject Selection -->
                <div>
                    <label
                        class="block text-[10px] font-bold text-[#78716C] uppercase tracking-wider mb-1.5 px-0.5">Select
                        Subject</label>
                    <select name="subject_id" required
                        class="w-full h-12 px-4 bg-stone-50/40 border border-stone-200/60 rounded-2xl text-xs font-semibold tracking-wide uppercase focus:outline-none focus:bg-white transition-all">
                        @foreach (Auth::user()->subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->code }} - {{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Room & Day -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label
                            class="block text-[10px] font-bold text-[#78716C] uppercase tracking-wider mb-1.5 px-0.5">Room</label>
                        <input type="text" name="room" placeholder="e.g., Room 302"
                            class="w-full h-12 px-4 bg-stone-50/40 border border-stone-200/60 rounded-2xl text-xs font-semibold placeholder-stone-300 focus:outline-none focus:bg-white transition-all">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-[#78716C] uppercase tracking-wider mb-1.5 px-0.5">Day</label>
                        <select name="day_of_week"
                            class="w-full h-12 px-4 bg-stone-50/40 border border-stone-200/60 rounded-2xl text-xs font-semibold focus:outline-none focus:bg-white">
                            @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
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

                <!-- Time Inputs -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label
                            class="block text-[10px] font-bold text-[#78716C] uppercase tracking-wider mb-1.5 px-0.5">Start
                            Time</label>
                        <input type="time" name="start_time" required
                            class="w-full h-12 px-4 bg-stone-50/40 border border-stone-200/60 rounded-2xl text-xs font-semibold focus:outline-none focus:bg-white transition-all">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-[#78716C] uppercase tracking-wider mb-1.5 px-0.5">End
                            Time</label>
                        <input type="time" name="end_time" required
                            class="w-full h-12 px-4 bg-stone-50/40 border border-stone-200/60 rounded-2xl text-xs font-semibold focus:outline-none focus:bg-white transition-all">
                    </div>
                </div>

                <button type="submit"
                    class="w-full h-12 rounded-2xl bg-[#1C1917] hover:bg-[#2E2925] text-xs font-bold text-white shadow-sm transition transform active:scale-98">
                    Save Class Schedule
                </button>
            </form>
        </div>
    </div>

    <!-- 2. Apple-Style Continuous Liquid Glass Dock Layout -->
    <nav
        class="fixed bottom-5 left-4 right-4 max-w-sm mx-auto bg-white/60 backdrop-blur-2xl rounded-[24px] border border-white/80 shadow-[0_12px_40px_rgba(0,0,0,0.06)] z-50 transition-all duration-300">
        <div class="flex items-center justify-between px-2 h-16">

            <a href="{{ route('dashboard') }}"
                class="flex flex-col items-center justify-center flex-1 h-12 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'text-[#DB2777] font-semibold' : 'text-[#78716C] hover:text-[#1C1917]' }}">
                <span
                    class="material-icons-round text-xl {{ request()->routeIs('dashboard') ? 'filter drop-shadow-sm' : '' }}">home</span>
                <span class="text-[9px] tracking-wide mt-0.5">Home</span>
            </a>

            <!-- UPDATED: Now points to the schedule route with active state styling -->
            <a href="{{ route('schedule') }}"
                class="flex flex-col items-center justify-center flex-1 h-12 rounded-2xl transition-all {{ request()->routeIs('schedule') ? 'text-[#DB2777] font-semibold' : 'text-[#78716C] hover:text-[#1C1917]' }}">
                <span
                    class="material-icons-round text-xl {{ request()->routeIs('schedule') ? 'filter drop-shadow-sm' : '' }}">calendar_today</span>
                <span class="text-[9px] tracking-wide mt-0.5">Sched</span>
            </a>

            <div class="flex items-center justify-center flex-1 h-12 relative">
                <button onclick="toggleFABMenu()" id="main-fab"
                    class="w-11 h-11 rounded-full bg-[#1C1917] hover:bg-[#2E2925] text-white flex items-center justify-center shadow-md border border-stone-800 transition-all duration-300 transform active:scale-90">
                    <span id="fab-icon"
                        class="material-icons-round text-xl transition-transform duration-300">add</span>
                </button>
            </div>

            <a href="{{ route('subject.index') }}"
                class="flex flex-col items-center justify-center flex-1 h-12 rounded-2xl transition-all {{ request()->routeIs('subject.index') ? 'text-[#DB2777] font-semibold' : 'text-[#78716C] hover:text-[#1C1917]' }}">
                <span
                    class="material-icons-round text-xl {{ request()->routeIs('subject.index') ? 'filter drop-shadow-sm' : '' }}">auto_stories</span>
                <span class="text-[9px] tracking-wide mt-0.5">Subjects</span>
            </a>

            <a href="{{ route('todo.index') }}"
                class="flex flex-col items-center justify-center flex-1 h-12 rounded-2xl transition-all {{ request()->routeIs('todo.index') ? 'text-[#DB2777] font-semibold' : 'text-[#78716C] hover:text-[#1C1917]' }}">
                <span
                    class="material-icons-round text-xl {{ request()->routeIs('todo.index') ? 'filter drop-shadow-sm' : '' }}">assignment_turned_in</span>
                <span class="text-[9px] tracking-wide mt-0.5">To-Do</span>
            </a>

            <a href="{{ route('assessments.index') }}"
                class="flex flex-col items-center justify-center flex-1 h-12 rounded-2xl transition-all {{ request()->routeIs('assessments.index') ? 'text-[#DB2777] font-semibold' : 'text-[#78716C] hover:text-[#1C1917]' }}">
                <span
                    class="material-icons-round text-xl {{ request()->routeIs('assessments.index') ? 'filter drop-shadow-sm' : '' }}">quiz</span>
                <span class="text-[9px] tracking-wide mt-0.5">Exams</span>
            </a>

        </div>
    </nav>

    <!-- Interface Animation Control Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('success-toast');
            if (toast) {
                setTimeout(() => {
                    toast.classList.remove('translate-y-[-20px]', 'opacity-0', 'pointer-events-none');
                    toast.classList.add('translate-y-0', 'opacity-100');
                }, 100);

                setTimeout(() => {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-[-20px]', 'opacity-0', 'pointer-events-none');
                }, 3500);
            }
        });

        function toggleFABMenu() {
            const menu = document.getElementById('fab-menu');
            const icon = document.getElementById('fab-icon');
            const fab = document.getElementById('main-fab');

            if (menu.classList.contains('pointer-events-none')) {
                menu.classList.remove('pointer-events-none');
                menu.classList.add('opacity-100', 'scale-100');
                icon.style.transform = 'rotate(135deg)';
                fab.classList.replace('bg-[#1C1917]', 'bg-[#DB2777]');
            } else {
                menu.classList.remove('opacity-100', 'scale-100');
                menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                icon.style.transform = 'rotate(0deg)';
                fab.classList.replace('bg-[#DB2777]', 'bg-[#1C1917]');
            }
        }

        function openSubjectModal() {
            toggleFABMenu();
            const modal = document.getElementById('subject-modal');
            const card = document.getElementById('subject-modal-card');
            modal.classList.remove('pointer-events-none');
            modal.classList.add('opacity-100');
            card.classList.remove('translate-y-8');
            card.classList.add('translate-y-0');
        }

        function openSubjectModal() {
            const modal = document.getElementById('subject-modal');
            const card = document.getElementById('subject-modal-card');

            // Optional smart-sync: Automatically match the dropdown to your current active filter tab
            const currentFilter = "{{ $currentFilter ?? 'all' }}";
            const semesterSelect = document.getElementById('add_semester');

            if (semesterSelect) {
                if (currentFilter === '1st-sem') {
                    semesterSelect.value = '1st Sem';
                } else if (currentFilter === '2nd-sem') {
                    semesterSelect.value = '2nd Sem';
                }
            }

            // Show modal & trigger smooth transition
            modal.classList.remove('pointer-events-none');
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');

            card.classList.remove('translate-y-8');
            card.classList.add('translate-y-0');
        }

        function closeSubjectModal() {
            const modal = document.getElementById('subject-modal');
            const card = document.getElementById('subject-modal-card');

            // Fade out & slide down
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');

            card.classList.remove('translate-y-0');
            card.classList.add('translate-y-8');

            setTimeout(() => {
                modal.classList.add('pointer-events-none');
            }, 300);
        }

        // Make sure your global window click listener includes closing the add modal if clicked outside:
        window.onclick = function(e) {
            if (e.target === document.getElementById('subject-modal')) closeSubjectModal();
            if (e.target === document.getElementById('editSubjectModal')) closeEditModal();
            if (e.target === document.getElementById('deleteSubjectModal')) closeDeleteModal();
            if (e.target === document.getElementById('archiveSubjectModal')) closeArchiveModal();
        }

        function closeSubjectModal() {
            const modal = document.getElementById('subject-modal');
            const card = document.getElementById('subject-modal-card');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0', 'pointer-events-none');
            card.classList.remove('translate-y-0');
            card.classList.add('translate-y-8');
        }

        function openTodoModal() {
            toggleFABMenu();
            const modal = document.getElementById('todo-modal');
            const card = document.getElementById('todo-modal-card');
            modal.classList.remove('pointer-events-none');
            modal.classList.add('opacity-100');
            card.classList.remove('translate-y-8');
            card.classList.add('translate-y-0');
        }

        function closeTodoModal() {
            const modal = document.getElementById('todo-modal');
            const card = document.getElementById('todo-modal-card');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0', 'pointer-events-none');
            card.classList.remove('translate-y-0');
            card.classList.add('translate-y-8');
        }

        function openScheduleModal() {
            toggleFABMenu(); // Close FAB menu first
            const modal = document.getElementById('schedule-modal');
            modal.classList.remove('pointer-events-none', 'opacity-0');
            document.getElementById('schedule-modal-card').classList.remove('translate-y-8');
        }

        function closeScheduleModal() {
            const modal = document.getElementById('schedule-modal');
            modal.classList.add('pointer-events-none', 'opacity-0');
            document.getElementById('schedule-modal-card').classList.add('translate-y-8');
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

        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('page-loader');
            const loaderCard = document.getElementById('loader-card');

            function showLoader() {
                if (!loader) return;
                loader.classList.remove('opacity-0', 'pointer-events-none');
                loader.classList.add('opacity-100');
                if (loaderCard) {
                    loaderCard.classList.remove('scale-95');
                    loaderCard.classList.add('scale-100');
                }
            }

            function hideLoader() {
                if (!loader) return;
                loader.classList.remove('opacity-100');
                loader.classList.add('opacity-0', 'pointer-events-none');
                if (loaderCard) {
                    loaderCard.classList.remove('scale-100');
                    loaderCard.classList.add('scale-95');
                }
            }

            // 1. Intercept internal link clicks
            document.addEventListener('click', function(e) {
                const anchor = e.target.closest('a');
                if (!anchor) return;

                const href = anchor.getAttribute('href');
                const target = anchor.getAttribute('target');

                // Skip if link is a modal trigger, anchor hash (#), JS void, new tab, or modifier keys (Ctrl/Cmd click)
                if (
                    !href ||
                    href.startsWith('#') ||
                    href.startsWith('javascript:') ||
                    target === '_blank' ||
                    e.ctrlKey ||
                    e.metaKey ||
                    anchor.hasAttribute('download')
                ) {
                    return;
                }

                showLoader();
            });

            // 2. Intercept form submissions
            document.addEventListener('submit', function(e) {
                // Skip full-screen loader if the submission is handled by AJAX (e.g., progress bar) or targets #addFileForm
                if (e.defaultPrevented || e.target.id === 'addFileForm' || e.target.closest(
                    '#addFileForm')) {
                    return;
                }

                // Check if form is valid before showing loader
                if (e.target.checkValidity()) {
                    showLoader();
                }
            });

            // 3. Hide loader when page is restored from Back/Forward Cache (BFCache)
            window.addEventListener('pageshow', function(event) {
                hideLoader();
            });
        });
    </script>
    <script src="//[unpkg.com/alpinejs](https://unpkg.com/alpinejs)" defer></script>
    <script src="{{ asset('js/guide.js') }}"></script>
</body>

</html>
