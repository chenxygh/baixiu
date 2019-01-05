<?php

// 1. 接收校验
if (empty($_FILES['avatar'])) exit('请正确上传文件');
$avatar = $_FILES['avatar'];

// 校验
if (!preg_match('/^(image\/)/', $avatar['type'])) exit('文件类型不正确');
if ($avatar['size'] > 1024 * 1024 * 10) exit('文件过大');

// 2. 持久化
$dir = '../../static/uploads';
if (!is_dir($dir)) {
	mkdir($dir);
}

$destination = $dir . '/' .  'img-' . uniqid() . '.' . pathinfo($avatar['name'], PATHINFO_EXTENSION);
if (!move_uploaded_file($avatar['tmp_name'], $destination)) {
	exit('上传失败');
}

// 3. 上传成功, 响应文件的 url
$file = substr($destination, 5);

echo $file;
