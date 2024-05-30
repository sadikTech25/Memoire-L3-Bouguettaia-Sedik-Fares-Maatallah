<?php  session_start(); ob_start(); // Sécurité à développer
 

$idEvenement = (isset($_POST['ide'])) ? $_POST['ide'] : 0; // Récupération  de l'identifiant de l'événement à modefier
$typeEvenement = (isset($_POST['typee'])) ? $_POST['typee'] : 0; // Récupération  le type de l'événement à modefier
$descriptionEvenement = (isset($_POST['descriptione'])) ? $_POST['descriptione'] : 0; // Récupération  de la description de l'événement à modefier

require_once '../core.php'//Création d'un objet PDO pour accéder à la base de données
$msg = ""; // Message vide

// Préparer la requête SQL

if($idEvenement > 0 && $typeEvenement >0 && descriptionEvenement>0)
{
	// Préparation de la commande SQL
	$cmd = "UPDATE evenement SET type_evenement = :typeEvenement, description_evenement = :descriptionEvenement WHERE id_evenement = :idEvenement"; // Commande de modéfication
	$stmt = $pdo->prepare($cmd); // Préparation de la commande pour l'exécution
	$stmt->bindParam(":idEvenement", $idEvenement); // Racordement des données avec les paramètres
        $stmt->bindParam(":descriptionEvenement", $descriptionEvenement);
        $stmt->bindParam(":typeEvenement", $typeEvenement);

	// Exécution de la commande de modéfication
	$stmt->execute();

	// Vérification si la modefication a réussi
	$msg = (isset($stmt) && ($stmt !== false) && $stmt->rowCount() > 0) ? 
		"Opération terminée avec succès !" : 
		"Echèc de l'opération";

}

echo $msg; // Affichage du message d'état de fonctionnement

?>

