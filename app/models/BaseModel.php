<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Core\ErrorHandler\ErrorHandler;

class BaseModel
{
    protected PDO $pdo;
    protected string $table;
    protected ErrorHandler $errorHandler;

    public function __construct(PDO $pdo, ErrorHandler $errorHandler, string $table)
    {
        $this->pdo = $pdo;
        $this->errorHandler = $errorHandler;
        $this->table = $table;
    }

    /**
     * Возвращает все записи.
     *
     * @return array
     */
    public function all(): array
    {
        try {
            $query = "SELECT * FROM {$this->table}";
            $stmt = $this->pdo->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->errorHandler->handleException($e);
            return [];
        }
    }
}