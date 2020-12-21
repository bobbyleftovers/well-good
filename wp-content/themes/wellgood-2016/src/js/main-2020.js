//The goal is to remove this
require('./main-legacy.js');
import '@scss/main-2020.scss'
var requireAll = require('./lib/require-all.js');
var initializeModules = require('./lib/init-modules.js');

//Require all components - scss
requireAll(require.context("@modules/main-2020", true, /\.scss$/));

//Require all components - js
initializeModules(name => require( "@modules/main-2020/" + name + '/' + name + '.js' ), 'main-2020')
