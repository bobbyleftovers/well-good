import Vue from 'vue'
import AppState from 'lib/appState'
import Cookies from 'js-cookie'
var axios = require('axios');

var API_URL = '/wp-json/api/v1/esp/signup'

var DEFAULT_ERROR_MESSAGE = 'An error has occurred';
var EMAIL_ERROR_MESSAGE = 'Please enter a valid email address';

var MOBILE_BREAKPOINT = 1024;
var IS_MOBILE = window.innerWidth <= MOBILE_BREAKPOINT;

var BACKGROUND_TYPE_COLOR = 'color';

var TRAFFIC_SOURCE_EMAIL_SELECTOR = 'traffic_source_is_email';
var CLOSE_POLICY_SUPPRESS_SELECTOR = 'close_policy_suppress';
var COOKIE_SINGUP_SUCCESS = 'wellgood_signed_up';
var COOKIE_SINGUP_FORCE_CLOSED = 'wellgood_signed_force_closed';

Vue.component('email-capture', {
  props: ['desktopData', 'mobileData', 'formId', 'isPreviewModeOn', 'horizontalFallback', 'squareFallback', 'recaptchaKey'],
  data: function () {
    return {
      email: null,
      widgetType: 'circle',
      isVisible: false,
      isLoading: false,
      isMobile: IS_MOBILE,
      isReady: false,
      customFormId: null,
      customData: {},
      successMessage: null,
      errorMessage: null,
      state: AppState
    };
  },
  watch: {
    'state.emailCapture': {
      deep: true,
      handler () {
        console.log(this.state.emailCapture)
        if (
          this.checkSignedUpCookie() ||
          this.checkForceClosedCookie()
        ) {
          return null
        }
        const loadDelay = this.currentCaptureData && this.currentCaptureData.load_delay ? parseInt(this.currentCaptureData.load_delay) : 0
        setTimeout(() => {
          this.isVisible = true
          this.isReady = true
          this.trackImpressionInGTM()
        }, isNaN(loadDelay) ? 0 : loadDelay * 1000)
      }
    }
  },
  computed: {
    isDarkMode () {
      return this.currentCaptureData && this.currentCaptureData.theme === 'dark'
    },
    captureDesignType () {
      return (this.state.emailCapture && this.state.emailCapture.data) ? this.state.emailCapture.data.form_vertical_form_type : 'horizontal';
    },
    fallbackDataForType () {
      return this.captureDesignType === 'square' ? JSON.parse(this.squareFallback) : JSON.parse(this.horizontalFallback)
    },
    currentCaptureData () {
      const data = this.state.emailCapture && this.state.emailCapture.data ? this.state.emailCapture.data : {}
      const finalData = Object.assign({}, this.fallbackDataForType)
      for (const key in data) {
        if (data.hasOwnProperty(key)) {
          if (data[key] && data[key] !== '') {
            finalData[key] = data[key]
          }
        }
      }
      return finalData
    },
    currentPartner() {
      return this.currentCaptureData.partner ? this.currentCaptureData.partner : 'Internal'
    },
    currentCampaign() {
      return this.currentCaptureData.campaign ? this.currentCaptureData.campaign : ''
    },
    dataByDevice: function () {
      return IS_MOBILE ? JSON.parse(this.mobileData) : JSON.parse(this.desktopData)
    },
    getBackgroundImage: function () {
      if (
        this.dataByDevice.background_type === BACKGROUND_TYPE_COLOR) {
        return false
      }

      return this.dataByDevice.background_image;
    },
    hasBannedElement: function () {
      return !!document.getElementById(TRAFFIC_SOURCE_EMAIL_SELECTOR) || !!document.getElementById(CLOSE_POLICY_SUPPRESS_SELECTOR);
    },
    hasUTMSourceEmail () {
      return window.location.search && window.location.search.includes('utm_medium=email')
    },
    currnetFormId () {
      const id = this.currentCaptureData.vertical_signup_list_id
      return id !== "" ? this.formId : id
    },
    cookiePrefix () {
      return this.state.emailCapture && this.state.emailCapture.vertical ? this.state.emailCapture.vertical : ''
    },
    successCookieKey () {
      return `${COOKIE_SINGUP_SUCCESS}_${this.cookiePrefix}`
    },
    closedCookieKey () {
      return `${COOKIE_SINGUP_FORCE_CLOSED}_${this.cookiePrefix}`
    }
  },
  methods: {
    checkSignedUpCookie () {
      return Cookies.get(this.successCookieKey) == `true`;
    },
    checkForceClosedCookie () {
      return Cookies.get(this.closedCookieKey) == `true`;
    },
    hideEmailCapture: function () {
      this.setForceCloseCookie();
      this.isReady = false;
      this.isVisible = false;
      this.trackForceCloseGTM();
    },
    setForceCloseCookie () {
      Cookies.set(this.closedCookieKey, `true`, { expires: 60 })
    },
    setErrorMessage: function (message) {
      this.errorMessage = message;
    },
    setSuccessMessage: function (message) {
      this.successMessage = message;
    },
    setIsLoading: function (isLoading) {
      this.isLoading = isLoading;
    },
    setIsReady: function (isReady) {
      this.isReady = isReady;
    },
    validateEmail: function () {
      this.setErrorMessage(null);

      var isEmailValid = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,8})+$/.test(this.email);

      if (!isEmailValid) {
        this.setErrorMessage(EMAIL_ERROR_MESSAGE);

        return;
      }
    },
    handleFormSubmit: function () {
      this.trackSubmitFormGTM();
      this.validateEmail();

      var trackErrorInGTM = this.trackErrorInGTM;

      if (this.errorMessage) {
        trackErrorInGTM();

        return;
      }
      grecaptcha.ready(() => {
        grecaptcha.execute(this.recaptchaKey, { action: 'submit' }).then((token) => {
          this.sendFormRequest(token)
        }).catch((error) => {
          console.log(error)
        })
      })
    },
    onSignupSuccess () {
      Cookies.set(this.successCookieKey, `true`)
      setTimeout(() => {
        this.isVisible = false
      }, 2500)
    },
    sendFormRequest: function (recaptchaToken = null) {
      var email = this.email;
      var formId = this.currnetFormId;
      var setErrorMessage = this.setErrorMessage;
      var setSuccessMessage = this.setSuccessMessage;
      var setIsLoading = this.setIsLoading;
      var getErrorMessageFromResponse = this.getErrorMessageFromResponse;
      var trackSuccessInGTM = this.trackSuccessInGTM;
      var trackErrorInGTM = this.trackErrorInGTM;
      this.errorMessage = null;
      this.successMessage = null;

      setIsLoading(true);

      axios.post(API_URL, {
        email: email,
        form_id: formId,
        recaptchaToken
      }).then((response) => {
          setIsLoading(false);
          trackSuccessInGTM();
          this.onSignupSuccess()
          if (!response.data && !response.data.success) {
            setErrorMessage(DEFAULT_ERROR_MESSAGE);

            return;
          }

          setSuccessMessage(response.data.message);

          try {
            if (window.PARSELY) {
              trackParselyTrackSubscription()
            }
          } catch (error) {}
        })
        .catch(function (error) {
          setIsLoading(false);

          var errorMessage = null;

          if (!errorMessage && error.response.data && error.response.data.data) {
            errorMessage = getErrorMessageFromResponse(error.response.data.data);
          }

          if (!errorMessage && error.response.data && error.response.data.message) {
            errorMessage = error.response.data.message;
          }

          if (!errorMessage) {
            errorMessage = DEFAULT_ERROR_MESSAGE;
          }

          setErrorMessage(errorMessage);
          trackErrorInGTM();
        });
    },
    getErrorMessageFromResponse: function (data) {
      if (
        data &&
        data.params &&
        data.params.email) {
        return data.params.email;
      }

      if (
        data &&
        data.params &&
        data.params.recaptchaToken) {
        return data.params.recaptchaToken;
      }

      return null;
    },
    trackSubmitFormGTM () {
      if (dataLayer) {
        dataLayer.push({
          'event': 'newsletter subscribe button',
          'prompt': 'popup',
          'popup_partner': this.currentPartner,
          'popup_campaign': this.currentCampaign,
       });
      }
    },
    trackForceCloseGTM () {
      if (dataLayer) {
        dataLayer.push({
          'event': 'newsletter close',
          'prompt': 'popup',
          'popup_partner': this.currentPartner,
          'popup_campaign': this.currentCampaign,
       });
      }
    },
    trackSuccessInGTM: function () {
      if (dataLayer) {
        dataLayer.push({
          'event': 'newsletter subscribe success',
          'prompt': 'popup',
          'popup_partner': this.currentPartner,
          'popup_campaign': this.currentCampaign,
        });
      }
    },
    trackErrorInGTM: function () {
      if (dataLayer) {
        dataLayer.push({
          'event': 'newsletter subscribe error',
          'prompt': 'popup',
          'error': this.errorMessage,
          'popup_partner': this.currentPartner,
          'popup_campaign': this.currentCampaign,
        });
      }
    },
    trackImpressionInGTM: function () {
      if (dataLayer) {
        dataLayer.push({
          'event': 'newsletter impression',
          'prompt': 'popup',
          'popup_partner': this.currentPartner,
          'popup_campaign': this.currentCampaign,
        });
      }
    }
  },
});
