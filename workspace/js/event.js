$(document).ready(function () {
    $('div#display').load('core/event/list_events.php');
    $('div#displayevents').load('workspace/core/event/list_indexevents.php');
    $('div#client_events').load('core/event/list_client_events.php');
    $('div#events_for_manager').load('core/event/list_events_formanager.php');
    $('div#displaydemandedevent').load('core/event/list_demandedevents.php');
    $('div#displayacceptedevent').load('core/event/list_acceptedevents.php');
    $('div#displayrefusedevent').load('core/event/list_refusedevents.php');


    $('#searchInput').on('keyup', function() {
        var query = $(this).val().toLowerCase(); // Convert query to lowercase for case-insensitivity
        if (query.length > 0) {
            searchEvents(query);
        } else {
            $('div#display').load('core/event/list_events.php');
        }
    });
});

function searchEvents(query) {
    $.ajax({
        url: 'core/event/search_event.php',
        method: 'POST',
        data: { query: query },
        success: function(data) {
            $('#display').html(data);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

function redirect_to_page(ide) {
    window.location.href = 'available_ressources.php?ide=' + ide;
}

function delete_user(id) {
    if (confirm('Etes-vous certain de vouloir supprimer cette ligne ?')) {
        $.post('core/event/delete.php', { id: id }, function (data) { alert(data); document.location.reload(); }, 'text');
    }
}
