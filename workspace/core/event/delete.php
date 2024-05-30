<?php
session_start();
ob_start(); 

require_once '../core.php'; 
$pdo = core_manager::generate_pdo();
$msg = "";
$idevenement = (isset($_POST['id'])) ? $_POST['id'] : 0; // Récupération  de l'identifiant de la ressource à supprimer

if($idevenement > 0)
{
    // Begin a transaction
    $pdo->beginTransaction();

    try {
        // Préparation de la commande SQL pour supprimer les ressources demandées
        $cmd_ressources = "DELETE FROM ressource_demanded WHERE id_evenement = :idevenement";
        $stmt_ressources = $pdo->prepare($cmd_ressources);
        $stmt_ressources->bindParam(":idevenement", $idevenement);
        $stmt_ressources->execute();

        // Préparation de la commande SQL pour supprimer l'événement
        $cmd = "DELETE FROM evenement WHERE id_evenement = :idevenement";
        $stmt = $pdo->prepare($cmd);
        $stmt->bindParam(":idevenement", $idevenement);
        $stmt->execute();

        // Commit the transaction
        $pdo->commit();

        // Vérification si la suppression a réussi
        $msg = ($stmt->rowCount() > 0) ? 
            "Opération terminée avec succès !" : 
            "Echèc de l'opération";
    } catch (Exception $e) {
        // Rollback the transaction if any errors occurred
        $pdo->rollback();
        $msg = "Une erreur est survenue : " . $e->getMessage();
    }
}

echo $msg; // Affichage du message d'état de fonctionnement
?>
