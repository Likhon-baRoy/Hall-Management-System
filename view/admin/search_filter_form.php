<div class="d-flex justify-content-between align-items-center mb-3">
    <!-- Filter Dropdown -->
    <form id="filter-form" class="d-inline-block">
        <label for="filter">Filter: </label>
        <select name="filter" id="filter" class="form-select">
            <option value="all">All Users</option>
            <option value="student">Students</option>
            <option value="staff">Staffs</option>
            <option value="administrator">Admins</option>
        </select>
    </form>

    <!-- Search Form -->
    <form id="search-form" class="d-inline-block">
        <input type="text" name="search" id="search" placeholder="Search by User ID" class="form-control" style="display:inline-block; width:auto;">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>
