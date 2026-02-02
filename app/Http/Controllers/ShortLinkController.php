<?php

namespace App\Http\Controllers;
use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

class ShortLinkController extends Controller
{

    use AuthorizesRequests;
    public function index(Request $request)
    {
        // $links = ShortLink::whereBelongsTo(auth()->user())
        //     ->latest()
        //     ->get();
        // return view('profile.shortener', compact('links'));
        $query = ShortLink::query()
            ->where('user_id', auth()->id())
            ->latest();

        // Optional: server-side search by title
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        // 10 per page (change as you want)
        $links = $query->paginate(10)->withQueryString();

        return view('profile.shortener', compact('links'));
    }


    public function store(Request $request)
    {

        //     'alias' => [
//     'required',
//     'alpha_dash',
//     'min:5',
//     'max:50',
//     Rule::notIn(['login','register','dashboard','profile','tasks','documents','shortlinks']),
// ],

        // Validate input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'long_url' => 'required|url|max:2048|unique:short_links,long_url',
            'alias' => 'nullable|min:5|string|max:50|unique:short_links,alias|unique:users,name',
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
            ->with('success', 'Short link created successfully! Your URL: ' . config('app.public_url') . '/' . $alias);
    }


    public function update(Request $request, ShortLink $shortLink)
    {
        $this->authorize('update', $shortLink);

        // Validate input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'long_url' => 'required|url|max:2048|unique:short_links,long_url,' . $shortLink->id,
            'alias' => 'nullable|min:5|string|max:50|unique:users,name|unique:short_links,alias,' . $shortLink->id,
            'track_clicks' => 'nullable|boolean',
        ]);

        // Update short link
        $shortLink->update([
            'title' => $validated['title'],
            'long_url' => $validated['long_url'],
            'alias' => $validated['alias'],
            'track_clicks' => $request->has('track_clicks'),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Short link updated successfully!');
    }

    public function expand(ShortLink $shortLink)
    {
        $this->authorize('update', $shortLink);

        // Extend expiry by 10 days
        $shortLink->expires_at = now()->addDays(10)->toDateString();
        $shortLink->save();

        return redirect()
            ->back()
            ->with('success', 'Link expiry extended by 10 days!');
    }

    // Redirect handler
    public function redirect(string $alias)
    {
        $link = ShortLink::where('alias', $alias)->first();

        if (!$link) {
            return redirect()->route('invalid');
        }
        // Optional: check expiration
        if ($link->expires_at && now()->greaterThan($link->expires_at)) {
            return redirect()->route('expired');
        }

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
