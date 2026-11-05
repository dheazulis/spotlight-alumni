<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAlumniOtpVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user &&
            $user->role === 'alumni' &&
            !$request->session()->get('otp_verified_this_session', false)
        ) {
            return redirect()
                ->route('otp.form')
                ->with(
                    'status',
                    'Silakan verifikasi OTP terlebih dahulu.'
                );
        }

        return $next($request);
    }
}
