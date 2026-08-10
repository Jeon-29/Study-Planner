@extends('layouts.app')

@section('content')
    <!-- Material Icons Link -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <div class="max-w-2xl mx-auto px-4 py-3 sm:px-6 lg:px-8 font-sans">

        <!-- Ultra-Compact Section Header with Live Term Indicator -->
        <div class="mb-5 pt-1 flex items-baseline justify-between">
            <div>
                <h1 class="text-2xl font-bold text-stone-900 tracking-tight">Academic Subjects</h1>
                <p class="text-stone-500 text-sm mt-1">Manage your course tracking streams</p>
            </div>

            <!-- Dynamic Term Badge -->
            <span
                class="px-2.5 py-1 rounded-full text-[0.65rem] font-extrabold uppercase tracking-wider
            {{ $currentFilter === '1st-sem' ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' : '' }}
            {{ $currentFilter === '2nd-sem' ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' : '' }}
            {{ $currentFilter === 'all' ? 'bg-stone-200/75 text-stone-700 border border-stone-300/60' : '' }}
            {{ $currentFilter === 'archived' ? 'bg-amber-100 text-amber-800 border border-amber-200' : '' }}">
                {{ str_replace('-', ' ', $currentFilter) }}
            </span>
        </div>

        <!-- ========================================================================= -->
        <!-- SEMESTER / ACADEMIC TERM FILTERS (Modern Minimalist Icon-Top Layout)      -->
        <!-- ========================================================================= -->
        <div class="flex items-center justify-between gap-2 mb-6 px-1">
            <!-- 'All Active' Tab -->
            <a href="{{ route('subject.index', ['filter' => 'all']) }}"
                class="flex-1 flex flex-col items-center justify-center py-2.5 px-2 rounded-2xl backdrop-blur-md transition-all duration-300 border
           {{ $currentFilter === 'all'
               ? 'bg-stone-900 text-white border-stone-900 shadow-md shadow-stone-900/20'
               : 'bg-white/70 text-stone-500 border-white/80 hover:bg-white/90 hover:text-stone-800' }}">
                <span class="material-icons-round text-lg mb-1">grid_view</span>
                <span class="text-[0.65rem] font-bold tracking-wider uppercase">All</span>
            </a>

            <!-- '1st Sem' Tab -->
            <a href="{{ route('subject.index', ['filter' => '1st-sem']) }}"
                class="flex-1 flex flex-col items-center justify-center py-2.5 px-2 rounded-2xl backdrop-blur-md transition-all duration-300 border
           {{ $currentFilter === '1st-sem'
               ? 'bg-indigo-600 text-white border-indigo-500 shadow-md shadow-indigo-600/20'
               : 'bg-white/70 text-stone-500 border-white/80 hover:bg-white/90 hover:text-stone-800' }}">
                <span class="material-icons-round text-lg mb-1">filter_1</span>
                <span class="text-[0.65rem] font-bold tracking-wider uppercase">1st Sem</span>
            </a>

            <!-- '2nd Sem' Tab -->
            <a href="{{ route('subject.index', ['filter' => '2nd-sem']) }}"
                class="flex-1 flex flex-col items-center justify-center py-2.5 px-2 rounded-2xl backdrop-blur-md transition-all duration-300 border
           {{ $currentFilter === '2nd-sem'
               ? 'bg-indigo-600 text-white border-indigo-500 shadow-md shadow-indigo-600/20'
               : 'bg-white/70 text-stone-500 border-white/80 hover:bg-white/90 hover:text-stone-800' }}">
                <span class="material-icons-round text-lg mb-1">filter_2</span>
                <span class="text-[0.65rem] font-bold tracking-wider uppercase">2nd Sem</span>
            </a>

            <!-- 'Archived' Tab -->
            <a href="{{ route('subject.index', ['filter' => 'archived']) }}"
                class="flex-1 flex flex-col items-center justify-center py-2.5 px-2 rounded-2xl backdrop-blur-md transition-all duration-300 border
           {{ $currentFilter === 'archived'
               ? 'bg-stone-700 text-white border-stone-600 shadow-md shadow-stone-700/20'
               : 'bg-white/70 text-stone-500 border-white/80 hover:bg-white/90 hover:text-stone-800' }}">
                <span class="material-icons-round text-lg mb-1">inventory_2</span>
                <span class="text-[0.65rem] font-bold tracking-wider uppercase">Archive</span>
            </a>
        </div>

        <!-- Stacked Clean Layout for Subjects -->
        <div class="flex flex-col gap-4 w-full box-border">

            @php
                // Utilizing Blade to define our core glassmorphism classes once, keeping the loop clean
                $baseGlassStyle =
                    'relative flex flex-col justify-between min-h-[120px] p-6 rounded-[24px] backdrop-blur-xl border border-white/40 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl box-border overflow-hidden';
            @endphp

            @forelse ($subjects as $subject)
                @php
                    $theme = $subject->color_theme ?? 'blue';

                    // Map themes to smooth Tailwind pastel gradients
                    $bgGradients = [
                        'yellow' => 'from-yellow-300/80 to-yellow-500/70',
                        'violet' => 'from-violet-300/80 to-violet-500/70',
                        'rose' => 'from-rose-300/80 to-rose-500/70',
                        'pink' => 'from-pink-300/80 to-pink-500/70',
                        'blue' => 'from-blue-300/80 to-blue-500/70',
                        'orange' => 'from-orange-300/80 to-orange-500/70',
                        'emerald' => 'from-emerald-300/80 to-emerald-500/70',
                        'green' => 'from-green-300/80 to-green-500/70',
                        'maroon' => 'from-rose-700/80 to-rose-900/70',
                        'red' => 'from-red-300/80 to-red-500/70',
                        'gray' => 'from-gray-300/80 to-gray-500/70',
                    ];
                    $gradientClass = $bgGradients[$theme] ?? 'from-blue-300/80 to-blue-500/70';
                @endphp

                <!-- Upward Hover Liquid Glass Card -->
                <div class="{{ $baseGlassStyle }} bg-gradient-to-br {{ $gradientClass }}">

                    <!-- Internal Ambient Highlight -->
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/20 rounded-full blur-2xl pointer-events-none">
                    </div>

                    <!-- ROW 1: Subject Title -->
                    <div class="flex w-full box-border relative z-10">
                        <h2 class="text-[1.35rem] font-bold text-white m-0 leading-tight drop-shadow-sm w-full break-words">
                            {{ $subject->name }}
                        </h2>
                    </div>

                    <!-- ROW 2: Code, Counter Stats & Isolated Floating Actions -->
                    <div class="flex justify-between items-end w-full mt-4 relative z-10">
                        <!-- Left Stats Stack -->
                        <div class="flex flex-col gap-1">
                            <span class="font-mono text-xs font-bold text-white/80 uppercase tracking-widest">
                                {{ $subject->code }}
                            </span>
                            <span class="text-sm font-semibold text-white/95 drop-shadow-sm">
                                {{ $subject->todos_count ?? 0 }} To-Dos
                            </span>
                        </div>

                        <!-- Right Action Management Shell -->
                        <div class="flex items-center gap-2">
                            <!-- Edit Button -->
                            <button type="button"
                                onclick="openEditModal({{ $subject->id }}, '{{ $subject->code }}', '{{ addslashes($subject->name) }}', '{{ $subject->semester }}', '{{ $theme }}')"
                                class="w-[34px] h-[34px] rounded-xl flex items-center justify-center text-white bg-white/20 border border-white/30 backdrop-blur-md cursor-pointer transition-colors duration-200 hover:bg-white/40 shadow-sm"
                                title="Edit Subject">
                                <i class="material-icons-round text-[1.1rem]">edit</i>
                            </button>

                            <!-- Archive / Restore Button -->
                            <form action="{{ route('subjects.archive', $subject->id) }}" method="POST" class="m-0">
                                @csrf
                                @method('PATCH')
                                <!-- Archive / Restore Button -->
                                <button type="button"
                                    onclick="openArchiveModal({{ $subject->id }}, '{{ addslashes($subject->name) }}', {{ $subject->is_archived ? 'true' : 'false' }})"
                                    class="w-[34px] h-[34px] rounded-xl flex items-center justify-center text-white bg-white/20 border border-white/30 backdrop-blur-md cursor-pointer transition-colors duration-200 hover:bg-amber-500/80 hover:border-amber-400 shadow-sm"
                                    title="{{ $subject->is_archived ? 'Restore Subject' : 'Archive Subject' }}">
                                    <i
                                        class="material-icons-round text-[1.1rem]">{{ $subject->is_archived ? 'unarchive' : 'archive' }}</i>
                                </button>
                            </form>

                            <!-- Delete Button -->
                            <button type="button"
                                onclick="openDeleteModal({{ $subject->id }}, '{{ addslashes($subject->name) }}')"
                                class="w-[34px] h-[34px] rounded-xl flex items-center justify-center text-white bg-white/20 border border-white/30 backdrop-blur-md cursor-pointer transition-colors duration-200 hover:bg-rose-500/80 hover:border-rose-400 shadow-sm"
                                title="Delete Subject">
                                <i class="material-icons-round text-[1.1rem]">delete</i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State if no subjects match the filter -->
                <div class="flex flex-col items-center justify-center py-10 text-stone-400">
                    <span class="material-icons-round text-4xl mb-2 opacity-50">search_off</span>
                    <p class="text-sm font-semibold">No subjects found for this filter.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- ================= MODERNISED LIQUID GLASS EDIT MODAL ================= -->
    <div id="editSubjectModal"
        class="hidden fixed inset-0 bg-stone-900/10 backdrop-blur-md z-[9999] items-end justify-center px-4 pb-24 transition-opacity duration-300 opacity-0">

        <div id="editModalCard"
            class="bg-white/80 backdrop-blur-2xl border border-white rounded-[2rem] w-full max-w-[384px] p-6 shadow-[0_24px_60px_rgba(0,0,0,0.12)] relative transform translate-y-8 transition-transform duration-300">

            <div class="flex items-start justify-between mb-5">
                <div>
                    <h3 class="m-0 text-sm font-bold text-stone-900 tracking-tight">Edit Course Subject</h3>
                    <p class="m-0 mt-1 text-[0.68rem] font-medium text-stone-500">Modify your dynamic core tracking cluster
                    </p>
                </div>
                <button type="button" onclick="closeEditModal()"
                    class="w-7 h-7 rounded-full bg-stone-100/50 border-none text-stone-500 cursor-pointer flex items-center justify-center transition-colors hover:bg-stone-200">
                    <span class="material-icons-round text-base">close</span>
                </button>
            </div>

            <form id="editForm" method="POST" class="flex flex-col gap-4">
                @csrf
                @method('PUT')

                <div>
                    <label
                        class="block text-[0.625rem] font-bold text-stone-500 mb-1.5 uppercase tracking-wider pl-0.5">Course
                        Code</label>
                    <input type="text" id="edit_code" name="code" required
                        class="w-full h-12 px-4 bg-stone-100/40 border border-stone-200/60 rounded-2xl text-xs font-semibold text-stone-900 outline-none uppercase tracking-wide focus:border-indigo-400 focus:bg-white/80 transition-colors">
                </div>

                <div>
                    <label
                        class="block text-[0.625rem] font-bold text-stone-500 mb-1.5 uppercase tracking-wider pl-0.5">Subject
                        Description</label>
                    <input type="text" id="edit_name" name="name" required
                        class="w-full h-12 px-4 bg-stone-100/40 border border-stone-200/60 rounded-2xl text-xs font-medium text-stone-900 outline-none focus:border-indigo-400 focus:bg-white/80 transition-colors">
                </div>

                <!-- Semester -->
                <div>
                    <label
                        class="block text-[0.625rem] font-bold text-stone-500 mb-1.5 uppercase tracking-wider pl-0.5">Academic
                        Semester</label>
                    <select id="edit_semester" name="semester" required
                        class="w-full h-12 px-4 bg-stone-100/40 border border-stone-200/60 rounded-2xl text-xs font-semibold text-stone-900 outline-none focus:border-indigo-400 focus:bg-white/80 transition-colors">
                        <option value="1st Sem">1st Semester</option>
                        <option value="2nd Sem">2nd Semester</option>
                    </select>
                </div>

                <!-- Modern Horizontal Color Strip Layer -->
                <div>
                    <label class="block text-[0.625rem] font-bold text-stone-500 mb-2 uppercase tracking-wider pl-0.5">Color
                        Theme Accent</label>
                    <input type="hidden" id="edit_color" name="color_theme" value="blue">

                    <div class="flex items-center gap-2.5 overflow-x-auto pb-2 pt-0.5 w-full hide-scrollbar">
                        @php
                            // Hex codes preserved here for the modal's active ring inline CSS logic
$colorHexes = [
    'yellow' => '#FDE047',
    'violet' => '#A78BFA',
    'rose' => '#FB7185',
    'pink' => '#F472B6',
    'blue' => '#60A5FA',
    'orange' => '#FB923C',
    'emerald' => '#34D399',
    'green' => '#4ADE80',
    'maroon' => '#9F1239',
    'red' => '#F87171',
    'gray' => '#9CA3AF',
                            ];
                        @endphp
                        @foreach ($colorHexes as $key => $hex)
                            <button type="button" onclick="selectColor('{{ $key }}')"
                                id="btn-color-{{ $key }}"
                                class="shrink-0 w-12 h-12 rounded-2xl border border-stone-200/60 bg-stone-100/20 flex items-center justify-center cursor-pointer transition-all duration-200"
                                style="--active-ring: {{ $hex }};">
                                <div class="w-6 h-6 rounded-full shadow-inner"
                                    style="background-color: {{ $hex }};"></div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="pt-2 grid grid-cols-2 gap-3 w-full">
                    <button type="button" onclick="closeEditModal()"
                        class="h-12 bg-white text-stone-600 border border-stone-200 rounded-2xl font-bold text-xs cursor-pointer transition-colors hover:bg-stone-50">Cancel</button>
                    <button type="submit"
                        class="h-12 bg-stone-900 text-white border-none rounded-2xl font-bold text-xs cursor-pointer transition-colors hover:bg-stone-800 shadow-lg shadow-stone-900/20">Save
                        Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= DELETE MODAL ================= -->
    <div id="deleteSubjectModal"
        class="hidden fixed inset-0 bg-stone-900/20 backdrop-blur-sm z-[9999] items-center justify-center p-4 transition-opacity duration-200 opacity-0">

        <div id="deleteModalCard"
            class="bg-white/95 backdrop-blur-xl border border-white/50 rounded-[28px] w-full max-w-[340px] p-6 shadow-2xl text-center transform scale-95 transition-transform duration-200">

            <h3 class="m-0 text-sm font-extrabold text-stone-900 uppercase tracking-wide">Delete Subject?</h3>
            <p class="mt-2 mb-6 text-stone-500 text-xs font-medium leading-relaxed">
                Remove <strong id="delete_target_name" class="text-stone-900 font-bold"></strong> permanently?
            </p>

            <form id="deleteForm" method="POST" class="m-0">
                @csrf
                @method('DELETE')
                <div class="flex gap-3 w-full">
                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 bg-white text-stone-600 border border-stone-200 p-3 rounded-full font-bold text-xs cursor-pointer hover:bg-stone-50 transition-colors">
                        Keep It
                    </button>
                    <button type="submit"
                        class="flex-1 bg-rose-600 text-white border-none p-3 rounded-full font-bold text-xs cursor-pointer transition-colors hover:bg-rose-700 shadow-md shadow-rose-600/20">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= ARCHIVE / RESTORE MODAL ================= -->
    <div id="archiveSubjectModal"
        class="hidden fixed inset-0 bg-stone-900/20 backdrop-blur-sm z-[9999] items-center justify-center p-4 transition-opacity duration-200 opacity-0">

        <div id="archiveModalCard"
            class="bg-white/95 backdrop-blur-xl border border-white/50 rounded-[28px] w-full max-w-[340px] p-6 shadow-2xl text-center transform scale-95 transition-transform duration-200">

            <h3 id="archive_modal_title" style="margin: 0;"
                class="text-sm font-extrabold text-stone-900 uppercase tracking-wide">Archive Subject?</h3>
            <p class="mt-2 mb-6 text-stone-500 text-xs font-medium leading-relaxed">
                <span id="archive_modal_desc">Move</span> <strong id="archive_target_name"
                    class="text-stone-900 font-bold"></strong> to archive?
            </p>

            <form id="archiveForm" method="POST" class="m-0">
                @csrf
                @method('PATCH')
                <div class="flex gap-3 w-full">
                    <button type="button" onclick="closeArchiveModal()"
                        class="flex-1 bg-white text-stone-600 border border-stone-200 p-3 rounded-full font-bold text-xs cursor-pointer hover:bg-stone-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="archive_submit_btn"
                        class="flex-1 bg-amber-600 text-white border-none p-3 rounded-full font-bold text-xs cursor-pointer transition-colors hover:bg-amber-700 shadow-md shadow-amber-600/20">
                        Archive
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add hide-scrollbar utility to your CSS or style block -->
    <style>
        .hide-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari and Opera */
        }
    </style>

    <!-- ================= JAVASCRIPT CONTROLLER ================= -->
    <script>
        const availableThemes = ['yellow', 'violet', 'rose', 'pink', 'blue', 'orange', 'emerald', 'green', 'maroon', 'red',
            'gray'
        ];

        function openEditModal(id, code, name, color) {
            document.getElementById('editForm').action = `/subjects/${id}`;
            document.getElementById('edit_code').value = code;
            document.getElementById('edit_name').value = name;

            selectColor(color || 'blue');

            const modal = document.getElementById('editSubjectModal');
            const card = document.getElementById('editModalCard');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Small timeout ensures transition frame updates correctly
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                card.classList.remove('translate-y-8');
                card.classList.add('translate-y-0');
            }, 10);
        }

        function closeEditModal() {
            const modal = document.getElementById('editSubjectModal');
            const card = document.getElementById('editModalCard');

            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            card.classList.remove('translate-y-0');
            card.classList.add('translate-y-8');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }

        function selectColor(colorName) {
            document.getElementById('edit_color').value = colorName;

            availableThemes.forEach(color => {
                const btn = document.getElementById(`btn-color-${color}`);
                if (btn) {
                    btn.style.boxShadow = 'none';
                    btn.style.backgroundColor = 'rgba(245, 245, 244, 0.2)';
                    btn.style.border = '1px solid rgba(231, 229, 228, 0.6)';
                }
            });

            const activeBtn = document.getElementById(`btn-color-${colorName}`);
            if (activeBtn) {
                activeBtn.style.backgroundColor = '#ffffff';
                activeBtn.style.boxShadow = '0 2px 8px rgba(0,0,0,0.04)';
                activeBtn.style.border = `2px solid ${activeBtn.style.getPropertyValue('--active-ring')}`;
            }
        }

        function openDeleteModal(id, name) {
            document.getElementById('deleteForm').action = `/subjects/${id}`;
            document.getElementById('delete_target_name').innerText = name;

            const modal = document.getElementById('deleteSubjectModal');
            const card = document.getElementById('deleteModalCard');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }, 10);
        }

        function openArchiveModal(id, name, isArchived) {
            document.getElementById('archiveForm').action = `/subjects/${id}/archive`;
            document.getElementById('archive_target_name').innerText = name;

            const titleEl = document.getElementById('archive_modal_title');
            const descEl = document.getElementById('archive_modal_desc');
            const submitBtn = document.getElementById('archive_submit_btn');

            if (isArchived) {
                titleEl.innerText = 'Restore Subject?';
                descEl.innerText = 'Restore';
                submitBtn.innerText = 'Restore';
                submitBtn.className =
                    'flex-1 bg-indigo-600 text-white border-none p-3 rounded-full font-bold text-xs cursor-pointer transition-colors hover:bg-indigo-700 shadow-md shadow-indigo-600/20';
            } else {
                titleEl.innerText = 'Archive Subject?';
                descEl.innerText = 'Move';
                submitBtn.innerText = 'Archive';
                submitBtn.className =
                    'flex-1 bg-amber-600 text-white border-none p-3 rounded-full font-bold text-xs cursor-pointer transition-colors hover:bg-amber-700 shadow-md shadow-amber-600/20';
            }

            const modal = document.getElementById('archiveSubjectModal');
            const card = document.getElementById('archiveModalCard');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }, 10);
        }

        function closeArchiveModal() {
            const modal = document.getElementById('archiveSubjectModal');
            const card = document.getElementById('archiveModalCard');

            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 200);
        }

        // Update your existing window.onclick listener to include the archive modal:
        window.onclick = function(e) {
            if (e.target === document.getElementById('editSubjectModal')) closeEditModal();
            if (e.target === document.getElementById('deleteSubjectModal')) closeDeleteModal();
            if (e.target === document.getElementById('archiveSubjectModal')) closeArchiveModal();
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteSubjectModal');
            const card = document.getElementById('deleteModalCard');

            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 200);
        }

        window.onclick = function(e) {
            if (e.target === document.getElementById('editSubjectModal')) closeEditModal();
            if (e.target === document.getElementById('deleteSubjectModal')) closeDeleteModal();
        }

        function openEditModal(id, code, name, semester, color) {
            document.getElementById('editForm').action = `/subjects/${id}`;
            document.getElementById('edit_code').value = code;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_semester').value = semester || '1st Sem';

            selectColor(color || 'blue');

            const modal = document.getElementById('editSubjectModal');
            const card = document.getElementById('editModalCard');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                card.classList.remove('translate-y-8');
                card.classList.add('translate-y-0');
            }, 10);
        }
    </script>
@endsection
