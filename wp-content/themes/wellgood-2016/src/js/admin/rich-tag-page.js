function ProviderToACFRelationship(el) {

  this.$el = jQuery(el).parents('[data-layout="posts_list"],[data-layout="youtube_channel"]')

  if(this.$el.data('ProviderToACFRelationship')) return this.$el.data('ProviderToACFRelationship')

  this.limit = 6

  this.isLoading = false

  this.$el.data('ProviderToACFRelationship', this)

  return this

}

/*
*
* Use module title as default search keyword, but prompt the user to use a custom one
*
*/
ProviderToACFRelationship.prototype.getSearchKeyword = function(){

  var defaultKeyword = this.$el.find('[data-name="title"] input').val()

  this.keyword = prompt("Search for keyword:", defaultKeyword);

  return this.keyword

}


/*
*
* Get Taxonomy term name
*
*/
ProviderToACFRelationship.prototype.getTermTitle = function(){

  this.termTitle = jQuery('.form-table[role="presentation"] input#name[name="name"]').val()

  return this.termTitle
}

/*
*
* Populate relationship ACF field with Parsely
*
*/
ProviderToACFRelationship.prototype.populateFromParsely = function(){

  if(this.isLoading) return Promise.reject();

  var self = this

  // Get ACF relationship value
  this.getValue()

  // Get Serach query
  this.getSearchKeyword()

  if(this.keyword === null) return Promise.reject();

  // Promt user to set search limit
  var limit = prompt("How many posts do you want to fetch?", this.limit);

  if(!limit) return Promise.reject();

  this.limit = limit;

  // Get Term Title (tag keyword in Parse.ly)
  this.getTermTitle()

  // Fetch Parse.ly
  return this.fetchParsely().then(function(data) {
    if(data.length === 0) {
      alert(`No results found for the keywords "${self.keyword}" inside tag ${self.termTitle}`)
    } else {
      data.forEach(function(post){
        self.injectItem(post)
      })
      self.getValue()
    }
  }).catch(function(){
    // console.log('Parse.ly populate rejected')
  }).then(function(){
    self.isLoading = false
  })
}

/*
*
* Populate relationship ACF field with
*
*/
ProviderToACFRelationship.prototype.populateFromYouTube = function(){

  if(this.isLoading) return Promise.reject();

  var self = this

  // Get ACF relationship value
  this.getValue()

  // Get Serach query
  this.getSearchKeyword()

  // Promt user to set search limit
  var limit = prompt("How many posts do you want to fetch?", this.limit);

  if(!limit) return Promise.reject();

  this.limit = limit;

  // Fetch Parse.ly
  return this.fetchYouTube().then(function(data) {
    if(data.length === 0) {
      alert(`No videos found`)
    } else {
      data.forEach(function(post){
        self.injectItem(post)
      })
      self.getValue()
    }
  }).catch(function(){
    // console.log('Parse.ly populate rejected')
  }).then(function(){
    self.isLoading = false
  })
}

/*
*
* Fetch parse.ly search endpoint hydrating posts via custom REST API
*
*/
ProviderToACFRelationship.prototype.fetchParsely = function(){

  this.isLoading = true

  var params = {
    'tag': this.termTitle.replace(" ", "+"),
    'limit': this.limit
  }

  if(this.keyword  && this.keyword != '') params.q = this.keyword.replace(" ", "+")
  return axios.get(`/wp-json/wellandgood/v1/trending_articles/search`, { params: params }).then(function (response) {
    return response.data;
  })
}

/*
*
* Fetch YouTube
*
*/
ProviderToACFRelationship.prototype.fetchYoutube = function(){

  this.isLoading = true

  var params = {
    'limit': this.limit
  }

  return axios.get(`/wp-json/wellandgood/v1/trending_articles/search`, { params: params }).then(function (response) {
    return response.data;
  })
}

/*
*
* Get ACF Relationship (posts_by_term_and_keyword) field name
*
*/
ProviderToACFRelationship.prototype.getFieldName = function( ){
 return this.$el.find('.acf-relationship > input').attr('name')
}


/*
*
* Get ACF Relationship (posts_by_term_and_keyword) field key
*
*/
ProviderToACFRelationship.prototype.getFieldKey = function(){
  return this.$el.find('[data-type="relationship"]').data('key')
}

/*
*
* Get ACF Relationship (posts_by_term_and_keyword) value (array of IDs)
*
*/
ProviderToACFRelationship.prototype.getValue = function(){
  var ids = []
  jQuery(`input[name="${this.getFieldName()}[]"]`).each(function(index) {
    ids.push(parseInt(jQuery(this).val()))
  })
  this.val = ids
  return ids
}

/*
*
* Inject post into ACF Relationship (posts_by_term_and_keyword)
*
*/
ProviderToACFRelationship.prototype.injectItem = function( post ){
  if(this.val.includes(post.ID)) return
  var $choice = this.$el.find(`.acf-relationship .selection .choices .choices-list [data-id="${post.ID}"]`)
  if($choice.length) $choice.addClass('disabled')
  var $values = this.$el.find('.acf-relationship .selection .values .values-list')
  $values.append(this.getListElementDOM(post))
}

/*
*
* Get LI markup for ACF Relationship injection
*
*/
ProviderToACFRelationship.prototype.getListElementDOM = function( data = {} ){
  return `<li><input type="hidden" name="${this.getFieldName()}[]" value="${data.id}"><span data-id="${data.id}" class="acf-rel-item"><div class="thumbnail"><img src="${data.image_thumbnail}" alt=""></div>${data.title}<a href="#" class="acf-icon -minus small dark" data-name="remove_item"></a></span></li>`
}


/*
*
* Init action
*
*/
function populateFromProvider(el, provider){
  var $el = jQuery(el)

  if($el.data('Provider2Acf')){
    var Provider2Acf = $el.data('Provider2Acf')
  } else {
    var Provider2Acf = new ProviderToACFRelationship(el)
  }

  if(Provider2Acf.isLoading) return

  acf.startButtonLoading($el)
  $el.addClass('disabled')


  return Provider2Acf['populateFrom'+provider]().catch(function(){
    console.log('Populate from '+ provider +' canceled')
  }).then(function(){
    acf.stopButtonLoading($el)
    $el.removeClass('disabled')
  })
}


/*
*
* Public functions to pass on buttons inside the ACF message field
*
*/

function populateFromParsely(el){

  populateFromProvider(el, 'Parsely')

}

function populateFromYoutube(el){

  populateFromProvider(el, 'YouTube')

}
