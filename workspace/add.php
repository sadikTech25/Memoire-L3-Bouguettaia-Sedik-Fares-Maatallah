<?php session_start(); ob_start(); // Sécurité à développer

require_once 'core.php'; // Création d'un objet PDO pour accéder à la base de données

$msg = ""; 

try
{

        $user = (isset($_POST) && count($_POST) > 0) ? (object) $_POST : core_manager::throw_exception('Erreur : Données manquantes !!!'); // Récupération  de l'identifiant de l'utilisateur à ajouter
        $user->reference = core_manager::generate_reference(md5($user->email.' '.$user->mobile.' '.$user->login));
        $cmd = "INSERT INTO utilisateur (reference_utilisateur, nom_utilisateur, prenom_utilisateur, email_utilisateur, mobile_utilisateur,datenaissance_utilisateur, password_utilisateur, etat_utilisateur) VALUES (:referenceUtilisateur, :nomUtilisateur, :prenomUtilisateur, :emailUtilisateur, :mobileUtilisateur, :dateNaissanceUtilisateur, :passwordUtilisateur, :etatUtilisateur)";

        $stmt = $pdo->prepare($cmd); // Préparation de la commande pour l'exécution

        $stmt->bindParam(":ref", $user->reference);
        $stmt->bindParam(":nom", $nomUtilisateur);
        $stmt->bindParam(":pnm", $prenomUtilisateur);
        $stmt->bindParam(":emailUtilisateur", $emailUtilisateur);
        $stmt->bindParam(":mobileUtilisateur", $mobileUtilisateur);
        $stmt->bindParam(":dateNaissanceUtilisateur", $dateNaissanceUtilisateur);
        $stmt->bindParam(":passwordUtilisateur", $passwordUtilisateur);
        $stmt->bindParam(":etatUtilisateur", $etatUtilisateur);
	$stmt->execute();

	// Vérification si la suppression a réussi
	$msg = (isset($stmt) && ($stmt !== false) && $stmt->rowCount() > 0) ? 
		"Opération terminée avec succès !" : 
		"Echèc de l'opération";

}
catch(Exception $exception)
{
        core_manager::treat_exception($exception);
}

echo $msg; // Affichage du message d'état de fonctionnement

?>


