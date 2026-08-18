@extends('layouts.app')

@section('content')
    <!-- 1. MINIMALIST PAGE HEADER & TABS NAVIGATION -->
    <div class="flex items-center justify-between mb-5 p-1">
        <div>
            <h1 class="text-lg font-bold text-[#1C1917] tracking-tight" id="main-page-title">Quiz & Exams</h1>
            <p class="text-xs font-medium text-[#78716C] mt-0.5" id="main-page-subtitle">Track your Exams/Quizzes</p>
        </div>

        <div class="flex items-center bg-stone-200/60 p-1 rounded-full border border-stone-300/50">
            <a href="{{ route('todo.index') }}" class="px-4 py-1.5 rounded-full text-xs font-bold text-stone-500 hover:text-stone-900 transition-all text-center">
                ToDo
            </a>
            <span class="px-4 py-1.5 rounded-full text-xs font-extrabold text-stone-900 bg-white shadow-sm transition-all text-center">
                Quiz/Exams
            </span>
        </div>
    </div>

    <div id="exam-quiz-main-content" class="space-y-6">
        <div class="px-4 pt-4 pb-32 max-w-md mx-auto relative">

            <div class="flex justify-between items-center mb-4 px-1">
                <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Exam / Quiz</span>
                <button type="button" onclick="openExamQuizModal()" class="bg-[#DB2777] hover:bg-[#BE185D] text-white text-xs font-bold px-4 py-2.5 rounded-full shadow-lg shadow-pink-200 transition-all active:scale-95 flex items-center gap-1.5">
                    <span class="material-icons-round text-sm">add</span>
                    <span>Add New</span>
                </button>
            </div>

            <!-- STAT CARDS (Unchanged) -->
            <div class="grid grid-cols-2 gap-3 mb-5">
                <div class="relative py-1.5 px-3 rounded-[20px] bg-white border border-stone-100 shadow-sm overflow-hidden group flex items-center gap-3">
                    <div class="absolute -right-4 -top-4 w-12 h-12 bg-amber-100/80 rounded-full blur-xl group-hover:bg-amber-200 transition-colors"></div>
                    <div class="relative z-10 w-7 h-7 rounded-lg bg-amber-50 border border-amber-200/60 flex items-center justify-center text-amber-600 shrink-0">
                        <span class="material-icons-round text-sm">quiz</span>
                    </div>
                    <div class="relative z-10 flex flex-col justify-center">
                        <span class="text-xl font-black text-stone-800 leading-tight">{{ $todayQuizzesCount }}</span>
                        <span class="text-[10px] font-bold text-stone-400">Quizzes Today</span>
                    </div>
                </div>

                <div class="relative py-1.5 px-3 rounded-[20px] bg-white border border-stone-100 shadow-sm overflow-hidden group flex items-center gap-3">
                    <div class="absolute -right-4 -top-4 w-12 h-12 bg-purple-100/80 rounded-full blur-xl group-hover:bg-purple-200 transition-colors"></div>
                    <div class="relative z-10 w-7 h-7 rounded-lg bg-purple-50 border border-purple-200/60 flex items-center justify-center text-purple-600 shrink-0">
                        <span class="material-icons-round text-sm">school</span>
                    </div>
                    <div class="relative z-10 flex flex-col justify-center">
                        <span class="text-xl font-black text-stone-800 leading-tight">{{ $todayExamsCount }}</span>
                        <span class="text-[10px] font-bold text-stone-400">Exams Today</span>
                    </div>
                </div>
            </div>

            <div class="flex p-1.5 bg-stone-100/80 rounded-full mb-6 border border-stone-200/50">
                <button type="button" id="main-tab-quiz" onclick="switchMainTab('quiz')" class="flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 bg-white text-stone-800 shadow-xs cursor-pointer">
                    Quizzes
                </button>
                <button type="button" id="main-tab-exam" onclick="switchMainTab('exam')" class="flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 text-stone-400 hover:text-stone-600 cursor-pointer">
                    Exams
                </button>
            </div>

            <!-- SECTION: QUIZZES -->
            <div id="section-quiz" class="space-y-4">
                <div class="flex items-center space-x-4 border-b border-stone-200 pb-2 px-1">
                    <button type="button" onclick="switchSubTab('quiz', 'upcoming')" id="subtab-quiz-upcoming" class="text-xs font-bold text-[#DB2777] border-b-2 border-[#DB2777] pb-1 transition-all cursor-pointer">Upcoming</button>
                    <button type="button" onclick="switchSubTab('quiz', 'finished')" id="subtab-quiz-finished" class="text-xs font-bold text-stone-400 hover:text-stone-600 pb-1 transition-all cursor-pointer">Finished</button>
                    <button type="button" onclick="switchSubTab('quiz', 'overdue')" id="subtab-quiz-overdue" class="text-xs font-bold text-stone-400 hover:text-stone-600 pb-1 transition-all cursor-pointer">Overdue</button>
                </div>

                @foreach (['upcoming', 'finished', 'overdue'] as $status)
                    <div id="quiz-list-{{ $status }}" class="quiz-sub-list space-y-3 {{ $status !== 'upcoming' ? 'hidden' : '' }}">
                        @forelse($quizzes->get($status, []) as $quiz)
                            @php
                                $themeColor = $quiz->subject->color ?? '#DB2777';
                                $themeBg = $themeColor . '1A'; // 10% opacity hex
                            @endphp
                            <div class="p-4 rounded-[24px] bg-white border border-stone-100 shadow-sm hover:shadow-md transition-all relative overflow-hidden">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-[10px] font-black tracking-wider uppercase px-2.5 py-1 rounded-full" style="color: {{ $themeColor }}; background-color: {{ $themeBg }};">
                                        {{ $quiz->subject->name ?? 'General Subject' }}
                                    </span>

                                    @if($status === 'finished')
                                        <span class="text-[10px] font-extrabold px-3 py-1 rounded-full uppercase text-emerald-600 bg-emerald-50 border border-emerald-200">
                                            FINISHED
                                        </span>
                                    @elseif($status === 'overdue')
                                        <span class="text-[10px] font-extrabold px-3 py-1 rounded-full uppercase text-rose-600 bg-rose-50 border border-rose-200">
                                            OVERDUE
                                        </span>
                                    @else
                                        <span class="text-[10px] font-extrabold px-3 py-1 rounded-full uppercase" style="color: {{ $themeColor }}; border: 1px solid {{ $themeColor }}40; background-color: white;">
                                            UPCOMING
                                        </span>
                                    @endif
                                </div>

                                <h4 class="text-[15px] font-black text-stone-800 mb-4">{{ $quiz->title }}</h4>

                                <div class="flex items-center justify-between pt-3 border-t border-stone-100/80">
                                    <div class="flex items-center gap-1.5 text-stone-500">
                                        <span class="material-icons-round text-[16px] opacity-70">schedule</span>
                                        <span class="text-[13px] font-bold">{{ $quiz->start_time ? \Carbon\Carbon::parse($quiz->start_time)->format('g:ia') : 'TBA' }}</span>
                                    </div>

                                    @if($status === 'upcoming')
                                        <button type="button" onclick="openScoreModal({{ $quiz->id }}, {{ $quiz->total_items }})" class="text-[11px] font-bold text-white px-4 py-1.5 rounded-full shadow-md transition-all flex items-center gap-1.5" style="background-color: {{ $themeColor }};">
                                            <span class="material-icons-round text-[14px]">check_circle</span>
                                            Mark as Done
                                        </button>
                                    @else
                                        <div class="flex items-center gap-1.5">
                                            <span class="material-icons-round text-[16px] text-[#F59E0B]">star</span>
                                            <span class="text-[13px] font-black text-stone-800">Score: {{ $quiz->score !== null ? $quiz->score . '/' . $quiz->total_items : 'N/A' }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-6 rounded-[24px] bg-stone-50 border-2 border-stone-200/60 border-dashed text-center">
                                <span class="material-icons-round text-stone-300 text-3xl mb-1">quiz</span>
                                <p class="text-xs text-stone-400 font-bold">No {{ $status }} quizzes found.</p>
                            </div>
                        @endforelse
                    </div>
                @endforeach
            </div>

            <!-- SECTION: EXAMS -->
            <div id="section-exam" class="space-y-4 hidden">
                <div class="flex items-center space-x-4 border-b border-stone-200 pb-2 px-1">
                    <button type="button" onclick="switchSubTab('exam', 'upcoming')" id="subtab-exam-upcoming" class="text-xs font-bold text-purple-600 border-b-2 border-purple-600 pb-1 transition-all cursor-pointer">Upcoming</button>
                    <button type="button" onclick="switchSubTab('exam', 'finished')" id="subtab-exam-finished" class="text-xs font-bold text-stone-400 hover:text-stone-600 pb-1 transition-all cursor-pointer">Finished</button>
                    <button type="button" onclick="switchSubTab('exam', 'overdue')" id="subtab-exam-overdue" class="text-xs font-bold text-stone-400 hover:text-stone-600 pb-1 transition-all cursor-pointer">Overdue</button>
                </div>

                @foreach (['upcoming', 'finished', 'overdue'] as $status)
                    <div id="exam-list-{{ $status }}" class="exam-sub-list space-y-3 {{ $status !== 'upcoming' ? 'hidden' : '' }}">
                        @forelse($exams->get($status, []) as $exam)
                            @php
                                $themeColor = $exam->subject->color ?? '#9333EA';
                                $themeBg = $themeColor . '1A';
                            @endphp
                            <div class="p-4 rounded-[24px] bg-white border border-stone-100 shadow-sm hover:shadow-md transition-all relative">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-[10px] font-black tracking-wider uppercase px-2.5 py-1 rounded-full" style="color: {{ $themeColor }}; background-color: {{ $themeBg }};">
                                        {{ $exam->subject->name ?? 'General Subject' }}
                                    </span>

                                    @if($status === 'finished')
                                        <span class="text-[10px] font-extrabold px-3 py-1 rounded-full uppercase text-emerald-600 bg-emerald-50 border border-emerald-200">
                                            FINISHED
                                        </span>
                                    @elseif($status === 'overdue')
                                        <span class="text-[10px] font-extrabold px-3 py-1 rounded-full uppercase text-rose-600 bg-rose-50 border border-rose-200">
                                            OVERDUE
                                        </span>
                                    @else
                                        <span class="text-[10px] font-extrabold px-3 py-1 rounded-full uppercase" style="color: {{ $themeColor }}; border: 1px solid {{ $themeColor }}40; background-color: white;">
                                            UPCOMING
                                        </span>
                                    @endif
                                </div>

                                <h4 class="text-[15px] font-black text-stone-800 mb-3">{{ $exam->title }}</h4>

                                <div class="grid grid-cols-3 gap-2 mb-4 p-3 rounded-2xl bg-stone-50 border border-stone-100 text-[11px]">
                                    <div class="flex flex-col">
                                        <span class="text-[9px] text-stone-400 font-bold uppercase mb-0.5">Date</span>
                                        <span class="font-extrabold text-stone-700">{{ \Carbon\Carbon::parse($exam->assessment_date)->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[9px] text-stone-400 font-bold uppercase mb-0.5">Room</span>
                                        <span class="font-extrabold text-stone-700">{{ $exam->room ?? 'TBA' }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[9px] text-stone-400 font-bold uppercase mb-0.5">Total Items</span>
                                        <span class="font-extrabold text-blue-600">{{ $exam->total_items }} Items</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-[13px] text-stone-400 font-medium">
                                        Time: {{ $exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->format('g:ia') : 'TBA' }}
                                    </span>

                                    @if($status === 'upcoming')
                                        <button type="button" onclick="openScoreModal({{ $exam->id }}, {{ $exam->total_items }})" class="text-[11px] font-bold text-white px-4 py-2 rounded-full shadow-md transition-all flex items-center gap-1.5" style="background-color: {{ $themeColor }};">
                                            <span class="material-icons-round text-[14px]">check_circle</span>
                                            Mark as Done
                                        </button>
                                    @else
                                        <div class="flex items-center gap-1.5">
                                            <span class="material-icons-round text-[16px] text-[#F59E0B]">star</span>
                                            <span class="text-[13px] font-black text-stone-800">Score: <span style="color: {{ $themeColor }};">{{ $exam->score !== null ? $exam->score . '/' . $exam->total_items : 'N/A' }}</span></span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-6 rounded-[24px] bg-stone-50 border-2 border-stone-200/60 border-dashed text-center">
                                <span class="material-icons-round text-stone-300 text-3xl mb-1">school</span>
                                <p class="text-xs text-stone-400 font-bold">No {{ $status }} exams found.</p>
                            </div>
                        @endforelse
                    </div>
                @endforeach
            </div>
        </div>

        @include('assessments.partials.add-modal')
        @include('assessments.partials.score-modal')
    </div>

    <script>
        if (typeof window.switchMainTab !== 'function') {
            window.switchMainTab = function(type) {
                const quizSec = document.getElementById('section-quiz');
                const examSec = document.getElementById('section-exam');
                const quizBtn = document.getElementById('main-tab-quiz');
                const examBtn = document.getElementById('main-tab-exam');

                if (!quizSec || !examSec || !quizBtn || !examBtn) return;

                if (type === 'quiz') {
                    quizSec.classList.remove('hidden');
                    examSec.classList.add('hidden');
                    quizBtn.className = "flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 bg-white text-stone-800 shadow-xs cursor-pointer";
                    examBtn.className = "flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 text-stone-400 hover:text-stone-600 cursor-pointer";
                } else {
                    examSec.classList.remove('hidden');
                    quizSec.classList.add('hidden');
                    examBtn.className = "flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 bg-white text-stone-800 shadow-xs cursor-pointer";
                    quizBtn.className = "flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 text-stone-400 hover:text-stone-600 cursor-pointer";
                }
            }
        }

        if (typeof window.switchSubTab !== 'function') {
            window.switchSubTab = function(type, status) {
                document.querySelectorAll(`.${type}-sub-list`).forEach(el => el.classList.add('hidden'));
                const targetList = document.getElementById(`${type}-list-${status}`);
                if (targetList) targetList.classList.remove('hidden');

                const highlightColor = type === 'quiz' ? 'text-[#DB2777] border-[#DB2777]' : 'text-purple-600 border-purple-600';

                ['upcoming', 'finished', 'overdue'].forEach(s => {
                    const btn = document.getElementById(`subtab-${type}-${s}`);
                    if (btn) {
                        if (s === status) {
                            btn.className = `text-xs font-bold ${highlightColor} border-b-2 pb-1 transition-all cursor-pointer`;
                        } else {
                            btn.className = "text-xs font-bold text-stone-400 hover:text-stone-600 pb-1 transition-all cursor-pointer";
                        }
                    }
                });
            }
        }
    </script>
@endsection
