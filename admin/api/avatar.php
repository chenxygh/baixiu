<?php

/**
 *
 * 根据 email ，获取用户头像
 * 通过 ? 参数获取 email
 * email 没有，或者有误等等
 * 返回 error
 * 
 */

require_once '../../config.php';

header('Content-Type: application/json');

// 获取邮箱
if (empty($_GET['email'])) {
	echo '{"error": "fuck"}';
	exit();
}

$email = $_GET['email'];

// 连接数据库
$conn = mysqli_connect(XIU_DB_HOST, XIU_DB_USER, XIU_DB_PASS, XIU_DB_NAME);
if (!$conn) {
	echo '{"error": "fuck"}';
	exit();
}

$select = "select avatar from users where email = '{$email}' limit 1;";
$query = mysqli_query($conn, $select);
if (!$query) {
	echo '{"error": "fuck"}';
	exit();
}
$avatar = mysqli_fetch_assoc($query);

if (!$avatar) {
	echo '{"error": "fuck"}';
	exit();
}

echo json_encode($avatar);
