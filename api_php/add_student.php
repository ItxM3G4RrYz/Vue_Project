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

        if (isset($data['first_name'], $data['last_name'],$data['email'], $data['phone'])) {
            $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, email, phone) VALUES (:firstName, :lastName, :email, :phone)");
            $stmt->bindParam(':firstName', $data['first_name']);
            $stmt->bindParam(':lastName', $data['last_name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':phone', $data['phone']);

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