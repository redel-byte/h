<?php
require_once '../templates/header.php';

// Check authentication
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

$userRole = $_SESSION['role'];
?>

<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Gestion des Rendez-vous</h1>
            <p class="text-gray-600 mt-2">Consultez et gérez vos rendez-vous médicaux</p>
        </div>
        <?php if (in_array($userRole, ['admin', 'doctor', 'patient'])): ?>
        <a href="/patient/book-appointment.php" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Nouveau Rendez-vous
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="flex flex-wrap items-center gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
            <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous les statuts</option>
                <option value="scheduled">Programmé</option>
                <option value="done">Effectué</option>
                <option value="cancelled">Annulé</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            <input type="date" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <?php if ($userRole == 'admin'): ?>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Médecin</label>
            <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous les médecins</option>
                <option value="1">Dr. Martin</option>
                <option value="2">Dr. Dupont</option>
            </select>
        </div>
        <?php endif; ?>
        
        <div class="flex items-end">
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                <i class="fas fa-filter mr-2"></i>Filtrer
            </button>
        </div>
    </div>
</div>

<!-- Appointments Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Patient / Médecin
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date & Heure
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Motif
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php 
                $appointments = [
                    ['id' => 1, 'date' => '2024-01-15', 'time' => '10:30', 'patient' => 'Mme. Bernard', 'doctor' => 'Dr. Martin', 'reason' => 'Consultation générale', 'status' => 'scheduled'],
                    ['id' => 2, 'date' => '2024-01-14', 'time' => '14:00', 'patient' => 'M. Petit', 'doctor' => 'Dr. Dupont', 'reason' => 'Suivi traitement', 'status' => 'done'],
                    ['id' => 3, 'date' => '2024-01-16', 'time' => '09:00', 'patient' => 'M. Durand', 'doctor' => 'Dr. Martin', 'reason' => 'Première visite', 'status' => 'scheduled'],
                    ['id' => 4, 'date' => '2024-01-13', 'time' => '11:30', 'patient' => 'Mme. Lambert', 'doctor' => 'Dr. Dupont', 'reason' => 'Vaccination', 'status' => 'cancelled'],
                ];
                
                foreach ($appointments as $appointment): 
                    $statusColors = [
                        'scheduled' => 'bg-blue-100 text-blue-800',
                        'done' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800'
                    ];
                    $statusTexts = [
                        'scheduled' => 'Programmé',
                        'done' => 'Effectué',
                        'cancelled' => 'Annulé'
                    ];
                ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo ($userRole == 'doctor' || $userRole == 'admin') ? htmlspecialchars($appointment['patient']) : htmlspecialchars($appointment['doctor']); ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?php echo ($userRole == 'doctor' || $userRole == 'admin') ? 'Patient' : 'Médecin'; ?>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 font-medium">
                            <?php echo date('d/m/Y', strtotime($appointment['date'])); ?>
                        </div>
                        <div class="text-sm text-gray-500">
                            <?php echo $appointment['time']; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900"><?php echo htmlspecialchars($appointment['reason']); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusColors[$appointment['status']]; ?>">
                            <?php echo $statusTexts[$appointment['status']]; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-blue-600 hover:text-blue-900 mr-3" title="Voir détails">
                            <i class="fas fa-eye"></i>
                        </button>
                        <?php if ($appointment['status'] == 'scheduled'): ?>
                        <button class="text-green-600 hover:text-green-900 mr-3" title="Marquer comme effectué">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-900" title="Annuler">
                            <i class="fas fa-times"></i>
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Précédent
                </a>
                <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Suivant
                </a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Affichage de <span class="font-medium">1</span> à <span class="font-medium">4</span> sur <span class="font-medium">4</span> résultats
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Précédent</span>
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            1
                        </a>
                        <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            2
                        </a>
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Suivant</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Calendar View (Optional) -->
<div class="mt-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">Vue Calendrier</h2>
        <div class="flex space-x-2">
            <button class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Jour
            </button>
            <button class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Semaine
            </button>
            <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700">
                Mois
            </button>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <!-- Calendar would be implemented with JavaScript -->
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-calendar-alt text-4xl mb-3"></i>
            <p>Vue calendrier (implémentation avec JavaScript)</p>
        </div>
    </div>
</div>

<script>
// JavaScript for appointment management
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterBtn = document.querySelector('button.bg-blue-600');
    filterBtn.addEventListener('click', function() {
        alert('Filtrage des rendez-vous...');
        // AJAX call to filter appointments
    });
    
    // Status change handlers
    document.querySelectorAll('button.text-green-600').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Marquer ce rendez-vous comme effectué?')) {
                // AJAX call to update status
                alert('Rendez-vous marqué comme effectué');
            }
        });
    });
    
    document.querySelectorAll('button.text-red-600').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Annuler ce rendez-vous?')) {
                // AJAX call to cancel appointment
                alert('Rendez-vous annulé');
            }
        });
    });
});
</script>

<?php require_once '../templates/footer.php'; ?>