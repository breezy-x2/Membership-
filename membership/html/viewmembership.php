<?php

include "db_connect.php";

$student_id   = "";
$member       = null;
$errorMessage = "";

if (isset($_GET['student_id'])) {

    $student_id = trim($_GET['student_id']);

    if ($student_id === "") {

        $errorMessage = "No Student ID provided.";

    } else {

        $sql = "SELECT * FROM membership WHERE student_id = :student_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':student_id' => $student_id]);
        $member = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$member) {
            $errorMessage = "Student not found.";
            $member = null;
        }
    }

} else {
    $errorMessage = "No Student ID provided.";
}

// Work out days remaining and a friendly expiry date, if we have a member
$daysRemaining   = null;
$expiryFormatted = null;
$isActive        = false;

if ($member) {
    $isActive = ($member['status'] === 'Active');

    if (!empty($member['expiry_date'])) {
        $expiryDate      = new DateTime($member['expiry_date']);
        $today           = new DateTime('today');
        $diff            = $today->diff($expiryDate);
        $daysRemaining   = ($expiryDate >= $today) ? $diff->days : 0;
        $expiryFormatted = $expiryDate->format('d F Y');
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view membership</title>
    <link rel="stylesheet" href="../css/view.css">
</head>

<body>
    <nav>
        <ul>
            <li><a href="Membership.html">Home</a></li>
            <li><a href="#" style="color:red;">Logout</a></li>
        </ul>
    </nav>

<?php if ($member): ?>

    <div class="membership-card">
        <div class="card-top">
            <span> Greenview Library</span>
            <span class="plan-badge"><?php echo htmlspecialchars(strtoupper($member['plan'])); ?></span>
        </div>
        <div class="name"><?php echo htmlspecialchars($member['full_name']); ?></div>
        <div class="student-id"><?php echo htmlspecialchars($member['student_id']); ?></div>
        <div class="card-bottom">
            <span><?php echo htmlspecialchars($member['course']); ?></span>
            <span>Expires: <?php echo $expiryFormatted ? date('M Y', strtotime($member['expiry_date'])) : 'N/A'; ?></span>
        </div>
    </div>

    <div class="status-list">
        <div class="status-row">
            <span class="label">Membership Status</span>
            <?php if ($isActive): ?>
                <span class="badge-green">✓ Active</span>
            <?php else: ?>
                <span class="badge-red"><?php echo htmlspecialchars($member['status']); ?></span>
            <?php endif; ?>
        </div>
        <div class="status-row">
            <span class="label">Expiry Date</span>
            <span class="value"><?php echo $expiryFormatted ?? 'N/A'; ?></span>
        </div>
        <div class="status-row">
            <span class="label">Days Remaining</span>
            <span class="value"><?php echo $daysRemaining !== null ? $daysRemaining . ' days' : 'N/A'; ?></span>
        </div>
        <div class="status-row">
            <span class="label">Current Plan</span>
            <span class="value"><?php echo htmlspecialchars(ucfirst($member['plan'])); ?></span>
        </div>
        <div class="status-row">
            <span class="label">Outstanding Fines</span>
            <span class="badge-green">R0.00</span>
        </div>
    </div>

    <div class="card">
        <div class="card-title"> Membership Summary</div>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="number">3</div>
                <div class="desc">Books currently borrowed</div>
            </div>
            <div class="stat-box">
                <div class="number">14</div>
                <div class="desc">Total books this year</div>
            </div>
            <div class="stat-box">
                <div class="number">R0</div>
                <div class="desc">Outstanding fines</div>
            </div>
        </div>
    </div>

    <div class="card">
        <h3>Currently Borrowed Books</h3>

        <p>
            <strong>Principles of Economics</strong><br>
            N. Gregory Mankiw<br>
            Due: 30 May 2025<br>
            Due Soon
        </p>

        <p>
            <strong>Business Law Today</strong><br>
            Roger Miller<br>
            Due: 07 June 2025<br>
            ✓ On Time
        </p>

        <p>
            <strong>Introduction to Algorithms</strong><br>
            Cormen, Leiserson et al.<br>
            Due: 12 June 2025<br>
            ✓ On Time
        </p>
    </div>

    <div style="display:flex; justify-content:flex-end;">
        <a href="registration.html?renew=true" class="renew-btn">Renew Membership</a>
    </div>

<?php else: ?>

    <div class="card">
        <p style="color:red;"><?php echo htmlspecialchars($errorMessage); ?></p>
    </div>

<?php endif; ?>

    <footer>
        © 2026 Greenview System · All rights reserved
    </footer>

</body>
</html>