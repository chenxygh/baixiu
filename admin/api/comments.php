<?php

require_once '../../functions.php';

// 获取所有的评论数据，json 格式返回

$data = xiu_select_all('select
	comments.*,
	posts.title as post_title
from comments
inner join posts on comments.post_id = posts.id;');

// 这是一个好习惯， jQuery 会自动转，原生 js 还是字符串
header('Content-Type: application/json');

echo json_encode($data);
