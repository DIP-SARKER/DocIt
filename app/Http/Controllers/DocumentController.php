<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $documents = Document::whereBelongsTo(auth()->user())
            ->latest()
            ->get();
        return view('profile.documents', compact('documents'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:2048|unique:documents,url',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
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

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }
}
