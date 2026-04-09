
Route::get('/nfc-tap', [AttendanceController::class, 'nfcTap']);

<?php

session_start();

$_SESSION['employeeId'] = "RTS251224";
// $_SESSION['Employee_name'] = "";
$_SESSION['employeeEmail'] = "manish.shunkaria@seo-magics.com";

$host = "localhost";
$username = "root";
$password ="";
$database = "seomagics";

$conn = new mysqli($host, $username, $password, $database);

if($conn->connect_error){
    die('conn failed');
}
//employee id , name, email
// $employeeId=$_GET['Employee_id'];
// $employeeName = $_GET['Employee_name'];
// $employeeEmail = $_GET['email'];

$user = $_SESSION['user'];
if(!$user){
    header("https://seo-magics.com/Our_Team/Id_card.php?Employee_id=$employeeId&name=$employeeEmail");
}

// public function nfcTap()
// {
//     $employeeId = session('employee_id');

//     if (!$employeeId) {
//         return response()->json([
//             'message' => 'Employee not found in session'
//         ]);
//     }

//     $today = today();

//     $attendance = AttendanceLog::where('employee_id', $employeeId)
//                     ->whereDate('date', $today)
//                     ->first();

//     $leave = Leave::where('employee_id', $employeeId)
//                 ->whereDate('from_date', '<=', $today)
//                 ->whereDate('to_date', '>=', $today)
//                 ->first();

//     if ($leave) {
//         return response()->json([
//             'message' => 'Employee is on leave'
//         ]);
//     }
    
//     if (!$attendance) {

//         $halfDayTime = Carbon::createFromTime(10, 30);

//         $status = now()->gt($halfDayTime)  ? 'half_day'  : 'checked_in';

//         Attendance::create([
//             'employee_id' => $employeeId,
//             'date' => $today,
//             'checked_in' => now(),
//             'status' => 'checked_in'
//         ]);

//         return response()->json([
//             'message' => 'Check-in successful'
//         ]);
//     }

//     if (!$attendance->checked_out) {

//         $attendance->update([
//             'checked_out' => now(),
//             'status' => 'checked_out'
//         ]);

//         return response()->json([
//             'message' => 'Check-out successful'
//         ]);
//     }

//     return response()->json([
//         'message' => 'Already completed'
//     ]);
// }

?>