<?php
session_start();
ob_start();

require_once '../core.php';

if (isset($_POST['query'])) {
    $search = '%' . strtolower($_POST['query']) . '%';

    $html = '
    <small><small>
    <p style="color:orange;">Hover over a supplier row to see their resources.</p>
        <table class="table text-start align-middle table-bordered table-striped table-hover mb-0">
            <tr class="text-dark">
                <th scope="col">Email</th>
                <th scope="col">Name</th>
                <th scope="col">Address</th>
                <th scope="col">Mobile</th>
                <th scope="col">Etat</th>
            </tr>';

    $pdo = core_manager::generate_pdo();
    $query = "SELECT * FROM utilisateur WHERE type_utilisateur=4 AND (LOWER(adresse_utilisateur) LIKE :search OR LOWER(email_utilisateur) LIKE :search OR LOWER(nom_utilisateur) LIKE :search OR LOWER(prenom_utilisateur) LIKE :search)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($line = $stmt->fetchObject()) {
            $html .= '    
            <tr class="supplier-row" data-id="'.$line->id_utilisateur.'">
                <td>'.$line->email_utilisateur.'</td>
                <td>'.$line->nom_utilisateur.' '.$line->prenom_utilisateur.'</td>
                <td>'.$line->adresse_utilisateur.'</td>
                <td>'.$line->mobile_utilisateur.'</td>
                <td>'. ($line->etat_utilisateur == 1 ? 'Active' : 'Not Active') .'</td>
            </tr>
            <tr class="resource-row" id="resources-'.$line->id_utilisateur.'" style="display:none;">
                <td colspan="5">
                    <div class="resources-container"></div>
                </td>
            </tr>';
        }

        $html .= '</table></small></small>';
    } else {
        $html .= '<tr><td colspan="5">No results found</td></tr></table>';
    }

    echo $html;
}
?>
