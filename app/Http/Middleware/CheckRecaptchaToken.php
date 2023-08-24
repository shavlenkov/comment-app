<?php

namespace App\Http\Middleware;

use Closure;
use ErrorException;
use Illuminate\Http\Request;
use ReCaptcha\ReCaptcha;
use Symfony\Component\HttpFoundation\Response;

class CheckRecaptchaToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verify reCAPTCHA token
        $recaptcha = new ReCaptcha(config('recaptcha.secret'));
        $response = $recaptcha->verify($request->input('recaptcha_token'), request()->ip());

        if (!$response->isSuccess()) {
            throw new ErrorException('reCAPTCHA verification failed');
        }

        return $next($request);
    }
}
