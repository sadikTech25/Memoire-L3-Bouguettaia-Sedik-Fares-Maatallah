$(document).ready(function () {
    $('div#display').load('core/user/list_suppliers.php');
});

function delete_user(id) {
    if(confirm('Etes-vous certain de vouloir supprimer cette ligne ?')) {
        $.post('core/user/deletesupplier.php', {id:id}, function (data) { alert(data); document.location.reload(); }, 'text');
    }
}