<?php
/*
Plugin Name: Sync Remote Images
Plugin URI: https://github.com/fanshengshuai/wp-sync-remote-images.git
Description: WordPress plugin for downloading automatically remote images when you post a article
Version: 0.01
Author: Fanshengshuai
Author URI: https://github.com/fanshengshuai
License: MIT
License URI: https://github.com/fanshengshuai/wp-sync-remote-images/blob/master/LICENSE
Text Domain: sync-remote-images
Domain Path: /languages
Disclaimer: Please do not use this plugin to violate copyrights. Don't be evil.
 */

// 需要用到这个文件
require_once ABSPATH . "wp-admin" . '/includes/image.php';

add_action('save_post', 'f_sync_remote_images');

function f_sync_remote_images($postID)
{
    // 自动保存操作 or 用户没有编辑权限 退出
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || (!current_user_can('edit_post', $postID))) {
        return;
    }

    remove_action('save_post', 'f_sync_remote_images');

    $post = get_post($postID);

    // 提取所有远程图片
    if (!preg_match_all('/<img.+src=[\'"]((http[s]*)[^\'"]+)[\'"].*>/i', $post->post_content, $matches)) {
        return;
    }

    $content = $post->post_content;

    foreach ($matches[1] as $key => $imgUrl) {

        if (strpos($imgUrl, $_SERVER['HTTP_HOST']) > 0) {
            continue;
        }

        $dataGet = wp_remote_get($imgUrl);

        if (is_wp_error($request)) {
            continue;
        }

        $imgInfo = parse_url($imgUrl);
        $imgFileName = basename($imgInfo['path']);
        $uploadFile = wp_upload_bits($imgFileName, null, wp_remote_retrieve_body($dataGet));

        $attach_id = wp_insert_attachment(array(
            'post_title' => $imgFileName,
            'post_mime_type' => wp_remote_retrieve_header($dataGet, 'content-type'),
        ), $uploadFile['file'], $postID);

        $attachment_data = wp_generate_attachment_metadata($attach_id, $uploadFile['file']);

        wp_update_attachment_metadata($attach_id, $attachment_data);

        // 设置第一张为特色图
        if ($key == 0 && !get_the_post_thumbnail()) {
            set_post_thumbnail($postID, $attach_id);
        }

        $content = str_replace($imgUrl, $uploadFile['url'], $content);
    }

    wp_update_post(array('ID' => $postID, 'post_content' => $content));

    add_action('save_post', 'f_sync_remote_images');
}
