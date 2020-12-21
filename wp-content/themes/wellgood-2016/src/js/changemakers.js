import '@scss/changemakers.scss'
var initializeModules = require('./lib/init-modules.js');
var requireAll = require('./lib/require-all.js');

//Require all components - scss
requireAll(require.context("@modules/changemakers", true, /\.scss$/));

// Require all js
initializeModules(name => require( "@modules/changemakers/" + name + '/' + name + '.js' ), 'changemakers')
