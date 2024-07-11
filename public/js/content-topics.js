(function( $ ) {
	'use strict';

	window.Obliby = {
        init: () => {

          Obliby.loadMoreButton();
		  Obliby.infinityScroll();

        },

		loadMoreButton: () => {
			
			jQuery( '#topic_load_more' ).on( 'click', async () => {

				const loadMoreBtn = jQuery( '#topic_load_more' );

				if ( loadMoreBtn.hasClass( 'loading' ) ) {
					return;
				}

				const filterType = loadMoreBtn.attr( 'filter' );
				const numberOfPosts = loadMoreBtn.attr( 'numberofposts' );
				const topicData = loadMoreBtn.attr( 'topicdata' );
				let offset = loadMoreBtn.attr( 'offset' );

				const topicsContentRow = jQuery( '#topic-content-row' );
				const loadingIcon = '<div class="spinner-border text-white my-1 ms-1" role="status"></div>';
				const downIcon = '<span class="bb-icon-angle-down bb-icon-l"></span>';

				loadMoreBtn.find( '.bb-icon-angle-down' ).remove();
				loadMoreBtn.append( loadingIcon );
				loadMoreBtn.addClass( 'loading' );

				const response = await fetch(
					`${window.location.origin}/wp-json/oblibytopics/v1/getpagination/?type=${filterType}&offset=${offset}&topicdata=${topicData}`
				);
				
				const topicResponse = await response.json();

				if ( topicResponse ) {

					if ( topicResponse.success === true &&  topicResponse.data.length > 0 ) {
						topicResponse.data.forEach((topic) => topicsContentRow.append( topic));

						loadMoreBtn.attr( 'offset', parseInt( offset, 10 ) + 1 );

						if ( topicResponse.data.length < parseInt( numberOfPosts, 10 ) ) {
							loadMoreBtn.remove();
						}

					} else {
						loadMoreBtn.remove();
					}

				}

				loadMoreBtn.find( '.spinner-border' ).remove();
				loadMoreBtn.append( downIcon );
				loadMoreBtn.removeClass( 'loading' );

				if ( parseInt( jQuery(document).width(), 10 ) > 1200 ) {
					const y = jQuery(window).scrollTop(); 
					jQuery("html, body").animate({ scrollTop: y + 50 }, 1000);
				}

			})

		},

		infinityScroll: () => {

			jQuery( window ).on( "scroll", () => {
				
				const scrollHeight = window.scrollY || $(window).scrollTop();                                          
				if ((window.innerHeight + scrollHeight) >= document.body.offsetHeight) {
					
					setTimeout(() => {

						const loadMoreBtn = jQuery( 'body' ).find( '#topic_load_more' );
						
						if ( loadMoreBtn && loadMoreBtn.length > 0 ) {

							if ( ! loadMoreBtn.hasClass( 'loading' ) ) {
								loadMoreBtn.trigger( 'click' );
							}
						}

					}, "800");
				} 

			} );

		}
        
        

    };

    jQuery( function( $ ) {
        Obliby.init();
    });

})( jQuery );
