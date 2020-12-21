<?php 

$mod_args = array_merge(array(
  'is_transparent' => false,
  'text_color' => 'black',
  'not_sticky' => false
), $args ?? array());

// Env classes
if(is_staging()) add_body_class('is-staging');
if(is_production()) add_body_class('is-production');
if(is_local()) add_body_class('is-local');
if(is_dev()) add_body_class('is-dev');
add_body_class(sanitize_title(get_host()));

?>
<!doctype html>
<html lang="en">
	<head>
		<?= include_partial('header/meta-head'); ?>
		<?= include_partial('header/ad-vars-head'); ?>
		<?= include_partial('header/permutive-head'); ?>
		<?= include_partial('header/datalayer-head'); ?>
    <?= include_partial('header/breadcrumbs-head'); ?>
    <?php brrl_the_module('narrative-js-tag'); ?>
    <?php wp_head(); ?>
		<style>
		.grecaptcha-badge { visibility: hidden; }
		</style>
		<script defer src="https://www.google.com/recaptcha/api.js?render=<?= get_field('recaptcha_site_key', 'options');?>"></script>
    <script>
      // Unicode Tests
      try {
				!function(e,n,o){function t(e,n){return typeof e===n}function a(){var e,n,o,a,s,i,r;for(var l in d)if(d.hasOwnProperty(l)){if(e=[],n=d[l],n.name&&(e.push(n.name.toLowerCase()),n.options&&n.options.aliases&&n.options.aliases.length))for(o=0;o<n.options.aliases.length;o++)e.push(n.options.aliases[o].toLowerCase());for(a=t(n.fn,"function")?n.fn():n.fn,s=0;s<e.length;s++)i=e[s],r=i.split("."),1===r.length?Modernizr[r[0]]=a:(!Modernizr[r[0]]||Modernizr[r[0]]instanceof Boolean||(Modernizr[r[0]]=new Boolean(Modernizr[r[0]])),Modernizr[r[0]][r[1]]=a),f.push((a?"":"no-")+r.join("-"))}}function s(e){var n=p.className,o=Modernizr._config.classPrefix||"";if(u&&(n=n.baseVal),Modernizr._config.enableJSClass){var t=new RegExp("(^|\\s)"+o+"no-js(\\s|$)");n=n.replace(t,"$1"+o+"js$2")}Modernizr._config.enableClasses&&(n+=" "+o+e.join(" "+o),u?p.className.baseVal=n:p.className=n)}function i(){return"function"!=typeof n.createElement?n.createElement(arguments[0]):u?n.createElementNS.call(n,"http://www.w3.org/2000/svg",arguments[0]):n.createElement.apply(n,arguments)}function r(){var e=n.body;return e||(e=i(u?"svg":"body"),e.fake=!0),e}function l(e,o,t,a){var s,l,f,d,c="modernizr",u=i("div"),m=r();if(parseInt(t,10))for(;t--;)f=i("div"),f.id=a?a[t]:c+(t+1),u.appendChild(f);return s=i("style"),s.type="text/css",s.id="s"+c,(m.fake?m:u).appendChild(s),m.appendChild(u),s.styleSheet?s.styleSheet.cssText=e:s.appendChild(n.createTextNode(e)),u.id=c,m.fake&&(m.style.background="",m.style.overflow="hidden",d=p.style.overflow,p.style.overflow="hidden",p.appendChild(m)),l=o(u,e),m.fake?(m.parentNode.removeChild(m),p.style.overflow=d,p.offsetHeight):u.parentNode.removeChild(u),!!l}var f=[],d=[],c={_version:"3.6.0",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(e,n){var o=this;setTimeout(function(){n(o[e])},0)},addTest:function(e,n,o){d.push({name:e,fn:n,options:o})},addAsyncTest:function(e){d.push({name:null,fn:e})}},Modernizr=function(){};Modernizr.prototype=c,Modernizr=new Modernizr;var p=n.documentElement,u="svg"===p.nodeName.toLowerCase();c.testStyles=l;Modernizr.addTest("unicoderange",function(){return Modernizr.testStyles('@font-face{font-family:"unicodeRange";src:local("Arial");unicode-range:U+0020,U+002E}#modernizr span{font-size:20px;display:inline-block;font-family:"unicodeRange",monospace}#modernizr .mono{font-family:monospace}',function(e){for(var n=[".",".","m","m"],o=0;o<n.length;o++){var t=i("span");t.innerHTML=n[o],t.className=o%2?"mono":"",e.appendChild(t),n[o]=t.clientWidth}return n[0]!==n[1]&&n[2]===n[3]})}),a(),s(f),delete c.addTest,delete c.addAsyncTest;for(var m=0;m<Modernizr._q.length;m++)Modernizr._q[m]();e.Modernizr=Modernizr}(window,document);
				"document"in self&&("classList"in document.createElement("_")&&(!document.createElementNS||"classList"in document.createElementNS("http://www.w3.org/2000/svg","g"))||!function(t){"use strict";if("Element"in t){var e="classList",n="prototype",i=t.Element[n],s=Object,r=String[n].trim||function(){return this.replace(/^\s+|\s+$/g,"")},o=Array[n].indexOf||function(t){for(var e=0,n=this.length;n>e;e++)if(e in this&&this[e]===t)return e;return-1},c=function(t,e){this.name=t,this.code=DOMException[t],this.message=e},a=function(t,e){if(""===e)throw new c("SYNTAX_ERR","The token must not be empty.");if(/\s/.test(e))throw new c("INVALID_CHARACTER_ERR","The token must not contain space characters.");return o.call(t,e)},l=function(t){for(var e=r.call(t.getAttribute("class")||""),n=e?e.split(/\s+/):[],i=0,s=n.length;s>i;i++)this.push(n[i]);this._updateClassName=function(){t.setAttribute("class",this.toString())}},u=l[n]=[],h=function(){return new l(this)};if(c[n]=Error[n],u.item=function(t){return this[t]||null},u.contains=function(t){return~a(this,t+"")},u.add=function(){var t,e=arguments,n=0,i=e.length,s=!1;do t=e[n]+"",~a(this,t)||(this.push(t),s=!0);while(++n<i);s&&this._updateClassName()},u.remove=function(){var t,e,n=arguments,i=0,s=n.length,r=!1;do for(t=n[i]+"",e=a(this,t);~e;)this.splice(e,1),r=!0,e=a(this,t);while(++i<s);r&&this._updateClassName()},u.toggle=function(t,e){var n=this.contains(t),i=n?e!==!0&&"remove":e!==!1&&"add";return i&&this[i](t),e===!0||e===!1?e:!n},u.replace=function(t,e){var n=a(t+"");~n&&(this.splice(n,1,e),this._updateClassName())},u.toString=function(){return this.join(" ")},s.defineProperty){var f={get:h,enumerable:!0,configurable:!0};try{s.defineProperty(i,e,f)}catch(p){void 0!==p.number&&-2146823252!==p.number||(f.enumerable=!1,s.defineProperty(i,e,f))}}else s[n].__defineGetter__&&i.__defineGetter__(e,h)}}(self),function(){"use strict";var t=document.createElement("_");if(t.classList.add("c1","c2"),!t.classList.contains("c2")){var e=function(t){var e=DOMTokenList.prototype[t];DOMTokenList.prototype[t]=function(t){var n,i=arguments.length;for(n=0;i>n;n++)t=arguments[n],e.call(this,t)}};e("add"),e("remove")}if(t.classList.toggle("c3",!1),t.classList.contains("c3")){var n=DOMTokenList.prototype.toggle;DOMTokenList.prototype.toggle=function(t,e){return 1 in arguments&&!this.contains(t)==!e?e:n.call(this,t)}}"replace"in document.createElement("_").classList||(DOMTokenList.prototype.replace=function(t,e){var n=this.toString().split(" "),i=n.indexOf(t+"");~i&&(n=n.slice(i),this.remove.apply(this,n),this.add(e),this.add.apply(this,n.slice(1)))}),t=null}());
        if(navigator.userAgent.indexOf('MSIE') >= 0 || navigator.appVersion.indexOf('Trident/') >= 0){
          document.documentElement.classList.remove("unicoderange")
        }
      } catch (error) {}
    </script>
    <!-- Array Polyfill -->
    <script>
      try { Array.prototype.reduce||Object.defineProperty(Array.prototype,"reduce",{value:function(r){if(null===this)throw new TypeError("Array.prototype.reduce called on null or undefined");if("function"!=typeof r)throw new TypeError(r+" is not a function");var e,t=Object(this),n=t.length>>>0,o=0;if(arguments.length>=2)e=arguments[1];else{for(;o<n&&!(o in t);)o++;if(o>=n)throw new TypeError("Reduce of empty array with no initial value");e=t[o++]}for(;o<n;)o in t&&(e=r(e,t[o],o,t)),o++;return e}}),Array.prototype.includes||Object.defineProperty(Array.prototype,"includes",{value:function(r,e){if(null==this)throw new TypeError('"this" is null or not defined');var t=Object(this),n=t.length>>>0;if(0===n)return!1;var o,i,u=0|e,a=Math.max(u>=0?u:n-Math.abs(u),0);for(;a<n;){if((o=t[a])===(i=r)||"number"==typeof o&&"number"==typeof i&&isNaN(o)&&isNaN(i))return!0;a++}return!1}}); } catch (e) {}
    </script>
  </head>
  
	<body <?php body_class(); ?> data-module-init="advertisement">
		<?= include_partial('gtm'); ?>
		<?= include_partial('ambassador'); ?>
		<div class="main-wrapper" data-module-init="anchor-link" data-anchor-link-offset=".header .header__inner">
			<?php
			$header_prefix = get_theme_prefix();
			$template = $header_prefix ? "$header_prefix/$header_prefix-header" : 'header';
			brrl_the_module($template, $mod_args); ?>
