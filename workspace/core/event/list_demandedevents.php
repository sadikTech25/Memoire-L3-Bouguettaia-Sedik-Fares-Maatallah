<?php session_start(); ob_start(); // Sécurité à développer
$account = (object) $_SESSION['account'];
require_once '../core.php'; // Création d'un objet PDO pour accéder à la base de données

$html = '
<small><small>
<table class="table text-start align-middle table-bordered table table-striped table-hover mb-0">
	<tr class="text-dark">
    <th scope="col">Title</th>
    <th scope="col">Description</th>
    <th scope="col">Start Date</th>
    <th scope="col">End Date</th>
    <th scope="col">Add Date</th>
    <th scope="col">Place</th>
    <th scope="col">Action</th>
	</tr>';
$pdo = core_manager::generate_pdo();
$stmt = $pdo->prepare("SELECT * FROM evenement WHERE etat_evenement =0 AND client_evenement=$account->id_utilisateur"); 
$stmt->execute();

if(isset($stmt) && $stmt !== false && $stmt->rowCount() > 0) {
	
	while($line = $stmt->fetchObject())
	{
		$html .= '	
		<tr>
			<td>'.$line->titre_evenement.'</td>
			<td>'.$line->description_evenement.'</td>
            <td>'.$line->date_debut_evenement.'</td>
			<td>'.$line->date_fin_evenement.'</td>
			<td>'.$line->date_ajout_evenement.'</td>
            <td>'.$line->lieu_evenement.'</td>
			<td> 
				<button type="button" class="btn btn-sm btn-danger" onclick="delete_user('.$line->id_evenement.');">
				Cancel
				</button>
			</td>
		</tr>';
	}

	$html .= '</table></small></small>';
}

echo $html; // Affichage du message d'état de fonctionnement

?>


