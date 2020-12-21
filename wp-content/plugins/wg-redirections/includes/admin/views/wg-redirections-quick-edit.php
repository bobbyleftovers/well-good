<?php
    $possible_types = $this->get_possible_types();
    $possible_responses = $this->get_possible_responses();
?>

<table style="display: none">
        <tbody id="inlineedit">
        <tr id="bulk-quickview-edit-hidden" class="hidden"></tr>
        <tr id="bulk-quickview-edit" class="inline-edit-row inline-edit-row-post quick-edit-row quick-edit-row-post inline-edit-post inline-editor">
            <td colspan="<?php echo $this->get_column_count(); ?>" class="colspanchange">
                <form class="js-quick-edit-form" method="post">

                    <input type="hidden" name="redirection_id" class="js-redirection-id" value="0">
                    <input type="hidden" name="wg_redirections_nonce" value="<?=wp_create_nonce('wg_redirections_nonce')?>">
                    <input type="hidden" name="action" value="wg_redirections_edit_redirection">
                    <input type="hidden" name="skip_options" value="1">

                    <fieldset class="inline-edit-col-left" style="width: 40%">
                        <legend class="inline-edit-legend"><?php echo __( 'Quick Edit', 'wg-redirections'); ?></legend>
                        <div class="inline-edit-col">
                            <label class="inline-edit-group wp-clearfix">
                                <span class="title"><?php _e( 'Source Uri', 'wg-redirections'); ?></span>
                                <span class="input-text-wrap" style="margin-left: 9em;"><input type="text" name="source_uri" class="js-source-uri" value="" required="required"/></span>
                            </label>
                            <label class="inline-edit-group wp-clearfix">
                                <span class="title"><?php _e( 'Target Uri', 'wg-redirections'); ?></span>
                                <span class="input-text-wrap" style="margin-left: 9em;"><input type="text" name="target_uri" class="js-target-uri" value="" required="required"/></span>
                            </label>
                            <br class="clear" />
                        </div>
                    </fieldset>

                    <fieldset class="inline-edit-col-right" style="width: 60%">
                        <legend class="inline-edit-legend">&nbsp&nbsp</legend>
                        <div class="inline-edit-col">

                            <label class="inline-edit-group wp-clearfix">
                                <span class="title"><?php _e( 'Http Response', 'wg-redirections'); ?></span>
                                <span class="input-text-wrap" style="margin-left: 9em;">

                                <select name="http_response" class="js-http-response" required="required">
                                    <?php foreach ($possible_responses as $response): ?>
                                        <option value="<?= $response['value'] ?>"><?= $response['label'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                                </span>
                            </label>
                            <label class="inline-edit-group wp-clearfix">
                                <span class="title"><?php _e( 'Type', 'wg-redirections'); ?></span>
                                <span class="input-text-wrap" style="margin-left: 9em;">
                                    <select name="type" class="js-type">
                                        <option value=""><?php echo __( 'None', 'wg_redirections' ); ?></option>
                                        <?php foreach ($possible_types as $type): ?>
                                            <option value="<?= $type['value'] ?>"><?= $type['label'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </span>
                            </label>
                            <div class="inline-edit-group wp-clearfix">
                                <label class="alignleft">
                                    <input type="checkbox" name="is_active" value="1" class="js-is-active">
                                    <span class="checkbox-title"><?php _e('Is Active', 'wg-redirections'); ?></span>
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    <div class="submit inline-edit-save">
                        <button type="button" class="button cancel alignleft js-quick-edit-cancel"><?php _e( 'Cancel', 'wg-redirections' ); ?></button>
                        <button type="submit" class="button button-primary save alignright js-quick-edit-update"><?php _e( 'Update', 'wg-redirections'); ?></button>
                        <span class="spinner js-spinner"></span>

                        <br class="clear" />
                        <div class="notice notice-error notice-alt inline hidden">
                            <p class="error"></p>
                        </div>
                    </div>
                </form>
            </td>
        </tr>
        </tbody>
    </table>