<?php

/**
 *
 *
 * 封装公用函数
 *
 * 
 * 
 */

/**
 * 搭桥，并且执行查询语句
 * 返回 $conn 和 $query
 * @param  [type] $sql [description]
 * @return [type]      [description]
 */
function xiu_query ($sql) {
	$conn = mysqli_connect('127.0.0.1', 'root', 'MYsql.77109', 'baixiu-dev');
	if (!$conn) {
		exit('连接数据库失败');
	}

	$query = mysqli_query($conn, $sql);
	if (!$query) {
		mysqli_close($conn);
		return false;
	}

	return array($conn, $query);
}

/**
 * 根据 session 获取用户信息
 * 如有，就返回用户信息，没有就跳转到登陆页面
 * @return [type] [description]
 */
function xiu_get_current_user () {
	@session_start();

	if (empty($_SESSION['current_user'])) {
		header('Location: /admin/login.php');
		exit();
	}

	session_write_close();

	return $_SESSION['current_user'];
}

/**
 * select query all
 * @return [type] [description]
 */
function xiu_select_all ($sql) {
	// 这里应该使用 require 进来的文件
	// 但是现在，没有解决 require 的路径问题
	// 不用！
	// require 和 include 会以引用它们的文件为当前文件，相对路径是不准的！

	$res = xiu_query($sql);
	$conn = $res[0];
	$query = $res[1];
	if (!$query) {
		return false;
	}

	while ($row = mysqli_fetch_assoc($query)) {
		$data[] = $row;
	}

	if (!isset($data)) {
		mysqli_free_result($query);
		mysqli_close($conn);
		return false;
	}

	mysqli_free_result($query);
	mysqli_close($conn);
	
	return $data;
}

/**
 * select query one
 * @return [type] [description]
 */
function xiu_select_one ($sql) {
	$res = xiu_select_all($sql);
	if (empty($res[0])) {
		return false;
	}
	return $res[0];
}

/**
 * 增删改
 * @return [type] [description]
 */
function xiu_execute ($sql) {
	$res = xiu_query($sql);
	$conn = $res[0];
	$query = $res[1];
	if (!$query) {
		return false;
	}

	$affected_rows = mysqli_affected_rows($conn);

	mysqli_close($conn);
	
	return $affected_rows;
}
