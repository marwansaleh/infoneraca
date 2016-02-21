var ImageDefaultManager = {
    _baseUrl: 'http://placehold.it/',
    availableName: ["tiny","smaller","square","small","medium","portrait","large"],
    availableWidth: [42,57,70,230,362,555,726],
    availableHeight: [42,57,70,147,205,350,463],
    getDefaultImage: function (size){
        //get default width and height if available
        var index = this.availableName.indexOf(size);
        if ( index>=0 && index<this.availableName.length ){
            var sizeText = this.availableWidth[index]+'x'+this.availableHeight[index];

            return this._baseUrl+sizeText+'?text=default';
        }
    },
    init: function() {
        //console.log('ImageDefaultMnager is running');
        var _this = this;
        $('img').on('error',function(){
            //check if image tag has class
            if ($(this).attr('class')){
                for (var i in _this.availableName){
                    if ($(this).hasClass(_this.availableName[i])){
                        $(this).attr('src', _this.getDefaultImage(_this.availableName[i]));
                        //console.log('error_image:'+_this.availableName[i]+' replaced by:'+_this.getDefaultImage(_this.availableName[i]));
                        return;
                    }
                }
                console.log('Error on image load, but colud not find its replacement');
            }
            
        });
    },
};
            
var Nasabah = {
    slideCounter : 0,
    init: function (){
        //nice scroll
        $('html').niceScroll({cursorcolor:"#00F"});
        $('.nicescroll').niceScroll({cursorcolor:"#00F"});
        
        $('.ticker').ticker();
        $('#slider').flexslider({
            controlNav: false,
            directionNav : false,
            touch: true,
            animation: "fade",
            animationLoop: true,
            slideshow : false
        });
        
        $('.slider-navigation .navigation-item').on('click', function() {
            var link = $(this).attr('rel');
            link = parseInt(link);
            $('.slider-navigation .navigation-item.active').removeClass('active');
            $(this).addClass('active');
            $('#slider').flexslider((link-1));
            clearInterval(intervalID);
            intervalID = setInterval( Nasabah.moveSliders, 5000 );
        });
        
        $('ul#mainmenu li a').on('mouseover', function (){
            var $li = $(this).parent('li');
            $('#submenu ul').empty();
            if ($li.attr('data-children')){
                var children = JSON.parse($li.attr('data-children'));
                for (var i in children){
                    var s = '<li><a href="'+children[i].url+'">' + children[i].name + '</a></li>';

                    $('#submenu ul').append(s);
                }
            }
        });

        var intervalID = setInterval( Nasabah.moveSliders, 5000 );
        $('.slider-navigation .navigation-item:first-child').click();
        
        jQuery("a[rel^='prettyPhoto']").prettyPhoto({social_tools:''});
        
        Nasabah.articleShowcase();
        ImageDefaultManager.init();
    },
    moveSliders : function() {
        var max = jQuery('.slider-navigation .navigation-item').length;
        Nasabah.slideCounter++;
        if (Nasabah.slideCounter < max) {
            $('.slider-navigation .navigation-item.active').next().click();
        } else {
            Nasabah.slideCounter = 0;
            $('.slider-navigation .navigation-item:first-child').click();
        }
    },
    articleShowcase : function() {
        jQuery('.article-showcase article').on('click', function() {
            jQuery('.article-showcase article.active').removeClass('active');
            jQuery(this).addClass('active');
            var link = jQuery(this).attr('rel');
            jQuery('.article-showcase .big-article.active').removeClass('active').fadeOut('slow', function() {
                jQuery('.article-showcase .big-article[rel="'+link+'"]').fadeIn('slow');
                jQuery('.article-showcase .big-article[rel="'+link+'"]').addClass('active');
            });
        });
    }
};

$(document).ready(function(){
    Nasabah.init();
});