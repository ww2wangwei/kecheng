<?php
header('Content-Type: application/json; charset=utf-8');

$dbPath = __DIR__ . '/../data/database.sqlite';
$input = json_decode(file_get_contents('php://input'), true);
$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$courseId) {
    http_response_code(400);
    echo json_encode(['error' => '缺少课程ID']);
    exit;
}

$fields = ['name', 'major_id', 'college_id', 'image_url', 'description', 'online_url', 'offline_url'];
$updates = [];
$params = [];

foreach ($fields as $field) {
    if (isset($input[$field])) {
        $updates[] = "$field = ?";
        $params[] = $input[$field];
    }
}

if (empty($updates)) {
    http_response_code(400);
    echo json_encode(['error' => '没有要更新的字段']);
    exit;
}

$params[] = $courseId;

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE courses SET " . implode(', ', $updates) . " WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}