<?php


namespace App\Core\Http;

class Request
{
    /**
     * Метод запроса (GET, POST, PUT, DELETE и т.д.).
     */
    private string $method;

    /**
     * URI запроса.
     */
    private string $uri;

    /**
     * Параметры запроса (из query string или тела запроса).
     */
    private array $queryParams;

    /**
     * Заголовки запроса.
     */
    private array $headers;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->queryParams = $_GET;
        $this->headers = getallheaders();
    }

    /**
     * Возвращает метод запроса.
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Возвращает URI запроса.
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * Возвращает параметры запроса.
     */
    public function queryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * Возвращает значение параметра запроса по ключу.
     */
    public function query(string $key, $default = null)
    {
        return $this->queryParams[$key] ?? $default;
    }

    /**
     * Возвращает заголовки запроса.
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * Возвращает значение заголовка по ключу.
     */
    public function header(string $key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }
}