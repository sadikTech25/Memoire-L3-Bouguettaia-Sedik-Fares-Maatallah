<?php
session_start();
ob_start();

require_once '../core.php';

$html = '
<small><small>
<table class="table text-start align-middle table-bordered table-striped table-hover mb-0">
    <tr class="text-dark">
        <th scope="col">Title</th>
        <th scope="col">Start Date</th>
        <th scope="col">End Date</th>
        <th scope="col">Manager</th>
        <th scope="col">Status</th>
        <th scope="col">Action</th>
    </tr>';
$pdo = core_manager::generate_pdo();

// Fetch validated managers not handling any event
$manager_stmt = $pdo->prepare("
    SELECT u.id_utilisateur, u.nom_utilisateur, u.prenom_utilisateur
    FROM utilisateur u
    LEFT JOIN evenement_manager em ON u.id_utilisateur = em.manager
    WHERE u.type_utilisateur = 3 
      AND u.manager_isvalidated = 1 
      AND u.etat_utilisateur = 1
      AND em.manager IS NULL
");
$manager_stmt->execute();

$manager_options = '';

if ($manager_stmt && $manager_stmt->rowCount() > 0) {
    while ($manager = $manager_stmt->fetchObject()) {
        $manager_options .= '<option value="' . $manager->id_utilisateur . '">' . $manager->nom_utilisateur . ' ' . $manager->prenom_utilisateur . '</option>';
    }
}

// Fetch events and order by status
$stmt = $pdo->prepare("SELECT * FROM evenement ORDER BY FIELD(etat_evenement, 0, 1, 2)");
$stmt->execute();

if(isset($stmt) && $stmt !== false && $stmt->rowCount() > 0) {
    while($line = $stmt->fetchObject()) {
        $manager_event_stmt = $pdo->prepare("
            SELECT u.mobile_utilisateur,u.nom_utilisateur,u.prenom_utilisateur, em.manager
            FROM evenement_manager em
            INNER JOIN utilisateur u ON em.manager = u.id_utilisateur
            WHERE em.evenement = :id_evenement
        ");
        $manager_event_stmt->bindParam(':id_evenement', $line->id_evenement, PDO::PARAM_INT);
        $manager_event_stmt->execute();
        
        $has_manager = $manager_event_stmt->rowCount() > 0;

        if ($has_manager) {
            while($line_manager = $manager_event_stmt->fetchObject()) {
                $html .= '
                <tr>
                    <td>'.$line->titre_evenement.'</td>
                    <td>'.$line->date_debut_evenement.'</td>
                    <td>'.$line->date_fin_evenement.'</td>
                    <td>'.$line_manager->nom_utilisateur.' '.$line_manager->prenom_utilisateur.' ('.$line_manager->mobile_utilisateur.')</td>
                    <td>'. ($line->etat_evenement == 0 ? 'Demanded' : ($line->etat_evenement == 1 ? 'Accepted' :'Refused')) . '</td>
                    <td style="text-align:right;">
                    </td>
                </tr>';
            }
        } else {
            $html .= '
            <tr>
                <td>'.$line->titre_evenement.'</td>
                <td>'.$line->date_debut_evenement.'</td>
                <td>'.$line->date_fin_evenement.'</td>
                <td> Not Selected Yet </td>
                <td>'. ($line->etat_evenement == 0 ? 'Demanded' : ($line->etat_evenement == 1 ? 'Accepted' :'Refused')) . '</td>
                <td style="text-align:left;">
                    '.($line->etat_evenement == 0 ? '<button type="button" class="btn btn-sm btn-primary open-popup-btn" data-toggle="modal" data-target="#exampleModal" data-event-id="'.$line->id_evenement.'">
                    <small><i class="bi bi-check-circle-fill"> </i> Accepter</small>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger open-popup-btn" data-toggle="modal" data-target="#exampleModal" data-event-id="'.$line->id_evenement.'">
                    <small><i class="bi bi-x-circle-fill"> </i> Refuser</small>
                    </button>' : '').'
                </td>
            </tr>';
        }
    }
    $html .= '</table></small></small>';
}

echo $html;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Event Management</title>
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
                    <form id="assign-manager-form" method="post">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="floatingSelect" name="event_id" aria-label="Floating label select example">
                                <!-- Event option will be set dynamically via JavaScript -->
                            </select>
                            <label for="floatingSelect">The Event</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="floatingSelect2" name="manager_id" aria-label="Floating label select example">
                                <?php echo $manager_options; ?>
                            </select>
                            <label for="floatingSelect2">Select The Manager</label>
                        </div>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="select_manager()">Validate</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.open-popup-btn').on('click', function() {
                var eventId = $(this).data('event-id');
                $('#floatingSelect').html('<option value="' + eventId + '">' + $(this).closest('tr').find('td:first').text() + '</option>');
            });
        });

        function select_manager() {
            var formData = $('#assign-manager-form').serialize();
            $.ajax({
                url: 'core/user/selectmanager.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    alert('Manager successfully assigned!');
                    location.reload();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    </script>
</body>
</html>
