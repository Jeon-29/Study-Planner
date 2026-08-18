<div id="score-modal" class="fixed inset-0 bg-stone-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="absolute inset-0 cursor-pointer" onclick="closeScoreModal()"></div>
    <div class="relative z-10 bg-white w-full max-w-xs rounded-[32px] p-6 shadow-2xl border border-white/80 text-center transform transition-all">

        <div class="w-12 h-12 rounded-2xl bg-pink-50 border border-pink-100 flex items-center justify-center text-[#DB2777] mx-auto mb-3 shadow-inner">
            <span class="material-icons-round text-xl">military_tech</span>
        </div>

        <h3 class="text-base font-black text-stone-900 tracking-tight mb-1">Complete Exam/Quiz</h3>
        <p class="text-xs text-stone-400 font-bold mb-5">Enter your score for this item</p>

        <form id="score-form" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div class="flex justify-center">
                <div class="relative w-40">
                    <input type="number" name="score" id="score-input" required min="0" placeholder="0"
                        class="w-full text-center py-3 rounded-2xl bg-stone-50 border border-stone-200 text-lg font-black text-stone-800 focus:outline-none focus:border-pink-500 transition-all">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-stone-400 pointer-events-none" id="total-items-label">/ 10</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <button type="button" onclick="closeScoreModal()" class="py-3 rounded-full border border-stone-200 text-stone-700 text-xs font-bold hover:bg-stone-50 transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="py-3 rounded-full bg-[#DB2777] hover:bg-[#BE185D] text-white text-xs font-extrabold shadow-lg shadow-pink-200 transition-all cursor-pointer">
                    Save Score
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openScoreModal(id, totalItems) {
        const modal = document.getElementById('score-modal');
        const form = document.getElementById('score-form');
        const totalLabel = document.getElementById('total-items-label');
        const scoreInput = document.getElementById('score-input');

        form.action = `/assessments/${id}/mark-as-done`;
        totalLabel.textContent = `/ ${totalItems}`;
        scoreInput.max = totalItems;
        scoreInput.value = '';

        modal.classList.remove('hidden');
    }

    function closeScoreModal() {
        document.getElementById('score-modal').classList.add('hidden');
    }
</script>
