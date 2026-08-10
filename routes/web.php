<?php

// Import Controller classes so we can map URL endpoints directly to their controller methods
use App\Http\Controllers\AuthController;       // Handles user registration, login authentication, and logout
use App\Http\Controllers\TodoController;       // Handles assignment creation, completion, update, and deletion
use App\Http\Controllers\SubjectController;    // Handles subject creation, listing, editing, and deletion
use App\Http\Controllers\ProfileController;    // Handles displaying user profile details
use Illuminate\Support\Facades\Route;           // Laravel's Route facade used to define HTTP request endpoints
use App\Http\Controllers\ScheduleController;   // Handles class schedule display, creation, updates, and deletion
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Public / Guest Routes
|--------------------------------------------------------------------------
| These routes can be accessed by anyone without needing to log in first.
|
*/

// Display the single-page welcome / login screen when visiting the root URL ('/')
Route::get('/', function () {
    return view('welcome'); // Loads and renders the 'resources/views/welcome.blade.php' template
})->name('login'); // Named 'login' so Laravel's 'auth' middleware automatically redirects unauthenticated users here

// Handles the registration form submission (HTTP POST)
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Handles the login form submission (HTTP POST)
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');



/*
|--------------------------------------------------------------------------
| Protected Routes (Requires 'auth' Middleware)
|--------------------------------------------------------------------------
| All routes inside this group require an active authenticated user session.
| If an unauthenticated user tries to visit them, Laravel redirects them to 'login'.
|
*/

Route::middleware('auth')->group(function () {

    // Displays the user's main dashboard view (HTTP GET)
    // NEW (Points to DashboardController which fetches $nextUpTask)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Destroys the current user session and logs the user out (HTTP POST)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- To-Do / Assignment Tracker Routes ---

    // Displays the main To-Do / Assignment list view
    Route::get('/todo', [TodoController::class, 'index'])->name('todo.index');

    // Stores a new assignment/To-Do task into the database
    Route::post('/todo', [TodoController::class, 'store'])->name('todo.store');

    // Toggles/marks a specific assignment task as completed (HTTP PATCH for partial resource update)
    Route::patch('/todo/{todo}/complete', [TodoController::class, 'complete'])->name('todo.complete');

    // Updates an existing assignment task's details (HTTP PUT for updating task fields)
    Route::put('/todo/{todo}', [TodoController::class, 'update'])->name('todo.update');

    // Removes/deletes a specific assignment task from the database
    Route::delete('/todo/{todo}', [TodoController::class, 'destroy'])->name('todo.destroy');

    Route::patch('/subjects/{id}/archive', [SubjectController::class, 'toggleArchive'])->name('subjects.archive');

    Route::get('/subjects/{id}', [SubjectController::class, 'show'])->name('subject.show');

    // Route for storing course files
    Route::post('/subjects/{id}/files', [SubjectController::class, 'storeFile'])->name('subject.files.store');

    Route::delete('/files/{id}', [SubjectController::class, 'destroyFile'])->name('subject.files.destroy');

    // Route to update just the status of a specific To-Do
    Route::patch('/todos/{id}/status', [TodoController::class, 'updateStatus'])->name('todos.update-status');

    // --- Subject Hub Routes ---

    // Resource route automatically generates standard CRUD routes for SubjectController
    // Sets URL prefix to '/subjects', but renames the route aliases to singular ('subject.index', 'subject.store', etc.)
    // Restricts generated routes strictly to: index, store, edit, update, and destroy
    Route::resource('subjects', SubjectController::class)->names('subject')->only([
        'index',
        'store',
        'edit',
        'update',
        'destroy'
    ]);

    // Displays the logged-in user's profile details view
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

    // Handles updating the user's profile information (Name, Email, Track, Year, Avatar)
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Handles updating the user's password securely
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    // --- Class Schedule Routes ---

    // Displays the schedule timeline, weekly strip, and monthly calendar view
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');

    // Stores a newly created class schedule entry into the database
    Route::post('/schedule/store', [ScheduleController::class, 'store'])->name('schedule.store');

    // Updates an existing class schedule entry (Supports PUT/PATCH to match Blade @method('PUT') form submission)
    Route::match(['put', 'patch'], '/schedule/{classSchedule}', [ScheduleController::class, 'update'])->name('schedule.update');

    // Deletes a class schedule entry from the database using Route Model Binding or Schedule ID
    Route::delete('/schedule/{classSchedule}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');
});


// for debugging
