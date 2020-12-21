var $ = require('jquery');
if(typeof window.BRRL_MODULES === 'undefined') window.BRRL_MODULES = [];
if(typeof window.BRRL_MODULES_INIT === 'undefined') window.BRRL_MODULES_INIT = [];
if(typeof window.BRRL_MODULES_AWAIT === 'undefined') window.BRRL_MODULES_AWAIT = [];
/**
 * Finds all elements with a "data-module-init" attribute
 * and calls the corresponding script
 */
function initializeModules(requireModule, namespace = null, globalModules = [], componentsAlreadyRegistered = false) {

  if(!componentsAlreadyRegistered){
    var $vueComponents = $('.brrl-vue-components')
    initializeModulesDom(requireModule, $vueComponents)
    initializeModules(requireModule, namespace, globalModules, true)
    return;
  }

  if(namespace){
    if(window.BRRL_MODULES[namespace] === 'loaded') return;
    window.BRRL_MODULES[namespace] = 'loaded'
  }

  initializeModulesDom(requireModule);

  if (globalModules && globalModules.length) {
    for ( var k = 0; k < globalModules.length; k++ ) {
      initModule( globalModules[k], document.body , requireModule);
    }
  }
}

function initializeModulesDom(requireModule, parentNode = document){

  if(parentNode instanceof jQuery){
    if(parentNode.length === 0) return;
    parentNode = parentNode.get( 0 )
  }

  var modules = parentNode.querySelectorAll( '[data-module-init]:not([data-module-fired])' ),
    el, names;

  for ( var i = 0; i < modules.length; i++ ) {
    el    = modules[ i ];
    names = el.getAttribute( "data-module-init" ).split( " " );


    for ( var j = 0; j < names.length; j++ ) {
      initModule( names[j], el , requireModule);
    }
  }
}

const fireFunctionIfModulesAreInitialized = (modules = [], callback = () => {}) => {
  var init = true
  modules.forEach(module => {
    if(!window.BRRL_MODULES_INIT.includes(module)) {
      init = false
      return false
    }
  })
  if(init) callback()
  return init;
}

const tryInitAwaitingModules = () => {
  clearTimeout(window.tryInitAwaitingModulesTimeout)
  window.tryInitAwaitingModulesTimeout = setTimeout(() => {
    window.BRRL_MODULES_AWAIT.forEach(() => {
      var awaitingModule = window.BRRL_MODULES_AWAIT.shift()
      waitForModules(awaitingModule[0], awaitingModule[1])
    })
  }, 10)
}


const waitForModules = function (modules = [], callback = () => {}) {
  if(!fireFunctionIfModulesAreInitialized(modules, callback)){
    window.BRRL_MODULES_AWAIT.push([modules, callback])
  }
}

function initModule( name, el , requireModule) {
  var Module;

  // Find the module script
  try {
    Module = requireModule(name);
    // Initialize the module with the calling element
    if ( Module && typeof Module === 'function') {
      new Module( el );
      el.dataset.moduleFired = true;
      window.BRRL_MODULES_INIT.push(name)
      tryInitAwaitingModules()
    }
  } catch ( e ) {
    Module = false;
    if(window.NODE_ENV === 'development') console.warn( name + " module does not exist." );
  }
}


module.exports = function (requireModule, namespace = null, globalModules = [], componentsAlreadyRegistered = false){

  if(/MSIE \d|Trident.*rv:/.test(navigator.userAgent)){
    $('body').addClass('ie');
    $('.waypoint').removeClass('waypoint');
    initializeModules(requireModule, namespace, globalModules, componentsAlreadyRegistered);
  } else {
    jQuery(function() {
      initializeModules(requireModule, namespace, globalModules, componentsAlreadyRegistered);
    });
  }

}

module.exports.waitForModules = waitForModules