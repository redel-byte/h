<?php

namespace UnityCare\Controllers\Patient;

use UnityCare\Services\Auth;
use UnityCare\Controllers\Controller;

class BookAppointmentController extends Controller
{
    public function index(): void
    {
        Auth::requireRole('patient');

        $userRole = Auth::role();
        $user = Auth::user();

        $doctorRepo = new \UnityCare\Repositories\DoctorRepository(\UnityCare\Services\Database::connect());
        $appointmentRepo = new \UnityCare\Repositories\AppointmentRepository(\UnityCare\Services\Database::connect());

        $doctors = [];
        $error = '';
        $success = '';

        try {
            $doctors = $doctorRepo->findAll();
        } catch (\Throwable $e) {
            $doctors = [];
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $date = (string)($_POST['date'] ?? '');
            $time = (string)($_POST['time'] ?? '');
            $doctorId = (int)($_POST['doctor_id'] ?? 0);
            $reason = (string)($_POST['reason'] ?? '');

            if ($date === '' || $time === '' || $doctorId <= 0) {
                $error = 'Veuillez remplir tous les champs requis.';
            } else {
                try {
                    $appointmentRepo->create([
                        'date' => $date,
                        'time' => $time,
                        'doctor_id' => $doctorId,
                        'patient_id' => (int)(Auth::id() ?? 0),
                        'reason' => $reason,
                        'status' => 'scheduled',
                    ]);
                    $success = 'Rendez-vous créé avec succès.';
                } catch (\Throwable $e) {
                    $error = 'Erreur lors de la création du rendez-vous.';
                }
            }
        }

        $this->render('patient/book-appointment', [
            'userRole' => $userRole,
            'user' => $user,
            'doctors' => $doctors,
            'error' => $error,
            'success' => $success,
        ]);
    }
}
