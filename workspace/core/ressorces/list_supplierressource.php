<?php session_start(); ob_start(); // Sécurité à développer
$account = (object) $_SESSION['account'];
require_once '../core.php'; // Création d'un objet PDO pour accéder à la base de données

$html = '
<small><small>
	<table class="table text-start align-middle table-bordered table table-striped table-hover mb-0">	<tr class="text-dark">
		<th scope="col">Name</th>
		<th scope="col">Type</th>
		<th scope="col">Description</th>
		<th scope="col">Quantity</th>
		<th scope="col">Lieu</th>
		<th scope="col">Status</th>

		<th scope="col"></th>
	</tr>';
$pdo = core_manager::generate_pdo();
$stmt = $pdo->prepare("SELECT * FROM ressource WHERE fournisseur_ressource=$account->id_utilisateur"); 
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
			<td>'. ($line->etat_ressource == 1 ? 'Not Reserved' : 'Reserved') . '</td>
				<td style="text-align: right;"> 
				'. ($line->etat_ressource == 1 ? '<button type="button" class="btn btn-sm text-danger" onclick="delete_ressource('.$line->id_ressource.');">
			<small><i class="bi bi-trash"></i></small>
			</button>' : '') . '
				</td>
		</tr>';
	}

	$html .= '</table></small></small>';
}

echo $html; // Affichage du message d'état de fonctionnement

?>


