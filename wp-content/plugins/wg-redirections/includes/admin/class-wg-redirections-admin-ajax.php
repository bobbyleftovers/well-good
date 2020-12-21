<?php

/**
 * Admin Ajax
 */
class WG_Redirections_Admin_Ajax {

    use WG_Redirections_Core, WG_Redirections_Admin_Data;

    /**
    * Insert Redirection via ajax
    */
    public function save_redirection_ajax( ) {
        $response_data = $this->handle_form();
        $response = $response_data[0];
        $data = $response_data[1];
        if( is_wp_error( $response ) ){
            $this->error( $response,  $data );
        } else {
            $this->success($response, $data);
        }
    }

    /**
    *  Maintanance action: flatten_target_urls
    */
    public function maintanance_flatten_target_urls(){
        $data = array();

        if($this->connect_mysqli()->connect_errno){
            $data = new WP_Error( 'cannot_connect_sql', __( 'Cannot connect SQL', 'wg-reddirections' ), array( 'status' => 500 ) );
        } else {
            $legacy_terms = array('good-advice', 'good-food', 'good-travel', 'good-home', 'good-looks', 'good-sweat');

            $i = 0;
            $like = '';
            $limit = 5;

            foreach($legacy_terms as $slug){
                if(!$i) $start = '';
                else $start = ' OR';
                $like .= "$start target_uri LIKE '/$slug/%' OR target_uri = '/$slug'";
                $i++;
            }

            $sql = "SELECT * FROM ".$this->get_table_name()." WHERE $like LIMIT $limit";
            $result = $this->mysqli->query($sql);

            if(!$result) {
                $data = new WP_Error( 'no_results_sql', __( 'Non unflattened URLs found', 'wg-reddirections' ), array( 'status' => 500 ) );
            } else if($result->num_rows === 0){
                $data['next'] = false;
            } else {
                $data['next'] = true;
                $data['logs'] = array();
                if ($_POST['data']['offset'] === '0') $data['logs'][] = 'Flattenning target URLs:';
                while ($row = $result->fetch_array()) {
                    $target_uri = $row['target_uri'];
                    foreach($legacy_terms as $slug){
                        $target_uri = str_replace("/$slug/", "/", $target_uri);
                        $target_uri = str_replace("/$slug", "/", $target_uri);
                    }
                    $sql = ' UPDATE '.$this->get_table_name().' SET target_uri="'.$target_uri.'" WHERE id="'.$row['id'].'"';
                    if($this->mysqli->query($sql)){
                        $data['logs'][] = $row['target_uri'].' → '.$target_uri;
                    } else {
                        $data = new WP_Error( 'error_sql', __( 'Error when updating the DB records', 'wg-reddirections' ), array( 'status' => 500 ) );
                    }
                }
            }
        }
        
        if( is_wp_error(  $data ) ){
            $this->error(  $data );
        } else {
            $this->success(200, $data);
        }
    }

    /**
    *  Add sitemap index page
    */
    public function build_sitemap( ) {
        $sitemap = $this->loader->load('Front/WG_Redirections_Sitemap');
        $sitemap->build_sitemap_ajax();
    }

    /**
    * Success
    */
    public function success( $response = 200, $data = array()){
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('response' => $response, 'data' => $data));
        wp_die();
    }

    /**
    * Error
    */
    public function error( $response = 500, $data = array() ){
        header('HTTP/1.1 500 Internal Server');
        header('Content-Type: application/json; charset=UTF-8');
        if(is_wp_error( $response )){
            $errors = $response->get_error_messages();
            $response = $errors[0];
        }
        $error_message = strip_tags(preg_replace('#<a.*?>.*?</a>#i', '', $response));
        echo json_encode(array('error' => $response, 'error_message' => $error_message, 'data' =>  $data));
        wp_die();
    }

}