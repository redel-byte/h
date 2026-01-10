<?php

namespace UnityCare\Controllers;

use UnityCare\Services\Auth;

class AppointmentsController extends Controller
{
    public function index(): void
    {
        Auth::requireAuth();

        $userRole = Auth::role();
        $user = Auth::user();

        $appointmentRepo = new \UnityCare\Repositories\AppointmentRepository(\UnityCare\Services\Database::connect());
        $doctorRepo = new \UnityCare\Repositories\DoctorRepository(\UnityCare\Services\Database::connect());
        $patientRepo = new \UnityCare\Repositories\PatientRepository(\UnityCare\Services\Database::connect());

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

        $this->render('appointments/index', [
            'userRole' => $userRole,
            'user' => $user,
            'appointments' => $appointments,
            'doctors' => $doctors,
            'patients' => $patients,
        ]);
    }
}
