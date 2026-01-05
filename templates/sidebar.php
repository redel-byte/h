<?php
$userRole = Auth::role() ?? 'guest';
$user = Auth::user();
?>

<aside class="w-64 bg-white shadow-lg h-screen sticky top-0">
    <div class="p-4">
        <div class="flex items-center space-x-3 mb-8">
            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                <i class="fas fa-clinic-medical text-white"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">Unity Care</h2>
                <p class="text-xs text-gray-500">Clinic V2</p>
            </div>
        </div>

        <!-- User Info -->
        <div class="mb-6 p-3 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-blue-600 text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">
                        <?php echo htmlspecialchars($user['first_name'] ?? 'Utilisateur'); ?>
                    </p>
                    <p class="text-xs text-gray-500 capitalize"><?php echo $userRole; ?></p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="space-y-2">
            <a href="/dashboard.php" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                <i class="fas fa-dashboard w-5"></i>
                <span>Tableau de bord</span>
            </a>

            <?php if ($userRole === 'admin'): ?>
                <!-- Admin Menu -->
                <a href="/admin/statistics.php" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span>Statistiques</span>
                </a>
                <a href="/admin/doctors.php" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-user-md w-5"></i>
                    <span>Médecins</span>
                </a>
                <a href="/admin/patients.php" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-users w-5"></i>
                    <span>Patients</span>
                </a>
                <a href="/admin/medications.php" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-pills w-5"></i>
                    <span>Médicaments</span>
                </a>
                <a href="/admin/departments.php" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-building w-5"></i>
                    <span>Départements</span>
                </a>
            <?php endif; ?>

            <?php if ($userRole === 'doctor'): ?>
                <!-- Doctor Menu -->
                <a href="/doctor/schedule.php" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-calendar-alt w-5"></i>
                    <span>Mon Planning</span>
                </a>
                <a href="/doctor/prescriptions.php" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-prescription w-5"></i>
                    <span>Prescriptions</span>
                </a>
                <a href="/doctor/patients.php" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-user-injured w-5"></i>
                    <span>Mes Patients</span>
                </a>
            <?php endif; ?>

            <?php if ($userRole === 'patient'): ?>
                <!-- Patient Menu -->
                <a href="/patient/appointments.php" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-calendar-check w-5"></i>
                    <span>Mes Rendez-vous</span>
                </a>
                <a href="/patient/book-appointment.php" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-plus-circle w-5"></i>
                    <span>Prendre RDV</span>
                </a>
                <a href="/patient/prescriptions.php" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-file-medical w-5"></i>
                    <span>Mes Prescriptions</span>
                </a>
            <?php endif; ?>

            <!-- Common Menu Items -->
            <a href="/appointments.php" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                <i class="fas fa-calendar w-5"></i>
                <span>Rendez-vous</span>
            </a>

            <!-- Profile Section -->
            <div class="pt-4 mt-4 border-t border-gray-200">
                <a href="/profile.php" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-user-cog w-5"></i>
                    <span>Mon Profil</span>
                </a>
                <a href="/logout.php" class="nav-item flex items-center space-x-3 px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </nav>
    </div>
</aside>

<script>
// Highlight current page
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    const navItems = document.querySelectorAll('.nav-item');
    
    navItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href && currentPath.includes(href.replace('.php', ''))) {
            item.classList.add('bg-blue-50', 'text-blue-600');
        }
    });
});
</script>