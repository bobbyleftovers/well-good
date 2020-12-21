<?php


function get_top_category_tags($category_ID, $total = 5) {

      $cached_category_tags = array();

      if(isset($category_ID) && !empty($category_ID)) {

        // Check cached results
        $cached_category_tags = wp_cache_get($category_ID, 'get_top_category_tags', true);

        // Check if cache expired
        if($cached_category_tags === false){

          $all_tags = array();
          $tags_by_category = new WP_Query('posts_per_page=100&post_type=post&post_status=publish&orderby=date&cat='.$category_ID.'');
          if ($tags_by_category->have_posts()) :
              while ($tags_by_category->have_posts()) : $tags_by_category->the_post();
                  $posttags = get_the_tags();
                  if ($posttags) {
                      foreach($posttags as $tag) {
                          $all_tags[] = $tag->term_id;
                      }
                  }
              endwhile;
              wp_reset_postdata();
          endif;

          $tags_arr = array_unique($all_tags);
          $tags_str = implode(",", $tags_arr);

          $cached_category_tags = wp_tag_cloud( array(
              'taxonomy'  => 'post_tag',
              'smallest'  => 11,
              'largest'   => 11,
              'unit'      => 'pt',
              'number'    => $total,
              'format'    => 'flat',
              'separator' => ", ",
              'orderby'   => 'count',
              'order'     => 'DESC',
              'exclude'   => null,
              'link'      => 'view',
              'echo'      => false,
              'include'   => $tags_str
          ) );

          // Cache new results every hour
          wp_cache_set($category_ID, $cached_category_tags, 'get_top_category_tags', 86400);
        }
      }

    return $cached_category_tags;
}
