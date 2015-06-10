var subnavFixed = false;
              
var masthead = $('.masthead');
var mastheadHeight = masthead.height();
var mastheadBottom = $(document).scrollTop() + mastheadHeight;

var subnav = $('#subnav');
var subnavTop = subnav.offset().top;
var subnavBottom = subnavTop + subnav.height();

var contentRows = $('[id^="content-row"]');
var subnavIcons = $('[id ^= "section-icon-"]');

subnav.pin();
$('#logo').pin();

$(window).load(function()
{   
    scroll();
    $(window).scroll(scroll);
});

var scroll = function()
{
    // Get the bottom position of the masthead 
    // (it's fixed, so it's current bottom position will just be scroll top + it's height)
    mastheadBottom = $(document).scrollTop() + mastheadHeight;

    if (subnavTop < mastheadBottom)
    {
        subnav.addClass('hide-text');                        
        subnavIcons.addClass('fixed');
        //masthead.addClass('masthead-color');

        subnavFixed = true;
    }
    else if (subnavFixed)
    {
        subnav.removeClass('hide-text');
        subnavIcons.removeClass('fixed');
        //masthead.removeClass('masthead-color');

        subnavFixed = false;
    }

    // Check to see if one of the content sections is "current"
    var activeSection = 0;
    var scrolledToBottom = ($(window).scrollTop() + $(window).height() >= $(document).height());

    if (scrolledToBottom)
    {
        activeSection = contentRows.length;
    }
    else
    {
        contentRows.each(function(index)
        {
            var relativeTop = $(this).offset().top - $(document).scrollTop();
            var relativeBottom = relativeTop + $(this).height();

            if (relativeTop <= $(window).height() * 0.25)
            {
                activeSection = (index + 1);
            }
        });
    }


    if (activeSection != 0)
    {
        subnavIcons.each(function(index)
        { 
            if (index+1 != activeSection)
            {
                $(this).removeClass('active');
                $(this).addClass('inactive');
            }
            else
            {
                $(this).removeClass('inactive');
                $(this).addClass('active');
            }
        });
    }
    else
    {
        subnavIcons.removeClass('active');
        subnavIcons.addClass('inactive');
    }
}

var scrollToElement = function(elementName)
{            
    $(window).scrollTop($(elementName).offset().top - (mastheadHeight*3));
}

setupImageGrids();
setupLightboxGalleries();

function setupImageGrids()
{
    setupSingleImageGrid('#researchImages');
    setupSingleImageGrid('#modelingImages');
    setupSingleImageGrid('#ixdImages');
    setupSingleImageGrid('#testingImages');

    $('.item').mouseenter(function()
    {
        var overlay = $(this).children('.imageOverlay');
        if (overlay != null)
        {
            overlay.show();
        }
    });

    $('.item').mouseleave(function()
    {
        var overlay = $(this).children('.imageOverlay');
        if (overlay != null)
        {
            overlay.hide();
        }
    });
}

function setupSingleImageGrid(gridSelector)
{
    $(gridSelector).gridalicious(
    {
        width : 180,
        gutter : 10,
        selector : '.item'
    });
}

function setupLightboxGalleries()
{
    setupSingleGallery('#modelingImages .item a');
    setupSingleGallery('#researchImages .item a');
    setupSingleGallery('#ixdImages .item a');
    setupSingleGallery('#testingImages .item a');
}

function setupSingleGallery(anchorGroupSelector)
{
    var imagesLightbox = $(anchorGroupSelector).imageLightbox(
    {
        onStart:		function() { navigationOn( imagesLightbox, anchorGroupSelector ); overlayOn(); closeButtonOn( imagesLightbox ); },
        onEnd:			function() { navigationOff(); overlayOff(); captionOff(); closeButtonOff(); activityIndicatorOff(); },
        onLoadStart: 	function() { captionOff(); activityIndicatorOn(); },
        onLoadEnd:	 	function() { navigationUpdate( anchorGroupSelector ); captionOn(); activityIndicatorOff(); }
    });
}