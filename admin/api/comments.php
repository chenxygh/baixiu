<?php

require_once '../../functions.php';

// TODO: 获取所有的评论数据，json 格式返回

// 根据传入的页码，返回相应的数据

/* ================== 计算分页参数 ================== */

$page = empty($_GET['page'])? 1: (is_numeric($_GET['page'])? intval($_GET['page']): 1);
$size = 3;
$offset = ($page - 1) * $size;


/* ================== 求总页数 ================== */

// 总条数
$cnt_res = xiu_select_one('select
	count(1) as cnt
from comments
inner join posts on comments.post_id = posts.id;');
$total_cnt = $cnt_res? $cnt_res['cnt']: 0;// 返回 false ，为 0

// 总页数
$total_pages = (int)ceil($total_cnt / $size);
$total_pages = $total_pages <= 0? 1: $total_pages;// 异常页数, 处理为 1


/* ================== 获取查询数据 ================== */

$data = xiu_select_all(sprintf('select
	comments.*,
	posts.title as post_title
from comments
inner join posts on comments.post_id = posts.id
order by comments.created desc
limit %d, %d;', $offset, $size));
$data = $data? $data: array();// 返回 false，处理为 []

// 这是一个好习惯， jQuery 会自动转，原生 js 还是字符串
header('Content-Type: application/json');

echo json_encode(array(
	'comments' => $data,
	'total_pages' => $total_pages
));
