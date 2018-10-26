<?php
session_start();
session_destroy();
Header("Location:auth.php");

?>
