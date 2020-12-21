// Main bundles
const mainEntries = {
  "main": [
    './node_modules/jquery-unveil/jquery.unveil.js',
    './src/js/lib/vendor/jquery.imagelinks.min.js',
    './node_modules/history.js/scripts/bundled/html4+html5/jquery.history.js',
    './src/js/main.js'
  ],
  "main-2020": [
    './node_modules/jquery-unveil/jquery.unveil.js',
    './src/js/main-2020.js'
  ],
  "styleguide-2020": [
    './src/js/styleguide-2020.js'
  ],
  "vendor": [
    './src/js/vendor.js'
  ],
  "gutenberg-editor": [
    './src/js/gutenberg.editor.js'
  ],
  "gutenberg": [
    './src/js/gutenberg.front.js'
  ]
}

// Specific bundles
const extraEntries = {
  "product-guide": [
    './src/js/product-guide.js'
  ],
  "location-hub": [
    './src/js/location-hub/index.js'
  ],
  "summer": [
    './node_modules/jquery-unveil/jquery.unveil.js',
    './node_modules/history.js/scripts/bundled/html4+html5/jquery.history.js',
    './src/js/summer.js'
  ],
  "trends-2020": [
    './src/js/trends-2020.js'
  ],
  "trends-2021": [
    './src/js/trends-2021.js'
  ],
  "changemakers": [
    './src/js/changemakers.js'
  ],
  "renew-year-2021": [
    './src/js/renew-year-2021.js'
  ],
  "rich-tag": [
    './src/js/rich-tag.js'
  ]
}

const allEntries = Object.assign({}, mainEntries, extraEntries);


function getBundles(process){

  var entries = {}

  if( process.args.only ){  
    process.args.only.split(',').forEach( bundle =>  { 
      entries[bundle] = allEntries[bundle] 
      if(bundle === 'gutenberg') entries['gutenberg-editor'] =  allEntries['gutenberg-editor'] 
    })
  } else {
    if(process.env.NODE_ENV !== 'development'){
      entries = allEntries;
    } else {
      if (!process.args.all){ entries = mainEntries;
      } else { entries = allEntries; }
    }
  }

  if( process.args.not ){
    process.args.not.split(',').forEach( bundle =>  { 
      delete entries[bundle]
    })
  }  

  process.args.NODE_ENTRIES = Object.keys(entries).join(',');

  return entries;
}


module.exports = {
  getBundles: getBundles,
  allEntries: allEntries,
  mainEntries: mainEntries,
  extraEntries: extraEntries
}
