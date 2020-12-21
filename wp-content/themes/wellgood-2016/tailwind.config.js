const purgecssWordpress = require('purgecss-with-wordpress');

const spacing = {
  //Grid
  'gutter1/2': '13px',
  'gutter': '26px',

  //Header height
  'header-sm':'43px',
  'header-lg':'68px',

  //Preset Utilities (Need aligment with design team)
  '0': '0',

  //Exact
  'e0':'0',
  'e1':'1px',
  'e2':'2px',
  'e4':'4px',
  'e3':'3px',
  'e5':'5px',
  'e6':'6px',
  'e7':'7px',
  'e8':'8px',
  'e9':'9px',
  'e10':'10px',
  'e11':'11px',
  'e12':'12px',
  'e13':'13px',
  'e14':'14px',
  'e15':'15px',
  'e16':'16px',
  'e18':'18px',
  'e19':'19px',
  'e20':'20px',
  'e21':'21px',
  'e22':'22px',
  'e24':'24px',
  'e25':'25px',
  'e27':'27px',
  'e28':'28px',
  'e30':'30px',
  'e33':'33px',
  'e35':'35px',
  'e37':'37px',
  'e38':'38px',
  'e40':'40px',
  'e43':'43px',
  'e45':'45px',
  'e50':'50px',
  'e55':'55px',
  'e60':'60px',
  'e65':'65px',
  'e70':'70px',
  'e73':'73px',
  'e75':'75px',
  'e76':'76px',
  'e77':'77px',
  'e80':'80px',
  'e84':'84px',
  'e85':'85px',
  'e90':'90px',
  'e98':'98px',
  'e100':'100px',
  'e106':'106px',
  'e109':'109px',
  'e110':'110px',
  'e111':'111px',
  'e120':'120px',
  'e122':'122px',
  'e125':'125px',
  'e129':'129px',
  'e130':'130px',
  'e135':'135px',
  'e138':'138px',
  'e140':'140px',
  'e149':'149px',
  'e150':'150px',
  'e156':'156px',
  'e160':'160px',
  'e170':'170px',
  'e180':'180px',
  'e190':'190px',
  'e200':'200px',

  //Proportional
  '1/1': '100%',
  '1/2': '50%',
  '16/9': '56.25%',
  'p80': '80%',
  'p98': '98%'
}

module.exports = {

  purge: {
    enabled: process.args.purge,
    content: [
      './modules/**/*.php',
      './templates/**/*.php',
      './*.php',
      './modules/**/*.vue',
    ],
    options: {
      whitelist: [...purgecssWordpress.whitelist ],
      whitelistPatterns: purgecssWordpress.whitelistPatterns,
      whitelistPatternsChildren: [/^post__main$/]
    }
  },

  theme: {

    screens: {
      xs: '480px',
      "xs-down": {'max': '479px'},
      sm: '640px',
      "sm-down": {'max': '639px'},
      md: '768px',
      "md-down": {'max': '767px'},
      ml: '1024px',
      "ml-down": {'max': '1023px'},
      lg: '1140px',
      "lg-down": {'max': '1139px'},
      header: '1200px',
      "header-down": {'max': '1199px'},
      xl: '1440px',
      "xl-down": {'max': '1439px'},
      xxl: '1920px',
      "xxl-down": {'max': '1919px'}
    },

    fontFamily: {
      "safe-display": ['orpheuspro', 'serif'],
      serif: ['freight-text-pro', 'serif'],
      sans: ['neue-haas-unica', 'sans-serif']
    },

    fontSize: {
      sm: ['14px', '20px'],
      base: ['16px', '24px'],
      "base-big": ['19px', '33px'],
      "nl-capture-title": ['19px', '24px'],
      "nl-capture-title-sm": ['16px', '21px'],
      "nl-capture-copy-sm": ['12px', '17px'],
      "nl-capture-copy": ['13px', '20px'],

      // text-h1
      'h1-mobile': ['28px', {
        lineHeight: '35px',
        letterSpacing: '0.5px',
      }],
      'h1-tablet': ['50px', {
        lineHeight: '56px',
        letterSpacing: '0.5px',
      }],
      'h1-desktop': ['56px', {
        lineHeight: '60px',
        letterSpacing: '0.5px',
      }],

      // text-h1--article
      'h1--article-mobile': ['26px', {
        lineHeight: '33px',
        letterSpacing: '0.5px',
      }],
      'h1--article-tablet': ['42px', {
        lineHeight: '50px',
        letterSpacing: '0.5px',
      }],
      'h1--article-desktop': ['47px', {
        lineHeight: '60px',
        letterSpacing: '0.5px',
      }],

      // text-h2
      'h2-mobile': ['23px','29px'],
      'h2-tablet': ['32px','42px'],
      'h2-desktop': ['37px', {
        lineHeight: '42px',
        letterSpacing: '0.56px',
      }],

      // text-h3
      'h3-mobile': ['24px','32px'],
      'h3-tablet': ['26px','34px'],
      'h3-desktop': ['28px','36px'],

      // text-h4
      'h4-mobile': ['18px','24px'],
      'h4-tablet': ['21px','30px'],
      'h4-desktop': ['22px','32px'],

      // text-h5
      'h5-mobile': ['16px',{
        lineHeight: '21px',
        letterSpacing: '0.5px',
      }],
      'h5-tablet': ['18px',{
        lineHeight: '24px',
        letterSpacing: '0.5px',
      }],
      'h5-desktop': ['19px',{
        lineHeight: '25px',
        letterSpacing: '0.5px',
      }],

      // dropcap
      'dropcap-mobile': ['70px','70px'],
      'dropcap-tablet': ['100px','114px'],
      'dropcap-desktop': ['105px','109px'],

      // text-quote
      'quote-mobile': ['23px','29px'],
      'quote-tablet': ['32px','42px'],
      'quote-desktop': ['33px','45px'],

      // text-big
      'big-mobile': ['18px','29px'],
      'big-tablet': ['19px','33px'],

      // text-default
      'default-mobile': ['15px','23px'],
      'default-tablet': ['15px','24px'],

      // text-small
      'small-mobile': ['12px','17px'],
      'small-tablet': ['13px','20px'],

      // text-tag
      'tag-mobile': ['10px',{
        lineHeight: '13px',
        letterSpacing: '1px',
      }],
      'tag-tablet': ['11px',{
        lineHeight: '15px',
        letterSpacing: '1.5px',
      }],

      // text-label
      'label-mobile': ['10px',{
        lineHeight: '13px',
        letterSpacing: '1.3px',
      }],
      'label-tablet': ['13px',{
        lineHeight: '17px',
        letterSpacing: '1.7px',
      }],

      // text-link
      'link-mobile': ['10px',{
        lineHeight: '11px',
        letterSpacing: '1px',
      }],
      'link-tablet': ['11px',{
        lineHeight: '12px',
        letterSpacing: '1px',
      }],
      'link-desktop': ['11px',{
        lineHeight: '13px',
        letterSpacing: '1.5px',
      }],

      // text-byline
      'byline-mobile': ['14px',{
        lineHeight: '17px',
        letterSpacing: '0.5px',
      }]
    },

    maxWidth: {
      'email-cap-square': '393px',
      'email-cap-rect': '1089px',
      'e167': '167px',
      'e257': '257px',
      'e290': '290px',
      'e320': '320px',
      'e350': '350px',
      'e397': '397px',
      'e530': '530px',
      'e610': '610px',
      'e800': '800px',
      'container-xl': '1440px',
    },

    colors: {
      seafoam: {
        dark: '#42676B',
        default: '#D0E6E4',
        light: '#F8FAF9'
      },
      gray: {
        dark: '#202020',
        default: '#333333',
        light: '#707070',
        "70": '#707070',
        "75": "#666666",
        "60": '#858585',
        "25": '#C8C8C8',
        "10": '#efefef'
      },
      tan: {
        light: '#F9F5F2',
        medium: '#EEE7E0',
        default: '#E7DFD7',
        dark: '#81756A',
      },
      white: {
        default: '#FFFFFF'
      },
      transparent: {
        default: 'transparent'
      },
      red: {
        default: '#F06767'
      }
    },

    borderWidth: {
      default: '1px',
      '05': '0.5px',
      '0': '0',
      '1': '1px',
      '2': '2px',
      '4': '4px',
    },

    borderRadius: {
      'none': '0',
      default: '4px'
    },

    spacing: spacing,

    inset: Object.assign({}, spacing, {
      '0': 0,
     auto: 'auto',
     '1/2': '50%',
     'e45': '45px',
     'e30': '30px'
    }),

    opacity: {
      '0': '0',
      '25': '.25',
      '30': '.3',
      '50': '.5',
      '75': '.75',
      '10': '.1',
      '20': '.2',
      '30': '.3',
      '40': '.4',
      '50': '.5',
      '60': '.6',
      '70': '.7',
      '80': '.8',
      '90': '.9',
      '100': '1',
    },
    minWidth: {
      '0': '0',
      '1/4': '25%',
      '1/3': '33.3333%',
      '1/2': '50%',
      '1/6': '66.6666%',
      '3/4': '75%',
      'full': '100%',
      'e200': '200px'
    },
    // maxWidth: {
    //   'lg':	'32rem',
    //   '3xl': '73.125rem',
    //   '4xl': '87.5rem',
    // }
  },
  variants: {
    margin: ['responsive', 'last', 'first'],
    padding: ['responsive', 'last'],
    scale: ['responsive', 'hover', 'focus', 'active', 'group-hover'],
    borderWidth: ['responsive', 'last'],
    borderColor: ['hover', 'focus'],
    textColor: ['responsive', 'hover', 'focus', 'active', 'group-hover'],
    opacity: ['responsive', 'hover', 'focus', 'active', 'group-hover'],
  },
  corePlugins: {
    preflight: false,
    container: false
  }
}
