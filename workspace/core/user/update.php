<?php session_start(); ob_start(); // Sécurité à développer


  require_once '../core.php'; // Création d'un objet PDO pour accéder à la base de données

$msg = ""; // Message vide

$idUtilisateur = (isset($_POST['ide'])) ? $_POST['ide'] : 0; // Récupération  de l'identifiant de l'utilisateur à modefier
$nomUtilisateur = (isset($_POST['nome'])) ? $_POST['nome'] : 0; // Récupération  le nom de l'utilisateur à modefier
$prenomUtilisateur = (isset($_POST['prenome'])) ? $_POST['prenome'] : 0; // Récupération le prenom de l'utilisateur à modefier
$emailUtilisateur = (isset($_POST['emaile'])) ? $_POST['emaile'] : 0; //  Récupération l'email de l'utilisateur à modefier
$mobileUtilisateur = (isset($_POST['mobile-e'])) ? $_POST['mobile-e'] : 0; // Récupération le numéro mobile de l'utilisateur à modefier
$dateNaissanceUtilisateur = (isset($_POST['datene'])) ? $_POST['datene'] : 0; //  Récupération la date naissance de  l'utilisateur à modefier
$passwordUtilisateur = (isset($_POST['passworde'])) ? $_POST['passworde'] : 0; // Récupération le mot de passe de  l'utilisateur à modefier
$etatUtilisateur = (isset($_POST['etate'])) ? $_POST['etate'] : 0; // Récupération l'etat de  l'utilisateur à modefier


if($idUtilisateur > 0 && nomUtilisateur>0 && prenomUtilisateur>0 && emailUtilisateur>0 && mobileUtilisateur>0 && dateNaissanceUtilisateur>0 &&passwordUtilisateur >0 && etatUtilisateur >0)
{
	// Préparation de la commande SQL
	 $cmd = $sql = "UPDATE utilisateur SET nom_utilisateur = :nomUtilisateur, prenom_utilisateur = :prenomUtilisateur, email_utilisateur = :emailUtilisateur, mobile_utilisateur = :mobileUtilisateur, datenaissance_utilisateur = :dateNaissanceUtilisateur, password_utilisateur = :passwordUtilisateur, etat_utilisateur = :etatUtilisateur WHERE id_utilisateur = :idUtilisateur";
	$stmt = $pdo->prepare($cmd); // Préparation de la commande pour l'exécution

        $stmt->bindParam(":idUtilisateur", $idUtilisateur);// Racordement des données avec les paramètres
        $stmt->bindParam(":nomUtilisateur", $nomUtilisateur);
        $stmt->bindParam(":prenomUtilisateur", $prenomUtilisateur);
        $stmt->bindParam(":emailUtilisateur", $emailUtilisateur);
        $stmt->bindParam(":mobileUtilisateur", $mobileUtilisateur);
        $stmt->bindParam(":dateNaissanceUtilisateur", $dateNaissanceUtilisateur);
        $stmt->bindParam(":passwordUtilisateur", $passwordUtilisateur);
        $stmt->bindParam(":etatUtilisateur", $etatUtilisateur);


	// Exécution de la commande de modefication
	$stmt->execute();

	// Vérification si la modefication est réussi
	$msg = (isset($stmt) && ($stmt !== false) && $stmt->rowCount() > 0) ? 
		"Opération terminée avec succès !" : 
		"Echèc de l'opération";

}
 echo $msg; // Affichage du message d'état de fonctionnement

?>



  
