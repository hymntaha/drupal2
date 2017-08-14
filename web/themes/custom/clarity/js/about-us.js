(function ($, Drupal) {

    'use strict';

    Drupal.behaviors.about_us = {
        attach: function (context, settings) {
            var timelineBlocks = $('.cd-timeline-block', context),
                offset = 0.8;

            var resizeTimer;

            $(window, context).resize(function(){
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(resizeFunctions(context), 250);
            });

            //hide timeline blocks which are outside the viewport
            hideBlocks(timelineBlocks, offset);
            setBioHeights(context);

            //on scolling, show/animate timeline blocks when enter the viewport
            $(window).on('scroll', function(){
                (!window.requestAnimationFrame)
                    ? setTimeout(function(){ showBlocks(timelineBlocks, offset); }, 100)
                    : window.requestAnimationFrame(function(){ showBlocks(timelineBlocks, offset); });
            });

            function resizeFunctions(context){
                setBioHeights(context);
            }

            function setBioHeights(context){
                Drupal.behaviors.clarity.setMinHeight($('.team-bio.teaser', context));
            }

            function hideBlocks(blocks, offset) {
                blocks.each(function(){
                    ( $(this).offset().top > $(window).scrollTop()+$(window).height()*offset ) && $(this).find('.cd-timeline-img, .cd-timeline-content').addClass('invisible');
                });
            }

            function showBlocks(blocks, offset) {
                blocks.each(function(){
                    ( $(this).offset().top <= $(window).scrollTop()+$(window).height()*offset && $(this).find('.cd-timeline-img').hasClass('invisible') ) && $(this).find('.cd-timeline-img, .cd-timeline-content').removeClass('invisible').addClass('bounce-in');
                });
            }
        }
    };

})(jQuery, Drupal);