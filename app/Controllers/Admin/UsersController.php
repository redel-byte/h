<?php

namespace UnityCare\Controllers\Admin;

use UnityCare\Controllers\Controller;
use UnityCare\Services\Auth;

class UsersController extends Controller
{
    public function index(): void
    {
        Auth::requireRole('admin');

        $repo = new \UnityCare\Repositories\UserRepository(\UnityCare\Services\Database::connect());

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $csrf_token = $_SESSION['csrf_token'];

        $error = '';
        $success = '';

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!is_string($token) || !hash_equals($csrf_token, $token)) {
                $error = 'Requête invalide.';
            } else {
                $email = trim((string)($_POST['email'] ?? ''));
                $username = trim((string)($_POST['username'] ?? ''));
                $password = (string)($_POST['password'] ?? '');
                $role = trim((string)($_POST['role'] ?? ''));

                $allowedRoles = ['admin', 'doctor', 'patient'];

                if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Email invalide.';
                } elseif ($username === '') {
                    $error = 'Nom d\'utilisateur requis.';
                } elseif ($password === '') {
                    $error = 'Mot de passe requis.';
                } elseif (!in_array($role, $allowedRoles, true)) {
                    $error = 'Rôle invalide.';
                } elseif ($repo->findByEmail($email)) {
                    $error = 'Un utilisateur avec cet email existe déjà.';
                } else {
                    $repo->create([
                        'email' => $email,
                        'username' => $username,
                        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                        'role' => $role,
                    ]);

                    $success = 'Utilisateur créé avec succès.';
                }
            }
        }

        $users = $repo->findAll();

        $this->render('admin/users', [
            'csrf_token' => $csrf_token,
            'error' => $error,
            'success' => $success,
            'users' => $users,
        ]);
    }
}
