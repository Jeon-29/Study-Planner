@extends('layouts.app')

@section('title', 'Overview')

@section('content')

@include('partials.guide-modal')

    <!-- ========================================================================= -->
    <!-- 1. HEADER ROW WITH INLINE MICRO PROGRESS BADGE                            -->
    <!-- ========================================================================= -->
    <div class="flex items-center justify-between mb-6 p-1">
        <div class="flex items-center space-x-3">
            <!-- Profile Avatar Anchor -->
            <a href="{{ route('profile.index') }}"
                class="w-12 h-12 rounded-full overflow-hidden border-2 border-pink-400 p-[2px] bg-white shadow-md flex-shrink-0 hover:scale-105 transition-transform duration-200 flex items-center justify-center">
                @if (auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Profile"
                        class="w-full h-full rounded-full object-cover">
                @else
                    <span class="text-sm font-black text-indigo-950">{{ substr(auth()->user()->name, 0, 1) }}</span>
                @endif
            </a>
            <div class="flex flex-col">
                <h1 class="text-lg font-black text-[#1C1917] leading-tight tracking-tight">
                    Hello, {{ auth()->user()->name }}
                </h1>

                <!-- Subtitle with Track, Year Level, & Integrated Micro Progress Pill -->
                <div class="flex items-center gap-2 mt-1 flex-wrap">
                    <span
                        class="text-[10px] font-extrabold text-[#DB2777] uppercase tracking-wider bg-pink-100/80 border border-pink-200 px-2.5 py-0.5 rounded-full shadow-sm">
                        {{ auth()->user()->track ?? 'BSIT Student' }} &bull; {{ auth()->user()->year_level ?? '2nd Year' }}
                    </span>

                    <span class="w-1 h-1 rounded-full bg-stone-300"></span>

                    <!-- Ultra-Compact Progress Pill -->
                    <div class="flex items-center gap-1.5 bg-white border border-stone-200 px-2 py-0.5 rounded-full shadow-sm">
                        <div class="w-8 bg-stone-100 h-1 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-indigo-500 to-[#DB2777] h-full rounded-full transition-all duration-500"
                                style="width: {{ $progressPercentage ?? 0 }}%;"></div>
                        </div>
                        <span class="text-[10px] font-black text-stone-700 leading-none">
                            {{ $completedTasks ?? 0 }}/{{ $totalTasks ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. FULL-HEIGHT HERO ANCHOR: NEXT UP TASK (Deep Indigo Distinction)        -->
    <!-- ========================================================================= -->
    <div
        class="mb-6 relative overflow-hidden bg-gradient-to-br from-indigo-950 via-slate-900 to-indigo-950 text-white p-5 rounded-[24px] shadow-xl border border-indigo-800/50">

        <!-- Distinct Glass Glows -->
        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-pink-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -top-10 w-40 h-40 bg-indigo-500/30 rounded-full blur-3xl pointer-events-none"></div>

        @if ($nextUpTask)
            <div class="flex justify-between items-center relative z-10">
                <span
                    class="px-3 py-1 rounded-full text-[9px] font-black tracking-widest uppercase bg-gradient-to-r from-pink-500 to-rose-500 text-white shadow-md">
                    Next Up Assignment
                </span>
                <div
                    class="flex items-center space-x-1 bg-amber-400/20 border border-amber-400/40 px-2.5 py-1 rounded-xl text-amber-300 shadow-sm backdrop-blur-md">
                    <span class="material-icons-round text-xs animate-pulse">schedule</span>
                    <span class="text-[10px] font-extrabold tracking-wide">
                        Due
                        {{ $nextUpTask->due_time ? \Carbon\Carbon::parse($nextUpTask->due_time)->format('g:i A') : \Carbon\Carbon::parse($nextUpTask->due_date)->format('M d') }}
                    </span>
                </div>
            </div>

            <div class="mt-4 flex items-start justify-between gap-3 relative z-10">
                <div>
                    <h3 class="text-base font-black tracking-tight text-white">{{ $nextUpTask->title }}</h3>
                    <p class="text-xs text-indigo-200 mt-1 font-medium leading-relaxed">
                        {{ $nextUpTask->description ?? 'Subject: ' . ($nextUpTask->subjectDetails->name ?? ($nextUpTask->subject ?? 'General Task')) }}
                    </p>
                </div>

                <form action="{{ route('todo.complete', $nextUpTask->id) }}" method="POST" class="shrink-0">
                    @csrf
                    @method('PATCH')
                    <button type="submit" title="Mark as Completed"
                        class="w-12 h-12 rounded-2xl bg-white/10 hover:bg-emerald-500 border border-white/20 flex items-center justify-center text-white transition-all duration-200 active:scale-95 group shadow-lg backdrop-blur-md">
                        <span class="material-icons-round text-2xl group-hover:scale-110 transition-transform">check</span>
                    </button>
                </form>
            </div>
        @else
            <div class="flex justify-between items-center relative z-10">
                <span
                    class="px-3 py-1 rounded-full text-[9px] font-black tracking-widest uppercase bg-emerald-500 text-white shadow-md">
                    All Clear
                </span>
            </div>
            <div class="mt-4 relative z-10">
                <h3 class="text-base font-black tracking-tight text-white">No Upcoming Deadlines</h3>
                <p class="text-xs text-indigo-200 mt-1 font-medium leading-relaxed">
                    You're completely caught up on your assignments! Outstanding work.
                </p>
            </div>
        @endif
    </div>

    <!-- ========================================================================= -->
    <!-- 3. COMPACT TODAY CALENDAR ROW (Clean Glassmorphism)                       -->
    <!-- ========================================================================= -->
    <div class="mb-6 flex items-stretch space-x-3">

        <!-- Date Block Container -->
        <div class="relative flex-shrink-0 pt-1">
            <!-- Pill Badge -->
            <span class="absolute -top-1.5 -right-2 z-20 whitespace-nowrap bg-indigo-950 text-white font-black text-[9px] uppercase tracking-wider px-2 py-0.5 rounded-full border-2 border-white shadow-md">
                {{ $todaysSchedules->count() }} {{ Str::plural('class', $todaysSchedules->count()) }}
            </span>

            <!-- Date Pill -->
            <div class="w-16 h-full bg-gradient-to-b from-pink-500 to-rose-600 text-white rounded-[24px] p-2.5 flex flex-col items-center justify-center text-center shadow-md">
                <span class="text-[9px] font-black uppercase tracking-widest text-pink-100">Today</span>
                <span class="text-2xl font-black mt-0.5 leading-none">{{ now()->format('d') }}</span>
                <span class="text-[9px] font-extrabold uppercase tracking-wide text-pink-200 mt-1">{{ now()->format('M') }}</span>
            </div>
        </div>

        <!-- Schedule Timeline Card (Simple Glassmorphism) -->
        <div class="flex-1 bg-white/70 backdrop-blur-md p-4 rounded-[24px] border border-white/80 shadow-sm flex flex-col justify-center space-y-2.5">

            <!-- Card Header -->
            <div class="flex items-center justify-between border-b border-stone-200/60 pb-2">
                <span class="text-[10px] font-black text-indigo-950 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="material-icons-round text-xs text-pink-500">event_note</span>
                    {{ now()->isoFormat('dddd') }} Timeline
                </span>
                <span class="w-2 h-2 rounded-full {{ $allTodayFinished ? 'bg-stone-300' : 'bg-emerald-500 animate-pulse' }}"></span>
            </div>

            @if($allTodayFinished)
                <!-- ALL CLASSES COMPLETED STATE -->
                <div class="space-y-2.5">
                    <!-- Finished Badge -->
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-black">
                            <span class="material-icons-round text-sm text-emerald-600">task_alt</span>
                            Classes finished for the day!
                        </span>
                    </div>

                    @if($nextClassSchedule)
                        @php
                            $nextStartTime = \Carbon\Carbon::parse($nextClassSchedule->start_time);
                            $nextEndTime = \Carbon\Carbon::parse($nextClassSchedule->end_time);
                            $nextTypeTag = $nextClassSchedule->type ?? null;
                            $nextIsExam = in_array(strtolower(trim($nextTypeTag)), ['prelims', 'midfinals', 'midterm', 'final', 'prelim', 'midterms', 'finals']);
                        @endphp
                        <div class="pt-1 flex items-center justify-between">
                            <div class="flex flex-col">
                                <div class="flex items-center space-x-1.5">
                                    <span class="text-[9px] font-black text-indigo-600 uppercase tracking-wider">
                                        Up Next ({{ $nextClassDayLabel }})
                                    </span>
                                    @if ($nextTypeTag)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-black {{ $nextIsExam ? 'bg-pink-100 text-pink-700' : 'bg-indigo-100 text-indigo-800' }} uppercase">
                                            {{ $nextTypeTag }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-xs font-black text-stone-800 mt-0.5">
                                    {{ $nextStartTime->format('g:i A') }} – {{ $nextEndTime->format('g:i A') }}
                                </span>
                                <span class="text-[10px] font-bold text-stone-500 mt-0.5">
                                    {{ $nextClassSchedule->subject->name ?? 'Class Session' }}
                                </span>
                            </div>

                            <!-- Room Code Pill -->
                            <span class="text-stone-700 font-black text-[10px] bg-white/90 px-2.5 py-1 rounded-xl border border-stone-200 shadow-sm">
                                {{ $nextClassSchedule->room ?? 'TBA' }}
                            </span>
                        </div>
                    @else
                        <p class="text-[10px] text-stone-400 font-bold">No upcoming classes found for this week.</p>
                    @endif
                </div>
            @else
                <!-- ACTIVE CLASS LIST STATE -->
                <div class="space-y-2">
                    @php
                        // Filter schedules to prevent duplicate time slots:
                        // Prioritize exam/term types (Prelims, Midterms, Finals) over regular lecture/lab if they share the same start time.
                        $uniqueSchedules = $todaysSchedules->groupBy(function ($item) {
                            return $item->subject_id . '-' . $item->start_time;
                        })->map(function ($group) {
                            if ($group->count() > 1) {
                                $examSchedule = $group->first(function ($item) {
                                    $t = strtolower(trim($item->type ?? ''));
                                    return in_array($t, ['prelims', 'midterms', 'finals', 'prelim', 'midterm', 'final']);
                                });
                                if ($examSchedule) {
                                    return $examSchedule;
                                }
                            }
                            return $group->first();
                        })->values()->take(2);
                    @endphp

                    @foreach($uniqueSchedules as $index => $schedule)
                        @php
                            $startTime = \Carbon\Carbon::parse($schedule->start_time);
                            $endTime = \Carbon\Carbon::parse($schedule->end_time);
                            $now = now();
                            $isInProgress = $now->between($startTime, $endTime);
                            $typeTag = $schedule->type ?? null;
                            $isExamType = in_array(strtolower(trim($typeTag)), ['prelims', 'midterms', 'finals', 'prelim', 'midterm', 'final']);
                        @endphp

                        <div class="flex items-center justify-between text-xs">
                            <div class="flex flex-col">
                                <div class="flex items-center space-x-1.5 flex-wrap gap-y-1">
                                    <span class="font-black text-stone-800">
                                        {{ $startTime->format('g:i A') }} – {{ $endTime->format('g:i A') }}
                                    </span>

                                    @if ($isInProgress)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[9px] font-black bg-emerald-100 text-emerald-700 border border-emerald-300 animate-pulse">
                                            ● In Progress
                                        </span>
                                    @endif

                                    @if ($typeTag)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[9px] font-extrabold {{ $isExamType ? 'bg-pink-100 text-pink-700 border border-pink-300' : 'bg-indigo-50 text-indigo-700 border border-indigo-200' }} uppercase tracking-wide">
                                            {{ $typeTag }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[10px] font-bold text-indigo-600 mt-0.5">{{ $schedule->subject->name ?? 'Class Session' }}</span>
                            </div>

                            <span class="text-stone-700 font-black text-[10px] bg-white/90 px-2.5 py-1 rounded-xl border border-stone-200 shadow-sm shrink-0">
                                {{ $schedule->room ?? 'TBA' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 4. CLICKABLE SUBJECTS FOR TODAY (Distinct Container vs Inner Cards)       -->
    <!-- ========================================================================= -->
    <div class="space-y-4">
        <!-- Minimalist Floating Header -->
        <div class="flex items-end justify-between px-1">
            <div>
                <h4 class="text-xs font-black text-[#1C1917] uppercase tracking-wider">Subjects For Today</h4>
                <p class="text-[10px] text-stone-500 mt-0.5 font-semibold">
                    Your active <span class="text-[#DB2777] font-black lowercase">{{ now()->isoFormat('dddd') }}</span> track
                </p>
            </div>
            <span class="text-[10px] font-extrabold text-[#DB2777] bg-gradient-to-tr from-pink-100 to-rose-50 px-2.5 py-1 rounded-lg border border-pink-200 shadow-sm">
                {{ $todaysSubjects->count() }} {{ Str::plural('Course', $todaysSubjects->count()) }}
            </span>
        </div>

        <!-- Subject Cards Stack -->
        <div class="space-y-3">
            @forelse($todaysSubjects as $subject)
                <a href="{{ route('subject.index') }}"
                    class="relative flex items-center justify-between p-4 rounded-[24px] bg-white border border-stone-100 shadow-sm hover:shadow-lg hover:-translate-y-0.5 hover:border-pink-200 transition-all duration-300 group overflow-hidden">

                    <!-- Ambient Glow inside the card for that premium glass feel -->
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-pink-50/80 rounded-full blur-xl group-hover:bg-pink-100 transition-colors duration-500"></div>

                    <div class="flex items-center space-x-4 relative z-10">
                        <!-- Icon Block -->
                        <div class="w-12 h-12 rounded-[16px] bg-gradient-to-br from-pink-50 to-rose-100 border border-pink-200/60 flex items-center justify-center text-[#DB2777] shadow-inner group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
                            <span class="material-icons-round text-xl">menu_book</span>
                        </div>

                        <!-- Text Info -->
                        <div class="flex flex-col">
                            <h5 class="text-sm font-black text-stone-800 group-hover:text-[#DB2777] transition-colors leading-tight">
                                {{ $subject->name }}
                            </h5>
                            <span class="text-[10px] font-extrabold text-stone-400 mt-1 tracking-widest uppercase">
                                Code: <span class="text-indigo-500">{{ $subject->code }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Action Button (Transforms on hover) -->
                    <div class="w-8 h-8 rounded-full bg-stone-50 flex items-center justify-center border border-stone-100 group-hover:bg-[#DB2777] group-hover:border-[#DB2777] group-hover:shadow-md transition-all duration-300 relative z-10">
                        <span class="material-icons-round text-stone-400 text-sm group-hover:text-white group-hover:translate-x-0.5 transition-all duration-300">arrow_forward</span>
                    </div>
                </a>
            @empty
                <!-- Empty State -->
                <div class="p-5 rounded-[24px] bg-stone-50/50 border-2 border-stone-200 border-dashed text-center flex flex-col items-center justify-center gap-2">
                    <span class="material-icons-round text-stone-300 text-2xl">event_busy</span>
                    <p class="text-xs text-stone-500 font-bold">No active courses listed for today.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
