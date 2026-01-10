<?php

namespace UnityCare\Controllers;

use UnityCare\Services\Auth;

class AuthController extends Controller
{
    public function login(): void
    {
        if (Auth::check()) {
            $this->redirect((defined('BASE_PATH') ? constant('BASE_PATH') : '') . '/dashboard.php');
        }

        $error = '';
        $old = [
            'email' => '',
        ];

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $email = (string)($_POST['email'] ?? '');
            $password = (string)($_POST['password'] ?? '');

            $old['email'] = $email;

            if (Auth::login($email, $password)) {
                $this->redirect((defined('BASE_PATH') ? constant('BASE_PATH') : '') . '/dashboard.php');
            }

            $error = 'Email ou mot de passe incorrect.';
        }

        $this->render('auth/login', [
            'error' => $error,
            'old' => $old,
        ]);
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect((defined('BASE_PATH') ? constant('BASE_PATH') : '') . '/login.php');
    }
}
