<?php

namespace UnityCare\Controllers\Admin;

use UnityCare\Services\Auth;
use UnityCare\Services\Database;
use UnityCare\Core\Response;

class MedicationsController
{
    public function index()
    {
        if (!Auth::check() || Auth::role() !== 'admin') {
            $base = defined('BASE_PATH') ? constant('BASE_PATH') : '';
            Response::redirect($base . '/login.php');
            return;
        }

        $db = Database::getInstance();
        $medications = [];
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
                    $result = $this->createMedication($db, $_POST);
                    if ($result['success']) {
                        $success = 'Médicament créé avec succès';
                    } else {
                        $error = $result['error'];
                    }
                } elseif ($action === 'delete') {
                    $result = $this->deleteMedication($db, $_POST['id']);
                    if ($result['success']) {
                        $success = 'Médicament supprimé avec succès';
                    } else {
                        $error = $result['error'];
                    }
                }
            }
        }

        // Fetch all medications
        try {
            $stmt = $db->query("
                SELECT * FROM medications 
                ORDER BY name
            ");
            $medications = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $error = 'Erreur lors de la récupération des médicaments';
        }

        // Generate CSRF token
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        include __DIR__ . '/../../../resources/views/admin/medications.php';
    }

    private function createMedication($db, $data)
    {
        try {
            $stmt = $db->prepare("
                INSERT INTO medications (name, description, dosage, side_effects, created_at) 
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $data['name'],
                $data['description'] ?? '',
                $data['dosage'] ?? '',
                $data['side_effects'] ?? ''
            ]);

            return ['success' => true];
        } catch (\PDOException $e) {
            return ['error' => 'Erreur lors de la création du médicament: ' . $e->getMessage()];
        }
    }

    private function deleteMedication($db, $medicationId)
    {
        try {
            $stmt = $db->prepare("DELETE FROM medications WHERE id = ?");
            $stmt->execute([$medicationId]);

            return ['success' => true];
        } catch (\PDOException $e) {
            return ['error' => 'Erreur lors de la suppression du médicament: ' . $e->getMessage()];
        }
    }
}
