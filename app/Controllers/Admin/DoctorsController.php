<?php

namespace UnityCare\Controllers\Admin;

use UnityCare\Services\Auth;
use UnityCare\Services\Database;
use UnityCare\Core\Response;

class DoctorsController
{
    public function index()
    {
        if (!Auth::check() || Auth::role() !== 'admin') {
            $base = defined('BASE_PATH') ? constant('BASE_PATH') : '';
            Response::redirect($base . '/login.php');
            return;
        }

        $db = Database::getInstance();
        $doctors = [];
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
                    $result = $this->createDoctor($db, $_POST);
                    if ($result['success']) {
                        $success = 'Médecin créé avec succès';
                    } else {
                        $error = $result['error'];
                    }
                } elseif ($action === 'delete') {
                    $result = $this->deleteDoctor($db, $_POST['id']);
                    if ($result['success']) {
                        $success = 'Médecin supprimé avec succès';
                    } else {
                        $error = $result['error'];
                    }
                }
            }
        }

        // Fetch all doctors
        try {
            $stmt = $db->query("
                SELECT d.*, u.email, u.username, u.first_name, u.last_name 
                FROM doctors d 
                JOIN users u ON d.user_id = u.id 
                WHERE u.role = 'doctor'
                ORDER BY u.last_name, u.first_name
            ");
            $doctors = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $error = 'Erreur lors de la récupération des médecins';
        }

        // Generate CSRF token
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        include __DIR__ . '/../../../resources/views/admin/doctors.php';
    }

    private function createDoctor($db, $data)
    {
        try {
            $db->beginTransaction();

            // Insert user first
            $stmt = $db->prepare("
                INSERT INTO users (email, username, password, first_name, last_name, role, created_at) 
                VALUES (?, ?, ?, ?, ?, 'doctor', NOW())
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

            // Insert doctor record
            $stmt = $db->prepare("
                INSERT INTO doctors (user_id, specialty, phone, address, created_at) 
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $userId,
                $data['specialty'] ?? '',
                $data['phone'] ?? '',
                $data['address'] ?? ''
            ]);

            $db->commit();
            return ['success' => true];
        } catch (\PDOException $e) {
            $db->rollBack();
            return ['error' => 'Erreur lors de la création du médecin: ' . $e->getMessage()];
        }
    }

    private function deleteDoctor($db, $doctorId)
    {
        try {
            $db->beginTransaction();

            // Get user_id first
            $stmt = $db->prepare("SELECT user_id FROM doctors WHERE id = ?");
            $stmt->execute([$doctorId]);
            $doctor = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($doctor) {
                // Delete doctor record
                $stmt = $db->prepare("DELETE FROM doctors WHERE id = ?");
                $stmt->execute([$doctorId]);

                // Delete user record
                $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$doctor['user_id']]);

                $db->commit();
                return ['success' => true];
            }

            return ['error' => 'Médecin non trouvé'];
        } catch (\PDOException $e) {
            $db->rollBack();
            return ['error' => 'Erreur lors de la suppression du médecin: ' . $e->getMessage()];
        }
    }
}
