<?php
session_start();
ob_start();

require_once '../core.php';

if (isset($_POST['query'])) {
    $search = '%' . $_POST['query'] . '%';

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
    $query = "SELECT u.*, COUNT(em.evenement) AS event_count
              FROM utilisateur u
              LEFT JOIN evenement_manager em ON u.id_utilisateur = em.manager
              WHERE u.type_utilisateur = 3
              AND (u.adresse_utilisateur LIKE :search OR u.email_utilisateur LIKE :search OR u.nom_utilisateur LIKE :search OR u.prenom_utilisateur LIKE :search)
              GROUP BY u.id_utilisateur";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
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
    } else {
        $html .= '<tr><td colspan="7">No results found</td></tr></table>';
    }

    echo $html;
}
?>
