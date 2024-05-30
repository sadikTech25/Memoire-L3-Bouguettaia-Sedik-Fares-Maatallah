<?php
session_start();
ob_start();

require_once '../core.php';

$pdo = core_manager::generate_pdo();
$msg = "";
$idutilisateur = (isset($_POST['id'])) ? $_POST['id'] : 0;

if ($idutilisateur > 0) {
    // Check if the user is a manager (type 3) with ongoing events
    $stmt = $pdo->prepare("SELECT COUNT(*) AS event_count 
                           FROM ressource 
                           WHERE fournisseur_ressource = :idutilisateur AND etat_ressource = 1"); // Check for ongoing events
    $stmt->bindParam(":idutilisateur", $idutilisateur);
    $stmt->execute();
    $eventCount = $stmt->fetchColumn();

    if ($eventCount > 0) {
        // User has ongoing events, prevent deletion
        $msg = "Impossible de supprimer l'utilisateur car il a des Ressources en cours d'utilisation.";
    } else {
        // No ongoing events, proceed with deletion
        $cmd = "DELETE FROM utilisateur WHERE id_utilisateur = :idutilisateur";
        $stmt = $pdo->prepare($cmd);
        $stmt->bindParam(":idutilisateur", $idutilisateur);
        $stmt->execute();

        $msg = (isset($stmt) && ($stmt !== false) && $stmt->rowCount() > 0) ?
            "Opération terminée avec succès !" :
            "Echec de l'opération";
    }
}

echo $msg; // Display status message
?>
