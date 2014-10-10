<?php
/******************************************
 * 用来接受火车头等其它工具传递过来的数据 *
 * 杨海涛 2014年2月25日 *******************
 * 这个页面只翻译内容，不翻译标题*/

require_once("./init.php");

//获取系统支持的翻译的源语言和目标语言
if(isset($_GET["action"])&&$_GET["action"]=="list"){
	echo str_replace("\n","",file_get_contents("http://lab.hitoy.org/collection/supportlang/support"));
	exit();
}


//当没有接口api或者api错误时，为非法操作
if(!isset($_GET["api"])||$_GET["api"]!=$cfg["api"])exit("非法操作!");

//开始数据处理
$post_title=isset($_POST["title"])?trim($_POST["title"]):"";
$post_content=isset($_POST["content"])?trim($_POST["content"]):"";
$post_category=isset($_POST["category"])?trim($_POST["category"]):"";
$post_keywords=isset($_POST["keyword"])?trim($_POST["keyword"]):"";
$post_description=isset($_POST["description"])?trim($_POST["description"]):"";

if($post_title=="") exit("发布失败，标题为空!");
if($post_content=="") exit("发布失败，内容为空!");

//初始化百度翻译接口
$translator=new BaiduTranslator($cfg["baiduapi"]);
if($post_category!==""){
	$slang=explode("-",$post_category)[0];
	$tlang=explode("-",$post_category)[1];
	$translator->setlang($slang,$tlang);
}

/*
 * 不翻译标题
//开始翻译标题
$translator->setContent($post_title);
$post_title=strip_tags($translator->getResult());
if($translator->error>0) exit("发布失败，失败原因:".$post_title);
 */

//开始翻译内容
$translator->setContent($post_content);
$post_content=$translator->getResult();
if($translator->error>0) exit("发布失败，失败原因:".$post_content);

//开始翻译关键词和描述
if($post_keywords!=""){
	$translator->setContent($post_keywords);
	$post_keywords=$translator->getResult();
}
if($post_description!=""){
	$translator->setContent($post_description);
	$post_description=$translator->getResult();
}

//把内容中的站外图片下载到本地
require_once(Croot."libs/file.class.php");
$complexstr=new Images($post_content);
$post_content=$complexstr->replace($cfg["uploads"]);

//下面开始进行写入操作
//加载相关链接引擎
require_once(Croot."libs/dirlist.class.php");
$newlist=new dirlist();
$linklist=$newlist->getLink($cfg["storedir"]);

//加载模板引擎
require_once(Croot."libs/template.class.php");
$template=new Template();
$template->assign("title",$post_title);
$template->assign("content",$post_content);
$template->assign("keywords",$post_keywords);
$template->assign("description",$post_description);
$template->assign("indexdir",urlroot);
$template->assign("templateurl",urlroot."templates/");
$template->assign("newslist",$linklist);

$template->save_html($cfg["storedir"]);
echo $post_title."发布成功!";
?>
