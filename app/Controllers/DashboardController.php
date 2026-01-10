<?php

namespace UnityCare\Controllers;

use UnityCare\Services\Auth;

class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::requireAuth();

        $userRole = Auth::role();
        $user = Auth::user();

        $stats = [];

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

        $this->render('dashboard/index', [
            'userRole' => $userRole,
            'user' => $user,
            'stats' => $stats,
        ]);
    }
}
