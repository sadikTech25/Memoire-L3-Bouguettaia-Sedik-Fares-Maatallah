$(document).ready(function () {
    // Load the initial list of suppliers
    loadSuppliers();

    // Handle search input
    $('#searchInput').on('keyup', function() {
        var query = $(this).val();
        if (query.length > 0) {
            searchSuppliers(query);
        } else {
            loadSuppliers();
        }
    });

    // Event delegation for mouse enter and leave events
    $('#display').on('mouseenter', '.supplier-row', function() {
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

    $('#display').on('mouseleave', '.supplier-row', function() {
        var supplierId = $(this).data('id');
        console.log('Mouse left row with supplier ID:', supplierId); // Debug logging
        $('#resources-' + supplierId).hide();
    });
});

// Function to load the initial list of suppliers
function loadSuppliers() {
    $.ajax({
        url: 'core/user/list_suppliers.php',
        method: 'POST',
        success: function(data) {
            $('#display').html(data).show();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

// Function to search for suppliers
function searchSuppliers(query) {
    $.ajax({
        url: 'core/user/search_suppliers.php',
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
        $.post('core/user/deletesupplier.php', { id: id }, function (data) {
            alert(data);
            loadSuppliers();
        }, 'text');
    }
}
