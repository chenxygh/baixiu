<?php include 'inc/login_state.php'; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<title>Comments &laquo; Admin</title>
	<link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
	<link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
	<link rel="stylesheet" href="/static/assets/css/admin.css">
	<style>
	#loading {
		display: flex;
		position: fixed;
		top: 0;
		left: 0;
		bottom: 0;
		right: 0;
		z-index: 999;
		justify-content: center;
		align-items: center;
		background-color: rgba(0, 0, 0, .5);
	}

	.flip-txt-loading {
		font: 26px Monospace;
		letter-spacing: 5px;
		color: #fff;
	}

	.flip-txt-loading > span {
		animation: flip-txt  2s infinite;
		display: inline-block;
		transform-origin: 50% 50% -10px;
		transform-style: preserve-3d;
	}

	.flip-txt-loading > span:nth-child(1) {
		-webkit-animation-delay: 0.10s;
		animation-delay: 0.10s;
	}

	.flip-txt-loading > span:nth-child(2) {
		-webkit-animation-delay: 0.20s;
		animation-delay: 0.20s;
	}

	.flip-txt-loading > span:nth-child(3) {
		-webkit-animation-delay: 0.30s;
		animation-delay: 0.30s;
	}

	.flip-txt-loading > span:nth-child(4) {
		-webkit-animation-delay: 0.40s;
		animation-delay: 0.40s;
	}

	.flip-txt-loading > span:nth-child(5) {
		-webkit-animation-delay: 0.50s;
		animation-delay: 0.50s;
	}

	.flip-txt-loading > span:nth-child(6) {
		-webkit-animation-delay: 0.60s;
		animation-delay: 0.60s;
	}

	.flip-txt-loading > span:nth-child(7) {
		-webkit-animation-delay: 0.70s;
		animation-delay: 0.70s;
	}

	@keyframes flip-txt  {
		to {
			-webkit-transform: rotateX(1turn);
			transform: rotateX(1turn);
		}
	}
	</style>
	<script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
	<script>NProgress.start()</script>

	<div class="main">
		<?php include 'inc/navbar.php'; ?>
		<div class="container-fluid">
			<div class="page-title">
				<h1>所有评论</h1>
			</div>
			<!-- 有错误信息时展示 -->
	  <!-- <div class="alert alert-danger">
		<strong>错误！</strong>发生XXX错误
	</div> -->
	<div class="page-action">
		<!-- show when multiple checked -->
		<div class="btn-batch" style="display: none">
			<button class="btn btn-info btn-sm">批量批准</button>
			<button class="btn btn-warning btn-sm">批量拒绝</button>
			<button class="btn btn-danger btn-sm">批量删除</button>
		</div>
		<ul class="pagination pagination-sm pull-right"></ul>
	</div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="text-center" width="40"><input type="checkbox"></th>
				<th width="50">作者</th>
				<th>评论</th>
				<th width="120">评论在</th>
				<th>提交于</th>
				<th>状态</th>
				<th class="text-center" width="150">操作</th>
			</tr>
		</thead>
		<tbody>
			<!-- <tr class="danger">
				<td class="text-center"><input type="checkbox"></td>
				<td>大大</td>
				<td>楼主好人，顶一个</td>
				<td>《Hello world》</td>
				<td>2016/10/07</td>
				<td>未批准</td>
				<td class="text-center">
					<a href="post-add.html" class="btn btn-info btn-xs">批准</a>
					<a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
				</td>
			</tr> -->
		</tbody>
	</table>
</div>
</div>

<?php include 'inc/sidebar.php'; ?>

<div id="loading" style="display: none;">
	<div class="flip-txt-loading">
		<span>L</span><span>o</span><span>a</span><span>d</span><span>i</span><span>n</span><span>g</span>
	</div> 
</div> 

<script type="text/x-jsrender" id="comments_tmpl">
	{{for comments}}
	<tr{{if status === 'rejected'}} class="danger"{{else status === 'held'}} class="warning"{{/if}} data-id="{{:id}}">
	<td class="text-center"><input type="checkbox"></td>
	<td>{{:author}}</td>
	<td>{{:content}}</td>
	<td>《{{:post_title}}》</td>
	<td>{{:created}}</td>
	<td>{{:status}}</td>
	<td class="text-center">
		{{if status === 'held'}}
		<a href="post-add.html" class="btn btn-info btn-xs">批准</a>
		<a href="post-add.html" class="btn btn-warning btn-xs">拒绝</a>
		{{/if}}
		<a href="javascript:;" class="btn btn-danger btn-xs btn-delete">删除</a>
	</td>
</tr>
{{/for}}
</script>
<script src="/static/assets/vendors/jquery/jquery.js"></script>
<script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
<script src="/static/assets/vendors/jsrender/jsrender.js"></script>
<script src="/static/assets/vendors/twbs-pagination/jquery.twbsPagination.js"></script>
<script>
	// nprogress
	$(document)
		.ajaxStart(function () {
			NProgress.start()
			$('#loading').fadeIn()
		})
		.ajaxStop(function () {
			NProgress.done()
			$('#loading').fadeOut()
		})

	$(function ($) {
		var currentPage = 1;// 当前页面，默认为 1

		/* =========== 发送 ajax 请求获取 json 数据, 并通过 jsrender 渲染到页面上 =========== */
		function showPageData (page) {
			$.getJSON('/admin/api/comments.php', {page: page}, function (res) {
				/* =========== 获取总页数 =========== */
				var totalPages = parseInt(res['total_pages']);
				if (page > totalPages) {// page 过大，跳到最后一页
					showPageData(totalPages);
					return;
				}

				currentPage = page;

				// ajax 是异步请求，用传统的返回 totalPages 的方式是不可行的
				/* =========== twbsPagination 插件的使用 =========== */
				$('.pagination').twbsPagination('destroy');// 动态总页数的方式, 要先销毁
				$('.pagination').twbsPagination({
					totalPages: totalPages,
					startPage: page,
					visiblePages: 5,
					initiateStartPageClick: false,
					onPageClick: function (event, page) {
						// 默认页面初始化的时候也会执行一次，initiateStartPageClick: false 取消该功能
						showPageData(page);
					}
				});

				/* =========== 模板引擎渲染到页面 =========== */
				var html = $('#comments_tmpl').render({comments: res['comments']});
				$('tbody').fadeOut(function () {
					$(this).html(html).fadeIn();
				});
			});
		}

		showPageData(currentPage);

		// 根据 id 发送 ajax 请求，删除评论(重新渲染)
		// 这里应该使用 事件委托，因为元素可能没有渲染到页面上
		$('tbody').on('click', '.btn-delete', function (event) {
			// 1. 获取 id，根据 id，发送 ajax 请求
			var tr = $(this).parent().parent();
			var id = tr.data('id');
			$.getJSON('/admin/api/comments_del.php', {id: id}, function (res) {
				// 2. 接收服务端返回信号, 根据信号，决定是否重新渲染当前评论页面
				res? showPageData(currentPage): '';
			})
		});
	});
</script>
<script>NProgress.done()</script>
</body>
</html>
