<?php 
namespace App\Core;

class Request
{
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function get()
    {
        return $_GET;
    }

    public function uri()
    {
        return strtok($_SERVER['REQUEST_URI'], '?');
    }

    public function body()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    public function header($key)
    {
        $headerKey = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $_SERVER[$headerKey] ?? null;
    }
}
