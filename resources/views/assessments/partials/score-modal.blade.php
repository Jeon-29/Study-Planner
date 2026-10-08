<div id="score-modal" class="fixed inset-0 bg-stone-900/40 backdrop-blur-sm z-50 flex items-center justify-center px-4 hidden">
    <div class="absolute inset-0 pointer-events-auto" onclick="closeScoreModal()"></div>

    <!-- 1. SCORE INPUT CARD -->
    <div id="score-modal-card" class="relative z-50 bg-white w-full max-w-xs rounded-[32px] p-6 shadow-2xl transform transition-all duration-200 scale-95 opacity-0 pointer-events-auto">
        <h3 class="text-lg font-black text-stone-800 mb-2 text-center">Assessment Complete!</h3>
        <p class="text-xs text-stone-500 font-bold text-center mb-4">What was your score?</p>

        <form id="score-form" method="POST" onsubmit="handleScoreSubmit(event)">
            @csrf
            @method('PATCH')

            <input type="hidden" id="max_items" value="">

            <div class="flex items-center justify-center gap-2 mb-6">
                <input type="number" name="score" id="earned_score" required min="0" class="w-20 px-3 py-2 text-center rounded-xl bg-stone-50 border border-stone-200 text-lg font-black text-stone-800 focus:outline-none focus:border-[#DB2777]">
                <span class="text-stone-400 font-bold text-lg">/</span>
                <span id="display_max_items" class="text-stone-800 font-black text-lg">0</span>
            </div>

            <button type="submit" class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-extrabold rounded-xl shadow-lg shadow-emerald-200 transition-all cursor-pointer flex items-center justify-center gap-2">
                <span class="material-icons-round text-[16px]">check_circle</span>
                Confirm & Mark Done
            </button>
        </form>
    </div>

    <!-- 2. CUSTOM RESULT FEEDBACK MODAL (Replaces browser alert) -->
    <div id="score-result-card" class="relative z-50 bg-white w-full max-w-xs rounded-[32px] p-6 shadow-2xl text-center space-y-4 transform transition-all duration-200 scale-95 opacity-0 pointer-events-auto hidden">
        <div id="result-modal-icon-bg" class="w-12 h-12 rounded-2xl mx-auto flex items-center justify-center shadow-xs">
            <span id="result-modal-icon" class="material-icons-round text-2xl">emoji_events</span>
        </div>

        <div>
            <h3 id="result-modal-title" class="text-lg font-black text-stone-800 tracking-tight">You scored 0%</h3>
            <p id="result-modal-message" class="text-xs font-medium text-stone-500 mt-1">Keep studying, you'll get it next time!</p>
        </div>

        <div class="pt-2">
            <button type="button" onclick="submitScoreForm()" class="w-full bg-[#DB2777] hover:bg-[#BE185D] text-white text-xs font-extrabold py-3 rounded-xl shadow-md shadow-pink-200 transition-all cursor-pointer">
                Continue
            </button>
        </div>
    </div>
</div>

<script>
    function openScoreModal(assessmentId, totalItems) {
        const modal = document.getElementById('score-modal');
        const scoreCard = document.getElementById('score-modal-card');
        const resultCard = document.getElementById('score-result-card');
        const form = document.getElementById('score-form');

        // Dynamically point the form to the specific assessment's update route
        form.action = `/assessments/${assessmentId}/mark-done`;

        document.getElementById('max_items').value = totalItems;
        document.getElementById('display_max_items').innerText = totalItems;

        // Add dynamic max attribute to input to prevent HTML validation errors
        document.getElementById('earned_score').setAttribute('max', totalItems);
        document.getElementById('earned_score').value = '';

        // Reset view states
        resultCard.classList.add('hidden', 'scale-95', 'opacity-0');
        scoreCard.classList.remove('hidden');

        modal.classList.remove('hidden');
        setTimeout(() => {
            scoreCard.classList.remove('scale-95', 'opacity-0');
            scoreCard.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeScoreModal() {
        const modal = document.getElementById('score-modal');
        const scoreCard = document.getElementById('score-modal-card');
        const resultCard = document.getElementById('score-result-card');

        scoreCard.classList.remove('scale-100', 'opacity-100');
        scoreCard.classList.add('scale-95', 'opacity-0');

        resultCard.classList.remove('scale-100', 'opacity-100');
        resultCard.classList.add('scale-95', 'opacity-0');

        setTimeout(() => modal.classList.add('hidden'), 200);
    }

    function handleScoreSubmit(event) {
        event.preventDefault();

        const score = parseFloat(document.getElementById('earned_score').value);
        const max = parseFloat(document.getElementById('max_items').value);

        if (isNaN(score) || max <= 0) return;

        const percentage = Math.round((score / max) * 100);

        let message = "";
        const iconBg = document.getElementById('result-modal-icon-bg');
        const icon = document.getElementById('result-modal-icon');

        if (percentage >= 90) {
            message = "Excellent work! You crushed it!";
            iconBg.className = "w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl mx-auto flex items-center justify-center border border-amber-200/60 shadow-xs";
            icon.innerText = "emoji_events";
        } else if (percentage >= 75) {
            message = "Good job! Solid passing score.";
            iconBg.className = "w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl mx-auto flex items-center justify-center border border-emerald-200/60 shadow-xs";
            icon.innerText = "thumb_up";
        } else {
            message = "Keep studying, you'll get it next time!";
            iconBg.className = "w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl mx-auto flex items-center justify-center border border-rose-200/60 shadow-xs";
            icon.innerText = "menu_book";
        }

        document.getElementById('result-modal-title').innerText = `You scored ${percentage}%`;
        document.getElementById('result-modal-message').innerText = message;

        // Transition from input card to result card
        const scoreCard = document.getElementById('score-modal-card');
        const resultCard = document.getElementById('score-result-card');

        scoreCard.classList.remove('scale-100', 'opacity-100');
        scoreCard.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            scoreCard.classList.add('hidden');
            resultCard.classList.remove('hidden');

            setTimeout(() => {
                resultCard.classList.remove('scale-95', 'opacity-0');
                resultCard.classList.add('scale-100', 'opacity-100');
            }, 10);
        }, 200);
    }

    function submitScoreForm() {
        document.getElementById('score-form').submit();
    }
</script>
