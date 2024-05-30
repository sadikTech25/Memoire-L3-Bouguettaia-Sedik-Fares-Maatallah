<?php
require_once '../core.php'; // Ensure this points to your core.php

if (isset($_GET['id_evenement'])) {
    $eventId = intval($_GET['id_evenement']);
    $pdo = core_manager::generate_pdo();

    $stmt = $pdo->prepare("
        SELECT r.type_ressource, re.quantite_ressource, u.email_utilisateur AS supplier_email
        FROM ressource_evenement re
        INNER JOIN ressource r ON re.id_ressource = r.id_ressource
        INNER JOIN utilisateur u ON r.fournisseur_ressource = u.id_utilisateur
        WHERE re.id_evenement = :eventId
    ");
    $stmt->execute(['eventId' => $eventId]);

    if ($stmt && $stmt->rowCount() > 0) {
        $resources_html = '<ul>';
        while ($resource = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $resources_html .= '<li>Type: ' . htmlspecialchars($resource['type_ressource']) . 
                               ' | Quantity: ' . htmlspecialchars($resource['quantite_ressource']) . 
                               ' | Supplier: ' . htmlspecialchars($resource['supplier_email']) . '</li>';
        }
        $resources_html .= '</ul>';
        echo $resources_html;
    } else {
        echo 'No reserved resources found for this event.';
    }
} else {
    echo 'Invalid event ID.';
}
?>
