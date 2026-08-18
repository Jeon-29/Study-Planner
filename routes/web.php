<?php

use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AssessmentController;

/*
|--------------------------------------------------------------------------
| Public / Guest Routes
|--------------------------------------------------------------------------
| These routes can be accessed by anyone without needing to log in first.
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::post('/guide/complete', function () {
    auth()->user()->update(['has_seen_guide' => true]);
    return response()->json(['success' => true]);
})->middleware('auth')->name('guide.complete');

// --- MOVING PASSWORD RESET ROUTES HERE (kasi nalilito na ako sa sobrang dami nakalagay pota hindi ko na alam) ---

Route::get('/forgot-password', [PasswordResetController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetController::class, 'store'])
    ->middleware('throttle:3,1')
    ->name('password.email');

Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [PasswordResetController::class, 'update'])
    ->middleware('guest')
    ->name('password.update');

/*
|--------------------------------------------------------------------------
| Protected Routes (Requires 'auth' Middleware)
|--------------------------------------------------------------------------
| All routes inside this group require an active authenticated user session.
|
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- To-Do / Assignment Tracker Routes ---
    Route::get('/todo', [TodoController::class, 'index'])->name('todo.index');
    Route::post('/todo', [TodoController::class, 'store'])->name('todo.store');
    Route::patch('/todo/{todo}/complete', [TodoController::class, 'complete'])->name('todo.complete');
    Route::put('/todo/{todo}', [TodoController::class, 'update'])->name('todo.update');
    Route::delete('/todo/{todo}', [TodoController::class, 'destroy'])->name('todo.destroy');
    Route::patch('/todos/{id}/status', [TodoController::class, 'updateStatus'])->name('todos.update-status');

    // --- Subject Hub Routes ---
    Route::patch('/subjects/{id}/archive', [SubjectController::class, 'toggleArchive'])->name('subjects.archive');
    Route::get('/subjects/{id}', [SubjectController::class, 'show'])->name('subject.show');
    Route::post('/subjects/{id}/files', [SubjectController::class, 'storeFile'])->name('subject.files.store');
    Route::delete('/files/{id}', [SubjectController::class, 'destroyFile'])->name('subject.files.destroy');
    Route::get('/files/{id}/download', [SubjectController::class, 'downloadFile'])->name('subject.files.download');

    Route::resource('subjects', SubjectController::class)->names('subject')->only([
        'index',
        'store',
        'edit',
        'update',
        'destroy',
    ]);

    // --- Exam/Quiz Routes ---
    // Inside your auth middleware group (if you have one)
    Route::get('/assessments', [AssessmentController::class, 'index'])->name('assessments.index');
    Route::post('/assessments', [AssessmentController::class, 'store'])->name('assessments.store');
    Route::patch('/assessments/{assessment}/mark-done', [AssessmentController::class, 'markAsDone'])->name('assessments.mark-done');



    // --- Profile Routes ---
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // --- Class Schedule Routes ---
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
    Route::post('/schedule/store', [ScheduleController::class, 'store'])->name('schedule.store');
    Route::match(['put', 'patch'], '/schedule/{classSchedule}', [ScheduleController::class, 'update'])->name('schedule.update');
    Route::delete('/schedule/{classSchedule}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');
});

// Route::get('/test-s3', function () {
//     try {
//         $disk = Storage::build([
//             'driver'                  => 's3',
//             'key'                     => env('AWS_ACCESS_KEY_ID'),
//             'secret'                  => env('AWS_SECRET_ACCESS_KEY'),
//             'region'                  => env('AWS_DEFAULT_REGION'),
//             'bucket'                  => env('AWS_BUCKET'),
//             'endpoint'                => env('AWS_ENDPOINT'),
//             'use_path_style_endpoint' => true,
//             'visibility'              => 'public', // <--- Force 'public-read' ACL for Backblaze B2
//             'throw'                   => true,
//         ]);

//         $disk->put('test-upload.txt', 'Connection successful!');

//         return response()->json([
//             'status'     => 'SUCCESS',
//             'message'    => 'Uploaded test-upload.txt to Backblaze successfully!',
//             'file_url'   => $disk->url('test-upload.txt'),
//         ]);
//     } catch (\Throwable $e) {
//         return response()->json([
//             'status'        => 'FAILED',
//             'error_type'    => get_class($e),
//             'error_message' => $e->getMessage(),
//         ], 500);
//     }
// });


