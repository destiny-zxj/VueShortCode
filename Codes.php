<?php
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