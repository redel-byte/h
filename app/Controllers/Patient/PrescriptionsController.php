<?php

namespace UnityCare\Controllers\Patient;

use UnityCare\Services\Auth;
use UnityCare\Controllers\Controller;

class PrescriptionsController extends Controller
{
    public function index(): void
    {
        Auth::requireRole('patient');

        $userRole = Auth::role();
        $user = Auth::user();

        $prescriptionRepo = new \UnityCare\Repositories\PrescriptionRepository(\UnityCare\Services\Database::connect());

        $prescriptions = [];
        try {
            $patientId = (int) (Auth::id() ?? 0);
            $prescriptions = $prescriptionRepo->findByPatient($patientId);
        } catch (\Throwable $e) {
            $prescriptions = [];
        }

        $this->render('patient/prescriptions', [
            'userRole' => $userRole,
            'user' => $user,
            'prescriptions' => $prescriptions,
        ]);
    }
}
