<?php
include("../config/config.php");
$id=$_GET['id'];

$sodeletesql="UPDATE c_info SET action=0 WHERE c_id='$id'";

$deleteresult=mysqli_query($myconnect,$sodeletesql);
if($deleteresult===TRUE) {
    header("location:../models/cudetails.php");
} else {
    echo("No data in the database!");
}
?>
