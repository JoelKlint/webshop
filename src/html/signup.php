<?php

require_once __DIR__ . "/../inc/DatabaseHelper.php";
$db = new DatabaseHelper();

$USERNAME = $_POST['inputUsername'];
$PASSWORD = $_POST['inputPassword'];
$CONFIRMPASSWORD = $_POST['inputConfirmPassword'];
$ADDRESS = $_POST['inputAddress'];

if(!empty($USERNAME && !empty($PASSWORD)) && !empty($CONFIRMPASSWORD) && !empty($ADDRESS)){

	if($PASSWORD == $CONFIRMPASSWORD){
		$success = $db->save_new_user_with_username_address_pswd($USERNAME, $ADDRESS, $PASSWORD);
    if($success) {
      header("Location: productlist.php");
      die;
    }

	}
}
header("Location: signup.html");

?>
