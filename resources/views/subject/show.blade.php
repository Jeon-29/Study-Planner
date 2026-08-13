@extends('layouts.app') {{-- Extends the primary layout blade template --}}

@section('content') {{-- Defines the 'content' section to be injected into the main layout --}}
    <div class="max-w-2xl mx-auto px-4 py-3 sm:px-6 lg:px-8 font-sans"> {{-- Container with responsive max-width, centering, padding, and sans font --}}

        @php
            // Start PHP code block for view logic
            $currentTab = request('tab', 'todos'); // Retrieves 'tab' query string parameter, defaulting to 'todos'
            $theme = $subject->color_theme ?? 'blue'; // Fallback to 'blue' theme if subject has no color_theme set

            // Map theme names to Tailwind CSS pastel gradient utility classes
            $bgGradients = [ // Array mapping color keys to Tailwind CSS gradient classes
                'yellow' => 'from-yellow-300/80 to-yellow-500/70', // Yellow gradient class string
                'violet' => 'from-violet-300/80 to-violet-500/70', // Violet gradient class string
                'rose' => 'from-rose-300/80 to-rose-500/70', // Rose gradient class string
                'pink' => 'from-pink-300/80 to-pink-500/70', // Pink gradient class string
                'blue' => 'from-blue-300/80 to-blue-500/70', // Blue gradient class string
                'orange' => 'from-orange-300/80 to-orange-500/70', // Orange gradient class string
                'emerald' => 'from-emerald-300/80 to-emerald-500/70', // Emerald gradient class string
                'green' => 'from-green-300/80 to-green-500/70', // Green gradient class string
                'maroon' => 'from-rose-700/80 to-rose-900/70', // Maroon gradient class string
                'red' => 'from-red-300/80 to-red-500/70', // Red gradient class string
                'gray' => 'from-gray-300/80 to-gray-500/70', // Gray gradient class string
            ]; // End of gradient lookup array
            $gradientClass = $bgGradients[$theme] ?? 'from-blue-300/80 to-blue-500/70'; // Select active gradient or default to blue
        @endphp

        <!-- Back Navigation & Header --> {{-- Header wrapper section --}}
        <div class="mb-6 flex items-center justify-between"> {{-- Flex row wrapper with margin bottom --}}
            <a href="{{ route('subject.index') }}" {{-- Link redirecting back to subjects index route --}}
                class="inline-flex items-center gap-1 text-xs font-bold text-stone-500 hover:text-stone-900 transition-colors"> {{-- Styled back button --}}
                <span class="material-icons-round text-base">arrow_back</span> Back to Subjects {{-- Icon and text for back navigation --}}
            </a> {{-- Close back link --}}
            <span {{-- Semester badge element --}}
                class="px-3 py-1 rounded-full text-[0.65rem] font-extrabold uppercase tracking-wider bg-white/70 backdrop-blur-md text-stone-800 border border-stone-200/60 shadow-sm"> {{-- Pill styling with glassmorphism --}}
                {{ $subject->semester }} {{-- Displays dynamic semester string --}}
            </span> {{-- Close semester badge --}}
        </div> {{-- Close header flex row --}}

        <!-- Subject Hero Glass Card (Dynamic Theme Color) --> {{-- Card banner section --}}
        <div {{-- Hero card container --}}
            class="p-6 rounded-[24px] bg-gradient-to-br {{ $gradientClass }} backdrop-blur-xl border border-white/40 shadow-xl text-white mb-6 relative overflow-hidden"> {{-- Glassmorphic styling with dynamic background gradient --}}
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/20 rounded-full blur-2xl pointer-events-none"></div> {{-- Decorative ambient glow shape --}}

            <span {{-- Subject code tag --}}
                class="font-mono text-xs font-bold text-white/80 uppercase tracking-widest drop-shadow-sm">{{ $subject->code }}</span> {{-- Renders subject code in uppercase --}}
            <h1 class="text-2xl font-bold mt-1 text-white drop-shadow-sm">{{ $subject->name }}</h1> {{-- Renders subject name heading --}}

            @if ($subject->instructor_name) {{-- Checks if instructor name exists --}}
                <div class="mt-4 pt-4 border-t border-white/20 text-xs font-medium flex flex-col gap-1 drop-shadow-sm"> {{-- Instructor details container --}}
                    <p><strong>Instructor:</strong> {{ $subject->instructor_name }}</p {{-- Displays instructor name --}}
                    @if ($subject->instructor_email) {{-- Checks if instructor email exists --}}
                        <p><strong>Email:</strong> {{ $subject->instructor_email }}</p> {{-- Displays instructor email --}}
                    @endif {{-- End email condition --}}
                    @if ($subject->consultation_hours) {{-- Checks if consultation hours exist --}}
                        <p><strong>Consultation:</strong> {{ $subject->consultation_hours }}</p> {{-- Displays consultation hours --}}
                    @endif {{-- End consultation condition --}}
                </div> {{-- Close instructor details container --}}
            @endif {{-- End instructor condition --}}
        </div> {{-- Close hero card --}}

        <!-- Horizontal Tabs (To-Do & Files) --> {{-- Tab navigation wrapper --}}
        <div {{-- Tab bar container --}}
            class="flex items-center gap-2 mb-6 bg-white/50 backdrop-blur-md p-1.5 rounded-2xl border border-stone-200/60 shadow-sm"> {{-- Glassmorphic tab bar container --}}
            <a href="{{ route('subject.show', ['id' => $subject->id, 'tab' => 'todos']) }}" {{-- Link to To-Dos tab --}}
                class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-bold transition-all duration-300 {{ $currentTab === 'todos' ? 'bg-stone-900 text-white shadow-md' : 'text-stone-500 hover:text-stone-800 hover:bg-white/60' }}"> {{-- Conditional active tab styles --}}
                <span class="material-icons-round text-base">checklist</span> {{-- To-Do tab icon --}}
                To-Do {{-- To-Do tab label --}}
            </a> {{-- Close To-Do tab link --}}
            <a href="{{ route('subject.show', ['id' => $subject->id, 'tab' => 'files']) }}" {{-- Link to Files tab --}}
                class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-bold transition-all duration-300 {{ $currentTab === 'files' ? 'bg-stone-900 text-white shadow-md' : 'text-stone-500 hover:text-stone-800 hover:bg-white/60' }}"> {{-- Conditional active tab styles --}}
                <span class="material-icons-round text-base">folder_open</span> {{-- Files tab icon --}}
                Files {{-- Files tab label --}}
            </a> {{-- Close Files tab link --}}
        </div> {{-- Close tab bar container --}}

        <!-- Tab Contents --> {{-- Main tab body container --}}
        @if ($currentTab === 'todos') {{-- Check if 'todos' tab is active --}}
            <!-- To-Dos Section Header --> {{-- To-Do section title block --}}
            <div class="flex items-center justify-between mb-4"> {{-- Header flex row --}}
                <h2 class="text-lg font-bold text-stone-900 tracking-tight">Course To-Dos</h2> {{-- Section heading --}}
            </div> {{-- Close title block --}}

            <!-- To-Dos List Container --> {{-- Container for task items --}}
            <div class="flex flex-col gap-3"> {{-- Vertical flex column for task cards --}}
                @forelse($subject->todos as $todo) {{-- Loop through each subject task --}}
                    @php
                        // Start task status styling logic
                        $statusMap = [ // Map of task status values to Tailwind CSS styles
                            'pending' => 'bg-amber-100 text-amber-800 border-amber-200', // Styling for pending tasks
                            'done' => 'bg-emerald-100 text-emerald-800 border-emerald-200', // Styling for completed tasks
                            'overdue' => 'bg-rose-100 text-rose-800 border-rose-200', // Styling for overdue tasks
                        ]; // End status mapping array
                        $todoStatus = $todo->status ?? 'pending'; // Fallback task status to 'pending'
                        $statusStyles = $statusMap[$todoStatus] ?? $statusMap['pending']; // Choose status CSS class
                    @endphp

                    <div {{-- Task card element --}}
                        class="p-4 bg-white/70 backdrop-blur-md border border-stone-200/60 rounded-2xl flex items-center justify-between shadow-sm"> {{-- Glassmorphic card styling --}}
                        <div> {{-- Task text info container --}}
                            <h3 class="text-xs font-bold text-stone-900 m-0">{{ $todo->title }}</h3> {{-- Task title --}}
                            <p class="text-[0.7rem] text-stone-500 m-0 mt-0.5"> {{-- Task description line --}}
                                {{ $todo->description ?? 'No additional details provided.' }}</p> {{-- Renders description or fallback --}}
                        </div> {{-- Close text info container --}}

                        <div class="flex items-center gap-3"> {{-- Task action button container --}}
                            @if (($todo->status ?? 'pending') !== 'done') {{-- Check if task is incomplete --}}
                                <form id="status-form-{{ $todo->id }}" {{-- Form to update task status to done --}}
                                    action="{{ route('todos.update-status', $todo->id) }}" method="POST" class="m-0"> {{-- POST request endpoint --}}
                                    @csrf {{-- CSRF protection token --}}
                                    @method('PATCH') {{-- HTTP PATCH override --}}
                                    <input type="hidden" name="status" value="done"> {{-- Hidden field passing new status value --}}
                                    <button type="button" {{-- Modal trigger button --}}
                                        onclick="openStatusModal('status-form-{{ $todo->id }}', 'Mark as Done?', 'Are you sure you want to mark &quot;{{ $todo->title }}&quot; as completed?')" {{-- Triggers status confirmation modal --}}
                                        class="px-2.5 py-0.5 rounded-full text-[0.6rem] font-bold uppercase tracking-wider border {{ $statusStyles }} hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all cursor-pointer shadow-sm" {{-- Button styles --}}
                                        title="Mark as Done"> {{-- Tooltip title --}}
                                        {{ ucfirst($todo->status ?? 'pending') }} {{-- Dynamic capitalized status text --}}
                                    </button> {{-- Close mark done button --}}
                                </form> {{-- Close mark done form --}}
                            @else {{-- Executed when task status is already done --}}
                                <form id="status-form-{{ $todo->id }}" {{-- Form to revert task status to pending --}}
                                    action="{{ route('todos.update-status', $todo->id) }}" method="POST" class="m-0"> {{-- POST request endpoint --}}
                                    @csrf {{-- CSRF protection token --}}
                                    @method('PATCH') {{-- HTTP PATCH override --}}
                                    <input type="hidden" name="status" value="pending"> {{-- Hidden field passing revert status value --}}
                                    <button type="button" {{-- Modal trigger button --}}
                                        onclick="openStatusModal('status-form-{{ $todo->id }}', 'Revert to Pending?', 'Are you sure you want to set &quot;{{ $todo->title }}&quot; back to pending?')" {{-- Triggers revert confirmation modal --}}
                                        class="px-2.5 py-0.5 rounded-full text-[0.6rem] font-bold uppercase tracking-wider border {{ $statusStyles }} opacity-70 hover:opacity-100 transition-opacity cursor-pointer" {{-- Button styles --}}
                                        title="Revert to Pending"> {{-- Tooltip title --}}
                                        <span class="flex items-center gap-1"> {{-- Inline flex icon container --}}
                                            <span class="material-icons-round text-[0.6rem]">check</span> Done {{-- Check icon and 'Done' text --}}
                                        </span> {{-- Close inline icon container --}}
                                    </button {{-- Close revert button --}}
                                </form> {{-- Close revert form --}}
                            @endif {{-- End task status conditional --}}
                        </div> {{-- Close action button container --}}
                    </div> {{-- Close task card element --}}
                @empty {{-- Executed when no task records exist --}}
                    <div {{-- Empty state container --}}
                        class="flex flex-col items-center justify-center py-10 text-stone-400 bg-white/40 backdrop-blur-md rounded-2xl border border-stone-200/60"> {{-- Glassmorphic empty card styling --}}
                        <span class="material-icons-round text-3xl mb-1 opacity-50">task_alt</span> {{-- Empty state icon --}}
                        <p class="text-xs font-semibold">No to-dos created for this subject yet.</p> {{-- Empty state message --}}
                    </div> {{-- Close empty state container --}}
                @endforelse {{-- End task loop --}}
            </div> {{-- Close task list container --}}
        @else {{-- Executed when 'files' tab is active --}}
            <!-- Files Section Header with Add File Button --> {{-- File section header block --}}
            <div class="flex items-center justify-between mb-4"> {{-- Header flex row --}}
                <div> {{-- Text header container --}}
                    <h2 class="text-lg font-bold text-stone-900 tracking-tight">Course Files & Documents</h2> {{-- Files section heading --}}
                    <p class="text-xs text-stone-500">Organized study materials and resources</p {{-- Files section subtitle --}}
                </div> {{-- Close text header container --}}
                <button type="button" onclick="openFileModal()" {{-- Button to open file upload modal --}}
                    class="px-4 py-2 bg-stone-900 text-white rounded-2xl text-xs font-bold hover:bg-stone-800 transition-colors shadow-md shadow-stone-900/20 flex items-center gap-1 cursor-pointer"> {{-- Button styling --}}
                    <span class="material-icons-round text-sm">add</span> Add File {{-- Add icon and button label --}}
                </button> {{-- Close add file button --}}
            </div> {{-- Close file header flex row --}}

            <!-- Files List Container --> {{-- Container for file list cards --}}
            <div class="flex flex-col gap-3"> {{-- Vertical flex column --}}
                @php
                    // Start file collection initialization
                    $files = $subject->files ?? []; // Fallback to empty array if no files attached
                @endphp

                @forelse($files as $file) {{-- Loop through attached file records --}}
                    @php
                        // Start file type configuration logic
                        $extension = strtolower(pathinfo($file->filename ?? $file->path, PATHINFO_EXTENSION)); // Extract file extension in lowercase

                        $fileConfig = match (true) { // Match file extension to icon and color scheme
                            in_array($extension, ['pdf']) => [ // PDF format rule
                                'icon' => 'picture_as_pdf', // PDF icon identifier
                                'color' => 'text-rose-600 bg-rose-50 border-rose-200', // Red/Rose theme for PDFs
                            ],
                            in_array($extension, ['doc', 'docx']) => [ // Word document format rule
                                'icon' => 'description', // Document icon identifier
                                'color' => 'text-blue-600 bg-blue-50 border-blue-200', // Blue theme for Word docs
                            ],
                            in_array($extension, ['ppt', 'pptx']) => [ // Presentation format rule
                                'icon' => 'slideshow', // Slideshow icon identifier
                                'color' => 'text-orange-600 bg-orange-50 border-orange-200', // Orange theme for PowerPoint
                            ],
                            in_array($extension, ['xls', 'xlsx', 'csv']) => [ // Spreadsheet format rule
                                'icon' => 'table_chart', // Table icon identifier
                                'color' => 'text-emerald-600 bg-emerald-50 border-emerald-200', // Emerald theme for Excel
                            ],
                            default => [ // Default fallback rule for other file types
                                'icon' => 'insert_drive_file', // Generic file icon identifier
                                'color' => 'text-stone-600 bg-stone-100 border-stone-200', // Neutral stone theme
                            ],
                        }; // End match expression
                    @endphp

                    <div {{-- File item card --}}
                        class="p-4 bg-white/70 backdrop-blur-md border border-stone-200/60 rounded-2xl flex items-center justify-between shadow-sm"> {{-- Card container styling --}}
                        <div class="flex items-center gap-3"> {{-- Left metadata flex row --}}
                            <div {{-- Icon box container --}}
                                class="w-10 h-10 rounded-xl border flex items-center justify-center shrink-0 {{ $fileConfig['color'] }}"> {{-- Dynamic styling based on extension --}}
                                <span class="material-icons-round text-xl">{{ $fileConfig['icon'] }}</span> {{-- Dynamic icon --}}
                            </div> {{-- Close icon box --}}
                            <div> {{-- Title and category container --}}
                                <h3 class="text-xs font-bold text-stone-900 m-0">{{ $file->title ?? $file->filename }}</h3> {{-- File title or filename --}}
                                <div class="flex items-center gap-2 mt-0.5"> {{-- Metadata badge row --}}
                                    <span {{-- Category badge --}}
                                        class="px-2 py-0.5 rounded-md text-[0.6rem] font-extrabold uppercase tracking-wider bg-stone-100 text-stone-600 border border-stone-200">
                                        {{ $file->category ?? 'General' }} {{-- Category string --}}
                                    </span> {{-- Close category badge --}}
                                    <span class="text-[0.68rem] text-stone-400">{{ strtoupper($extension) }}</span> {{-- Uppercase extension string --}}
                                </div> {{-- Close metadata badge row --}}
                            </div> {{-- Close title and category container --}}
                        </div> {{-- Close left metadata flex row --}}

                        <div class="flex items-center gap-2"> {{-- Action buttons wrapper --}}
                            <!-- Download Button --> {{-- Link to download file --}}
                            <a href="{{ route('subject.files.download', $file->id) }}" target="_blank" {{-- Endpoint opening in new tab --}}
                                class="w-9 h-9 rounded-xl flex items-center justify-center text-stone-600 bg-stone-100/60 border border-stone-200/60 hover:bg-stone-900 hover:text-white transition-colors" {{-- Download button styles --}}
                                title="Download / View File"> {{-- Tooltip label --}}
                                <span class="material-icons-round text-base">download</span> {{-- Download icon --}}
                            </a> {{-- Close download button link --}}

                            <!-- Delete Button Form --> {{-- Form wrapper for file deletion --}}
                            <form id="delete-form-{{ $file->id }}" {{-- Unique ID per file --}}
                                action="{{ route('subject.files.destroy', $file->id) }}" method="POST"> {{-- DELETE request route --}}
                                @csrf {{-- CSRF protection token --}}
                                @method('DELETE') {{-- HTTP DELETE override --}}
                                <button type="button" onclick="openDeleteModal('delete-form-{{ $file->id }}')" {{-- Trigger confirmation modal --}}
                                    class="w-9 h-9 rounded-xl flex items-center justify-center text-rose-600 bg-rose-50/60 border border-rose-200/60 hover:bg-rose-600 hover:text-white transition-colors cursor-pointer" {{-- Delete button styles --}}
                                    title="Delete File"> {{-- Tooltip label --}}
                                    <span class="material-icons-round text-base">delete_outline</span> {{-- Delete trash icon --}}
                                </button {{-- Close delete trigger button --}}
                            </form {{-- Close delete form --}}
                        </div> {{-- Close action buttons wrapper --}}
                    </div> {{-- Close file item card --}}
                @empty {{-- Executed when no files exist --}}
                    <div {{-- Empty state container --}}
                        class="flex flex-col items-center justify-center py-12 text-stone-400 bg-white/40 backdrop-blur-md rounded-2xl border border-stone-200/60"> {{-- Glassmorphic empty container styling --}}
                        <span class="material-icons-round text-3xl mb-1 opacity-50">folder_off</span> {{-- Empty folder icon --}}
                        <p class="text-xs font-semibold">No files uploaded for this subject yet.</p {{-- Empty state primary text --}}
                        <p class="text-[0.7rem] text-stone-400 mt-0.5">Click 'Add File' to upload lecture notes, slides, or {{-- Subtext --}}
                            guidelines.</p> {{-- Subtext continuation --}}
                    </div> {{-- Close empty state container --}}
                @endforelse {{-- End file loop --}}
            </div> {{-- Close files list container --}}
        @endif {{-- End main tab active check --}}

    </div> {{-- Close primary content wrapper --}}

    <!-- ================= MODERNISED LIQUID GLASS ADD FILE MODAL ================= --> {{-- Modal overlay wrapper --}}
    <div id="addFileModal" {{-- File upload modal backdrop element --}}
        class="hidden fixed inset-0 bg-stone-900/10 backdrop-blur-md z-[9999] items-end justify-center px-4 pb-24 transition-opacity duration-300 opacity-0"> {{-- Glassmorphic backdrop styling --}}

        <div id="fileModalCard" {{-- Modal floating card dialog --}}
            class="bg-white/85 backdrop-blur-2xl border border-white rounded-[2rem] w-full max-w-[384px] p-6 shadow-[0_24px_60px_rgba(0,0,0,0.12)] relative transform translate-y-8 transition-transform duration-300 max-h-[90vh] overflow-y-auto"> {{-- Liquid glass card styling --}}

            <div class="flex items-start justify-between mb-4"> {{-- Modal header flex row --}}
                <div> {{-- Modal title group --}}
                    <h3 class="m-0 text-sm font-bold text-stone-900 tracking-tight">Upload Course File</h3> {{-- Modal title --}}
                    <p class="m-0 mt-1 text-[0.68rem] font-medium text-stone-500">Categorize and attach documents</p> {{-- Modal subtitle --}}
                </div> {{-- Close title group --}}
                <button type="button" onclick="closeFileModal()" {{-- Button to dismiss modal --}}
                    class="w-7 h-7 rounded-full bg-stone-100/50 border-none text-stone-500 cursor-pointer flex items-center justify-center transition-colors hover:bg-stone-200"> {{-- Close button styling --}}
                    <span class="material-icons-round text-base">close</span> {{-- Close icon --}}
                </button> {{-- Close modal close button --}}
            </div> {{-- Close modal header row --}}

            <form id="addFileForm" action="{{ route('subject.files.store', $subject->id) }}" method="POST" {{-- Form dispatches file payload to controller --}}
                enctype="multipart/form-data" class="flex flex-col gap-3"> {{-- Enables file stream uploads --}}
                @csrf {{-- CSRF protection token --}}

                <div> {{-- Input field group for Title --}}
                    <label {{-- Input label --}}
                        class="block text-[0.625rem] font-bold text-stone-500 mb-1 uppercase tracking-wider pl-0.5">Document {{-- Label text line 1 --}}
                        Title</label> {{-- Label text line 2 --}}
                    <input type="text" name="title" required placeholder="e.g., Chapter 1 Lecture Notes" {{-- Text input element --}}
                        class="w-full h-11 px-4 bg-stone-100/40 border border-stone-200/60 rounded-2xl text-xs font-medium text-stone-900 outline-none focus:border-indigo-400 focus:bg-white/80 transition-colors"> {{-- Styled input field --}}
                </div> {{-- Close Title input group --}}

                <div> {{-- Select field group for Category --}}
                    <label {{-- Select label --}}
                        class="block text-[0.625rem] font-bold text-stone-500 mb-1 uppercase tracking-wider pl-0.5">Category</label> {{-- Category field label --}}
                    <select name="category" required {{-- Select menu --}}
                        class="w-full h-11 px-4 bg-stone-100/40 border border-stone-200/60 rounded-2xl text-xs font-semibold text-stone-900 outline-none focus:border-indigo-400 focus:bg-white/80 transition-colors"> {{-- Styled select box --}}
                        <option value="Lecture">Lecture Notes / PDF</option> {{-- Option: Lecture --}}
                        <option value="Presentation">Presentation (PPTX)</option> {{-- Option: Presentation --}}
                        <option value="Syllabus">Syllabus & Guidelines</option> {{-- Option: Syllabus --}}
                        <option value="Reference">Reference Material / Reading</option> {{-- Option: Reference --}}
                        <option value="Assignment">Assignment Guide</option> {{-- Option: Assignment --}}
                    </select> {{-- Close dropdown list --}}
                </div> {{-- Close Category select group --}}

                <div> {{-- File selector input group --}}
                    <label {{-- File input label --}}
                        class="block text-[0.625rem] font-bold text-stone-500 mb-1 uppercase tracking-wider pl-0.5">Select {{-- Label text line 1 --}}
                        File (PDF, DOCX, PPTX)</label> {{-- Label text line 2 --}}
                    <input type="file" name="file" required accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx" {{-- Standard browser file input --}}
                        class="w-full py-2 px-3 bg-stone-100/40 border border-stone-200/60 rounded-2xl text-xs text-stone-600 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"> {{-- Custom file selector styling --}}
                </div> {{-- Close File selector group --}}

                <!-- ================= UPLOAD PROGRESS BAR CONTAINER ================= --> {{-- Real-time visual byte tracker container --}}
                <div id="uploadProgressContainer" class="hidden space-y-1.5 pt-2"> {{-- Initially hidden until form submits --}}
                    <div class="flex justify-between items-center text-[0.68rem] font-bold text-stone-600 px-0.5"> {{-- Progress label row --}}
                        <span id="uploadStatusText">Uploading file...</span> {{-- Dynamic status message span --}}
                        <span id="uploadPercentText" class="text-indigo-600 font-extrabold">0%</span> {{-- Dynamic percentage text span --}}
                    </div> {{-- Close progress label row --}}
                    <div {{-- Outer progress bar track --}}
                        class="w-full h-2.5 bg-stone-100/80 rounded-full overflow-hidden border border-stone-200/60 p-0.5"> {{-- Track bar styling --}}
                        <div id="uploadProgressBar" {{-- Animated inner bar fill --}}
                            class="h-full bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 w-0 transition-all duration-150 ease-out rounded-full"> {{-- Dynamic width indicator --}}
                        </div> {{-- Close inner fill bar --}}
                    </div> {{-- Close outer track --}}
                </div> {{-- Close upload progress container --}}

                <div class="pt-2 grid grid-cols-2 gap-3 w-full"> {{-- Action buttons grid container --}}
                    <button type="button" onclick="closeFileModal()" {{-- Cancel button --}}
                        class="h-11 bg-white text-stone-600 border border-stone-200 rounded-2xl font-bold text-xs cursor-pointer transition-colors hover:bg-stone-50">Cancel</button> {{-- Cancel button styles --}}
                    <button type="submit" id="uploadSubmitBtn" {{-- Form submit button --}}
                        class="h-11 bg-stone-900 text-white border-none rounded-2xl font-bold text-xs cursor-pointer transition-colors hover:bg-stone-800 shadow-lg shadow-stone-900/20 flex items-center justify-center gap-1"> {{-- Submit button styles --}}
                        <span>Upload File</span> {{-- Button label --}}
                    </button> {{-- Close submit button --}}
                </div> {{-- Close button grid --}}
            </form> {{-- Close file upload form --}}
        </div> {{-- Close modal floating card dialog --}}
    </div> {{-- Close file upload modal overlay --}}

    <!-- Script Controller for File Modal with Progress Tracker --> {{-- JavaScript controller block for modal and file AJAX upload --}}
    <script> // Begin JavaScript context
        function openFileModal() { // Function to open the add file modal
            const modal = document.getElementById('addFileModal'); // Find modal container element
            const card = document.getElementById('fileModalCard'); // Find modal inner card element

            modal.classList.remove('hidden'); // Remove display hidden class
            modal.classList.add('flex'); // Add flex display class

            setTimeout(() => { // Delay slightly to allow CSS transitions to calculate
                modal.classList.remove('opacity-0'); // Fade backdrop in
                modal.classList.add('opacity-100'); // Set full opacity
                card.classList.remove('translate-y-8'); // Slide card up to rest position
                card.classList.add('translate-y-0'); // Reset vertical translation
            }, 10); // 10ms timeout
        } // End openFileModal function

        function closeFileModal() { // Function to close the add file modal
            const modal = document.getElementById('addFileModal'); // Find modal container element
            const card = document.getElementById('fileModalCard'); // Find modal inner card element

            modal.classList.remove('opacity-100'); // Fade backdrop out
            modal.classList.add('opacity-0'); // Set zero opacity
            card.classList.remove('translate-y-0'); // Slide card back down
            card.classList.add('translate-y-8'); // Apply downward offset

            setTimeout(() => { // Wait for animation transition to complete before hiding
                modal.classList.remove('flex'); // Remove flex display class
                modal.classList.add('hidden'); // Hide modal element completely

                // Reset progress bar on close // Clear state for future uploads
                const progressContainer = document.getElementById('uploadProgressContainer'); // Get container
                const progressBar = document.getElementById('uploadProgressBar'); // Get bar element
                const submitBtn = document.getElementById('uploadSubmitBtn'); // Get button element

                if (progressContainer) progressContainer.classList.add('hidden'); // Hide progress container
                if (progressBar) progressBar.style.width = '0%'; // Reset progress bar width to 0%
                if (submitBtn) { // Restore submit button interactivity
                    submitBtn.disabled = false; // Enable submit button
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed'); // Restore standard styling
                } // End submitBtn check
            }, 300); // 300ms matching transition duration
        } // End closeFileModal function

        window.addEventListener('click', function(e) { // Backdrop click listener to close modal
            if (e.target === document.getElementById('addFileModal')) { // Check if click was directly on background
                closeFileModal(); // Trigger modal close function
            } // End if target condition
        }); // End window click listener

        // AJAX Form Upload with Real-time Progress Tracking // Handles asynchronous form submission via XMLHttpRequest
        document.addEventListener('DOMContentLoaded', function() { // Wait until DOM tree is completely loaded
            const fileForm = document.getElementById('addFileForm'); // Select file upload form element

            if (fileForm) { // Guard clause checking if form element exists on page
                fileForm.addEventListener('submit', function(e) { // Attach event listener for form submission
                    e.preventDefault(); // Prevent standard browser full-page form submission reload

                    const formData = new FormData(this); // Construct Multipart FormData object from form inputs
                    const xhr = new XMLHttpRequest(); // Instantiate a clean XMLHttpRequest object for AJAX operations

                    const progressContainer = document.getElementById('uploadProgressContainer'); // Get UI element
                    const progressBar = document.getElementById('uploadProgressBar'); // Get progress bar fill UI
                    const percentText = document.getElementById('uploadPercentText'); // Get percentage text UI
                    const statusText = document.getElementById('uploadStatusText'); // Get status label UI
                    const submitBtn = document.getElementById('uploadSubmitBtn'); // Get submit button element

                    // Reveal progress bar UI & disable button // Provide instant visual user feedback
                    progressContainer.classList.remove('hidden'); // Unhide progress bar container
                    submitBtn.disabled = true; // Disable button to prevent double submissions
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed'); // Dim button visually

                    // Track byte transfer stream // Real-time byte upload event callback
                    xhr.upload.addEventListener('progress', function(e) { // Attach progress listener to XHR upload object
                        if (e.lengthComputable) { // Verify if total upload file size is known
                            const percent = Math.round((e.loaded / e.total) * 100); // Calculate uploaded percentage integer
                            progressBar.style.width = percent + '%'; // Update CSS width of fill bar
                            percentText.innerText = percent + '%'; // Update text percentage counter

                            if (percent === 100) { // Check if client stream completed
                                statusText.innerText = 'Syncing with cloud storage...'; // Update status text for cloud processing
                            } // End completion check
                        } // End lengthComputable check
                    }); // End upload progress listener

                    // Completion response handler // Triggers when request completes (success or server error)
                    xhr.addEventListener('load', function() { // Attach load event listener to response
                        if (xhr.status >= 200 && xhr.status < 300) { // Status 200-299 indicates successful HTTP upload
                            statusText.innerText = 'Upload complete!'; // Update UI text to success
                            window.location.reload(); // Refresh page to display newly created file record
                        } else if (xhr.status === 422) { // Status 422 indicates Laravel Form Validation Failure
                            const response = JSON.parse(xhr.responseText); // Parse JSON response payload
                            console.error("Validation Failed:", response.errors); // Log detailed errors to browser console

                            const firstError = Object.values(response.errors)[0][0]; // Extract first validation message
                            statusText.innerText = 'Validation Error!'; // Update UI text on error
                            alert('Upload rejected: ' + firstError); // Display error message to user

                            submitBtn.disabled = false; // Re-enable submit button
                            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed'); // Restore button UI styling
                        } else { // Fallback for 400, 413, 500 or other HTTP server status codes
                            statusText.innerText = 'Upload failed!'; // Update UI text on generic failure
                            alert('Server Error (' + xhr.status + '). Check console.'); // Alert server status code
                            submitBtn.disabled = false; // Re-enable submit button
                            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed'); // Restore button UI styling
                        } // End HTTP status evaluation
                    }); // End response load event listener

                    // Network Error handler // Handles client connection dropouts
                    xhr.addEventListener('error', function() { // Attach error listener for network issues
                        statusText.innerText = 'Network error!'; // Update UI text on connection failure
                        alert('A connection error occurred during upload.'); // Alert connection failure message
                        submitBtn.disabled = false; // Re-enable submit button
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed'); // Restore button UI styling
                    }); // End network error listener

                    xhr.open('POST', this.action, true); // Initialize connection with POST method to target form URL
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); // Identify request as AJAX to Laravel framework
                    xhr.setRequestHeader('Accept', 'application/json'); // Instruct Laravel to return JSON on validation errors instead of 302 redirects
                    xhr.send(formData); // Dispatch asynchronous HTTP request containing form payload
                }); // End form submit event listener
            } // End fileForm existence check
        }); // End DOMContentLoaded event listener
    </script> // End JavaScript context for file modal

    <!-- ================= MODERNISED LIQUID GLASS DELETE CONFIRMATION MODAL ================= --> {{-- Confirmation modal overlay --}}
    <div id="deleteModal" {{-- Delete confirmation modal container --}}
        class="hidden fixed inset-0 bg-stone-900/15 backdrop-blur-md z-[9999] items-center justify-center px-4 transition-opacity duration-300 opacity-0"> {{-- Modal backdrop styling --}}

        <div id="deleteModalCard" {{-- Delete modal dialog card --}}
            class="bg-white/85 backdrop-blur-2xl border border-white rounded-[2rem] w-full max-w-[320px] p-6 shadow-[0_24px_60px_rgba(0,0,0,0.12)] text-center transform scale-95 transition-transform duration-300"> {{-- Glassmorphic dialog card styling --}}

            <div {{-- Warning icon container --}}
                class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-100"> {{-- Circle warning icon container styling --}}
                <span class="material-icons-round text-2xl">warning_amber</span> {{-- Warning icon --}}
            </div> {{-- Close icon container --}}

            <h3 class="m-0 text-sm font-bold text-stone-900 tracking-tight">Delete File?</h3> {{-- Delete modal title --}}
            <p class="m-0 mt-1 text-[0.72rem] text-stone-500 font-medium">This action cannot be undone. The file will be {{-- Warning subtitle line 1 --}}
                permanently removed.</p> {{-- Warning subtitle line 2 --}}

            <div class="mt-6 grid grid-cols-2 gap-3"> {{-- Button grid wrapper --}}
                <button type="button" onclick="closeDeleteModal()" {{-- Cancel modal button --}}
                    class="h-10 bg-white text-stone-600 border border-stone-200 rounded-2xl font-bold text-xs cursor-pointer transition-colors hover:bg-stone-50">Cancel</button> {{-- Button styling --}}
                <button type="button" id="confirmDeleteBtn" {{-- Confirm delete action button --}}
                    class="h-10 bg-rose-600 text-white border-none rounded-2xl font-bold text-xs cursor-pointer transition-colors hover:bg-rose-700 shadow-md shadow-rose-600/20">Delete</button> {{-- Button styling --}}
            </div> {{-- Close button grid --}}
        </div> {{-- Close delete dialog card --}}
    </div> {{-- Close delete modal overlay --}}

    <!-- Script Controller for Delete Modal --> {{-- Script managing delete confirmation dialog logic --}}
    <script> // Begin JavaScript context
        let activeFormId = null; // Global reference variable storing active target form ID for deletion

        function openDeleteModal(formId) { // Opens delete modal and tracks form ID
            activeFormId = formId; // Store selected form ID in variable
            const modal = document.getElementById('deleteModal'); // Get modal element
            const card = document.getElementById('deleteModalCard'); // Get card element

            modal.classList.remove('hidden'); // Remove hidden class
            modal.classList.add('flex'); // Add flex layout class

            setTimeout(() => { // Trigger CSS opacity and scale animations
                modal.classList.remove('opacity-0'); // Fade backdrop in
                modal.classList.add('opacity-100'); // Set full opacity
                card.classList.remove('scale-95'); // Scale card up
                card.classList.add('scale-100'); // Set full scale
            }, 10); // 10ms timeout
        } // End openDeleteModal function

        function closeDeleteModal() { // Closes delete confirmation modal
            const modal = document.getElementById('deleteModal'); // Get modal element
            const card = document.getElementById('deleteModalCard'); // Get card element

            modal.classList.remove('opacity-100'); // Fade backdrop out
            modal.classList.add('opacity-0'); // Set zero opacity
            card.classList.remove('scale-100'); // Scale card down
            card.classList.add('scale-95'); // Apply scale-down class

            setTimeout(() => { // Wait for exit animation to finish
                modal.classList.remove('flex'); // Remove flex display class
                modal.classList.add('hidden'); // Hide modal element
                activeFormId = null; // Reset target form reference to null
            }, 300); // 300ms transition delay
        } // End closeDeleteModal function

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() { // Attach click handler to delete confirm button
            if (activeFormId) { // Check if an active target form ID is stored
                document.getElementById(activeFormId).submit(); // Submit corresponding form to perform deletion
            } // End activeFormId check
        }); // End delete confirmation listener

        window.addEventListener('click', function(e) { // Window click listener to close modal on backdrop click
            if (e.target === document.getElementById('deleteModal')) { // Check if click target is modal background
                closeDeleteModal(); // Execute modal close function
            } // End if condition
        }); // End window click listener
    </script> // End JavaScript context for delete modal

    <!-- ================= MODERNISED LIQUID GLASS STATUS CONFIRMATION MODAL ================= --> {{-- Status confirmation overlay --}}
    <div id="statusModal" {{-- Status change modal container --}}
        class="hidden fixed inset-0 bg-stone-900/15 backdrop-blur-md z-[9999] items-center justify-center px-4 transition-opacity duration-300 opacity-0"> {{-- Backdrop styling --}}

        <div id="statusModalCard" {{-- Status modal dialog card --}}
            class="bg-white/85 backdrop-blur-2xl border border-white rounded-[2rem] w-full max-w-[320px] p-6 shadow-[0_24px_60px_rgba(0,0,0,0.12)] text-center transform scale-95 transition-transform duration-300"> {{-- Dialog card styling --}}

            <div {{-- Icon container --}}
                class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4 border border-emerald-100"> {{-- Circle check icon container --}}
                <span class="material-icons-round text-2xl">task_alt</span> {{-- Checkmark icon --}}
            </div> {{-- Close icon container --}}

            <h3 id="statusModalTitle" class="m-0 text-sm font-bold text-stone-900 tracking-tight">Update Status?</h3> {{-- Dynamic status modal title element --}}
            <p id="statusModalDesc" class="m-0 mt-1 text-[0.72rem] text-stone-500 font-medium">Are you sure you want to {{-- Dynamic description line 1 --}}
                change this task status?</p> {{-- Dynamic description line 2 --}}

            <div class="mt-6 grid grid-cols-2 gap-3"> {{-- Button layout grid --}}
                <button type="button" onclick="closeStatusModal()" {{-- Dismiss status modal button --}}
                    class="h-10 bg-white text-stone-600 border border-stone-200 rounded-2xl font-bold text-xs cursor-pointer transition-colors hover:bg-stone-50">Cancel</button> {{-- Button styling --}}
                <button type="button" id="confirmStatusBtn" {{-- Confirm status update button --}}
                    class="h-10 bg-stone-900 text-white border-none rounded-2xl font-bold text-xs cursor-pointer transition-colors hover:bg-stone-800 shadow-md shadow-stone-900/20">Confirm</button> {{-- Button styling --}}
            </div> {{-- Close button layout grid --}}
        </div> {{-- Close status dialog card --}}
    </div> {{-- Close status modal container --}}

    <!-- Script Controller for Status Modal --> {{-- Script managing status confirmation dialog logic --}}
    <script> // Begin JavaScript context
        let activeStatusFormId = null; // Global reference variable storing active target form ID for status updates

        function openStatusModal(formId, title, description) { // Function to open status confirmation modal with custom strings
            activeStatusFormId = formId; // Store selected form ID
            document.getElementById('statusModalTitle').innerText = title; // Update title text dynamically
            document.getElementById('statusModalDesc').innerText = description; // Update description text dynamically

            const modal = document.getElementById('statusModal'); // Get modal element
            const card = document.getElementById('statusModalCard'); // Get card element

            modal.classList.remove('hidden'); // Remove hidden class
            modal.classList.add('flex'); // Add flex display class

            setTimeout(() => { // Trigger opening animation
                modal.classList.remove('opacity-0'); // Fade backdrop in
                modal.classList.add('opacity-100'); // Set full opacity
                card.classList.remove('scale-95'); // Scale card up
                card.classList.add('scale-100'); // Set full scale
            }, 10); // 10ms timeout
        } // End openStatusModal function

        function closeStatusModal() { // Function to close status confirmation modal
            const modal = document.getElementById('statusModal'); // Get modal element
            const card = document.getElementById('statusModalCard'); // Get card element

            modal.classList.remove('opacity-100'); // Fade backdrop out
            modal.classList.add('opacity-0'); // Set zero opacity
            card.classList.remove('scale-100'); // Scale card down
            card.classList.add('scale-95'); // Apply scale-down class

            setTimeout(() => { // Wait for closing animation to finish
                modal.classList.remove('flex'); // Remove flex display class
                modal.classList.add('hidden'); // Hide modal element completely
                activeStatusFormId = null; // Reset target form reference to null
            }, 300); // 300ms transition delay
        } // End closeStatusModal function

        document.getElementById('confirmStatusBtn').addEventListener('click', function() { // Attach click handler to confirm status button
            if (activeStatusFormId) { // Check if target status form ID is present
                document.getElementById(activeStatusFormId).submit(); // Submit target status form to update task status
            } // End activeStatusFormId check
        }); // End status confirmation listener

        window.addEventListener('click', function(e) { // Window click listener to close modal on backdrop click
            if (e.target === document.getElementById('statusModal')) { // Check if click target is modal background
                closeStatusModal(); // Execute modal close function
            } // End if condition
        }); // End window click listener
    </script> // End JavaScript context for status modal
@endsection {{-- Terminate Blade section 'content' --}}
