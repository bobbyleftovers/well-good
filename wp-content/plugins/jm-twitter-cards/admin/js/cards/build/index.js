this["cards"] =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 45);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

var core = module.exports = { version: '2.5.7' };
if (typeof __e == 'number') __e = core; // eslint-disable-line no-undef


/***/ }),
/* 1 */
/***/ (function(module, exports) {

// https://github.com/zloirock/core-js/issues/86#issuecomment-115759028
var global = module.exports = typeof window != 'undefined' && window.Math == Math
  ? window : typeof self != 'undefined' && self.Math == Math ? self
  // eslint-disable-next-line no-new-func
  : Function('return this')();
if (typeof __g == 'number') __g = global; // eslint-disable-line no-undef


/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

var global = __webpack_require__(1);
var core = __webpack_require__(0);
var ctx = __webpack_require__(32);
var hide = __webpack_require__(6);
var has = __webpack_require__(5);
var PROTOTYPE = 'prototype';

var $export = function (type, name, source) {
  var IS_FORCED = type & $export.F;
  var IS_GLOBAL = type & $export.G;
  var IS_STATIC = type & $export.S;
  var IS_PROTO = type & $export.P;
  var IS_BIND = type & $export.B;
  var IS_WRAP = type & $export.W;
  var exports = IS_GLOBAL ? core : core[name] || (core[name] = {});
  var expProto = exports[PROTOTYPE];
  var target = IS_GLOBAL ? global : IS_STATIC ? global[name] : (global[name] || {})[PROTOTYPE];
  var key, own, out;
  if (IS_GLOBAL) source = name;
  for (key in source) {
    // contains in native
    own = !IS_FORCED && target && target[key] !== undefined;
    if (own && has(exports, key)) continue;
    // export native or passed
    out = own ? target[key] : source[key];
    // prevent global pollution for namespaces
    exports[key] = IS_GLOBAL && typeof target[key] != 'function' ? source[key]
    // bind timers to global for call from export context
    : IS_BIND && own ? ctx(out, global)
    // wrap global constructors for prevent change them in library
    : IS_WRAP && target[key] == out ? (function (C) {
      var F = function (a, b, c) {
        if (this instanceof C) {
          switch (arguments.length) {
            case 0: return new C();
            case 1: return new C(a);
            case 2: return new C(a, b);
          } return new C(a, b, c);
        } return C.apply(this, arguments);
      };
      F[PROTOTYPE] = C[PROTOTYPE];
      return F;
    // make static versions for prototype methods
    })(out) : IS_PROTO && typeof out == 'function' ? ctx(Function.call, out) : out;
    // export proto methods to core.%CONSTRUCTOR%.methods.%NAME%
    if (IS_PROTO) {
      (exports.virtual || (exports.virtual = {}))[key] = out;
      // export proto methods to core.%CONSTRUCTOR%.prototype.%NAME%
      if (type & $export.R && expProto && !expProto[key]) hide(expProto, key, out);
    }
  }
};
// type bitmap
$export.F = 1;   // forced
$export.G = 2;   // global
$export.S = 4;   // static
$export.P = 8;   // proto
$export.B = 16;  // bind
$export.W = 32;  // wrap
$export.U = 64;  // safe
$export.R = 128; // real proto method for `library`
module.exports = $export;


/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

var anObject = __webpack_require__(11);
var IE8_DOM_DEFINE = __webpack_require__(33);
var toPrimitive = __webpack_require__(17);
var dP = Object.defineProperty;

exports.f = __webpack_require__(4) ? Object.defineProperty : function defineProperty(O, P, Attributes) {
  anObject(O);
  P = toPrimitive(P, true);
  anObject(Attributes);
  if (IE8_DOM_DEFINE) try {
    return dP(O, P, Attributes);
  } catch (e) { /* empty */ }
  if ('get' in Attributes || 'set' in Attributes) throw TypeError('Accessors not supported!');
  if ('value' in Attributes) O[P] = Attributes.value;
  return O;
};


/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

// Thank's IE8 for his funny defineProperty
module.exports = !__webpack_require__(8)(function () {
  return Object.defineProperty({}, 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),
/* 5 */
/***/ (function(module, exports) {

var hasOwnProperty = {}.hasOwnProperty;
module.exports = function (it, key) {
  return hasOwnProperty.call(it, key);
};


/***/ }),
/* 6 */
/***/ (function(module, exports, __webpack_require__) {

var dP = __webpack_require__(3);
var createDesc = __webpack_require__(12);
module.exports = __webpack_require__(4) ? function (object, key, value) {
  return dP.f(object, key, createDesc(1, value));
} : function (object, key, value) {
  object[key] = value;
  return object;
};


/***/ }),
/* 7 */
/***/ (function(module, exports) {

module.exports = function (it) {
  return typeof it === 'object' ? it !== null : typeof it === 'function';
};


/***/ }),
/* 8 */
/***/ (function(module, exports) {

module.exports = function (exec) {
  try {
    return !!exec();
  } catch (e) {
    return true;
  }
};


/***/ }),
/* 9 */
/***/ (function(module, exports, __webpack_require__) {

// to indexed object, toObject with fallback for non-array-like ES3 strings
var IObject = __webpack_require__(36);
var defined = __webpack_require__(18);
module.exports = function (it) {
  return IObject(defined(it));
};


/***/ }),
/* 10 */
/***/ (function(module, exports, __webpack_require__) {

var store = __webpack_require__(21)('wks');
var uid = __webpack_require__(15);
var Symbol = __webpack_require__(1).Symbol;
var USE_SYMBOL = typeof Symbol == 'function';

var $exports = module.exports = function (name) {
  return store[name] || (store[name] =
    USE_SYMBOL && Symbol[name] || (USE_SYMBOL ? Symbol : uid)('Symbol.' + name));
};

$exports.store = store;


/***/ }),
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(7);
module.exports = function (it) {
  if (!isObject(it)) throw TypeError(it + ' is not an object!');
  return it;
};


/***/ }),
/* 12 */
/***/ (function(module, exports) {

module.exports = function (bitmap, value) {
  return {
    enumerable: !(bitmap & 1),
    configurable: !(bitmap & 2),
    writable: !(bitmap & 4),
    value: value
  };
};


/***/ }),
/* 13 */
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.14 / 15.2.3.14 Object.keys(O)
var $keys = __webpack_require__(35);
var enumBugKeys = __webpack_require__(22);

module.exports = Object.keys || function keys(O) {
  return $keys(O, enumBugKeys);
};


/***/ }),
/* 14 */
/***/ (function(module, exports) {

module.exports = true;


/***/ }),
/* 15 */
/***/ (function(module, exports) {

var id = 0;
var px = Math.random();
module.exports = function (key) {
  return 'Symbol('.concat(key === undefined ? '' : key, ')_', (++id + px).toString(36));
};


/***/ }),
/* 16 */
/***/ (function(module, exports) {

exports.f = {}.propertyIsEnumerable;


/***/ }),
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

// 7.1.1 ToPrimitive(input [, PreferredType])
var isObject = __webpack_require__(7);
// instead of the ES6 spec version, we didn't implement @@toPrimitive case
// and the second argument - flag - preferred type is a string
module.exports = function (it, S) {
  if (!isObject(it)) return it;
  var fn, val;
  if (S && typeof (fn = it.toString) == 'function' && !isObject(val = fn.call(it))) return val;
  if (typeof (fn = it.valueOf) == 'function' && !isObject(val = fn.call(it))) return val;
  if (!S && typeof (fn = it.toString) == 'function' && !isObject(val = fn.call(it))) return val;
  throw TypeError("Can't convert object to primitive value");
};


/***/ }),
/* 18 */
/***/ (function(module, exports) {

// 7.2.1 RequireObjectCoercible(argument)
module.exports = function (it) {
  if (it == undefined) throw TypeError("Can't call method on  " + it);
  return it;
};


/***/ }),
/* 19 */
/***/ (function(module, exports) {

// 7.1.4 ToInteger
var ceil = Math.ceil;
var floor = Math.floor;
module.exports = function (it) {
  return isNaN(it = +it) ? 0 : (it > 0 ? floor : ceil)(it);
};


/***/ }),
/* 20 */
/***/ (function(module, exports, __webpack_require__) {

var shared = __webpack_require__(21)('keys');
var uid = __webpack_require__(15);
module.exports = function (key) {
  return shared[key] || (shared[key] = uid(key));
};


/***/ }),
/* 21 */
/***/ (function(module, exports, __webpack_require__) {

var core = __webpack_require__(0);
var global = __webpack_require__(1);
var SHARED = '__core-js_shared__';
var store = global[SHARED] || (global[SHARED] = {});

(module.exports = function (key, value) {
  return store[key] || (store[key] = value !== undefined ? value : {});
})('versions', []).push({
  version: core.version,
  mode: __webpack_require__(14) ? 'pure' : 'global',
  copyright: '© 2018 Denis Pushkarev (zloirock.ru)'
});


/***/ }),
/* 22 */
/***/ (function(module, exports) {

// IE 8- don't enum bug keys
module.exports = (
  'constructor,hasOwnProperty,isPrototypeOf,propertyIsEnumerable,toLocaleString,toString,valueOf'
).split(',');


/***/ }),
/* 23 */
/***/ (function(module, exports) {

exports.f = Object.getOwnPropertySymbols;


/***/ }),
/* 24 */
/***/ (function(module, exports, __webpack_require__) {

// 7.1.13 ToObject(argument)
var defined = __webpack_require__(18);
module.exports = function (it) {
  return Object(defined(it));
};


/***/ }),
/* 25 */
/***/ (function(module, exports) {

module.exports = {};


/***/ }),
/* 26 */
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.2 / 15.2.3.5 Object.create(O [, Properties])
var anObject = __webpack_require__(11);
var dPs = __webpack_require__(70);
var enumBugKeys = __webpack_require__(22);
var IE_PROTO = __webpack_require__(20)('IE_PROTO');
var Empty = function () { /* empty */ };
var PROTOTYPE = 'prototype';

// Create object with fake `null` prototype: use iframe Object with cleared prototype
var createDict = function () {
  // Thrash, waste and sodomy: IE GC bug
  var iframe = __webpack_require__(34)('iframe');
  var i = enumBugKeys.length;
  var lt = '<';
  var gt = '>';
  var iframeDocument;
  iframe.style.display = 'none';
  __webpack_require__(71).appendChild(iframe);
  iframe.src = 'javascript:'; // eslint-disable-line no-script-url
  // createDict = iframe.contentWindow.Object;
  // html.removeChild(iframe);
  iframeDocument = iframe.contentWindow.document;
  iframeDocument.open();
  iframeDocument.write(lt + 'script' + gt + 'document.F=Object' + lt + '/script' + gt);
  iframeDocument.close();
  createDict = iframeDocument.F;
  while (i--) delete createDict[PROTOTYPE][enumBugKeys[i]];
  return createDict();
};

module.exports = Object.create || function create(O, Properties) {
  var result;
  if (O !== null) {
    Empty[PROTOTYPE] = anObject(O);
    result = new Empty();
    Empty[PROTOTYPE] = null;
    // add "__proto__" for Object.getPrototypeOf polyfill
    result[IE_PROTO] = O;
  } else result = createDict();
  return Properties === undefined ? result : dPs(result, Properties);
};


/***/ }),
/* 27 */
/***/ (function(module, exports, __webpack_require__) {

var def = __webpack_require__(3).f;
var has = __webpack_require__(5);
var TAG = __webpack_require__(10)('toStringTag');

module.exports = function (it, tag, stat) {
  if (it && !has(it = stat ? it : it.prototype, TAG)) def(it, TAG, { configurable: true, value: tag });
};


/***/ }),
/* 28 */
/***/ (function(module, exports, __webpack_require__) {

exports.f = __webpack_require__(10);


/***/ }),
/* 29 */
/***/ (function(module, exports, __webpack_require__) {

var global = __webpack_require__(1);
var core = __webpack_require__(0);
var LIBRARY = __webpack_require__(14);
var wksExt = __webpack_require__(28);
var defineProperty = __webpack_require__(3).f;
module.exports = function (name) {
  var $Symbol = core.Symbol || (core.Symbol = LIBRARY ? {} : global.Symbol || {});
  if (name.charAt(0) != '_' && !(name in $Symbol)) defineProperty($Symbol, name, { value: wksExt.f(name) });
};


/***/ }),
/* 30 */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["data"]; }());

/***/ }),
/* 31 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = Type;
function Type(props) {
    return props.meta.twitterCardType || tcData.defaultType;
}

/***/ }),
/* 32 */
/***/ (function(module, exports, __webpack_require__) {

// optional / simple context binding
var aFunction = __webpack_require__(50);
module.exports = function (fn, that, length) {
  aFunction(fn);
  if (that === undefined) return fn;
  switch (length) {
    case 1: return function (a) {
      return fn.call(that, a);
    };
    case 2: return function (a, b) {
      return fn.call(that, a, b);
    };
    case 3: return function (a, b, c) {
      return fn.call(that, a, b, c);
    };
  }
  return function (/* ...args */) {
    return fn.apply(that, arguments);
  };
};


/***/ }),
/* 33 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = !__webpack_require__(4) && !__webpack_require__(8)(function () {
  return Object.defineProperty(__webpack_require__(34)('div'), 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),
/* 34 */
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(7);
var document = __webpack_require__(1).document;
// typeof document.createElement is 'object' in old IE
var is = isObject(document) && isObject(document.createElement);
module.exports = function (it) {
  return is ? document.createElement(it) : {};
};


/***/ }),
/* 35 */
/***/ (function(module, exports, __webpack_require__) {

var has = __webpack_require__(5);
var toIObject = __webpack_require__(9);
var arrayIndexOf = __webpack_require__(52)(false);
var IE_PROTO = __webpack_require__(20)('IE_PROTO');

module.exports = function (object, names) {
  var O = toIObject(object);
  var i = 0;
  var result = [];
  var key;
  for (key in O) if (key != IE_PROTO) has(O, key) && result.push(key);
  // Don't enum bug & hidden keys
  while (names.length > i) if (has(O, key = names[i++])) {
    ~arrayIndexOf(result, key) || result.push(key);
  }
  return result;
};


/***/ }),
/* 36 */
/***/ (function(module, exports, __webpack_require__) {

// fallback for non-array-like ES3 and non-enumerable old V8 strings
var cof = __webpack_require__(37);
// eslint-disable-next-line no-prototype-builtins
module.exports = Object('z').propertyIsEnumerable(0) ? Object : function (it) {
  return cof(it) == 'String' ? it.split('') : Object(it);
};


/***/ }),
/* 37 */
/***/ (function(module, exports) {

var toString = {}.toString;

module.exports = function (it) {
  return toString.call(it).slice(8, -1);
};


/***/ }),
/* 38 */
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.9 / 15.2.3.2 Object.getPrototypeOf(O)
var has = __webpack_require__(5);
var toObject = __webpack_require__(24);
var IE_PROTO = __webpack_require__(20)('IE_PROTO');
var ObjectProto = Object.prototype;

module.exports = Object.getPrototypeOf || function (O) {
  O = toObject(O);
  if (has(O, IE_PROTO)) return O[IE_PROTO];
  if (typeof O.constructor == 'function' && O instanceof O.constructor) {
    return O.constructor.prototype;
  } return O instanceof Object ? ObjectProto : null;
};


/***/ }),
/* 39 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

var _iterator = __webpack_require__(65);

var _iterator2 = _interopRequireDefault(_iterator);

var _symbol = __webpack_require__(76);

var _symbol2 = _interopRequireDefault(_symbol);

var _typeof = typeof _symbol2.default === "function" && typeof _iterator2.default === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof _symbol2.default === "function" && obj.constructor === _symbol2.default && obj !== _symbol2.default.prototype ? "symbol" : typeof obj; };

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = typeof _symbol2.default === "function" && _typeof(_iterator2.default) === "symbol" ? function (obj) {
  return typeof obj === "undefined" ? "undefined" : _typeof(obj);
} : function (obj) {
  return obj && typeof _symbol2.default === "function" && obj.constructor === _symbol2.default && obj !== _symbol2.default.prototype ? "symbol" : typeof obj === "undefined" ? "undefined" : _typeof(obj);
};

/***/ }),
/* 40 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var LIBRARY = __webpack_require__(14);
var $export = __webpack_require__(2);
var redefine = __webpack_require__(41);
var hide = __webpack_require__(6);
var Iterators = __webpack_require__(25);
var $iterCreate = __webpack_require__(69);
var setToStringTag = __webpack_require__(27);
var getPrototypeOf = __webpack_require__(38);
var ITERATOR = __webpack_require__(10)('iterator');
var BUGGY = !([].keys && 'next' in [].keys()); // Safari has buggy iterators w/o `next`
var FF_ITERATOR = '@@iterator';
var KEYS = 'keys';
var VALUES = 'values';

var returnThis = function () { return this; };

module.exports = function (Base, NAME, Constructor, next, DEFAULT, IS_SET, FORCED) {
  $iterCreate(Constructor, NAME, next);
  var getMethod = function (kind) {
    if (!BUGGY && kind in proto) return proto[kind];
    switch (kind) {
      case KEYS: return function keys() { return new Constructor(this, kind); };
      case VALUES: return function values() { return new Constructor(this, kind); };
    } return function entries() { return new Constructor(this, kind); };
  };
  var TAG = NAME + ' Iterator';
  var DEF_VALUES = DEFAULT == VALUES;
  var VALUES_BUG = false;
  var proto = Base.prototype;
  var $native = proto[ITERATOR] || proto[FF_ITERATOR] || DEFAULT && proto[DEFAULT];
  var $default = $native || getMethod(DEFAULT);
  var $entries = DEFAULT ? !DEF_VALUES ? $default : getMethod('entries') : undefined;
  var $anyNative = NAME == 'Array' ? proto.entries || $native : $native;
  var methods, key, IteratorPrototype;
  // Fix native
  if ($anyNative) {
    IteratorPrototype = getPrototypeOf($anyNative.call(new Base()));
    if (IteratorPrototype !== Object.prototype && IteratorPrototype.next) {
      // Set @@toStringTag to native iterators
      setToStringTag(IteratorPrototype, TAG, true);
      // fix for some old engines
      if (!LIBRARY && typeof IteratorPrototype[ITERATOR] != 'function') hide(IteratorPrototype, ITERATOR, returnThis);
    }
  }
  // fix Array#{values, @@iterator}.name in V8 / FF
  if (DEF_VALUES && $native && $native.name !== VALUES) {
    VALUES_BUG = true;
    $default = function values() { return $native.call(this); };
  }
  // Define iterator
  if ((!LIBRARY || FORCED) && (BUGGY || VALUES_BUG || !proto[ITERATOR])) {
    hide(proto, ITERATOR, $default);
  }
  // Plug for library
  Iterators[NAME] = $default;
  Iterators[TAG] = returnThis;
  if (DEFAULT) {
    methods = {
      values: DEF_VALUES ? $default : getMethod(VALUES),
      keys: IS_SET ? $default : getMethod(KEYS),
      entries: $entries
    };
    if (FORCED) for (key in methods) {
      if (!(key in proto)) redefine(proto, key, methods[key]);
    } else $export($export.P + $export.F * (BUGGY || VALUES_BUG), NAME, methods);
  }
  return methods;
};


/***/ }),
/* 41 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(6);


/***/ }),
/* 42 */
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.7 / 15.2.3.4 Object.getOwnPropertyNames(O)
var $keys = __webpack_require__(35);
var hiddenKeys = __webpack_require__(22).concat('length', 'prototype');

exports.f = Object.getOwnPropertyNames || function getOwnPropertyNames(O) {
  return $keys(O, hiddenKeys);
};


/***/ }),
/* 43 */
/***/ (function(module, exports, __webpack_require__) {

var pIE = __webpack_require__(16);
var createDesc = __webpack_require__(12);
var toIObject = __webpack_require__(9);
var toPrimitive = __webpack_require__(17);
var has = __webpack_require__(5);
var IE8_DOM_DEFINE = __webpack_require__(33);
var gOPD = Object.getOwnPropertyDescriptor;

exports.f = __webpack_require__(4) ? gOPD : function getOwnPropertyDescriptor(O, P) {
  O = toIObject(O);
  P = toPrimitive(P, true);
  if (IE8_DOM_DEFINE) try {
    return gOPD(O, P);
  } catch (e) { /* empty */ }
  if (has(O, P)) return createDesc(!pIE.f.call(O, P), O[P]);
};


/***/ }),
/* 44 */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["i18n"]; }());

/***/ }),
/* 45 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_extends__ = __webpack_require__(46);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_extends___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_extends__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_babel_runtime_core_js_object_get_prototype_of__ = __webpack_require__(55);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_babel_runtime_core_js_object_get_prototype_of___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_babel_runtime_core_js_object_get_prototype_of__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_babel_runtime_helpers_classCallCheck__ = __webpack_require__(59);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_babel_runtime_helpers_classCallCheck___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_babel_runtime_helpers_classCallCheck__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_babel_runtime_helpers_createClass__ = __webpack_require__(60);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_babel_runtime_helpers_createClass___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3_babel_runtime_helpers_createClass__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4_babel_runtime_helpers_possibleConstructorReturn__ = __webpack_require__(64);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4_babel_runtime_helpers_possibleConstructorReturn___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_4_babel_runtime_helpers_possibleConstructorReturn__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5_babel_runtime_helpers_inherits__ = __webpack_require__(86);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5_babel_runtime_helpers_inherits___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_5_babel_runtime_helpers_inherits__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__wordpress_data__ = __webpack_require__(30);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__wordpress_data___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_6__wordpress_data__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__wordpress_editor__ = __webpack_require__(94);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__wordpress_editor___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_7__wordpress_editor__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__wordpress_components__ = __webpack_require__(95);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__wordpress_components___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_8__wordpress_components__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__wordpress_element__ = __webpack_require__(96);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__wordpress_element___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_9__wordpress_element__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_10__wordpress_editPost__ = __webpack_require__(97);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_10__wordpress_editPost___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_10__wordpress_editPost__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_11__wordpress_compose__ = __webpack_require__(98);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_11__wordpress_compose___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_11__wordpress_compose__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__ = __webpack_require__(44);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_12__wordpress_i18n___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_13__wordpress_plugins__ = __webpack_require__(99);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_13__wordpress_plugins___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_13__wordpress_plugins__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_14__components_cardType__ = __webpack_require__(31);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_15__components_preview__ = __webpack_require__(100);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_16__style_scss__ = __webpack_require__(104);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_16__style_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_16__style_scss__);






/**
 * WordPress dependencies
 */
















/**
 * Custom dependencies
 */





var JM_Twitter_Cards = function (_Component) {
    __WEBPACK_IMPORTED_MODULE_5_babel_runtime_helpers_inherits___default()(JM_Twitter_Cards, _Component);

    function JM_Twitter_Cards() {
        __WEBPACK_IMPORTED_MODULE_2_babel_runtime_helpers_classCallCheck___default()(this, JM_Twitter_Cards);

        var _this = __WEBPACK_IMPORTED_MODULE_4_babel_runtime_helpers_possibleConstructorReturn___default()(this, (JM_Twitter_Cards.__proto__ || __WEBPACK_IMPORTED_MODULE_1_babel_runtime_core_js_object_get_prototype_of___default()(JM_Twitter_Cards)).apply(this, arguments));

        _this.state = {
            isOpen: false
        };
        return _this;
    }

    __WEBPACK_IMPORTED_MODULE_3_babel_runtime_helpers_createClass___default()(JM_Twitter_Cards, [{
        key: "render",
        value: function render() {
            var _this2 = this;

            var _props = this.props,
                _props$meta = _props.meta,
                twitterCardType = _props$meta.twitterCardType,
                cardTitle = _props$meta.cardTitle,
                cardDesc = _props$meta.cardDesc,
                cardImageID = _props$meta.cardImageID,
                cardImage = _props$meta.cardImage,
                cardImageAlt = _props$meta.cardImageAlt,
                cardPlayer = _props$meta.cardPlayer,
                cardPlayerWidth = _props$meta.cardPlayerWidth,
                cardPlayerHeight = _props$meta.cardPlayerHeight,
                updatePostMeta = _props.updatePostMeta;
            var isOpen = this.state.isOpen;


            return wp.element.createElement(
                __WEBPACK_IMPORTED_MODULE_9__wordpress_element__["Fragment"],
                null,
                wp.element.createElement(
                    __WEBPACK_IMPORTED_MODULE_10__wordpress_editPost__["PluginSidebar"],
                    {
                        icon: "twitter",
                        name: "jm-tc-sidebar",
                        title: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Twitter Cards settings', 'jm-tc-gut') },
                    wp.element.createElement(
                        __WEBPACK_IMPORTED_MODULE_8__wordpress_components__["PanelBody"],
                        { title: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Main settings & preview', 'jm-tc-gut') },
                        wp.element.createElement(
                            "p",
                            { className: "description smaller" },
                            Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('The preview button allows you to change main twitter cards settings and see what it might look like on Twitter.', 'jm-tc-gut')
                        ),
                        wp.element.createElement(
                            "p",
                            { className: "description smaller" },
                            Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('On no account this could replace the Twitter cards validator', 'jm-tc-gut')
                        ),
                        wp.element.createElement(
                            __WEBPACK_IMPORTED_MODULE_8__wordpress_components__["Placeholder"],
                            {
                                instructions: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Preview and set your cards', 'jm-tc-gut'),
                                icon: isOpen ? "hidden" : "visibility",
                                label: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('preview', 'jm-tc-gut') },
                            wp.element.createElement(
                                "div",
                                { className: "buttons" },
                                wp.element.createElement(
                                    __WEBPACK_IMPORTED_MODULE_8__wordpress_components__["Button"],
                                    {
                                        isDefault: true,
                                        onClick: function onClick() {
                                            return _this2.setState({ isOpen: true });
                                        } },
                                    Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('open modal', 'jm-tc-gut')
                                ),
                                wp.element.createElement(
                                    __WEBPACK_IMPORTED_MODULE_8__wordpress_components__["Button"],
                                    {
                                        href: "https://cards-dev.twitter.com/validator" },
                                    Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('check validator', 'jm-tc-gut')
                                )
                            )
                        ),
                        isOpen ? wp.element.createElement(
                            __WEBPACK_IMPORTED_MODULE_8__wordpress_components__["Modal"],
                            {
                                title: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Twitter Cards', 'jm-tc-gut'),
                                closeButtonLabel: 'close',
                                onRequestClose: function onRequestClose() {
                                    return _this2.setState({ isOpen: false });
                                } },
                            wp.element.createElement(__WEBPACK_IMPORTED_MODULE_15__components_preview__["a" /* Preview */], { props: this.props }),
                            wp.element.createElement(
                                "div",
                                { className: "tc-fields-container" },
                                wp.element.createElement(__WEBPACK_IMPORTED_MODULE_8__wordpress_components__["SelectControl"], {
                                    label: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Card Type', 'jm-tc-gut'),
                                    value: Object(__WEBPACK_IMPORTED_MODULE_14__components_cardType__["a" /* Type */])(this.props),
                                    options: [{ label: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Summary', 'jm-tc-gut'), value: 'summary' }, {
                                        label: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Summary Large Image', 'jm-tc-gut'),
                                        value: 'summary_large_image'
                                    }, { label: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Player', 'jm-tc-gut'), value: 'player' }, { label: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Application', 'jm-tc-gut'), value: 'app' }],
                                    onChange: function onChange(value) {
                                        updatePostMeta({ twitterCardType: value || '' });
                                    }
                                })
                            ),
                            wp.element.createElement(
                                "div",
                                { className: "tc-fields-container" },
                                wp.element.createElement(__WEBPACK_IMPORTED_MODULE_8__wordpress_components__["TextControl"], {
                                    type: "text",
                                    label: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Custom title', 'jm-tc-gut'),
                                    help: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Best is under 55 chars. If no set default card title would be post title', 'jm-tc-gut'),
                                    value: cardTitle,
                                    placeholder: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Enter custom title…', 'jm-tc-gut'),
                                    onChange: function onChange(value) {
                                        updatePostMeta({ cardTitle: value || '' });
                                    }
                                })
                            ),
                            wp.element.createElement(
                                "div",
                                { className: "tc-fields-container" },
                                wp.element.createElement(__WEBPACK_IMPORTED_MODULE_8__wordpress_components__["TextareaControl"], {
                                    label: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Card description', 'jm-tc-gut'),
                                    help: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('200 chars max but it is better to keep it short, 120-130 chars is fine. By default description will be automatically generated or retrieved from a SEO plugin such as Yoast or All in One SEO but you can override this here.', 'jm-tc-gut'),
                                    value: cardDesc,
                                    onChange: function onChange(value) {
                                        updatePostMeta({ cardDesc: value || '' });
                                    }
                                })
                            ),
                            'player' === twitterCardType && wp.element.createElement(
                                "div",
                                { className: "tc-fields-container" },
                                wp.element.createElement(__WEBPACK_IMPORTED_MODULE_8__wordpress_components__["TextControl"], {
                                    type: "url",
                                    label: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Player URL', 'jm-tc-gut'),
                                    value: cardPlayer,
                                    placeholder: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Enter URL…', 'jm-tc-gut'),
                                    onChange: function onChange(value) {
                                        updatePostMeta({ cardPlayer: value || '' });
                                    }
                                }),
                                wp.element.createElement(__WEBPACK_IMPORTED_MODULE_8__wordpress_components__["RangeControl"], {
                                    label: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Player Width', 'jm-tc-gut'),
                                    value: Number(cardPlayerWidth),
                                    min: 262,
                                    max: 1000,
                                    onChange: function onChange(value) {
                                        updatePostMeta({ cardPlayerWidth: value || '' });
                                    }
                                }),
                                wp.element.createElement(__WEBPACK_IMPORTED_MODULE_8__wordpress_components__["RangeControl"], {
                                    label: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Player Height', 'jm-tc-gut'),
                                    value: Number(cardPlayerHeight),
                                    min: 196,
                                    max: 1000,
                                    onChange: function onChange(value) {
                                        updatePostMeta({ cardPlayerHeight: value || '' });
                                    }
                                })
                            ),
                            wp.element.createElement(
                                "div",
                                { className: "tc-mb buttons" },
                                wp.element.createElement(
                                    __WEBPACK_IMPORTED_MODULE_8__wordpress_components__["Button"],
                                    { isDefault: true, onClick: function onClick() {
                                            return _this2.setState({ isOpen: false });
                                        } },
                                    Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Close', 'jm-tc-gut')
                                ),
                                wp.element.createElement(
                                    __WEBPACK_IMPORTED_MODULE_8__wordpress_components__["Button"],
                                    {
                                        href: "https://cards-dev.twitter.com/validator" },
                                    Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('check validator', 'jm-tc-gut')
                                )
                            )
                        ) : null
                    ),
                    wp.element.createElement(
                        __WEBPACK_IMPORTED_MODULE_8__wordpress_components__["PanelBody"],
                        { title: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])("Image Settings", 'jm-tc-gut') },
                        wp.element.createElement(
                            "p",
                            { className: "description smaller" },
                            Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])("Depending on your card type, please use appropriate ratio. Best is :", 'jm-tc-gut')
                        ),
                        wp.element.createElement(
                            "ul",
                            { className: "image-instructions" },
                            wp.element.createElement(
                                "li",
                                null,
                                Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])("1:1 for summary card (square)", 'jm-tc-gut')
                            ),
                            wp.element.createElement(
                                "li",
                                null,
                                Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])("2:1 (rectangle) for summary large image", 'jm-tc-gut')
                            )
                        ),
                        wp.element.createElement(
                            "p",
                            { className: "description smaller" },
                            Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])("Using featured image is highly recommended (if supported by your post type which is the case for posts) but you can override this here.", 'jm-tc-gut')
                        ),
                        !cardImage && wp.element.createElement(
                            __WEBPACK_IMPORTED_MODULE_8__wordpress_components__["Placeholder"],
                            {
                                instructions: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Override featured image and default image for this post', 'jm-tc-gut'),
                                icon: "format-image",
                                label: "Image"
                            },
                            wp.element.createElement(__WEBPACK_IMPORTED_MODULE_7__wordpress_editor__["MediaUpload"], {
                                onSelect: function onSelect(media) {
                                    return updatePostMeta({
                                        cardImage: media.url,
                                        cardImageID: media.id
                                    });
                                },
                                type: "image",
                                render: function render(_ref) {
                                    var open = _ref.open;
                                    return wp.element.createElement(
                                        __WEBPACK_IMPORTED_MODULE_8__wordpress_components__["Button"],
                                        { isLarge: true, onClick: open },
                                        Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])("Insert from Media Library", 'jm-tc-gut')
                                    );
                                }
                            })
                        ),
                        cardImage && wp.element.createElement(
                            __WEBPACK_IMPORTED_MODULE_8__wordpress_components__["Placeholder"],
                            {
                                instructions: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])("Change twitter Image source", 'jm-tc-gut'),
                                icon: "format-image",
                                label: "Image" },
                            wp.element.createElement(
                                "div",
                                { className: "thumbnail" },
                                wp.element.createElement(
                                    "div",
                                    { className: "centered" },
                                    wp.element.createElement(__WEBPACK_IMPORTED_MODULE_7__wordpress_editor__["MediaUpload"], {
                                        onSelect: function onSelect(media) {
                                            return updatePostMeta({
                                                cardImage: media.url,
                                                cardImageID: media.id
                                            });
                                        },
                                        type: "image",
                                        value: cardImageID,
                                        render: function render(_ref2) {
                                            var open = _ref2.open;
                                            return wp.element.createElement("img", { src: cardImage, alt: cardImageAlt || '',
                                                className: "tc-image-overview", onClick: open });
                                        }
                                    })
                                )
                            )
                        ),
                        wp.element.createElement(
                            "div",
                            { className: "tc-mb" },
                            wp.element.createElement(__WEBPACK_IMPORTED_MODULE_8__wordpress_components__["TextareaControl"], {
                                label: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Card Image alt', 'jm-tc-gut'),
                                help: Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])('Alt text - accessibility for your Twitter Image', 'jm-tc-gut'),
                                value: cardImageAlt,
                                onChange: function onChange(value) {
                                    updatePostMeta({ cardImageAlt: value || '' });
                                }
                            })
                        )
                    )
                ),
                wp.element.createElement(
                    __WEBPACK_IMPORTED_MODULE_10__wordpress_editPost__["PluginSidebarMoreMenuItem"],
                    {
                        icon: "twitter",
                        target: "jm-tc-sidebar"
                    },
                    Object(__WEBPACK_IMPORTED_MODULE_12__wordpress_i18n__["__"])("Twitter Cards", "jm-tc-gut")
                )
            );
        }
    }]);

    return JM_Twitter_Cards;
}(__WEBPACK_IMPORTED_MODULE_9__wordpress_element__["Component"]);

/**
 * This is how it's done in core
 */


var applyWithSelect = Object(__WEBPACK_IMPORTED_MODULE_6__wordpress_data__["withSelect"])(function (select) {
    return {
        meta: select('core/editor').getEditedPostAttribute('meta')
    };
});

var applyWithDispatch = Object(__WEBPACK_IMPORTED_MODULE_6__wordpress_data__["withDispatch"])(function (dispatch, _ref3) {
    var meta = _ref3.meta;

    return {
        updatePostMeta: function updatePostMeta(Meta) {
            dispatch('core/editor').editPost({ meta: __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_extends___default()({}, meta, Meta) }); // merge
        }
    };
});

/**
 * Combine components
 */
var render = Object(__WEBPACK_IMPORTED_MODULE_11__wordpress_compose__["compose"])(applyWithSelect, applyWithDispatch)(JM_Twitter_Cards);

/**
 * Custom plugin register in GUT
 */
Object(__WEBPACK_IMPORTED_MODULE_13__wordpress_plugins__["registerPlugin"])('jm-tc-sidebar', {
    icon: 'twitter',
    render: render
});

/***/ }),
/* 46 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

var _assign = __webpack_require__(47);

var _assign2 = _interopRequireDefault(_assign);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = _assign2.default || function (target) {
  for (var i = 1; i < arguments.length; i++) {
    var source = arguments[i];

    for (var key in source) {
      if (Object.prototype.hasOwnProperty.call(source, key)) {
        target[key] = source[key];
      }
    }
  }

  return target;
};

/***/ }),
/* 47 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(48), __esModule: true };

/***/ }),
/* 48 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(49);
module.exports = __webpack_require__(0).Object.assign;


/***/ }),
/* 49 */
/***/ (function(module, exports, __webpack_require__) {

// 19.1.3.1 Object.assign(target, source)
var $export = __webpack_require__(2);

$export($export.S + $export.F, 'Object', { assign: __webpack_require__(51) });


/***/ }),
/* 50 */
/***/ (function(module, exports) {

module.exports = function (it) {
  if (typeof it != 'function') throw TypeError(it + ' is not a function!');
  return it;
};


/***/ }),
/* 51 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

// 19.1.2.1 Object.assign(target, source, ...)
var getKeys = __webpack_require__(13);
var gOPS = __webpack_require__(23);
var pIE = __webpack_require__(16);
var toObject = __webpack_require__(24);
var IObject = __webpack_require__(36);
var $assign = Object.assign;

// should work with symbols and should have deterministic property order (V8 bug)
module.exports = !$assign || __webpack_require__(8)(function () {
  var A = {};
  var B = {};
  // eslint-disable-next-line no-undef
  var S = Symbol();
  var K = 'abcdefghijklmnopqrst';
  A[S] = 7;
  K.split('').forEach(function (k) { B[k] = k; });
  return $assign({}, A)[S] != 7 || Object.keys($assign({}, B)).join('') != K;
}) ? function assign(target, source) { // eslint-disable-line no-unused-vars
  var T = toObject(target);
  var aLen = arguments.length;
  var index = 1;
  var getSymbols = gOPS.f;
  var isEnum = pIE.f;
  while (aLen > index) {
    var S = IObject(arguments[index++]);
    var keys = getSymbols ? getKeys(S).concat(getSymbols(S)) : getKeys(S);
    var length = keys.length;
    var j = 0;
    var key;
    while (length > j) if (isEnum.call(S, key = keys[j++])) T[key] = S[key];
  } return T;
} : $assign;


/***/ }),
/* 52 */
/***/ (function(module, exports, __webpack_require__) {

// false -> Array#indexOf
// true  -> Array#includes
var toIObject = __webpack_require__(9);
var toLength = __webpack_require__(53);
var toAbsoluteIndex = __webpack_require__(54);
module.exports = function (IS_INCLUDES) {
  return function ($this, el, fromIndex) {
    var O = toIObject($this);
    var length = toLength(O.length);
    var index = toAbsoluteIndex(fromIndex, length);
    var value;
    // Array#includes uses SameValueZero equality algorithm
    // eslint-disable-next-line no-self-compare
    if (IS_INCLUDES && el != el) while (length > index) {
      value = O[index++];
      // eslint-disable-next-line no-self-compare
      if (value != value) return true;
    // Array#indexOf ignores holes, Array#includes - not
    } else for (;length > index; index++) if (IS_INCLUDES || index in O) {
      if (O[index] === el) return IS_INCLUDES || index || 0;
    } return !IS_INCLUDES && -1;
  };
};


/***/ }),
/* 53 */
/***/ (function(module, exports, __webpack_require__) {

// 7.1.15 ToLength
var toInteger = __webpack_require__(19);
var min = Math.min;
module.exports = function (it) {
  return it > 0 ? min(toInteger(it), 0x1fffffffffffff) : 0; // pow(2, 53) - 1 == 9007199254740991
};


/***/ }),
/* 54 */
/***/ (function(module, exports, __webpack_require__) {

var toInteger = __webpack_require__(19);
var max = Math.max;
var min = Math.min;
module.exports = function (index, length) {
  index = toInteger(index);
  return index < 0 ? max(index + length, 0) : min(index, length);
};


/***/ }),
/* 55 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(56), __esModule: true };

/***/ }),
/* 56 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(57);
module.exports = __webpack_require__(0).Object.getPrototypeOf;


/***/ }),
/* 57 */
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.9 Object.getPrototypeOf(O)
var toObject = __webpack_require__(24);
var $getPrototypeOf = __webpack_require__(38);

__webpack_require__(58)('getPrototypeOf', function () {
  return function getPrototypeOf(it) {
    return $getPrototypeOf(toObject(it));
  };
});


/***/ }),
/* 58 */
/***/ (function(module, exports, __webpack_require__) {

// most Object methods by ES6 should accept primitives
var $export = __webpack_require__(2);
var core = __webpack_require__(0);
var fails = __webpack_require__(8);
module.exports = function (KEY, exec) {
  var fn = (core.Object || {})[KEY] || Object[KEY];
  var exp = {};
  exp[KEY] = exec(fn);
  $export($export.S + $export.F * fails(function () { fn(1); }), 'Object', exp);
};


/***/ }),
/* 59 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

exports.default = function (instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
};

/***/ }),
/* 60 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

var _defineProperty = __webpack_require__(61);

var _defineProperty2 = _interopRequireDefault(_defineProperty);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = function () {
  function defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
      var descriptor = props[i];
      descriptor.enumerable = descriptor.enumerable || false;
      descriptor.configurable = true;
      if ("value" in descriptor) descriptor.writable = true;
      (0, _defineProperty2.default)(target, descriptor.key, descriptor);
    }
  }

  return function (Constructor, protoProps, staticProps) {
    if (protoProps) defineProperties(Constructor.prototype, protoProps);
    if (staticProps) defineProperties(Constructor, staticProps);
    return Constructor;
  };
}();

/***/ }),
/* 61 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(62), __esModule: true };

/***/ }),
/* 62 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(63);
var $Object = __webpack_require__(0).Object;
module.exports = function defineProperty(it, key, desc) {
  return $Object.defineProperty(it, key, desc);
};


/***/ }),
/* 63 */
/***/ (function(module, exports, __webpack_require__) {

var $export = __webpack_require__(2);
// 19.1.2.4 / 15.2.3.6 Object.defineProperty(O, P, Attributes)
$export($export.S + $export.F * !__webpack_require__(4), 'Object', { defineProperty: __webpack_require__(3).f });


/***/ }),
/* 64 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

var _typeof2 = __webpack_require__(39);

var _typeof3 = _interopRequireDefault(_typeof2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = function (self, call) {
  if (!self) {
    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
  }

  return call && ((typeof call === "undefined" ? "undefined" : (0, _typeof3.default)(call)) === "object" || typeof call === "function") ? call : self;
};

/***/ }),
/* 65 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(66), __esModule: true };

/***/ }),
/* 66 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(67);
__webpack_require__(72);
module.exports = __webpack_require__(28).f('iterator');


/***/ }),
/* 67 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var $at = __webpack_require__(68)(true);

// 21.1.3.27 String.prototype[@@iterator]()
__webpack_require__(40)(String, 'String', function (iterated) {
  this._t = String(iterated); // target
  this._i = 0;                // next index
// 21.1.5.2.1 %StringIteratorPrototype%.next()
}, function () {
  var O = this._t;
  var index = this._i;
  var point;
  if (index >= O.length) return { value: undefined, done: true };
  point = $at(O, index);
  this._i += point.length;
  return { value: point, done: false };
});


/***/ }),
/* 68 */
/***/ (function(module, exports, __webpack_require__) {

var toInteger = __webpack_require__(19);
var defined = __webpack_require__(18);
// true  -> String#at
// false -> String#codePointAt
module.exports = function (TO_STRING) {
  return function (that, pos) {
    var s = String(defined(that));
    var i = toInteger(pos);
    var l = s.length;
    var a, b;
    if (i < 0 || i >= l) return TO_STRING ? '' : undefined;
    a = s.charCodeAt(i);
    return a < 0xd800 || a > 0xdbff || i + 1 === l || (b = s.charCodeAt(i + 1)) < 0xdc00 || b > 0xdfff
      ? TO_STRING ? s.charAt(i) : a
      : TO_STRING ? s.slice(i, i + 2) : (a - 0xd800 << 10) + (b - 0xdc00) + 0x10000;
  };
};


/***/ }),
/* 69 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var create = __webpack_require__(26);
var descriptor = __webpack_require__(12);
var setToStringTag = __webpack_require__(27);
var IteratorPrototype = {};

// 25.1.2.1.1 %IteratorPrototype%[@@iterator]()
__webpack_require__(6)(IteratorPrototype, __webpack_require__(10)('iterator'), function () { return this; });

module.exports = function (Constructor, NAME, next) {
  Constructor.prototype = create(IteratorPrototype, { next: descriptor(1, next) });
  setToStringTag(Constructor, NAME + ' Iterator');
};


/***/ }),
/* 70 */
/***/ (function(module, exports, __webpack_require__) {

var dP = __webpack_require__(3);
var anObject = __webpack_require__(11);
var getKeys = __webpack_require__(13);

module.exports = __webpack_require__(4) ? Object.defineProperties : function defineProperties(O, Properties) {
  anObject(O);
  var keys = getKeys(Properties);
  var length = keys.length;
  var i = 0;
  var P;
  while (length > i) dP.f(O, P = keys[i++], Properties[P]);
  return O;
};


/***/ }),
/* 71 */
/***/ (function(module, exports, __webpack_require__) {

var document = __webpack_require__(1).document;
module.exports = document && document.documentElement;


/***/ }),
/* 72 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(73);
var global = __webpack_require__(1);
var hide = __webpack_require__(6);
var Iterators = __webpack_require__(25);
var TO_STRING_TAG = __webpack_require__(10)('toStringTag');

var DOMIterables = ('CSSRuleList,CSSStyleDeclaration,CSSValueList,ClientRectList,DOMRectList,DOMStringList,' +
  'DOMTokenList,DataTransferItemList,FileList,HTMLAllCollection,HTMLCollection,HTMLFormElement,HTMLSelectElement,' +
  'MediaList,MimeTypeArray,NamedNodeMap,NodeList,PaintRequestList,Plugin,PluginArray,SVGLengthList,SVGNumberList,' +
  'SVGPathSegList,SVGPointList,SVGStringList,SVGTransformList,SourceBufferList,StyleSheetList,TextTrackCueList,' +
  'TextTrackList,TouchList').split(',');

for (var i = 0; i < DOMIterables.length; i++) {
  var NAME = DOMIterables[i];
  var Collection = global[NAME];
  var proto = Collection && Collection.prototype;
  if (proto && !proto[TO_STRING_TAG]) hide(proto, TO_STRING_TAG, NAME);
  Iterators[NAME] = Iterators.Array;
}


/***/ }),
/* 73 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var addToUnscopables = __webpack_require__(74);
var step = __webpack_require__(75);
var Iterators = __webpack_require__(25);
var toIObject = __webpack_require__(9);

// 22.1.3.4 Array.prototype.entries()
// 22.1.3.13 Array.prototype.keys()
// 22.1.3.29 Array.prototype.values()
// 22.1.3.30 Array.prototype[@@iterator]()
module.exports = __webpack_require__(40)(Array, 'Array', function (iterated, kind) {
  this._t = toIObject(iterated); // target
  this._i = 0;                   // next index
  this._k = kind;                // kind
// 22.1.5.2.1 %ArrayIteratorPrototype%.next()
}, function () {
  var O = this._t;
  var kind = this._k;
  var index = this._i++;
  if (!O || index >= O.length) {
    this._t = undefined;
    return step(1);
  }
  if (kind == 'keys') return step(0, index);
  if (kind == 'values') return step(0, O[index]);
  return step(0, [index, O[index]]);
}, 'values');

// argumentsList[@@iterator] is %ArrayProto_values% (9.4.4.6, 9.4.4.7)
Iterators.Arguments = Iterators.Array;

addToUnscopables('keys');
addToUnscopables('values');
addToUnscopables('entries');


/***/ }),
/* 74 */
/***/ (function(module, exports) {

module.exports = function () { /* empty */ };


/***/ }),
/* 75 */
/***/ (function(module, exports) {

module.exports = function (done, value) {
  return { value: value, done: !!done };
};


/***/ }),
/* 76 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(77), __esModule: true };

/***/ }),
/* 77 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(78);
__webpack_require__(83);
__webpack_require__(84);
__webpack_require__(85);
module.exports = __webpack_require__(0).Symbol;


/***/ }),
/* 78 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

// ECMAScript 6 symbols shim
var global = __webpack_require__(1);
var has = __webpack_require__(5);
var DESCRIPTORS = __webpack_require__(4);
var $export = __webpack_require__(2);
var redefine = __webpack_require__(41);
var META = __webpack_require__(79).KEY;
var $fails = __webpack_require__(8);
var shared = __webpack_require__(21);
var setToStringTag = __webpack_require__(27);
var uid = __webpack_require__(15);
var wks = __webpack_require__(10);
var wksExt = __webpack_require__(28);
var wksDefine = __webpack_require__(29);
var enumKeys = __webpack_require__(80);
var isArray = __webpack_require__(81);
var anObject = __webpack_require__(11);
var isObject = __webpack_require__(7);
var toIObject = __webpack_require__(9);
var toPrimitive = __webpack_require__(17);
var createDesc = __webpack_require__(12);
var _create = __webpack_require__(26);
var gOPNExt = __webpack_require__(82);
var $GOPD = __webpack_require__(43);
var $DP = __webpack_require__(3);
var $keys = __webpack_require__(13);
var gOPD = $GOPD.f;
var dP = $DP.f;
var gOPN = gOPNExt.f;
var $Symbol = global.Symbol;
var $JSON = global.JSON;
var _stringify = $JSON && $JSON.stringify;
var PROTOTYPE = 'prototype';
var HIDDEN = wks('_hidden');
var TO_PRIMITIVE = wks('toPrimitive');
var isEnum = {}.propertyIsEnumerable;
var SymbolRegistry = shared('symbol-registry');
var AllSymbols = shared('symbols');
var OPSymbols = shared('op-symbols');
var ObjectProto = Object[PROTOTYPE];
var USE_NATIVE = typeof $Symbol == 'function';
var QObject = global.QObject;
// Don't use setters in Qt Script, https://github.com/zloirock/core-js/issues/173
var setter = !QObject || !QObject[PROTOTYPE] || !QObject[PROTOTYPE].findChild;

// fallback for old Android, https://code.google.com/p/v8/issues/detail?id=687
var setSymbolDesc = DESCRIPTORS && $fails(function () {
  return _create(dP({}, 'a', {
    get: function () { return dP(this, 'a', { value: 7 }).a; }
  })).a != 7;
}) ? function (it, key, D) {
  var protoDesc = gOPD(ObjectProto, key);
  if (protoDesc) delete ObjectProto[key];
  dP(it, key, D);
  if (protoDesc && it !== ObjectProto) dP(ObjectProto, key, protoDesc);
} : dP;

var wrap = function (tag) {
  var sym = AllSymbols[tag] = _create($Symbol[PROTOTYPE]);
  sym._k = tag;
  return sym;
};

var isSymbol = USE_NATIVE && typeof $Symbol.iterator == 'symbol' ? function (it) {
  return typeof it == 'symbol';
} : function (it) {
  return it instanceof $Symbol;
};

var $defineProperty = function defineProperty(it, key, D) {
  if (it === ObjectProto) $defineProperty(OPSymbols, key, D);
  anObject(it);
  key = toPrimitive(key, true);
  anObject(D);
  if (has(AllSymbols, key)) {
    if (!D.enumerable) {
      if (!has(it, HIDDEN)) dP(it, HIDDEN, createDesc(1, {}));
      it[HIDDEN][key] = true;
    } else {
      if (has(it, HIDDEN) && it[HIDDEN][key]) it[HIDDEN][key] = false;
      D = _create(D, { enumerable: createDesc(0, false) });
    } return setSymbolDesc(it, key, D);
  } return dP(it, key, D);
};
var $defineProperties = function defineProperties(it, P) {
  anObject(it);
  var keys = enumKeys(P = toIObject(P));
  var i = 0;
  var l = keys.length;
  var key;
  while (l > i) $defineProperty(it, key = keys[i++], P[key]);
  return it;
};
var $create = function create(it, P) {
  return P === undefined ? _create(it) : $defineProperties(_create(it), P);
};
var $propertyIsEnumerable = function propertyIsEnumerable(key) {
  var E = isEnum.call(this, key = toPrimitive(key, true));
  if (this === ObjectProto && has(AllSymbols, key) && !has(OPSymbols, key)) return false;
  return E || !has(this, key) || !has(AllSymbols, key) || has(this, HIDDEN) && this[HIDDEN][key] ? E : true;
};
var $getOwnPropertyDescriptor = function getOwnPropertyDescriptor(it, key) {
  it = toIObject(it);
  key = toPrimitive(key, true);
  if (it === ObjectProto && has(AllSymbols, key) && !has(OPSymbols, key)) return;
  var D = gOPD(it, key);
  if (D && has(AllSymbols, key) && !(has(it, HIDDEN) && it[HIDDEN][key])) D.enumerable = true;
  return D;
};
var $getOwnPropertyNames = function getOwnPropertyNames(it) {
  var names = gOPN(toIObject(it));
  var result = [];
  var i = 0;
  var key;
  while (names.length > i) {
    if (!has(AllSymbols, key = names[i++]) && key != HIDDEN && key != META) result.push(key);
  } return result;
};
var $getOwnPropertySymbols = function getOwnPropertySymbols(it) {
  var IS_OP = it === ObjectProto;
  var names = gOPN(IS_OP ? OPSymbols : toIObject(it));
  var result = [];
  var i = 0;
  var key;
  while (names.length > i) {
    if (has(AllSymbols, key = names[i++]) && (IS_OP ? has(ObjectProto, key) : true)) result.push(AllSymbols[key]);
  } return result;
};

// 19.4.1.1 Symbol([description])
if (!USE_NATIVE) {
  $Symbol = function Symbol() {
    if (this instanceof $Symbol) throw TypeError('Symbol is not a constructor!');
    var tag = uid(arguments.length > 0 ? arguments[0] : undefined);
    var $set = function (value) {
      if (this === ObjectProto) $set.call(OPSymbols, value);
      if (has(this, HIDDEN) && has(this[HIDDEN], tag)) this[HIDDEN][tag] = false;
      setSymbolDesc(this, tag, createDesc(1, value));
    };
    if (DESCRIPTORS && setter) setSymbolDesc(ObjectProto, tag, { configurable: true, set: $set });
    return wrap(tag);
  };
  redefine($Symbol[PROTOTYPE], 'toString', function toString() {
    return this._k;
  });

  $GOPD.f = $getOwnPropertyDescriptor;
  $DP.f = $defineProperty;
  __webpack_require__(42).f = gOPNExt.f = $getOwnPropertyNames;
  __webpack_require__(16).f = $propertyIsEnumerable;
  __webpack_require__(23).f = $getOwnPropertySymbols;

  if (DESCRIPTORS && !__webpack_require__(14)) {
    redefine(ObjectProto, 'propertyIsEnumerable', $propertyIsEnumerable, true);
  }

  wksExt.f = function (name) {
    return wrap(wks(name));
  };
}

$export($export.G + $export.W + $export.F * !USE_NATIVE, { Symbol: $Symbol });

for (var es6Symbols = (
  // 19.4.2.2, 19.4.2.3, 19.4.2.4, 19.4.2.6, 19.4.2.8, 19.4.2.9, 19.4.2.10, 19.4.2.11, 19.4.2.12, 19.4.2.13, 19.4.2.14
  'hasInstance,isConcatSpreadable,iterator,match,replace,search,species,split,toPrimitive,toStringTag,unscopables'
).split(','), j = 0; es6Symbols.length > j;)wks(es6Symbols[j++]);

for (var wellKnownSymbols = $keys(wks.store), k = 0; wellKnownSymbols.length > k;) wksDefine(wellKnownSymbols[k++]);

$export($export.S + $export.F * !USE_NATIVE, 'Symbol', {
  // 19.4.2.1 Symbol.for(key)
  'for': function (key) {
    return has(SymbolRegistry, key += '')
      ? SymbolRegistry[key]
      : SymbolRegistry[key] = $Symbol(key);
  },
  // 19.4.2.5 Symbol.keyFor(sym)
  keyFor: function keyFor(sym) {
    if (!isSymbol(sym)) throw TypeError(sym + ' is not a symbol!');
    for (var key in SymbolRegistry) if (SymbolRegistry[key] === sym) return key;
  },
  useSetter: function () { setter = true; },
  useSimple: function () { setter = false; }
});

$export($export.S + $export.F * !USE_NATIVE, 'Object', {
  // 19.1.2.2 Object.create(O [, Properties])
  create: $create,
  // 19.1.2.4 Object.defineProperty(O, P, Attributes)
  defineProperty: $defineProperty,
  // 19.1.2.3 Object.defineProperties(O, Properties)
  defineProperties: $defineProperties,
  // 19.1.2.6 Object.getOwnPropertyDescriptor(O, P)
  getOwnPropertyDescriptor: $getOwnPropertyDescriptor,
  // 19.1.2.7 Object.getOwnPropertyNames(O)
  getOwnPropertyNames: $getOwnPropertyNames,
  // 19.1.2.8 Object.getOwnPropertySymbols(O)
  getOwnPropertySymbols: $getOwnPropertySymbols
});

// 24.3.2 JSON.stringify(value [, replacer [, space]])
$JSON && $export($export.S + $export.F * (!USE_NATIVE || $fails(function () {
  var S = $Symbol();
  // MS Edge converts symbol values to JSON as {}
  // WebKit converts symbol values to JSON as null
  // V8 throws on boxed symbols
  return _stringify([S]) != '[null]' || _stringify({ a: S }) != '{}' || _stringify(Object(S)) != '{}';
})), 'JSON', {
  stringify: function stringify(it) {
    var args = [it];
    var i = 1;
    var replacer, $replacer;
    while (arguments.length > i) args.push(arguments[i++]);
    $replacer = replacer = args[1];
    if (!isObject(replacer) && it === undefined || isSymbol(it)) return; // IE8 returns string on undefined
    if (!isArray(replacer)) replacer = function (key, value) {
      if (typeof $replacer == 'function') value = $replacer.call(this, key, value);
      if (!isSymbol(value)) return value;
    };
    args[1] = replacer;
    return _stringify.apply($JSON, args);
  }
});

// 19.4.3.4 Symbol.prototype[@@toPrimitive](hint)
$Symbol[PROTOTYPE][TO_PRIMITIVE] || __webpack_require__(6)($Symbol[PROTOTYPE], TO_PRIMITIVE, $Symbol[PROTOTYPE].valueOf);
// 19.4.3.5 Symbol.prototype[@@toStringTag]
setToStringTag($Symbol, 'Symbol');
// 20.2.1.9 Math[@@toStringTag]
setToStringTag(Math, 'Math', true);
// 24.3.3 JSON[@@toStringTag]
setToStringTag(global.JSON, 'JSON', true);


/***/ }),
/* 79 */
/***/ (function(module, exports, __webpack_require__) {

var META = __webpack_require__(15)('meta');
var isObject = __webpack_require__(7);
var has = __webpack_require__(5);
var setDesc = __webpack_require__(3).f;
var id = 0;
var isExtensible = Object.isExtensible || function () {
  return true;
};
var FREEZE = !__webpack_require__(8)(function () {
  return isExtensible(Object.preventExtensions({}));
});
var setMeta = function (it) {
  setDesc(it, META, { value: {
    i: 'O' + ++id, // object ID
    w: {}          // weak collections IDs
  } });
};
var fastKey = function (it, create) {
  // return primitive with prefix
  if (!isObject(it)) return typeof it == 'symbol' ? it : (typeof it == 'string' ? 'S' : 'P') + it;
  if (!has(it, META)) {
    // can't set metadata to uncaught frozen object
    if (!isExtensible(it)) return 'F';
    // not necessary to add metadata
    if (!create) return 'E';
    // add missing metadata
    setMeta(it);
  // return object ID
  } return it[META].i;
};
var getWeak = function (it, create) {
  if (!has(it, META)) {
    // can't set metadata to uncaught frozen object
    if (!isExtensible(it)) return true;
    // not necessary to add metadata
    if (!create) return false;
    // add missing metadata
    setMeta(it);
  // return hash weak collections IDs
  } return it[META].w;
};
// add metadata on freeze-family methods calling
var onFreeze = function (it) {
  if (FREEZE && meta.NEED && isExtensible(it) && !has(it, META)) setMeta(it);
  return it;
};
var meta = module.exports = {
  KEY: META,
  NEED: false,
  fastKey: fastKey,
  getWeak: getWeak,
  onFreeze: onFreeze
};


/***/ }),
/* 80 */
/***/ (function(module, exports, __webpack_require__) {

// all enumerable object keys, includes symbols
var getKeys = __webpack_require__(13);
var gOPS = __webpack_require__(23);
var pIE = __webpack_require__(16);
module.exports = function (it) {
  var result = getKeys(it);
  var getSymbols = gOPS.f;
  if (getSymbols) {
    var symbols = getSymbols(it);
    var isEnum = pIE.f;
    var i = 0;
    var key;
    while (symbols.length > i) if (isEnum.call(it, key = symbols[i++])) result.push(key);
  } return result;
};


/***/ }),
/* 81 */
/***/ (function(module, exports, __webpack_require__) {

// 7.2.2 IsArray(argument)
var cof = __webpack_require__(37);
module.exports = Array.isArray || function isArray(arg) {
  return cof(arg) == 'Array';
};


/***/ }),
/* 82 */
/***/ (function(module, exports, __webpack_require__) {

// fallback for IE11 buggy Object.getOwnPropertyNames with iframe and window
var toIObject = __webpack_require__(9);
var gOPN = __webpack_require__(42).f;
var toString = {}.toString;

var windowNames = typeof window == 'object' && window && Object.getOwnPropertyNames
  ? Object.getOwnPropertyNames(window) : [];

var getWindowNames = function (it) {
  try {
    return gOPN(it);
  } catch (e) {
    return windowNames.slice();
  }
};

module.exports.f = function getOwnPropertyNames(it) {
  return windowNames && toString.call(it) == '[object Window]' ? getWindowNames(it) : gOPN(toIObject(it));
};


/***/ }),
/* 83 */
/***/ (function(module, exports) {



/***/ }),
/* 84 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(29)('asyncIterator');


/***/ }),
/* 85 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(29)('observable');


/***/ }),
/* 86 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

var _setPrototypeOf = __webpack_require__(87);

var _setPrototypeOf2 = _interopRequireDefault(_setPrototypeOf);

var _create = __webpack_require__(91);

var _create2 = _interopRequireDefault(_create);

var _typeof2 = __webpack_require__(39);

var _typeof3 = _interopRequireDefault(_typeof2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = function (subClass, superClass) {
  if (typeof superClass !== "function" && superClass !== null) {
    throw new TypeError("Super expression must either be null or a function, not " + (typeof superClass === "undefined" ? "undefined" : (0, _typeof3.default)(superClass)));
  }

  subClass.prototype = (0, _create2.default)(superClass && superClass.prototype, {
    constructor: {
      value: subClass,
      enumerable: false,
      writable: true,
      configurable: true
    }
  });
  if (superClass) _setPrototypeOf2.default ? (0, _setPrototypeOf2.default)(subClass, superClass) : subClass.__proto__ = superClass;
};

/***/ }),
/* 87 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(88), __esModule: true };

/***/ }),
/* 88 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(89);
module.exports = __webpack_require__(0).Object.setPrototypeOf;


/***/ }),
/* 89 */
/***/ (function(module, exports, __webpack_require__) {

// 19.1.3.19 Object.setPrototypeOf(O, proto)
var $export = __webpack_require__(2);
$export($export.S, 'Object', { setPrototypeOf: __webpack_require__(90).set });


/***/ }),
/* 90 */
/***/ (function(module, exports, __webpack_require__) {

// Works with __proto__ only. Old v8 can't work with null proto objects.
/* eslint-disable no-proto */
var isObject = __webpack_require__(7);
var anObject = __webpack_require__(11);
var check = function (O, proto) {
  anObject(O);
  if (!isObject(proto) && proto !== null) throw TypeError(proto + ": can't set as prototype!");
};
module.exports = {
  set: Object.setPrototypeOf || ('__proto__' in {} ? // eslint-disable-line
    function (test, buggy, set) {
      try {
        set = __webpack_require__(32)(Function.call, __webpack_require__(43).f(Object.prototype, '__proto__').set, 2);
        set(test, []);
        buggy = !(test instanceof Array);
      } catch (e) { buggy = true; }
      return function setPrototypeOf(O, proto) {
        check(O, proto);
        if (buggy) O.__proto__ = proto;
        else set(O, proto);
        return O;
      };
    }({}, false) : undefined),
  check: check
};


/***/ }),
/* 91 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(92), __esModule: true };

/***/ }),
/* 92 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(93);
var $Object = __webpack_require__(0).Object;
module.exports = function create(P, D) {
  return $Object.create(P, D);
};


/***/ }),
/* 93 */
/***/ (function(module, exports, __webpack_require__) {

var $export = __webpack_require__(2);
// 19.1.2.2 / 15.2.3.5 Object.create(O [, Properties])
$export($export.S, 'Object', { create: __webpack_require__(26) });


/***/ }),
/* 94 */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["editor"]; }());

/***/ }),
/* 95 */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["components"]; }());

/***/ }),
/* 96 */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["element"]; }());

/***/ }),
/* 97 */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["editPost"]; }());

/***/ }),
/* 98 */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["compose"]; }());

/***/ }),
/* 99 */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["plugins"]; }());

/***/ }),
/* 100 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Preview; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wordpress_i18n__ = __webpack_require__(44);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wordpress_i18n___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__wordpress_i18n__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__cardType__ = __webpack_require__(31);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__title__ = __webpack_require__(101);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__image__ = __webpack_require__(102);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__style_scss__ = __webpack_require__(103);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__style_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_4__style_scss__);







var Preview = function Preview(_ref) {
    var props = _ref.props;
    return wp.element.createElement(
        "div",
        { className: "EmbeddedTweet" },
        wp.element.createElement(
            "div",
            { className: "EmbeddedTweet-author u-cf" },
            wp.element.createElement("img", { className: "EmbeddedTweet-author-avatar",
                src: tcData.avatar }),
            wp.element.createElement(
                "div",
                {
                    className: "EmbeddedTweet-author-name u-pullLeft" },
                Object(__WEBPACK_IMPORTED_MODULE_0__wordpress_i18n__["__"])("Your Twitter name", 'jm-tc-gut')
            ),
            wp.element.createElement(
                "div",
                { className: "EmbeddedTweet-author-handle u-pullLeft" },
                "@",
                tcData.twitterSite
            )
        ),
        wp.element.createElement(
            "div",
            { className: "EmbeddedTweet-text" },
            'app' !== Object(__WEBPACK_IMPORTED_MODULE_1__cardType__["a" /* Type */])(props) && wp.element.createElement(
                "p",
                null,
                Object(__WEBPACK_IMPORTED_MODULE_0__wordpress_i18n__["__"])("The card for your website will look a little something like this!", 'jm-tc-gut')
            ),
            'app' === Object(__WEBPACK_IMPORTED_MODULE_1__cardType__["a" /* Type */])(props) && wp.element.createElement(
                "p",
                null,
                Object(__WEBPACK_IMPORTED_MODULE_0__wordpress_i18n__["__"])('Preview is not provided for application card', 'jm-tc-gut')
            )
        ),
        wp.element.createElement(
            "div",
            { className: "CardPreview u-marginVm", id: "CardPreview" },
            wp.element.createElement(
                "div",
                { className: "CardPreview-preview js-cardPreview" },
                wp.element.createElement(
                    "div",
                    { className: "TwitterCardsGrid TwitterCard TwitterCard--animation" },
                    'app' !== Object(__WEBPACK_IMPORTED_MODULE_1__cardType__["a" /* Type */])(props) && wp.element.createElement(
                        "div",
                        {
                            className: "TwitterCardsGrid-col--12 TwitterCardsGrid-col--spacerBottom CardContent" },
                        wp.element.createElement(
                            "div",
                            {
                                className: "js-openLink u-block TwitterCardsGrid-col--12 TwitterCard-container " + Object(__WEBPACK_IMPORTED_MODULE_1__cardType__["a" /* Type */])(props) + "--small " + Object(__WEBPACK_IMPORTED_MODULE_1__cardType__["a" /* Type */])(props) + "--noImage" },
                            wp.element.createElement(
                                "div",
                                { className: Object(__WEBPACK_IMPORTED_MODULE_1__cardType__["a" /* Type */])(props) + "-image TwitterCardsGrid-float--prev" },
                                wp.element.createElement(
                                    "div",
                                    { className: "tcu-imageContainer tcu-imageAspect--1to1" },
                                    wp.element.createElement(__WEBPACK_IMPORTED_MODULE_3__image__["a" /* Image */], { props: props })
                                )
                            ),
                            wp.element.createElement(
                                "div",
                                {
                                    className: Object(__WEBPACK_IMPORTED_MODULE_1__cardType__["a" /* Type */])(props) + "-contentContainer TwitterCardsGrid-float--prev" },
                                wp.element.createElement(
                                    "div",
                                    { className: Object(__WEBPACK_IMPORTED_MODULE_1__cardType__["a" /* Type */])(props) + "-content TwitterCardsGrid-ltr" },
                                    wp.element.createElement(__WEBPACK_IMPORTED_MODULE_2__title__["a" /* Title */], { props: props }),
                                    wp.element.createElement(
                                        "p",
                                        { className: "TwitterCard-desc tcu-resetMargin u-block TwitterCardsGrid-col--spacerTop tcu-textEllipse--multiline" },
                                        props.meta.cardDesc,
                                        wp.element.createElement(
                                            "span",
                                            {
                                                className: "SummaryCard-destination" },
                                            tcData.domain
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    );
};

/***/ }),
/* 101 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Title; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wordpress_data__ = __webpack_require__(30);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wordpress_data___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__wordpress_data__);


var Title = function Title(_ref) {
    var props = _ref.props;
    return wp.element.createElement(
        "h2",
        { className: "TwitterCard-title js-cardClick tcu-textEllipse--multiline" },
        props.meta.cardTitle || Object(__WEBPACK_IMPORTED_MODULE_0__wordpress_data__["select"])('core/editor').getEditedPostAttribute('title')
    );
};

/***/ }),
/* 102 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Image; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__cardType__ = __webpack_require__(31);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__wordpress_data__ = __webpack_require__(30);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__wordpress_data___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__wordpress_data__);



function theImageUrl(props) {

    var fallback = props.meta.cardImage || tcData.defaultImage;

    var _select = Object(__WEBPACK_IMPORTED_MODULE_1__wordpress_data__["select"])('core'),
        getPostType = _select.getPostType;

    var postTypeCheck = getPostType(Object(__WEBPACK_IMPORTED_MODULE_1__wordpress_data__["select"])('core/editor').getEditedPostAttribute('type')); // no use if no support for thumbnail

    if (!postTypeCheck || !postTypeCheck.supports['thumbnail']) {
        return fallback;
    }
    var featuredImageId = Object(__WEBPACK_IMPORTED_MODULE_1__wordpress_data__["select"])('core/editor').getEditedPostAttribute('featured_media');

    if (featuredImageId === 0) {
        return fallback;
    }

    var media = Object(__WEBPACK_IMPORTED_MODULE_1__wordpress_data__["select"])('core').getMedia(featuredImageId);

    if (typeof media !== 'undefined') {
        return props.meta.cardImage || media.source_url;
    }

    return fallback;
}

var Image = function Image(_ref) {
    var props = _ref.props;
    return wp.element.createElement(
        'div',
        { className: 'tcu-imageWrapper',
            style: { backgroundImage: "url(" + theImageUrl(props) + ")" } },
        'player' === Object(__WEBPACK_IMPORTED_MODULE_0__cardType__["a" /* Type */])(props) && wp.element.createElement('div', { className: 'PlayerCard-playButton',
            style: { backgroundImage: "url(" + tcData.pluginUrl + "img/player.svg)" } }),
        wp.element.createElement('img', { className: 'u-block',
            alt: props.meta.cardImageAlt || '',
            src: theImageUrl(props) })
    );
};

/***/ }),
/* 103 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 104 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);
//# sourceMappingURL=index.js.map