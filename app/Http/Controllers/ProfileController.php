<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $subjectsCount = Subject::query()->where('user_id', $user->id)->count();
        $totalTasks = Todo::query()->where('user_id', $user->id)->count();

        return view('profile.index', compact('subjectsCount', 'totalTasks'));
    }

    public function update(Request $request)
{
    $user = auth()->user();

    // Handle Avatar Upload
    if ($request->hasFile('avatar')) {
        // Delete old avatar from Backblaze if it exists
        if ($user->avatar && Storage::disk('s3')->exists($user->avatar)) {
            Storage::disk('s3')->delete($user->avatar);
        }

        // Store new avatar in Backblaze B2 'avatars' folder
        $path = $request->file('avatar')->store('avatars', 's3');
        $user->avatar = $path;
    }

    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->track = $request->input('track');
    $user->year_level = $request->input('year_level');
    $user->save();

    return back()->with('success', 'Profile updated successfully!');
}

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.index')->with('success', 'Password updated successfully!');
    }
}
