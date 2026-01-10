<?php

namespace UnityCare\Controllers\Doctor;

use UnityCare\Services\Auth;
use UnityCare\Controllers\Controller;

class PatientsController extends Controller
{
    public function index(): void
    {
        Auth::requireRole('doctor');

        $userRole = Auth::role();
        $user = Auth::user();

        $patientRepo = new \UnityCare\Repositories\PatientRepository(\UnityCare\Services\Database::connect());

        $patients = [];
        try {
            $patients = $patientRepo->findAll();
        } catch (\Throwable $e) {
            $patients = [];
        }

        $this->render('doctor/patients', [
            'userRole' => $userRole,
            'user' => $user,
            'patients' => $patients,
        ]);
    }
}
