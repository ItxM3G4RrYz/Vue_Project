<?php
include 'condb.php';
header("Content-Type: application/json; charset=UTF-8");

try {
    $method = $_SERVER['REQUEST_METHOD'];

    // ✅ ดึงข้อมูลลูกค้าทั้งหมด
    if ($method === "GET") {
        $stmt = $conn->prepare("SELECT emp_id, firstname, lastname, phone, salary, timewent FROM employees ORDER BY emp_id ASC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["success" => true, "data" => $result]);
    }

    // ✅ เพิ่มข้อมูลลูกค้า
    elseif ($method === "POST") {
        // ตรวจสอบว่าข้อมูลมาจาก JSON หรือ form-data
        $contentType = $_SERVER["CONTENT_TYPE"] ?? '';

        if (stripos($contentType, "application/json") !== false) {
            $data = json_decode(file_get_contents("php://input"), true);
        } else {
            $data = $_POST;
        }

        // ตรวจสอบค่าว่าง
        if (empty($data["firstname"]) || empty($data["lastname"]) || empty($data["phone"]) || empty($data["phone"]) || empty($data["salary"]) || empty($data["password"]) ) {
            echo json_encode(["success" => false, "message" => "Please provide all required fields"]);
            exit;
        }

        // เข้ารหัสรหัสผ่าน
        $password_hash = password_hash($data["password"], PASSWORD_BCRYPT);

        // เพิ่มข้อมูลลูกค้า
        $stmt = $conn->prepare("INSERT INTO employees (firstname, lastname, phone, salary, timewent, password)
                                VALUES (:firstname, :lastname, :phone, :salary, :timewent :password)");

        $stmt->bindParam(":firstname", $data["firstname"]);
        $stmt->bindParam(":lastname", $data["lastname"]);
        $stmt->bindParam(":phone", $data["phone"]);
        $stmt->bindParam(":salary", $data["salary"]);
        $stmt->bindParam(":password", $password_hash);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Added employee successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Could not add employee"]);
        }
    }

    // ✅ แก้ไขข้อมูล
    elseif ($method === "PUT") {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["emp_id"])) {
            echo json_encode(["success" => false, "message" => "Not found employee_id"]);
            exit;
        }

        $emp_id = intval($data["emp_id"]);

        if (!empty($data["password"])) {
            $password_hash = password_hash($data["password"], PASSWORD_BCRYPT);
            $sql = "UPDATE employees 
                    SET firstName = :firstname, 
                        lastName = :lastname, 
                        phone = :phone, 
                        salary = :salary,
                        password = :password
                    WHERE emp_id = :id";
        } else {
            $sql = "UPDATE employees 
                    SET firstname = :firstname, 
                        lastname = :lastname, 
                        phone = :phone, 
                        salary = :salary
                    WHERE emp_id = :id";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":firstname", $data["firstname"]);
        $stmt->bindParam(":lastname", $data["lastname"]);
        $stmt->bindParam(":phone", $data["phone"]);
        $stmt->bindParam(":salary", $data["salary"]);
        if (!empty($data["password"])) {
            $stmt->bindParam(":password", $password_hash);
        }
        $stmt->bindParam(":id", $emp_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Edit data successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "cannot edit data"]);
        }
    }

    // ✅ ลบข้อมูล
    elseif ($method === "DELETE") {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["emp_id"])) {
            echo json_encode(["success" => false, "message" => "Not found employee_id"]);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM employees WHERE emp_id = :id");
        $stmt->bindParam(":id", $data["emp_id"], PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "deleted employee successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "cannot delete employee"]);
        }
    }

    else {
        echo json_encode(["success" => false, "message" => "Method not allowed"]);
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
