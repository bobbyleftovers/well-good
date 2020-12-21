const penthouse = require('penthouse')
const fs = require('fs')
const Clean = require('clean-css')
const path = require('path')
const fileGetContents = require('file-get-contents');

const templates = [
  {
    path: '/',
    input: ['vendor.css','main.css'],
    forceInclude: [
      '.logo',
      '.search-overlay',
      '.header:not(.header--tall) .logo',
      '.logo .js-header-sliding, .header:not(.header--tall) .logo',
      '.logo .js-header-sliding',
      '.menu-drawer',
      '.search-bar',
      '.post__content',
      '.post__sidebar'
    ],
    propertiesToRemove: [
      '(.*)transition(.*)',
      'cursor',
      'pointer-events',
      '(-webkit-)?tap-highlight-color',
      '(.*)user-select'
    ]
  },
  {
    prefix: 'category',
    path: '/food-nutrition',
    input: ['vendor.css','main.css'],
    forceInclude: [
      '.logo',
      '.header:not(.header--tall) .logo',
      '.logo .js-header-sliding, .header:not(.header--tall) .logo',
      '.logo .js-header-sliding',
      '.menu-drawer',
      '.search-bar',
      '.post__content',
      '.post__sidebar',
      '.editorialtag-grid-card__content--share',
      '.editorialtag-share',
      '.editorialtag-grid-card__share',
      '.post-card-share',
      '.post-card-share li'
    ],
    propertiesToRemove: [
      '(.*)transition(.*)',
      'cursor',
      'pointer-events',
      '(-webkit-)?tap-highlight-color',
      '(.*)user-select'
    ]
  },
  {
    prefix: 'rich-tag',
    path: '/active-recovery',
    input: ['vendor.css','main-2020.css','rich-tag.css'],
    forceInclude: [
      '.logo',
      '.header:not(.header--tall) .logo',
      '.logo .js-header-sliding, .header:not(.header--tall) .logo',
      '.logo .js-header-sliding',
      '.menu-drawer',
      '.search-bar',
      '.post__content',
      '.post__sidebar',
      '.editorialtag-grid-card__content--share',
      '.editorialtag-share',
      '.editorialtag-grid-card__share',
      '.post-card-share'
    ],
    propertiesToRemove: [
      '(.*)transition(.*)',
      'cursor',
      'pointer-events',
      '(-webkit-)?tap-highlight-color',
      '(.*)user-select'
    ]
  },
  {
    prefix: 'trends-2021-home',
    path: '/trends-2021',
    input: ['vendor.css', 'gutenberg.css', 'main-2020.css','trends-2021.css'],
    forceInclude: [
      '.logo',
      '.header:not(.header--tall) .logo',
      '.logo .js-header-sliding, .header:not(.header--tall) .logo',
      '.logo .js-header-sliding',
      '.menu-drawer',
      '.search-bar',
      '.post__content',
      '.post__sidebar'
    ],
    propertiesToRemove: [
      '(.*)transition(.*)',
      'cursor',
      'pointer-events',
      '(-webkit-)?tap-highlight-color',
      '(.*)user-select'
    ]
  },
  {
    prefix: 'trends-2021-child',
    path: '/trends-2021/beauty',
    input: ['vendor.css', 'gutenberg.css', 'main-2020.css','trends-2021.css'],
    forceInclude: [
      '.logo',
      '.header:not(.header--tall) .logo',
      '.logo .js-header-sliding, .header:not(.header--tall) .logo',
      '.logo .js-header-sliding',
      '.menu-drawer',
      '.search-bar',
      '.post__content',
      '.post__sidebar'
    ],
    propertiesToRemove: [
      '(.*)transition(.*)',
      'cursor',
      'pointer-events',
      '(-webkit-)?tap-highlight-color',
      '(.*)user-select'
    ]
  },
  {
    prefix: 'styleguide',
    path: '/styleguide',
    input: ['vendor.css','main-2020.css', 'styleguide-2020.css'],
    forceInclude: [
      '.logo',
      '.header:not(.header--tall) .logo',
      '.logo .js-header-sliding, .header:not(.header--tall) .logo',
      '.logo .js-header-sliding',
      '.menu-drawer',
      '.search-bar'
    ],
    propertiesToRemove: [
      '(.*)transition(.*)',
      'cursor',
      'pointer-events',
      '(-webkit-)?tap-highlight-color',
      '(.*)user-select'
    ]
  },
  {
    prefix: 'summer',
    path: '/99daysofsummer',
    input: ['vendor.css','summer.css'],
    propertiesToRemove: [
      '(.*)transition(.*)',
      'cursor',
      'pointer-events',
      '(-webkit-)?tap-highlight-color',
      '(.*)user-select'
    ]
  },
  {
    prefix: 'product-guide',
    path: '/holiday-gift-shop',
    input: 'product-guide.css',
    forceInclude: [
      '.main-wrapper',
      '.main-wrapper>*',
      'header',
      '.product-guide-subnav',
      '.product-guide-header__hero',
      '.product-guide-header__hero--index',
      '.product-guide-header__hero--category',
      '.product-guide-header',
      '.product-guide-header--index',
      '.product-guide-header--category',
      '.product-guide-header__navigation',
      '.product-guide-header__hero--top',
      '.product-guide-header__hero--intro',
      '.product-guide-sidebar',
      '.product-guide-header__hero--mobile',
      '.product-guide-header__hero--intro--top',
      '.product-guide-header__hero--intro--logo',
      '.product-guide-header__hero--intro--logo>a *',
      '.product-guide-header__hero--intro--callout',
      '.product-guide-header__hero--intro--callout p',
      '.product-guide-header__hero--intro--bottom',
      '.product-guide-header__hero--intro--sponsor',
      '.product-guide-header__hero--intro--sponsor_logo',
      '.product-guide-header__hero--intro--social>span',
      '.product-guide-header__hero--intro--social>ul',
      '.product-guide-header__hero--mobile',
      '.product-guide-header__hero--index>.product-guide-header__hero--top',
      '.product-guide-header__hero--category>.product-guide-header__hero--top'
    ],
    propertiesToRemove: [
      'background-image'
    ]
  },
  {
    prefix: 'trends-2020',
    path: '/fitness-wellness-trends',
    input: ['vendor.css','main.css'],
    forceInclude: [
      '.menu-drawer-container',
      '.drawer-overlay'
    ]
  }
]

class CriticalCss {
  constructor(process, hash) {
    
    this.templates = templates
    this.testDimensions = { width: 1440, height: 1440 }
    this.assetPath = './assets'
    this.urlBase = (process.args.CRITICAL_URL || process.args.critical_url) || ((process.args.URL || process.args.url) || 'https://www.wellandgood.com/')
    this.hash = hash

  }

  apply() {
    var url = this.urlBase
    setTimeout(function(){
      console.log('\x1b[33m%s\x1b[0m','Compiling critical css from '+url)
    }, 0)
    this.enqueueNextTemplate()
  }

  enqueueNextTemplate(){
    if(this.templates.length) this.run(this.templates.shift())
    else console.log('\x1b[32m%s\x1b[0m', 'Critical CSS done!')
  }

  getRemoveProperties(customProperties) {
    const defaultProperties = [
      '(.*)transition(.*)',
      'cursor',
      'pointer-events',
      '(-webkit-)?tap-highlight-color',
      '(.*)user-select'
    ]
    let a = customProperties != null
      ? customProperties.concat(defaultProperties)
      : defaultProperties

    for (let i = 0; i < a.length; ++i) {
      for (let j = i + 1; j < a.length; ++j) {
        if (a[i] === a[j]) {
          a.splice(j--, 1)
        }
      }
    }

    return a
  }

  async run(template) {
    const width = this.testDimensions.width
    const height = this.testDimensions.height
    
    const prefix = 'prefix' in template && template.prefix != 'main' ? `${template.prefix}-` : ''
    const forceInclude = 'forceInclude' in template ? template.forceInclude : []
    const propertiesToRemove = 'propertiesToRemove' in template ? this.getRemoveProperties(template.propertiesToRemove) : this.getRemoveProperties()
    const name = 'prefix' in template ? template.prefix.charAt(0).toUpperCase() + template.prefix.slice(1) : 'Main'

    const urlBase = this.urlBase.charAt(this.urlBase.length - 1) == '/' ? this.urlBase.substr(0, this.urlBase.length - 1) : this.urlBase
    const urlPath = template.path.charAt(0) == '/' ? template.path : `/${template.path}`

    setTimeout(function() {
      console.log(`Compiling critical for ${name}...`)
    }, 0)

    var config = {
      url: urlBase + urlPath,
      width: width,
      height: height,
      forceInclude: forceInclude,
      propertiesToRemove: propertiesToRemove,
      timeout: 180000, //3min
      renderWaitTime: 1000,
    }

    const getFile = (input) => {
      const file = this.hash ? input.replace('.css', `.min.css`) : input
      return path.resolve(__dirname, `${this.assetPath}/${file}`)
    }

    if(typeof template.input === 'object'){

      config.cssString = '';
      const appendStylesheets = async () => {

        const input = template.input.shift();
        if(typeof input === 'undefined') return;

        try {
          let data = await fileGetContents(getFile(input));
          config.cssString += ` ${data} `
        } catch (err) {
          console.log(`Unable to load data from ${css}`);
        }

        await appendStylesheets()
      }
      
      await appendStylesheets()

    } else {
      config.css = getFile(template.input);
    }
    penthouse(config).then(criticalCss => {
      fs.writeFileSync(path.resolve(__dirname, `${this.assetPath}/${prefix}critical.min.css`), new Clean().minify(criticalCss).styles)
      console.log('\x1b[32m%s\x1b[0m', 'Compiled critical for '+name)
    }).catch(err => {
      console.error(err)
      console.error(config);
    }).then(() => {
      this.enqueueNextTemplate()
    })
  }
}

module.exports = function(process, hash = null){
  const critical = new CriticalCss(process, hash)
  critical.apply()
  return critical
}
