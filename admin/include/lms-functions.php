<?php
$cashAccountId = 1; // Cash
$salesRevenueAccountId = 15; //Sales/Revenue
$accountsReceivableAccountId = 3; // AccountReceivable
$accountsPayableAccountId = 2; // Account Payable
$inventoryAccountId = 4; // Inventory Account
$costOfGoodsAccountId = 18; // COGS


include __DIR__ . '/config.php';
// include __DIR__ . '/sms-API.php';
// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


function getLmsBatches()
{

    global $lms_link;
    $ArrayResult = array();

    $sql = "SELECT `id`, `course_name`, `course_code`, `instructor_id`, `course_description`, `course_duration`, `course_fee`, `registration_fee`, `other`, `created_at`, `created_by`, `update_by`, `update_at`, `enroll_key`, `display`, `CertificateImagePath`, `course_img`, `certification`, `mini_description` FROM `course` ORDER BY `id` DESC";

    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['course_code']] = $row;
        }
    }

    return $ArrayResult;
}

function GetDefaultCourseValue($sessionUser)
{
    global $lms_link;
    $EnrolledCourseCode = "";
    // Get Default Course
    $sql = "SELECT `id`, `index_number`, `title`, `value` FROM `default_values` WHERE `index_number` LIKE '$sessionUser' AND `title` LIKE 'Course'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $title = $row["title"];
            $EnrolledCourseCode = $row["value"];
        }
    }

    return  $EnrolledCourseCode;
}


function GetLmsStudents()
{
    $ArrayResult = array();
    global $lms_link;

    $sql = "SELECT * FROM `user_full_details` ORDER BY `id` DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['username']] = $row;
        }
    }
    return $ArrayResult;
}


function GetLmsStudentsByUserId()
{
    $ArrayResult = array();
    global $lms_link;

    $sql = "SELECT * FROM `user_full_details` ORDER BY `id` DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['student_id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetLmsStudentsByUserName($userName)
{
    $ArrayResult = array();
    global $lms_link;

    $sql = "SELECT `id`, `student_id`, `username`, `civil_status`, `first_name`, `last_name`, `gender`, `address_line_1`, `address_line_2`, `city`, `district`, `postal_code`, `telephone_1`, `telephone_2`, `nic`, `e_mail`, `birth_day`, `updated_by`, `updated_at`, `full_name`, `name_with_initials`, `name_on_certificate` FROM `user_full_details` WHERE `username` LIKE '$userName'  ORDER BY `id` DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['username']] = $row;
        }
    }
    return $ArrayResult[$userName];
}

function getAllUserEnrollments()
{
    $ArrayResult = array();
    global $lms_link;
    $sql = "SELECT `id`, `course_code`, `student_id`, `enrollment_key`, `created_at` FROM `student_course` ORDER BY `id` DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}


function GetLmsSpecialAccounts()
{
    $ArrayResult = array();
    global $lms_link;

    $sql = "SELECT `id`, `status_id`, `userid`, `fname`, `lname`, `batch_id`, `username`, `phone`, `email`, `password`, `userlevel`, `status`, `created_by`, `created_at`, `batch_lock` FROM `users` WHERE `userlevel` LIKE 'sp-winpharma' OR `userlevel` LIKE 'Admin'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['username']] = $row;
        }
    }
    return $ArrayResult;
}


function GetLmsAccounts()
{
    $ArrayResult = array();
    global $lms_link;

    $sql = "SELECT `id`, `status_id`, `userid`, `fname`, `lname`, `batch_id`, `username`, `phone`, `email`, `password`, `userlevel`, `status`, `created_by`, `created_at`, `batch_lock` FROM `users`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['username']] = $row;
        }
    }
    return $ArrayResult;
}


function getAllUserEnrollmentsByCourse($courseCode)
{
    $ArrayResult = array();
    global $lms_link;
    $sql = "SELECT `id`, `course_code`, `student_id`, `enrollment_key`, `created_at` FROM `student_course` WHERE `course_code` LIKE '$courseCode' ORDER BY `id` DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}

function CertificatePrintStatus()
{

    $ArrayResult = array();
    global $lms_link;

    $sql = "SELECT `id`, `student_number`, `certificate_id`, `print_date`, `print_status`, `print_by`, `type`, `course_code` FROM `user_certificate_print_status`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['certificate_id']] = $row;
        }
    }
    return $ArrayResult;
}

function CertificatePrintStatusByCourse($courseCode, $type)
{

    $ArrayResult = array();
    global $lms_link;

    $sql = "SELECT `id`, `student_number`, `certificate_id`, `print_date`, `print_status`, `print_by`, `type`, `course_code` FROM `user_certificate_print_status` WHERE `course_code` LIKE '$courseCode' AND `type` LIKE '$type'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['student_number']] = $row;
        }
    }
    return $ArrayResult;
}

function CertificatePrintStatusByCourseStudent($courseCode, $studentNumber)
{

    $ArrayResult = array();
    global $lms_link;

    $sql = "SELECT `id`, `student_number`, `certificate_id`, `print_date`, `print_status`, `print_by`, `type`, `course_code` FROM `user_certificate_print_status` WHERE `course_code` LIKE '$courseCode' AND `student_number` LIKE '$studentNumber'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['type']] = $row;
        }
    }
    return  $ArrayResult;
}


function GetStudentEnrollCounts()
{
    $ArrayResult = array();
    global $lms_link;
    $sql = "SELECT COUNT(`id`) AS `studentCount`, `course_code`, `student_id`, `enrollment_key`, `created_at` FROM `student_course` GROUP BY `course_code` ORDER BY `id` DESC ";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['course_code']] = $row['studentCount'];
        }
    }
    return $ArrayResult;
}

function getUserEnrollments($userName)
{
    $ArrayResult = array();
    global $lms_link;
    $studentId = GetLmsStudentsByUserName($userName)['student_id'];
    $sql = "SELECT `id`, `course_code`, `student_id`, `enrollment_key`, `created_at` FROM `student_course` WHERE `student_id` LIKE '$studentId' ORDER BY `id` DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}

function getUserEnrollmentsBuStudentId($studentId)
{
    $ArrayResult = array();
    global $lms_link;
    $sql = "SELECT `id`, `course_code`, `student_id`, `enrollment_key`, `created_at` FROM `student_course` WHERE `student_id` LIKE '$studentId' ORDER BY `id` DESC";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[] = $row;
        }
    }
    return $ArrayResult;
}

function GetOrders()
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `delivery_orders` ORDER BY `id` DESC";

    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function GetDeliverySetting()
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `delivery_setting`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }

    return $ArrayResult;
}

function GetProductLinkStatus($refId)
{
    global $link;

    $ArrayResult = array();
    $sql = "SELECT `id`, `product_code`, `reference_id`, `created_by`, `created_at`, `is_active` FROM `addon_product_link` WHERE `reference_id` LIKE '$refId' AND `is_active` LIKE 1";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['reference_id']] = $row;
        }
    }

    return $ArrayResult;
}

function SaveProductErpLink($productCode, $refId, $createdBy, $isActive)
{
    global $link;
    // Initialize the error array
    $error = array();
    $current_time = date("Y-m-d H:i:s");
    $sql = "INSERT INTO `addon_product_link` (`product_code`, `reference_id`, `created_by`, `created_at`, `is_active`) VALUE (?, ?, ?, ?, ?)"; // Prepare the SQL statement
    if ($stmt_sql = mysqli_prepare($link, $sql)) {
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt_sql, "sssss", $productCode, $refId, $createdBy, $current_time, $isActive);

        // Execute the statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt_sql);
            $error = array('status' => 'success', 'message' => 'Product Link updated successfully', 'affected_rows' => $affected_rows);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($link));
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($link));
    }

    // Return the error as a JSON-encoded string
    return json_encode($error);
}

function UpdateOrderStatus($refId, $trackingNumber, $orderStatus, $codAmount, $packageWeight)
{
    global $lms_link;

    $current_time = date("Y-m-d H:i:s");
    // Initialize the error array
    $error = array();

    if ($orderStatus == 1) {
        $columnName = "order_date";
    } else if ($orderStatus == 2) {
        $columnName = "packed_date";
    } else if ($orderStatus == 3) {
        $columnName = "send_date";
    } else if ($orderStatus == 4) {
        $columnName = 'removed_date';
    }



    // If the stock entry doesn't exist, insert a new one
    $sql = "UPDATE `delivery_orders` SET `tracking_number` = ?, `current_status` = ?, `$columnName` = ?, `cod_amount` = ?, `package_weight` = ? WHERE `id` = ?";

    // Prepare the SQL statement
    if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt_sql, "ssssss", $trackingNumber, $orderStatus, $current_time, $codAmount, $packageWeight, $refId);

        // Execute the statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt_sql);
            $error = array('status' => 'success', 'message' => 'Order status updated successfully', 'affected_rows' => $affected_rows);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link));
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link));
    }

    // Return the error as a JSON-encoded string
    return json_encode($error);
}

function getLmsBatchByCourse($courseCode)
{

    global $lms_link;
    $ArrayResult = array();

    $sql = "SELECT `id`, `course_name`, `course_code`, `instructor_id`, `course_description`, `course_duration`, `course_fee`, `registration_fee`, `other`, `created_at`, `created_by`, `update_by`, `update_at`, `enroll_key`, `display`, `CertificateImagePath`, `course_img`, `certification`, `mini_description` FROM `course` WHERE `course_code` LIKE '$courseCode' ORDER BY `id` DESC";

    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['course_code']] = $row;
        }
    }

    return $ArrayResult[$courseCode];
}

function getStudentPaymentDetails($userName)
{

    global $lms_link;
    $ArrayResult = array();

    $studentId = GetLmsStudentsByUserName($userName)['student_id'];
    $sql = "SELECT `receipt_number`, `payment_status`, `paid_amount`, `discount_amount` FROM `student_payment` WHERE `student_id` LIKE '$studentId'";

    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['receipt_number']] = $row;
        }
    }

    return $ArrayResult;
}

function GetStudentBalance($userName)
{
    $totalPaymentAmount = $TotalStudentPaymentRecords = $studentBalance = $TotalRegistrationFee = 0;
    $paymentRecords = getStudentPaymentDetails($userName);
    $studentEnrollments = getUserEnrollments($userName);
    $courseList = getLmsBatches();

    if (!empty($studentEnrollments)) {
        foreach ($studentEnrollments as $selectedArray) {
            $totalCourseFee = 0;
            $courseDetails = $courseList[$selectedArray['course_code']];
            $totalCourseFee = $courseDetails['course_fee'] + $courseDetails['registration_fee'];
            $TotalRegistrationFee += $courseDetails['registration_fee'];
            $totalPaymentAmount += $totalCourseFee;
        }
    }

    if (!empty($paymentRecords)) {
        foreach ($paymentRecords as $selectedArray) {
            $paymentRecord = 0;
            $paymentRecord = ($selectedArray['paid_amount'] + $selectedArray['discount_amount']);
            $TotalStudentPaymentRecords += $paymentRecord;
        }
    }

    $studentBalance = $totalPaymentAmount - $TotalStudentPaymentRecords;

    // Construct Result Array
    $resultArray = array(
        'totalPaymentAmount' => $totalPaymentAmount,
        'TotalStudentPaymentRecords' => $TotalStudentPaymentRecords,
        'studentBalance' => $studentBalance,
        'TotalRegistrationFee' => $TotalRegistrationFee
    );
    return $resultArray;
}

function GetMainCourseList()
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `parent_main_course` ORDER BY `id` DESC";

    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['course_code']] = $row;
        }
    }
    return $ArrayResult;
}


function GetCourseModules()
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT `id`, `module_code`, `credit`, `module_name`, `duration`, `level`, `course_code`, `is_active` FROM `course_modules`";

    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['module_code']] = $row;
        }
    }
    return $ArrayResult;
}


function GetTemporaryUsers()
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT `id`, `email_address`, `civil_status`, `first_name`, `last_name`, `password`, `nic_number`, `phone_number`, `whatsapp_number`, `address_l1`, `address_l2`, `city`, `district`, `postal_code`, `paid_amount`, `aprroved_status`, `created_at`, `full_name`, `name_with_initials`, `gender`, `index_number`, `name_on_certificate`,`selected_course` FROM `temp_lms_user` ORDER BY `id` DESC";

    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }
    return $ArrayResult;
}

function UpdateTempUserStatus($refId, $updatedStatus, $index_number)
{
    global $lms_link;

    $current_time = date("Y-m-d H:i:s");
    // Initialize the error array
    $error = array();

    // If the stock entry doesn't exist, insert a new one
    $sql = "UPDATE `temp_lms_user` SET `aprroved_status` = ?, `index_number` = ? WHERE `id` = ?";

    // Prepare the SQL statement
    if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt_sql, "sss", $updatedStatus, $index_number, $refId);

        // Execute the statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt_sql);
            if ($updatedStatus == "Rejected") {
                $index_number = "Not Set";
            }
            $error = array('status' => 'success', 'message' => 'Temporary Account updated successfully', 'affected_rows' => $affected_rows, 'username' => $index_number);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link), 'username' => "Not Set");
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link), 'username' => "Not Set");
    }

    // Return the error as a JSON-encoded string
    return json_encode($error);
}

function formatPhoneNumber($phoneNumber)
{
    $length = strlen($phoneNumber);

    // Check if the number has 9 digits
    if ($length === 9) {
        $formattedNumber = "(+94) " . substr($phoneNumber, 0, 2) . ' ' . substr($phoneNumber, 2, 3) . ' ' . substr($phoneNumber, 5, 4);
    }
    // Check if the number starts with 0 and has 10 digits
    elseif ($length === 10 && substr($phoneNumber, 0, 1) === '0') {
        $formattedNumber = "(+94) " . substr($phoneNumber, 1, 2) . ' ' . substr($phoneNumber, 3, 3) . ' ' . substr($phoneNumber, 6, 4);
    }
    // If the number doesn't match any of the conditions, use the original number
    else {
        $formattedNumber = $phoneNumber;
    }

    return $formattedNumber;
}


function generateWhatsAppLink($phoneNumber, $message = null)
{

    // Remove any non-numeric characters from the phone number
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

    // Add the country code if it's not present
    if (strlen($phoneNumber) == 9) {
        $phoneNumber = '94' . $phoneNumber;
    }

    // Construct the WhatsApp link
    $whatsAppLink = 'https://wa.me/' . $phoneNumber;

    // Add message parameter if provided
    if ($message !== null) {
        $whatsAppLink .= '?text=' . urlencode($message);
    }

    return $whatsAppLink;
}


function GenerateLmsIndexNumber($batchCode)
{

    $batchCode = str_pad($batchCode, 2, '0', STR_PAD_LEFT);

    global $lms_link;

    $sql = "SELECT COUNT(*) AS `userCount` FROM `users` WHERE `username` LIKE 'PA$batchCode%'";
    $result = $lms_link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $userCount = $row["userCount"];
        }
    }

    $newUserId = str_pad($userCount + 1, 3, '0', STR_PAD_LEFT);

    //Create User ID & User Name
    $userName = "PA" . $batchCode . $newUserId;
    $userId = "PA/" . $batchCode . "/" . $newUserId;

    // Check Availability
    $sql = "SELECT * FROM `users` WHERE `username` LIKE '$userName'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        $sql = "SELECT COUNT(*) AS `userCount` FROM `users`";
        $result = $lms_link->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $userCount = $row["userCount"];
            }
        }

        $newUserId = str_pad($userCount + 1, 3, '0', STR_PAD_LEFT);

        //Create User ID & User Name
        $userName = "PA" . $batchCode . $newUserId;
        $userId = "PA/" . $batchCode . "/" . $newUserId;
    }

    return array('userName' => $userName, 'userId' => $userId);
}


function CreateNewLmsUser($user_id, $first_name, $last_name, $user_name, $password, $user_level, $logged_user, $account_status, $civil_status, $phone_number, $email_address, $batchCode)
{
    global $lms_link;
    $tempPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);

    $error = array('status' => 'initial', 'message' => '');

    $sql = "INSERT INTO users (`userid`, `fname`, `lname`, `username`, `password`, `userlevel`, `created_by`, `status`, `status_id`, `phone`, `email`, `batch_id`, `temp_password`) VALUES (?, ? , ?, ? , ? ,? , ?, ?, ?, ?, ?, ?, ?)";


    if ($stmt = mysqli_prepare($lms_link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssssssssss", $param_id, $param_fname, $param_lname, $param_username, $param_password, $param_userlevel, $param_session, $param_status, $param_civil_status, $param_phone, $param_email, $param_batch, $tempPassword);
        // Set parameters
        $param_id = $user_id;
        $param_fname = $first_name;
        $param_lname = $last_name;
        $param_username = $user_name;
        $param_password = $password;
        $param_userlevel = $user_level;
        $param_session = $logged_user;
        $param_status = $account_status;
        $param_civil_status = $civil_status;
        $param_phone = $phone_number;
        $param_email = $email_address;
        $param_batch = $batchCode;
        $tempPassword = $tempPassword;

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            $error = array('status' => 'success', 'message' => 'Account Created successfully', 'affected_rows' => $affected_rows, 'tempPassword' => $tempPassword);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link));
        }
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link));
    }

    // Return the error as a JSON-encoded string
    return json_encode($error);
}

function CreateNewLmsUserFullDetails($updateKey, $user_id, $first_name, $last_name, $gender, $user_name, $password, $user_level, $logged_user, $account_status, $civil_status, $phone_number, $email_address, $batchCode, $address_l1, $address_l2, $city, $whatsapp_number, $nic_number, $district, $postalCode, $full_name, $name_with_initials, $name_on_certificate, $birth_day, $update_by)
{
    global $lms_link;
    $error = array('status' => 'initial', 'message' => '', 'username' => "Not Set");

    if ($updateKey == 0) {
        $sql = "INSERT INTO `user_full_details`(`student_id`, `civil_status`, `first_name`, `last_name`, `gender`, `telephone_1`, `e_mail`,`username`, `address_line_1`, `address_line_2`, `city`, `telephone_2`, `nic`, `district`, `postal_code`, `full_name`, `name_with_initials`, `name_on_certificate`, `birth_day`, `updated_by`) VALUES (?, ? , ?, ? , ? ,? , ?, ?, ? , ?, ? , ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } else {
        $sql = "UPDATE `user_full_details` SET `student_id` = ?, `civil_status` = ?, `first_name` = ?, `last_name` = ?, `gender`=?, `telephone_1` = ?, `e_mail` = ?,`username` = ?, `address_line_1` = ?, `address_line_2` = ?, `city` = ?, `telephone_2` = ?, `nic` = ?, `district` = ?, `postal_code`= ? `full_name` = ?, `name_with_initials` = ?, `name_on_certificate` = ?, `birth_day`= ?, `updated_by`= ? WHERE `username` LIKE '$updateKey'";
    }

    if ($stmt = mysqli_prepare($lms_link, $sql)) {

        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssssssssssssssssss", $user_id, $civil_status, $first_name, $last_name, $gender, $phone_number, $email_address, $user_name, $address_l1,  $address_l2,  $city, $whatsapp_number, $nic_number, $district, $postalCode, $full_name, $name_with_initials, $name_on_certificate, $birth_day, $update_by);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            $error = array('status' => 'success', 'message' => 'Full Details Updated successfully', 'affected_rows' => $affected_rows, 'username' => $user_name);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link), 'username' => "Not Set");
        }
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link), 'username' => "Not Set");
    }

    // Return the error as a JSON-encoded string
    return json_encode($error);
}


function StudentEnrollment($courseCode, $studentId, $enrollKey)
{
    global $lms_link;
    $error = array('status' => 'initial', 'message' => '');

    //SQL
    $sql = "INSERT INTO `student_course`(`course_code`, `student_id`,`enrollment_key`) VALUES  (?, ?, ?)";

    if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "sss", $courseCode, $studentId, $enrollKey);

        // Execute the statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt_sql);
            $error = array('status' => 'success', 'message' => 'Student Enrolled successfully');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link));
        }
        // Close statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link));
    }

    // Return the error as a JSON-encoded string
    return json_encode($error);
}


function UpdateTempUserDetails($Email, $status_id, $fname, $lname, $password, $NicNumber, $phoneNumber, $whatsAppNumber, $addressL1, $addressL2, $city, $District, $postalCode, $paid_amount, $full_name, $name_with_initials, $name_on_certificate, $gender, $selectedCourse, $refId)
{
    global $lms_link;
    date_default_timezone_set("Asia/Colombo");
    $current_time = date("Y-m-d H:i:s");
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE `temp_lms_user` SET `email_address`=?, `civil_status`=?, `first_name`=?, `last_name`=?, `password`=?, `nic_number`=?, `phone_number`=?, `whatsapp_number`=?, `address_l1`=?, `address_l2`=?, `city`=?, `district`=?, `postal_code`=?, `paid_amount`=?, `created_at`=?, `full_name`=?, `name_with_initials`=?, `name_on_certificate`=?, `gender`=?, `selected_course`=? WHERE `id`= '$refId'";
    if ($stmt_sql = mysqli_prepare($lms_link, $sql)) {

        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_sql, "ssssssssssssssssssss", $Email, $status_id, $fname, $lname, $hashPassword, $NicNumber, $phoneNumber, $whatsAppNumber, $addressL1, $addressL2, $city, $District, $postalCode, $paid_amount, $current_time, $full_name, $name_with_initials, $name_on_certificate, $gender, $selectedCourse);

        // Execute the statement
        if (mysqli_stmt_execute($stmt_sql)) {
            $last_inserted_id = mysqli_insert_id($lms_link);
            $affected_rows = mysqli_stmt_affected_rows($stmt_sql);

            $error = array('status' => 'success', 'message' => 'Temporary Account updated successfully', 'affected_rows' => $affected_rows, 'last_inserted_id' => $last_inserted_id, 'username' => 'Not Set');
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link), 'username' => "Not Set", 'last_inserted_id' => "Not Set");
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt_sql);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link), 'username' => "Not Set", 'last_inserted_id' => "Not Set");
    }

    // Return the error as a JSON-encoded string
    return json_encode($error);
}


function GetCertificatePrintStatus()
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `user_certificate_print_status` WHERE `type` LIKE 'Certificate'";

    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $certificateId = $row['certificate_id'];
            $userId = $row['student_number'];
            $ArrayResult[$certificateId . '-' . $userId] = $row;
        }
    }
    return $ArrayResult;
}


function GetTranscriptPrintStatus()
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `user_certificate_print_status` WHERE `type` LIKE 'Transcript'";

    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $certificateId = $row['certificate_id'];
            $userId = $row['student_number'];
            $ArrayResult[$certificateId . '-' . $userId] = $row;
        }
    }
    return $ArrayResult;
}


function GenerateLMSReceiptNumber()
{

    $recPrefix = 'CPCREC';
    global $lms_link;

    $sql = "SELECT COUNT(receipt_number) FROM student_payment;";
    $result = $lms_link->query($sql);
    while ($row = $result->fetch_assoc()) {
        $previous_code = $row["COUNT(receipt_number)"];
        $previous_code = $previous_code + 1;
        $receiptNumber = $recPrefix . $previous_code;
    }

    return $receiptNumber;
}


function insertStudentPayment($courseCode, $studentNumber, $paymentMethod, $paymentValue, $LoggedUser, $discountAmount)
{
    global $lms_pdo;

    $receiptNumber = GenerateLMSReceiptNumber();
    $courseName = getLmsBatches()[$courseCode]['course_name'];
    $selectedStudent = GetLmsStudentsByUserName($studentNumber);
    $studentId = $selectedStudent['student_id'];
    $stdFirstName = $selectedStudent['first_name'];
    $stdLastName = $selectedStudent['last_name'];
    $telephoneNumber = $selectedStudent['telephone_1'];
    $senderId = 'Pharma C.';
    $paymentStatus = 'Paid';

    $currentDate = date("Y-m-d");
    $currentDateTime = date("Y-m-d H:i:s");
    $sql = "INSERT INTO `student_payment`(`course_code`, `student_id`, `payment_status`, `payment_type`, `paid_amount`, `paid_date`, `created_by`, `receipt_number`, `discount_amount`, `created_at`) VALUES  (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    try {
        $stmt = $lms_pdo->prepare($sql);
        $success = $stmt->execute([$courseCode, $studentId, $paymentStatus, $paymentMethod, $paymentValue, $currentDate, $LoggedUser, $receiptNumber, $discountAmount, $currentDateTime]);

        if (!$success) {
            throw new Exception("Error executing SQL statement.");
        }

        // Load message from text file
        $messageFilePath = __DIR__ . '/../assets/sms-templates/lms-payment.txt';
        $message = file_get_contents($messageFilePath);
        // You may want to add error handling here in case the file cannot be read

        // Replace placeholders in the message with actual values
        $message = str_replace(
            ['{{first_name}}', '{{last_name}}', '{{payment_value}}', '{{course_name}}'],
            [$stdFirstName, $stdLastName, number_format($paymentValue, 2), $courseName],
            $message
        );

        // Additional actions after successful insertion
        $phone_number = (strlen($telephoneNumber) == 10) ? $telephoneNumber : '0' . $telephoneNumber;
        // SentSMS($phone_number, $senderId, $message);

        return array('status' => 'success', 'message' => 'Payment inserted successfully');
    } catch (Exception $e) {
        return array('status' => 'error', 'message' => $e->getMessage());
    }
}


function GetCertificateTemplates()
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT * FROM `certificate_template`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['template_id']] = $row;
        }
    }
    return $ArrayResult;
}


function GetTemplateConfig($courseCode)
{
    global $lms_link;
    $ArrayResult = array();
    $sql = "SELECT * FROM `certificate_course_end_date` WHERE `CourseCode` LIKE '$courseCode'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['CourseCode']] = $row;
        }
    }

    return $ArrayResult;
}

function SaveOrUpdateTemplateConfig($courseCode, $completeDate, $defaultTemplate, $convocationDate, $convocationPlace, $transcriptBack)
{
    global $lms_link;

    $error = array();
    // Prepare SQL statement to check if the record exists
    $sql_check = "SELECT `id` FROM `certificate_course_end_date` WHERE `CourseCode` = ?";
    $stmt_check = $lms_link->prepare($sql_check);
    $stmt_check->bind_param("s", $courseCode);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();


    if ($result_check->num_rows > 0) {
        // Update the existing record
        $sql_update = "UPDATE `certificate_course_end_date` SET `CompleteDate` = ?, `defaultTemplate` = ?, `convocation_date` = ?, `convocation_place` = ?, `transcript_back` = ? WHERE `CourseCode` = ?";
        $stmt_update = $lms_link->prepare($sql_update);
        $stmt_update->bind_param("ssssss", $completeDate, $defaultTemplate, $convocationDate, $convocationPlace, $transcriptBack, $courseCode);
        if ($stmt_update->execute()) {
            $error = array('status' => 'success', 'message' => 'Record Updated successfully');
        } else {
            $error = array('status' => 'success', 'message' => "Error updating record: " . $stmt_update->error);
        }
    } else {
        // Insert a new record
        $sql_insert = "INSERT INTO `certificate_course_end_date` (`CourseCode`, `CompleteDate`, `defaultTemplate`, `convocation_date`, `convocation_place`, `transcript_back`) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert = $lms_link->prepare($sql_insert);
        $stmt_insert->bind_param("ssssss", $courseCode, $completeDate, $convocationDate, $convocationPlace, $transcriptBack, $defaultTemplate);
        if ($stmt_insert->execute()) {
            $error = array('status' => 'success', 'message' => 'Record saved successfully');
        } else {
            $error = array('status' => 'success', 'message' => "Error updating record: " . $stmt_insert->error);
        }
    }

    return $error;
}


function GenerateCertificateId($type, $prefix)
{

    global $lms_link;

    $sql = "SELECT COUNT(id) FROM user_certificate_print_status WHERE `type` LIKE '$type';";
    $result = $lms_link->query($sql);
    while ($row = $result->fetch_assoc()) {
        $previous_code = $row["COUNT(id)"];
        $previous_code = $previous_code + 1;
    }

    $certificateId = $prefix . $previous_code;

    return $certificateId;
}

function GetCertificateID($certificateType, $studentNumber, $courseCode)
{
    global $lms_link;

    $error = array();
    // Prepare SQL statement to check if the record exists
    $sql_check = "SELECT `certificate_id` FROM `user_certificate_print_status` WHERE `student_number` = ? AND `course_code` LIKE ? AND `type` LIKE ?";
    $stmt_check = $lms_link->prepare($sql_check);
    $stmt_check->bind_param("sss", $studentNumber,  $courseCode, $certificateType);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    if ($result_check->num_rows > 0) {
        $row = $result_check->fetch_assoc();
        return $row['certificate_id'];
    } else {
        return null;
    }
}



function EnterCertificateEntry($printDate, $printStatus, $printedBy, $certificateType, $studentNumber, $courseCode)
{
    global $lms_link;

    $error = array();
    // Prepare SQL statement to check if the record exists
    $sql_check = "SELECT `id` FROM `user_certificate_print_status` WHERE `student_number` = ? AND `course_code` LIKE ? AND `type` LIKE ?";
    $stmt_check = $lms_link->prepare($sql_check);
    $stmt_check->bind_param("sss", $studentNumber,  $courseCode, $certificateType);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();


    if ($result_check->num_rows > 0) {
        // Update the existing record
        $sql_update = "UPDATE `user_certificate_print_status` SET `print_date` = ?, `print_status` = ?, `print_by` = ?, `type` = ? WHERE `student_number` = ? AND `course_code` LIKE ?";
        $stmt_update = $lms_link->prepare($sql_update);
        $stmt_update->bind_param("ssssss", $printDate, $printStatus, $printedBy, $certificateType, $studentNumber, $courseCode);
        if ($stmt_update->execute()) {
            $error = array('status' => 'success', 'message' => 'Record Updated successfully');
        } else {
            $error = array('status' => 'success', 'message' => "Error updating record: " . $stmt_update->error);
        }
    } else {
        if ($certificateType == "Transcript") {
            $certificateId = GenerateCertificateId('Transcript', 'CTR');
        } else if ($certificateType == "Certificate") {
            $certificateId = GenerateCertificateId('Certificate', 'CREF');
        } else {
            $certificateId = GenerateCertificateId('Workshop-Certificate', 'WC');
        }
        // Insert a new record
        $sql_insert = "INSERT INTO `user_certificate_print_status` (`student_number`, `certificate_id`, `print_date`, `print_status`, `print_by`, `type`, `course_code`) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $lms_link->prepare($sql_insert);
        $stmt_insert->bind_param("sssssss", $studentNumber, $certificateId, $printDate, $printStatus, $printedBy, $certificateType, $courseCode);
        if ($stmt_insert->execute()) {
            $error = array('status' => 'success', 'message' => 'Record saved successfully');
        } else {
            $error = array('status' => 'success', 'message' => "Error updating record: " . $stmt_insert->error);
        }
    }

    return $error;
}


function GetTemplate($TemplateID)
{
    global $lms_link;
    $ArrayResult = array();
    $sql = "SELECT * FROM `certificate_template` WHERE `template_id` LIKE '$TemplateID'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row["template_id"]] = $row;
        }
    }
    return $ArrayResult;
}


function UpdateUserFullDetails($user_id, $first_name, $last_name, $gender, $user_name, $civil_status, $phone_number, $email_address, $address_l1, $address_l2, $city, $whatsapp_number, $nic_number, $district, $postalCode, $full_name, $name_with_initials, $name_on_certificate, $birth_day, $update_by)
{
    global $lms_link;
    $error = array('status' => 'initial', 'message' => '', 'username' => "Not Set");


    $sql = "UPDATE `user_full_details` SET `student_id` = ?, `civil_status` = ?, `first_name` = ?, `last_name` = ?, `gender`=?, `telephone_1` = ?, `e_mail` = ?,`username` = ?, `address_line_1` = ?, `address_line_2` = ?, `city` = ?, `telephone_2` = ?, `nic` = ?, `district` = ?, `postal_code`= ?, `full_name` = ?, `name_with_initials` = ?, `name_on_certificate` = ?, `birth_day`= ?, `updated_by`= ? WHERE `username` LIKE '$user_name'";


    if ($stmt = mysqli_prepare($lms_link, $sql)) {

        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssssssssssssssssss", $user_id, $civil_status, $first_name, $last_name, $gender, $phone_number, $email_address, $user_name, $address_l1,  $address_l2,  $city, $whatsapp_number, $nic_number, $district, $postalCode, $full_name, $name_with_initials, $name_on_certificate, $birth_day, $update_by);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            $error = array('status' => 'success', 'message' => 'Full Details Updated successfully', 'affected_rows' => $affected_rows, 'username' => $user_name);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link), 'username' => "Not Set");
        }
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link), 'username' => "Not Set");
    }

    // Return the error as a JSON-encoded string
    return json_encode($error);
}


function UpdateMainUser($first_name, $last_name, $civil_status, $phone_number, $email_address, $user_name)
{
    global $lms_link;
    $error = array('status' => 'initial', 'message' => '');

    $sql = "UPDATE users SET `fname` = ? , `lname` = ? , `status_id` = ? , `phone` = ? , `email` = ? WHERE `username` = ? ";


    if ($stmt = mysqli_prepare($lms_link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssss", $first_name, $last_name, $civil_status, $phone_number, $email_address, $user_name);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            $error = array('status' => 'success', 'message' => 'Account Updated successfully', 'affected_rows' => $affected_rows);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link));
        }
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link));
    }

    // Return the error as a JSON-encoded string
    return json_encode($error);
}


function GetFinalGrade($UserName, $CourseCode)
{
    global $lms_link;

    $ArrayResult = array();
    $final_percentage_value = 0;
    $sql_inner = "SELECT `result` FROM `certificate_user_result` WHERE `index_number` LIKE '$UserName' AND `course_code` LIKE '$CourseCode' AND `title_id` LIKE 'OverRallGrade'";
    $result_inner = $lms_link->query($sql_inner);
    if ($result_inner->num_rows > 0) {
        while ($row = $result_inner->fetch_assoc()) {
            $final_percentage_value = $row['result'];
        }
    }

    $ArrayResult["grade"] = $final_percentage_value;

    if ($final_percentage_value == "Result Not Submitted") {
        $finalGrade = "Result Not Submitted";
    } elseif ($final_percentage_value >= 90) {
        $finalGrade = "A+";
    } elseif ($final_percentage_value >= 80) {
        $finalGrade = "A";
    } elseif ($final_percentage_value >= 75) {
        $finalGrade = "A-";
    } elseif ($final_percentage_value >= 70) {
        $finalGrade = "B+";
    } elseif ($final_percentage_value >= 65) {
        $finalGrade = "B";
    } elseif ($final_percentage_value >= 60) {
        $finalGrade = "B-";
    } elseif ($final_percentage_value >= 55) {
        $finalGrade = "C+";
    } elseif ($final_percentage_value >= 45) {
        $finalGrade = "C";
    } elseif ($final_percentage_value >= 40) {
        $finalGrade = "C-";
    } elseif ($final_percentage_value >= 35) {
        $finalGrade = "D+";
    } elseif ($final_percentage_value >= 30) {
        $finalGrade = "D";
    } elseif ($final_percentage_value >= 0) {
        $finalGrade = "E";
    }

    $ArrayResult["final_grade"] = $finalGrade;

    return $ArrayResult;
}

function GetCompanyDetails()
{
    global $lms_link;

    $ArrayResult = array();
    $sql = "SELECT `id`, `company_name`, `company_address`, `company_address2`, `company_city`, `company_postalcode`, `company_email`, `company_telephone`, `company_telephone2`, `job_position` , `owner_name` FROM `company`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row['id']] = $row;
        }
    }

    return $ArrayResult[1];
}



function GetAssignmentSubmissions()
{
    global $lms_link;
    $ArrayResult = array();
    $sql = "SELECT `id`, `assignment_id`, `file_path`, `created_by`, `created_at`, `status`, `grade` FROM `assignment_submittion`";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $assignmentId = $row["assignment_id"];
            $submitBy = $row["created_by"];
            $ArrayResult[$assignmentId . '-' . $submitBy] = $row;
        }
    }
    return $ArrayResult;
}




function GetAssignmentSubmissionsByUser($studentNumber)
{
    global $lms_link;
    $ArrayResult = array();
    $sql = "SELECT `id`, `assignment_id`, `file_path`, `created_by`, `created_at`, `status`, `grade` FROM `assignment_submittion` WHERE `created_by` = '$studentNumber'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $assignmentId = $row["assignment_id"];
            $ArrayResult[$assignmentId] = $row;
        }
    }
    return $ArrayResult;
}




function GetAssignments($courseCode)
{
    global $lms_link;
    $ArrayResult = array();
    $sql = "SELECT * FROM `assignment` WHERE `course_code` LIKE '$courseCode'";
    $result = $lms_link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ArrayResult[$row["assignment_id"]] = $row;
        }
    }
    return $ArrayResult;
}



function SaveEditedAssignmentGrade($assignmentId, $gradeValue, $studentNumber)
{
    global $lms_link;
    $error = array('status' => 'initial', 'message' => '', 'username' => "Not Set");

    $sql = "INSERT INTO `assignment_submittion` (`assignment_id`, `created_by`, `created_at`, `status`, `grade`) 
        VALUES (?, ?, NOW(), 'Graded', ?)
        ON DUPLICATE KEY UPDATE grade = VALUES(grade)";


    if ($stmt = mysqli_prepare($lms_link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $assignmentId, $studentNumber, $gradeValue);

        if (mysqli_stmt_execute($stmt)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            $error = array('status' => 'success', 'message' => 'Grade updated successfully', 'affected_rows' => $affected_rows);
        } else {
            $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link));
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = array('status' => 'error', 'message' => 'Something went wrong. Please try again later. ' . mysqli_error($lms_link));
    }

    return $error;
}
