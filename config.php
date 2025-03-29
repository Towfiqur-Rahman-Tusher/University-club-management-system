<?php
$conn = mysqli_connect('localhost','root','','club_members');
if(!$conn){
    die('Connection failed: '.mysqli_connect_error());
}
?>