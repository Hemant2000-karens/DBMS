<?php 
session_start();
if(!isset($_SESSION["admin_name"]))
{
    header("location: adminlogin.php");
}

$db=mysqli_connect("localhost","root","","car_showroom");

if(isset($_POST["APPROVE"])){
    $id=$_POST["custId"];
    $sql = "UPDATE sale2 SET status=1 WHERE sale_id=$id";
    if (mysqli_query($db, $sql)) {
        
      } else {
        echo "Error updating record: " . mysqli_error($conn);
      }
    $getorders= mysqli_query($db, "SELECT * from sale2 WHERE sale_id = $id;");
    $ord=mysqli_fetch_assoc($getorders);
    $model=$ord['carmodel'];
    $del=mysqli_query($db, "SELECT * from car WHERE model=$model ");
    $numrows3 =mysqli_num_rows($del);
    if($numrows3 !=0){
        $row3=mysqli_fetch_assoc($del);
        $ch_no=$row3['chassis_number'];

        $sqld = "DELETE FROM car WHERE chassis_number=$ch_no";

        if (mysqli_query($db, $sqld)=== TRUE) {
          
        } else {
          echo "Error deleting record: ";
        }
    }
}
header("location: adminorders.php")
?>
