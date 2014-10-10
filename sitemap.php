<?php
//为了节省系统资源，对来源请求进行分流
$randkey=rand(0,9);

if($randkey!=7&&file_exists("./sitemap.xml")){
	//70%的机会不更新sitemap
	header("location:sitemap.xml");
	exit();
}

//获取页面个数
$pagecount=trim(file_get_contents("./data/filename.txt"));

//设置最大显示的sitemap个数
$urlcount=5000;

//获取页面内容
ob_start();
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.84\">\n";
while($pagecount>0 && $urlcount>0){
	echo "<url><loc>http://www.wiki-mining.com/$pagecount</loc></url>\n";
	$urlcount--;
	$pagecount--;
}
echo "</urlset>";
$sitemap=ob_get_contents();
ob_clean();
//写入sitemap并跳转
file_put_contents("./sitemap.xml",$sitemap);
header("location:sitemap.xml")
?>
