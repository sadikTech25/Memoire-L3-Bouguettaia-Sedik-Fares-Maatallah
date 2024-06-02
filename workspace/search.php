<?php
session_start();
ob_start();

$account = isset($_SESSION['account']) ? (object)$_SESSION['account'] : header('location: core/user/deconnection.php');

require_once 'core.php';

try {
    $pdo = core_manager::generate_pdo();

    if (isset($_POST['query']) || isset($_POST['queryevent']) || isset($_POST['queryressource'])) {
        if (isset($_POST['query'])) {
            $search = '%' . $_POST['query'] . '%';
            $sql = "SELECT * FROM ressource WHERE nom_ressource LIKE :search OR description_ressource LIKE :search";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':search', $search, PDO::PARAM_STR);

            $html = '
            <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Ressources</h6>
            </div>
            <small><small>
            <table class="table text-start align-middle table-bordered table-striped table-hover mb-0">
                <tr class="text-dark">
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Description</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Lieu</th>
                    <th scope="col">Supplier</th>
                    <th scope="col">Status</th>
                </tr>';
        } elseif (isset($_POST['queryevent'])) {
            $search = '%' . $_POST['queryevent'] . '%';
            $sql = "SELECT e.* 
                    FROM evenement e 
                    INNER JOIN evenement_manager em 
                        ON e.id_evenement = em.evenement 
                    WHERE em.manager = :manager 
                    AND e.etat_evenement = 1 
                    AND (e.titre_evenement LIKE :search OR e.description_evenement LIKE :search)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':manager', $account->id_utilisateur, PDO::PARAM_INT);
            $stmt->bindParam(':search', $search, PDO::PARAM_STR);

            $html = '
            <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Events</h6>
            </div>
            <small><small>
            <table class="table text-start align-middle table-bordered table-striped table-hover mb-0">
                <tr class="text-dark">
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Date</th>
                    <th scope="col">Location</th>
                    <th scope="col">Status</th>
                </tr>';
        } elseif (isset($_POST['queryressource'])) {
            $search = '%' . $_POST['queryressource'] . '%';
            $sql = "SELECT * FROM ressource WHERE nom_ressource LIKE :search OR description_ressource LIKE :search";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':search', $search, PDO::PARAM_STR);

            $html = '
            <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Ressources</h6>
            </div>
            <small><small>
            <table class="table text-start align-middle table-bordered table-striped table-hover mb-0">
                <tr class="text-dark">
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Description</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Lieu</th>
                    <th scope="col">Supplier</th>
                    <th scope="col">Status</th>
                </tr>';
        }

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (isset($_POST['queryevent'])) {
                    $html .= '
                    <tr>
                        <td>'.htmlspecialchars($row['titre_evenement']).'</td>
                        <td>'.htmlspecialchars($row['description_evenement']).'</td>
                        <td>'.htmlspecialchars($row['date_evenement']).'</td>
                        <td>'.htmlspecialchars($row['lieu_evenement']).'</td>
                        <td>'.($row['etat_evenement'] == 1 ? 'Active' : 'Inactive').'</td>
                    </tr>';
                } else {
                    $html .= '
                    <tr>
                        <td>'.htmlspecialchars($row['nom_ressource']).'</td>
                        <td>'.htmlspecialchars(core_manager::display_label($row['type_ressource'])).'</td>
                        <td>'.htmlspecialchars($row['description_ressource']).'</td>
                        <td>'.htmlspecialchars($row['quantite_ressource']).'</td>
                        <td>'.htmlspecialchars($row['lieu_ressource']).'</td>
                        <td>'.htmlspecialchars($row['fournisseur_ressource']).'</td>
                        <td>'.($row['etat_ressource'] == 1 ? 'Available' : 'Not Available').'</td>
                    </tr>';
                }
            }
            $html .= '</table><small><small>';
        } else {
            $html .= '<tr><td colspan="7">No results found</td></tr></table>';
        }

        echo $html;
    }
} catch (Exception $e) {
    core_manager::treat_exception($e);
}
?>
