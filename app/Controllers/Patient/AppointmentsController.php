<?php

namespace UnityCare\Controllers\Patient;

use UnityCare\Services\Auth;
use UnityCare\Controllers\Controller;

class AppointmentsController extends Controller
{
    public function index(): void
    {
        Auth::requireRole('patient');

        $userRole = Auth::role();
        $user = Auth::user();

        $appointmentRepo = new \UnityCare\Repositories\AppointmentRepository(\UnityCare\Services\Database::connect());
        $doctorRepo = new \UnityCare\Repositories\DoctorRepository(\UnityCare\Services\Database::connect());

        $appointments = [];
        $doctors = [];

        try {
            $patientId = (int) (Auth::id() ?? 0);
            $appointments = $appointmentRepo->findByPatient($patientId);
            $doctors = $doctorRepo->findAll();
        } catch (\Throwable $e) {
            $appointments = [];
            $doctors = [];
        }

        $this->render('patient/appointments', [
            'userRole' => $userRole,
            'user' => $user,
            'appointments' => $appointments,
            'doctors' => $doctors,
        ]);
    }
}
