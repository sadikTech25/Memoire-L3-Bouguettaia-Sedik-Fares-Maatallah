<?php session_start(); ob_start(); // Sécurité à développer


require_once '../core.php'; // Création d'un objet PDO pour accéder à la base de données

$msg = ""; // Message vide

$referenceEvenement= (isset($_POST['refe'])) ? $_POST['refe'] : 0; // Récupération  de la reference de l'événement à ajouter
$managerEvenement= (isset($_POST['idm'])) ? $_POST['idm'] : 0; // Récupération   l'identifiant de le manager de  l'événement 
$clientEvenement= (isset($_POST['idc'])) ? $_POST['idc'] : 0; // Récupération  l'identifiant de le client de  l'événement
$dateAjoutEvenement= (isset($_POST['dae'])) ? $_POST['dae'] : 0; // Récupération  de la date d'ajout de l'événement 
$typeEvenement= (isset($_POST['typee'])) ? $_POST['typee'] : 0; // Récupération  de type de l'événement à ajouter
$descriptionEvenement= (isset($_POST['descriptione'])) ? $_POST['descriptione'] : 0; // Récupération  de la description de l'événement à ajouter
$etat= (isset($_POST['etate'])) ? $_POST['etate'] : 0; // Récupération  de l'etat de l'événement à ajouter
$paiementIdPaiment= (isset($_POST['idp'])) ? $_POST['id-p'] : 0; // Récupération  de l'identifiant depaiment de l'événement à ajouter
$paiementUtilisateurIdUtilisateur= (isset($_POST['id-pu'])) ? $_POST['id-pu'] : 0; // Récupération  de l'identifiant de l''utilisateur qui a effectué le paiement

if(idEvenement>0){
        // Préparer la requête sql
        $cmd = "INSERT INTO evenement (reference_evenement, manager_evenement, client_evenement, dateajout_evenement, type_evenement,           description_evenement, etat, paiement_id_paiment, paiement_utilisateur_id_utilisateur) VALUES  (:referenceEvenement, :managerEvenement, :clientEvenement, :dateAjoutEvenement, :typeEvenement, :descriptionEvenement, :etat, :paieme ntIdPaiment, :paiementUtilisateurIdUtilisateur)";  // Commande de l'ajout

        //$stmt->bindParam(":idEvenement", $idEvenement); // Racordement des données avec les paramètres
        $stmt->bindParam(":referenceEvenement", $referenceEvenement);
        $stmt->bindParam(":managerEvenement", $managerEvenement);
        $stmt->bindParam(":clientEvenement", $clientEvenement);
        $stmt->bindParam(":dateAjoutEvenement", $dateAjoutEvenement);
        $stmt->bindParam(":typeEvenement", $typeEvenement);
        $stmt->bindParam(":descriptionEvenement", $descriptionEvenement);
        $stmt->bindParam(":etat", $etat);
        $stmt->bindParam(":paiementIdPaiment", $paiementIdPaiment);
        $stmt->bindParam(":paiementUtilisateurIdUtilisateur", $paiementUtilisateurIdUtilisateur);

	// Exécution de la commande de suppression
	$stmt->execute();

	// Vérification si l'ajout est réussi
	$msg = (isset($stmt) && ($stmt !== false) && $stmt->rowCount() > 0) ? 
		"Opération terminée avec succès !" : 
		"Echèc de l'opération";;
}
echo $msg; // Affichage du message d'état de fonctionnement

?>
