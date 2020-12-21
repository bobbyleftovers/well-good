<?php 

$class = $class ?? 'acf-2021-adverstisement';
$next = $next ?? false;
$prev = $prev ?? false;

if($next && $next['blockName']) $class = "$class next-".$next['blockName'];
if($prev && $prev['blockName']) $class = "$class prev-".$prev['blockName'];

if(isset($is_editor) && $is_editor){ ?>
  <div class="acf-advertisement container__ad--inline" data-ad-loaded="true">
    <section class="advertisement ad-position-inline">
      <div class="advertising-adslot">
        <div class="advertising-adslot__placeholder flex items-center justify-center text-default">
          <span class="opacity-25">Ad placeholder</span>
        </div>
      </div>
    </section>
  </div>
<?php } else {
  global $gutenberg_ad_index;

  if ( ! isset($gutenberg_ad_index) ) :
    $gutenberg_ad_index = 0;
  endif;
  
  if ($gutenberg_ad_index < 5) :
    the_module('advertisement', array(
      'class' => array(
        "acf-advertisement $class"
      ),
      'slots' => array(
        'inline',
        'slot'
      ),
      'page' => 0,
      'iteration' => $gutenberg_ad_index
    )); 
    $gutenberg_ad_index++; 
  endif;
}