<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminController extends Controller
{
    public function showAdmin(Request $request)
    {

        $user = auth()->user();

        abort_unless($user && $user->isAdmin(), 403, 'Unauthorized');


        $perPage = (int) $request->get('per_page', 12);
        $perPage = max(6, min($perPage, 100));

        $query = User::query()->latest();
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $query
            ->withCount(['tasks', 'documents', 'shortLinks'])
            ->paginate($perPage)
            ->withQueryString();

        return view('profile.admin', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        // 🔐 Admin only
        abort_unless(auth()->user() && auth()->user()->isAdmin(), 403, 'Unauthorized');

        // 🚫 Prevent admin changing own role or banning self
        if ($user->id === auth()->id()) {
            return back()->withErrors('You cannot modify your own account.');
        }

        // ✅ Validate
        $validated = $request->validate([
            'role' => 'required|in:' . implode(',', User::roles()),
            'is_banned' => 'nullable|boolean',
        ]);



        DB::transaction(function () use ($user, $validated, $request) {
            $wasBanned = $user->is_banned;

            // Update user
            $user->update([
                'role' => $validated['role'],
                'is_banned' => $request->boolean('is_banned'),
            ]);

            // 🔥 If user just got banned → destroy all sessions
            if (!$wasBanned && $user->is_banned) {
                DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->delete();
            }
        });

        return back()->with(
            'success',
            "User {$user->name} updated successfully."
        );
    }

    public function deleteUserData(Request $request, User $user)
    {
        $auth = auth()->user();

        // Admin only
        abort_unless($auth && $auth->isAdmin(), 403, 'Unauthorized');

        // Prevent self-delete of data (optional but recommended)
        abort_if($auth->id === $user->id, 403, "You can't delete your own data.");

        // Validate type
        $validated = $request->validate([
            'type' => 'required|in:tasks,documents,shortlinks',
        ]);

        $type = $validated['type'];

        // Map type -> relation/model delete
        $deleted = match ($type) {
            'tasks' => $user->tasks()->delete(),
            'documents' => $user->documents()->delete(),
            'shortlinks' => $user->shortLinks()->delete(),
        };

        return redirect()->back()->with('success', "Successfully deleted {$user->name}'s {$type}.");
    }
}
