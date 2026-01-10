<?php

namespace UnityCare\Controllers\Admin;

use UnityCare\Services\Auth;
use UnityCare\Services\Database;
use UnityCare\Core\Response;

class DepartmentsController
{
    public function index()
    {
        if (!Auth::check() || Auth::role() !== 'admin') {
            $base = defined('BASE_PATH') ? constant('BASE_PATH') : '';
            Response::redirect($base . '/login.php');
            return;
        }

        $db = Database::getInstance();
        $departments = [];
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
                    $result = $this->createDepartment($db, $_POST);
                    if ($result['success']) {
                        $success = 'Département créé avec succès';
                    } else {
                        $error = $result['error'];
                    }
                } elseif ($action === 'delete') {
                    $result = $this->deleteDepartment($db, $_POST['id']);
                    if ($result['success']) {
                        $success = 'Département supprimé avec succès';
                    } else {
                        $error = $result['error'];
                    }
                }
            }
        }

        // Fetch all departments
        try {
            $stmt = $db->query("
                SELECT d.*, COUNT(dr.id) as doctor_count 
                FROM departments d 
                LEFT JOIN doctors dr ON d.id = dr.department_id 
                GROUP BY d.id 
                ORDER BY d.name
            ");
            $departments = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $error = 'Erreur lors de la récupération des départements';
        }

        // Generate CSRF token
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        include __DIR__ . '/../../../resources/views/admin/departments.php';
    }

    private function createDepartment($db, $data)
    {
        try {
            $stmt = $db->prepare("
                INSERT INTO departments (name, description, head_doctor_id, created_at) 
                VALUES (?, ?, ?, NOW())
            ");
            $stmt->execute([
                $data['name'],
                $data['description'] ?? '',
                $data['head_doctor_id'] ?? null
            ]);

            return ['success' => true];
        } catch (\PDOException $e) {
            return ['error' => 'Erreur lors de la création du département: ' . $e->getMessage()];
        }
    }

    private function deleteDepartment($db, $departmentId)
    {
        try {
            $db->beginTransaction();

            // Check if department has doctors
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM doctors WHERE department_id = ?");
            $stmt->execute([$departmentId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return ['error' => 'Impossible de supprimer ce département car il contient des médecins'];
            }

            // Delete department
            $stmt = $db->prepare("DELETE FROM departments WHERE id = ?");
            $stmt->execute([$departmentId]);

            $db->commit();
            return ['success' => true];
        } catch (\PDOException $e) {
            $db->rollBack();
            return ['error' => 'Erreur lors de la suppression du département: ' . $e->getMessage()];
        }
    }
}
