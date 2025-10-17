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
            $stmt = $conn->prepare("INSERT INTO employees (emp_id, firstname, lastname, phone, salary, timewent) VALUES (:emp_id, :firstname, :lastname, :phone, :salary, :timewent)");
            $stmt->bindParam(':emp_id', $data['emp_id']);
            $stmt->bindParam(':firstname', $data['firstname']);
            $stmt->bindParam(':lastName', $data['lastname']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':salary', $data['salary']);
            $stmt->bindParam(':timewent', $data['timewent']);

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