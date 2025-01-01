<?php

namespace App\Controllers;

use App\Core\FileManager\FileManager;

class UploadController extends BaseController
{
    private $fileManager;

    public function __construct(FileManager $fileManager, View $view, Language $language, Auth $auth)
    {
        parent::__construct($view, $language, $auth);
        $this->fileManager = $fileManager;
    }

    /**
     * Обработка загрузки файла (POST).
     */
    public function upload()
    {
        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir . basename($_FILES['file']['name']);

            $this->fileManager->move($_FILES['file']['tmp_name'], $uploadFile);

            echo "File uploaded successfully!";
        } else {
            echo "Error uploading file.";
        }
    }
}