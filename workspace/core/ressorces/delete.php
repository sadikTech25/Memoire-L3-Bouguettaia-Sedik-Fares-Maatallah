<?php
session_start();
ob_start(); 

require_once '../core.php'; 
$pdo = core_manager::generate_pdo();
$msg = "";
$idRessource = (isset($_POST['id'])) ? $_POST['id'] : 0; // Récupération  de l'identifiant de la ressorce à supprimer

if($idRessource > 0)
{
    // Retrieve the resource's etat_ressource value
    $cmd_etat = "SELECT etat_ressource FROM ressource WHERE id_ressource = :idRessource";
    $stmt_etat = $pdo->prepare($cmd_etat);
    $stmt_etat->bindParam(":idRessource", $idRessource);
    $stmt_etat->execute();
    $etat_ressource = $stmt_etat->fetchColumn();

    // Check if etat_ressource is 1
    if ($etat_ressource == 1) {
        // Préparation de la commande SQL
        $cmd = "DELETE FROM ressource WHERE id_ressource = :idRessource AND etat_ressource = 1"; // Commande de suppression
        $stmt = $pdo->prepare($cmd); // Préparation de la commande pour l'exécution
        $stmt->bindParam(":idRessource", $idRessource); // Racordement des données avec les paramètres

        // Exécution de la commande de suppression
        $stmt->execute();

        // Vérification si la suppression a réussi
        $msg = ($stmt->rowCount() > 0) ? 
            "Opération terminée avec succès !" : 
            "Echèc de l'opération";
    } else {
        $msg = "Vous ne pouvez pas supprimer cette ressource car elle est resérvée.";
    }
}

echo $msg; // Affichage du message d'état de fonctionnement
?>
