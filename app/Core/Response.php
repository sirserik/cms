<?php
namespace App\Core\Http;

class Response
{
    /**
     * HTTP-статус код.
     */
    private int $statusCode = 200;

    /**
     * Заголовки ответа.
     */
    private array $headers = [];

    /**
     * Тело ответа.
     */
    private $content;

    /**
     * Устанавливает HTTP-статус код.
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Добавляет заголовок ответа.
     */
    public function header(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * Устанавливает тело ответа.
     */
    public function setContent($content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Отправляет ответ клиенту.
     */
    public function send(): void
    {
        // Устанавливаем HTTP-статус код
        http_response_code($this->statusCode);

        // Устанавливаем заголовки
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        // Отправляем тело ответа
        echo $this->content;
    }

    /**
     * Возвращает JSON-ответ.
     */
    public function json(array $data, int $statusCode = 200): self
    {
        $this->setStatusCode($statusCode)
            ->header('Content-Type', 'application/json')
            ->setContent(json_encode($data));

        return $this;
    }

    /**
     * Перенаправляет на другой URI.
     */
    public function redirect(string $uri, int $statusCode = 302): void
    {
        $this->setStatusCode($statusCode)
            ->header('Location', $uri)
            ->send();
        exit;
    }
}