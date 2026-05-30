<?php
header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];
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

    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT id, name FROM colleges ORDER BY id");
        $colleges = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($colleges as $c) {
            $mStmt = $pdo->prepare("SELECT COUNT(*) FROM majors WHERE college_id = ?");
            $mStmt->execute([$c['id']]);
            $couStmt = $pdo->prepare("SELECT COUNT(*) FROM courses WHERE college_id = ?");
            $couStmt->execute([$c['id']]);
            $result[] = [
                'id' => (int)$c['id'],
                'name' => $c['name'],
                'major_count' => (int)$mStmt->fetchColumn(),
                'course_count' => (int)$couStmt->fetchColumn()
            ];
        }
        echo json_encode($result);
    } elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $name = trim($data['name'] ?? '');
        if (!$name) {
            http_response_code(400);
            echo json_encode(['error' => '学院名称不能为空']);
            exit;
        }
        $stmt = $pdo->prepare("INSERT INTO colleges (name) VALUES (?)");
        $stmt->execute([$name]);
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(405);
        echo json_encode(['error' => '不支持的方法']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}