<?php
require_once __DIR__ . '/../includes/header.php';

header('Content-Type: application/json');

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$destination = isset($_GET['destination']) ? sanitize($_GET['destination']) : '';
$budget_min = isset($_GET['budget_min']) ? (int)$_GET['budget_min'] : 0;
$budget_max = isset($_GET['budget_max']) ? (int)$_GET['budget_max'] : 999999;
$page = getPageNumber();
const PER_PAGE = 12;

$query = "SELECT * FROM packages WHERE 1=1";

if (!empty($search)) {
    $search = sanitize($search);
    $query .= " AND (name LIKE '%$search%' OR description LIKE '%$search%' OR destination LIKE '%$search%')";
}

if (!empty($destination)) {
    $destination = sanitize($destination);
    $query .= " AND destination = '$destination'";
}

if ($budget_min > 0 || $budget_max < 999999) {
    $query .= " AND price BETWEEN $budget_min AND $budget_max";
}

$query .= " ORDER BY created_at DESC ";
$query .= getPaginationLimit($page, PER_PAGE);

$result = $conn->query($query);

$packages = [];
if ($result->num_rows > 0) {
    while ($package = $result->fetch_assoc()) {
        $packages[] = $package;
    }
}

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM packages WHERE 1=1";
if (!empty($search)) {
    $count_query .= " AND (name LIKE '%$search%' OR description LIKE '%$search%' OR destination LIKE '%$search%')";
}
if (!empty($destination)) {
    $count_query .= " AND destination = '$destination'";
}
if ($budget_min > 0 || $budget_max < 999999) {
    $count_query .= " AND price BETWEEN $budget_min AND $budget_max";
}

$count_result = $conn->query($count_query);
$count_row = $count_result->fetch_assoc();
$total_pages = ceil($count_row['total'] / PER_PAGE);

sendJSON([
    'success' => true,
    'packages' => $packages,
    'total_pages' => $total_pages,
    'current_page' => $page
]);
?>