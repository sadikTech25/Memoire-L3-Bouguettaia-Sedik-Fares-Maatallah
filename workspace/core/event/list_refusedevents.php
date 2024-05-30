<?php session_start(); ob_start(); // Sécurité à développer
$account = isset($_SESSION['account']) ? (object)$_SESSION['account'] : header('location: core/user/deconnection.php');
switch($account->type_utilisateur)
{
    case 4: header('location: index_fournisseur.php'); break;
    case 3: header('location: index_manager.php'); break;
    case 1: header('location: index_admin.php'); break;


}
require_once '../core.php'; // Création d'un objet PDO pour accéder à la base de données

$html = '
<small><small>
<table class="table text-start align-middle table-bordered table table-striped table-hover mb-0">	<tr class="text-dark">
    <th scope="col">Title</th>
    <th scope="col">Description</th>
    <th scope="col">Start Date</th>
    <th scope="col">End Date</th>
    <th scope="col">Add Date</th>
    <th scope="col">Place</th>
	</tr>';
$pdo = core_manager::generate_pdo();
$stmt = $pdo->prepare("SELECT * FROM evenement WHERE etat_evenement = 2 AND client_evenement=$account->id_utilisateur"); 
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
		</tr>';
	}

	$html .= '</table></small></small>';
}

echo $html; // Affichage du message d'état de fonctionnement

?>


