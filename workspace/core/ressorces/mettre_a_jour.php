<?php session_start();ob_start(); // Sécurité à développer
 
require_once '../core.php'; // Création d'un objet PDO pour accéder à la base de données

msg=''//message vide 


$idRessource = (isset($_POST['idr'])) ? $_POST['idr'] : 0; // Récupération   l'identifiant de la ressource à modefier
$nomRessource = (isset($_POST['nomr'])) ? $_POST['nomr'] : 0; // Récupération  le nom de la ressourceà modefier
$typeRessource = (isset($_POST['type-r'])) ? $_POST['type-r'] : 0; // Récupération le type de la ressource à modefier
$quantiteRessource = (isset($_POST['qte-r'])) ? $_POST['qte-r'] : 0; //Récupération le quantite de la ressource à modefier
$etatRessource = (isset($_POST['etat-r'])) ? $_POST['etat-r-e'] : 0; //Récupération l'etat de la ressource à modefier
$fournisseur = (isset($_POST['fournisseur-r'])) ? $_POST['fournisseur-r'] : 0; //Récupération le fournisseur de la ressource à modefier
$utilisateurIdUtilisateur = (isset($_POST['idu-r'])) ? $_POST['idu-r'] : 0; //

if(idRessource>0 && nomRessource>0 && typeRessource>0 && quantiteRessource>0 && etatRessource>0 && fournisseur>0 &&utilisateurIdUtilisateur>0)
{
          // Préparation de la commande SQL
$cmd = "UPDATE ressource SET nom_ressorce = :nomRessource, type_ressorce = :typeRessource, quantité_ressorce = :quantiteRessource, etat_ressorce = :etatRessource, fournisseur = :fournisseur, utilisateur_id_utilisateur = :utilisateurIdUtilisateur WHERE id_ressorce = :idRessource";

       $stmt = $pdo->prepare($cmd); // Préparation de la commande pour l'exécution
   // Racordement des données avec les paramètres
       $stmt->bindParam(":idRessource", $idRessource);
       $stmt->bindParam(":nomRessource", $nomRessource);
       $stmt->bindParam(":typeRessource", $typeRessource);
       $stmt->bindParam(":quantiteRessource", $quantiteRessource);
       $stmt->bindParam(":etatRessource", $etatRessource);
       $stmt->bindParam(":fournisseur", $fournisseur);
       $stmt->bindParam(":utilisateurIdUtilisateur", $utilisateurIdUtilisateur);


	// Exécution de la commande de mettre a jour
	$stmt->execute();

	// Vérification si la mettre a jour de la ressource est réussi
	$msg = (isset($stmt) && ($stmt !== false) && $stmt->rowCount() > 0) ? 
		"Opération terminée avec succès !" : 
		"Echèc de l'opération";

}
 echo $msg; // Affichage du message d'état de fonctionnement

?>


