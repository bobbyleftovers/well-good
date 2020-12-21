import '@scss/_fonts.scss'
import '@scss/gutenberg.editor.scss'
var requireAll = require('./lib/require-all.js');
var initializeModules = require('./lib/init-modules.js');

// Gutenberg js
requireAll(require.context("./gutenberg/", true, /\.js$/));

//Require all components

( function( blocks, element ) {
  requireAll(require.context("@modules/", true, /\.editor.js$/));
}(
  window.wp.blocks,
  window.wp.element
) )

requireAll(require.context("@modules/", true, /\.editor.scss$/));

//Require all components - js
initializeModules(name => require( "@modules/acf/" + name + '/' + name + '.editor.js' ))
initializeModules(name => require( "@modules/core/" + name + '/' + name + '.editor.js' ))
initializeModules(name => require( "@modules/embed/" + name + '/' + name + '.editor.js' ))
