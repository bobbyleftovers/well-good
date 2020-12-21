var $ = require('jquery')

function UpdateAdCats(el) {
  this.container = el
  this.data = csvData
  this.index = !csvIndex ? 0 : csvIndex
  this.count = csvCount
  this.status = {
    success: {
      count: 0,
      get post () {
        return this.count > 1 ? ' posts' : ' post'
      },
      get message () {
        return 'Updated ad cats for ' + this.count + this.post + ' successfully'
      }
    },
    issue: {
      count: 0,
      get post () {
        return this.count > 1 ? ' posts' : ' post'
      },
      get message () {
        return 'Updated ad cats for ' + this.count + this.post + ' with issues'
      }
    },
    error: {
      count: 0,
      get post () {
        return this.count > 1 ? ' posts' : ' post'
      },
      get message () {
        return 'Error updating ' + this.count + this.post
      }
    }
  }
  this.run()
}

UpdateAdCats.prototype = {
  run: function () {
    var self = this
    $.ajax({
      url: '/wp-json/wellandgood/v1/update-ad-cats',
      type: 'POST',
      dataType: 'json',
      data: {
        index: self.index,
        data: self.data[self.index]
      }
    }).done(function (data) {
      var code = data[1]
      self.index = data[0]
      self.status[code].count++
      self.update(code)

      if (self.index < self.count) {
        self.index++
        self.run()
      } else {
        self.end()
      }
    }).error(function () {
      self.error()
    })
  },

  update: function (code) {
    var el = document.getElementsByClassName('update-ad-cats-' + code)[0]
    if (el) {
      el.innerHTML = this.status[code].message
    } else {
      var div = document.createElement('div')
      div.className = 'update-ad-cats-' + code
      div.innerHTML = this.status[code].message
      this.container.appendChild(div)
    }
  },

  error: function () {
    var head = document.getElementsByClassName('update-ad-cats-head')[0]
    head.innerHTML = 'Error converting categories'
  },
  
  end: function () {
    var head = document.getElementsByClassName('update-ad-cats-head')[0]
    head.innerHTML = 'Conversion Finished'
  }
}

module.exports = UpdateAdCats
