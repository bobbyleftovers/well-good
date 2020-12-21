
function updateFields() {
    var types = document.querySelectorAll('.ads-type')

    if (!types) return
    for (var i = 0; i < types.length; i++) {
        var type = types[i]
        var typeRow = type.closest('.acf-row')
        var typeLabel = typeRow.querySelector('.ads-type__label input')

        var pages = typeRow.querySelectorAll('.ads-page')
        for (var ii = 0; ii < pages.length; ii++) {
            var page = pages[ii]
            var pageRow = page.closest('.acf-row')
            var pageId = ii + 1
            
            var pageName = typeLabel.value + pageId
            var pageLabel = pageRow.querySelector('.ads-page__label .acf-label')
            var pageHeader = pageLabel.querySelector('label')

            var pageUpdated = pageHeader.innerText === pageName
            if (typeLabel.value && !pageUpdated) {
                pageHeader.innerText = pageName
            } else if (!typeLabel.value && !pageUpdated) {
                pageHeader.innerText = 'Page ' + pageId
            }

            var units = pageRow.querySelectorAll('.ads-unit')  
            for (var iii = 0; iii < units.length; iii++) {
                var unit = units[iii]
                var unitRow = unit.closest('.acf-row')
                var unitId = iii + 1

                var unitName = typeLabel.value + pageId + '-' + unitId
                var unitLabel = unitRow.querySelector('.ads-unit__label .acf-label')
                var unitHeader = unitLabel.querySelector('label')

                var unitUpdated = unitHeader.innerText === unitName
                if (typeLabel.value && !unitUpdated) {
                    unitHeader.innerText = unitName
                } else if (!typeLabel.value && !unitUpdated) {
                    unitHeader.innerText = 'Unit ' + unitId
                }
            }
        }
    }
}

jQuery(function() {
    new acf.Model({
        actions: {
            'load': 'updateFields',
            'append': 'updateFields'
        },
        events: {
            'keyup input[type="text"]': 'updateFields'
        },
        updateFields: function() {
            updateFields()
        }
    })
})
