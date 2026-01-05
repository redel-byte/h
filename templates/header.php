<?php
session_start();
// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $_SESSION['role'] ?? 'guest';
$username = $_SESSION['username'] ?? 'Guest';

// Define role-based colors
$roleColors = [
    'admin' => 'bg-red-600',
    'doctor' => 'bg-blue-600',
    'patient' => 'bg-green-600'
];
$roleColor = $roleColors[$userRole] ?? 'bg-gray-600';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unity Care Clinic V2 - Backoffice</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/custom.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #7c3aed;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
        }
        
        .sidebar-link.active {
            background-color: rgba(37, 99, 235, 0.1);
            border-left: 4px solid var(--primary-color);
            color: var(--primary-color);
        }
        
        .sidebar-link:hover {
            background-color: rgba(37, 99, 235, 0.05);
        }
        
        .stat-card {
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .table-row:hover {
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- CSRF Token for forms -->
    <?php 
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    $csrf_token = $_SESSION['csrf_token'];
    ?>
    
    <?php if ($isLoggedIn): ?>
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <div class="flex items-center">
                            <i class="fas fa-clinic-medical text-2xl text-blue-600 mr-3"></i>
                            <h1 class="text-2xl font-bold text-gray-800">Unity Care Clinic</h1>
                            <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full <?php echo $roleColor; ?> text-white">
                                <?php echo ucfirst($userRole); ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- User menu -->
                <div class="flex items-center">
                    <div class="ml-3 relative">
                        <div class="flex items-center space-x-4">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-medium text-gray-700"><?php echo htmlspecialchars($username); ?></p>
                                <p class="text-xs text-gray-500"><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></p>
                            </div>
                            <div class="relative">
                                <button id="user-menu-button" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                    <a href="/profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user-circle mr-2"></i>Mon profil
                                    </a>
                                    <a href="/settings.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-cog mr-2"></i>Paramètres
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                    <a href="/logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="flex">
        <!-- Sidebar -->
        <aside class="bg-white w-64 min-h-screen shadow-md hidden md:block">
            <div class="p-4">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Navigation</h2>
                <nav>
                    <!-- Dashboard -->
                    <a href="/dashboard.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2 <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Tableau de bord
                    </a>
                    
                    <?php if (in_array($userRole, ['admin', 'doctor', 'patient'])): ?>
                    <!-- Appointments -->
                    <a href="/appointments.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2 <?php echo basename($_SERVER['PHP_SELF']) == 'appointments.php' ? 'active' : ''; ?>">
                        <i class="fas fa-calendar-check mr-3"></i>
                        Rendez-vous
                    </a>
                    <?php endif; ?>
                    
                    <?php if (in_array($userRole, ['doctor', 'patient'])): ?>
                    <!-- Prescriptions -->
                    <a href="/prescriptions.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2 <?php echo basename($_SERVER['PHP_SELF']) == 'prescriptions.php' ? 'active' : ''; ?>">
                        <i class="fas fa-prescription mr-3"></i>
                        Prescriptions
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($userRole == 'admin'): ?>
                    <!-- Admin Section -->
                    <div class="mt-6 mb-2">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Administration</h3>
                    </div>
                    
                    <a href="/admin/doctors.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2">
                        <i class="fas fa-user-md mr-3"></i>
                        Médecins
                    </a>
                    
                    <a href="/admin/patients.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2">
                        <i class="fas fa-users mr-3"></i>
                        Patients
                    </a>
                    
                    <a href="/admin/medications.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2">
                        <i class="fas fa-pills mr-3"></i>
                        Médicaments
                    </a>
                    
                    <a href="/admin/departments.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2">
                        <i class="fas fa-building mr-3"></i>
                        Départements
                    </a>
                    
                    <a href="/admin/statistics.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2">
                        <i class="fas fa-chart-bar mr-3"></i>
                        Statistiques
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($userRole == 'doctor'): ?>
                    <!-- Doctor Section -->
                    <div class="mt-6 mb-2">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Médecin</h3>
                    </div>
                    
                    <a href="/doctor/patients.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2">
                        <i class="fas fa-user-injured mr-3"></i>
                        Mes patients
                    </a>
                    
                    <a href="/doctor/schedule.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2">
                        <i class="fas fa-clock mr-3"></i>
                        Mon emploi du temps
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($userRole == 'patient'): ?>
                    <!-- Patient Section -->
                    <div class="mt-6 mb-2">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Patient</h3>
                    </div>
                    
                    <a href="/patient/history.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2">
                        <i class="fas fa-history mr-3"></i>
                        Mon historique
                    </a>
                    
                    <a href="/patient/book-appointment.php" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2">
                        <i class="fas fa-calendar-plus mr-3"></i>
                        Prendre rendez-vous
                    </a>
                    <?php endif; ?>
                </nav>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 p-6">
    <?php else: ?>
    <!-- Public pages layout (login, etc.) -->
    <main>
    <?php endif; ?>