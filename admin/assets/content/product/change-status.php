<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
// Assuming you have received the necessary parameters for the plan
$LoggedUser = $_POST["LoggedUser"];
$UpdateKey = $_POST["UpdateKey"];
$isActive = $_POST["IsActive"];
$createdAt = date("Y-m-d H:i:s"); // You can set the creation date here

$result = UpdateProductStatus($link,  $isActive, $UpdateKey, $createdAt, $LoggedUser);
echo $result;
