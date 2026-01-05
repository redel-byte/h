<?php
session_start();
require_once '../index.php';

Auth::requireAuth();

$userRole = Auth::role();
$user = Auth::user();

// Role-based dashboard data
$stats = [];
$recentAppointments = [];
$upcomingAppointments = [];

// Mock data based on role
if ($userRole == 'admin') {
    $stats = [
        ['title' => 'Total Patients', 'value' => '1,245', 'icon' => 'fas fa-users', 'color' => 'bg-blue-500', 'change' => '+12%'],
        ['title' => 'Médecins Actifs', 'value' => '48', 'icon' => 'fas fa-user-md', 'color' => 'bg-green-500', 'change' => '+5%'],
        ['title' => 'Rendez-vous Aujourd\'hui', 'value' => '89', 'icon' => 'fas fa-calendar-check', 'color' => 'bg-purple-500', 'change' => '+8%'],
        ['title' => 'Prescriptions', 'value' => '342', 'icon' => 'fas fa-prescription', 'color' => 'bg-yellow-500', 'change' => '+15%'],
    ];
} elseif ($userRole == 'doctor') {
    $stats = [
        ['title' => 'Patients du Jour', 'value' => '12', 'icon' => 'fas fa-user-injured', 'color' => 'bg-blue-500'],
        ['title' => 'Rendez-vous En Attente', 'value' => '5', 'icon' => 'fas fa-clock', 'color' => 'bg-yellow-500'],
        ['title' => 'Prescriptions Créées', 'value' => '8', 'icon' => 'fas fa-file-medical', 'color' => 'bg-green-500'],
        ['title' => 'Heures Travaillées', 'value' => '6.5h', 'icon' => 'fas fa-chart-line', 'color' => 'bg-purple-500'],
    ];
} elseif ($userRole == 'patient') {
    $stats = [
        ['title' => 'Prochain RDV', 'value' => 'Demain 10:30', 'icon' => 'fas fa-calendar-alt', 'color' => 'bg-blue-500'],
        ['title' => 'Prescriptions Actives', 'value' => '3', 'icon' => 'fas fa-pills', 'color' => 'bg-green-500'],
        ['title' => 'Consultations Passées', 'value' => '24', 'icon' => 'fas fa-history', 'color' => 'bg-purple-500'],
        ['title' => 'Médecin Traitant', 'value' => 'Dr. Martin', 'icon' => 'fas fa-user-md', 'color' => 'bg-red-500'],
    ];
}
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Tableau de bord</h1>
    <p class="text-gray-600 mt-2">Bienvenue, <?php echo htmlspecialchars($user['first_name'] ?? 'Utilisateur'); ?>! Voici un aperçu de vos activités.</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <?php foreach ($stats as $stat): ?>
    <div class="stat-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500"><?php echo $stat['title']; ?></p>
                <p class="text-2xl font-bold text-gray-800 mt-2"><?php echo $stat['value']; ?></p>
                <?php if (isset($stat['change'])): ?>
                <p class="text-sm <?php echo strpos($stat['change'], '+') === 0 ? 'text-green-600' : 'text-red-600'; ?> font-medium mt-1">
                    <i class="fas <?php echo strpos($stat['change'], '+') === 0 ? 'fa-arrow-up' : 'fa-arrow-down'; ?> mr-1"></i>
                    <?php echo $stat['change']; ?>
                </p>
                <?php endif; ?>
            </div>
            <div class="<?php echo $stat['color']; ?> text-white rounded-full p-3">
                <i class="<?php echo $stat['icon']; ?> text-xl"></i>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Quick Actions -->
<div class="mb-8">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Actions Rapides</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php if ($userRole == 'patient'): ?>
        <a href="/patient/book-appointment.php" class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-blue-600 mb-2">
                <i class="fas fa-calendar-plus text-2xl"></i>
            </div>
            <p class="font-medium text-gray-800">Prendre RDV</p>
        </a>
        <a href="/prescriptions.php" class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-green-600 mb-2">
                <i class="fas fa-prescription text-2xl"></i>
            </div>
            <p class="font-medium text-gray-800">Mes Prescriptions</p>
        </a>
        <?php endif; ?>
        
        <?php if ($userRole == 'doctor'): ?>
        <a href="/doctor/schedule.php" class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-purple-600 mb-2">
                <i class="fas fa-clock text-2xl"></i>
            </div>
            <p class="font-medium text-gray-800">Mon Planning</p>
        </a>
        <a href="/appointments.php" class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-blue-600 mb-2">
                <i class="fas fa-calendar-check text-2xl"></i>
            </div>
            <p class="font-medium text-gray-800">Rendez-vous</p>
        </a>
        <?php endif; ?>
        
        <?php if ($userRole == 'admin'): ?>
        <a href="/admin/statistics.php" class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-red-600 mb-2">
                <i class="fas fa-chart-bar text-2xl"></i>
            </div>
            <p class="font-medium text-gray-800">Statistiques</p>
        </a>
        <a href="/admin/doctors.php" class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-blue-600 mb-2">
                <i class="fas fa-user-md text-2xl"></i>
            </div>
            <p class="font-medium text-gray-800">Gérer Médecins</p>
        </a>
        <?php endif; ?>
        
        <a href="/profile.php" class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-gray-600 mb-2">
                <i class="fas fa-user-cog text-2xl"></i>
            </div>
            <p class="font-medium text-gray-800">Mon Profil</p>
        </a>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">Activité Récente</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Type
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Détails
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                <tr class="table-row hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo date('d/m/Y H:i', strtotime("-$i hours")); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php 
                        $types = ['Rendez-vous', 'Prescription', 'Consultation', 'Paiement'];
                        echo $types[array_rand($types)];
                        ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo ['Dr. Martin', 'Dr. Dupont', 'Mme. Bernard', 'M. Petit'][array_rand([0,1,2,3])]; ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    Consultation de routine
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Terminé
                        </span>
                    </td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Notifications -->
<div class="mt-6">
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    <strong>Rappel:</strong> Pensez à mettre à jour vos informations médicales si nécessaire.
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../templates/footer.php'; ?>