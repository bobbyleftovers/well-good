<?php if(!$intro || $intro == '') return; ?>
<?php $has_border = true; if(isset($modules[0]) && $modules[0]['acf_fc_layout'] === 'youtube_channel') $has_border = false; ?>

<div data-module-init="rich-tag-intro">
  <div class="container mt-e22 sm:mt-e50 md:mt-e65 lg:mt-e85">
    <div class="flex flex-wrap -mx-gutter1/2" sty>
      <div class="ml:order-2 w-full ml:w-1/3 px-gutter1/2 block sm:hidden ml:block">
        <div class="w-full ml:sticky ml:top-e120 header:top-e100 z-20">
            <?php brrl_the_module('rich-tag/rich-tag-anchor-menu',$modules); ?>
        </div>
      </div>
      <div class="w-full ml:w-2/3 px-gutter1/2 mt-e30 sm:mt-0">
        <div class="<?php if($has_border): ?>border-b-1 border-tan-medium<?php endif;?> sm:pb-e38">
          <div class="relative" style="min-height:80px;">
            <?php brrl_the_module('main-2020/drop-cap',
              array(
                'text'=>$intro, 
                'class' => 'text-gray'
              )); ?>
          </div>
          <div class="_hidden sm:block ml:hidden mt-e38">
            <?php brrl_the_module('rich-tag/rich-tag-anchor-menu',$modules); ?>
          </div>
          <?php brrl_the_module('main-2020/trending-posts-app', array(
            'class'   => 'mt-e40 lg:mt-e38 ml:mt-e50 sm:pb-0',
            'mount'   => false,
            'tag'=>   $tag ?? $name,
            'title'=>  'Most Popular'
            )); ?>
        </div>
      </div>
    </div>
  </div>
</div>
