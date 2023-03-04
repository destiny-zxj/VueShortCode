# Typecho VueShortCode 短代码插件
Typecho VueShortCode 是一款基于Vue3用于自定义短代码的Typecho插件

本插件基于 [ShortCode](https://github.com/moeshin/Typecho-Plugin-ShortCode) 开发

## 功能介绍
* 集成 Vue3，支持在写文章时直接使用 Vue3 语法
* 集成 Element-Plus，支持相关组件
## 使用方法
### Element-Plus 组件
* 模板
短代码属性和内容和 Element-Plus 一致，请参考 [Element-Plus](https://element-plus.gitee.io/zh-CN/component/button.html)

```
[foo name="var"]content[/foo]
[foo name="var" /]
```
## 二次开发
**短代码开发**
直接在 Codes.php 文件下写短代码相关功能即可

**添加支持**
在 InitVueShortCode.php 文件下写修改相关代码即可
