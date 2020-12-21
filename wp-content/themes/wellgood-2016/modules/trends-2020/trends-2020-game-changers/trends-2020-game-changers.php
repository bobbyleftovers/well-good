<style>
body:not(.ie) .trends-2020-game-changers__slideshow:not(.flickity-enabled){
    opacity:0;
}
</style>

<div class="trends-2020-game-changers waypoint" data-module-init="trends-2020-game-changers">

  <!-- slideshow desktop -->
  <div class="trends-2020-game-changers__slideshow trends-2020-game-changers__slideshow--desktop carousel">

    <?php foreach($module['game_changers'] as $slide): if($slide['image']): ?>

      <div class="trends-2020-game-changers__slide trends-2020-game-changers__slide--desktop">
        <div class="trends-2020-game-changers__slide__img" data-image-bg="<?=$slide['image']['sizes']['medium']?>"></div>
        <div class="trends-2020-game-changers__slide__text h5 align-c">
            <?= $slide['name'] ?><br>
            <?= $slide['title'] ?>
        </div>
      </div>
      
    <?php endif; endforeach; ?>

  </div>


   <!-- slideshow mobile -->
   <div class="trends-2020-game-changers__slideshow trends-2020-game-changers__slideshow--mobile carousel">
      <?php
      $length = sizeof($module['game_changers']);
      foreach($module['game_changers'] as $key => $slide): if($slide['image']): ?>

      <?php if($key%4 === 0){ ?><div class="trends-2020-game-changers__slide--mobile"><?php } ?>
        <div class="trends-2020-game-changers__slide">
          <div class="trends-2020-game-changers__slide__img" data-image-bg="<?=$slide['image']['sizes']['medium']?>"></div>
          <div class="trends-2020-game-changers__slide__text h5 align-c">
              <?= $slide['name'] ?><br>
              <?= $slide['title'] ?>
          </div>
        </div>
      <?php if(($key+1)%4 === 0 ||  $key+1 === $length ){ ?></div><?php } ?>
      <?php endif; endforeach; ?>
  </div>

  <?php if ($module['game_changers_cta']):?>
      <!-- CTA button -->
  <div class="trends-2020-game-changers__cta align-c">
    <a class="btn filled trends-2020__btn" href="<?=$module['game_changers_cta']['url']?>" target="<?=$module['game_changers_cta']['target']?>"><?= $module['game_changers_cta']['title']?></a>
  </div>
  <?php endif; ?>
</div>