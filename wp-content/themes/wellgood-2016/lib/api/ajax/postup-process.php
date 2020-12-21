<?php
/**
 * Script to process PostUp signups async
 */

include( "../../../../../../wp-load.php" );
include( "postup.php" );
header( 'Content-type: application/json' );

//Make sure the function exists, gets posted to, gets an email, and gets an empty checksum field val
if( !function_exists( 'add_to_postup' ) || !isset( $_POST ) || empty( $_POST ) )
{
    echo json_encode( array( 'success' => false, 'message' => 'Please enter a valid email address' ) );
    exit;
}

if( isset( $_POST['email'] ) )
{

    //Make sure the email is actually valid
    if( empty( $_POST['email'] ) || !is_email( $_POST['email'] ) )
    {
        echo json_encode( array( 'success' => false, 'message' => 'Please enter a valid email address' ) );
        exit;
    }

    $template_id = 1; // maybe need this for importTemplateId?
    $send_template_id = 3;

    $args = array(
        'importTemplateId' => $template_id, // need to be dynamic?
        'sendTemplateId' => $send_template_id,
        'data' => array( $_POST['email'] )
    );

    //Add the user to the local email list BEFORE hitting postup in case there is another error like on 20-Mar-2014
    /*
    CREATE TABLE `wp_ebkp` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `common_email` varchar(220) DEFAULT '',
      `common_added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
    */
    // 2016 COMMENTED OUT 2014 LEGACY CUSTOM EMAIL TABLE
    /*global $wpdb;
    $email = esc_sql( trim( $_POST['email'] ) );
    $thetable = $wpdb->prefix . 'ebkp';
    $wpdb->query( "INSERT INTO `".$thetable."`
            (`common_email`, `common_added`)
        VALUES
            ('".$email."', NOW())" );*/

    $data = add_to_postup( $args );

    if( $data['status'] == 'error' )
    {
        echo json_encode( array( 'success' => false, 'message' => 'An error occurred, please try again later.', 'extra' => $data ) );
    }
    else
    {
        if( isset( $_POST['update_user'] ) && $_POST['update_user'] == 'true' )
        {
            $message = 'Your account has been updated successfully!';
        }
        else
        {
            $message = 'Got it, you\'ve been added to our email list.';
        }
        echo json_encode( array( 'success' => true, 'message' => $message, 'extra' => $data ) );
    }
    exit;
}


exit;
