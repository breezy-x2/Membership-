<?php

include "db_connect.php";

$student_id   = "";
$member       = null;
$errorMessage = "";

if (isset($_POST['student_id'])) {

    $student_id = trim($_POST['student_id']);

    if ($student_id === "") {

        $errorMessage = "Please enter a Student ID.";

    } else {

        // Step 1: does this student_id exist in the membership table at all?
        $sql = "SELECT * FROM membership WHERE student_id = :student_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':student_id' => $student_id]);
        $member = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$member) {

            // No row at all for this student_id
            $errorMessage = "Student not found.";
            $member = null;

        } elseif ($member['status'] !== 'Active') {

            // Row exists, but membership isn't active
            $errorMessage = "Membership not active.";

        }
        // else: $member is set and status is Active -> redirect below
    }

} else {
    $errorMessage = "No Student ID submitted.";
}

// If we have an active member, redirect BEFORE any HTML is sent
if ($member && $member['status'] === 'Active') {
    header("Location: viewmembership.php?student_id=" . urlencode($member['student_id']));
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Membership</title>
</head>
<body>

<?php if ($errorMessage): ?>
    <p style="color:red;"><?php echo htmlspecialchars($errorMessage); ?></p>
<?php endif; ?>

</body>
</html>