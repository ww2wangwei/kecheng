<?php
header('Content-Type: application/json; charset=utf-8');

$dbPath = __DIR__ . '/../data/database.sqlite';
$input = json_decode(file_get_contents('php://input'), true);
$username = isset($input['username']) ? trim($input['username']) : '';
$password = isset($input['password']) ? $input['password'] : '';

if (!$username || !$password) {
    http_response_code(400);
    echo json_encode(['error' => '请输入用户名和密码']);
    exit;
}

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin || !password_verify($password, $admin['password'])) {
        http_response_code(401);
        echo json_encode(['error' => '用户名或密码错误']);
        exit;
    }

    // 生成简单token
    $token = base64_encode($username . '|' . time() . '|' . password_hash($username, PASSWORD_DEFAULT));
    echo json_encode(['token' => $token, 'username' => $username]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}