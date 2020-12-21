const servingsInput = 'js-servings-input'
const ingredientAmount = 'js-ingredient-calc'

function recipeCard( el ){

  var servingsInputEl = document.getElementById(servingsInput)
  if( servingsInputEl ){
    servingsInputEl.addEventListener('input', function(e){
      updateIngredients(el, e.target.value)
    })
  }

}

function updateIngredients(el, servings){
  var ingredients = el.getElementsByClassName(ingredientAmount)

  for( var ii = 0; ii <= ingredients.length; ii++ ){
    var ratio = parseFloat(ingredients[ii].getAttribute('data-ratio'));

    if( ratio > 0){
      var newAmount = ratio * servings
      ingredients[ii].textContent = Math.round(newAmount * 100)/100
    }

  }
}

module.exports = recipeCard;
