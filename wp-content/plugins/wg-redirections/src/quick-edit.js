const jQuery = window.jQuery

jQuery(document).ready(function ($) {
  var tableEl = $('#the-list')
  var quickViewEditEl = $('.js-quickview-edit')
  var bulkQuickViewEditEl = $('#bulk-quickview-edit')
  var bulkQuickViewEditHiddenEl = $('#bulk-quickview-edit-hidden')
  var quickEditCancelButton = $('.js-quick-edit-cancel')
  var quickEditForm = $('.js-quick-edit-form')

  var redirectionInputHidden = $('.js-redirection-id')
  var sourceUriInput = $('.js-source-uri')
  var targetUriInput = $('.js-target-uri')
  var httpResponseSelect = $('.js-http-response')
  var typeSelect = $('.js-type')
  var isActiveCheckbox = $('.js-is-active')
  var spinnerEl = $('.js-spinner')

  // row show all
  function rowsShowAll () {
    if (tableEl.length > 0) {
      tableEl.find('tr').each(function () {
        $(this).show()
      })
    }
  }

  // binding data to quick edit form
  function bindingData (rowEl) {
    if (rowEl) {
      var resourceUri = rowEl.find('.js-column-source-uri')
      var targetUri = rowEl.find('.js-column-target-uri')
      var httpResponse = rowEl.find('.js-column-http-response')
      var type = rowEl.find('.js-column-type')
      var isActive = rowEl.find('.js-is-active')

      sourceUriInput.val(resourceUri.text())
      targetUriInput.val(targetUri.text())
      httpResponseSelect.val(httpResponse.text())

      if (type.text() === 'None') {
        typeSelect.val(null)
      } else {
        type = window.wg_redirections_data.possible_types.filter(possibleType => possibleType.label === type.text())[0].value
        typeSelect.val(type)
      }
      if (isActive.text() === 'Yes') {
        isActiveCheckbox.prop('checked', true)
      } else {
        isActiveCheckbox.prop('checked', false)
      }
    }
  }

  // binding data to row edit
  function bindingDataRow (rowEl, data) {
    if (rowEl && data) {
      var resourceUri = rowEl.find('.js-column-source-uri')
      var targetUri = rowEl.find('.js-column-target-uri')
      var httpResponse = rowEl.find('.js-column-http-response')
      var type = rowEl.find('.js-column-type')
      var isActive = rowEl.find('.js-is-active')

      resourceUri.text(data.source_uri)
      targetUri.text(data.target_uri)
      httpResponse.text(data.http_response)

      if (!data.type) {
        var typeLabel = '<span style="opacity:0.4; font-style: italic;">None</span>'
      } else {
        typeLabel = window.wg_redirections_data.possible_types.filter(possibleType => possibleType.value === data.type)[0].label
      }
      type.html(typeLabel)

      if (data.is_active === '1') {
        isActive.text('Yes')
        isActive.css('background', 'lime')
      } else {
        isActive.text('No')
        isActive.css('background', '#FF6347')
      }
    }
  }

  // quick edit click
  if (quickViewEditEl) {
    quickViewEditEl.on('click', function (e) {
      e.preventDefault()

      var redirectionId = $(this).data('id')
      var rowEl = $('#redirection-' + redirectionId)

      bindingData(rowEl)
      rowsShowAll()
      bulkQuickViewEditEl.hide()
      if (rowEl && bulkQuickViewEditEl && bulkQuickViewEditHiddenEl) {
        bulkQuickViewEditEl.show()
        rowEl.hide()

        bulkQuickViewEditEl.insertAfter(rowEl)
        bulkQuickViewEditHiddenEl.insertAfter(rowEl.closest('tr'))

        // set redirection id to form quick edit
        redirectionInputHidden.val(redirectionId)
      }
    })
  }

  // button cancel click
  if (quickEditCancelButton) {
    quickEditCancelButton.on('click', function (e) {
      e.preventDefault()

      rowsShowAll()
      bulkQuickViewEditEl.hide()
    })
  }

  // submit form
  if (quickEditForm) {
    quickEditForm.submit(function (e) {
      e.preventDefault()
      var data = $(this).serialize()

      if (spinnerEl) {
        spinnerEl.addClass('is-active')
        $.ajax({
          type: 'POST',
          url: window.redirection_vars.ajax_url,
          dataType: 'json',
          data,
          success: response => {
            spinnerEl.removeClass('is-active')
            if (typeof response.error === 'undefined') {
              rowsShowAll()
              bulkQuickViewEditEl.hide()
              setTimeout(() => {
                var data = response.data
                bindingDataRow($('#redirection-' + data.id), data)
              }, 1)
            } else {
              alert(response.error_message)
            }
          }
        })
      }
    })
  }
})
