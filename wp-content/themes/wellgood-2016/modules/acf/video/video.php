<div class="acf-video text-left relative">
  <?php brrl_the_module(
    'main-2020/video', 
    array(
      'oembed' => $oembed,
      'image' => $image,
      'width' => 1131,
      'height' => 672,
      'crop' => true,
      'class' => 'mb-e30'
    )); ?>
  <?php if($is_editor): ?>
    <button class="components-button components-notice__action is-secondary absolute top-e20 left-e15">Edit</button>
  <?php endif; ?>
</div>