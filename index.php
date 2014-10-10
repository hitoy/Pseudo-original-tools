<?php
/***********************************************
 * 此文件用来像wordpress一样，是所有请求的入口 *
 **********************************************/
require_once("./init.php");
//请求的文件名
$request_filename=basename($_SERVER["REQUEST_URI"]).".html";
//实际请求的文件名
$real_file=(dirname($_SERVER["DOCUMENT_ROOT"].$_SERVER["REQUEST_URI"]))."/".$cfg["storedir"]."/".$request_filename;

if(file_exists($real_file)){
	//对实际文件是否存在作出判断
	echo file_get_contents($real_file);
}else if($_SERVER["REQUEST_URI"]==$_SERVER["PHP_SELF"]||($_SERVER["REQUEST_URI"]."index.php")==$_SERVER["PHP_SELF"]){
	//如果访问的是首页，则随机展示出一张网站
	$filename=trim(file_get_contents("./data/filename.txt"));
	$filename=rand(1,$filename);
	$originalfile="./".$cfg["storedir"]."/$filename".".html";
	if(file_exists($originalfile)){
		echo file_get_contents("./".$cfg["storedir"]."/$filename".".html");
	}else{
		header("HTTP/1.1 404 Not Found");
		echo "您的系统暂时还没有内容，请发布内容后重试!";
	}
}else{
	header("HTTP/1.1 404 Not Found");
	//相关链接引擎
	require_once(Croot."libs/dirlist.class.php");
	$newlist=new dirlist();
	$linklist=$newlist->getLink($cfg["storedir"]);
	//模板引擎
	require_once(Croot."libs/template.class.php");
	$template=new Template();
	$template->assign("title","Unable to Process Request");
	$template->assign("indexdir",urlroot);
	$template->assign("templateurl",urlroot."templates/");
	$template->assign("newslist",$linklist);

	echo $template->display("404");
}
?>
