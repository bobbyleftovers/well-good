<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WG_Varnish
 * @subpackage WG_Varnish/includes/admin
 * @author     Barrel
 */
class WG_Varnish_Admin_Notices {

	public function purge_notices() {
		if(!apply_filters( 'wg_varnish_notices', true)) return;
		$notice = get_option( 'notice_wg_varnish' );
		if(!$notice) return;
		$home = get_home_url();
		$servers = $notice['purged_servers'];
		$urls = $notice['purged_urls'];
		delete_option( 'notice_wg_varnish' );
		?>
			<div class="updated">
			   <p>
			   Purged <strong><?=sizeof($urls)?></strong> urls.
			   	<?php if(apply_filters( 'wg_varnish_db_logs', true)):?>
				   See full logs <a href="/wp-admin/admin.php?page=wg-varnish_logs">here</a>
			   <?php endif /*
			   on <?=sizeof($servers)?> servers: <?php foreach($servers as $server): ?><span><?= $server ?></span> <?php  endforeach; ?>
			   <ul>
			   	<?php foreach($urls as $url): ?>
					<li><a href="<?=$home.$url?>" target="_blank"><span><?= $home.$url ?></span></a></li>
				<?php  endforeach; ?>
				</ul> */?>
			   </p>
			</div>
		<?php
	}
}
