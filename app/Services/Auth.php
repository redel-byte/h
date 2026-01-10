<?php

namespace UnityCare\Services;

class Auth
{
    private static ?array $currentUser = null;

    public static function login(string $email, string $password): bool
    {
        $repo = new \UnityCare\Repositories\UserRepository(Database::connect());
        $user = $repo->findByEmail($email);

        $passwordHash = null;
        if (is_array($user)) {
            // Support both schema variants
            $passwordHash = $user['password_hash'] ?? ($user['password'] ?? null);
        }

        if ($user && is_string($passwordHash) && password_verify($password, $passwordHash)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_email'] = $user['email'];

            self::$currentUser = $user;
            return true;
        }

        return false;
    }

    public static function logout(): void
    {
        session_destroy();
        self::$currentUser = null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array
    {
        if (self::$currentUser === null && self::check()) {
            $repo = new \UnityCare\Repositories\UserRepository(Database::connect());
            self::$currentUser = $repo->findById((int) $_SESSION['user_id']);
        }
        return self::$currentUser;
    }

    public static function role(): ?string
    {
        return $_SESSION['user_role'] ?? null;
    }

    public static function id(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function requireAuth(): void
    {
        if (!self::check()) {
            $base = defined('BASE_PATH') ? constant('BASE_PATH') : '';
            Response::redirect($base . '/login.php');
        }
    }

    public static function requireRole(string $role): void
    {
        self::requireAuth();

        if (self::role() !== $role) {
            // http_response_code(403);
            echo 'Access denied';
            exit;
        }
    }

    public static function requireAnyRole(array $roles): void
    {
        self::requireAuth();

        if (!in_array(self::role(), $roles)) {
            // http_response_code(403);
            echo 'Access denied';
            exit;
        }
    }
}