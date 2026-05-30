<?php
header('Content-Type: application/json; charset=utf-8');

$dbPath = __DIR__ . '/../data/database.sqlite';
$collegeId = isset($_GET['college_id']) ? (int)$_GET['college_id'] : 0;

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 获取学院下的所有专业方向
    $stmt = $pdo->prepare("
        SELECT m.id, m.name, m.college_id,
               COUNT(cou.id) as course_count
        FROM majors m
        LEFT JOIN courses cou ON cou.major_id = m.id
        WHERE m.college_id = ?
        GROUP BY m.id
        ORDER BY m.id
    ");
    $stmt->execute([$collegeId]);
    $majors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($majors);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}