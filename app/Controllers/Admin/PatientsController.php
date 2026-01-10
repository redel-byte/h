<?php

namespace UnityCare\Controllers\Admin;

use UnityCare\Services\Auth;
use UnityCare\Services\Database;
use UnityCare\Core\Response;

class PatientsController
{
    public function index()
    {
        if (!Auth::check() || Auth::role() !== 'admin') {
            $base = defined('BASE_PATH') ? constant('BASE_PATH') : '';
            Response::redirect($base . '/login.php');
            return;
        }

        $db = Database::getInstance();
        $patients = [];
        $error = '';
        $success = '';

        // Handle form submissions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrf_token = $_POST['csrf_token'] ?? '';
            if (!isset($_SESSION['csrf_token']) || $csrf_token !== $_SESSION['csrf_token']) {
                $error = 'Token CSRF invalide';
            } else {
                $action = $_POST['action'] ?? '';
                
                if ($action === 'create') {
                    $result = $this->createPatient($db, $_POST);
                    if ($result['success']) {
                        $success = 'Patient créé avec succès';
                    } else {
                        $error = $result['error'];
                    }
                } elseif ($action === 'delete') {
                    $result = $this->deletePatient($db, $_POST['id']);
                    if ($result['success']) {
                        $success = 'Patient supprimé avec succès';
                    } else {
                        $error = $result['error'];
                    }
                }
            }
        }

        // Fetch all patients
        try {
            $stmt = $db->query("
                SELECT p.*, u.email, u.username, u.first_name, u.last_name 
                FROM patients p 
                JOIN users u ON p.user_id = u.id 
                WHERE u.role = 'patient'
                ORDER BY u.last_name, u.first_name
            ");
            $patients = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $error = 'Erreur lors de la récupération des patients';
        }

        // Generate CSRF token
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        include __DIR__ . '/../../../resources/views/admin/patients.php';
    }

    private function createPatient($db, $data)
    {
        try {
            $db->beginTransaction();

            // Insert user first
            $stmt = $db->prepare("
                INSERT INTO users (email, username, password, first_name, last_name, role, created_at) 
                VALUES (?, ?, ?, ?, ?, 'patient', NOW())
            ");
            $hashedPassword = password_hash($data['password'] ?? 'password123', PASSWORD_DEFAULT);
            $stmt->execute([
                $data['email'],
                $data['username'],
                $hashedPassword,
                $data['first_name'],
                $data['last_name']
            ]);

            $userId = $db->lastInsertId();

            // Insert patient record
            $stmt = $db->prepare("
                INSERT INTO patients (user_id, date_of_birth, phone, address, emergency_contact, emergency_phone, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $userId,
                $data['date_of_birth'] ?? null,
                $data['phone'] ?? '',
                $data['address'] ?? '',
                $data['emergency_contact'] ?? '',
                $data['emergency_phone'] ?? ''
            ]);

            $db->commit();
            return ['success' => true];
        } catch (\PDOException $e) {
            $db->rollBack();
            return ['error' => 'Erreur lors de la création du patient: ' . $e->getMessage()];
        }
    }

    private function deletePatient($db, $patientId)
    {
        try {
            $db->beginTransaction();

            // Get user_id first
            $stmt = $db->prepare("SELECT user_id FROM patients WHERE id = ?");
            $stmt->execute([$patientId]);
            $patient = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($patient) {
                // Delete patient record
                $stmt = $db->prepare("DELETE FROM patients WHERE id = ?");
                $stmt->execute([$patientId]);

                // Delete user record
                $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$patient['user_id']]);

                $db->commit();
                return ['success' => true];
            }

            return ['error' => 'Patient non trouvé'];
        } catch (\PDOException $e) {
            $db->rollBack();
            return ['error' => 'Erreur lors de la suppression du patient: ' . $e->getMessage()];
        }
    }
}
