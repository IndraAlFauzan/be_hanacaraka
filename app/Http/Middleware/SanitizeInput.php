<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * Fields that should not be sanitized (like passwords, rich text).
     */
    protected array $except = [
        'password',
        'password_confirmation',
        'current_password',
        'content_markdown',
    ];

    /**
     * Handle an incoming request.
     * Sanitizes string inputs to prevent XSS attacks.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value, $key) {
            if (is_string($value) && !in_array($key, $this->except)) {
                // Remove null bytes
                $value = str_replace(chr(0), '', $value);
                
                // Trim whitespace
                $value = trim($value);
                
                // For non-HTML fields, strip potentially dangerous HTML
                // Note: Fields that need HTML should be explicitly allowed
                if (!$this->isHtmlField($key)) {
                    $value = strip_tags($value);
                }
            }
        });

        $request->merge($input);

        return $next($request);
    }

    /**
     * Check if field is allowed to contain HTML.
     */
    protected function isHtmlField(string $key): bool
    {
        $htmlFields = [
            'content_markdown',
            'description',
        ];

        return in_array($key, $htmlFields);
    }
}
