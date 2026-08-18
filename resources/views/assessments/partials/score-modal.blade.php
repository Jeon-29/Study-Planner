<div id="score-modal" class="fixed inset-0 bg-stone-900/40 backdrop-blur-sm z-50 flex items-center justify-center px-4 hidden">
    <div class="absolute inset-0 pointer-events-auto" onclick="closeScoreModal()"></div>
    <div id="score-modal-card" class="relative z-50 bg-white w-full max-w-xs rounded-[32px] p-6 shadow-2xl transform transition-all scale-95 opacity-0 pointer-events-auto">

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
</div>

<script>
    function openScoreModal(assessmentId, totalItems) {
        const modal = document.getElementById('score-modal');
        const card = document.getElementById('score-modal-card');
        const form = document.getElementById('score-form');

        // Dynamically point the form to the specific assessment's update route
        form.action = `/assessments/${assessmentId}/mark-done`;

        document.getElementById('max_items').value = totalItems;
        document.getElementById('display_max_items').innerText = totalItems;

        // Add dynamic max attribute to input to prevent HTML validation errors
        document.getElementById('earned_score').setAttribute('max', totalItems);

        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeScoreModal() {
        const modal = document.getElementById('score-modal');
        const card = document.getElementById('score-modal-card');
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function handleScoreSubmit(event) {
        event.preventDefault();

        const score = parseFloat(document.getElementById('earned_score').value);
        const max = parseFloat(document.getElementById('max_items').value);
        const percentage = (score / max) * 100;

        let message = "";
        if (percentage >= 90) {
            message = "Excellent work! You crushed it!";
        } else if (percentage >= 75) {
            message = "Good job! Solid passing score.";
        } else {
            message = "Keep studying, you'll get it next time!";
        }

        alert(`You scored ${percentage.toFixed(0)}%\n\n${message}`);

        document.getElementById('score-form').submit();
    }
</script>
