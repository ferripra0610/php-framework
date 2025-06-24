<?php 
namespace App\Core;

interface Middleware
{
    public function handle(Request $request, Response $response);
}
