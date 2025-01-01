<?php

namespace App\Models;

use PDO;

class UserModel extends BaseModel
{
    protected $table = 'users';

    /**
     * Находит пользователя по email.
     *
     * @param string $email - Email пользователя.
     * @return array|null
     */
    public function findByEmail(string $email): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}