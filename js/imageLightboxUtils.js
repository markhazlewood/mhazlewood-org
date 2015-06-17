
var activityIndicatorOn = function()
{
    $( '<div id="imagelightbox-loading"><div></div></div>' ).appendTo( 'body' );
};

var activityIndicatorOff = function()
{
    $( '#imagelightbox-loading' ).remove();
};

var overlayOn = function()
{
    $( '<div id="imagelightbox-overlay"></div>' ).appendTo( 'body' );
};

var overlayOff = function()
{
    $( '#imagelightbox-overlay' ).remove();
};

var closeButtonOn = function( instance )
{
    $( '<a href="#" id="imagelightbox-close">Close</a>' ).appendTo( 'body' ).on( 'click', function(){ $( this ).remove(); instance.quitImageLightbox(); return false; });
};

var closeButtonOff = function()
{
    $( '#imagelightbox-close' ).remove();
};

var captionOn = function()
{
    var description = $( 'a[href="' + $( '#imagelightbox' ).attr( 'src' ) + '"] img' ).attr( 'alt' );
    if( typeof description !== 'undefined' && description.length > 0 )
        $( '<div id="imagelightbox-caption">' + description + '</div>' ).appendTo( 'body' );
};

var captionOff = function()
{
    $( '#imagelightbox-caption' ).remove();
};

var navigationOn = function( instance, selector )
{
    var images = $( selector );
    if( images.length )
    {
        var nav = $( '<div id="imagelightbox-nav"></div>' );
        for( var i = 0; i < images.length; i++ )
            nav.append( '<a href="#"></a>' );

        nav.appendTo( 'body' );
        nav.on( 'click touchend', function(){ return false; });

        var navItems = nav.find( 'a' );
        navItems.on( 'click touchend', function()
        {
            var $this = $( this );
            if( images.eq( $this.index() ).attr( 'href' ) != $( '#imagelightbox' ).attr( 'src' ) )
                instance.switchImageLightbox( $this.index() );

            navItems.removeClass( 'active' );
            navItems.eq( $this.index() ).addClass( 'active' );

            return false;
        })
        .on( 'touchend', function(){ return false; });
    }
};

var navigationUpdate = function( selector )
{
    var items = $( '#imagelightbox-nav a' );
    items.removeClass( 'active' );

    var filterString = '[href="' + $( '#imagelightbox' ).attr( 'src' ) + '"]'
    var filter = $( selector ).filter(filterString);

    items.eq(   filter.index( selector ) ).addClass( 'active' );
};

var navigationOff = function()
{
    $( '#imagelightbox-nav' ).remove();
};