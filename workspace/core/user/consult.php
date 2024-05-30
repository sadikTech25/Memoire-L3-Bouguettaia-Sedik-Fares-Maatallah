<?php session_start();ob_start(); // Sécurité à développer

require_once '../core.php'; // Création d'un objet PDO pour accéder à la base de données

$html = '';

$id = (isset($_POST['idu'])) ? $_POST['idu'] : 0; // Récupération  de l'identifiant de l'utilisateur à lirer

try
{
	// Préparation de la commande SQL
	$cmd = "SELECT * FROM utilisateur WHERE id_utilisateur = :idUtilisateur";
	$stmt = $pdo->prepare($cmd); // Préparation de la commande pour l'exécution
	$stmt->bindParam(":idUtilisateur", $idUtilisateur); // Racordement des données avec les paramètres
	$stmt->execute();

	$user = (isset($stmt) && ($stmt !== false) && $stmt->rowCount() == 1) ? $stmt->fetchObject() : NULL;

	$html = ''; // TODO : Implementation en fonction du type d'utlisateur...
}
catch (Exception $exception)
{
	core_manager::treat_exception($e);
}

echo $html;

?>
