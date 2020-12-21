import '@scss/renew-year-2021.scss'
var initializeModules = require('./lib/init-modules.js');
var requireAll = require('./lib/require-all.js');

//Require all components - scss
requireAll(require.context("@modules/renew-year-2021", true, /\.scss$/));

// Require all js
initializeModules(name => require( "@modules/renew-year-2021/" + name + '/' + name + '.js' ), 'renew-year-2021')
