<?php 

namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;
use App\Core\Response;
use App\Core\JwtHelper;

class JwtAuthMiddleware implements Middleware
{
    public function handle(Request $request, Response $response)
    {
        $authHeader = $request->header('Authorization');

        // if (!$authHeader || !preg_match('/Bearer\\s+(.*)/', $authHeader, $matches)) {
        //     $response->json(['message' => 'Unauthorized'], 401);
        //     return false;
        // }

        // $token = $matches[1];
        // $decoded = JwtHelper::validateToken($token);

        // if (!$decoded) {
        //     $response->json(['message' => 'Invalid token'], 401);
        //     return false;
        // }

        return true;
    }
}
