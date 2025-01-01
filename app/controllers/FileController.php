<?php

use App\Controllers\BaseController;
use App\Core\FileManager\FileManager;

class FileController extends BaseController
{
    private $fileManager;

    public function __construct(FileManager $fileManager, View $view, Language $language, Auth $auth)
    {
        parent::__construct($view, $language, $auth);
        $this->fileManager = $fileManager;
    }

    public function deleteFile($filename)
    {
        $filePath = 'uploads/' . $filename;

        try {
            $this->fileManager->delete($filePath);
            echo "File deleted successfully!";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}