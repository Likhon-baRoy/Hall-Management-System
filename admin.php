<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dormitory Management System</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
  </head>
  <body>
    <div class="d-flex both">
      <!-- Sidebar -->
      <div class="sidebar">
        <h2>ADMIN</h2>
        <ul class="nav flex-column">
          <li><a href="#"><i class="fas fa-user"></i> Provost</a></li>
          <li><a href="#"><i class="fas fa-building"></i> Hall</a></li>
          <li><a href="#"><i class="fas fa-utensils"></i> Dining</a></li>
          <li><a href="view/cuview.php" class="active"><i class="fas fa-user-graduate"></i> Students</a></li>
          <li><a href="#"><i class="fas fa-users"></i> Staffs</a></li>
          <li><a href="#"><i class="fas fa-door-open"></i> Floors</a></li>
          <li><a href="#"><i class="fas fa-bed"></i> Rooms</a></li>
          <li><a href="#"><i class="fas fa-exclamation-triangle"></i> Facilities Problem</a></li>
          <li><a href="#"><i class="fas fa-envelope"></i> Messages</a></li>
          <li><a href="logout.html"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
        </ul>
      </div>

      <!-- Dashboard Content -->
      <div class="dashboard container">
        <h1 class="text-center">Dormitory Management System</h1>
        <!-- Manage Students Section in admin.php -->
        <div id="manage-students">
          <h2>Manage Students</h2>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Preferred Hall</th>
                <th>Room Type</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="students-table-body">
              <!-- AJAX will dynamically load student rows here -->
            </tbody>
          </table>
        </div>

        <!-- Include a script to load student data -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
          $(document).ready(function(){
              // Fetch and load student data when the page is ready
              loadStudents();

              // Function to fetch and display student data
              function loadStudents() {
                  $.ajax({
                      url: "fetch_students.php", // PHP file to fetch student data
                      method: "GET",
                      success: function(data) {
                          $("#students-table-body").html(data); // Populate table body with data
                      }
                  });
              }

              // More AJAX functions for editing, deleting, etc., can be added here
          });
        </script>

        <!-- Modal for Editing Student -->
        <div class="modal" id="editStudentModal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Edit Student</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <form id="editStudentForm">
                  <input type="hidden" name="uid" id="edit-uid">
                  <div class="form-group">
                    <label for="edit-username">Username:</label>
                    <input type="text" class="form-control" name="username" id="edit-username">
                  </div>
                  <div class="form-group">
                    <label for="edit-preferred-hall">Preferred Hall:</label>
                    <input type="text" class="form-control" name="preferred_hall" id="edit-preferred-hall">
                  </div>
                  <!-- Add more fields as needed -->
                  <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <script>
          $(document).on('click', '.edit-btn', function() {
              let uid = $(this).data('uid');
              $.ajax({
                  url: "get_student.php", // PHP file to fetch specific student data
                  method: "POST",
                  data: {uid: uid},
                  dataType: "json",
                  success: function(data) {
                      $('#edit-uid').val(data.uid);
                      $('#edit-username').val(data.username);
                      $('#edit-preferred-hall').val(data.preferred_hall);
                      // Populate more fields if needed
                      $('#editStudentModal').modal('show');
                  }
              });
          });

          $('#editStudentForm').submit(function(e) {
              e.preventDefault();
              $.ajax({
                  url: "update_student.php", // PHP file to handle update
                  method: "POST",
                  data: $(this).serialize(),
                  success: function(response) {
                      $('#editStudentModal').modal('hide');
                      loadStudents(); // Reload the students table
                  }
              });
          });
        </script>
  </body>
</html>
