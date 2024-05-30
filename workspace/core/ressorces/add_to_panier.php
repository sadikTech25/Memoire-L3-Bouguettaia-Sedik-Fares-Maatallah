<?php
session_start();
require_once '../core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = isset($_POST['id_evenement']) ? intval($_POST['id_evenement']) : 0;
    $resource_id = isset($_POST['id_ressource']) ? intval($_POST['id_ressource']) : 0;
    $quantity = isset($_POST['quantite_ressource']) ? intval($_POST['quantite_ressource']) : 0;

    if ($event_id > 0 && $resource_id > 0 && $quantity > 0) {
        try {
            $pdo = core_manager::generate_pdo();
            $stmt = $pdo->prepare("
                INSERT INTO ressource_evenement (id_evenement, id_ressource, quantite_ressource)
                VALUES (:event_id, :resource_id, :quantity)
            ");
            $stmt->execute([
                ':event_id' => $event_id,
                ':resource_id' => $resource_id,
                ':quantity' => $quantity
            ]);

            echo json_encode(['status' => 'success', 'message' => 'Resource successfully reserved!']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
