$(document).ready(function () {
    // Load the initial list of managers
    loadManagers();

    // Handle search input
    $('#searchInput').on('keyup', function() {
        var query = $(this).val();
        if (query.length > 0) {
            searchManagers(query);
        } else {
            loadManagers();
        }
    });
});

// Function to load the initial list of managers
function loadManagers() {
    $.ajax({
        url: 'core/user/list_managers.php',
        method: 'POST',
        success: function(data) {
            $('#display').html(data).show();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

// Function to search for managers
function searchManagers(query) {
    $.ajax({
        url: 'core/user/search_managers.php',
        method: 'POST',
        data: { query: query },
        success: function(data) {
            $('#display').html(data).show();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

// Function to delete a user
function delete_user(id) {
    if (confirm('Etes-vous certain de vouloir supprimer cette ligne ?')) {
        $.post('core/user/delete.php', { id: id }, function (data) {
            alert(data);
            loadManagers();
        }, 'text');
    }
}
