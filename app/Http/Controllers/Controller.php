<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Return a success response with flash message.
     */
    protected function successResponse(string $message, string $route, array $params = []): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route($route, $params)
            ->with('success', $message);
    }

    /**
     * Return an error response with flash message.
     */
    protected function errorResponse(string $message, string $route = '', array $params = []): \Illuminate\Http\RedirectResponse
    {
        if ($route) {
            return redirect()->route($route, $params)
                ->with('error', $message);
        }

        return back()->with('error', $message);
    }

    /**
     * Return a response with validation errors.
     */
    protected function validationErrorResponse(array $errors): \Illuminate\Http\RedirectResponse
    {
        return back()
            ->withErrors($errors)
            ->withInput();
    }

    /**
     * Format a date for display.
     */
    protected function formatDate(\DateTime $date, string $format = 'Y-m-d H:i:s'): string
    {
        return $date->format($format);
    }

    /**
     * Get pagination limit from request or use default.
     */
    protected function getPaginationLimit(int $default = 10): int
    {
        $limit = request()->input('limit', $default);
        return min($limit, 100); // Cap at 100 items per page
    }
}
