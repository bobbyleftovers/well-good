<h3 class="text-h2-styleguide">Inline Vue global component registration</h3>

<div class="grid grid-cols-3 col-gap-gutter">
<?php brrl_the_module('styleguide-2020/code', array(
'title' => 'PHP Include',
'lang' => 'php',
'code' => "brrl_register_vue_component('styleguide-2020/vue-component-name', array(
  'arg1' => 'value1',
  'arg2' => 'value2',
));

/*
 Can be called multiple times, but it
 will only be registered once.
 Available as &#60;vue-component-name />
 */
"
));
?>

<?php brrl_the_module('styleguide-2020/code', array(
'title' => 'Component',
'footer' => '<span class="font-code text-small-mobile">/modules/main-2020/vue-component-name/vue-component-name.php</span>',
'lang' => 'html',
'code' => '<div data-module-init="vue-component-name">
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
'footer' => '<span class="font-code text-small-mobile">/modules/main-2020/vue-component-name/vue-component-name.js</span>',
'lang' => 'js',
'code' => "import { registerVueComponent } from 'lib/init-vue';

module.exports = (el) => {
  registerVueComponent(el, {
    props: [ 'prop1', 'prop2' ],
    data: function(){
     return {}
    }
    ...Vue object
  };
}"))
?>
</div>
