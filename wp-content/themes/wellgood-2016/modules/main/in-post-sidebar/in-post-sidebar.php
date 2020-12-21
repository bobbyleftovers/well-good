<?php

global $post;
$content = isset($post->in_post_sidebar_field) ? $post->in_post_sidebar_field : '';

?>

<div class="in-post-sidebar"><?= $content ?></div>
