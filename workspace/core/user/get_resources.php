<?php
require_once '../core.php'; // Assurez-vous que cela pointe vers votre core.php

if (isset($_POST['supplier_id'])) {
    $supplier_id = intval($_POST['supplier_id']);
    $pdo = core_manager::generate_pdo();

    $stmt = $pdo->prepare("SELECT * FROM ressource WHERE fournisseur_ressource = :supplier_id");
    $stmt->bindParam(':supplier_id', $supplier_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo '<table class="table text-start align-middle table-bordered table-striped table-hover mb-0">';
        echo '<tr><th>Type</th><th>Description</th><th>Quantité</th></tr>';
        while ($resource = $stmt->fetchObject()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars(core_manager::display_label($resource->type_ressource)) . '</td>';
            echo '<td>' . htmlspecialchars($resource->description_ressource) . '</td>';
            echo '<td>' . htmlspecialchars($resource->quantite_ressource) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo 'Aucune ressource trouvée pour ce fournisseur.';
    }
}
?>
