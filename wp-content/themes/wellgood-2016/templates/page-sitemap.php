<?php
@get_header();
?>

<div class="container">
  <h1><?= $this->get_page_title() ?></h1>
  <div>
    <?php foreach ($this->get_page_links() as $link){ ?>

      <a href="<?=$link['url']?>" target="_self"><?=$link['text']?></a><br>

    <?php }

    if(function_exists('wg_redirections_sitemap_index') && $this->page_id === -1) wg_redirections_sitemap_index();

    ?>
  </div>
</div>

<?php
@get_footer();
?>
