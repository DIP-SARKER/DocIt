<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class InactivityLogout
{
    /**
     * Inactivity timeout in seconds (10 minutes).
     */
    protected int $timeoutSeconds = 600;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {

            // ✅ Skip inactivity logout if user chose "remember me"
            // We use session flag AND cookie presence as backup.
            $rememberSession = (bool) $request->session()->get('remember_me', false);

            // Laravel "remember me" cookie name:
            $recallerName = Auth::getRecallerName();
            $hasRememberCookie = $request->cookies->has($recallerName);

            $isRemembered = $rememberSession || $hasRememberCookie;

            if (!$isRemembered) {
                $lastActivity = (int) $request->session()->get('last_activity', time());

                if ((time() - $lastActivity) > $this->timeoutSeconds) {
                    Auth::logout();

                    // invalidate current session
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()
                        ->route('login')
                        ->withErrors(['email' => 'You were logged out due to inactivity (10 minutes).']);
                }
            }

            // update last activity timestamp on every request
            $request->session()->put('last_activity', time());
        }
        return $next($request);
    }
}
