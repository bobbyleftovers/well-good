var $ = require( 'jquery' )

function SignupForm(el) {
	this.form = el

	// var form_data = $(this.form).serialize();
	// var action = $(this.form).attr('action');

	this.inputEmail = el.email
	this.submitButton = el.submit
	this.errorDisplay = this.form.querySelector('.signup-form__result')

	this.subscribeUrl = 'https://api.iterable.com/api/lists/subscribe'
	this.formId = this.form.dataset.formId
  this.listId = this.form.dataset.listId
  this.location = this.form.dataset.location
	this.formIsValid = false
	this.formErrors = []
  this.loadingInterval = null
  this.recaptchaKey = el.dataset.recaptchaKey

	this.events()
}

SignupForm.prototype = {
	events: function() {
		this.form.addEventListener('focusin', this.onFormFocusIn.bind(this))
		this.form.addEventListener('focusout', this.onFormFocusOut.bind(this))
		this.submitButton.addEventListener('click', this.onFormSubmit.bind(this))
		this.inputEmail.addEventListener('input', this.onFormChange.bind(this))
	},


	/**
	 * On Form Focus In
	 */
	onFormFocusIn: function() {
    this.form.classList.add('focus')
		this.form.classList.add('signup-form__form--active')
	},


	/**
	 * On Form Focus Out
	 */
	onFormFocusOut: function() {
    this.form.classList.remove('focus')
		if (this.inputEmail.value == '') {
			this.form.classList.remove('signup-form__form--active')
		}
	},


	/**
	 * On Form Change
	 *
	 * Run Validate Form and enable the submit
	 * button if form is valid
	 */
	onFormChange: function() {
		this.formIsValid = this.validateForm()

		if (this.inputEmail.value == '') {
      this.submitButton.classList.add('disabled')
		} else {
			this.submitButton.classList.remove('disabled')
		}
	},


	/**
	 * Serialize Form
	 * @param {HTML} form
	 */
	serializeForm: function(form) {
		if (!form || form.nodeName !== "FORM") {
				return;
		}
		var i, j, q = [];
		for (i = form.elements.length - 1; i >= 0; i = i - 1) {
			if (form.elements[i].name === "") {
				continue;
			}
			switch (form.elements[i].nodeName) {
				case 'INPUT':
					switch (form.elements[i].type) {
						case 'text':
						case 'tel':
						case 'email':
						case 'hidden':
						case 'password':
						case 'button':
						case 'reset':
						case 'submit':
							q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
							break;
						case 'checkbox':
						case 'radio':
							if (form.elements[i].checked) {
									q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
							}
							break;
					}
					break;
					case 'file':
					break;
				case 'TEXTAREA':
						q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
						break;
				case 'SELECT':
					switch (form.elements[i].type) {
						case 'select-one':
							q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
							break;
						case 'select-multiple':
							for (j = form.elements[i].options.length - 1; j >= 0; j = j - 1) {
								if (form.elements[i].options[j].selected) {
										q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].options[j].value));
								}
							}
							break;
					}
					break;
				case 'BUTTON':
					switch (form.elements[i].type) {
						case 'reset':
						case 'submit':
						case 'button':
							q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
							break;
					}
					break;
				}
			}
		return q.join("&");
	},


	/**
	 * Validate Form
	 *
	 * Run through each field that requires validation
	 * and return a bool if there are no errors
	 *
	 * @returns {bool} errorCount === 0
	 */
	validateForm: function(compileErrorMessages) {
		var errorCount = 0
    this.formErrors = []
		// Email Validation
		if (this.inputEmail) {
			var email = this.inputEmail.value
			var emailIsValid = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,8})+$/.test(email)

			if (!emailIsValid) {
				errorCount++

				if (compileErrorMessages) {
					var emailErrorMessage = 'Please enter a valid email address'

					this.formErrors.push(emailErrorMessage)
				}
			}
		}

		return errorCount === 0
	},


	/**
	 * Loading Animation
	 *
	 * Display an animated elipsis in the
	 * submit button when being submitted
	 */
	loadingAnimation: function(unset) {
    if (unset) {
      this.submitButton.value = 'Join'
      return
    }
		if (this.submitButton.value.length > 3) {
			this.submitButton.value = ''
		}
		this.submitButton.value += '.'
	},


	/**
	 * On Form Submit
	 *
	 * Check if form is valid and route to
	 * proper function
	 *
	 * @param {*} e
	 */
	onFormSubmit: function(e) {
		e.preventDefault()

		this.formIsValid = this.validateForm(true)
    this.trackSubmitFormGTM()
		if (this.formIsValid) {
      grecaptcha.ready(() => {
        grecaptcha.execute(this.recaptchaKey, { action: 'submit' }).then((token) => {
            this.sendSubscriptionRequest(token)
        }).catch((error) => {
          console.log(error)
        })
      });
		} else {
			this.displayEntryError()
		}
	},


	/**
	 * Display Entry Error
	 *
	 * Append error messages to invalid fields
	 */
	displayEntryError: function() {
    jQuery(this.errorDisplay).empty()
		for (var i = 0; i < this.formErrors.length; i++) {
			var errorMessage = this.formErrors[i]
			var node = document.createElement('DIV')
			var textnode = document.createTextNode(errorMessage)

			node.appendChild(textnode)
			this.errorDisplay.appendChild(node)
    }
    jQuery(this.form).toggleClass('with-error', this.formErrors.length)
    if (this.formErrors.length) {
      this.trackErrorInGTM(this.formErrors.join(' '))
    }
  },

  getFormLocation () {
    return this.location ? this.location : 'popup'
  },

  trackSubmitFormGTM () {
    if (dataLayer) {
      dataLayer.push({
        'event': 'newsletter subscribe button',
        'prompt': this.getFormLocation(),
        'popup_partner': 'Internal'
     });
    }
  },

  trackForceCloseGTM () {
    if (dataLayer) {
      dataLayer.push({
        'event': 'newsletter close',
        'prompt': this.getFormLocation(),
        'popup_partner': 'Internal'
     });
    }
  },

  trackSuccessInGTM: function () {
    if (dataLayer) {
      dataLayer.push({
        'event': 'newsletter subscribe success',
        'prompt': this.getFormLocation(),
        'popup_partner': 'Internal'
      });
    }
  },

  trackErrorInGTM: function (error) {
    if (dataLayer) {
      dataLayer.push({
        'event': 'newsletter subscribe error',
        'prompt': this.getFormLocation(),
        'error': error,
        'popup_partner': 'Internal'
      });
    }
  },

  trackImpressionInGTM: function () {
    if (dataLayer) {
      dataLayer.push({
        'event': 'newsletter impression',
        'prompt': 'popup',
        'popup_partner': 'Internal'
      });
    }
  },


	/**
	 * Send Subscription Request
	 */
	sendSubscriptionRequest: function(recaptchaToken = null) {
		var self = this
		var action = this.form.action
    var formData = {
      recaptchaToken
    }
    $.map(jQuery(this.form).serializeArray(), function(n, i){
      formData[n['name']] = n['value'];
    })

    jQuery(self.form).removeClass('with-error')
    jQuery(this.errorDisplay).empty()

		this.loadingInterval = setInterval(function() {
			self.loadingAnimation()
    }, 360)
    jQuery(self.form).find('.signup-form__error').remove()
		$.ajax({
			url: action,
			type: 'POST',
			data: formData,
			success: function(data) {
				clearInterval(self.loadingInterval)
				if (data.success === true) {
          if (data.form.successMethod !== 'fake-redirect') {
            jQuery(self.form).addClass('success')
            jQuery(self.form).append('<div class="signup-form__thanks">' + data.message + '</div>')
          }
          self.trackSuccessInGTM()
          try {
            if (window.PARSELY) {
              trackParselyTrackSubscription()
            }
          } catch (error) {}
					if (data.form.successMethod === 'redirect' && data.form.redirect) {
						window.location.href = data.form.redirect
					}
        }

        if (data.form.successMethod === 'fake-redirect') {
          window.location.href = window.location.origin + window.location.pathname + "?success_signup=" + formData.email
        }
			},
			error: function(data) {
        clearInterval(self.loadingInterval)
        self.loadingAnimation(true)
        var responseJSON = data.responseJSON
        if (responseJSON.code === "rest_invalid_param") {
          jQuery(self.form).append('<div class="signup-form__error"> ' + Object.values(responseJSON.data.params).join('\n') + '</div>')
        }

        if (responseJSON.code === "internal_error") {
          jQuery(self.form).append('<div class="signup-form__error"> ' + (responseJSON.message) + '</div>')
        }

        jQuery(self.form).addClass('with-error')
        self.trackErrorInGTM(jQuery(self.form).find('.signup-form__error').text())
			}
		})
	}
}

module.exports = SignupForm
