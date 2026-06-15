<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureUserHasRole
{
    /**
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next, string $role): Response|JsonResponse
    {
        $user = $request->user();

        if ($user === null || ! method_exists($user, 'hasRole') || ! $user->hasRole($role)) {
            return response()->json([
                'message' => 'Forbidden.',
                'required_role' => $role,
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
