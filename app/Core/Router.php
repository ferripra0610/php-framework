<?php 

namespace App\Core;

class Router
{
    private static $routes = [];

    public static function get($uri, $callback, $middleware = null)
    {
        self::$routes['GET'][] = ['uri' => $uri, 'callback' => $callback, 'middleware' => $middleware];
    }

    public static function post($uri, $callback, $middleware = null)
    {
        self::$routes['POST'][] = ['uri' => $uri, 'callback' => $callback, 'middleware' => $middleware];
    }

    public static function put($uri, $callback, $middleware = null)
    {
        self::$routes['PUT'][] = ['uri' => $uri, 'callback' => $callback, 'middleware' => $middleware];
    }

    public static function delete($uri, $callback, $middleware = null)
    {
        self::$routes['DELETE'][] = ['uri' => $uri, 'callback' => $callback, 'middleware' => $middleware];
    }

    public static function dispatch(Request $request, Response $response, Container $container)
    {
        $method = $request->method();
        $uri = $request->uri();

        $matchedRoute = null;
        $routeParams = [];

        if (!isset(self::$routes[$method])) {
            $response->json(['message' => 'Method not allowed'], 405);
            return;
        }

        foreach (self::$routes[$method] as $route) {
            $pattern = preg_replace('/\\{[a-zA-Z0-9_]+\\}/', '([a-zA-Z0-9_]+)', $route['uri']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $matchedRoute = $route;
                $routeParams = $matches;
                break;
            }
        }

        if ($matchedRoute) {
            if ($matchedRoute['middleware']) {
                $middleware = $container->resolve($matchedRoute['middleware']);
                $middlewareResult = $middleware->handle($request, $response);
                if ($middlewareResult === false) {
                    return;
                }
            }

            $callback = $matchedRoute['callback'];

            if (is_array($callback)) {
                $controller = $container->resolve($callback[0]);
                $methodName = $callback[1];
                echo $controller->$methodName($request, $response, ...$routeParams);
            } else {
                echo call_user_func($callback, $request, $response, ...$routeParams);
            }
        } else {
            $response->json(['message' => 'Route not found'], 404);
        }
    }
}
