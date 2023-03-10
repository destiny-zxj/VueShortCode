<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * Vue Short Code 短代码
 * 
 * @package VueShortCode 
 * @author destiny_zxj
 * @version 0.0.1
 * @link https://tech-cub.cn
 */
class VueShortCode_Plugin implements Typecho_Plugin_Interface
{
	/**
	 * 激活插件方法,如果激活失败,直接抛出异常
	 * 
	 * @access public
	 * @return void
	 * @throws Typecho_Plugin_Exception
	 */
	public static function activate(){
        Typecho_Plugin::factory('admin/common.php')->begin = [__Class__, 'init'];
		Typecho_Plugin::factory('Widget_Archive')->handleInit = [__Class__, 'init'];
	}

	/**
	 * 禁用插件方法,如果禁用失败,直接抛出异常
	 * 
	 * @static
	 * @access public
	 * @return void
	 * @throws Typecho_Plugin_Exception
	 */
	public static function deactivate(){}

	/**
	 * 获取插件配置面板
	 * 
	 * @access public
	 * @param Typecho_Widget_Helper_Form $form 配置面板
	 * @return void
	 */
	public static function config(Typecho_Widget_Helper_Form $form)
	{
		$name = new Typecho_Widget_Helper_Form_Element_Text('point', NULL, 'article.post .post-content', _t('vue 挂载点'));
    	$form->addInput($name);
	}

	/**
	 * 个人用户的配置面板
	 * 
	 * @access public
	 * @param Typecho_Widget_Helper_Form $form
	 * @return void
	 */
	public static function personalConfig(Typecho_Widget_Helper_Form $form){}

	/**
	 * 插件初始化
	 *
	 * @access public
	 * @return void
	 */
	public static function init(){
		require_once 'libs/ShortCode.php';
		require_once 'InitVueShortCode.php';
	}
}
