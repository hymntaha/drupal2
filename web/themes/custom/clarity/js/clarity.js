(function ($, Drupal) {

    'use strict';

    Drupal.behaviors.clarity = {
        attach: function (context, settings) {

            var resizeTimer;

            $(window, context).resize(function(){
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(resizeFunctions(context), 250);
            });

            loadFunctions(context);

            function loadFunctions(context){
                smoothScroll(context);
                navbarSetAffix(context);
                newsletterSubmit(context);
                newsletterSubmittedScroll();
                alternatingContentLoadSetup(context);
                solutionCategoriesLoadSetup(context);
                teaserSetHeights(context);
                alternatingContentSetLeft(context);
                setAnimationWaypoints(context);
                triggerAnimations(context);
                triggerAnimationsLeft(context);
            }

            function resizeFunctions(context){
                teaserSetHeights(context);
                alternatingContentSetLeft(context);
            }

            function smoothScroll(context){
                $('a[href*="#"]:not([href="#"],[role="button"])', context).click(function() {
                    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
                        var target = $(this.hash);
                        target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
                        if (target.length) {
                            $('#navbar').css('position','fixed');
                            $('html, body').animate({
                                scrollTop: target.offset().top - $('#navbar').height()
                            }, 1000);
                            $('#navbar').css('position','');
                            return false;
                        }
                    }
                });

                var uri = new URI();
                var queryParams = uri.search(true);

                if(queryParams.anchor !== undefined){
                    window.setTimeout(function(){
                        $('a[href="#'+queryParams.anchor+'"]').trigger('click');
                    }, 50);
                }
            }

            function navbarSetAffix(context){
                $('.navbar-default', context).affix({
                    offset:{
                        top: 45
                    }
                });
            }

            function newsletterSubmit(context){
                $('#webform-submission-newsletter-sign-up-node-196-form', context).submit(function(){
                   goog_report_conversion(window.location.href);
                });
            }

            function newsletterSubmittedScroll(){
                var uri = new URI();
                var queryParams = uri.search(true);

                if(queryParams.yamlform_id !== undefined){
                    if(queryParams.yamlform_id == 'newsletter_sign_up'){
                        if(uri.pathname() != '/resources/news'){
                            window.scrollTo(0,document.body.scrollHeight);
                        }
                    }
                }
            }

            function alternatingContentLoadSetup(context){
                var i = 0;
                $('.field--name-field-alternating-content .alternating-content',context).each(function(){
                    if(i % 2 == 0){
                        $('.alternating-content-cell-left',this).addClass('text').find('> div').addClass('bounce-in-left');
                        $('.alternating-content-cell-right',this).addClass('image');
                    }
                    else{
                        var text = $('.alternating-content-cell-left', this).html();
                        var image = $('.alternating-content-cell-right', this).html();

                        $('.alternating-content-cell-left',this).addClass('image').html(image);
                        $('.alternating-content-cell-right',this).addClass('text').html(text).find('> div').addClass('bounce-in-right');
                    }
                    i++;
                });
            }

            function solutionCategoriesLoadSetup(context){
                var i = 0;
                $('.solution-category-wrapper', context).each(function(){
                   if(i % 2 != 0){
                       if($('.solution-category-video', this).length > 0){
                           $('.solution-category-video', this).before($('.solution-category-text',this).detach());
                       }
                   }
                   i++;
                });
            }

            function alternatingContentSetLeft(context){
                var offset = $('.page-header', context).offset();
                if(offset !== undefined){
                    var left = offset.left - 15;
                    $('.alternating-content-cell-left.text > div[class^="col-"]', context).css('left',left);
                    $('.alternating-content-cell-right.text > div[class^="col-"]', context);
                }
            }

            function setAnimationWaypoints(context){
                $('.animation-waypoint', context).waypoint({
                   handler: function(direction){
                       $(
                           '.fade-in,' +
                           '.fade-in-slow,' +
                           '.bounce-in,' +
                           '.bounce-in-left,' +
                           '.bounce-in-right'
                           ,this.element).addClass('active');
                   },
                   offset: '65%'
                });
            }

            function triggerAnimations(context){
                setTimeout(function() {
                    $('#hero-image-text-wrapper').addClass('fade-in-slow').addClass('active');
                }, 1000);
            }

            function triggerAnimationsLeft(context){
                setTimeout(function() {
                    $('.hero-image-text-wrapper-left').addClass('fade-in-slow').addClass('active');
                }, 1000);
            }

            function teaserSetHeights(context){
                var $elements = $(
                    'article.white-paper.featured .node-title a, ' +
                    'article.solution.teaser .node-title a, ' +
                    '.field--name-field-how-we-make-it-easy > .field__items > .field--item'
                    , context);

                Drupal.behaviors.clarity.setMinHeight($elements);
            }
        },

        setMinHeight: function($elements){
            var max_height = 0;

            $elements.css('min-height','');

            $elements.each(function(){
                if($(this).outerHeight() > max_height){
                    max_height = $(this).outerHeight();
                }
            });

            if(max_height > 0){
                $elements.css('min-height',max_height);
            }
        }
    };

})(jQuery, Drupal);