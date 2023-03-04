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
上下不要有文本，一个短代码独占一行

```
[foo name="var"]content[/foo]
[foo name="var" /]
```
* 现已支持的组件

| 短代码  | 说明                 | 用法                                                         |
| ------- | -------------------- | ------------------------------------------------------------ |
| tc-vue  | `见下方详细说明`     | `见下方介绍`                                                 |
| tc-rate | 评分组件。用于评分。 | [用法](https://element-plus.gitee.io/zh-CN/component/rate.html) |
|         |                      |                                                              |

* tc-vue

> 每篇需要使用短代码的文章必须，放在文章最后。可在此定义变量。

**举例**

如果要显示一个三颗星星的评分组件。

```bash
[tc-rate v-model="value" disabled disabled show-score score-template="{value}" /]
[tc-vue value=3 /]
```

**注意**

* 变量定义不要重名哦！

## 二次开发

**短代码开发**

直接在 Codes.php 文件下写短代码相关功能即可

**添加支持**

在 InitVueShortCode.php 文件下写修改相关代码即可
