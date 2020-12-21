<?php
  $args['class'] = 'trends-2021-adverstisement';
?>
<!-- end .container -->
<?php if(!$is_parent): ?></div><?php endif; ?>

<!-- trends 2021 add slot -->
<?php brrl_the_module('acf/advertisement', $args); ?>

<!-- reset .container -->
<?php if(!$is_parent): ?><div class="trends-2021-container"><?php endif; ?>