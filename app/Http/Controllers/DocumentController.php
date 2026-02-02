<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Document::query()
            ->where('user_id', auth()->id())
            ->latest();
        // Search (title/category/description)
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Paginate
        $documents = $query->paginate(12)->withQueryString(); // 12 fits grid-3 nicely

        return view('profile.documents', compact('documents'));
    }
    public function show(string $name, Request $request)
    {
        $user = User::where('name', $name)->first();

        // If user not found
        if (!$user) {
            return redirect()->route('invalid');
        }

        $name = $user->name;

        // Base query: only unlocked docs for that user, latest first
        $query = Document::query()
            ->where('user_id', $user->id)
            ->where('is_locked', false)
            ->latest();

        // Search (title/category/description)
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Paginate (same style as index)
        $documents = $query->paginate(3)->withQueryString();

        return view('layouts.docs', compact('name', 'documents'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:2048|unique:documents,url',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_locked' => 'nullable|boolean',
            'is_important' => 'nullable|boolean',
        ]);

        // Create document
        Document::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'url' => $validated['url'],
            'category' => $validated['category'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_locked' => $request->has('is_locked'),
            'is_important' => $request->has('is_important'),
        ]);
        return redirect()->back()->with('success', 'Document link added successfully.');
    }

    public function update(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:2048|unique:documents,url,' . $document->id,
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_locked' => 'nullable|boolean',
            'is_important' => 'nullable|boolean',
        ]);

        $document->update([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'category' => $validated['category'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_locked' => $request->has('is_locked'),
            'is_important' => $request->has('is_important'),
        ]);

        return redirect()->back()->with('success', 'Document updated successfully.');
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);
        $document->delete();
        return redirect()->back()->with('success', 'Document deleted successfully.');
    }
}
