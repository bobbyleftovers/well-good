
<div data-module-init="computed-styles">
  <div>
    <div class="hidden" :class="targetClass" ref="styleRef"></div>
    <span v-for="property in properties"><span class="inline-block w-13">{{property}}:</span> <strong>{{computedStyles[property]}}</strong><br></span>
  </div>
</div>
