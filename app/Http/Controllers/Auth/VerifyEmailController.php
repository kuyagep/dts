<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        if ($user->status !== 'approved') {
            // Log out the user immediately
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $statusMessage = match ($user->status) {
                'pending' => 'Email verified successfully! However, your account is still pending administrator approval.',
                'deactivated' => 'Email verified, but your account has been deactivated. Please contact your administrator.',
                default => 'Your account is not authorized to access the system.',
            };

            return redirect()->route('login')->with('status', $statusMessage);
        }

        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
