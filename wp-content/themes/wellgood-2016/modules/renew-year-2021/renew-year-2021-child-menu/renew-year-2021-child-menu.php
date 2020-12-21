<?php if(sizeof($children) > 1): ?>
  <div class="sticky top-header-sm header:top-header-lg renew-year-2021-child-menu py-e10 ml:py-e18 z-50" data-module-init="renew-year-2021-child-menu">
    <div class="container">
     <span class="text-tag">Explore By Pillar: </span><select class="renew-year-2021-child-menu__select text-tag">
        <?php foreach ($children as $child): ?>
          <option <?= $child->ID == $post->ID ? 'selected':''?> value="<?= get_the_permalink($child->ID) ?>">
            <?=$child->post_title;?>
          </option>
        <?php endforeach;?>
      </select>
    </div>
  </div>
<?php endif; ?>