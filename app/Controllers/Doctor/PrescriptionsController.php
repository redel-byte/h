<?php

namespace UnityCare\Controllers\Doctor;

use UnityCare\Services\Auth;
use UnityCare\Controllers\Controller;

class PrescriptionsController extends Controller
{
    public function index(): void
    {
        Auth::requireRole('doctor');

        $userRole = Auth::role();
        $user = Auth::user();

        $prescriptionRepo = new \UnityCare\Repositories\PrescriptionRepository(\UnityCare\Services\Database::connect());

        $prescriptions = [];
        try {
            $doctorId = (int) (Auth::id() ?? 0);
            $prescriptions = $prescriptionRepo->findByDoctor($doctorId);
        } catch (\Throwable $e) {
            $prescriptions = [];
        }

        $this->render('doctor/prescriptions', [
            'userRole' => $userRole,
            'user' => $user,
            'prescriptions' => $prescriptions,
        ]);
    }
}
