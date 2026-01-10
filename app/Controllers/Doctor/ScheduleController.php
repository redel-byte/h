<?php

namespace UnityCare\Controllers\Doctor;

use UnityCare\Controllers\Controller;
use UnityCare\Services\Auth;

class ScheduleController extends Controller
{
    public function index(): void
    {
        Auth::requireRole('doctor');

        $userRole = Auth::role();
        $user = Auth::user();

        $appointmentRepo = new \UnityCare\Repositories\AppointmentRepository(\UnityCare\Services\Database::connect());

        $appointments = [];
        try {
            $doctorId = (int) (Auth::id() ?? 0);
            $appointments = $appointmentRepo->findByDoctor($doctorId);
        } catch (\Throwable $e) {
            $appointments = [];
        }

        $this->render('doctor/schedule', [
            'userRole' => $userRole,
            'user' => $user,
            'appointments' => $appointments,
        ]);
    }
}
