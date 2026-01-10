<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/vendor/autoload.php';

$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$basePath = rtrim($basePath, '/');
if ($basePath === '/') {
    $basePath = '';
}
if (!defined('BASE_PATH')) {
    define('BASE_PATH', $basePath);
}

if (!class_exists('Auth', false)) {
    class_alias(\UnityCare\Services\Auth::class, 'Auth');
}
if (!class_exists('Database', false)) {
    class_alias(\UnityCare\Services\Database::class, 'Database');
}

if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') !== __FILE__) {
    return;
}

$request_uri = (string)($_SERVER['REQUEST_URI'] ?? '/');
$request_method = (string)($_SERVER['REQUEST_METHOD'] ?? 'GET');

$path = (string)(parse_url($request_uri, PHP_URL_PATH) ?? '/');

// Normalize: strip BASE_PATH prefix if present
if (BASE_PATH !== '' && strpos($path, BASE_PATH . '/') === 0) {
    $path = substr($path, strlen(BASE_PATH));
}

if ($path === '') {
    $path = '/';
}

// Root: redirect to dashboard/login
if ($path === '/' || $path === '/index.php') {
    if (\UnityCare\Services\Auth::check()) {
        \UnityCare\Services\Response::redirect(BASE_PATH . '/dashboard.php');
    }

    \UnityCare\Services\Response::redirect(BASE_PATH . '/login.php');
}

// Routes (new MVC URLs + legacy public URLs)
$routes = [
    '/login.php' => [\UnityCare\Controllers\AuthController::class, 'login'],
    '/logout.php' => [\UnityCare\Controllers\AuthController::class, 'logout'],
    '/dashboard.php' => [\UnityCare\Controllers\DashboardController::class, 'index'],
    '/appointments.php' => [\UnityCare\Controllers\AppointmentsController::class, 'index'],

    '/doctor/schedule.php' => [\UnityCare\Controllers\Doctor\ScheduleController::class, 'index'],
    '/doctor/prescriptions.php' => [\UnityCare\Controllers\Doctor\PrescriptionsController::class, 'index'],
    '/doctor/patients.php' => [\UnityCare\Controllers\Doctor\PatientsController::class, 'index'],

    '/patient/appointments.php' => [\UnityCare\Controllers\Patient\AppointmentsController::class, 'index'],
    '/patient/book-appointment.php' => [\UnityCare\Controllers\Patient\BookAppointmentController::class, 'index'],
    '/patient/prescriptions.php' => [\UnityCare\Controllers\Patient\PrescriptionsController::class, 'index'],
    '/admin/users.php' => [\UnityCare\Controllers\Admin\UsersController::class, 'index'],
    '/admin/doctors.php' => [\UnityCare\Controllers\Admin\DoctorsController::class, 'index'],
    '/admin/patients.php' => [\UnityCare\Controllers\Admin\PatientsController::class, 'index'],
    '/admin/medications.php' => [\UnityCare\Controllers\Admin\MedicationsController::class, 'index'],
    '/admin/departments.php' => [\UnityCare\Controllers\Admin\DepartmentsController::class, 'index'],
    '/admin/statistics.php' => [\UnityCare\Controllers\Admin\StatisticsController::class, 'index'],

    // Legacy paths (served by router if rewritten here)
    '/public/login.php' => [\UnityCare\Controllers\AuthController::class, 'login'],
    '/public/logout.php' => [\UnityCare\Controllers\AuthController::class, 'logout'],
    '/public/dashboard.php' => [\UnityCare\Controllers\DashboardController::class, 'index'],
    '/public/appointments.php' => [\UnityCare\Controllers\AppointmentsController::class, 'index'],
    '/public/admin/users.php' => [\UnityCare\Controllers\Admin\UsersController::class, 'index'],
    '/public/admin/doctors.php' => [\UnityCare\Controllers\Admin\DoctorsController::class, 'index'],
    '/public/admin/patients.php' => [\UnityCare\Controllers\Admin\PatientsController::class, 'index'],
    '/public/admin/medications.php' => [\UnityCare\Controllers\Admin\MedicationsController::class, 'index'],
    '/public/admin/departments.php' => [\UnityCare\Controllers\Admin\DepartmentsController::class, 'index'],
    '/public/admin/statistics.php' => [\UnityCare\Controllers\Admin\StatisticsController::class, 'index'],
];

if (isset($routes[$path])) {
    [$class, $method] = $routes[$path];
    $controller = new $class();
    $controller->$method();
    exit;
}

http_response_code(404);
echo 'Not Found';
