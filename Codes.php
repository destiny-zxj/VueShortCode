<?php
// 以下是自定义`短代码`
/**
 * [foo value="123" data=321]content[/foo]
 * $name `foo`
 * $attr ` value="123" data=321`
 * $text `content`
 * $code `[foo value="123" data=321]content[/foo]`
 */

ShortCode::set('tc-rate', function ($name,$attr,$text,$code) 
{
    return "<el-rate $attr/>";
});