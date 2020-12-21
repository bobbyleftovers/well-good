(function ($) {

  /**
   * Send data to Leaf's API on form submit.
   * @constructor
   * @param {Object} el - The element containing the rating.
   */

  function Form(el) {
    var self = this;
      
    this.$el = $(el),
    this.successMessage = "<h2>Thank you.</h2><p>Your request has been submitted and we will contact you via email regarding further action.</p>",
    this.errorMessage = "<h2>Sorry, we encountered an error when submitting your request.</h2><p>Please try again later, or send us an email at info@wellandgoodnyc.com.</p>";
      
    this.$el.on('submit', function(e) {
      e.preventDefault();

      var formData = {
        "subject": "Well and Good GDPR Request (testing)",
        "comment": {
          "body": buildString(self.$el)
        }
      };
      
      $.ajax({
        url: 'https://api.leafmedia.io/zendesk/request',
        type: 'POST',
        dataType: 'application/json',
        data: formData
      }).done(function (data) {
        showStatusMessage(self.successMessage);
      }).error(function(error) {
        showStatusMessage(self.errorMessage);
        console.log(error);
      });

    });
    
    function showStatusMessage(message) {
      $('.gdpr-form__status').html(message).fadeIn(200);
    }

    function buildString($el) {
      var name = $el.find("input[name=name]").val(),
          email = $el.find("input[name=email]").val(),
          comment = $el.find("textarea[name=comment]").val();

      return name + ', ' + email + ', ' + comment;
    }

  }

  module.exports = Form;

})(jQuery);
