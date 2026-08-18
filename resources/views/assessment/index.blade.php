@extends('layouts.app')

@section('content')
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
                <div class="absolute -right-4 -top-4 w-14 h-14 bg-amber-100/80 rounded-full blur-xl group-hover:bg-amber-200 transition-colors"></div>
                <div class="relative z-10 flex flex-col">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 border border-amber-200/60 flex items-center justify-center text-amber-600 mb-2">
                        <span class="material-icons-round text-base">quiz</span>
                    </div>
                    <span class="text-3xl font-black text-stone-800">{{ $todayQuizzes }}</span>
                    <span class="text-[11px] font-bold text-stone-400 mt-0.5">Quizzes Today</span>
                </div>
            </div>

            <div class="relative p-4 rounded-[24px] bg-white border border-stone-100 shadow-sm overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-14 h-14 bg-purple-100/80 rounded-full blur-xl group-hover:bg-purple-200 transition-colors"></div>
                <div class="relative z-10 flex flex-col">
                    <div class="w-8 h-8 rounded-xl bg-purple-50 border border-purple-200/60 flex items-center justify-center text-purple-600 mb-2">
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
                <div id="quiz-list-{{ $status }}" class="quiz-sub-list space-y-3 {{ $status !== 'upcoming' ? 'hidden' : '' }}">
                    @forelse($quizzes->get($status, []) as $quiz)
                        <div class="p-4 rounded-[24px] bg-white border border-stone-100 shadow-sm hover:shadow-md transition-all relative overflow-hidden">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="text-[10px] font-black tracking-wider text-pink-600 uppercase bg-pink-50 px-2 py-0.5 rounded-full border border-pink-100">
                                        {{ $quiz->subject->name ?? 'General Subject' }}
                                    </span>
                                    <h4 class="text-sm font-black text-stone-800 mt-1">{{ $quiz->title }}</h4>
                                </div>
                                <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase
                                    {{ $status === 'finished' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : ($status === 'overdue' ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-amber-50 text-amber-600 border border-amber-200') }}">
                                    {{ $status }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between pt-2 border-t border-stone-50 text-xs text-stone-500 font-semibold">
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-round text-sm text-stone-400">schedule</span>
                                    <span>{{ $quiz->start_time ? \Carbon\Carbon::parse($quiz->start_time)->format('g:ia') : 'TBA' }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-round text-sm text-amber-500">grade</span>
                                    <span class="font-bold text-stone-700">
                                        Score: {{ $quiz->score !== null ? $quiz->score . '/' . $quiz->total_items : 'Not Graded' }}
                                    </span>
                                </div>
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
                <div id="exam-list-{{ $status }}" class="exam-sub-list space-y-3 {{ $status !== 'upcoming' ? 'hidden' : '' }}">
                    @forelse($exams->get($status, []) as $exam)
                        <div class="p-4 rounded-[24px] bg-white border border-stone-100 shadow-sm hover:shadow-md transition-all relative">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="text-[10px] font-black tracking-wider text-purple-600 uppercase bg-purple-50 px-2 py-0.5 rounded-full border border-purple-100">
                                        {{ $exam->subject->name ?? 'General Subject' }}
                                    </span>
                                    <h4 class="text-sm font-black text-stone-800 mt-1">{{ $exam->title }}</h4>
                                </div>
                                <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase
                                    {{ $status === 'finished' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : ($status === 'overdue' ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-purple-50 text-purple-600 border border-purple-200') }}">
                                    {{ $status }}
                                </span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 my-3 p-2.5 rounded-xl bg-stone-50 border border-stone-100 text-[11px]">
                                <div class="flex flex-col">
                                    <span class="text-[9px] text-stone-400 font-bold uppercase">Date</span>
                                    <span class="font-extrabold text-stone-700">{{ \Carbon\Carbon::parse($exam->assessment_date)->format('M d, Y') }}</span>
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
                                    Time: {{ $exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->format('g:ia') : 'TBA' }}
                                </span>
                                <span class="font-black text-stone-800">
                                    Score: <span class="text-purple-600">{{ $exam->score !== null ? $exam->score . '/' . $exam->total_items : 'Pending' }}</span>
                                </span>
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

    <!-- FIXED MODAL POSITIONING (Clears Bottom Nav & Scrollable) -->
    <div id="assessment-modal" class="fixed inset-0 bg-stone-900/40 backdrop-blur-sm z-40 flex items-end justify-center pb-32 px-4 hidden overflow-y-auto">
        <div class="absolute inset-0 pointer-events-auto" onclick="closeAssessmentModal()"></div>
        <div id="assessment-modal-card" class="relative z-50 bg-white w-full max-w-sm max-h-[75vh] overflow-y-auto rounded-[32px] p-6 shadow-2xl border border-white/80 transform transition-all scale-95 opacity-0 pointer-events-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-black text-stone-800">Add Assessment</h3>
                <button onclick="closeAssessmentModal()" class="w-8 h-8 rounded-full bg-stone-100 flex items-center justify-center text-stone-400 hover:text-stone-600">
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
                    <select name="subject_id" required class="w-full px-3.5 py-2.5 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800 focus:outline-none focus:border-pink-500">
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Type</label>
                        <select name="type" required class="w-full px-3 py-2.5 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800">
                            <option value="quiz">Quiz</option>
                            <option value="exam">Exam</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Status</label>
                        <select name="status" required class="w-full px-3 py-2.5 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800">
                            <option value="upcoming">Upcoming</option>
                            <option value="finished">Finished</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Date</label>
                        <input type="date" name="assessment_date" required class="w-full px-3 py-2 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Time</label>
                        <input type="time" name="start_time" class="w-full px-3 py-2 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Room</label>
                        <input type="text" name="room" placeholder="e.g. 302" class="w-full px-2.5 py-2 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Total Items</label>
                        <input type="number" name="total_items" value="10" class="w-full px-2.5 py-2 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Score</label>
                        <input type="number" name="score" placeholder="Optional" class="w-full px-2.5 py-2 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800">
                    </div>
                </div>

                <button type="submit" class="w-full mt-2 py-3 bg-[#DB2777] hover:bg-[#BE185D] text-white text-xs font-extrabold rounded-xl shadow-lg shadow-pink-200 transition-all">
                    Save Assessment
                </button>
            </form>
        </div>
    </div>

    <script>
        function switchMainTab(type) {
            const quizSec = document.getElementById('section-quiz');
            const examSec = document.getElementById('section-exam');
            const quizBtn = document.getElementById('main-tab-quiz');
            const examBtn = document.getElementById('main-tab-exam');

            if (type === 'quiz') {
                quizSec.classList.remove('hidden');
                examSec.classList.add('hidden');
                quizBtn.className = "flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 bg-white text-stone-800 shadow-xs";
                examBtn.className = "flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 text-stone-400 hover:text-stone-600";
            } else {
                examSec.classList.remove('hidden');
                quizSec.classList.add('hidden');
                examBtn.className = "flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 bg-white text-stone-800 shadow-xs";
                quizBtn.className = "flex-1 py-2 rounded-full text-xs font-black transition-all duration-300 text-stone-400 hover:text-stone-600";
            }
        }

        function switchSubTab(type, status) {
            document.querySelectorAll(`.${type}-sub-list`).forEach(el => el.classList.add('hidden'));
            document.getElementById(`${type}-list-${status}`).classList.remove('hidden');

            const highlightColor = type === 'quiz' ? 'text-[#DB2777] border-[#DB2777]' : 'text-purple-600 border-purple-600';

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
    </script>
@endsection
