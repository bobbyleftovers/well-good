<?php

$title = $title ?? (is_object($post) ? $post->post_title : '');
$permalink = $permalink ?? (is_object($post) ? get_the_permalink($post) : '');
$id = $id ?? (is_object($post) ? $post->ID : '');

$links = array(
        'facebook' => array(
            'url' => '//www.facebook.com/sharer/sharer.php?u=' . urlencode( $permalink ),
            'icon' => 'icon-facebook',
            'name' => 'Facebook',
            'attrs' => ''
        ),
        'twitter' => array(
            'url' => '//twitter.com/share?text=' . wg_esc_url( $title . ' via ' . get_twitter_handle()) .'&amp;url=' . urlencode($permalink ),
            'icon' => 'icon-twitter',
            'name' => 'Twitter',
            'attrs' => ''
        ),
        'pinterest' => array(
            'url' => '//pinterest.com/pin/create/link/?url=' . urlencode($permalink) . '&amp;description=' . wg_esc_url($title) . '&amp;media=' . urlencode( get_the_post_thumbnail_url($id, 'medium') ),
            'icon' => 'icon-pinterest-p',
            'name' => 'Pinterest',
            'attrs' => ''
        ),
        'copy' => array(
            'url' => $permalink,
            'icon' => 'icon-link',
            'name' => 'Copy Link',
            'attrs' => "data-module-init='copy-clipboard' data-copy='$permalink' data-message-target='.post-card-share--network'"
        )
    );

?>

<ul class="post-card-share">
    <?php foreach($links as $handle => $link): ?>
	<li class="bg-seafoam-dark text-white font-sans text-uppercase mt-e5 py-e4 px-e8">
	    <a class="flex items-center justify-start text-white post-card-share--<?=$handle?>" <?=$link['attrs']?> target="_blank" href="<?= $link['url'] ?>">
	        <span class="post-card-share--icon mr-e5 <?=$link['icon']?>"></span>
	        <span class="post-card-share--network"><?=$link['name']?></span>
	    </a>
    </li>
    <?php endforeach; ?>
</ul>