<?php
/**********************************
 * 用来获取展示指定目录中相关链接 *
 *********************************/

class dirlist{
	private $count;//获取列表的个数

	//构造函数指定要打开的文件
	public function __construct($c=5){
		$this->count=$c;
	}
	
	//获取不重复的随机文件名
	private function getrandfilename(){
		$max_file_name=trim(file_get_contents(Croot."data/filename.txt"));
		if($this->count>$max_file_name) $this->count=$max_file_name;
		$randfile=array();
		for($i=0;$i<$this->count;$i++){
			$randnum=rand(1,$max_file_name);
			while(in_array($randnum,$randfile)){
				$randnum=rand(1,$max_file_name);	
			}
			$randfile[]=$randnum;
		}
		return $randfile;
	}

	//获取指定文件名和title
	public function getlink($dir){
		$filelist=$this->getrandfilename();
		$link=array();
		foreach($filelist as $singlefile){
			$filecontent=file_get_contents(Croot.$dir."/".$singlefile.".html");	
			preg_match("/<head>[\s\S]*<title>([^\n]+)<\/title>/i",$filecontent,$matchs);
			$link[urlroot.$singlefile]=$matchs[1];
		}
	return $link;
	}
}
?>
