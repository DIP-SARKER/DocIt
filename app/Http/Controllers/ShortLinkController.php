<?php

namespace App\Http\Controllers;
use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

class ShortLinkController extends Controller
{

    use AuthorizesRequests;
    public function index()
    {
        $links = ShortLink::whereBelongsTo(auth()->user())
            ->latest()
            ->get();
        return view('profile.shortener', compact('links'));
    }


    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'long_url' => 'required|url|max:2048|unique:short_links,long_url',
            'alias' => 'nullable|min:5|string|max:50|unique:short_links,alias',
            'track_clicks' => 'nullable|boolean',
        ]);

        // Generate alias
        if (!empty($validated['alias'])) {
            $alias = Str::slug($validated['alias']); // sanitize user input
        } else {
            // Create slug from title
            $alias = Str::slug($validated['title']);

            // Ensure unique alias
            $originalAlias = $alias;
            $counter = 1;
            while (ShortLink::where('alias', $alias)->exists()) {
                $alias = $originalAlias . '-' . $counter++;
            }
        }

        // Create short link
        ShortLink::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'long_url' => $validated['long_url'],
            'alias' => $alias,
            'track_clicks' => $request->has('track_clicks'),
            'clicks' => 0,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Short link created successfully! Your URL: docit.free.nf/' . $alias);
    }


    public function update(Request $request, ShortLink $shortLink)
    {
        $this->authorize('update', $shortLink);

        // Validate input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'long_url' => 'required|url|max:2048|unique:short_links,long_url,' . $shortLink->id,
            'track_clicks' => 'nullable|boolean',
        ]);

        // Update short link
        $shortLink->update([
            'title' => $validated['title'],
            'long_url' => $validated['long_url'],
            'track_clicks' => $request->has('track_clicks'),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Short link updated successfully!');
    }

    // Redirect handler
    public function redirect($alias)
    {
        $link = ShortLink::where('alias', $alias)->firstOrFail();

        if ($link->track_clicks) {
            $link->increment('clicks');
        }

        return redirect()->away($link->long_url);
    }

    public function destroy(ShortLink $shortLink)
    {
        $this->authorize('delete', $shortLink);

        $shortLink->delete();

        return redirect()->back()->with('success', 'Link deleted successfully.');
    }
}
