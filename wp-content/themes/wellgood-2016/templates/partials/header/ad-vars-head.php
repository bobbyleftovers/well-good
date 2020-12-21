<?php
$ad_config = get_ad_config(false);
?>

<script>
    // Establish an array of advertising slots
    // Used to check if Permutive is loaded before an ad fires
    var ADINTERVALS = [];

    // An array of ads that have loaded
    var ADSLOADED = [];

    // Used to compile a list of ads loaded on each page
    var ADCODES = {};

    // This will get set to true after the initial ad request has fired
    var ADSREADY = false;
    var EMAIL_CAPTURE_INIT = false;

    // Used to differentiate between mobile and desktop ads
    // var BREAKPOINT = 869;
    var BREAKPOINT = 1040;

    // Boolean
    var ISMOBILE = window.innerWidth <= BREAKPOINT;

    var ADCONFIG = <?= $ad_config; ?>;

</script>
