<?php
header('Content-Type: application/json; charset=utf-8');

$dbPath = __DIR__ . '/../data/database.sqlite';

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 获取所有学院，含课程数
    $stmt = $pdo->query("
        SELECT c.id, c.name, c.icon,
               COUNT(DISTINCT m.id) as major_count,
               COUNT(cou.id) as course_count
        FROM colleges c
        LEFT JOIN majors m ON m.college_id = c.id
        LEFT JOIN courses cou ON cou.college_id = c.id
        GROUP BY c.id
        ORDER BY c.id
    ");
    $colleges = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($colleges);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}