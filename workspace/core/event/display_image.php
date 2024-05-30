<?php
require_once '../core.php';
$pdo = core_manager::generate_pdo();

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT image_evenement FROM evenement WHERE id_evenement = :id");
    $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: image/jpeg'); // Adjust content type if different (e.g., 'image/png' for PNG images)
        echo $row['image_evenement'];
        exit;
    } else {
        // No image found, return a default image or 404
        header('Content-Type: image/jpeg');
        readfile('img/about-1.jpg'); // Adjust the path to your default image
        exit;
    }
} else {
    // No ID provided, return a default image or 404
    header('Content-Type: image/jpeg');
    readfile('img/about-2.jpg'); // Adjust the path to your default image
    exit;
}
