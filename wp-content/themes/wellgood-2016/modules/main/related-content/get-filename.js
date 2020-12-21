module.exports = function(yourstring = '') {
  var index = yourstring.lastIndexOf("/") + 1
  var filename = yourstring.substr(index)
  return filename
}
