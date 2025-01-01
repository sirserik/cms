<?php


namespace App\Core\DI;

class Container
{
    private $bindings = [];

    public function bind($key, $resolver)
    {
        $this->bindings[$key] = $resolver;
    }

    public function resolve($key)
    {
        if (isset($this->bindings[$key])) {
            return call_user_func($this->bindings[$key]);
        }

        throw new \Exception("No binding found for {$key}");
    }
}