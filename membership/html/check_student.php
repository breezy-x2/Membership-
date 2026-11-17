<?php

require "db_connect.php";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_number = $_POST['student_number'] ?? "";

    if ($student_number == "") {
        echo json_encode(["found" => false, "message" => "No student number provided."]);
        exit;
    }

    $sql = "SELECT full_name, course, year_level, email 
            FROM students 
            WHERE student_number = :student_number";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':student_number', $student_number);
    $stmt->execute();

    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        echo json_encode([
            "found" => true,
            "full_name" => $student['full_name'],
            "course" => $student['course'],
            "year_level" => $student['year_level'],
            "email" => $student['email']
        ]);
    } else {
        echo json_encode(["found" => false, "message" => "Student not found."]);
    }

} else {
    echo json_encode(["found" => false, "message" => "Invalid request method."]);
}
?>