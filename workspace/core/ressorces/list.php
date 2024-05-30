<?php session_start(); ob_start(); // Sécurité à développer

require_once '../core.php'; // Création d'un objet PDO pour accéder à la base de données

$html = '
<div class="d-flex align-items-center justify-content-between mb-4">
<h6 class="mb-0">Ressources</h6>
</div>
<small><small>
<table class="table text-start align-middle table-bordered table table-striped table-hover mb-0">
	<tr class="text-dark">
		<th scope="col">Name</th>
		<th scope="col">Type</th>
		<th scope="col">Description</th>
		<th scope="col">Quantity</th>
		<th scope="col">Lieu</th>
		<th scope="col">Supplier</th>
		<th scope="col">Status</th>
	</tr>';
$pdo = core_manager::generate_pdo();
$stmt = $pdo->prepare("SELECT r.*, u.email_utilisateur 
FROM ressource r 
INNER JOIN utilisateur u ON r.fournisseur_ressource = u.id_utilisateur"); 
$stmt->execute();

if(isset($stmt) && $stmt !== false && $stmt->rowCount() > 0) {
	
	while($line = $stmt->fetchObject())
	{
		$html .= '	
		<tr>
			<td>'.$line->nom_ressource.'</td>
			<td>'.core_manager::display_label($line->type_ressource).'</td>
			<td>'.$line->description_ressource.'</td>
			<td>'.$line->quantite_ressource.'</td>
			<td>'.$line->lieu_ressource.'</td>
			<td>'.$line->email_utilisateur.'</td>
			<td>'. ($line->etat_ressource == 1 ? 'Available' : 'Not Available') . '</td>
		</tr>';
	}

	$html .= '</table><small><small>';
}

echo $html; // Affichage du message d'état de fonctionnement

?>


