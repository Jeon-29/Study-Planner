<div id="assessment-modal" class="fixed inset-0 bg-stone-900/40 backdrop-blur-sm z-40 flex items-end justify-center pb-32 px-4 hidden overflow-y-auto">
    <div class="absolute inset-0 cursor-pointer" onclick="closeExamQuizModal()"></div>
    <div id="assessment-modal-card" class="relative z-50 bg-white w-full max-w-sm max-h-[75vh] overflow-y-auto rounded-[32px] p-6 shadow-2xl border border-white/80 transform transition-all scale-95 opacity-0">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-black text-stone-800">Add Exam/Quiz</h3>
            <button type="button" onclick="closeExamQuizModal()" class="w-8 h-8 rounded-full bg-stone-100 flex items-center justify-center text-stone-400 hover:text-stone-600 cursor-pointer">
                <span class="material-icons-round text-sm">close</span>
            </button>
        </div>

        <form action="{{ route('assessments.store') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Title</label>
                <input type="text" name="title" required placeholder="e.g. Midterm Exam / Ch 1 Quiz" class="w-full px-3.5 py-2.5 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800 focus:outline-none focus:border-pink-500">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Subject</label>
                    <select name="subject_id" required class="w-full px-3.5 py-2.5 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800 focus:outline-none focus:border-pink-500">
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Type</label>
                    <select name="type" required class="w-full px-3 py-2.5 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800">
                        <option value="quiz">Quiz</option>
                        <option value="exam">Exam</option>
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

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Room</label>
                    <input type="text" name="room" placeholder="e.g. 302" class="w-full px-2.5 py-2 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-stone-400 mb-1">Total Items</label>
                    <input type="number" name="total_items" value="10" class="w-full px-2.5 py-2 rounded-xl bg-stone-50 border border-stone-200 text-xs font-bold text-stone-800">
                </div>
            </div>

            <button type="submit" class="w-full mt-2 py-3 bg-[#DB2777] hover:bg-[#BE185D] text-white text-xs font-extrabold rounded-xl shadow-lg shadow-pink-200 transition-all cursor-pointer">
                Save Task
            </button>
        </form>
    </div>
</div>

<script>
    if (typeof window.openExamQuizModal !== 'function') {
        window.openExamQuizModal = function() {
            const modal = document.getElementById('assessment-modal');
            const card = document.getElementById('assessment-modal-card');
            if (!modal || !card) return;
            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
    }

    if (typeof window.closeExamQuizModal !== 'function') {
        window.closeExamQuizModal = function() {
            const modal = document.getElementById('assessment-modal');
            const card = document.getElementById('assessment-modal-card');
            if (!modal || !card) return;
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    }
</script>
