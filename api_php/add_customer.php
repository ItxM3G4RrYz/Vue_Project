<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

include 'condb.php';

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['firstName'], $data['lastName'], $data['phone'], $data['username'], $data['password'])) {
            $stmt = $conn->prepare("INSERT INTO customers (firstName, lastName, phone, username, password) VALUES (:firstName, :lastName, :phone, :username, :password)");
            $stmt->bindParam(':firstName', $data['firstName']);
            $stmt->bindParam(':lastName', $data['lastName']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':username', $data['username']);
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $hashedPassword);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Sign in successful"]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to sign in"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Please provide all required fields"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Method not allowed"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>