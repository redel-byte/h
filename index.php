<?php

session_start();

require_once __DIR__ . '/Core/Database.php';
require_once __DIR__ . '/Core/BaseModel.php';
require_once __DIR__ . '/Core/Auth.php';
require_once __DIR__ . '/Core/Validator.php';

require_once __DIR__ . '/classes/repositories/BaseRepository.php';
require_once __DIR__ . '/classes/repositories/UserRepository.php';
require_once __DIR__ . '/classes/repositories/DoctorRepository.php';
require_once __DIR__ . '/classes/repositories/PatientRepository.php';
require_once __DIR__ . '/classes/repositories/AppointmentRepository.php';
require_once __DIR__ . '/classes/repositories/PrescriptionRepository.php';
require_once __DIR__ . '/classes/repositories/MedicationRepository.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_uri === '/' || $request_uri === '/index.php') {
    if (Auth::check()) {
        header('Location: /dashboard.php');
    } else {
        header('Location: /login.php');
    }
    exit;
}

if (preg_match('/^\/public\/(.+)/', $request_uri, $matches)) {
    $file_path = __DIR__ . '/public/' . $matches[1];
    
    if (file_exists($file_path) && !is_dir($file_path)) {
        $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        
        if ($extension === 'php') {
            require_once $file_path;
            exit;
        }
        
        $mime_types = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon'
        ];
        
        if (isset($mime_types[$extension])) {
            header('Content-Type: ' . $mime_types[$extension]);
            readfile($file_path);
            exit;
        }
    }
}

$public_php_files = [
    '/login.php' => 'public/login.php',
    '/dashboard.php' => 'public/dashboard.php',
    '/appointments.php' => 'public/appointments.php',
    '/logout.php' => 'public/logout.php'
];

if (isset($public_php_files[$request_uri])) {
    $file_path = __DIR__ . '/' . $public_php_files[$request_uri];
    if (file_exists($file_path)) {
        require_once $file_path;
        exit;
    }
}

http_response_code(404);
echo 'Page not found';