var ini = require("ini")
var fs = require("fs")
const chalk = require('chalk')
const { exec } = require("child_process")
const log = console.log


module.exports.getArgs = function() {
  var data = {}
  data.NODE_ENV = process.env.NODE_ENV
  process.argv.forEach(function (val) {
    if(val.includes('=')) {
      if(val.startsWith('--')) val = val.slice(2)
      var arg = val.split('=')
      data[arg[0]] = arg[1]
    } else if(val.startsWith('--') && val != '--'){
      val = val.slice(2)
      data[val] = true
    }
  });

  //purge
  data.purge = data.NODE_ENV === 'production';
  if(data.purge && data.local && data.local === 'true') data.purge = false

  return data
}

module.exports.iniFile = function(data, file = "./assets/scripts.ini"){

  data.entries = module.exports.entry

  fs.unlink(file, function (err) {
    fs.appendFile(file, ini.stringify(data, {}), function (
      err
    ) {
      if (err) throw err;
    });
  });
}


module.exports.readIniFile = function(file = "./assets/scripts.ini"){

  return ini.parse(fs.readFileSync(file, 'utf-8'))

}

const logPlane = function(color = chalk.yellow) {
  log(color("                               |                               "))
  log(color("                         --====|====--                         "))
  log(color("                               |                               "))
  log(color('                           .-"""""-.                           '))
  log(color("                         .'_________'.                         "))
  log(color("                        /_/_|__|__|_\\_\\                        "))
  log(color("                       ;'-._       _.-';                       "))
  log(color("  ,--------------------|    `-. .-'    |--------------------,  "))
  log(color('   ``""--..__    ____  ;       "       ;   ____    __..--""``  '))
  log(color('             `"-//  \\\\..               /_.//  \\\\-"`        '))
  log(color("                \\\\__//   '._       _.'    \\\\__//           "))
  log(color('                            ``---``                            '))
  log('\n')
}

const logLogo = function(colorBorder = chalk.yellow, colorLogo = chalk.yellow) {
  log(colorBorder("✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️")                                 )
  log(colorBorder("✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️")                                 )
  log(colorBorder("✈️✈️✈️                                                        ✈️✈️✈️")                                 )
  log(colorBorder("✈️✈️✈️    ")+colorLogo("██████╗  █████╗ ██████╗ ██████╗ ███████╗██╗       ")+colorBorder("  ✈✈️✈️")   )
  log(colorBorder("✈️✈️✈️    ")+colorLogo("██╔══██╗██╔══██╗██╔══██╗██╔══██╗██╔════╝██║       ")+colorBorder("  ✈️✈️✈️")   )
  log(colorBorder("✈️✈️✈️    ")+colorLogo("██████╔╝███████║██████╔╝██████╔╝█████╗  ██║       ")+colorBorder("  ✈️✈️✈️")   )    
  log(colorBorder("✈️✈️✈️    ")+colorLogo("██╔══██╗██╔══██║██╔══██╗██╔══██╗██╔══╝  ██║       ")+colorBorder("  ✈️✈️✈️")   )     
  log(colorBorder("✈️✈️✈️    ")+colorLogo("██████╔╝██║  ██║██║  ██║██║  ██║███████╗███████╗  ")+colorBorder("  ✈️✈️✈️")   ) 
  log(colorBorder("✈️✈️✈️    ")+colorLogo("╚═════╝ ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚══════╝╚══════╝  ")+colorBorder("  ✈️✈️✈️")   )
  log(colorBorder("✈️✈️✈️                                                        ✈️✈️✈️")                                 )
  log(colorBorder("✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️")                                 )
  log(colorBorder("✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️✈️")                                 )
}

module.exports.initialLogs = function(entries){

  exec('reset')

  log('\n')

  // Logo
  logLogo(chalk.cyan, chalk.yellow)

  //Env
  var colorIndex = 'greenBright'
  log(chalk.cyanBright('\n\ENV \n---'))

  //Node version
  log(chalk.magentaBright('⊳ ')+chalk[colorIndex](('Node version: ').toUpperCase())+chalk.yellow(process.version))
  
  //Args
  Object.keys(process.args).forEach(arg => {
    if(arg === 'NODE_ENTRIES') return
    if(process.args[arg] === 'false' || !process.args[arg]) color = 'redBright' 
    else color = 'yellow'
    log(
      chalk.magentaBright('⊳ ')
      + chalk[colorIndex](arg.toUpperCase().replace('_',' ')+': ')
      + chalk[color](process.args[arg])
    )
  })

  //Bundles
  log(chalk.cyanBright('\n\nBundles \n-------'))
  Object.keys(entries).forEach(entry => log(chalk.magentaBright('⊳ ')+chalk.greenBright(entry)))

  log('\n');
}

module.exports.endLogs = function(){
  logPlane(chalk.greenBright);
}
