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
        } elseif (isset($_POST['queryressource'])) {
            $search = '%' . $_POST['queryressource'] . '%';
            $sql = "SELECT * FROM ressource WHERE nom_ressource LIKE :search OR description_ressource LIKE :search";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':search', $search, PDO::PARAM_STR);
        }

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (isset($_POST['queryevent'])) {
                    echo '<div id="' . htmlspecialchars($row['id_evenement']) . '" class="result-item">' . htmlspecialchars($row['titre_evenement']) . ': ' . htmlspecialchars($row['description_evenement']) . '</div>';
                } else {
                    echo '<div id="' . htmlspecialchars($row['id_ressource']) . '" class="result-item">' . htmlspecialchars(core_manager::display_label($row['type_ressource'])) . ': ' . htmlspecialchars($row['description_ressource']) .': ' . htmlspecialchars($row['quantite_ressource']) . '</div>';
                }
            }
        } else {
            echo '<div class="result-item">No results found</div>';
        }
    }
} catch (Exception $e) {
    core_manager::treat_exception($e);
}
?>
