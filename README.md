# Typecho VueShortCode 短代码插件
Typecho VueShortCode 是一款基于Vue3用于自定义短代码的Typecho插件

本插件基于 [ShortCode](https://github.com/moeshin/Typecho-Plugin-ShortCode) 开发

## 功能介绍
* 集成 Vue3，支持在写文章时直接使用 Vue3 语法
* 集成 Element-Plus，支持相关组件

## 注意

* 由于安装 Handsome 主题导致各种错误

  1. 直接将 ShortCodeFull.php 文件复制到主题目录下

  2. 修改 ShortCodeFull.php 第 336 行的挂载点

```vue
app.mount("#post-content .entry-content"); -> app.mount("你的挂载点");
```

## 使用方法
### Element-Plus 组件
#### 模板
* 短代码属性和内容和 Element-Plus 一致，请参考 [Element-Plus](https://element-plus.gitee.io/zh-CN/component/button.html)
```bash
[foo name="var"]content[/foo]
[foo name="var" /]
```
* `块级元素` 上下不要有文本，一个短代码独占一行
* `行内元素` 可以在同一行
* 最后一个属性和 `/` 之间有空格
```bash
[foo name="var" /]  # 正确
[foo name="var"/]   # 错误
```

#### 现已支持的组件

| 短代码      | 说明   | 用法                                                         |
| ----------- | ------ | ------------------------------------------------------------ |
| tc-vue      | Vue    | `见下方介绍`                                                 |
| el-button   | 按钮   | [用法](https://element-plus.gitee.io/zh-CN/component/button.html) |
| el-iconfont | 图标   | `见下方介绍`                                                 |
| el-link     | 链接   | [用法](https://element-plus.gitee.io/zh-CN/component/link.html) |
| el-rate     | 评分   | [用法](https://element-plus.gitee.io/zh-CN/component/rate.html) |
| el-switch   | 开关   | [用法](https://element-plus.gitee.io/zh-CN/component/switch.html) |
| el-avatar   | 头像   | [用法](https://element-plus.gitee.io/zh-CN/component/avatar.html) |
| el-empty    | 空状态 | [用法](https://element-plus.gitee.io/zh-CN/component/empty.html) |
| el-image    | 图片   | [用法](https://element-plus.gitee.io/zh-CN/component/image.html) |
| el-progress | 进度条 | [用法](https://element-plus.gitee.io/zh-CN/component/progress.html) |
| el-result   | 结果   | [用法](https://element-plus.gitee.io/zh-CN/component/result.html) |
| el-skeleton | 骨架屏 | [用法](https://element-plus.gitee.io/zh-CN/component/skeleton.html) |
| el-tag      | 标签   | [用法](https://element-plus.gitee.io/zh-CN/component/tag.html) |
| el-alert    | 提示   | [用法](https://element-plus.gitee.io/zh-CN/component/alert.html) |
| el-divider  | 分割线 | [用法](https://element-plus.gitee.io/zh-CN/component/divider.html) |

#### tc-iconfont

> 引用阿里图标库

1. [阿里图标库](https://www.iconfont.cn/) 配置自己的图标，采用 `Font class` 格式，获取 `css` 链接；
2. 修改 `Codes.php` 第 `17` 行链接；
3. 用法

```bash
[tc-iconfont icon="icon-yes" size="20" color="#FF0000" /]
# icon 图标名称
# size 图标大小，不要加单位！如 `px`、`em` 等
# coloe 颜色，和 css `color` 属性一致
```

#### tc-vue

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
