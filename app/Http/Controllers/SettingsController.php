<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function edit()
    {
        return view('profile.settings');
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80', Rule::unique('users', 'name')->ignore($user->id)],
            'email' => [
                'required',
                'email',
                'max:120',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $emailChanged = $validated['email'] !== $user->email;

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // If you ever enable email verification, this is correct behavior:
        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function export(Request $request)
    {
        $user = $request->user();

        // Load related data (adjust fields if you want smaller export)
        $payload = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at,
            ],
            'tasks' => $user->tasks()->latest()->get(),
            'documents' => $user->documents()->latest()->get(),
            'short_links' => $user->shortLinks()->latest()->get(),
            'exported_at' => now()->toIso8601String(),
        ];

        $filename = 'docit-export-user-' . $user->id . '-' . now()->format('Y-m-d_H-i-s') . '.json';

        return response()->streamDownload(function () use ($payload) {
            echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function logoutEverywhere(Request $request)
    {
        $user = $request->user();

        // ✅ Clear "remember me" token so old remember cookie can’t re-login
        $user->setRememberToken(null);
        $user->save();

        // ✅ Clear all sessions for this user (works when SESSION_DRIVER=database)
        DB::table('sessions')->where('user_id', $user->id)->delete();

        // ✅ Logout current device too
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out from all devices.');
    }

    public function destroyAccount(Request $request)
    {
        $user = $request->user();

        DB::transaction(function () use ($user) {
            // Delete user data first
            $user->tasks()->delete();
            $user->documents()->delete();
            $user->shortLinks()->delete();

            // Clear sessions
            DB::table('sessions')->where('user_id', $user->id)->delete();

            // Delete account
            $user->delete();
        });

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been deleted.');
    }
}
