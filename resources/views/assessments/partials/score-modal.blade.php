<!-- Score Input Modal -->
<div id="score-modal" class="fixed inset-0 bg-stone-900/40 backdrop-blur-sm z-40 flex items-center justify-center px-4 hidden">
    <div class="absolute inset-0 pointer-events-auto" onclick="closeScoreModal()"></div>
    <div id="score-modal-card" class="relative z-50 bg-white w-full max-w-xs rounded-[32px] p-6 shadow-2xl transform transition-all scale-95 opacity-0 pointer-events-auto">

        <h3 class="text-lg font-black text-stone-800 mb-2 text-center">Exam/Quiz Complete!</h3>
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

<!-- Custom Styled Alert Modal -->
<div id="custom-alert-modal" class="fixed inset-0 bg-black/20 z-[60] flex items-center justify-center px-4 hidden">
    <div id="custom-alert-card" class="bg-white w-full max-w-sm rounded-3xl p-5 shadow-2xl transform transition-all scale-95 opacity-0">
        <p id="alert-score-text" class="text-stone-600 text-sm mb-4"></p>
        <p id="alert-message-text" class="text-stone-600 text-sm mb-8"></p>
        <div class="flex justify-end border-t border-stone-200 pt-3">
            <button type="button" onclick="confirmAlertAndSubmit()" class="text-[#DB2777] border border-[#DB2777] hover:bg-pink-50 px-4 py-1.5 rounded-lg text-sm transition-colors cursor-pointer">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    let pendingFormToSubmit = null;

    function openScoreModal(assessmentId, totalItems) {
        const modal = document.getElementById('score-modal');
        const card = document.getElementById('score-modal-card');
        const form = document.getElementById('score-form');

        form.action = `/assessments/${assessmentId}/mark-done`;

        document.getElementById('max_items').value = totalItems;
        document.getElementById('display_max_items').innerText = totalItems;
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
        pendingFormToSubmit = event.target; // Save the form reference

        const score = parseFloat(document.getElementById('earned_score').value);
        const max = parseFloat(document.getElementById('max_items').value);
        const percentage = (score / max) * 100;

        let message = "";
        if (percentage >= 90) {
            message = "Excellent work! You crushed it!";
        } else if (percentage >= 70) {
            message = "Good job! Solid passing score.";
        } else {
            message = "Keep studying, you'll get it next time!";
        }

        // Populate our new custom alert
        document.getElementById('alert-score-text').innerText = `You scored ${percentage.toFixed(0)}%`;
        document.getElementById('alert-message-text').innerText = message;

        // Show the custom alert modal
        const alertModal = document.getElementById('custom-alert-modal');
        const alertCard = document.getElementById('custom-alert-card');
        alertModal.classList.remove('hidden');
        setTimeout(() => {
            alertCard.classList.remove('scale-95', 'opacity-0');
            alertCard.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function confirmAlertAndSubmit() {
        // Submit the form to the server when they click "Close"
        if (pendingFormToSubmit) {
            pendingFormToSubmit.submit();
        }
    }
</script>
