<?php

require_once '../../functions.php';

// TODO: 通过 id 删除数据
// 获取id

function getid () {
	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		if (empty($_GET['id'])) {
			exit('请正确删除');			
		}

		return $_GET['id'];
	}
}

// 解决 sql 注入，可以使用正则表达式，设置过滤函数

$id = getid();

$affected_rows = xiu_execute("delete from comments where id in ({$id});");

header('Content-Type: aplication/json');

echo json_encode($affected_rows > 0);
