@if (auth()->check() && !auth()->user()->has_seen_guide)
    <div id="guideModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm ...">
        <div id="guide-modal"
            class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-stone-900/50 backdrop-blur-md">
            <div
                class="relative w-full max-w-md bg-white/90 backdrop-blur-2xl p-7 rounded-[2.5rem] shadow-2xl border border-white/80 transition-all duration-300">

                <!-- Header & Progress Dots -->
                <div class="flex items-center justify-between mb-6">
                    <span id="guide-step-indicator"
                        class="text-[10px] font-extrabold uppercase tracking-widest text-[#DB2777] bg-pink-100 px-3 py-1 rounded-full">
                        Step 1 of 5
                    </span>
                    <button onclick="finishGuide()"
                        class="text-xs font-bold text-[#78716C] hover:text-[#1C1917] transition cursor-pointer">
                        Skip Tour
                    </button>
                </div>

                <!-- SLIDE 1: Welcome -->
                <div id="guide-slide-1" class="guide-slide space-y-4">
                    <div
                        class="w-16 h-16 mx-auto rounded-2xl bg-pink-100 flex items-center justify-center text-[#DB2777]">
                        <span class="material-icons-round text-3xl">auto_awesome</span>
                    </div>
                    <div class="text-center space-y-1.5">
                        <h3 class="text-xl font-bold text-[#1C1917]">Welcome to StudyPlanner!</h3>
                        <p class="text-xs text-[#78716C] leading-relaxed">Your central workspace for organizing
                            coursework, monitoring deadlines, and keeping academic stress under control.</p>
                    </div>
                </div>

                <!-- SLIDE 2: Adding Tasks -->
                <div id="guide-slide-2" class="guide-slide hidden space-y-4">
                    <div
                        class="w-16 h-16 mx-auto rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-600">
                        <span class="material-icons-round text-3xl">add_task</span>
                    </div>
                    <div class="text-center space-y-1.5">
                        <h3 class="text-xl font-bold text-[#1C1917]">Adding Requirements</h3>
                        <p class="text-xs text-[#78716C] leading-relaxed">Click <strong class="text-[#1C1917]">+ New
                                Task</strong> to record assignments, projects, or exam reviews. Assign subjects, due
                            dates, and priority tags easily.</p>
                    </div>
                </div>

                <!-- SLIDE 3: Status Lifecycle -->
                <div id="guide-slide-3" class="guide-slide hidden space-y-4">
                    <div
                        class="w-16 h-16 mx-auto rounded-2xl bg-amber-100 flex items-center justify-center text-amber-600">
                        <span class="material-icons-round text-3xl">pending_actions</span>
                    </div>
                    <div class="text-center space-y-1.5">
                        <h3 class="text-xl font-bold text-[#1C1917]">Statuses & Auto-Overdue</h3>
                        <p class="text-xs text-[#78716C] leading-relaxed">Tasks automatically switch between three key
                            stages:</p>
                        <div class="grid grid-cols-3 gap-2 pt-2 text-[10px] font-bold">
                            <span
                                class="p-2 rounded-xl bg-amber-50 text-amber-700 border border-amber-200">Pending</span>
                            <span
                                class="p-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200">Done</span>
                            <span class="p-2 rounded-xl bg-rose-50 text-rose-700 border border-rose-200">Overdue</span>
                        </div>
                    </div>
                </div>

                <!-- SLIDE 4: Filtering & Organization -->
                <div id="guide-slide-4" class="guide-slide hidden space-y-4">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-sky-100 flex items-center justify-center text-sky-600">
                        <span class="material-icons-round text-3xl">filter_list</span>
                    </div>
                    <div class="text-center space-y-1.5">
                        <h3 class="text-xl font-bold text-[#1C1917]">Smart Filtering</h3>
                        <p class="text-xs text-[#78716C] leading-relaxed">Use status filters on your main board to
                            quickly isolate urgent deadlines, review completed tasks, or view items by specific subject
                            tags.</p>
                    </div>
                </div>

                <!-- SLIDE 5: Ready to Go -->
                <div id="guide-slide-5" class="guide-slide hidden space-y-4">
                    <div
                        class="w-16 h-16 mx-auto rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <span class="material-icons-round text-3xl">rocket_launch</span>
                    </div>
                    <div class="text-center space-y-1.5">
                        <h3 class="text-xl font-bold text-[#1C1917]">You're Ready!</h3>
                        <p class="text-xs text-[#78716C] leading-relaxed">You're all set up to start organizing your
                            semester. Create your first task to get started.</p>
                    </div>
                </div>

                <!-- Navigation Controls -->
                <div class="flex items-center justify-between gap-3 pt-6 border-t border-stone-100 mt-6">
                    <button id="guide-back-btn" onclick="prevSlide()"
                        class="invisible px-4 py-3 rounded-xl bg-stone-100 hover:bg-stone-200 text-[#1C1917] font-semibold text-xs transition cursor-pointer">
                        ← Back
                    </button>
                    <button id="guide-next-btn" onclick="nextSlide()"
                        class="flex-1 py-3.5 rounded-xl bg-[#1C1917] text-[#FFF5F5] font-semibold text-xs shadow-lg hover:bg-[#37312D] transition cursor-pointer text-center">
                        Next Step →
                    </button>
                </div>

            </div>
        </div>
    </div>
@endif
