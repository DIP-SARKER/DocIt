<?php

namespace App\Http\Controllers;
use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ShortLinkController extends Controller
{

    use AuthorizesRequests;
    public function index(Request $request)
    {
        $query = ShortLink::query()
            ->where('user_id', auth()->id())
            ->latest();

        // Optional: server-side search by title
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        // 10 per page (change as you want)
        $links = $query->paginate(8)->withQueryString();
        $totalClicks = ShortLink::where('user_id', auth()->id())->sum('clicks');
        return view('profile.shortener', compact('links', 'totalClicks'));
    }


    public function store(Request $request)
    {
        $reserved = $this->reservedAliases();

        // Validate input
        $validated = $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],

                'long_url' => [
                    'required',
                    'url',
                    'max:2048',
                    Rule::unique('short_links', 'long_url'),
                ],

                'alias' => [
                    'nullable',
                    'string',
                    'min:5',
                    'max:50',
                    'alpha_dash',
                    Rule::unique('short_links', 'alias'),
                    Rule::unique('users', 'name'),
                    Rule::notIn($reserved),
                ],

                'track_clicks' => ['nullable', 'boolean'],
            ],
            [
                'alias.not_in' => 'This alias is reserved. Please choose another one.',
            ]
        );

        // Generate alias
        if (!empty($validated['alias'])) {
            $alias = Str::slug($validated['alias']);
        } else {
            $alias = Str::slug($validated['title']);
            $originalAlias = $alias;
            $counter = 1;

            while (ShortLink::where('alias', $alias)->exists()) {
                $alias = $originalAlias . '-' . $counter++;
            }
        }

        // Re-check after formatting
        if (in_array($alias, $reserved, true)) {
            return back()
                ->withErrors(['alias' => 'This alias is reserved. Please choose another one.'])
                ->withInput();
        }

        if (ShortLink::where('alias', $alias)->exists()) {
            return back()
                ->withErrors(['alias' => 'Alias already exists. Choose another.'])
                ->withInput();
        }

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
            ->with(
                'success',
                'Short link created successfully! Your URL: ' . config('app.public_url') . '/' . $alias
            );
    }


    public function update(Request $request, ShortLink $shortLink)
    {
        $this->authorize('update', $shortLink);

        $reserved = $this->reservedAliases();

        // Validate input
        $validated = $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],

                'long_url' => [
                    'required',
                    'url',
                    'max:2048',
                    Rule::unique('short_links', 'long_url')->ignore($shortLink->id),
                ],

                'alias' => [
                    'nullable',
                    'string',
                    'min:5',
                    'max:50',
                    'alpha_dash',
                    Rule::unique('short_links', 'alias')->ignore($shortLink->id),
                    Rule::unique('users', 'name'),
                    Rule::notIn($reserved),
                ],

                'track_clicks' => ['nullable', 'boolean'],
            ],
            [
                'alias.not_in' => 'This alias is reserved. Please choose another one.',
            ]
        );

        $alias = null;

        if (!empty($validated['alias'])) {
            $alias = Str::slug($validated['alias']);
        }

        // Re-check after slugging
        if ($alias !== null) {
            if (in_array($alias, $reserved, true)) {
                return back()
                    ->withErrors(['alias' => 'This alias is reserved. Please choose another one.'])
                    ->withInput();
            }

            $exists = ShortLink::where('alias', $alias)
                ->where('id', '!=', $shortLink->id)
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['alias' => 'Alias already exists. Choose another.'])
                    ->withInput();
            }
        }

        $shortLink->update([
            'title' => $validated['title'],
            'long_url' => $validated['long_url'],
            'alias' => $alias,
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

    private function reservedAliases(): array
    {
        return [
            // auth routes
            'register',
            'login',
            'logout',
            'forgot-password',
            'reset-password',
            'verify-email',
            'confirm-password',
            'password',
            'email',

            // your pages
            'dashboard',
            'admin',
            'mods',
            'tasks',
            'documents',
            'shortlinks',
            'profile',
            'settings',
            'terms-and-conditions',
            'privacy-policy',
            'link-expired',
            'invalid-link',
            'doc', // because /doc/{name}
        ];
    }

}
