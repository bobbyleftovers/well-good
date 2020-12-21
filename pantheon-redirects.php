<?php

// Redirect Logic
$primary_domain = "wellandgood.com";
$redirect_domains = array(
  "live-well-good.pantheonsite.io",
  "wellandgoodnyc.com"
);
$protocol = "https";
$with_www = "www."; // set to empty string for false
$_http_host = str_replace( "www.", "", $_SERVER['HTTP_HOST'] );
$_request_uri = $_SERVER['REQUEST_URI'];
$_url_redirect = "$protocol://$with_www" . $primary_domain . $_request_uri;

    // one-to-one redirects
    $one_to_ones = array(
        "/wellness-wire"                                                                             => "https://www.wellandgood.com/",
        "/media"                                                                                     => "https://www.wellandgood.com/press/",
        "/advertise/"                                                                                => "https://www.wellandgood.com/advertise-with-wellgood/",
        "/fitnessbiathlon"                                                                           => "https://www.wellandgood.com/fitness-biathlon-2016/",
        "/mymorningroutine"                                                                          => "https://www.wellandgood.com/good-advice/best-celeb-morning-routines-of-2016/",
        "/exceed-fitness-the-upper-east-sides-new-fitness-playground"                                => "https://www.wellandgood.com/good-advice/exceeds-massive-new-tribeca-studio-comes-with-three-floors-of-functional-fitness/",
        "/goodness/april-eat-breakfast-before-you-drink-coffee"                                      => "https://www.wellandgood.com/good-advice/habits-of-morning-people/",
        "/goodness/heather-lilleston-scrub-your-skin-with-sand-at-the-beach"                         => "https://www.wellandgood.com/good-advice/how-to-dry-brush-your-body-for-the-beach/",
        "/2013/03/06/why-you-should-stick-your-butt-out-at-the-gym"                                  => "https://www.wellandgood.com/good-advice/how-to-sneak-squats-and-stretches-in-at-work/",
        "/5-new-health-and-fitness-books-worth-buying"                                               => "https://www.wellandgood.com/good-advice/the-10-most-exciting-healthy-books-to-read-in-2017/",
        "/cycling-chic-5-must-have-bike-accessories-for-spring"                                      => "https://www.wellandgood.com/good-advice/the-beginners-guide-to-biking-in-nyc-cyclist-essentials/",
        "/goodness/hot-poultice-massage"                                                             => "https://www.wellandgood.com/good-advice/the-five-life-changing-stress-busting-spa-therapies-youve-got-to-try-this-season/",
        "/wellness-summer-camps-7-healthy-getaways-for-grownups-near-nyc"                            => "https://www.wellandgood.com/good-advice/when-wellness-meets-summer-camp/",
        "/goodness/peaches"                                                                          => "https://www.wellandgood.com/good-food/3-fruits-that-wont-give-you-a-sugar-spike/",
        "/new-york-citys-best-veggie-burgers"                                                        => "https://www.wellandgood.com/good-food/best-healthy-frozen-veggie-burgers/",
        "/goodness/the-superfood-guide-pitaya"                                                       => "https://www.wellandgood.com/good-food/recipe-chia-oat-pitaya-super-bowl/",
        "/top-vegan-desserts-at-new-york-city-restaurants"                                           => "https://www.wellandgood.com/good-food/the-9-healthiest-bakeries-in-new-york/",
        "/2010/07/20/cetaphil-why-the-popular-cleanser-isnt-doing-your-skin-any-favors"              => "https://www.wellandgood.com/good-looks/cetaphil-why-the-popular-cleanser-isnt-doing-your-skin-any-favors/",
        "/lacityguide/best-los-angeles-yoga-studios"                                                 => "https://www.wellandgood.com/good-sweat/11-super-pretty-los-angeles-and-san-francisco-yoga-studios/",
        "/fantasy-home-gym-marisa-sakos-yoga-rope-wall"                                              => "https://www.wellandgood.com/good-sweat/3-things-a-yoga-rope-wall-can-do-for-your-practice/",
        "/new-york-citys-most-expensive-trainers"                                                    => "https://www.wellandgood.com/good-sweat/best-up-and-coming-fitness-instructors-2016/",
        "/lacityguide/best-los-angeles-pilates-group-mat-private-classes"                            => "https://www.wellandgood.com/good-sweat/best-workouts-los-angeles-2015/",
        "/goodness/brooklyn-athletic-club-september"                                                 => "https://www.wellandgood.com/good-sweat/brooklyn-athletic-club-massive-gym-williamsburg/",
        "/goodness/april-27-akt-in-motion-with-ariel-hoffman"                                        => "https://www.wellandgood.com/good-sweat/first-look-inside-akt-in-motions-new-upper-east-side-studio/",
        "/goodness/july-12-shadowbox-with-julie-jaws-nelson"                                         => "https://www.wellandgood.com/good-sweat/first-workout-report-shadowbox-gives-boxing-the-boutique-fitness-treatment/",
        "/brooklyns-boutique-fitness-scene-takes-off"                                                => "https://www.wellandgood.com/good-sweat/is-the-boutique-fitness-boom-moving-to-brooklyn/",
        "/2010/01/13/bi-bim-bath-soaping-up-in-the-citys-korean-bathhouses"                          => "https://www.wellandgood.com/good-sweat/just-add-water-aire-ancient-baths-pour-forth-in-tribeca/",
        "/reimagined-yoga-mats-3-innovative-mats-roll-out-this-spring"                               => "https://www.wellandgood.com/good-sweat/luxury-yoga-mats/",
        "/goodness/wellgood-readers-11-favorite-running-routes"                                      => "https://www.wellandgood.com/good-sweat/nycs-best-running-routes-wellgood-readers-picks/",
        "/best-los-angeles-megaformer-workouts"                                                      => "https://www.wellandgood.com/good-sweat/popularity-of-megaformer-workout/",
        "/7-extreme-races-that-will-make-you-a-total-fitness-badass"                                 => "https://www.wellandgood.com/good-sweat/spartan-vs-tough-mudder-how-the-two-muddy-challenges-compare/",
        "/goodness/half-marathon-training-plan"                                                      => "https://www.wellandgood.com/good-sweat/the-fit-persons-guide-to-running-your-first-half-marathon-with-training-plan/",
        "/extra-goodness/sweat-series-2"                                                             => "https://www.wellandgood.com/good-sweat/wellandgood-sweat-series-is-back-workout-events-athleta/",
        "/goodness/my-healthy-day-in-montauk-sian-gordon"                                            => "https://www.wellandgood.com/good-sweat/your-guide-to-the-hamptons-best-fitness-and-yoga/",
        "/goodness/the-superfood-guide-raw-cacao"                                                    => "https://www.wellandgood.com/good-food/recipe-cacao-balls/",
        "/goodness/3-ways-to-help-prevent-adrenal-fatigue-from-naturopathicas-founder-barbara-close" => "https://www.wellandgood.com/good-advice/8-signs-you-have-adrenal-fatigue-and-what-to-do-about-it/",
        "/goodness/summer-herbs"                                                                     => "https://www.wellandgood.com/good-advice/new-research-on-the-health-benefits-of-herbs-and-spices/",
        "/summer-running-style"                                                                      => "https://www.wellandgood.com/good-looks/running-gear-for-every-level/",
        "/healthysummerhotlist"                                                                      => "https://www.wellandgood.com/healthy-summer-hot-list/",
        "/hottesthealingmodalities"                                                                  => "https://www.wellandgood.com/good-sweat/investigating-reiki-one-of-the-spa-menus-most-mysterious-treatments/",
        "/2015/08/09/zarbees"                                                                        => "https://www.wellandgood.com/good-advice/power-plants-holy-basil/",
        "/2015/11/24/wellnesswonderland"                                                             => "https://www.wellandgood.com/good-looks/healthy-holiday-gift-guide-what-to-get-the-travel-junkie/",
        "/surfsidesalutations"                                                                       => "https://www.wellandgood.com/good-sweat/photos-surfside-salutations-montauk/",
        "/extra-goodness/sweat-series-3"                                                             => "https://www.wellandgood.com/good-sweat/wellandgood-sweat-series-is-back-workout-events-athleta/",
        "/extra-goodness/sweat-series-pop-ups"                                                       => "https://www.wellandgood.com/good-sweat/wellandgood-sweat-series-is-back-workout-events-athleta/",
        "/good-food/where-to-eat-and-drink-healthy-in-the-hamptons"                                  => "https://www.wellandgood.com/good-sweat/your-guide-to-the-hamptons-best-fitness-and-yoga/",
        "/extra-goodness/fitness-biathlons"                                                          => "https://www.wellandgood.com/good-sweat/fitness-biathlon-2016/",
        "/hamptonsguide"                                                                             => "https://www.wellandgood.com/good-sweat/the-ultimate-hamptons-summer-guide/",
        "/extra-goodness/farmers-market-navigator"                                                   => "https://www.wellandgood.com/good-advice/how-to-navigate-the-farmers-market-like-a-pro/",
        "/extra-goodness/fallfitnesspreview"                                                         => "https://www.wellandgood.com/fall-fitness-preview/",
        "/hamptons-styleguide"                                                                       => "https://www.wellandgood.com/good-sweat/the-ultimate-hamptons-summer-guide/",
        "/6-celebrities-who-love-natural-and-organic-beauty"                                         => "https://www.wellandgood.com/good-looks/celeb-natural-beauty-favorites/",
        "/five-biggest-fitness-cults"                                                                => "https://www.wellandgood.com/good-sweat/booty-works-indie-workouts-los-angeles/",
        "/madonna-turns-53-today-heres-how-she-stays-in-shape"                                       => "https://www.wellandgood.com/good-advice/madonna-launches-a-fitness-empire/",
        "/nail-polish-new-shades-and-higher-standards"                                               => "https://www.wellandgood.com/good-looks/a-new-era-of-super-clean-nail-polishes/",
        "/milk-alternatives-a-primer"                                                                => "https://www.wellandgood.com/good-advice/download-our-handy-and-popular-guide-to-milk-alternatives/",
        "/what-does-detox-mean-to-you"                                                               => "https://www.wellandgood.com/good-sweat/science-backed-detox/",
        "/wellness-tune-ups-small-sessions-you-can-get-done-in-a-jiffy"                              => "https://www.wellandgood.com/good-advice/serenebook-wellness-trends-2017/",
        "/more-than-just-juicethe-rise-of-combo-cleanses"                                            => "https://www.wellandgood.com/good-advice/are-juice-cleanses-really-healthy-why-some-wellness-experts-worry/",
        "/wellgood%e2%80%99s-12-fitness-and-wellness-trends-of-2012"                                 => "https://www.wellandgood.com/2016-fitness-wellness-trends/",
        "/superfood-guide"                                                                           => "https://www.wellandgood.com/good-food/8-new-superfoods/",
        "/cityguide/chicago"                                                                         => "https://www.wellandgood.com/tag/chicago/",
        "/cityguide/boston"                                                                          => "https://www.wellandgood.com/tag/boston/",
        "/cityguide/sanfrancisco"                                                                    => "https://www.wellandgood.com/good-looks/natural-beauty-brands-made-in-california/",
        "/best-los-angeles-natural-nail-salons"                                                      => "https://www.wellandgood.com/good-advice/are-natural-nail-polishes-really-non-toxic/",
        "/lacityguide/best-los-angeles-barre-studios"                                                => "https://www.wellandgood.com/good-sweat/new-los-angeles-barre-studios/",
        "/lacityguide/best-los-angeles-dance-cardio-workouts"                                        => "https://www.wellandgood.com/good-sweat/meet-the-fitness-scenes-dance-cardio-divas/",
        "/lacityguide/best-los-angeles-bootcamp-studios"                                             => "https://www.wellandgood.com/tag/bootcamps/",
        "/lacityguide/best-los-angeles-indoor-cycling-studios"                                       => "https://www.wellandgood.com/good-sweat/best-workouts-los-angeles-2015/",
        "/lacityguide/best-spas-near-los-angeles"                                                    => "https://www.wellandgood.com/good-looks/the-now-massage-los-angeles/",
        "/were-hiring"                                                                               => "https://www.leafgroup.com/job-openings/",
        "/good-food/beginners-guide-to-ketogenic-diet/"                                              => "https://www.wellandgood.com/good-food/keto-diet-for-beginners/",
        "/good-food/beginners-guide-to-ketogenic-diet/amp"                                           => "https://www.wellandgood.com/good-food/keto-diet-for-beginners/amp",
        "/best-exercises-for-mental-health"                                                         => "https://www.wellandgood.com/good-advice/mental-benefits-of-exercise/"
    );
 
        // automatically redirect if host other than primary domain is detected
       /* if ( in_array( $_http_host, $redirect_domains ) ) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: $_url_redirect");
            exit;
          } */
      
          // automatically redirect specific paths from old site
          foreach( $one_to_ones as $requestPath => $redirect_to ) {
            if ( strpos( $_request_uri, $requestPath ) !== false ) {
              header("HTTP/1.1 301 Moved Permanently"); 
              header("Location: $redirect_to"); 
              exit;
            }
          }
      
          // automatically redirect based on rules
          /* if(isset($regex_rules) && (is_array($regex_rules) || is_object($regex_rules))){
            foreach( $regex_rules as $regex => $replace ) {
              if ( @preg_match( $regex, $_request_uri ) ) {
                $replacement = preg_replace( $regex, $replace, $_request_uri, -1 );
                header("HTTP/1.1 301 Moved Permanently"); 
                header("Location: $replacement"); 
                exit;
              }
            }
          } */
      
          // Require HTTPS when $protocol set to https
          /* if ( "https" == $protocol && ( !isset( $_SERVER['HTTP_USER_AGENT_HTTPS'] ) 
          || $_SERVER['HTTP_USER_AGENT_HTTPS'] != 'ON' ) ) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: $_url_redirect");
            exit();
          } */
