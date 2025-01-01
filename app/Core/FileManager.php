<?php

namespace App\Core\FileManager;

use Exception;

class FileManager
{
    /**
     * Чтение содержимого файла.
     */
    public function read($path)
    {
        if (!file_exists($path)) {
            throw new Exception("File not found: {$path}");
        }

        return file_get_contents($path);
    }

    /**
     * Запись в файл.
     */
    public function write($path, $content, $append = false)
    {
        $mode = $append ? 'a' : 'w';
        $file = fopen($path, $mode);

        if (!$file) {
            throw new Exception("Unable to open file: {$path}");
        }

        fwrite($file, $content);
        fclose($file);
    }

    /**
     * Удаление файла.
     */
    public function delete($path)
    {
        if (file_exists($path)) {
            if (!unlink($path)) {
                throw new Exception("Unable to delete file: {$path}");
            }
        } else {
            throw new Exception("File not found: {$path}");
        }
    }

    /**
     * Копирование файла.
     */
    public function copy($source, $destination)
    {
        if (!copy($source, $destination)) {
            throw new Exception("Unable to copy file from {$source} to {$destination}");
        }
    }

    /**
     * Перемещение файла.
     */
    public function move($source, $destination)
    {
        if (!rename($source, $destination)) {
            throw new Exception("Unable to move file from {$source} to {$destination}");
        }
    }

    /**
     * Создание директории.
     */
    public function createDirectory($path, $permissions = 0755)
    {
        if (!mkdir($path, $permissions, true)) {
            throw new Exception("Unable to create directory: {$path}");
        }
    }

    /**
     * Удаление директории.
     */
    public function deleteDirectory($path)
    {
        if (is_dir($path)) {
            $files = array_diff(scandir($path), ['.', '..']);
            foreach ($files as $file) {
                $this->deleteDirectory("$path/$file");
            }
            rmdir($path);
        } else {
            $this->delete($path);
        }
    }

    /**
     * Получение списка файлов в директории.
     */
    public function listFiles($path)
    {
        if (!is_dir($path)) {
            throw new Exception("Directory not found: {$path}");
        }

        return array_diff(scandir($path), ['.', '..']);
    }

    /**
     * Проверка существования файла.
     */
    public function exists($path)
    {
        return file_exists($path);
    }

    /**
     * Получение размера файла.
     */
    public function size($path)
    {
        if (!file_exists($path)) {
            throw new Exception("File not found: {$path}");
        }

        return filesize($path);
    }

    /**
     * Получение MIME-типа файла.
     */
    public function mimeType($path)
    {
        if (!file_exists($path)) {
            throw new Exception("File not found: {$path}");
        }

        return mime_content_type($path);
    }
}