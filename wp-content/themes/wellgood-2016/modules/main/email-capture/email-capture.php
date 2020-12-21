<?php
global $post;
$is_legacy_post = is_post_legacy( get_the_ID() );
$queried_tax = is_category() ? get_queried_object() : NULL;
$vertical = get_vertical( $queried_tax ? $queried_tax->slug : get_field( 'hero_tag', get_the_ID() ) );
$args = $post->email_capture_field;
$form_id = array_key_exists('form_id', $args) ? $args['form_id'] : 'email-capture';

$is_active = get_field('email_capture_active', 'option');
$is_preview_mode = get_field('email_capture_preview', 'option');
$desktop_data = get_field('email_capture_desktop', 'option');
$mobile_data = get_field('email_capture_mobile', 'option');
$square_form_defaults = get_field('square_form_defaults', 'option');
$horizontal_form_defaults = get_field('horizontal_form_defaults', 'option');

$is_admin_user = current_user_can('editor') || current_user_can('administrator');
$is_preview_mode_on = $is_preview_mode && $is_admin_user;

if (empty($is_preview_mode_on)) {
  $is_preview_mode_on = false;
}

function get_post_name($page_object) {
  return $page_object->post_name;
}

// Check if current page shouldn't display email capture form
function is_current_page_banned($post = null) {
  if(!$post || !isset($post->ID)) return false;
  $default_banned_page_slugs = array('about-wg', 'were-hiring', 'contact-us', 'advertise-with-wellgood', 'press', 'giveaway');
  $custom_banned_pages = get_field('email_capture_banned_pages', 'option');
  $current_post_slug = $post->post_name;

  if ($custom_banned_pages) {
    $custom_banned_pages_slugs = array_map('get_post_name', $custom_banned_pages);
    $all_banned_pages_slugs = array_merge($default_banned_page_slugs, $custom_banned_pages_slugs);

    return in_array($current_post_slug, $all_banned_pages_slugs);
  }

  return in_array($current_post_slug, $default_banned_page_slugs);
}

// $verticalData = NULL;

// if (!empty($vertical)) {
//   $vertical_assignments = get_field('verticals_form_assignment', 'options');
//   foreach ($vertical_assignments as $vertical_assignment) {
//     if (isset($vertical_assignment['form_vertical']) && $vertical_assignment['form_vertical'] === $vertical) {
//       $verticalData = $vertical_assignment;
//       break;
//     }
//   }
// }

// dump($verticalData);

$is_current_page_banned = false;

if (!$is_preview_mode_on) {
  $is_current_page_banned = is_current_page_banned($post);
}

$visit_from_email = false;

if (isset($_SERVER['QUERY_STRING'])) {
  $email_query_position = strpos($_SERVER['QUERY_STRING'], 'utm=email');
  $visit_from_email = $email_query_position === false ? false : true;
}
// data-vertical-data='<?= json_encode($verticalData);
if (($is_active || $is_preview_mode_on) && $desktop_data && $mobile_data && !$visit_from_email && !$is_current_page_banned): ?>

<div class="email-capture <?= $is_legacy_post ? 'legacy--post' : 'standard--post' ?>" data-module-init="email-capture" v-cloak>
  <email-capture
    tag="div"
    inline-template
    form-id="<?= $form_id ?>"
    recaptcha-key="<?= get_field('recaptcha_site_key', 'options');?>"
    desktop-data='<?= json_encode($desktop_data); ?>'
    mobile-data='<?= json_encode($mobile_data); ?>'
    horizontal-fallback='<?= json_encode($horizontal_form_defaults); ?>'
    square-fallback='<?= json_encode($square_form_defaults); ?>'
    :is-preview-mode-on='<?= $is_preview_mode_on && !empty($is_preview_mode_on) ? 'true' : 'false'?>'
  >
    <div v-if="currentCaptureData" :class="[`type--${captureDesignType}`, captureDesignType === 'square' ? 'xl:fixed xl:max-w-container-xl xl:mx-auto xl:left-0 xl:right-0 xl:bottom-0' : '' ]">
      <transition appear name="fade">
        <div key="square" v-if="isVisible && captureDesignType === 'square'" :class="[isDarkMode ? 'text-white dark-mode':'']" class="email-capture__square fixed w-full sm:max-w-email-cap-square pt-e40 pb-e40 px-e24 md:px-e30 md:py-e55 overflow-hidden top-e45 left-0 sm:top-auto sm:bottom-e40 sm:left-e40 xl:absolute">
          <button class="email-capture__cancel-button" v-on:click="hideEmailCapture" data-vars-event="email capture cancel button"><span class="visually-hidden">Close</span></button>
          <img v-if="currentCaptureData.background_image" :src="currentCaptureData.background_image" alt="" class="inset-0 w-full h-full object-cover object-center absolute">
          <div class="z-10 relative text-center max-w-e350 mx-auto">
            <p class="text-nl-capture-title font-display m-0 mb-e7 md:mb-e2" v-html="currentCaptureData.title"></p>
            <p class="text-nl-capture-copy-sm md:text-nl-capture-copy mt-0 mb-e16 sm:mb-e22" v-html="currentCaptureData.subtitle"></p>
            <form :id="customFormId" v-on:submit.prevent="handleFormSubmit" :class="[errorMessage ? `email-capture__form--error border-red ${isDarkMode ? 'error-outline':''}` : isDarkMode ? 'border-white' : 'border-gray']" class="relative signup-form--default signup-form--bordered p-e4 md:p-e5 border-05 border-solid" method="post">
              <div class="signup-form__fields theme-main-2020">
                <div class="signup-form__group signup-form__group__fields md:max-w-e350 md:ml-auto">
                  <?php brrl_the_module('main-2020/text-input', [
                    'type'  => 'text',
                    'class' => "required signup-form__email text-sm text-gray-dark placeholder-gray-60 focus:placeholder-gray-70 focus:border-seafoam-dark",
                    'name'  => 'email',
                    'required' => true,
                    'attrs' => 'ref="email" v-model="email" :class="[isDarkMode ? \'text-white\' : \'\', email && email.length ? \'is-dirty\' : \'\']"',
                    'labelAttrs' => 'v-text="currentCaptureData.email_input_placeholder" :class="[isDarkMode ? \'text-white\' : \'\']"'
                  ]); ?>
                  <input
                    :class="[isDarkMode ? 'base-button--white base-button--white-alt' : 'base-button--primary']"
                    class="text-center text-link base-button signup-form__submit bg-primary"
                    type="submit"
                    name="email"
                    :value="currentCaptureData.signup_button_cta"
                    data-vars-event="newsletter subscribe"
                    data-vars-info="popup"
                  />
                </div>
                <p v-if="errorMessage" v-html="errorMessage" class="signup-form__result font-serif error -ml-e5 md:-ml-e6"></p>
                <p v-if="successMessage" v-html="successMessage" class="signup-form__result font-serif success error -ml-e5 md:-ml-e6"></p>
              </div>
            </form>
          </div>
        </div>
      </transition>

      <transition appear name="fade">
        <div key="horizontal" v-if="isVisible && captureDesignType === 'horizontal'" class="container top-e45 fixed md:top-auto md:bottom-0 right-0 left-0 email-capture__rect-wrapper" :class="[isDarkMode ? 'bg-seafoam-dark md:bg-transparent text-white':'bg-tan-light md:bg-transparent']">
          <button class="email-capture__cancel-button md:hidden" v-on:click="hideEmailCapture" data-vars-event="email capture cancel button"><span class="visually-hidden">Close</span></button>
          <div class="email-capture__rect-container">
            <div :class="[isDarkMode ? 'dark-mode text-white bg-seafoam-dark':'bg-tan-light']" class="relative email-capture__rect mx-auto md:mx-auto md:mb-e30 w-full">
              <button class="hidden md:flex email-capture__cancel-button" v-on:click="hideEmailCapture" data-vars-event="email capture cancel button"><span class="visually-hidden">Close</span></button>
              <div class="px-e20 pb-e25 sm:py-e20 sm:px-e15 md:py-e20 lg:py-e7 lg:px-e38 flex flex-col sm:flex-row flex-wrap sm:flex-no-wrap sm:items-center sm:justity-between mx-auto max-w-e350 sm:max-w-e530 lg:max-w-e610">
                <div class="w-e120 h-e106 mx-auto sm:ml-0 sm:mb-0 ml:mt-0 ml:mb-0 sm:w-e125 sm:h-e111 lg:w-e156 lg:h-e138 ratio z-10 flex-shrink-0">
                  <img v-if="currentCaptureData.marketting_image" :src="currentCaptureData.marketting_image" alt="" class="inset-0 w-full h-full object-contain absolute">
                </div>
                <div class="grid grid-cols-1 w-full sm:pl-e20 ml:pl-e27 xl:pl-e43 ml:pb-e2">
                  <div class="z-10 relative whitespace-no-wrap text-center sm:text-left mb-e10 sm:mb-0">
                    <p class="text-nl-capture-title font-display m-0 sm:mb-e5 md:mb-0" v-html="currentCaptureData.title"></p>
                    <p class="text-nl-capture-copy-sm md:text-nl-capture-copy mt-0 mb-0 sm:mb-e10 md:mb-e6" v-html="currentCaptureData.subtitle"></p>
                  </div>
                  <div class="z-10 w-full">
                    <form :id="customFormId" v-on:submit.prevent="handleFormSubmit" :class="[errorMessage ? `email-capture__form--error border-red ${isDarkMode ? 'error-outline' : ''}` : isDarkMode ? 'border-white' : '', isDarkMode ? 'signup-form--bordered p-e4 md:p-e5 border-05 border-solid md:max-w-e320' : 'md:max-w-e350']" class="z-10 relative signup-form--default sm:max-w-e320" method="post">
                      <div class="signup-form__fields theme-main-2020">
                        <div class="signup-form__group signup-form__group__fields sm:max-w-e320 md:max-w-e350">
                          <?php brrl_the_module('main-2020/text-input', [
                            'type'  => 'text',
                            'class' => "required signup-form__email text-sm text-gray-dark placeholder-gray-60 focus:placeholder-gray-70 focus:border-seafoam-dark",
                            'name'  => 'email',
                            'required' => true,
                            'wrapperAttrs' => ':class="[isDarkMode ? \'\' : \'bg-white\', errorMessage && !isDarkMode ? \'border-red\' : \'\']"',
                            'attrs' => 'ref="email" v-model="email" :class="[isDarkMode ? \'text-white\' : \'bg-white\', email && email.length ? \'is-dirty\' : \'\']"',
                            'labelAttrs' => 'v-text="currentCaptureData.email_input_placeholder" :class="[isDarkMode ? \'text-white\' : \'\']"'
                          ]); ?>
                          <input
                            :class="[isDarkMode ? 'base-button--white base-button--white-alt' : 'base-button--primary']"
                            class="text-center text-link base-button signup-form__submit bg-primary"
                            type="submit"
                            name="email"
                            :value="currentCaptureData.signup_button_cta"
                            data-vars-event="newsletter subscribe"
                            data-vars-info="popup"
                          />
                        </div>
                        <p v-if="errorMessage" v-html="errorMessage" class="signup-form__result font-serif text-left error"></p>
                        <p v-if="successMessage" v-html="successMessage" class="signup-form__result font-serif text-left success error"></p>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>

      <transition appear name="fade">
        <div
            class="email-capture__wrapper email-capture__wrapper--visible email-capture__wrapper--ready"
            v-if="isVisible && currentCaptureData && captureDesignType === 'circle'"
            key="circle"
            v-cloak
            :class="{
              'email-capture__wrapper--mobile': isMobile
            }"
          >
            <div
              class="email-capture__circle"
              :style="{
                color: customData.text_color,
                backgroundColor: customData.background_color,
                backgroundImage: getBackgroundImage ? 'url(' + getBackgroundImage + ')' : 'none'
              }"
            >
              <div v-if='!successMessage && !isLoading' class="email-capture__content">
                <div class="email-capture__text">
                  <p v-if="currentCaptureData.title" class="email-capture__title" v-html="currentCaptureData.title"></p>
                  <p v-if="currentCaptureData.subtitle" class="email-capture__subtitle" v-html="currentCaptureData.subtitle"></p>
                </div>
                <form :id="customFormId" v-on:submit.prevent="handleFormSubmit" class="email-capture__form" :class="{ 'email-capture__form--error': errorMessage }">
                  <div class="email-capture__form__input-wrapper">
                    <?php brrl_the_module('main-2020/text-input', [
                      'type'  => 'text',
                      'class' => "required signup-form__email text-sm text-white placeholder-gray-60 focus:placeholder-gray-70 focus:border-seafoam-dark",
                      'name'  => 'email',
                      'required' => true,
                      'inputClasses' => 'text-center',
                      'attrs' => 'ref="email" v-model="email" :class="[isDarkMode ? \'text-white\' : \'\', email && email.length ? \'is-dirty\' : \'\']"',
                      'labelAttrs' => 'v-text="currentCaptureData.email_input_placeholder" :class="[isDarkMode ? \'text-white\' : \'\']"'
                    ]); ?>
                    <span v-if="errorMessage" class="email-capture__form__error-message" v-html="errorMessage"></span>
                  </div>
                  <button
                    type="submit"
                    name="submit"
                    class="email-capture__form__submit"
                    value=""
                    data-vars-event="newsletter subscribe"
                    data-vars-info="popup"
                    :style="{
                      color: customData.submit_text_color,
                      backgroundColor: customData.submit_background_color,
                    }"
                    v-html="currentCaptureData.signup_button_cta"
                  >
                  </button>
                </form>
                <a
                  v-if="customData.cancel_text"
                  class="email-capture__cancel-link"
                  v-on:click="hideEmailCapture"
                  data-vars-event="email capture cancel link"
                  :style="{ color: customData.text_color }"
                >
                  {{ customData.cancel_text }}
                </a>
              </div><!-- /.email-capture__content -->
              <div v-if='successMessage && !isLoading' class="email-capture__success">
                <p>{{ successMessage }}</p>
              </div>
              <div v-if="isLoading" class="email-capture__loader">
                <p>Please wait a moment...</p>
              </div>
              <button class="email-capture__cancel-button" v-on:click="hideEmailCapture" data-vars-event="email capture cancel button"><span class="visually-hidden">Close</span></button>
            </div>
          </div>
      </transition>

    </div>
  </email-capture>
</div>
<?php endif; ?>
