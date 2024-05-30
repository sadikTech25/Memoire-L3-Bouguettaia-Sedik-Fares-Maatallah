$(document).ready(function () {
    $('div#display').load('core/event/list_events.php');
    $('div#displayevents').load('workspace/core/event/list_indexevents.php');
    $('div#client_events').load('core/event/list_client_events.php');
    $('div#events_for_manager').load('core/event/list_events_formanager.php');
    $('div#displaydemandedevent').load('core/event/list_demandedevents.php');
    $('div#displayacceptedevent').load('core/event/list_acceptedevents.php');
    $('div#displayrefusedevent').load('core/event/list_refusedevents.php');
});

function redirect_to_page(ide)
{
    window.location.href = 'available_ressources.php?ide='+ide;
}

function delete_user(id) {
    if(confirm('Etes-vous certain de vouloir supprimer cette ligne ?')) {
        $.post('core/event/delete.php', {id:id}, function (data) { alert(data); document.location.reload(); }, 'text');
    }
}
