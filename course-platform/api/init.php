<?php
header('Content-Type: application/json; charset=utf-8');

$dbPath = __DIR__ . '/../data/database.sqlite';
$schemaPath = __DIR__ . '/../data/schema.sql';
$jsonPath = __DIR__ . '/../courses.json';

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 初始化表结构
    $schema = file_get_contents($schemaPath);
    $pdo->exec($schema);

    // 检查是否已有数据
    $stmt = $pdo->query("SELECT COUNT(*) FROM colleges");
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['status' => 'already_initialized', 'colleges' => 9, 'courses' => 48]);
        exit;
    }

    // 导入课程数据
    $courses = json_decode(file_get_contents($jsonPath), true);

    $collegeMap = [];
    $majorMap = [];
    $collegeId = $majorId = 1;

    foreach ($courses as $course) {
        $collegeName = $course['产业学院'];
        $majorName = $course['专业方向'];
        $courseName = $course['课程'];

        if (!isset($collegeMap[$collegeName])) {
            $stmt = $pdo->prepare("INSERT OR IGNORE INTO colleges (id, name) VALUES (?, ?)");
            $stmt->execute([$collegeId, $collegeName]);
            $collegeMap[$collegeName] = $collegeId++;
        }

        $collegeIdVal = $collegeMap[$collegeName];
        $majorKey = $collegeName . '|' . $majorName;
        if (!isset($majorMap[$majorKey])) {
            $stmt = $pdo->prepare("INSERT OR IGNORE INTO majors (id, name, college_id) VALUES (?, ?, ?)");
            $stmt->execute([$majorId, $majorName, $collegeIdVal]);
            $majorMap[$majorKey] = $majorId++;
        }

        $majorIdVal = $majorMap[$majorKey];
        $stmt = $pdo->prepare("INSERT INTO courses (name, major_id, college_id) VALUES (?, ?, ?)");
        $stmt->execute([$courseName, $majorIdVal, $collegeIdVal]);
    }

    $stmt = $pdo->prepare("INSERT OR IGNORE INTO admins (username, password) VALUES (?, ?)");
    $stmt->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT)]);

    $collegeCount = $pdo->query("SELECT COUNT(*) FROM colleges")->fetchColumn();
    $courseCount = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();

    echo json_encode([
        'status' => 'success',
        'colleges' => $collegeCount,
        'courses' => $courseCount
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}