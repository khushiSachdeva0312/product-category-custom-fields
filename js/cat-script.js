jQuery(document).ready(function() {
    jQuery('.employee-title').on('click', function() {
        var description = jQuery(this).data('description');
        jQuery('#popup-description').text(description);
        jQuery('#description-popup').fadeIn();
    });

    jQuery('.close-button').on('click', function() {
        jQuery('#description-popup').fadeOut();
    });

    jQuery(window).on('click', function(event) {
        if (jQuery(event.target).is('#description-popup')) {
            jQuery('#description-popup').fadeOut();
        }
    });



    jQuery(".at-title").click(function () {
        jQuery(this)
          .toggleClass("active")
          .next(".at-tab")
          .slideToggle()
          .parent()
          .siblings()
          .find(".at-tab")
          .slideUp()
          .prev()
          .removeClass("active");
      });


});