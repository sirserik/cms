<?php

namespace App\Core\DI;

use Exception;
use ReflectionClass;
use ReflectionParameter;

class Container
{
    /**
     * @var array - Зарегистрированные зависимости.
     */
    private array $bindings = [];

    /**
     * @var array - Кэш разрешенных экземпляров.
     */
    private array $resolvedInstances = [];

    /**
     * Регистрирует зависимость.
     *
     * @param string $key - Имя зависимости.
     * @param callable $resolver - Функция, которая возвращает экземпляр зависимости.
     */
    public function bind(string $key, callable $resolver): void
    {
        $this->bindings[$key] = $resolver;
    }

    /**
     * Регистрирует синглтон (один экземпляр на все время работы приложения).
     *
     * @param string $key - Имя зависимости.
     * @param callable $resolver - Функция, которая возвращает экземпляр зависимости.
     */
    public function singleton(string $key, callable $resolver): void
    {
        $this->bind($key, function ($container) use ($resolver) {
            static $instance;
            if (!$instance) {
                $instance = $resolver($container);
            }
            return $instance;
        });
    }

    /**
     * Регистрирует интерфейс с реализацией.
     *
     * @param string $interface - Имя интерфейса.
     * @param string $implementation - Имя класса-реализации.
     * @throws Exception - Если класс-реализация не существует.
     */
    public function bindInterface(string $interface, string $implementation): void
    {
        if (!class_exists($implementation)) {
            throw new Exception("Implementation class {$implementation} does not exist.");
        }

        $this->bind($interface, function ($container) use ($implementation) {
            return $container->resolve($implementation);
        });
    }

    /**
     * Разрешает зависимость.
     *
     * @param string $key - Имя зависимости.
     * @param array $parameters - Дополнительные параметры для передачи в конструктор.
     * @return object - Экземпляр зависимости.
     * @throws Exception - Если зависимость не зарегистрирована.
     */
    public function resolve(string $key, array $parameters = []): object
    {
        // Если экземпляр уже был разрешен, возвращаем его из кэша.
        if (isset($this->resolvedInstances[$key])) {
            return $this->resolvedInstances[$key];
        }

        // Если зависимость зарегистрирована, вызываем резолвер.
        if (isset($this->bindings[$key])) {
            $instance = call_user_func($this->bindings[$key], $this);
            $this->resolvedInstances[$key] = $instance;
            return $instance;
        }

        // Если это класс, пытаемся создать его через автосвязывание.
        if (class_exists($key)) {
            $instance = $this->autowire($key, $parameters);
            $this->resolvedInstances[$key] = $instance;
            return $instance;
        }

        throw new Exception("No binding found for {$key}");
    }

    /**
     * Автоматически создает экземпляр класса, используя рефлексию.
     *
     * @param string $className - Имя класса.
     * @param array $parameters - Дополнительные параметры для передачи в конструктор.
     * @return object - Экземпляр класса.
     * @throws Exception - Если класс не может быть создан.
     */
    private function autowire(string $className, array $parameters = []): object
    {
        $reflector = new ReflectionClass($className);

        if (!$reflector->isInstantiable()) {
            throw new Exception("Class {$className} is not instantiable.");
        }

        $constructor = $reflector->getConstructor();

        // Если у класса нет конструктора, просто создаем экземпляр.
        if (is_null($constructor)) {
            return new $className;
        }

        // Разрешаем зависимости конструктора.
        $dependencies = array_map(
            fn(ReflectionParameter $parameter) => $this->resolveDependency($parameter, $parameters),
            $constructor->getParameters()
        );

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Разрешает параметр конструктора.
     *
     * @param ReflectionParameter $parameter - Параметр конструктора.
     * @param array $parameters - Дополнительные параметры для передачи в конструктор.
     * @return mixed - Экземпляр зависимости или значение параметра.
     * @throws Exception - Если параметр не может быть разрешен.
     */
    private function resolveDependency(ReflectionParameter $parameter, array $parameters = []): mixed
    {
        $name = $parameter->getName();
        $type = $parameter->getType();

        // Если параметр передан в $parameters, используем его.
        if (array_key_exists($name, $parameters)) {
            return $parameters[$name];
        }

        // Если параметр имеет тип класса, пытаемся разрешить его.
        if ($type && !$type->isBuiltin()) {
            return $this->resolve($type->getName());
        }

        // Если есть значение по умолчанию, возвращаем его.
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        throw new Exception(
            "Cannot resolve parameter: {$name} in {$parameter->getDeclaringClass()->getName()}"
        );
    }
}