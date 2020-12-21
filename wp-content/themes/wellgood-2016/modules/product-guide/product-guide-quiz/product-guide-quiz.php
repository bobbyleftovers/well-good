<?php
/**
 * Product guide quiz
 * @author BarrelNY
 */

$page_id = get_the_id();
$parent_id = get_the_parent_id();
$is_index = $page_id == $parent_id;

$back_to_site = get_field('product_guide_back_to_site', $parent_id);
$back_to_site_link = $back_to_site['product_guide_back_to_site_link'] == 'home' ? get_home_url() : $back_to_site['product_guide_back_to_site_url'];
$browse_link = $is_index ? 'class="product-guide-quiz__back modal__close--quiz"' : 'href="' . get_the_permalink( $parent_id ) . '" class="product-guide-quiz__back"';
$campaign_quiz = get_field('product_guide_campaign_quiz', $parent_id);
$campaign_quiz_title = $campaign_quiz['product_guide_campaign_quiz_title'];
$campaign_quiz_sponsor = $campaign_quiz['product_guide_campaign_quiz_sponsor'];
$campaign_quiz_sponsor_name = $campaign_quiz_sponsor['product_guide_campaign_quiz_sponsor_name'];
$campaign_quiz_sponsor_logo = $campaign_quiz_sponsor['product_guide_campaign_quiz_sponsor_logo'];
$campaign_quiz_sponsor_logo_light = $campaign_quiz_sponsor['product_guide_campaign_quiz_sponsor_logo_light'];
$campaign_quiz_sponsor_relationship = $campaign_quiz_sponsor['product_guide_campaign_quiz_sponsor_relationship'];

$questions = get_field('product_guide_questions', $parent_id);
$total_questions = count($questions);

$sponsor_logo = $campaign_quiz_sponsor_logo_light['url'] ? $campaign_quiz_sponsor_logo_light['url'] : $campaign_quiz_sponsor_logo['url'];
?>

<div class="product-guide-quiz loading" data-module-init="product-guide-quiz">
  <div class="product-guide-quiz__content">
    <div class="product-guide-quiz__close modal__close--quiz"></div>
    <div class="product-guide-quiz__content--top">
      <div class="product-guide-quiz__header">
        <div class="product-guide-quiz__header--logo">
          <a href="<?= $back_to_site_link; ?>">
            <h5>WELL+GOOD</h5>
          </a>
        </div>
      </div>
      <div class="product-guide-quiz__body">
        <div class="product-guide-quiz__body--legend"></div>
        <?php
        if ($questions) : ?>
          <form>
            <?php
            foreach($questions as $i => $question) :
              $text = $question['question_text'];
              $slug = str_replace(' ', '-', strtolower($text));
              $answer_type = $question['question_answer_type'];

              $answers = $question['question_answers'];
              $sorting = $question['question_sorting'];
              $option_type = $answer_type == 'single' ? 'radio' : 'checkbox';
              $current_question = $i + 1;
              ?>
              <div class="product-guide-quiz__question product-guide-quiz__slide" data-sorting="<?= $sorting; ?>" data-type="<?= $answer_type; ?>">
                <div class="product-guide-quiz__question--text"><?= $text; ?></div>
                <small class="product-guide-quiz__navigation--warning"></small>
                <div class="product-guide-quiz__answers">
                  <?php
                  foreach($answers as $answer) :
                    $text = $answer['question_answer_text'];
                    //temp
                    $data = array();
                    // new
                    // $new_data = array();
                    if ($sorting == 'categories') :
                      $constraint_categories = $answer['question_sorting_categories'];

                      if (!empty($constraint_categories)) :
                        //temp
                        array_push($data, 'data-categories="'. json_encode($constraint_categories) .'"');
                        // new
                        // $new_data['categories'] = array($constraint_categories);
                      endif;

                    elseif ($sorting == 'price') :
                      $constraint_min_price = $answer['question_min_price'];
                      $constraint_max_price = $answer['question_max_price'];

                      if (!empty($constraint_min_price)) :
                        //temp
                        array_push($data, 'data-min-price="'.$constraint_min_price.'"');
                        // new
                        // $new_data['min-price'] = (int) $constraint_min_price;
                      endif;
                      if (!empty($constraint_max_price)) :
                        //temp
                        array_push($data, 'data-max-price="'.$constraint_max_price.'"');
                        // new
                        // $new_data['max-price'] = (int) $constraint_max_price;
                      endif;
                    endif;
                    ?>
                    <label class="product-guide-quiz__answer">
                      <h5 class="product-guide-quiz__answer--title">
                        <?= $text; ?>
                      </h5>

                      <?php //on the //new items i am passing a `value='json_encode($new_data);'` ?>
                      <input type="<?= $option_type; ?>" name="<?= $sorting; ?>" <?= implode(' ', $data); ?> required="required">
                      <div class="product-guide-quiz__answer--bubble"></div>
                    </label>
                  <?php
                  endforeach; ?>
                </div>
              </div>
            <?php
            endforeach; ?>
          </form>
        <?php
        endif; ?>
        <div class="product-guide-quiz__recommendations product-guide-quiz__slide">
          <?php
          the_module('pre-loader'); ?>
        </div>
        <div class="product-guide-quiz__navigation">
          <button id="next" class="product-guide-quiz__button product-guide-quiz__button--next">Next</button>
          <button id="restart" class="product-guide-quiz__button product-guide-quiz__button--restart">Retake Quiz</button>
          <a <?= $browse_link; ?>>Browse Gifts</a>
        </div>
      </div>
    </div>
    <div class="product-guide-quiz__content--bottom">
      <div class="product-guide-quiz__share">
        <?php
        the_module('product-guide/product-guide-share'); ?>
      </div>
      <div class="product-guide-quiz__sponsor">
        <?php
        if ($campaign_quiz_sponsor) : ?>
          <div class="product-guide-quiz__sponsor--container">
            <h5 class="product-guide-quiz__sponsor--sponsor_relationship"><?= $campaign_quiz_sponsor_relationship; ?></h5>
            <div class="product-guide-quiz__sponsor--sponsor_logo" style="background-image: url(<?= $sponsor_logo; ?>);"></div>
          </div>
        <?php
        endif; ?>
        <div class="product-guide-quiz__sponsor--enclosure">
          <?= get_svg('holidaygiftgiude-logo', array(
            'role' => 'banner'
          )); ?>
        </div>
      </div>
    </div>
  </div>
  <div class="product-guide-quiz__background"></div>
</div>
