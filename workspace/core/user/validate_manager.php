<?php
require_once '../core.php'; // Adjust the path if necessary

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    try {
        $pdo = core_manager::generate_pdo();
        $stmt = $pdo->prepare("UPDATE utilisateur SET manager_isvalidated = 1 WHERE id_utilisateur = :id");
        $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo 'Manager validated successfully';
        } else {
            echo 'Error validating manager: ' . implode(', ', $stmt->errorInfo());
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
} else {
    echo 'Invalid request or missing ID';
}
?>
