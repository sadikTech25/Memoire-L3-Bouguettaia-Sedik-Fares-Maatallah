$(document).ready(function () {
    $('div#displayressource').load('core/ressorces/list.php');
    $('div#displaysupplierressource').load('core/ressorces/list_supplierressource.php');
});
function addToPanier(eventId, idr,resourceQte) {
    var utrd = $('div#utr'+idr).val();

    if(utrd > 0 && utrd <= ressourceQte)
    {
        // AJAX request to add the resource to the cart
        $.ajax({
            url: '/core/panier/add.php',
            method: 'POST',
            data: { ide: eventId, idrd: idr ,utrd: utrd},
            success: function(response) {
                // Handle success response, e.g., display a success message
                alert('Resource added to cart successfully!');
                location.reload(); // For example, reload the page to refresh the cart
            },
            error: function(xhr, status, error) {
                // Handle error response, e.g., display an error message
                console.error(xhr.responseText);
                alert('An error occurred while adding the resource to the cart.');
            }
        });
    }
    else
    {
        if(utrd == 0) { alert('Erreur : Quantite Commandee nulle !!!')}
        else if(utrd < 0) { alert('Erreur : Quantite Commandee negative !!!')}
            else if(utrd > resourceQte) { alert("Erreur : Quantite Commandee supperieur a l'existant !!!")}
    }
}

function delete_ressource(id) {
    if(confirm('Etes-vous certain de vouloir supprimer cette ligne ?')) {
        $.post('core/ressorces/delete.php', {id:id}, function (data) { alert(data); document.location.reload(); }, 'text');
    }
}