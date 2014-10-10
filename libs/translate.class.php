<?php
/************************************
 * PHP翻译插件 杨海涛 2014年2月20日 *
 ***********************************/

class BaiduTranslator {
	private $apikey;			//百度的API key
	private $slang;				//需要翻译的语言代码
	private $tlang;				//目标语言代码
	private $apiurl;			//百度的翻译API的url
	private $content=array();	//需要翻译的内容
	public $error;				//翻译成功与否的错误代码

	public function __construct($key="T93TH0Y1x5262LZSkHr5tLZx",$apiurl="http://openapi.baidu.com/public/2.0/bmt/translate",$slang="auto",$tlang="auto"){
		$this->apikey=$key;
		$this->apiurl=$apiurl;
		$this->slang=$slang;
		$this->tlang=$tlang;
		$this->error=0;
	}

	public function setlang($slang,$tlang){
		$this->slang=$slang;
		$this->tlang=$tlang;
	}

	public function setContent($str){
		$this->error=403;
		$str=str_replace("\n"," ",trim($str));
		$str=html_entity_decode($str,ENT_NOQUOTES);
		$str=preg_replace("/\s{2,}/i"," ",$str);
		$this->content=array_filter(preg_split("/(?<=<\/p>)/i",$str));
	}

	public function getResult(){
		$trans_result="";
		foreach($this->content as $part){
			$part=urlencode(trim($part));
			$result=file_get_contents($this->apiurl."?client_id=$this->apikey"."&q=".$part."&from=".$this->slang."&to=".$this->tlang);
			$re_arr=json_decode($result,true);
			if(!$re_arr||array_key_exists("error_msg",$re_arr)){
				//如果翻译失败，则返回并且往contents里写入没有翻译的内容
				$filename=Croot.'translate/1.txt';
				file_put_contents($filename,urldecode($part),FILE_APPEND);

				$this->error=($re_arr["error_code"])?$re_arr["error_code"]:403;
				return "translate error:".$re_arr["error_msg"];
			}else{
				//如果不存在翻译错误字段，则翻译成功，并返回翻译原文
				$this->error=0;
				$trans_result.=$re_arr["trans_result"][0]["dst"];
			}
			usleep(rand(2,100));
		}
		return $trans_result;
	}
}
?>
