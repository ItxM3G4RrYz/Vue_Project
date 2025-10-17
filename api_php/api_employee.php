<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// เชื่อมต่อฐานข้อมูล
include 'condb.php';

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === "GET") {
        // ✅ ดึงข้อมูลลูกค้าทั้งหมด
        $stmt = $conn->prepare("SELECT emp_id, firstname, lastname, phone, salary, timewent FROM employees ORDER BY emp_id ASC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(["success" => true, "data" => $result]);
        exit;
    }

    elseif ($method === "POST") {
        // ✅ เพิ่มพนักงานใหม่
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            echo json_encode(["success" => false, "message" => "Invalid JSON body"]);
            exit;
        }

        // Validate required fields (adjust names to match frontend: firstname, lastname, phone, salary)
        if (empty($data['firstname']) || empty($data['lastname']) || empty($data['phone']) || !isset($data['salary'])) {
            echo json_encode(["success" => false, "message" => "Please provide firstname, lastname, phone and salary"]);
            exit;
        }

        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $phone = $data['phone'];
        $salary = $data['salary'];
        $timewent = isset($data['timewent']) ? $data['timewent'] : date('Y-m-d H:i:s');

        $stmt = $conn->prepare("INSERT INTO employees (firstname, lastname, phone, salary, timewent) VALUES (:firstname, :lastname, :phone, :salary, :timewent)");
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':salary', $salary);
        $stmt->bindParam(':timewent', $timewent);

        if ($stmt->execute()) {
            $id = $conn->lastInsertId();
            echo json_encode(["success" => true, "message" => "Employee added", "emp_id" => $id]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to add employee"]);
        }
        exit;
    }

    elseif ($method === "PUT") {
        // ✅ อัปเดตข้อมูลพนักงาน
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !isset($data['emp_id'])) {
            echo json_encode(["success" => false, "message" => "emp_id is required"]);
            exit;
        }

        $emp_id = intval($data['emp_id']);
        // Accept updating these fields (adjust as needed)
        $firstname = isset($data['firstname']) ? $data['firstname'] : null;
        $lastname = isset($data['lastname']) ? $data['lastname'] : null;
        $phone = isset($data['phone']) ? $data['phone'] : null;
        $salary = isset($data['salary']) ? $data['salary'] : null;

        // Build dynamic SET clause
        $fields = [];
        $params = [':id' => $emp_id];
        if ($firstname !== null) { $fields[] = "firstname = :firstname"; $params[':firstname'] = $firstname; }
        if ($lastname !== null)  { $fields[] = "lastname = :lastname";   $params[':lastname']  = $lastname;  }
        if ($phone !== null)     { $fields[] = "phone = :phone";         $params[':phone']     = $phone;     }
        if ($salary !== null)    { $fields[] = "salary = :salary";       $params[':salary']    = $salary;    }

        if (empty($fields)) {
            echo json_encode(["success" => false, "message" => "No fields to update"]);
            exit;
        }

        $sql = "UPDATE employees SET " . implode(", ", $fields) . " WHERE emp_id = :id";
        $stmt = $conn->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Employee updated"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update employee"]);
        }
        exit;
    }

    elseif ($method === "DELETE") {
        // ✅ ลบข้อมูลลูกค้าตาม 
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["emp_id"])) {
            echo json_encode(["success" => false, "message" => "can't find employee_id"]);
            exit;
        }

        $emp_id = intval($data["emp_id"]);

        $stmt = $conn->prepare("DELETE FROM employees WHERE emp_id = :id");
        $stmt->bindParam(":id", $emp_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Delete Successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "can't delete the data"]);
        }
        exit;
    }

    else {
        echo json_encode(["success" => false, "message" => "Method not allowed"]);
        exit;
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>