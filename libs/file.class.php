<?php
/*****************************
 * 文件管理系统，
 * 主要用来把需要替换的文字中含有的图片等信息从服务器上下载到本地
 ****************************/
class Images{

	private $str;				//需要处理的字符串
	private $filearr=array();//需要上传的文件名
	private $filehash=array();//存储需要下载的文件url的哈希值，如果存在，则不继续下载

	public function __construct($str){
		$this->str=trim($str);
		$pattern="/(<img[^\>]+src=[^\>]+)(http:\/\/[^\"\'\n\>]+)/i";
		preg_match_all($pattern,$str,$matchs);
		$this->filearr=$matchs[2];
	}

	//把含有远程文件的内容保存到本地，并替换相应内容
	public function replace($dir){
		//另存为本地文件的文件名
		$remotefileexp=array();
		$locafilearr=array();
		foreach($this->filearr as $singlefile){

			$localfilecontent=file_get_contents($singlefile);
			$thisname=time()."_".rand(0,100).strrchr($singlefile,".");
			//产生需要替换的两个数组
			$remotefileexp[]="/(<img[^\>]+src=[^\>]+)(http:\/\/[^\"\'\n\>]+)/i";//远程的数据
			$locafilearr[]="$1".urlroot.$dir."/".$thisname;//本地数据存放位置

			file_put_contents(Croot.$dir."/".$thisname,$localfilecontent);
		}
		$str=preg_replace($remotefileexp,$locafilearr,$this->str,1);
		return $str;
	}
}
?>
