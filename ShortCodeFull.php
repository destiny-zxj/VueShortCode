<?php
Typecho_Plugin::factory('Widget_Abstract_Contents')->content = ['ShortCode', 'content'];
Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = ['ShortCode', 'contentEx'];
class TOC{
	
	/**
	 * DOMDocument的实例
	 *
	 * @access private
	 * @var DOMDocument
	 */
	private static $dom = null;
	
	/**
	 * DOMXPath的实例
	 *
	 * @access private
	 * @var DOMXPath
	 */
	private static $xpath = null;
	
	/**
	 * 建立目录
	 * 
	 * @access public
	 * @param string
	 * @return string
	 */
	public static function build($content,$single){
		$html = '';
		if($single){
			$dom = self::$dom?self::$dom:(self::$dom = new DOMDocument());
			libxml_use_internal_errors(true);
			$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><body>'.$content.'</body>');
			libxml_use_internal_errors(false);
			
			if(self::$xpath){
				self::$xpath->__construct($dom);
				$xpath = self::$xpath;
			}else
				self::$xpath = $xpath = new DOMXPath($dom);
			$objs = $xpath->query('//h1|//h2|//h3|//h4|//h5|//h6');
			if (!$objs->length)
				return $content;
			$arr = [];
			$html = '<div class="TOC"><span>目录</span>';
			foreach($objs as $n => $obj){
				$obj->setAttribute('id','TOC'.$n);
				self::handle($obj,$n,$arr,$html);
			}
			foreach($arr as $n)
				$html .= '</li></ol>';
			$html .= '</div>';
			$content = self::html($xpath->document->getElementsByTagName('body')->item(0));
		}
		return preg_replace('#<p>\[toc\]</p>#i',$html,$content);
	}
	
	/**
	 * 处理目录
	 * 
	 * @param DOMElement $obj
	 * @param int $n
	 * @param array &$arr
	 * @param string &$html
	 * @return void
	 */
	public static function handle($obj,$n,&$arr,&$html){
		$i = str_replace('h','',$obj->tagName);
		$j = end($arr);
		if($i > $j){
			$arr[] = $i;
			$html .= '<ol>';
		}else if($i == $j)
			$html .= '</li>';
		else if(in_array($i,$arr)){
			$html .= '</li></ol>';
			array_pop($arr);
			self::handle($obj,$n,$arr,$html);
			return;
		}else{
			$arr = [$i];
			$html .= '</li>';
		}
		$html .= '<li><a href="#TOC'.$n.'">'.$obj->textContent.'</a>';
	}
	
	/**
	 * 获取DOMDocument的HTML
	 * 
	 * @param DOMElement $obj
	 * @return string
	 */
	public static function html($obj){
		$dom = self::$dom?self::$dom:(self::$dom = new DOMDocument());
		$html = '';
		foreach ($obj->childNodes as $child)
			$html .= $dom->saveHTML($child);
		return $html;
	}
}
class ShortCode{
	
	/**
	 * 已注册的短代码列表
	 *
	 * @access private
	 * @var array
	 */
	private static $ShortCodes = [];

	/**
	 * 实例
	 *
	 * @access private
	 * @var array
	 */
	private static $instance = null;

	/**
	 * 是否强制处理文本
	 *
	 * @access public
	 * @var bool
	 */
	public static $isForce = false;

	/**
	 * 注册短代码
	 *
	 * @access public
	 * @param mixed $names 短代码名称，可以一个字符串或字符串数组
	 * @param mixed $callbacks 短代码对应回调函数，可以一个回调函数或回调函数数组
	 * @param bool $overried 覆盖已注册的短代码<br>可选，默认<code>false</code>
	 * @return ShortCode
	 */
	public static function set($names,$callbacks,$overried = false){
		if(!is_array($names)) $names = [$names];
		if(!is_array($callbacks)) $callbacks = [$callbacks];
		$i = count($callbacks)-1;
		foreach($names as $j => $name){
			$k = $j;
			if($i<$j) $k = $i;
			$callback = $callbacks[$k];
			if(!array_key_exists($name,self::$ShortCodes)||$overried)
				self::$ShortCodes[$name] = $callback;
		}
		return self::instance();
	}

	/**
	 * 移除短代码
	 *
	 * @access public
	 * @param string $name 短代码名称
	 * @param callback $callback 只有回调函数相同，短代码才会被移除<br>可选，默认<code>Null</code>
	 * @return ShortCode
	 */
	public static function remove($name,$callback = null){
		if(isset(self::$ShortCodes[$name]))
			if(self::$ShortCodes[$name] === $callback||empty($callback))
				unset(self::$ShortCodes[$name]);
		return self::instance();
	}
	
	/**
	 * 移除所有短代码
	 *
	 * @access public
	 * @return ShortCode
	 */
	public static function removeAll(){
		self::$ShortCodes[] = [];
		return self::instance();
	}
	
	/**
	 * 获取短代码列表
	 * 
	 * @access public
	 * @return array
	 */
	public static function get(){
		return self::$ShortCodes;
	}

	/**
	 * 强制处理文本
	 * 使用此插件后Markdown或AutoP失效，使用此函数，并传入<code>true</code>值
	 * @access public
	 * @param bool
	 * @return bool
	 */
	public static function isForce($bool = null){
		if(is_bool($bool)) self::$isForce = $bool;
		return self::$isForce;
	}
	
	/**
	 * 文本处理
	 *
	 * @access public
	 * @param string
	 * @retur string
	 */
	public static function handle($content){
		$pattern  = [];
		$RegExp = '((?:"[^"]*"|'."'[^']*'|[^'".'"\]])*)';
		foreach(array_keys(self::$ShortCodes) as $name)
			array_push($pattern,
				"#\\\\\[|\[($name)$RegExp\]([\s\S]*?)\[/$name\]#i",
				"#\\\\\[|\[($name)$RegExp\]()#i"
			);
		return preg_replace_callback($pattern,function($a){
			if(count($a) == 1)
				return $a[0];
			$name = strtolower($a[1]);
			$ShortCodes = self::$ShortCodes;
			$callback = $ShortCodes[$name];
			if(array_key_exists($name,$ShortCodes)&&is_callable($callback))
				return call_user_func($callback, $name, $a[2], trim($a[3]), $a[0]);
			else
				return $a[0];
		},$content);
	}
	
	/**
	 * 插件处理 content
	 *
	 * @access public
	 * @param string
	 * @param Widget_Abstract_Contents
	 * @param string
	 * @return string
	 */
	public static function content($content,$archive,$last){
		if($last) $content = $last;
		$content = self::handle($content);
		if(Typecho_Plugin::export()['handles']['Widget_Abstract_Contents:content'] === [[__Class__,__Function__]]||self::$isForce)
			return $archive->isMarkdown?$archive->markdown($content):$archive->autoP($content);
		return $content;
	}
	
	/**
	 * 插件处理 contentEx
	 *
	 * @access public
	 * @param string
	 * @param Widget_Abstract_Contents
	 * @param string
	 * @return string
	 */
	public static function contentEx($content,$archive,$last){
		if($last) $content = $last;
		return TOC::build($content,$archive->is('single'));
	}
	
	/**
	 * 获取实例
	 * 
	 * @access private
	 * @return ShortCode
	 */
	private static function instance(){
		return self::$instance?self::$instance:new ShortCode();
	}
	
	/**
	 * 构造函数
	 *
	 * @access public
	 */
	public function __construct(){
		self::$instance = $this;
	}
	
}
?>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/element-plus/dist/index.css" />
<script src="//cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js"></script>
<script src="//cdn.jsdelivr.net/npm/element-plus"></script>
<script src="//cdn.jsdelivr.net/npm/@element-plus/icons-vue"></script>

<?php

/**
 * 获取某个属性值
 * @param string $attr 属性字符串
 * @param string $key 欲获得属性的键
 */
function get_value($attr, $key)
{
	$str = $attr;
	$start = strpos($str, $key);
	if (!$start) return;
	$str = substr($str, $start);
	$end = strpos($str, " ");
	$str = substr($str, 0, $end);
	# echo $str;
	$params = explode("=", $str);
	if (count($params) != 2) return;
	return str_replace('"', '', $params[1]);
}

/**
 * 属性字符串转换为键值对
 * @param string 属性字符串
 */
function get_vals($attr)
{
	$vals = array();
	$attr = trim($attr, "/");
	$attr = trim($attr);
	$attrs = explode(" ", $attr);
	foreach ($attrs as $item)
	{
		if (!strpos($item, "=")) continue;
		$params = explode("=", $item);
		$vals[$params[0]] = $params[1];
	}
	return $vals;
}

/**
 * 引入 vue 支持，必须在文章末尾引入
 *  可定义其他标签需要的变量
 */
ShortCode::set('tc-vue', function ($name,$attr,$text,$code) 
{
	$vals = get_vals($attr);
	$data = "";
	foreach ($vals as $key=>$val)
	{
		$data = $data."$key: $val,";
	}
	return '<script>const App = {data() {return {'.$data.'};}};const app = Vue.createApp(App);app.use(ElementPlus);app.mount("#post-content .entry-content");</script>';
});


// 以下是自定义`短代码`
/**
 * [foo value="123" data=321]content[/foo]
 * $name `foo`
 * $attr ` value="123" data=321`
 * $text `content`
 * $code `[foo value="123" data=321]content[/foo]`
 */
// el-button
ShortCode::set('tc-button', function ($name,$attr,$text,$code) 
{
    return "<el-button $attr>$text</el-button>";
});
?>
<!-- 阿里图标库 -->
<link rel="stylesheet" href="//at.alicdn.com/t/font_3418910_sjen7nmfd7h.css" />
<?php
// el-iconfont  阿里图标库
ShortCode::set('tc-iconfont', function ($name,$attr,$text,$code) 
{
    $icon = get_value($attr, "icon");
    $size = get_value($attr, "size");
    $color = get_value($attr, "color");
    return '<i class="iconfont '.$icon.'" style="font-size:'.$size.'px;color:'.$color.';"></i>';
});
// el-link
ShortCode::set('tc-link', function ($name,$attr,$text,$code) 
{
    return "<el-link $attr>$text</el-link>";
});
// el-rate
ShortCode::set('tc-rate', function ($name,$attr,$text,$code) 
{
    return "<el-rate $attr/>";
});
// el-switch
ShortCode::set('tc-switch', function ($name,$attr,$text,$code) 
{
    return "<el-switch $attr/>";
});
// el-avatar
ShortCode::set('tc-avatar', function ($name,$attr,$text,$code) 
{
    return "<el-avatar $attr/>";
});
// el-empty
ShortCode::set('tc-empty', function ($name,$attr,$text,$code) 
{
    return "<el-empty $attr/>";
});
// el-image
ShortCode::set('tc-image', function ($name,$attr,$text,$code) 
{
    return "<el-image $attr/>";
});
// el-progress
ShortCode::set('tc-progress', function ($name,$attr,$text,$code) 
{
    return "<el-progress $attr/>";
});
// el-result
ShortCode::set('tc-result', function ($name,$attr,$text,$code) 
{
    return "<el-result $attr/>";
});
// el-skeleton
ShortCode::set('tc-skeleton', function ($name,$attr,$text,$code) 
{
    return "<el-skeleton $attr/>";
});
// el-tag
ShortCode::set('tc-tag', function ($name,$attr,$text,$code) 
{
    return "<el-tag $attr>".$text."</el-tag>";
});
// el-alert
ShortCode::set('tc-alert', function ($name,$attr,$text,$code) 
{
    return "<el-alert $attr/>";
});
// el-divider
ShortCode::set('tc-divider', function ($name,$attr,$text,$code) 
{
    return "<el-divider $attr>".$text."</el-divider>";
});