<?php
session_start();
require_once '../core.php';

$ide = isset($_GET['ide']) ? intval($_GET['ide']) : 0;
$pdo = core_manager::generate_pdo();

$html_demanded = '';
if ($ide > 0) {
    $html_demanded = '<h3>Demanded Resources</h3>
    <small><small>
    <table class="table text-start align-middle table-bordered table-striped table-hover mb-0">
        <tr class="text-dark">
            <th scope="col">Type</th>
            <th scope="col">Quantity Demanded</th>
            <th scope="col">Status</th>
        </tr>';

    // Fetch demanded resources for the event if event ID is provided
    $demanded_stmt = $pdo->prepare("
        SELECT type_ressource, quantite_ressource
        FROM ressource_demanded
        WHERE id_evenement = :ide
    ");
    $demanded_stmt->execute(['ide' => $ide]);

    // Fetch reserved resources for the event
    $reserved_stmt = $pdo->prepare("
        SELECT re.id_ressource, re.quantite_ressource, r.type_ressource
        FROM ressource_evenement re
        INNER JOIN ressource r ON re.id_ressource = r.id_ressource
        WHERE re.id_evenement = :ide
    ");
    $reserved_stmt->execute(['ide' => $ide]);

    $reserved_resources = [];
    if ($reserved_stmt && $reserved_stmt->rowCount() > 0) {
        while ($reserved = $reserved_stmt->fetch(PDO::FETCH_ASSOC)) {
            $reserved_resources[] = $reserved;
        }
    }

    if ($demanded_stmt && $demanded_stmt->rowCount() > 0) {
        while ($demanded = $demanded_stmt->fetch(PDO::FETCH_ASSOC)) {
            $is_reserved = false;
            foreach ($reserved_resources as $reserved) {
                if ($reserved['type_ressource'] == $demanded['type_ressource'] &&
                    $reserved['quantite_ressource'] == $demanded['quantite_ressource']) {
                    $is_reserved = true;
                    break;
                }
            }
            $html_demanded .= '
            <tr>
                <td>'.core_manager::display_label($demanded['type_ressource']).'</td>
                <td>'.$demanded['quantite_ressource'].'</td>
                <td>'.($is_reserved ? '<span style="color: green;"><i class="bi bi-check2-circle"></i></span>' : '').'</td>
            </tr>';
        }
    } else {
        $html_demanded .= '<tr><td colspan="3">No demanded resources found for this event.</td></tr>';
    }
    $html_demanded .= '</table></small></small>';
}
// Display the demanded resources
echo $html_demanded;

$html_available = '<br><h3>Available Resources</h3>
<small><small>
<table class="table text-start align-middle table-bordered table-striped table-hover mb-0">
    <tr class="text-dark">
        <th scope="col">Type</th>
        <th scope="col">Description</th>
        <th scope="col">Available Quantity</th>
        <th scope="col">Supplier</th>
        <th scope="col">Price DA</th>';

if ($ide > 0) {
    $html_available .= '<th scope="col">Quantity to Reserve</th>
                        <th scope="col">Action</th>';
}

$html_available .= '</tr>';

// Fetch all available resources
$available_stmt = $pdo->prepare("
    SELECT r.type_ressource, r.description_ressource, r.quantite_ressource, r.prix_ressource, u.email_utilisateur, r.id_ressource
    FROM ressource r
    INNER JOIN utilisateur u ON r.fournisseur_ressource = u.id_utilisateur
    WHERE r.etat_ressource = 1
");
$available_stmt->execute();

if ($available_stmt && $available_stmt->rowCount() > 0) {
    while ($available = $available_stmt->fetch(PDO::FETCH_ASSOC)) {
        $html_available .= '
        <tr>
            <td>'.core_manager::display_label($available['type_ressource']).'</td>
            <td>'.$available['description_ressource'].'</td>
            <td>'.$available['quantite_ressource'].'</td>
            <td>'.$available['email_utilisateur'].'</td>
            <td>'.$available['prix_ressource'].'</td>';

        if ($ide > 0) {
            $html_available .= '
            <td>
                <input type="number" id="quantity_'.$available['id_ressource'].'" value="0" min="0" max="'.$available['quantite_ressource'].'" style="width: 50px;">
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-primary" onclick="addToPanier('.$ide.', '.$available['id_ressource'].', '.$available['quantite_ressource'].')">
                    <small>Reserve</small>
                </button>
            </td>';
        }

        $html_available .= '</tr>';
    }
} else {
    $html_available .= '<tr><td colspan="'.($ide > 0 ? '7' : '5').'">No available resources found.</td></tr>';
}
$html_available .= '</table></small></small>';

// Display the available resources
echo $html_available;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Available Resources</title>
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
function addToPanier(ide, idr, quantityAvailable) {
    var quantityDemanded = $('#quantity_' + idr).val();
    quantityDemanded = parseInt(quantityDemanded, 10);

    if (quantityDemanded > 0 && quantityDemanded <= quantityAvailable) {
        $.ajax({
            url: 'core/ressorces/add_to_panier.php',
            type: 'POST',
            data: {
                id_evenement: ide,
                id_ressource: idr,
                quantite_ressource: quantityDemanded
            },
            success: function(response) {
                try {
                    var res = JSON.parse(response);
                    if (res.status === 'success') {
                        alert('Resource reserved for the event.');
                        location.reload();
                    } else {
                        alert(res.message);
                    }
                } catch (e) {
                    console.log('Error parsing response:', e);
                    alert('Unexpected error occurred.');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error:', error);
                alert('Error reserving resource for the event.');
            }
        });
    } else {
        alert('Invalid quantity.');
    }
}
</script>
</body>
</html>
