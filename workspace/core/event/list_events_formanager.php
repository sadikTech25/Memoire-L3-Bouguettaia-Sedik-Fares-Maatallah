<?php
session_start();
ob_start();
$account = isset($_SESSION['account']) ? (object) $_SESSION['account'] : header('location: core/user/deconnection.php');
switch($account->type_utilisateur) {
    case 2: header('location: index_client.php'); break;
    case 4: header('location: index_fournisseur.php'); break;
    case 1: header('location: index_admin.php'); break;
}

require_once '../core.php'; // Création d'un objet PDO pour accéder à la base de données

$html = '
<div class="d-flex align-items-center justify-content-between mb-4">
<h6 class="mb-0">My Events</h6>
</div>
<small><small>
<p style="color:orange;">Hover over an event row to see their reserved resources.</p>
<table class="table text-start align-middle table-bordered table-striped table-hover mb-0">
    <tr class="text-dark">
    <th scope="col">Title</th>
    <th scope="col">Description</th>
    <th scope="col">Client</th>
    <th scope="col">Start Date</th>
    <th scope="col">End Date</th>
    <th scope="col">Status</th>
    <th scope="col">Action</th>
    </tr>';

$pdo = core_manager::generate_pdo();
$managerId = $_SESSION['account']['id_utilisateur'];
$stmt = $pdo->prepare("
    SELECT e.*, u.mobile_utilisateur, u.email_utilisateur 
    FROM evenement e 
    INNER JOIN evenement_manager em ON e.id_evenement = em.evenement 
    INNER JOIN utilisateur u ON e.client_evenement = u.id_utilisateur
    WHERE em.manager = :managerId
"); 
$stmt->bindParam(':managerId', $managerId, PDO::PARAM_INT);
$stmt->execute();

if ($stmt && $stmt->rowCount() > 0) {
    while ($line = $stmt->fetchObject()) {
        // Fetch demanded resources for the event
        $demanded_stmt = $pdo->prepare("
            SELECT type_ressource, quantite_ressource
            FROM ressource_demanded
            WHERE id_evenement = :ide
        ");
        $demanded_stmt->execute(['ide' => $line->id_evenement]);
        $demanded_resources = $demanded_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch reserved resources for the event
        $reserved_stmt = $pdo->prepare("
            SELECT re.quantite_ressource, r.type_ressource
            FROM ressource_evenement re
            INNER JOIN ressource r ON re.id_ressource = r.id_ressource
            WHERE re.id_evenement = :ide
        ");
        $reserved_stmt->execute(['ide' => $line->id_evenement]);
        $reserved_resources = $reserved_stmt->fetchAll(PDO::FETCH_ASSOC);

        $all_resources_reserved = true;
        foreach ($demanded_resources as $demanded) {
            $is_reserved = false;
            foreach ($reserved_resources as $reserved) {
                if ($reserved['type_ressource'] == $demanded['type_ressource'] &&
                    $reserved['quantite_ressource'] >= $demanded['quantite_ressource']) {
                    $is_reserved = true;
                    break;
                }
            }
            if (!$is_reserved) {
                $all_resources_reserved = false;
                break;
            }
        }

        $status = $all_resources_reserved ? 'Ready' : 'Pending';
        $action_button = $all_resources_reserved ? '' : '<button type="button" class="btn btn-sm btn-primary" onclick="redirect_to_page(' . $line->id_evenement . ');"><small><small><i class="bi bi-cart-check-fill"></i> Reserve Ressource</small></small></button>';

        $html .= '
        <tr class="event-row" data-id="'.$line->id_evenement.'">
            <td>'.$line->titre_evenement.'</td>
            <td>'.$line->description_evenement.'</td>
            <td>'.$line->mobile_utilisateur.' | '.$line->email_utilisateur.'</td>
            <td>'.$line->date_debut_evenement.'</td>
            <td>'.$line->date_fin_evenement.'</td>
            <td>'.$status.'</td>
            <td style="text-align: left;">'.$action_button.'</td>
        </tr>
        <tr class="resource-row" id="resources-'.$line->id_evenement.'" style="display:none;">
            <td colspan="7">
                <div class="resources-container"></div>
            </td>
        </tr>';
    }
}

$html .= '</table></small></small>';

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <script>
        $(document).ready(function(){
            $('.event-row').mouseenter(function(){
                var eventId = $(this).data('id');
                var resourceRow = $('#resources-' + eventId);

                $.ajax({
                    url: 'core/event/fetch_reserved_resources.php',
                    type: 'GET',
                    data: { id_evenement: eventId },
                    success: function(response) {
                        resourceRow.find('.resources-container').html(response);
                        resourceRow.show();
                    },
                    error: function(error) {
                        console.log('Error fetching resources:', error);
                    }
                });
            }).mouseleave(function(){
                var eventId = $(this).data('id');
                $('#resources-' + eventId).hide();
            });
        });
    </script>
</body>
</html>
