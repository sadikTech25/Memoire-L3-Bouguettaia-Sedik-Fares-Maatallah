<?php
session_start();
ob_start();

require_once '../core.php';
$pdo = core_manager::generate_pdo();

if (isset($_POST['event_id']) && isset($_POST['manager_id'])) {
    $eventId = $_POST['event_id'];
    $managerId = $_POST['manager_id'];

    // Insert into evenement_manager
    $sql = "INSERT INTO evenement_manager (evenement,manager) VALUES (:event_id, :manager_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':event_id', $eventId);
    $stmt->bindParam(':manager_id', $managerId);

    if ($stmt->execute()) {
        // Update etat_evenement only if insertion is successful
        $updateEtatStmt = $pdo->prepare("UPDATE evenement SET etat_evenement = 1 WHERE id_evenement = :event_id");
        $updateEtatStmt->bindParam(':event_id', $eventId);
        $updateEtatStmt->execute();

        echo "Manager assigned successfully! Event status updated."; // Or a more specific success message
    } else {
        echo "Error assigning manager: " . $stmt->errorCode(); // Or a more informative error message
    }
} else {
    echo "Missing required data."; // Handle missing data from the AJAX request
}

?>
