<?php
session_start();
if($_SESSION["RegConfirm"] == true) {
    echo "Your Account has been registered. Now please confirm your account with the email you got.";
} else {
    header('Location:'.'https://swapitg.com');
}
?>
