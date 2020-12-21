<?php
    $add_new_url = add_query_arg(
        array('page' => 'wg-redirections', 'action' => 'new'),
        admin_url('/admin.php')
    );
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e( 'Redirections', 'wg_redirections' ); ?></h1>
    <a href="<?php echo $add_new_url; ?>" class="page-title-action"><?php _e( 'Add New', 'wg_redirections' ); ?></a>
    <hr class="wp-header-end">
    <?php $table->views(); ?>
    <script type="text/javascript">jQuery( document ).ready(function() { jQuery("input[name='_wp_http_referer'], input[name='_wpnonce']").remove();});</script>
    <div id="nds-wp-list-table-demo">
        <div id="nds-post-body">
            <form id="wg-redirections-list-form" method="get">
                <input type="hidden" name="page" value="<?= $_REQUEST['page'] ?>" />
                <?php
                    $table->search_box('Find', 'wg-redirections-find');
                    $table->display();
                ?>
            </form>
        </div>
    </div>
</div>
<?php $table->inline_edit(); ?>