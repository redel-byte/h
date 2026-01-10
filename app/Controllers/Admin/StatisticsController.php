<?php

namespace UnityCare\Controllers\Admin;

use UnityCare\Services\Auth;
use UnityCare\Services\Database;
use UnityCare\Core\Response;

class StatisticsController
{
    public function index()
    {
        if (!Auth::check() || Auth::role() !== 'admin') {
            $base = defined('BASE_PATH') ? constant('BASE_PATH') : '';
            Response::redirect($base . '/login.php');
            return;
        }

        $db = Database::getInstance();
        $statistics = [];

        try {
            // Get total counts
            $statistics['total_doctors'] = $this->getTotalDoctors($db);
            $statistics['total_patients'] = $this->getTotalPatients($db);
            $statistics['total_appointments'] = $this->getTotalAppointments($db);
            $statistics['total_departments'] = $this->getTotalDepartments($db);
            $statistics['total_medications'] = $this->getTotalMedications($db);

            // Get recent appointments
            $statistics['recent_appointments'] = $this->getRecentAppointments($db);

            // Get appointments by month
            $statistics['appointments_by_month'] = $this->getAppointmentsByMonth($db);

            // Get doctors by department
            $statistics['doctors_by_department'] = $this->getDoctorsByDepartment($db);

        } catch (\PDOException $e) {
            $error = 'Erreur lors de la récupération des statistiques';
        }

        include __DIR__ . '/../../../resources/views/admin/statistics.php';
    }

    private function getTotalDoctors($db)
    {
        $stmt = $db->query("SELECT COUNT(*) as count FROM doctors d JOIN users u ON d.user_id = u.id WHERE u.role = 'doctor'");
        return $stmt->fetch(\PDO::FETCH_ASSOC)['count'];
    }

    private function getTotalPatients($db)
    {
        $stmt = $db->query("SELECT COUNT(*) as count FROM patients p JOIN users u ON p.user_id = u.id WHERE u.role = 'patient'");
        return $stmt->fetch(\PDO::FETCH_ASSOC)['count'];
    }

    private function getTotalAppointments($db)
    {
        $stmt = $db->query("SELECT COUNT(*) as count FROM appointments");
        return $stmt->fetch(\PDO::FETCH_ASSOC)['count'];
    }

    private function getTotalDepartments($db)
    {
        $stmt = $db->query("SELECT COUNT(*) as count FROM departments");
        return $stmt->fetch(\PDO::FETCH_ASSOC)['count'];
    }

    private function getTotalMedications($db)
    {
        $stmt = $db->query("SELECT COUNT(*) as count FROM medications");
        return $stmt->fetch(\PDO::FETCH_ASSOC)['count'];
    }

    private function getRecentAppointments($db)
    {
        $stmt = $db->query("
            SELECT a.*, u.first_name, u.last_name, d.first_name as doctor_first_name, d.last_name as doctor_last_name
            FROM appointments a
            JOIN users u ON a.patient_id = u.id
            JOIN users d ON a.doctor_id = d.id
            ORDER BY a.appointment_date DESC, a.appointment_time DESC
            LIMIT 10
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getAppointmentsByMonth($db)
    {
        $stmt = $db->query("
            SELECT 
                DATE_FORMAT(appointment_date, '%Y-%m') as month,
                COUNT(*) as count
            FROM appointments
            WHERE appointment_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(appointment_date, '%Y-%m')
            ORDER BY month
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getDoctorsByDepartment($db)
    {
        $stmt = $db->query("
            SELECT 
                d.name as department_name,
                COUNT(dr.id) as doctor_count
            FROM departments d
            LEFT JOIN doctors dr ON d.id = dr.department_id
            GROUP BY d.id, d.name
            ORDER BY doctor_count DESC
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
