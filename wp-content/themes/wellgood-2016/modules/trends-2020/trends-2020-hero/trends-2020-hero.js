var $ = require( 'jquery' );
var loadImage = require('@modules/main/image-load/image-load')

module.exports = function (el) {

    var $el = $(el)

    var add_scroll_animation = $el.data('add-scroll-animation');

    var $dom = {
      'wellness' : $('.trends-2020-hero__hero__img.img-wellness')[0],
      'trends-1' : $('.trends-2020-hero__hero__img.img-trends--1')[0],
      'trends-2' : $('.trends-2020-hero__hero__img.img-trends--2')[0],
      'trends-3' : $('.trends-2020-hero__hero__img.img-trends--3')[0],
      'trends-4' : $('.trends-2020-hero__hero__img.img-trends--4')[0],
      'trends-5' : $('.trends-2020-hero__hero__img.img-trends--5')[0],
      'trends-6' : $('.trends-2020-hero__hero__img.img-trends--6')[0],
      'trends-7' : $('.trends-2020-hero__hero__img.img-trends--7')[0],
      'trends-8' : $('.trends-2020-hero__hero__img.img-trends--8')[0],
      'trends-9' : $('.trends-2020-hero__hero__img.img-trends--9')[0],
      'trends-10' : $('.trends-2020-hero__hero__img.img-trends--10')[0],
      '2020' : $('.trends-2020-hero__hero__img.img-2020')[0],
      'introduction': $('.trends-2020-hero__introduction')[0]
    }

    var timeout = null

    var onScroll = function(debounce){

      if(typeof debounce === 'undefined') debounce = true;

      clearTimeout(timeout)

      if(debounce){
        timeout = setTimeout(function(){
          onScroll(false)
        }, 100)
      }

      var scrollTop = document.documentElement.scrollTop | document.body.scrollTop;

      var duration = window.innerHeight;

      if(scrollTop <= duration){
        var factor = 1/duration*scrollTop
        $dom['wellness'].style.transform = "translate3d(0,-"+170*factor+"%, 0)";
        $dom['trends-1'].style.transform = "translate3d(0,"+(0-300*factor)+"%, 0)";
        $dom['trends-2'].style.transform = "translate3d(0,"+(10-270*factor)+"%, 0)";
        $dom['trends-3'].style.transform = "translate3d(0,"+(20-240*factor)+"%, 0)";
        $dom['trends-4'].style.transform = "translate3d(0,"+(30-210*factor)+"%, 0)";
        $dom['trends-5'].style.transform = "translate3d(0,"+(40-180*factor)+"%, 0)";
        $dom['trends-6'].style.transform = "translate3d(0,"+(50-150*factor)+"%, 0)";
        $dom['trends-7'].style.transform = "translate3d(0,"+(60-120*factor)+"%, 0)";
        $dom['trends-8'].style.transform = "translate3d(0,"+(70-90*factor)+"%, 0)";
        $dom['trends-9'].style.transform = "translate3d(0,"+(80-60*factor)+"%, 0)";
        $dom['trends-10'].style.transform = "translate3d(0,"+(90-30*factor)+"%, 0)";
        $dom['2020'].style.transform = "translate3d(0,"+(-30*factor)+"%, 0)";

        $dom['wellness'].style.opacity = 1.0-factor*5.5;
        $dom['trends-1'].style.opacity = 1.0-factor*5.5;
        $dom['trends-2'].style.opacity = 1.0-factor*5;
        $dom['trends-3'].style.opacity = 1.0-factor*4.5;
        $dom['trends-4'].style.opacity = 1.1-factor*4;
        $dom['trends-5'].style.opacity = 1.1-factor*3.5;
        $dom['trends-6'].style.opacity = 1.1-factor*3;
        $dom['trends-7'].style.opacity = 1.15-factor*2.5;
        $dom['trends-8'].style.opacity = 1.15-factor*2;
        $dom['trends-9'].style.opacity = 1.15-factor*1.5;
        $dom['trends-10'].style.opacity = 1.2-factor;
        $dom['2020'].style.opacity = 1.3-factor*1.8;
        $dom['introduction'].style.opacity = 1.3-factor*1.5;
      }
    }

    var onLoad = function(){
      $el.addClass('is-loaded')
      setTimeout(function(){
        $el.addClass('animation-finished')
      },2300)
      if(add_scroll_animation) onScroll()
    }

    if(/MSIE \d|Trident.*rv:/.test(navigator.userAgent)){
      onLoad();
    } else {
      $(window).on('load', function(){
        onLoad();
      })
    }

    if(add_scroll_animation) $(window).on('scroll', onScroll)
};
