<?php
  $id = get_the_ID();
  $franchise = get_franchise( $id );

  if ( $franchise ) :
    $hide_franchise = get_field( 'override_franchise_display', $id );
    if ( ! $hide_franchise ) :
      $franchise_overrides = array(
        'description' => get_field( 'override_franchise_description', $id ),
        'logo' 				=> get_field( 'override_franchise_logo', $id ),
        'more_link' 	=> get_field( 'override_franchise_see_more_link', $id ),
        'sponsor'			=> get_field( 'override_franchise_sponsor', $id, false )
      );

      the_module( 'franchise-blurb', array(
        'franchise' => $franchise['id'],
        'overrides' => $franchise_overrides
      ) );
    endif;
  endif;
?>
