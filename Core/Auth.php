<?php

class Auth
{
    private static ?array $currentUser = null;

    public static function login(string $email, string $password): bool
    {
        $pdo = Database::connect();
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
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
            $pdo = Database::connect();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->execute(['id' => $_SESSION['user_id']]);
            self::$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
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
            header('Location: /login.php');
            exit;
        }
    }

    public static function requireRole(string $role): void
    {
        self::requireAuth();
        
        if (self::role() !== $role) {
            http_response_code(403);
            echo 'Access denied';
            exit;
        }
    }

    public static function requireAnyRole(array $roles): void
    {
        self::requireAuth();
        
        if (!in_array(self::role(), $roles)) {
            http_response_code(403);
            echo 'Access denied';
            exit;
        }
    }
}