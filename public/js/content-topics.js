(function( $ ) {
	'use strict';

	window.Obliby = {
        init: function () {

          Obliby.loadMoreButton();

        },

		loadMoreButton: function() {
			
			jQuery( '#topic_load_more' ).on( 'click', async () => {

				const filterType = jQuery( '#topic_load_more' ).attr( 'filter' );
				const numberOfPosts = jQuery( '#topic_load_more' ).attr( 'numberofposts' );
				const topicData = jQuery( '#topic_load_more' ).attr( 'topicdata' );
				let offset = jQuery( '#topic_load_more' ).attr( 'offset' );

				const topicsContentRow = jQuery( '#topic-content-row' );
				const loadingIcon = '<div class="spinner-border text-white" role="status"></div>';
				const downIcon = '<span class="bb-icon-angle-down bb-icon-l"></span>';

				jQuery( '#topic_load_more' ).find( '.bb-icon-angle-down' ).remove();
				jQuery( '#topic_load_more' ).append( loadingIcon );

				const response = await fetch(
					`${window.location.origin}/wp-json/oblibytopics/v1/getpagination/?type=${filterType}&offset=${offset}&topicdata=${topicData}`
				);
				
				const topicResponse = await response.json();

				if ( topicResponse ) {

					if ( topicResponse.success === true &&  topicResponse.data.length > 0 ) {
						topicResponse.data.forEach((topic) => topicsContentRow.append( topic));

						if ( topicResponse.data.length < parseInt( numberOfPosts, 10 ) ) {
							jQuery( '#topic_load_more' ).addClass( 'd-none' );
						}

					} else {
						jQuery( '#topic_load_more' ).addClass( 'd-none' );
					}

				}

				jQuery( '#topic_load_more' ).find( '.spinner-border' ).remove();
				jQuery( '#topic_load_more' ).append( downIcon );

				if ( parseInt( jQuery(document).width(), 10 ) > 1200 ) {
					jQuery("html, body").animate({ scrollTop: jQuery(document).height() }, 1000);
				}

			})

		}
        
        

    };

    jQuery( function( $ ) {
        Obliby.init();
    });

})( jQuery );
