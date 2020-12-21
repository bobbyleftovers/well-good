<?php
    $item = $this->get_default_row();
    $page = 'new';
    $redirection_id = (!empty($_GET['id'])) ? $_GET['id'] : false;
    if($redirection_id) {
        $page = 'edit';
        $current = $this->get_redirection( $redirection_id );
        $item['options'] = $item['options'] ?? array();
        $current['options'] = array_merge($item['options'], $current['options']);
        $options = (!empty($current['options'])) ? $current['options'] : array();
        $current['options'] = array_merge($item['options'], $options);
        $item = array_merge($item, $current);
    }
    $possible_types = $this->get_possible_types();
    $possible_responses = $this->get_possible_responses();
?>

<div class="wrap">
    <h1><?php _e( 'Edit redirection', 'wg_redirections' ); ?></h1>
    <form action="" method="post">

        <table class="form-table">
            <tbody>
                <tr class="row-redirect-from">
                    <th scope="row">
                        <label for="redirect_from"><?php _e( 'Redirect from', 'wg_redirections' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="redirect_from" id="redirect_from" class="regular-text" placeholder="<?php echo esc_attr( '', 'wg_redirections' ); ?>" value="<?php echo esc_attr( $item['source_uri'] ); ?>" required="required" />
                        <span class="description"><?php _e('Path url to redirect from', 'wg_redirections' ); ?></span>
                    </td>
                </tr>
                <tr class="row-redirect-to">
                    <th scope="row">
                        <label for="redirect_to"><?php _e( 'Redirect to', 'wg_redirections' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="redirect_to" id="redirect_to" class="regular-text" placeholder="<?php echo esc_attr( '', 'wg_redirections' ); ?>" value="<?php echo esc_attr( $item['target_uri'] ); ?>" />
                        <span class="description"><?php _e('Will redirect to this path', 'wg_redirections' ); ?></span>
                    </td>
                </tr>
                <tr class="row-http-response">
                    <th scope="row">
                        <label for="http_response"><?php _e( 'HTTP Response', 'wg_redirections' ); ?></label>
                    </th>
                    <td>
                        <select name="http_response" id="http_response" required="required">
                            <?php foreach ($possible_responses as $response): ?>
                                <option value="<?= $response['value'] ?>" <?php selected( $item['http_response'], $response['value'] ); ?>><?= $response['label'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr class="row-type">
                    <th scope="row">
                        <label for="type"><?php _e( 'Type', 'wg_redirections' ); ?></label>
                    </th>
                    <td>
                        <select name="type" id="type">
                            <option value="" <?php selected( $item['type'], null ); ?>><?php echo __( 'None', 'wg_redirections' ); ?></option>
                            <?php foreach ($possible_types as $type): ?>
                                <option value="<?= $type['value'] ?>" <?php selected( $item['type'], $type['value'] ); ?>><?= $type['label'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr class="row-is-active">
                    <th scope="row">
                        <?php _e( 'Active', 'wg_redirections' ); ?>
                    </th>
                    <td>
                        <label for="is_active"><input type="checkbox" name="is_active" id="is_active" value="1" <?php checked( $item['is_active'], true ); ?> /> <?php _e( 'Redirection is active', 'wg_redirections' ); ?></label>
                    </td>
                </tr>
                <tr class="row-options">
                    <th scope="row">
                        <?php _e( 'Options', 'wg_redirections' ); ?>
                    </th>
                    <td>
                        
                        <?php
                        foreach ($item['options'] as $key => $value){ ?>
                            <label for="options[<?=$key?>]">
                                <input type="checkbox" name="options[<?=$key?>]" id="options[<?=$key?>]" value="1" <?php checked( $value, true ); ?>/> 
                                <?= $this->get_data_label($key) ?>
                            </label><br><br>
                        <?php } ?>
                        <label for="add_to_sitemap">
                            <input type="checkbox" name="add_to_sitemap" id="add_to_sitemap" value="1" <?php checked( $item['add_to_sitemap'], true ); ?>/> 
                            <?= __('Add redirection to sitemap') ?>
                        </label><br><br>
                    </td>
                </tr>
             </tbody>
        </table>

        <?php if ($page === 'edit'): ?>
            <input type="hidden" name="field_id" value="<?php echo $item['id']; ?>">
        <?php endif; ?>

        <?php wp_nonce_field( '' ); ?>
        
        <?php if ($page === 'new'): 
                submit_button( __( 'Add new redirection', 'wg_redirections' ), 'primary', 'submit_redirection' ); 
            else:
                submit_button( __( 'Update redirection', 'wg_redirections' ), 'primary', 'submit_redirection' );
                ?>
                    <span class="submitbox">
                        <a class="submitdelete" href="/wp-admin/admin.php?page=wg-redirections&action=trash&ids[]=<?= $item['id'] ?>">
                            <?= __('Move to Trash', $this->plugin_name); ?>
                        </a>
                    </span>
                <?php
             endif; ?>

    </form>
</div>