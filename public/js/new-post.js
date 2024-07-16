(function( $ ) {
	'use strict';

	window.OblibyPost = {
        init: () => {

          OblibyPost.setCategory();

        },

		setCategory: () => {
			
			if ( ! oblibyAjax.categoryID ) {
				return;
			}

			const categoryEl = jQuery( 'body' ).find( `.sap-category-widget .cat_${oblibyAjax.categoryID}` );

			if ( categoryEl && categoryEl.length > 0 ) {
				categoryEl.find( 'input' ).prop( 'checked', true );
			}

		}
        
        

    };

    jQuery( function( $ ) {

		setTimeout(() => {
			OblibyPost.init();
		}, "1000");
        
    });

})( jQuery );
