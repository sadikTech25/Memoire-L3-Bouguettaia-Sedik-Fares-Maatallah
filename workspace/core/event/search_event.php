<?php
session_start();
ob_start();

require_once '../core.php';

if (isset($_POST['query'])) {
    $search = '%' . strtolower($_POST['query']) . '%';

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

    // Fetch events that match the search query
    $stmt = $pdo->prepare("SELECT * FROM evenement WHERE LOWER(titre_evenement) LIKE :search");
    $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($line = $stmt->fetchObject()) {
            // Fetch manager details for the event
            $manager_event_stmt = $pdo->prepare("
                SELECT u.mobile_utilisateur, u.nom_utilisateur, u.prenom_utilisateur, em.manager
                FROM evenement_manager em
                INNER JOIN utilisateur u ON em.manager = u.id_utilisateur
                WHERE em.evenement = :id_evenement
            ");
            $manager_event_stmt->bindParam(':id_evenement', $line->id_evenement, PDO::PARAM_INT);
            $manager_event_stmt->execute();
            
            $has_manager = $manager_event_stmt->rowCount() > 0;

            if ($has_manager) {
                while ($line_manager = $manager_event_stmt->fetchObject()) {
                    $html .= '
                    <tr>
                        <td>'.$line->titre_evenement.'</td>
                        <td>'.$line->date_debut_evenement.'</td>
                        <td>'.$line->date_fin_evenement.'</td>
                        <td>'.$line_manager->nom_utilisateur.' '.$line_manager->prenom_utilisateur.' ('.$line_manager->mobile_utilisateur.')</td>
                        <td>'. ($line->etat_evenement == 0 ? 'Demanded' : ($line->etat_evenement == 1 ? 'Accepted' :'Refused')) . '</td>
                        <td style="text-align:right;"></td>
                    </tr>';
                }
            } else {
                $html .= '
                <tr>
                    <td>'.$line->titre_evenement.'</td>
                    <td>'.$line->date_debut_evenement.'</td>
                    <td>'.$line->date_fin_evenement.'</td>
                    <td>Not Selected Yet</td>
                    <td>'. ($line->etat_evenement == 0 ? 'Demanded' : ($line->etat_evenement == 1 ? 'Accepted' :'Refused')) . '</td>
                    <td style="text-align:left;">
                        '.($line->etat_evenement == 0 ? '<button type="button" class="btn btn-sm btn-primary open-popup-btn" data-toggle="modal" data-target="#exampleModal" data-event-id="'.$line->id_evenement.'">
                        <small><i class="bi bi-check-circle-fill"></i> Accepter</small>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger open-popup-btn" data-toggle="modal" data-target="#exampleModal" data-event-id="'.$line->id_evenement.'">
                        <small><i class="bi bi-x-circle-fill"></i> Refuser</small>
                        </button>' : '').'
                    </td>
                </tr>';
            }
        }
        $html .= '</table></small></small>';
    } else {
        $html .= '<tr><td colspan="6">No results found</td></tr></table>';
    }

    echo $html;
}
?>
