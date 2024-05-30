<?php
session_start(); ob_start(); // Sécurité à développer

require_once '../core.php'; // Création d'un objet PDO pour accéder à la base de données

$html = '
<small><small>
    <table class="table text-start align-middle table-bordered table-striped table-hover mb-0">
        <tr class="text-dark">
            <th scope="col">Email</th>
            <th scope="col">Name</th>
            <th scope="col">Address</th>
            <th scope="col">Mobile</th>
            <th scope="col">Status</th>
            <th scope="col">Validation Status</th>
            <th scope="col"> </th>
        </tr>';
$pdo = core_manager::generate_pdo();
$stmt = $pdo->prepare("SELECT u.*, COUNT(em.evenement) AS event_count
FROM utilisateur u
LEFT JOIN evenement_manager em ON u.id_utilisateur = em.manager
WHERE u.type_utilisateur = 3
GROUP BY u.id_utilisateur"); 
$stmt->execute();

if (isset($stmt) && $stmt !== false && $stmt->rowCount() > 0) {
    while ($line = $stmt->fetchObject()) {
        $status = ($line->event_count > 0) ? 'Active' : 'Not Active';
        $validation_status = ($line->manager_isvalidated) ? 'Validated' : 'Not Validated';

        $validate_button = '';
        if (!$line->manager_isvalidated) {
            $validate_button = '
                <button type="button" class="btn btn-sm btn-success" onclick="confirm_validate_manager('.$line->id_utilisateur.');">
                    <i class="bi bi-check-circle"></i> Validate
                </button>';
        }

        $html .= '    
        <tr>
            <td>'.$line->email_utilisateur.'</td>
            <td id="'.$line->id_utilisateur.'">'.$line->nom_utilisateur.' '.$line->prenom_utilisateur.'</td>
            <td>'.$line->adresse_utilisateur.'</td>
            <td>'.$line->mobile_utilisateur.'</td>
            <td>'. $status.'</td>
            <td>'. $validation_status.'</td>
            <td style="text-align: right;">
                '.$validate_button.'
                <button type="button" class="btn btn-sm btn-danger" onclick="delete_user('.$line->id_utilisateur.');">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>';
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

    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

<script>
function confirm_validate_manager(id) {
    if (confirm('Are you sure you want to validate this manager?')) {
        validate_manager(id);
    }
}

function validate_manager(id) {
    $.ajax({
        url: 'core/user/validate_manager.php',
        type: 'POST',
        data: {id: id},
        success: function(response) {
            alert('Manager validated successfully');
            location.reload(); // Reload the page to see the changes
        },
        error: function(error) {
            console.log(error);
        }
    });
}
</script>

</body>
</html>
