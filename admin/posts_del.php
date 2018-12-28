<?php

require_once '../functions.php';

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

$affected_rows = xiu_execute("delete from posts where id in ({$id});");
if ($affected_rows <= 0) {
	exit('删除失败');
}

/* ================= 原来页面状态的保持 ================ */
// $search = '';
// $search .= empty($_GET['page'])? 'page=1': 'page=' . $_GET['page'];
// $search .= empty($_GET['category'])? '': '&category=' . $_GET['category'];
// $search .= empty($_GET['status'])? '': '&status=' . $_GET['status'];

// 一切 OK，跳转回 posts.php
// http 中的 referer 记录的是发出请求的来源
xiu_redirect($_SERVER['HTTP_REFERER']);
