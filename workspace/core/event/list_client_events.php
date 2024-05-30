<?php session_start(); ob_start();
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
<table class="table text-start align-middle table-bordered table table-striped table-hover mb-0">
<tr class="text-dark">
    <th scope="col">Title</th>
    <th scope="col">Start Date</th>
    <th scope="col">End Date</th>
    <th scope="col">Manager</th>
    <th scope="col">Status</th>
    <th scope="col">Action</th>
	</tr>';
$pdo = core_manager::generate_pdo();
$manager_stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE type_utilisateur = 3 AND etat_utilisateur=0");
$manager_stmt->execute(); //Pour le formulaire d'affectaion des managers

$manager_options = '';

if ($manager_stmt && $manager_stmt->rowCount() > 0) {
  while ($manager = $manager_stmt->fetchObject()) {
    $manager_options .= '<option value="' . $manager->id_utilisateur . '">' . $manager->nom_utilisateur . ' ' . $manager->prenom_utilisateur . '</option>';
  }
}
$stmt = $pdo->prepare("SELECT * FROM evenement WHERE client_evenement=$account->id_utilisateur");
$stmt->execute();

if(isset($stmt) && $stmt !== false && $stmt->rowCount() > 0) {
	
	while($line = $stmt->fetchObject()) {
		$manager_event_stmt = $pdo->prepare("
			SELECT u.mobile_utilisateur, em.manager
			FROM evenement_manager em
			INNER JOIN utilisateur u ON em.manager = u.id_utilisateur
			WHERE em.evenement = $line->id_evenement
		");
		$manager_event_stmt->execute();
	
		// Check if any managers are assigned to this event
		$has_manager = $manager_event_stmt->rowCount() > 0;
	
		if ($has_manager) {
			while($line_manager = $manager_event_stmt->fetchObject()) {
				if($line->etat_evenement!=0){
					$html .= '
					<tr>
						<td>'.$line->titre_evenement.'</td>
						<td>'.$line->date_debut_evenement.'</td>
						<td>'.$line->date_fin_evenement.'</td>
						<td>'.$line_manager->mobile_utilisateur.'</td>
						<td>'. ($line->etat_evenement == 0 ? 'Demanded' : ($line->etat_evenement == 1 ? 'Accepted' :'Refused')) . '</td>
						<td style="text-align:right;">
							<button type="button" class="btn btn-sm text-danger" onclick="delete_user('.$line->id_evenement.');">
							<small><i class="bi bi-trash"></i></small>
							</button>
						</td>
					</tr>';
				}
			}
		} else {
			$html .= '
			<tr>
				<td>'.$line->titre_evenement.'</td>
				<td>'.$line->date_debut_evenement.'</td>
				<td>'.$line->date_fin_evenement.'</td>
				<td>Not Selected Yet</td>
				<td>'. ($line->etat_evenement == 0 ? 'Demanded' : ($line->etat_evenement == 1 ? 'Accepted' :'Refused')) . '</td>
				<td style="text-align:right;">
					<button type="button" class="btn btn-sm text-danger" onclick="delete_user('.$line->id_evenement.');">
					<small><i class="bi bi-trash"></i></small>
					</button>
					
				</td>
			</tr>';
	
			$option = '<option value="' . $line->id_evenement . '">' . $line->titre_evenement . '</option>';
		}
	}
	
	$html .= '</table></small></small>';

}
echo $html; // Affichage du message d'état de fonctionnement

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
	 <!-- modal -->

	 <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
		  <div class="modal-content">
			<div class="modal-header">
			  <h5 class="modal-title" id="exampleModalLabel">Choose A Manager For The Event</h5>
			  <button type="button" id="close-popup" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			  </button>
			</div>
			<div class="modal-body">
				<form action="" method="post">
				<div class="form-floating mb-3">
				<select class="form-select" id="floatingSelect" aria-label="Floating label select example">
					<?php echo $option; ?>

					</select>
					<label for="floatingelect">The Event</label>
				</div>
				<div class="form-floating mb-3">
				<select class="form-select" id="floatingSelect2" aria-label="Floating label select example">
					<?php echo $manager_options; ?>

					</select>
					<label for="floatingelect">Select The Manager</label>
				</div>
				  
				<div class="form-floating mb-3">
					<input type="message" class="form-control" id="floatingInput" placeholder="something else">
					<label for="floatingInput">Something Else</label>
				</div>
				
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-primary" onclick="select_manager(document.getElementById('floatingSelect').value, document.getElementById('floatingSelect2').value)">Validate</button>
			</div>
		</form>
		  </div>
		</div>
	  </div>
	  <!--end modal -->
	  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->


</body>
</html>
