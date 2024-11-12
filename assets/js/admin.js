function loadUserTable() {
    const filter = document.getElementById('filter').value;
    const search = document.querySelector('input[name="search"]').value;

    $.ajax({
        url: '../../controller/admin/fetch_user.php',
        type: 'GET',
        data: { filter: filter, search: search },
        success: function(response) {
            $('#user-table-body').html(response); // Populate the table body
        },
        error: function() {
            alert("An error occurred while fetching data.");
        }
    });
}

// Trigger table load on filter or search change
document.getElementById('filter').addEventListener('change', loadUserTable);
        document.querySelector('input[name="search"]').addEventListener('keyup', loadUserTable);

// Load the table on page load
window.onload = loadUserTable;

// Show a loading spinner or progress indicator when the AJAX request is in progress
$(document).ready(function() {
    // Trigger AJAX on filter or search change
    $('#filter, #search').on('change input', function() {
        var filter = $('#filter').val();  // Get filter value
        var search = $('#search').val();  // Get search value

        // Show the loading spinner
        $('#loading-spinner').show();

        // Send the request to fetch_user.php
        $.ajax({
            url: 'fetch_user.php',  // The PHP file to fetch data from
            method: 'GET',
            data: {
                filter: filter,
                search: search
            },
            success: function(data) {
                // When the data is successfully fetched, update the table
                $('#user-table-body').html(data);

                // Hide the loading spinner after data is loaded
                $('#loading-spinner').hide();
            },
            error: function(xhr, status, error) {
                // Log error to the console
                console.error("AJAX Error: " + status + ": " + error);

                // Hide the loading spinner in case of error
                $('#loading-spinner').hide();
            }
        });
    });
});

function deleteUser(uid) {
    if (confirm('Are you sure you want to delete this user?')) {
        $.ajax({
            url: 'delete.php',
            method: 'GET',
            data: { uid: uid },
            success: function(response) {
                if (response.success) {
                    alert('User deleted successfully!');
                    // Reload the table data
                    loadUserData();
                } else {
                    alert('An error occurred while deleting.');
                }
            }
        });
    }
}
