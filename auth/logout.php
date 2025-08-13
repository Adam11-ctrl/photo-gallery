<?php
session_start();
$_SESSION = [];
session_destroy();
header('Location: http://localhost/photo-gallery//public/index.php');
exit;
