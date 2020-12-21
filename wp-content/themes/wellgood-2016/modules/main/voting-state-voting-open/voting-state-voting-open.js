var $ = require('jquery');


function Vote(el) {
    var $el = $(el),
        modalSelector = $el.data('modal'),
        $modal = $el.find(modalSelector),
        $triggers = $($el.data('modal-trigger')),
        $form = $el.find('form'),
        $formWrapper = $el.find('.voting-modal__form'),
        $thanks = $el.find('.voting-modal__thanks'),
        $target,
        campaignId,
        nomineeId,
        $countTarget,
        $voteBar;

        global.onCaptchaSuccess = function(){
            document.getElementById('signup-form__submit').disabled = false;
        }

        $triggers.on('click', function(e) {
        $target = $(this);
        campaignId = $el.data('campaign-id');
        nomineeId = $target.data('nominee-id');
        $countTarget = $($target.data('vote-count'));
        $voteBar = $($target.data('vote-bar'));

        $modal.addClass('js-open');

        if ( ! $form.length ) {
            $thanks.fadeIn( 'fast' );
            addVote();
        }
    });

    $form.submit(function(e) {
        e.preventDefault();
        var $this = $(this);
        var form_data = $this.serialize();
        var action = $this.attr('action');
        var loading_class = $this.data( 'loading-class' );
        var $error = $( $this.data( 'error-elem' ) );
        fbq('track', 'CompleteRegistration');
        $error.html('');
        $this.addClass(loading_class).find('input').prop( 'disabled', true );
        $.ajax({
            url: action,
            type: 'POST',
            data: form_data,
            success: function(data){
                $this.removeClass(loading_class).find('input').prop( 'disabled', false );
                if(data.success === true) {
                    $('input[type!="submit"]').val('');
                    $thanks.fadeIn('fast');
                    $formWrapper.hide();
                    dataLayer.push({'event' : 'JoinNewsletter', 'formName' : 'Newsletter Signup'});

                    addVote();
                }
                else {
                    $error.html(data.message);
                }
            },
            error: function(data) {
                $this.removeClass(loading_class).find('input').prop( 'disabled', false );
                $error.html('Sorry, an error occurred. Please try again.');
            }
        });
        return false;
    });

    $modal.on('click', function(e) {
        var $target = $(e.target);

        if ($target.is(modalSelector + ',' + $el.data('modal-close'))) {
            $modal.removeClass('js-open');
        }
    });

    function addVote() {
        $.ajax({
            url: '/wp-json/wellandgood/v1/campaign/' + campaignId + '/nominee/' + nomineeId + '/vote',
            method: 'POST',
            beforeSend: function ( xhr ){
                xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
            },
            dataType: 'json'
        }).done(function(data) {
            $countTarget.text(data.ratio + '%');
            $voteBar.css('width', data.ratio + '%');

            $triggers.removeClass('voting-button');
            $triggers.prop('disabled', true);
            $triggers.not($target).text('Already Voted');
            $target.text('Voted').addClass('nominee__button--voted');
        });
    }
}

module.exports = Vote;