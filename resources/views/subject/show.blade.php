@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-3 sm:px-6 lg:px-8 font-sans">

        @php
            $currentTab = request('tab', 'todos');
            $theme = $subject->color_theme ?? 'blue';

            // Map themes to smooth Tailwind pastel gradients matching index view
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

        <!-- Back Navigation & Header -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('subject.index') }}"
                class="inline-flex items-center gap-1 text-xs font-bold text-stone-500 hover:text-stone-900 transition-colors">
                <span class="material-icons-round text-base">arrow_back</span> Back to Subjects
            </a>
            <span
                class="px-3 py-1 rounded-full text-[0.65rem] font-extrabold uppercase tracking-wider bg-white/70 backdrop-blur-md text-stone-800 border border-stone-200/60 shadow-sm">
                {{ $subject->semester }}
            </span>
        </div>

        <!-- Subject Hero Glass Card (Dynamic Theme Color) -->
        <div
            class="p-6 rounded-[24px] bg-gradient-to-br {{ $gradientClass }} backdrop-blur-xl border border-white/40 shadow-xl text-white mb-6 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/20 rounded-full blur-2xl pointer-events-none"></div>

            <span
                class="font-mono text-xs font-bold text-white/80 uppercase tracking-widest drop-shadow-sm">{{ $subject->code }}</span>
            <h1 class="text-2xl font-bold mt-1 text-white drop-shadow-sm">{{ $subject->name }}</h1>

            @if ($subject->instructor_name)
                <div class="mt-4 pt-4 border-t border-white/20 text-xs font-medium flex flex-col gap-1 drop-shadow-sm">
                    <p><strong>Instructor:</strong> {{ $subject->instructor_name }}</p>
                    @if ($subject->instructor_email)
                        <p><strong>Email:</strong> {{ $subject->instructor_email }}</p>
                    @endif
                    @if ($subject->consultation_hours)
                        <p><strong>Consultation:</strong> {{ $subject->consultation_hours }}</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Horizontal Tabs (To-Do & Files) -->
        <div
            class="flex items-center gap-2 mb-6 bg-white/50 backdrop-blur-md p-1.5 rounded-2xl border border-stone-200/60 shadow-sm">
            <a href="{{ route('subject.show', ['id' => $subject->id, 'tab' => 'todos']) }}"
                class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-bold transition-all duration-300 {{ $currentTab === 'todos' ? 'bg-stone-900 text-white shadow-md' : 'text-stone-500 hover:text-stone-800 hover:bg-white/60' }}">
                <span class="material-icons-round text-base">checklist</span>
                To-Do
            </a>
            <a href="{{ route('subject.show', ['id' => $subject->id, 'tab' => 'files']) }}"
                class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-bold transition-all duration-300 {{ $currentTab === 'files' ? 'bg-stone-900 text-white shadow-md' : 'text-stone-500 hover:text-stone-800 hover:bg-white/60' }}">
                <span class="material-icons-round text-base">folder_open</span>
                Files
            </a>
        </div>

        <!-- Tab Contents -->
        @if ($currentTab === 'todos')
            <!-- To-Dos Section Header -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-stone-900 tracking-tight">Course To-Dos</h2>
            </div>

            <!-- To-Dos List Container -->
            <div class="flex flex-col gap-3">
                @forelse($subject->todos as $todo)
                    @php
                        $statusMap = [
                            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                            'done' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'overdue' => 'bg-rose-100 text-rose-800 border-rose-200',
                        ];
                        $todoStatus = $todo->status ?? 'pending';
                        $statusStyles = $statusMap[$todoStatus] ?? $statusMap['pending'];
                    @endphp

                    <div
                        class="p-4 bg-white/70 backdrop-blur-md border border-stone-200/60 rounded-2xl flex items-center justify-between shadow-sm">
                        <div>
                            <h3 class="text-xs font-bold text-stone-900 m-0">{{ $todo->title }}</h3>
                            <p class="text-[0.7rem] text-stone-500 m-0 mt-0.5">
                                {{ $todo->description ?? 'No additional details provided.' }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            @if (($todo->status ?? 'pending') !== 'done')
                                <form id="status-form-{{ $todo->id }}"
                                    action="{{ route('todos.update-status', $todo->id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="done">
                                    <button type="button"
                                        onclick="openStatusModal('status-form-{{ $todo->id }}', 'Mark as Done?', 'Are you sure you want to mark &quot;{{ $todo->title }}&quot; as completed?')"
                                        class="px-2.5 py-0.5 rounded-full text-[0.6rem] font-bold uppercase tracking-wider border {{ $statusStyles }} hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all cursor-pointer shadow-sm"
                                        title="Mark as Done">
                                        {{ ucfirst($todo->status ?? 'pending') }}
                                    </button>
                                </form>
                            @else
                                <form id="status-form-{{ $todo->id }}"
                                    action="{{ route('todos.update-status', $todo->id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="pending">
                                    <button type="button"
                                        onclick="openStatusModal('status-form-{{ $todo->id }}', 'Revert to Pending?', 'Are you sure you want to set &quot;{{ $todo->title }}&quot; back to pending?')"
                                        class="px-2.5 py-0.5 rounded-full text-[0.6rem] font-bold uppercase tracking-wider border {{ $statusStyles }} opacity-70 hover:opacity-100 transition-opacity cursor-pointer"
                                        title="Revert to Pending">
                                        <span class="flex items-center gap-1">
                                            <span class="material-icons-round text-[0.6rem]">check</span> Done
                                        </span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div
                        class="flex flex-col items-center justify-center py-10 text-stone-400 bg-white/40 backdrop-blur-md rounded-2xl border border-stone-200/60">
                        <span class="material-icons-round text-3xl mb-1 opacity-50">task_alt</span>
                        <p class="text-xs font-semibold">No to-dos created for this subject yet.</p>
                    </div>
                @endforelse
            </div>
        @else
            <!-- Files Section Header with Add File Button -->
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-stone-900 tracking-tight">Course Files & Documents</h2>
                    <p class="text-xs text-stone-500">Organized study materials and resources</p>
                </div>
                <button type="button" onclick="openFileModal()"
                    class="px-4 py-2 bg-stone-900 text-white rounded-2xl text-xs font-bold hover:bg-stone-800 transition-colors shadow-md shadow-stone-900/20 flex items-center gap-1 cursor-pointer">
                    <span class="material-icons-round text-sm">add</span> Add File
                </button>
            </div>

            <!-- Files List Container -->
            <div class="flex flex-col gap-3">
                @php
                    $files = $subject->files ?? [];
                @endphp

                @forelse($files as $file)
                    @php
                        $extension = strtolower(pathinfo($file->filename ?? $file->path, PATHINFO_EXTENSION));

                        $fileConfig = match (true) {
                            in_array($extension, ['pdf']) => [
                                'icon' => 'picture_as_pdf',
                                'color' => 'text-rose-600 bg-rose-50 border-rose-200',
                            ],
                            in_array($extension, ['doc', 'docx']) => [
                                'icon' => 'description',
                                'color' => 'text-blue-600 bg-blue-50 border-blue-200',
                            ],
                            in_array($extension, ['ppt', 'pptx']) => [
                                'icon' => 'slideshow',
                                'color' => 'text-orange-600 bg-orange-50 border-orange-200',
                            ],
                            in_array($extension, ['xls', 'xlsx', 'csv']) => [
                                'icon' => 'table_chart',
                                'color' => 'text-emerald-600 bg-emerald-50 border-emerald-200',
                            ],
                            default => [
                                'icon' => 'insert_drive_file',
                                'color' => 'text-stone-600 bg-stone-100 border-stone-200',
                            ],
                        };
                    @endphp

                    <div
                        class="p-4 bg-white/70 backdrop-blur-md border border-stone-200/60 rounded-2xl flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl border flex items-center justify-center shrink-0 {{ $fileConfig['color'] }}">
                                <span class="material-icons-round text-xl">{{ $fileConfig['icon'] }}</span>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-stone-900 m-0">{{ $file->title ?? $file->filename }}</h3>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span
                                        class="px-2 py-0.5 rounded-md text-[0.6rem] font-extrabold uppercase tracking-wider bg-stone-100 text-stone-600 border border-stone-200">
                                        {{ $file->category ?? 'General' }}
                                    </span>
                                    <span class="text-[0.68rem] text-stone-400">{{ strtoupper($extension) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- Download Button -->
                            <a href="{{ route('subject.files.download', $file->id) }}" target="_blank"
                                class="w-9 h-9 rounded-xl flex items-center justify-center text-stone-600 bg-stone-100/60 border border-stone-200/60 hover:bg-stone-900 hover:text-white transition-colors"
                                title="Download / View File">
                                <span class="material-icons-round text-base">download</span>
                            </a>

                            <!-- Delete Button Form -->
                            <form id="delete-form-{{ $file->id }}"
                                action="{{ route('subject.files.destroy', $file->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="openDeleteModal('delete-form-{{ $file->id }}')"
                                    class="w-9 h-9 rounded-xl flex items-center justify-center text-rose-600 bg-rose-50/60 border border-rose-200/60 hover:bg-rose-600 hover:text-white transition-colors cursor-pointer"
                                    title="Delete File">
                                    <span class="material-icons-round text-base">delete_outline</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div
                        class="flex flex-col items-center justify-center py-12 text-stone-400 bg-white/40 backdrop-blur-md rounded-2xl border border-stone-200/60">
                        <span class="material-icons-round text-3xl mb-1 opacity-50">folder_off</span>
                        <p class="text-xs font-semibold">No files uploaded for this subject yet.</p>
                        <p class="text-[0.7rem] text-stone-400 mt-0.5">Click 'Add File' to upload lecture notes, slides, or
                            guidelines.</p>
                    </div>
                @endforelse
            </div>
        @endif

    </div>

    <!-- ================= MODERNISED LIQUID GLASS ADD FILE MODAL ================= -->
    <div id="addFileModal"
        class="hidden fixed inset-0 bg-stone-900/10 backdrop-blur-md z-[9999] items-end justify-center px-4 pb-24 transition-opacity duration-300 opacity-0">

        <div id="fileModalCard"
            class="bg-white/85 backdrop-blur-2xl border border-white rounded-[2rem] w-full max-w-[384px] p-6 shadow-[0_24px_60px_rgba(0,0,0,0.12)] relative transform translate-y-8 transition-transform duration-300 max-h-[90vh] overflow-y-auto">

            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="m-0 text-sm font-bold text-stone-900 tracking-tight">Upload Course File</h3>
                    <p class="m-0 mt-1 text-[0.68rem] font-medium text-stone-500">Categorize and attach documents</p>
                </div>
                <button type="button" onclick="closeFileModal()"
                    class="w-7 h-7 rounded-full bg-stone-100/50 border-none text-stone-500 cursor-pointer flex items-center justify-center transition-colors hover:bg-stone-200">
                    <span class="material-icons-round text-base">close</span>
                </button>
            </div>

            <form id="addFileForm" action="{{ route('subject.files.store', $subject->id) }}" method="POST"
                enctype="multipart/form-data" class="flex flex-col gap-3">
                @csrf

                <div>
                    <label
                        class="block text-[0.625rem] font-bold text-stone-500 mb-1 uppercase tracking-wider pl-0.5">Document
                        Title</label>
                    <input type="text" name="title" required placeholder="e.g., Chapter 1 Lecture Notes"
                        class="w-full h-11 px-4 bg-stone-100/40 border border-stone-200/60 rounded-2xl text-xs font-medium text-stone-900 outline-none focus:border-indigo-400 focus:bg-white/80 transition-colors">
                </div>

                <div>
                    <label
                        class="block text-[0.625rem] font-bold text-stone-500 mb-1 uppercase tracking-wider pl-0.5">Category</label>
                    <select name="category" required
                        class="w-full h-11 px-4 bg-stone-100/40 border border-stone-200/60 rounded-2xl text-xs font-semibold text-stone-900 outline-none focus:border-indigo-400 focus:bg-white/80 transition-colors">
                        <option value="Lecture">Lecture Notes / PDF</option>
                        <option value="Presentation">Presentation (PPTX)</option>
                        <option value="Syllabus">Syllabus & Guidelines</option>
                        <option value="Reference">Reference Material / Reading</option>
                        <option value="Assignment">Assignment Guide</option>
                    </select>
                </div>

                <div>
                    <label
                        class="block text-[0.625rem] font-bold text-stone-500 mb-1 uppercase tracking-wider pl-0.5">Select
                        File (PDF, DOCX, PPTX)</label>
                    <input type="file" name="file" required accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx"
                        class="w-full py-2 px-3 bg-stone-100/40 border border-stone-200/60 rounded-2xl text-xs text-stone-600 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                </div>

                <!-- ================= UPLOAD PROGRESS BAR CONTAINER ================= -->
                <div id="uploadProgressContainer" class="hidden space-y-1.5 pt-2">
                    <div class="flex justify-between items-center text-[0.68rem] font-bold text-stone-600 px-0.5">
                        <span id="uploadStatusText">Uploading file...</span>
                        <span id="uploadPercentText" class="text-indigo-600 font-extrabold">0%</span>
                    </div>
                    <div
                        class="w-full h-2.5 bg-stone-100/80 rounded-full overflow-hidden border border-stone-200/60 p-0.5">
                        <div id="uploadProgressBar"
                            class="h-full bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 w-0 transition-all duration-150 ease-out rounded-full">
                        </div>
                    </div>
                </div>

                <div class="pt-2 grid grid-cols-2 gap-3 w-full">
                    <button type="button" onclick="closeFileModal()"
                        class="h-11 bg-white text-stone-600 border border-stone-200 rounded-2xl font-bold text-xs cursor-pointer transition-colors hover:bg-stone-50">Cancel</button>
                    <button type="submit" id="uploadSubmitBtn"
                        class="h-11 bg-stone-900 text-white border-none rounded-2xl font-bold text-xs cursor-pointer transition-colors hover:bg-stone-800 shadow-lg shadow-stone-900/20 flex items-center justify-center gap-1">
                        <span>Upload File</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Controller for File Modal with Progress Tracker -->
    <script>
        function openFileModal() {
            const modal = document.getElementById('addFileModal');
            const card = document.getElementById('fileModalCard');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                card.classList.remove('translate-y-8');
                card.classList.add('translate-y-0');
            }, 10);
        }

        function closeFileModal() {
            const modal = document.getElementById('addFileModal');
            const card = document.getElementById('fileModalCard');

            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            card.classList.remove('translate-y-0');
            card.classList.add('translate-y-8');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');

                // Reset progress bar on close
                const progressContainer = document.getElementById('uploadProgressContainer');
                const progressBar = document.getElementById('uploadProgressBar');
                const submitBtn = document.getElementById('uploadSubmitBtn');

                if (progressContainer) progressContainer.classList.add('hidden');
                if (progressBar) progressBar.style.width = '0%';
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }, 300);
        }

        window.addEventListener('click', function(e) {
            if (e.target === document.getElementById('addFileModal')) {
                closeFileModal();
            }
        });

        // AJAX Form Upload with Real-time Progress Tracking
        document.addEventListener('DOMContentLoaded', function() {
            const fileForm = document.getElementById('addFileForm');

            if (fileForm) {
                fileForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // Prevent standard page reload

                    const formData = new FormData(this);
                    const xhr = new XMLHttpRequest();

                    const progressContainer = document.getElementById('uploadProgressContainer');
                    const progressBar = document.getElementById('uploadProgressBar');
                    const percentText = document.getElementById('uploadPercentText');
                    const statusText = document.getElementById('uploadStatusText');
                    const submitBtn = document.getElementById('uploadSubmitBtn');

                    // Reveal progress bar UI & disable button
                    progressContainer.classList.remove('hidden');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

                    // Track byte transfer stream
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            const percent = Math.round((e.loaded / e.total) * 100);
                            progressBar.style.width = percent + '%';
                            percentText.innerText = percent + '%';

                            if (percent === 100) {
                                statusText.innerText = 'Syncing with Backblaze cloud...';
                            }
                        }
                    });

                    // Completion response
                    xhr.addEventListener('load', function() {
                        if (xhr.status >= 200 && xhr.status < 400) {
                            statusText.innerText = 'Upload complete!';
                            window.location.reload();
                        } else {
                            statusText.innerText = 'Upload failed!';
                            alert(
                                'Upload failed. Please check the file format or size and try again.'
                            );
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    });

                    xhr.open('POST', this.action, true);
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('Accept', 'application/json'); // ADD THIS LINE
                    xhr.send(formData);

                });
            }
        });
    </script>

    <!-- ================= MODERNISED LIQUID GLASS DELETE CONFIRMATION MODAL ================= -->
    <div id="deleteModal"
        class="hidden fixed inset-0 bg-stone-900/15 backdrop-blur-md z-[9999] items-center justify-center px-4 transition-opacity duration-300 opacity-0">

        <div id="deleteModalCard"
            class="bg-white/85 backdrop-blur-2xl border border-white rounded-[2rem] w-full max-w-[320px] p-6 shadow-[0_24px_60px_rgba(0,0,0,0.12)] text-center transform scale-95 transition-transform duration-300">

            <div
                class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-100">
                <span class="material-icons-round text-2xl">warning_amber</span>
            </div>

            <h3 class="m-0 text-sm font-bold text-stone-900 tracking-tight">Delete File?</h3>
            <p class="m-0 mt-1 text-[0.72rem] text-stone-500 font-medium">This action cannot be undone. The file will be
                permanently removed.</p>

            <div class="mt-6 grid grid-cols-2 gap-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="h-10 bg-white text-stone-600 border border-stone-200 rounded-2xl font-bold text-xs cursor-pointer transition-colors hover:bg-stone-50">Cancel</button>
                <button type="button" id="confirmDeleteBtn"
                    class="h-10 bg-rose-600 text-white border-none rounded-2xl font-bold text-xs cursor-pointer transition-colors hover:bg-rose-700 shadow-md shadow-rose-600/20">Delete</button>
            </div>
        </div>
    </div>

    <!-- Script Controller for Delete Modal -->
    <script>
        let activeFormId = null;

        function openDeleteModal(formId) {
            activeFormId = formId;
            const modal = document.getElementById('deleteModal');
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

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const card = document.getElementById('deleteModalCard');

            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                activeFormId = null;
            }, 300);
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (activeFormId) {
                document.getElementById(activeFormId).submit();
            }
        });

        window.addEventListener('click', function(e) {
            if (e.target === document.getElementById('deleteModal')) {
                closeDeleteModal();
            }
        });
    </script>

    <!-- ================= MODERNISED LIQUID GLASS STATUS CONFIRMATION MODAL ================= -->
    <div id="statusModal"
        class="hidden fixed inset-0 bg-stone-900/15 backdrop-blur-md z-[9999] items-center justify-center px-4 transition-opacity duration-300 opacity-0">

        <div id="statusModalCard"
            class="bg-white/85 backdrop-blur-2xl border border-white rounded-[2rem] w-full max-w-[320px] p-6 shadow-[0_24px_60px_rgba(0,0,0,0.12)] text-center transform scale-95 transition-transform duration-300">

            <div
                class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4 border border-emerald-100">
                <span class="material-icons-round text-2xl">task_alt</span>
            </div>

            <h3 id="statusModalTitle" class="m-0 text-sm font-bold text-stone-900 tracking-tight">Update Status?</h3>
            <p id="statusModalDesc" class="m-0 mt-1 text-[0.72rem] text-stone-500 font-medium">Are you sure you want to
                change this task status?</p>

            <div class="mt-6 grid grid-cols-2 gap-3">
                <button type="button" onclick="closeStatusModal()"
                    class="h-10 bg-white text-stone-600 border border-stone-200 rounded-2xl font-bold text-xs cursor-pointer transition-colors hover:bg-stone-50">Cancel</button>
                <button type="button" id="confirmStatusBtn"
                    class="h-10 bg-stone-900 text-white border-none rounded-2xl font-bold text-xs cursor-pointer transition-colors hover:bg-stone-800 shadow-md shadow-stone-900/20">Confirm</button>
            </div>
        </div>
    </div>

    <!-- Script Controller for Status Modal -->
    <script>
        let activeStatusFormId = null;

        function openStatusModal(formId, title, description) {
            activeStatusFormId = formId;
            document.getElementById('statusModalTitle').innerText = title;
            document.getElementById('statusModalDesc').innerText = description;

            const modal = document.getElementById('statusModal');
            const card = document.getElementById('statusModalCard');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }, 10);
        }

        function closeStatusModal() {
            const modal = document.getElementById('statusModal');
            const card = document.getElementById('statusModalCard');

            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                activeStatusFormId = null;
            }, 300);
        }

        document.getElementById('confirmStatusBtn').addEventListener('click', function() {
            if (activeStatusFormId) {
                document.getElementById(activeStatusFormId).submit();
            }
        });

        window.addEventListener('click', function(e) {
            if (e.target === document.getElementById('statusModal')) {
                closeStatusModal();
            }
        });


        xhr.addEventListener('load', function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                statusText.innerText = 'Upload complete!';
                window.location.reload();
            } else if (xhr.status === 422) {
                // This catches Laravel Validation Errors!
                const response = JSON.parse(xhr.responseText);
                console.error("Validation Failed:", response.errors);

                // Extract the first error message to show the user
                const firstError = Object.values(response.errors)[0][0];
                statusText.innerText = 'Validation Error!';
                alert('Upload rejected: ' + firstError);

                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                statusText.innerText = 'Upload failed!';
                alert('Server Error (' + xhr.status + '). Check console.');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    </script>
@endsection
