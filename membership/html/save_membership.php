<?php

require "db_connect.php";

// Only for debugging (REMOVE later if you want clean output)
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id = $_POST['student_id'] ?? "";
    $full_name  = $_POST['full_name'] ?? "";
    $course     = $_POST['course'] ?? "";
    $year_level = $_POST['year_level'] ?? "";
    $email      = $_POST['email'] ?? "";
    $plan       = $_POST['plan'] ?? "";
    $status     = "Active";

    if ($student_id == "" || $plan == "") {
        die("Missing required fields.");
    }

    $sql = "INSERT INTO membership
    (student_id, full_name, course, year_level, email, plan, status)
    VALUES 
    (:student_id, :full_name, :course, :year_level, :email, :plan, :status)";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':course', $course);
    $stmt->bindParam(':year_level', $year_level);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':plan', $plan);
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()) {
        echo "Membership saved successfully!";
    } else {
        echo "Error saving membership.";
    }

} else {
    echo "Invalid request method.";
}
?>
