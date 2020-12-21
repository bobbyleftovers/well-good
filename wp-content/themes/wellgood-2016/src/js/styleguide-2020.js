var requireAll = require('./lib/require-all.js');
var initializeModules = require('./lib/init-modules.js');

//Require main css
import '@scss/styleguide-2020.scss'

//Require all components - scss
requireAll(require.context("@modules/styleguide-2020", true, /\.scss$/));

//Require all components - js
initializeModules(name => require( "@modules/styleguide-2020/" + name + '/' + name + '.js' ), 'styleguide-2020')
