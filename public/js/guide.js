let currentGuideStep = 1;
const totalGuideSteps = 5;

// Instant client-side execution to prevent back-button re-rendering
if (localStorage.getItem('study_planner_guide_completed') === 'true') {
    const modal = document.getElementById('guide-modal');
    if (modal) modal.remove();
}

function updateGuideUI() {
    document.querySelectorAll('.guide-slide').forEach((slide, idx) => {
        slide.classList.toggle('hidden', idx + 1 !== currentGuideStep);
    });

    document.getElementById('guide-step-indicator').innerText = `Step ${currentGuideStep} of ${totalGuideSteps}`;

    const backBtn = document.getElementById('guide-back-btn');
    const nextBtn = document.getElementById('guide-next-btn');

    // Toggle Back Button Visibility
    if (currentGuideStep === 1) {
        backBtn.classList.add('invisible');
    } else {
        backBtn.classList.remove('invisible');
    }

    // Toggle Next / Finish Button State
    if (currentGuideStep === totalGuideSteps) {
        nextBtn.innerText = "Get Started 🚀";
        nextBtn.className = "flex-1 py-3.5 rounded-xl bg-[#DB2777] text-white font-bold text-xs shadow-lg hover:bg-pink-700 transition cursor-pointer text-center";
        nextBtn.onclick = finishGuide;
    } else {
        nextBtn.innerText = "Next Step →";
        nextBtn.className = "flex-1 py-3.5 rounded-xl bg-[#1C1917] text-[#FFF5F5] font-semibold text-xs shadow-lg hover:bg-[#37312D] transition cursor-pointer text-center";
        nextBtn.onclick = nextSlide;
    }
}

function nextSlide() {
    if (currentGuideStep < totalGuideSteps) {
        currentGuideStep++;
        updateGuideUI();
    }
}

function prevSlide() {
    if (currentGuideStep > 1) {
        currentGuideStep--;
        updateGuideUI();
    }
}

function finishGuide() {
    // 1. Immediately store in browser memory
    localStorage.setItem('study_planner_guide_completed', 'true');

    // 2. Hide from UI immediately
    const modal = document.getElementById('guide-modal');
    if (modal) modal.remove();

    // 3. Persist state to Laravel backend
    fetch("{{ route('guide.complete') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json",
            "Accept": "application/json"
        }
    }).catch(err => console.error("Guide persistence error:", err));
}