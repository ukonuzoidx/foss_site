jQuery(document).ready(function($){
    // Price detail toggle
    $(document).on('click', '.download-cart-btn', function(e) {
        // Close all price overlays
        $(".edd-download-buy-button").removeClass("show-pricing");

        var id = $(this).data('id');
        console.log(id);
        // Show the price overlay
        $("#edd_download_btn_"+id).addClass("show-pricing");
    });
    
    // Escape key closes pricing on downloads
    $(document).on( 'keyup', function(e) {
        if (e.keyCode == 27) {
            $(".edd-download-buy-button").removeClass("show-pricing");
        }
    });
    
    // Close price detail
    $(".edd-download-buy-button .btn-close").on( 'click', function() {
        // Hide the price overlay
        $(".edd-download-buy-button").removeClass("show-pricing");

        return false;
    }); 

});