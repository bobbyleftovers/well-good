<h3 class="text-h2-styleguide">Vue app wrapper</h3>

<div class="grid grid-cols-3 col-gap-gutter">
<?php brrl_the_module('styleguide-2020/code', array(
'title' => 'PHP Include',
'lang' => 'php',
'code' => "brrl_the_module('main-2020/component-name', array(
  'arg1' => 'value1',
  'arg2' => 'value2',
));
"
));
?>
<?php brrl_the_module('styleguide-2020/code', array(
'title' => 'Component',
'footer' => '<span class="font-code text-small-mobile">/modules/main-2020/component-name/component-name.php</span>',
'lang' => 'html',
'code' => '<div data-module-init="component-name">
  <div>
  <!--
    PHP arguments available as separate variables: $arg1 and $arg2
    Vue syntax also available {{prop1} }
  -->
  </div>
</div>'
));
?>
<?php brrl_the_module('styleguide-2020/code', array(
'title' => 'JS',
'footer' => '<span class="font-code text-small-mobile">/modules/main-2020/component-name/component-name.js</span>',
'lang' => 'js',
'code' => 'import { initVueApp } from "lib/init-vue"

module.exports = (el) => initVueApp(el, {
  /*
    Vue object
  */
};'
));
?>
</div>
