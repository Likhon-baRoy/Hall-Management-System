<?php
include("../db/config.php");

$name=$_POST['name'];
$email=$_POST['email'];
$pass=$_POST['password'];
$encode=md5($pass);
$action=1;

$sql="INSERT INTO c_info(name,email,password,action) VALUES('$name','$email','$encode','$action')";

$datainsert=mysqli_query($myconnect,$sql);

if($datainsert==TRUE) {
    echo header("location:../index.php");
} else {
    echo  "Customer Info Not Added!";
}
?>
