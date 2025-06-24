<?php 
namespace App\Core;

use ReflectionClass;

class Container
{
    private $bindings = [];
    private $instances = [];

    public function bind($abstract, $concrete, $singleton = false)
    {
        $this->bindings[$abstract] = ['concrete' => $concrete, 'singleton' => $singleton];
    }

    public function resolve($class)
    {
        if (isset($this->bindings[$class])) {
            $binding = $this->bindings[$class];

            if ($binding['singleton']) {
                if (isset($this->instances[$class])) {
                    return $this->instances[$class];
                }
            }

            $class = $binding['concrete'];
        }

        if (!class_exists($class)) {
            throw new \Exception("Class $class not found");
        }

        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            $object = new $class;
            if (isset($binding) && $binding['singleton']) {
                $this->instances[$class] = $object;
            }
            return $object;
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependencyClass = $parameter->getType()->getName();
            $dependencies[] = $this->resolve($dependencyClass);
        }

        $object = $reflection->newInstanceArgs($dependencies);

        if (isset($binding) && $binding['singleton']) {
            $this->instances[$class] = $object;
        }

        return $object;
    }
}
