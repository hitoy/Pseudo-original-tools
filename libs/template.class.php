<?php
/****************************************
 * 模板插件，用来利用模板保存为html文档 *
 ***************************************/

class Template{
	private $templatedir;
	private $compiledir;
	private $tpl_vars=array();

	public function __construct($templatedir="templates/",$compiledir="data/templates_compiledir/"){
		$this->templatedir=Croot.$templatedir;
		$this->compiledir=Croot.$compiledir;
	}
	//分配变量
	public function assign($k,$v=NULL){
		if(!$k==""){
			$this->tpl_vars[$k]=$v;
		}
	}
	//魔术方法获取私有变量tpl_vars key值
	public function __get($k){
		if(array_key_exists($k,$this->tpl_vars)){
			return $this->tpl_vars[$k];
		}else{
			return "";
		}
	}

	//把含有include的模板合并
	private function include_combine($str){
		//先处理含有include的信息
		if(preg_match_all("/([\s\S]*){hito:include=[\s\"\']+([\w\d\.]+)[\s\"\']+}([\s\S]*)/i",$str,$matchs)){
			$str=$matchs[1][0].file_get_contents($this->templatedir.$matchs[2][0]).$matchs[3][0];
			return $this->include_combine($str);
		}else{
			return $str;
			break;
		}
	}

	//标签替换，把标签替换成系统自动标签
	private function tag_replace($str){
		//普通标签替换
		$str=preg_replace("/{hito:\s*(\w+)\s*}/i","<?php echo \$this->$1;?>",$str);
		//foreach标签替换
		$str=preg_replace("/({foreach[\s]+name=[\"\'\s]+([\w\d]+)[\"\'\s]+})/i","<?php foreach(\$this->$2 as \$key=>\$value){ ?>",$str);
		$str=preg_replace("/{([\w]+)\[(\w+)\]}/i","<?php echo \$$2;?>",$str);
		$str=str_replace("{endforeach}","<?php }?>",$str);
		return $str;
	}

	//编译和保存文件
	public function save_html($dir){
		$tpl=$this->templatedir."main.html";
		$com=$this->compiledir."main.php";
		//当不存在模板文件时，返回
		if(!file_exists($tpl)){
			return false;
		}
		//判断什么时候需要编译文件
		if(!file_exists($com)||filemtime($tpl)>filemtime($com)){
			$tpl_content=file_get_contents($tpl);
			$tpl_content=$this->tag_replace($this->include_combine($tpl_content));
			file_put_contents($com,$tpl_content);
		}
		ob_start();
		include $com;
		$html=ob_get_contents();
		ob_clean();
		//获取要保存文件的名称
		$filename=trim(file_get_contents(dirname($this->compiledir)."/filename.txt"))+1;
		//把数字html文件名存入以便下次使用
		file_put_contents(dirname($this->compiledir)."/filename.txt",$filename,LOCK_EX);
		//把实际html内容存入文档
		file_put_contents(Croot.$dir."/".$filename.".html",$html);
	}

	//展示编译的文件,参数为模板的名称，不包含后缀!
	public function display($temname){
		$tpl=$this->templatedir.$temname.".html";
		$com=$this->compiledir.$temname.".php";
		//当不存在模板文件时，返回
		if(!file_exists($tpl)){
			return false;
		}
		//判断什么时候需要编译文件
		if(!file_exists($com)||filemtime($tpl)>filemtime($com)){
			$tpl_content=file_get_contents($tpl);
			$tpl_content=$this->tag_replace($this->include_combine($tpl_content));
			file_put_contents($com,$tpl_content);
		}
		include $com;
	}
}
?>
