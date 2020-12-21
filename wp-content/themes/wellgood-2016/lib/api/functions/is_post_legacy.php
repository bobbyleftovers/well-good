<?php
/**
 * Is Post Legacy
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 15.0.1
 */

function is_post_legacy( $post_id ) {
  $migration_threshold_date = get_field( 'hero_migration_threshold_date', 'options' );
  $hero_data = get_post_meta( $post_id, '_post_hero_data', true ) ?? array();
  $threshold_passed = new \DateTime( get_the_date( '', $post_id ) ) > new \DateTime( "$migration_threshold_date 23:59:59" );

  $is_legacy = ( ! $hero_data 
    || ! array_key_exists( 'template_name', $hero_data ) 
    || $hero_data['template_name'] === 'legacy'
    || $hero_data['template_name'] === 'standard' )
    && ! $threshold_passed;

  return $is_legacy;
}