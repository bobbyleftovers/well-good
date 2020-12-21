var initializeModules = require('./lib/init-modules.js');
var requireAll = require('./lib/require-all.js');

//Require all components - scss
requireAll(require.context("@modules/rich-tag", true, /\.scss$/));

// Require all js
initializeModules(name => require( "@modules/rich-tag/" + name + '/' + name + '.js' ), 'rich-tag')