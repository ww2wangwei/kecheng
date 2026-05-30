<?php
header('Content-Type: application/json; charset=utf-8');

$auth = isset($_SERVER['HTTP_AUTHORIZATION']) ? trim(str_replace('Bearer', '', $_SERVER['HTTP_AUTHORIZATION'])) : '';
if (!$auth) {
    http_response_code(401);
    echo json_encode(['error' => '未授权']);
    exit;
}

$dbPath = __DIR__ . '/../data/database.sqlite';

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT m.id, m.name, m.college_id, c.name as college_name FROM majors m JOIN colleges c ON c.id = m.college_id ORDER BY m.id");
    $majors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    foreach ($majors as $m) {
        $couStmt = $pdo->prepare("SELECT COUNT(*) FROM courses WHERE major_id = ?");
        $couStmt->execute([$m['id']]);
        $result[] = [
            'id' => (int)$m['id'],
            'name' => $m['name'],
            'college_id' => (int)$m['college_id'],
            'college_name' => $m['college_name'],
            'course_count' => (int)$couStmt->fetchColumn()
        ];
    }

    echo json_encode($result);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}