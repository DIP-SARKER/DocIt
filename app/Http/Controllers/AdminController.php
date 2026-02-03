<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
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
    public function exportAllData(Request $request)
    {
        abort_unless($request->user() && $request->user()->isAdmin(), 403, 'Unauthorized');

        // ⚠️ Export everything. Adjust what you want included.
        $payload = [
            'meta' => [
                'app' => 'DocIt',
                'exported_at' => now()->toIso8601String(),
            ],
            'users' => DB::table('users')->orderBy('id')->get(),
            'tasks' => DB::table('tasks')->orderBy('id')->get(),
            'documents' => DB::table('documents')->orderBy('id')->get(),
            'short_links' => DB::table('short_links')->orderBy('id')->get(), // change table name if yours differs
        ];

        $filename = 'docit-export-all-' . now()->format('Y-m-d_H-i-s') . '.json';

        return response()->streamDownload(function () use ($payload) {
            echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function clearCache(Request $request)
    {
        abort_unless($request->user() && $request->user()->isAdmin(), 403, 'Unauthorized');

        // Clears cache safely (choose what you want to clear)
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');

        return back()->with('success', 'Cache cleared successfully.');
    }

    public function purgeAllData(Request $request)
    {
        abort_unless($request->user() && $request->user()->isAdmin(), 403, 'Unauthorized');

        $request->validate([
            'confirm' => 'required|in:DELETE_ALL',
        ]);

        DB::transaction(function () {

            DB::table('tasks')->delete();
            DB::table('documents')->delete();
            DB::table('short_links')->delete();

            DB::table('users')->where('role', '!=', 'admin')->delete();
        });
        DB::table('sessions')->truncate();


        return back()->with('success', 'All data has been deleted successfully.');
    }
}
