<?php
global $post;

$args = !empty($args) ? $args : $post->signup_form_field;
if(!is_array($args)) $args = array();

$source = array_key_exists('source', $args) ? $args['source'] : '';
$form_id = array_key_exists('form_id', $args) ? $args['form_id'] : 'SignupForm';
$location = array_key_exists('location', $args) ? $args['location'] : 'popup';
$form_tagline = array_key_exists('form_tagline', $args) ? $args['form_tagline'] : get_field('newsletter_description', 'options');
$show_thank_you = array_key_exists('show_thank_you', $args) ? $args['show_thank_you'] : false;
$newsletter_button_text = array_key_exists('newsletter_button_text', $args) ? $args['newsletter_button_text'] : 'Sign Up';
$edition_copy = array_key_exists('edition_copy', $args) ? $args['edition_copy'] : 'interested in another edition?';
$inline = array_key_exists('inline', $args) && $args['inline'] === true ? true : false;

$form_classes = array('signup-form__form');
array_push($form_classes, "signup-form__$form_id");
if ($inline) :
	array_push($form_classes, 'signup-form__inline');
endif;

$form_options = get_field('signup_forms', 'options');
$form_data = $form_options ? array_values(
	array_filter($form_options, function($form) use($form_id) {
		return $form['signup_form_id'] === $form_id;
	})
) : array();
$form_data = $form_data ? $form_data[0] : array();

$list_id = $form_data && array_key_exists('signup_list_id', $form_data) && $form_data['signup_list_id'] ? $form_data['signup_list_id'] : '';
$success_method = $form_data && array_key_exists('signup_form_success_method', $form_data) && $form_data['signup_form_success_method'] ? $form_data['signup_form_success_method'] : 'message';
$thanks = $form_data && array_key_exists('signup_form_success_message', $form_data) && ($form_data['signup_form_success_method'] === 'message' || $form_data['signup_form_success_method'] === 'fake-redirect') ? $form_data['signup_form_success_message'] : get_field('newsletter_thank_you_message', 'options');
$redirect = $form_data && $form_data['signup_form_success_method'] === 'redirect' ? $form_data['signup_form_success_redirect'] : false;
$fake_redirect = $form_data && $form_data['signup_form_success_method'] === 'fake-redirect';
$is_thankyou_redirect = isset($_GET['success_signup']);
$style = $style ?? null;
$class = $class ?? '';
?>

<?php if(!empty($form_tagline)): ?>
<div class="signup-form__tagline small <?=$class?>"><?= $form_tagline ?></div>
<?php endif;?>

<form action="/wp-json/api/v1/esp/signup/" class="w-1/1 relative signup-form--<?= $style ?? 'default' ?> <?= implode(' ', $form_classes); ?>" method="post" data-module-init="signup-form" data-form-id="<?= $form_id ?>" data-location="<?= $location ?>" data-recaptcha-key="<?= get_field('recaptcha_site_key', 'options');?>">
  <?php if ($fake_redirect && $is_thankyou_redirect): ?>
    <div class="signup-form__thanks"><?= $thanks; ?></div>
  <?php else: ?>
	<div class="signup-form__fields theme-main-2020">
		<div class="signup-form__group signup-form__group__fields">
      <?php brrl_the_module('main-2020/text-input', [
        'type'  => 'email',
        'class' => "required signup-form__email font-small text-gray-dark placeholder-gray-60 focus:placeholder-gray-70 focus:border-seafoam-dark" . " bg-" . $style,
        'name'  => 'email',
        'id'  => 'signup_form_' . $location,
        'required' => true,
        'label'  => 'Email Address'
      ]); ?>
      <?php brrl_the_module('main-2020/base-input-submit',array(
        'value' => $newsletter_button_text,
        'class' => 'signup-form__submit disabled bg-'.$style,
        'style' => $style
      )); ?>
		</div>

		<input type="hidden" name="form_id" value="<?= $form_id ?>" />

		<p class="signup-form__result small error"></p>
	</div>
  <?php endif; ?>
</form>
