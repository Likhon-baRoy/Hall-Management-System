// Load the user table with filter, search, and pagination data
function loadTable(page = 1) {
    const filter = document.getElementById('filter').value;
    const search = document.querySelector('input[name="search"]').value;

    $.ajax({
        url: '../../controller/admin/fetch_user.php',
        method: 'GET',
        data: { filter: filter, search: search, page: page },
        dataType: 'json', // Expect JSON data in response
        success: function(response) {
            // Check for any error in response
            if (response.error) {
                alert(response.error);
                return;
            }

            // Populate the table body with user data
            const userTableBody = $('#user-table-body');
            userTableBody.empty(); // Clear existing rows
            response.users.forEach(user => {
                userTableBody.append(`
                    <tr>
                        <td>${user.uid}</td>
                        <td>${user.username}</td>
                        <td>${user.email}</td>
                        <td>${user.phone}</td>
                        <td>${user.role}</td>
                        <td>
                            <a href='edit.php?id=${user.uid}' class='btn btn-warning edit-btn'>Edit</a>
                            <a href='delete.php?id=${user.uid}' class='btn btn-danger delete-btn' onclick="return confirmAction('delete')">Delete</a>
                        </td>
                    </tr>
                `);
            });

            // Update pagination buttons
            paginationInfo(response.pagination.currentPage, response.pagination.totalPages);
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            alert("An error occurred while loading data.");
        }
    });
}

// Update pagination buttons
function paginationInfo(currentPage, totalPages) {
    const pagination = $('#pagination');
    pagination.empty();

    for (let i = 1; i <= totalPages; i++) {
        pagination.append(`
            <button class="btn ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'}"
                    onclick="loadTable(${i})">${i}</button>
        `);
    }
}

// Event listeners for filter and search input changes
document.getElementById('filter').addEventListener('change', () => loadTable(1));
document.querySelector('input[name="search"]').addEventListener('keyup', () => loadTable(1));

// Initial load on page load
$(document).ready(function() {
    loadTable(1); // Load the first page of data initially
});

$(document).ready(function() {
    // Trigger AJAX on filter or search change
    $('#filter, #search').on('change input', function() {
        var filter = $('#filter').val();  // Get filter value
        var search = $('#search').val();  // Get search value

        // Show the loading spinner
        $('#loading-spinner').show();  // Ensure the spinner is shown

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
