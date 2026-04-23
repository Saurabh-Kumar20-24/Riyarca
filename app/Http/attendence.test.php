<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "seomagics";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed");
}

$employeeId = $_GET['Employee_id'] ?? "";

// email
$employeeEmail = $_GET['name'] ?? "";

$date = date('Y-m-d');
date_default_timezone_set('Asia/Kolkata');
$time =  date('H:i:s');
// $action= $_GET['action']   ?? "check_in"; 

// echo $time;

$user = $_SESSION['user'];

if (!$user) {
    header("Location: https://seo-magics.com/Our_Team/Id_card.php?Employee_id=$employeeId&name=$employeeEmail");
    exit;
}

// $query = "SELECT * FROM attendence_log WHERE employee_id = ?";
// $temp = $conn->prepare($query);
// $temp->bind_param("s",$employeeId);
// $temp->execute();
// $temp2 = $temp->get_result();
// $res = $temp2->fetch_assoc();

// $action = $res['check_in'];

//setting the time checkpoints
$currentTime    = strtotime($time);
$morningCutoff  = strtotime("10:05:00"); // 10:05 AM
$lunchStart     = strtotime("13:00:00"); // 1:00 PM
$lunchEnd       = strtotime("13:30:00"); // 1:30 PM
$eveningCutoff  = strtotime("17:45:00"); // 5:45 PM

//user_id from users table
$userQuery = $conn->prepare("SELECT id FROM users WHERE employee_id = ? AND is_active = 1");
$userQuery->bind_param("s", $employeeId);
$userQuery->execute();
$userRes = $userQuery->get_result()->fetch_assoc();
$userQuery->close();

if (!$userRes) {
    $errorMsg  = urlencode("Employee not found or inactive.");
    header("Location: dashbord.php?msg=$errorMsg&type=error");
    exit;
}
$userId = $userRes['id'];

$query = "SELECT * FROM attendances WHERE employee_id = ? AND attendance_date = ?";
$temp  = $conn->prepare($query);
$temp->bind_param("ss", $employeeId, $date);
$temp->execute();
$res = $temp->get_result()->fetch_assoc();
$temp->close();

if (!$res) {
    if ($currentTime > $eveningCutoff) {
        $message     = "Attendance not accepted after 5:45 PM.";
        $messageType = "error";
    } else {

        $stmt = $conn->prepare("INSERT INTO attendances
                    (employee_id, attendance_date, check_in, status, created_at, updated_at) VALUES  (?, ?, '$time', 'pending', NOW(), NOW())");
        $stmt->bind_param("ss", $employeeId, $date);
        $stmt->execute();
        $stmt->close();

        $message     = "Check-in recorded at $time.";
        $messageType = "success";
    }
    // CHECK OUT Record exists, no check_out yet
} elseif (!empty($res['check_in']) && empty($res['check_out'])) {

    $checkInTime  = strtotime($res['check_in']);
    $totalSeconds = max(0, $currentTime - $checkInTime);

    $checkedInBeforeMorning = ($checkInTime <= $morningCutoff);
    $checkedInDuringLunch   = ($checkInTime >= $lunchStart && $checkInTime <= $lunchEnd);
    $checkedInLate          = ($checkInTime > $morningCutoff && !$checkedInDuringLunch); // after 10:05, not lunch

    $checkingOutAfterEvening = ($currentTime >= $eveningCutoff);
    $fourHours               = 4 * 3600;

    // default
    $status = "absent";

    if ($checkedInBeforeMorning) {

        if ($checkingOutAfterEvening) {
            $status = "present";                        // in before 10:05 + out after 5:45
        } elseif ($currentTime >= $lunchStart) {
            $status = "half_day";                       // in before 10:05 + out after 1PM but before 5:45
        } else {
            $status = "absent";                         // in before 10:05 + out before 1PM
        }
    } elseif ($checkedInDuringLunch) {

        if ($checkingOutAfterEvening) {
            $status = "half_day";                       // in 1:00–1:30 + out after 5:45
        } else {
            $status = "absent";                         // in 1:00–1:30 + out before 5:45
        }
    } elseif ($checkedInLate) {

        if ($totalSeconds >= $fourHours) {
            $status = "half_day";                       // late check-in + stayed 4+ hrs
        } else {
            $status = "absent";                         // late check-in + less than 4 hrs
        }
    }

    // Calculate Total Hours
    $hours      = floor($totalSeconds / 3600);
    $minutes    = floor(($totalSeconds % 3600) / 60);
    $seconds    = $totalSeconds % 60;
    $timeFormat = str_pad($hours,   2, '0', STR_PAD_LEFT) . ":" .
        str_pad($minutes, 2, '0', STR_PAD_LEFT) . ":" .
        str_pad($seconds, 2, '0', STR_PAD_LEFT);

    // Update Record
    $stmt = $conn->prepare("UPDATE attendences
    SET check_out   = ?,
        total_hours = ?,
        status      = ?,
        updated_at  = NOW()
    WHERE employee_id = ? AND attendance_date = ?
");
    $stmt->bind_param("sssss", $time, $timeFormat, $status, $employeeId, $date);
    $stmt->execute();
    $stmt->close();

    $message     = "Check-out recorded. Status: $status.";
    $messageType = "success";

    // $checkInTime             = strtotime($res['check_in']);
    // $checkedInBeforeMorning  = ($checkInTime <= $morningCutoff);
    // $checkedInDuringLunch    = ($checkInTime >= $lunchStart && $checkInTime <= $lunchEnd);
    // $checkingOutAfterEvening = ($currentTime >= $eveningCutoff);
    // $checkingOutDuringLunch  = ($currentTime >= $lunchStart && $currentTime <= $lunchEnd);

    // default fallback
    // $status = "absent";

    // Checked in before 10:05 AM
    // if ($checkedInBeforeMorning) {
    //     if ($checkingOutAfterEvening) {
    //         $status = "present";       // in before 10:05 + out after 5:45 → present
    //     } elseif ($checkingOutDuringLunch) {
    //         $status = "half_day";      // in before 10:05 + out 1:00–1:30  → half day
    //     } else {
    //         $status = "half_day";      // in before 10:05 + out before 5:45 → half day
    //     }

    //     // Checked in during lunch (1:00–1:30 PM)
    // } elseif ($checkedInDuringLunch) {
    //     if ($checkingOutAfterEvening) {
    //         $status = "half_day";      // in 1:00–1:30 + out after 5:45    → half day
    //     } else {
    //         $status = "absent";        // in 1:00–1:30 + out before 5:45   → absent
    //     }
    // }

    // // Calculate Total Hours
    // $totalSeconds = max(0, $currentTime - $checkInTime);

    // $totalSeconds = max(0, $currentTime - $checkInTime);

    // $hours   = floor($totalSeconds / 3600);
    // $minutes = floor(($totalSeconds % 3600) / 60);
    // $seconds = $totalSeconds % 60;

    // $timeFormat = str_pad($hours,   2, '0', STR_PAD_LEFT) . ":" .
    //     str_pad($minutes, 2, '0', STR_PAD_LEFT) . ":" .
    //     str_pad($seconds, 2, '0', STR_PAD_LEFT);

    // // $totalHours   = round($totalSeconds / 3600, 2);

    // // echo $timeFormat;

    // //Update Record
    // $stmt = $conn->prepare("  UPDATE attendances
    //             SET check_out   = ?,
    //                 total_hours = ?,
    //                 status      = ?,
    //                 updated_at  = NOW()
    //             WHERE employee_id = ? AND attendance_date = ?
    //         ");
    // $stmt->bind_param("sssss", $time, $timeFormat, $status, $employeeId, $date);
    // $stmt->execute();
    // $stmt->close();

    // $message     = "Check-out recorded. Status: $status.";
    // $messageType = "success";
    //Already Completed 
} else {
    $message     = "Attendance already completed for today.";
    $messageType = "info";
}

if (isset($message) && isset($messageType)) {
    $message1 = urlencode($message);
    $messageType1 = urlencode($messageType);

    header("Location: dashbord.php?msg=$message1&type=$messageType1");
    exit;
} else {
    $errorMsg = urlencode("An unexpected error occurred. Please try again.");
    $errorType = "error";

    header("Location: dashbord.php?msg=$errorMsg&type=$errorType");
    exit;
}

// <10.5 to 5.45 present
// <10  <5.45 half time
//after 1-1.30 to 5.45 half time
//10 to 1 half time
//after 1 < 5.45  absent
//if not check out after 5.45 absent
