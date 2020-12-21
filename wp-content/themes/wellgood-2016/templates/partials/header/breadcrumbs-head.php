<?php
$breadcrumbs = get_post_breadcrumbs( get_queried_object_id() );
?>

<?php if ( $breadcrumbs ) : ?>
  <script type="application/ld+json">
    <?= $breadcrumbs; ?>
  </script>
<?php endif; ?>