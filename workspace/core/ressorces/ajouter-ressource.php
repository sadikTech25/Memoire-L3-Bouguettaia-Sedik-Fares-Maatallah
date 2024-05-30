<?php session_start(); ob_start();// Sécurité à développer

require_once '../core.php'; // Création d'un objet PDO pour accéder à la base de données

msg="" //Message vide

$idRessorce = (isset($_POST['idr'])) ? $_POST['idr'] : 0; // Récupération  de l'identifiant de la ressource a  ajouter
$nomRessource = (isset($_POST['nomr'])) ? $_POST['nomr'] : 0; // Récupération  le nom de la ressource a ajouter
$typeRessource = (isset($_POST['typer'])) ? $_POST['typer'] : 0; // Récupération  de type  de la ressource a  ajouter
$quantiteRessource = (isset($_POST['qter'])) ? $_POST['qter'] : 0; //Récupération  la quantitué de la ressource a  ajouter
$prixRessource = (isset($_POST['prixr'])) ? $_POST['prixr'] : 0; // Récupération  de la le prix de la ressource a ajouter
$etatRessource = (isset($_POST['etatr'])) ? $_POST['etatr'] : 0; // Récupération  l'etat de la ressource  a ajouter
$fournisseur = (isset($_POST['fournisseur-r'])) ? $_POST['fournisseur-r'] : 0; // Récupération  le fornisseur de la ressource 
$utilisateurIdUtilisateur= (isset($_POST['idu'])) ? $_POST['id'] : 0; //   

if(idRessorce>0 && nomRessource>0 && typeRessource>0 && quantiteRessource>0 && prixRessource>0 && etatRessource>0  )
{
     // Préparer la requête SQL
        $cmd = "INSERT INTO ressource (reference_ressorce, nom_ressorce, type_ressorce, quantité_ressorce, etat_ressorce, fournisseur, utilisateur_id_utilisateur) VALUES (:referenceRessource, :nomRessource, :typeRessource, :quantiteRessource, :etatRessource, :fournisseur, :utilisateurIdUtilisateur)";

        $stmt = $pdo->prepare($cmd); // Préparation de la commande pour l'exécution

	
         $stmt->bindParam(":referenceRessource", $referenceRessource);
         $stmt->bindParam(":nomRessource", $nomRessource);
         $stmt->bindParam(":typeRessource", $typeRessource);
         $stmt->bindParam(":quantiteRessource", $quantiteRessource);
         $stmt->bindParam(":etatRessource", $etatRessource);
         $stmt->bindParam(":fournisseur", $fournisseur);
         $stmt->bindParam(":utilisateurIdUtilisateur", $utilisateurIdUtilisateur);



// Exécution de la commande de creation
	$stmt->execute();

	// Vérification si la suppression a réussi
	$msg = (isset($stmt) && ($stmt !== false) && $stmt->rowCount() > 0) ? 
		"Opération terminée avec succès !" : 
		"Echèc de l'opération";

}

echo $msg; // Affichage du message d'état de fonctionnement

?>


