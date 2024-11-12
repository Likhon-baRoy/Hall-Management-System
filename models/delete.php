<?php
include("../config/config.php");
$id=$_GET['id'];

$deletesql="DELETE FROM c_info WHERE c_id='$id'";
$deleteresult=mysqli_query($myconnect,$deletesql);

if($deleteresult===TRUE) {
    header("location:../views/pages/index.php");
} else {
    echo("data not in the database");
}
?>
