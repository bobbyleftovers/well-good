<?php
$args = isset($post->disclaimer_field) ? $post->disclaimer_field : array();

$disclaimers = array_key_exists('disclaimers', $args) ? $args['disclaimers'] : array();
$position = array_key_exists('position', $args) ? $args['position'] : '';
$class = array_key_exists('class', $args) ? $args['class'] : '';

$classes = array(
  "disclaimers__$position", 
  'mb-e18',
  'mt-e16',
  'pt-e16'
);
if ($class) :
  $classes[] = "disclaimers__$position--$class";
endif;

$disclaimers_data = array(
  'order' => array(
    'standard'
  ),
  'styles' => array(
    'standard' => array()
  )
);

if ( $position === 'after_content' ) :
  $disclaimers_data['order'][] = 'earmark';
  $disclaimers_data['styles']['earmark'] = array(
    'outline-earmark',
    'relative',
    'mt-e8',
    'pt-e10',
    'pl-e20'
  );
  $disclaimers_data['styles']['standard'] = array(
    'mt-e28',
    'pt-e45',
    'border-solid',
    'border-tan',
    'border-t-1'
  );
endif;
?>

<?php 
if ($disclaimers) : ?>
  <div class="<?= implode(' ', $classes); ?>">
    <?php
    $sorted_disclaimers = array();
    foreach ( $disclaimers as $disclaimer ) :
      if ( ! array_key_exists( $disclaimer['style'], $sorted_disclaimers ) ) :
        $sorted_disclaimers[$disclaimer['style']] = array();
      endif;

      $sorted_disclaimers[$disclaimer['style']][] = $disclaimer['text'];
    endforeach;
    
    foreach( $disclaimers_data['order'] as $disclaimer ) :
      if ( ! array_key_exists( $disclaimer, $sorted_disclaimers ) ) :
        continue;
      endif;

      $disclaimer_styles = array_key_exists( $disclaimer, $disclaimers_data['styles'] ) ? implode(' ', $disclaimers_data['styles'][$disclaimer] ) : '';
      ?>

      <div class="<?=$disclaimer_styles?>">
        <?php foreach( $sorted_disclaimers[$disclaimer] as $i => $disclaimer_content ) : 
          $content_classes = array(
            'text-default',
            'text-gray-light',
            'italic',
            'inline-block'
          );

          if ( count($sorted_disclaimers[$disclaimer]) !== 1 
            && ( $i + 1 ) !== count($sorted_disclaimers[$disclaimer]) ) :
            $content_classes[] = 'mb-e10';
          endif;
          ?>

          <div class="<?= implode( ' ', $content_classes ); ?>">
            <?= $disclaimer_content; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>