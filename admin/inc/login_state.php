<?php

session_start();

if (empty($_SESSION['current_user'])) {
	header('Location: /admin/login.php');
	exit();
}
