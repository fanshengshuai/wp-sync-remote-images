=== Sync Remote Images  ===
Contributors: islacker
Donate link: http://ifxdaily.com/buy-me-a-coffee/
Tags: remote image
Requires at least: 5.0.2
Tested up to: 5.0.2
Stable tag: 5.0.2
Requires PHP: 5.2.4
License: MIT
License URI: https://github.com/fanshengshuai/wp-sync-remote-images/blob/master/LICENSE

这个一个能在发布文章时同步文章中的远程图片的插件。

== Description ==

在发布文章中，如果文章中引用了远程图片，那么之前的做法就是：下载这个远程图片，然后重新上传。
有了这个插件，你就可以省掉这些工作了，插件会在提交的时候，检查文章中引用的图片，如果是远程图片，就自动下载到附件目录，并保存到媒体库。
如果没有选择特色图片，会自动选择第一张下载的图片作为特色图片。

== Installation ==

安装后，启用即可。
本插件暂时没有任何设置项目，应该不不需要怎么设置。
本插件只下载图片，并不会对图片进行处理（如：缩略图，剪裁等。）
如果不想使用，只需要停用即可。


== Changelog ==

= 0.01 =
* 完成功能
