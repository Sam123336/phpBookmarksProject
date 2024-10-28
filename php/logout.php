<?php
session_start();
session_destroy();
header('Location: /project/views/login_form.php');
?>
