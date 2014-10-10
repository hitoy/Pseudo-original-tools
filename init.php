<?php
/**************************
 * 启动文件 2014年2月25日 *
 *************************/

//定义系统主目录
define("Croot",str_replace("\\","/",dirname(__FILE__))."/");

//加载翻译库文件
require_once(Croot."libs/translate.class.php");

//加载配置文件
require_once(Croot."config.php");

//网站的域名后的url
$uri=str_replace("\\","/",dirname($_SERVER["SCRIPT_NAME"])."/");
$uri=str_replace("//","/",$uri);
define("urlroot",$uri);
?>
