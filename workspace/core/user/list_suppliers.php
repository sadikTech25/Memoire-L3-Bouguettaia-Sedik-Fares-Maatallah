<?php 
session_start(); 
ob_start(); // Sécurité à développer

require_once '../core.php'; // Création d'un objet PDO pour accéder à la base de données

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
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE type_utilisateur=4"); 
$stmt->execute();

if(isset($stmt) && $stmt !== false && $stmt->rowCount() > 0) {
    
    while($line = $stmt->fetchObject()) {
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
}

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <script>
        $(document).ready(function(){
            $('.supplier-row').mouseenter(function(){
                var supplierId = $(this).data('id');
                var resourceRow = $('#resources-' + supplierId);
                console.log('Mouse entered row with supplier ID:', supplierId); // Debug logging

                $.ajax({
                    url: 'core/user/get_resources.php', // Server-side script to fetch resources
                    type: 'POST',
                    data: {supplier_id: supplierId},
                    success: function(response) {
                        console.log('AJAX success:', response); // Debug logging
                        resourceRow.find('.resources-container').html(response);
                        resourceRow.show();
                    },
                    error: function(error) {
                        console.log('AJAX error:', error); // Debug logging
                    }
                });
            });

            $('.supplier-row').mouseleave(function(){
                var supplierId = $(this).data('id');
                console.log('Mouse left row with supplier ID:', supplierId); // Debug logging
                $('#resources-' + supplierId).hide();
            });
        });
    </script>

</body>
</html>
