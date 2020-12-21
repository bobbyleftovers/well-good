import '@scss/trends-2021.scss'
import { bus } from 'lib/appState'
var initializeModules = require('./lib/init-modules.js');
var requireAll = require('./lib/require-all.js');

//Require all components - scss
requireAll(require.context("@modules/trends-2021", true, /\.scss$/));

// Require all js
initializeModules(name => require( "@modules/trends-2021/" + name + '/' + name + '.js' ), 'trends-2021')

// Load event
bus.$once('gsap-scroll-refresh:ready', () => {
  // console.log('gsap-scroll-refresh ready!')
})