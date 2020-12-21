<div class="trends-2020-trends-2020">

  <?php

  $length = sizeof($module['posts']);

  if($module['posts']):
    foreach ($module['posts'] as $key => $article):
    
    ?>
    <div class="trends-2020-trends-2020__article">
      <?php 
      $article['counter'] = $key + 1;
      $article['total_length'] = $length;

      if ($key > 0 && $key % 2 == 0 && ($length - $article['counter']) >= 1) { ?>
        <div class="trends-2020-trends-2020__add">
          <?php include_module('trends-2020/trends-2020-advertisement', $article); ?>
        </div>
      <?php }

      include_module('trends-2020/trends-2020-article', $article);
    ?>
    </div>
    <?php

    endforeach;
  endif;

  ?>

</div>