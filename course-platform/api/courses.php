<?php
header('Content-Type: application/json; charset=utf-8');

$dbPath = __DIR__ . '/../data/database.sqlite';
$collegeId = isset($_GET['college_id']) ? (int)$_GET['college_id'] : 0;
$majorId = isset($_GET['major_id']) ? (int)$_GET['major_id'] : 0;

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT c.*, m.name as major_name, col.name as college_name
            FROM courses c
            JOIN majors m ON m.id = c.major_id
            JOIN colleges col ON col.id = c.college_id
            WHERE 1=1";
    $params = [];

    if ($collegeId > 0) {
        $sql .= " AND c.college_id = ?";
        $params[] = $collegeId;
    }
    if ($majorId > 0) {
        $sql .= " AND c.major_id = ?";
        $params[] = $majorId;
    }

    $sql .= " ORDER BY c.id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($courses);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}