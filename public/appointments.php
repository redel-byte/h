<?php
session_start();
require_once '../index.php';

Auth::requireAuth();

$userRole = Auth::role();
$user = Auth::user();

$appointmentRepo = new AppointmentRepository(Database::connect());
$doctorRepo = new DoctorRepository(Database::connect());
$patientRepo = new PatientRepository(Database::connect());

$appointments = [];
$doctors = [];
$patients = [];

if ($userRole === 'admin') {
    $appointments = $appointmentRepo->findAll();
    $doctors = $doctorRepo->findAll();
    $patients = $patientRepo->findAll();
} elseif ($userRole === 'doctor') {
    $appointments = $appointmentRepo->findByDoctor($user['id']);
    $patients = $patientRepo->findAll();
} elseif ($userRole === 'patient') {
    $appointments = $appointmentRepo->findByPatient($user['id']);
    $doctors = $doctorRepo->findAll();
}
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
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous</option>
                <option value="scheduled">Programmé</option>
                <option value="done">Terminé</option>
                <option value="cancelled">Annulé</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <?php if ($userRole === 'admin'): ?>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Médecin</label>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous</option>
                <?php foreach ($doctors as $doctor): ?>
                <option value="<?php echo $doctor['id']; ?>">
                    Dr. <?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
        <div class="flex items-end">
            <button class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                <i class="fas fa-filter mr-2"></i>
                Filtrer
            </button>
        </div>
    </div>
</div>

<!-- Appointments Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">Liste des Rendez-vous</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date & Heure
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Patient
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Médecin
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Motif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($appointments)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-calendar-times text-4xl mb-4"></i>
                        <p>Aucun rendez-vous trouvé</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($appointments as $appointment): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php 
                            $date = new DateTime($appointment['date']);
                            $time = new DateTime($appointment['time']);
                            echo $date->format('d/m/Y') . ' à ' . $time->format('H:i');
                            ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600 text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($appointment['patient_first_name'] . ' ' . $appointment['patient_last_name']); ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-md text-green-600 text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        Dr. <?php echo htmlspecialchars($appointment['doctor_first_name'] . ' ' . $appointment['doctor_last_name']); ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?php echo htmlspecialchars($appointment['specialization'] ?? ''); ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <?php echo htmlspecialchars($appointment['reason'] ?? 'Non spécifié'); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php
                            $statusClass = [
                                'scheduled' => 'bg-blue-100 text-blue-800',
                                'done' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                            $statusText = [
                                'scheduled' => 'Programmé',
                                'done' => 'Terminé',
                                'cancelled' => 'Annulé'
                            ];
                            $status = $appointment['status'] ?? 'scheduled';
                            ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass[$status]; ?>">
                                <?php echo $statusText[$status]; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <?php if ($status === 'scheduled' && ($userRole === 'admin' || ($userRole === 'doctor' && $appointment['doctor_id'] == $user['id']) || ($userRole === 'patient' && $appointment['patient_id'] == $user['id']))): ?>
                                    <button class="text-blue-600 hover:text-blue-900" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-900" title="Annuler" onclick="cancelAppointment(<?php echo $appointment['id']; ?>)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                <?php endif; ?>
                                <?php if ($userRole === 'doctor' && $status === 'scheduled' && $appointment['doctor_id'] == $user['id']): ?>
                                    <button class="text-green-600 hover:text-green-900" title="Marquer comme terminé" onclick="completeAppointment(<?php echo $appointment['id']; ?>)">
                                        <i class="fas fa-check"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function cancelAppointment(id) {
    if (confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')) {
        // Implement AJAX call to cancel appointment
        window.location.reload();
    }
}

function completeAppointment(id) {
    if (confirm('Êtes-vous sûr de vouloir marquer ce rendez-vous comme terminé ?')) {
        // Implement AJAX call to complete appointment
        window.location.reload();
    }
}
</script>
