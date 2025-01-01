<?php
namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    private $userModel;

    public function __construct(UserModel $userModel, View $view, Language $language, SessionManager $session)
    {
        parent::__construct($view, $language, $session);
        $this->userModel = $userModel;
    }

    public function profile(int $userId)
    {
        $user = $this->userModel->find($userId);

        if ($user) {
            $this->render('user/profile', [
                'user' => $user,
                'title' => 'User Profile',
            ]);
        } else {
            $this->redirect('/404');
        }
    }
}