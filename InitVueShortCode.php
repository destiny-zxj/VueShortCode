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
	return '<script>const App = {data() {return {'.$data.'};}};const app = Vue.createApp(App);app.use(ElementPlus);app.mount("article.post .post-content");</script>';
});

require_once "Codes.php";

?>