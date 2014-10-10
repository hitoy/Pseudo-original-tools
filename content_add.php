<?php
/*************************************
 * 内容发布页面 杨海涛 2014年2月28日 *
 ************************************/
//特别注意：因为很少用到手动添加内容的功能，
//为节省开发时间，这里不在做授权认证
//当需要手动添加内容时，直接访问此页面即可
//如果长期不需要添加内容，请删除此页面，避免被黑客扫描到
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>添加内容</title>
<style>
* {margin:0;padding:0}
form {display:block;width:800px;margin:20px auto;font-size:12px}
label {width:50px;height:20px;display:inline-block;}
input {width:500px;height:20px;line-height:20px;margin:5px auto;}
textarea {width:700px;height:400px}
input[type="submit"] {width:60px;height:30px;float:right;margin-right:100px}
#trans {width:30px;}
</style>
</head>
<body>
<form action="./interface.php?api=aOojk3ddhTBB" method="POST" target="_blank"/>	
<label>标题:</label>
<input name="title" type="text">
<br>
<label>关键词:</label>
<input name="keyword" type="text">
<br>
<label>描述:</label>
<input name="description" type="text">
<br/>
<label>内容:</label>
<br>
<textarea name="content" class="post_content"></textarea>
<br>
不翻译直接发布:<input type="checkbox" id="trans" onchange="changeaction()"/>
<input type="submit" value="提交" class="submit">
<form>
<script>
var change=1;
function changeaction(){
	if(change%2==1){
		document.forms[0].action="./post.php?api=aOojk3ddhTBB";
	}else{
		document.forms[0].action="./interface.php?api=aOojk3ddhTBB";
	}
change++;
}
</script>
</body>
</html>
