<?php
include '../../config/config.php';

header('Content-Type: application/json'); // Set the response header to JSON

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$sql = "SELECT uid, username, email, phone, role FROM c_info";
$whereClauses = [];

// Apply role filter
if ($filter === 'student') {
    $whereClauses[] = "role = 'Student'";
} elseif ($filter === 'staff') {
    $whereClauses[] = "role = 'Staff'";
} elseif ($filter === 'administrator') {
    $whereClauses[] = "role = 'Administrator'";
}

// Apply search condition for uid, username, or email
if (!empty($search)) {
    $searchEscaped = mysqli_real_escape_string($myconnect, $search);
    $whereClauses[] = "(uid = '$searchEscaped' OR username LIKE '%$searchEscaped%' OR email LIKE '%$searchEscaped%')";
}

// Combine base query with conditions
if (!empty($whereClauses)) {
    $sql .= " WHERE " . implode(' AND ', $whereClauses);
}

// Add pagination to the query
$sql .= " LIMIT $limit OFFSET $offset";

// Execute query
$result = mysqli_query($myconnect, $sql);
if (!$result) {
    echo json_encode(['error' => "Query failed: " . mysqli_error($myconnect)]);
    exit;
}

// Collect data to return as JSON
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data['users'][] = $row;
}

// Calculate pagination details
$countQuery = "SELECT COUNT(*) as total FROM c_info";
if (!empty($whereClauses)) {
    $countQuery .= " WHERE " . implode(' AND ', $whereClauses);
}
$countResult = mysqli_query($myconnect, $countQuery);
$totalRecords = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRecords / $limit);

$data['pagination'] = [
    'currentPage' => $page,
    'totalPages' => $totalPages
];

// Output JSON data
echo json_encode($data);
?>
