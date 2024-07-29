(function( $ ) {
	'use strict';

	window.OblibyUploader = {
        init: () => {

            if ( ! notice_data.notice_content ) return;

        	const noticeHtml = `<aside class="bp-feedback bp-messages loading" style="margin-bottom:20px;"><span class="bp-icon" aria-hidden="true"></span><p>${notice_data.notice_content}</p></aside>`;

            if ( jQuery( 'body' ).find( '#bp-media-uploader' ).length > 0 ) {
                jQuery( 'body' ).find( '#bp-media-uploader' ).find( '#bp-dropzone-content' ).prepend( noticeHtml );
            }

            if ( jQuery( 'body' ).find( '#bp-video-uploader' ).length > 0 ) {
                jQuery( 'body' ).find( '#bp-video-uploader' ).find( '#bp-video-dropzone-content' ).prepend( noticeHtml );
            }

        }
        
        

    };

    jQuery( function( $ ) {

		setTimeout(() => {
			OblibyUploader.init();
		}, '1000' );
        
    });

})( jQuery );
