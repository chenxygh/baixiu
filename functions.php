<?php

/**
 *
 *
 * 封装公用函数
 *
 * 
 * 
 */

session_start();

/**
 * 根据 session 获取用户信息
 * 如有，就返回用户信息，没有就跳转到登陆页面
 * @return [type] [description]
 */
function xiu_get_current_user () {
	if (empty($_SESSION['current_user'])) {
		header('Location: /admin/login.php');
		exit();
	}

	return $_SESSION['current_user'];
}

/**
 * select query all
 * @return [type] [description]
 */
function xiu_select_all ($select) {
	// 这里应该使用 require 进来的文件
	// 但是现在，没有解决 require 的路径问题
	// 不用！
	// require 和 include 会以引用它们的文件为当前文件，相对路径是不准的！
	$conn = mysqli_connect('127.0.0.1', 'root', 'MYsql.77109', 'baixiu-dev');
	if (!$conn) {
		exit('连接数据库失败');
	}

	$query = mysqli_query($conn, $select);
	if (!$query) {
		return false;
	}

	while ($row = mysqli_fetch_assoc($query)) {
		$data[] = $row;
	}

	if (count($data) === 0) {
		return false;
	}

	return $data;
}

/**
 * select query one
 * @return [type] [description]
 */
function xiu_select_one ($select) {
	$res = xiu_select_all($select);
	if (empty($res[0])) {
		return false;
	}
	return $res[0];
}
