<?php
session_start();
require_once '../core.php'; // Include your core script for database connection

if (isset($_POST['id'])) {
    $eventId = intval($_POST['id']);
    $pdo = core_manager::generate_pdo();
    
    $stmt = $pdo->prepare("UPDATE evenement SET etat_evenement = 2 WHERE id_evenement = :id");
    $stmt->bindParam(':id', $eventId, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo 'Success';
    } else {
        echo 'Failed to update event status';
    }
} else {
    echo 'Invalid event ID';
}
?>
