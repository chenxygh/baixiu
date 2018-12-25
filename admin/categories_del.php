<?php

require_once '../functions.php';

// TODO: 通过 id 删除数据
// 获取id

function getid () {
	// 单个 id
	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		if (empty($_GET['id'])) {
			exit('请正确删除');			
		}

		return $_GET['id'];
	}

	// 多个 id
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (empty($_POST['checkbox'])) {
			exit('请正确删除');			
		}
		return join(',', $_POST['checkbox']);
	}
}

$id = getid();

$affected_rows = xiu_execute("delete from categories where id in ({$id});");
if ($affected_rows <= 0) {
	exit('删除失败');
}

// 一切 OK，跳转回 categories.php
header('Location: /admin/categories.php');
