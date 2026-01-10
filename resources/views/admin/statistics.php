<?php
require_once __DIR__ . '/../../templates/header.php';
?>

<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Statistiques</h1>
            <p class="text-sm text-gray-600">Vue d'ensemble des statistiques de la clinique</p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo defined('BASE_PATH') ? constant('BASE_PATH') : ''; ?>/dashboard.php" class="px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-50">Retour</a>
            <a href="<?php echo defined('BASE_PATH') ? constant('BASE_PATH') : ''; ?>/logout.php" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">Déconnexion</a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white shadow rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-user-md text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Médecins</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars((string)($statistics['total_doctors'] ?? 0)); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Patients</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars((string)($statistics['total_patients'] ?? 0)); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-calendar-check text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Rendez-vous</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars((string)($statistics['total_appointments'] ?? 0)); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-full">
                    <i class="fas fa-building text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Départements</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars((string)($statistics['total_departments'] ?? 0)); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-pills text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Médicaments</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars((string)($statistics['total_medications'] ?? 0)); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Recent Appointments -->
        <div class="bg-white shadow rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Rendez-vous récents</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médecin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach (($statistics['recent_appointments'] ?? []) as $appointment): ?>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <?php echo htmlspecialchars((string)($appointment['first_name'] ?? '')); ?> 
                                    <?php echo htmlspecialchars((string)($appointment['last_name'] ?? '')); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    Dr. <?php echo htmlspecialchars((string)($appointment['doctor_first_name'] ?? '')); ?> 
                                    <?php echo htmlspecialchars((string)($appointment['doctor_last_name'] ?? '')); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <?php echo htmlspecialchars((string)($appointment['appointment_date'] ?? '')); ?>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 rounded-full text-xs <?php echo ($appointment['status'] ?? '') === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                        <?php echo htmlspecialchars((string)($appointment['status'] ?? 'pending')); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Doctors by Department -->
        <div class="bg-white shadow rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Médecins par département</h2>
            </div>
            <div class="p-6">
                <?php foreach (($statistics['doctors_by_department'] ?? []) as $dept): ?>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-600 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-700"><?php echo htmlspecialchars((string)($dept['department_name'] ?? 'Non assigné')); ?></span>
                        </div>
                        <span class="text-sm font-medium text-gray-800"><?php echo htmlspecialchars((string)($dept['doctor_count'] ?? 0)); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Appointments by Month Chart -->
    <div class="bg-white shadow rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Rendez-vous par mois</h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <?php foreach (($statistics['appointments_by_month'] ?? []) as $month): ?>
                    <div class="flex items-center">
                        <div class="w-24 text-sm text-gray-600"><?php echo htmlspecialchars((string)($month['month'] ?? '')); ?></div>
                        <div class="flex-1 mx-4">
                            <div class="bg-gray-200 rounded-full h-6">
                                <div class="bg-blue-600 h-6 rounded-full flex items-center justify-end pr-2" style="width: <?php echo min(100, (int)($month['count'] ?? 0) * 10); ?>%">
                                    <span class="text-xs text-white font-medium"><?php echo htmlspecialchars((string)($month['count'] ?? 0)); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../../templates/footer.php';
?>
