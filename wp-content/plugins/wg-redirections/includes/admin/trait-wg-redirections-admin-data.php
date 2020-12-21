<?php

/**
 * Admin Menu
 */
trait WG_Redirections_Admin_Data {

    use WG_Redirections_Core;

    /**
     * Text labels
     */
    public function get_data_label( $key ) {

        $labels = array(
            'skip_amp_on_redirect' => __( 'Skip AMP on redirection', 'wg-redirections' ),
            'remove_query_on_redirect' => __( 'Remove query string on redirection', 'wg-redirections' )
        );

        return $labels[ $key ];
    }

    /**
     * Get record redirection
     * @param $redirection_id
     * @return array|object|void|null
     */
    public function get_redirection( $redirection_id ) {

        $row = $this->get_sql_row( $redirection_id );

        if($row['options']) $row['options'] = wp_parse_args( unserialize($row['options']), $this->get_default_row()['options'] );
        else $row['options'] = $this->get_default_row()['options'];

        return $row;
    }

    /**
    * Insert or Edit Redirection
    * @param array $args
    * @return bool|int
    */
    public function save_redirection( $args = array(), $props = array()) {
        global $wpdb;

        // Format paths
        $args['source_uri'] = $this->get_formatted_path($args['source_uri']);
        if(!isset($args['type']) || $args['type'] !== 'external'){
            $args['target_uri'] = $this->get_formatted_path($args['target_uri']);
        } else {
            $args['target_uri'] = $this->build_target($args);
        }

        // Check if already exists
        $already_exists = $this->get_sql_row( $args['source_uri'], 'source_uri' );
        if(!$this->admin_page) $this->admin_page = get_home_url().'/wp-admin/?page='.$this->plugin_name;
        if($already_exists && (!isset($args['id']) || (int) $args['id'] !== (int) $already_exists['id'])) {
            $error = new WP_Error();
            $error->add( 'already_exists', __( "Error! There's already a redirection in place for <i>".$already_exists['source_uri']."</i>. <a href='".$this->admin_page.'&action=edit&id='.$already_exists['id']."'>Edit it here</a>", $this->plugin_name ));
            return $error;
        }

        //Fix options
        $skip_options = $props['skip_options'];
        if($skip_options && $already_exists){
            $args['options'] = unserialize($already_exists['options']);
        } else if(!$skip_options && $already_exists) {
            foreach($this->get_default_row()['options'] as $key => $option){
                if(!isset($args['options'][$key])) $args['options'][$key] = 0;
            }
            $args['options'] = array_merge(unserialize($already_exists['options']), $args['options']);
        } else if ($skip_options && !$already_exists){
            $args['options'] = array();
        }
        $args['options'] = $args['options'] ?? array();

        // Fix values that are not on quick edit
        if($already_exists &&  $skip_options){
            $args['add_to_sitemap'] = $already_exists['add_to_sitemap'];
        }

        //Parse args
        $args['options'] = wp_parse_args( $args['options'] );
        $args = wp_parse_args( $args );

        $table_name = $this->get_table_name();

        // remove row id to determine if new or update
        $row_id = (int) $args['id'];
        unset( $args['id'] );

        $args['is_active'] = (int) ($args['is_active'] === '' ? 0 : $args['is_active']);
        $args['add_to_sitemap'] = (int) ($args['add_to_sitemap'] === '' ? 0 : $args['add_to_sitemap']);

        $args['options'] = serialize($args['options']);
        if ( ! $row_id ) {
          $args['edit_time'] = current_time( 'mysql' );
          if ( $wpdb->insert( $table_name, $args ) ) {
            return $wpdb->insert_id;
          }
        } else {
            if ( $wpdb->update( $table_name, $args, array( 'id' => $row_id ) ) !== false ) {
              return $row_id;
            }
        }
        return false;
    }

    /**
    * Handle form
    */
    public function handle_form() {

        if ( ! isset( $_POST['submit_redirection'] ) && !wp_doing_ajax() ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], '' ) && ! wp_verify_nonce( $_POST['_wpnonce'], 'wg_redirections_nonce' ) && ! wp_verify_nonce($_POST['wg_redirections_nonce'], 'wg_redirections_nonce')) {
            wp_die( __( 'Are you cheating?', $this->plugin_name ) );
        }

        if ( ! current_user_can( 'read' ) ) {
            wp_die( __( 'Permission Denied!', $this->plugin_name ) );
        }

        $field_id = isset( $_POST['redirection_id'] ) ? intval( $_POST['redirection_id'] ) : ( isset( $_POST['field_id'] ) ? intval( $_POST['field_id'] ) : 0 );

        if(isset($_POST['redirect_from'])) $_POST['source_uri'] = $_POST['redirect_from'];
        if(isset($_POST['redirect_to'])) $_POST['target_uri'] = $_POST['redirect_to'];
     
        $source_uri = isset( $_POST['source_uri'] ) ? sanitize_text_field( $_POST['source_uri'] ) : '';
        $target_uri = isset( $_POST['target_uri'] ) ? sanitize_text_field( $_POST['target_uri'] ) : '/';
        $skip_options = isset( $_POST['skip_options'] ) ? $_POST['skip_options'] : 0;
        $skip_options = !(!$skip_options || $skip_options === 0 || $skip_options === "0");
        $http_response = isset( $_POST['http_response'] ) ? sanitize_text_field( $_POST['http_response'] ) : 301;
        if($http_response === '') $http_response = 301;
        $is_active = isset( $_POST['is_active'] ) ? sanitize_text_field( $_POST['is_active'] ) : 0;
        $add_to_sitemap = isset( $_POST['add_to_sitemap'] ) ? sanitize_text_field( $_POST['add_to_sitemap'] ) : 0;
        $type = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';
        switch($type){
            case 'post':
                $type = 'post:post';
            break;
            case 'page':
                $type = 'post:page';
            break;
            case 'category':
                $type = 'taxonomy:category';
            break;
            case 'tag':
                $type = 'taxonomy:tag';
            break;
        }
        $options = array();
        if(!$skip_options && $skip_options !== 0 && $skip_options !== "0"){
            if(isset( $_POST['options']) && is_array($_POST['options'])){
                $options = $_POST['options'];
            } else {
                foreach($this->get_default_row()['options'] as $key => $option){
                    if(isset( $_POST[$key] )){
                        $options[$key] = $_POST[$key];
                    }
                }
            }
        }

        $errors = new WP_Error();

        // Validation field input
        if ( ! $source_uri ) {
            $errors->add( 'required', __( '"Redirect from" field is required', $this->plugin_name ));
        }

        if ( ! $http_response ) {
            $errors->add( 'required', __( '"HTTP Response" field is required', $this->plugin_name ));
        }

        $fields = array(
            'id' => $field_id,
            'source_uri' => $source_uri,
            'target_uri' => $target_uri,
            'http_response' => $http_response,
            'type' => $type,
            'is_active' => $is_active,
            'options' => $options,
            'add_to_sitemap' => $add_to_sitemap
        );

        if ( $fields['source_uri'] ===$fields['target_uri'] ) {
            $errors->add( 'invalid', __( 'Error: Redirection target url has to be different from the source url', $this->plugin_name ));
        }

        // Bail out if error found
        if (  $errors->has_errors()  ) {
            return $this->response(  $errors,  $_POST );
        }

        // Insert or edit redirection
        return $this->response( $this->save_redirection( $fields, array(
            'skip_options' => $skip_options
        ) ), $fields );
    }

    /**
    * After data is saved
    */
    public function response( $response, $data = null ){

        if(wp_doing_ajax()){
            return array( $response, $data );
        }

        // Notification
        if ( is_wp_error( $response ) ) {
            $errors = $response->get_error_messages();
            return WG_Redirections_Admin_Notices::add(array( 'error' => $errors[0] ));
        } else {
            $item_page = $this->admin_page.'&action=edit&id='.$response;
            $path = $data['source_uri'];
            $link = get_home_url().$data['source_uri'];
            $message = __('Redirection for <i style="opacity: 0.7">'.$path.'</i> %1$s. &nbsp&nbsp<a href="'.$item_page.'">Edit</a>&nbsp&nbsp&nbsp<a href="'.$link.'" target="_blank">Test url</a>', $this->plugin_name);

            if ( ! $data['id'] ) {
                $message = sprintf(
                    $message,
                    'added'
                );
            } else {
                $message =   sprintf(
                $message,
                    'has been updated'
                );
            }
            WG_Redirections_Admin_Notices::add( array( 'message' => $message ));
        }

        wp_redirect( $this->admin_page, 302, 'WG Redirections plugin' );
        exit();
    }


    /**
    * Get types
    */
    public function get_possible_types(){
        return array(
            array(
                'value' => 'post:post',
                'label' => __( 'Post', $this->plugin_name )
            ),
            array(
                'value' => 'post:page',
                'label' => __( 'Page', $this->plugin_name )
            ),
            array(
                'value' => 'taxonomy:category',
                'label' => __( 'Category', $this->plugin_name )
            ),
            array(
                'value' => 'taxonomy:tag',
                'label' => __( 'Tag', $this->plugin_name )
            ),
            array(
                'value' => 'external',
                'label' => __( 'External Target', $this->plugin_name )
            )
        );
    }

    /**
    * Get responses
    */
    public function get_possible_responses(){

        return array(
            array(
                'value' => 301,
                'label' => __( '301 Moved Permanently', $this->plugin_name )
            ),
            array(
                'value' => 308,
                'label' => __( '308 Permanent Redirect', $this->plugin_name )
            ),
            array(
                'value' => 302,
                'label' => __( '302 Found', $this->plugin_name )
            ),
            array(
                'value' => 303,
                'label' => __( '303 See Other', $this->plugin_name )
            ),
            array(
                'value' => 307,
                'label' => __( '307 Temporary Redirect', $this->plugin_name )
            ),
        );
    }
}