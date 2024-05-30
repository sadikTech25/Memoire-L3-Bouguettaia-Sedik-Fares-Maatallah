$(document).ready(function () {
    $('div#display').load('core/user/list_managers.php');
});

function delete_user(id) {
    if(confirm('Etes-vous certain de vouloir supprimer cette ligne ?')) {
        $.post('core/user/delete.php', {id:id}, function (data) { alert(data); document.location.reload(); }, 'text');
    }
}