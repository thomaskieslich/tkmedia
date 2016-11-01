/* ========================================================================
 * Responsive Image
 *
 * Inspired by:
 * http://luis-almeida.github.com/unveil
 * http://verge.airve.com/
 * ======================================================================== */

+function ($) {

    // cache img.lazyload collection
    var $lazyload;

    // VIEWPORT HELPER CLASS DEFINITION
    // ================================
    var viewport;
    var ViewPort = function (options) {
        this.viewportWidth = 0;
        this.viewportHeight = 0;
        this.update();
    };

    ViewPort.prototype.viewportW = function () {
        var clientWidth = document.documentElement['clientWidth'], innerWidth = window['innerWidth'];
        return this.viewportWidth = clientWidth < innerWidth ? innerWidth : clientWidth;
    };

    ViewPort.prototype.viewportH = function () {
        var clientHeight = document.documentElement['clientHeight'], innerHeight = window['innerHeight'];
        return this.viewportHeight = clientHeight < innerHeight ? innerHeight : clientHeight;
    };

    ViewPort.prototype.inviewport = function (boundingbox) {
        return !!boundingbox && boundingbox.bottom >= 0 && boundingbox.right >= 0 && boundingbox.top <= this.viewportHeight && boundingbox.left <= this.viewportWidth;
    };

    ViewPort.prototype.update = function () {
        this.viewportH();
        this.viewportW();
    };

    // expose viewportH & viewportW methods
    $.fn.viewportH = ViewPort.prototype.viewportH;
    $.fn.viewportW = ViewPort.prototype.viewportW;

    // RESPONSIVE IMAGES CLASS DEFINITION
    // ==================================
    var ResponsiveImage = function (element, options) {
        this.$element = $(element);
        this.options = $.extend({}, ResponsiveImage.DEFAULTS, options);
        this.attrib = "src";
        this.loaded = false;

        this.checkviewport();
    };

    ResponsiveImage.DEFAULTS = {
        threshold: 0,
        hiDpi: false,
        attrib: "src",
        skip_invisible: false,
        preload: false
    };

    ResponsiveImage.prototype.checkviewport = function () {
        var source;
        var sourceWidth = this.options.sourceWidth;
        var parentWidth = this.$element.parents('.ce-media').width();
        if (!parentWidth) {
            parentWidth = this.$element.parents('figure').width();
        }
        if (this.options.hiDpi) {
            parentWidth = parentWidth * 2
        }

        $.each(this.options, function (srcWidth, value) {
            if ($.isNumeric(srcWidth) && srcWidth >= parentWidth) {
                source = value;
                if (source.length > 0) {
                    sourceWidth = srcWidth;
                    return false;
                }
            } else if ($.isNumeric(srcWidth)) {
                source = value;
                if (source.length > 0) {
                    sourceWidth = srcWidth;
                }
            }
        });
        if (this.attrib !== sourceWidth) {
            this.attrib = sourceWidth;
            this.loaded = false;
        }

        this.unveil();
    };

    ResponsiveImage.prototype.boundingbox = function () {
        var boundingbox = {},
            coords = this.$element[0].getBoundingClientRect(),
            threshold = +this.options.threshold || 0;
        boundingbox['right'] = coords['right'] + threshold;
        boundingbox['left'] = coords['left'] - threshold;
        boundingbox['bottom'] = coords['bottom'] + threshold;
        boundingbox['top'] = coords['top'] - threshold;
        return boundingbox;
    };

    ResponsiveImage.prototype.inviewport = function () {
        var boundingbox = this.boundingbox();
        return viewport.inviewport(boundingbox);
    };

    ResponsiveImage.prototype.unveil = function () {
        if (this.loaded || !this.options.preload && this.options.skip_invisible && this.$element.is(":hidden")) return;
        var inview = this.options.preload || this.inviewport();
        if (inview) {
            var source = this.options[this.attrib] || this.options["src"];
            if (source) {
                this.$element.attr("width", this.attrib);
                if (this.options.hiDpi) {
                    this.$element.attr("width", this.attrib * 0.5)
                }
                this.$element.attr("src", source);

                //this.$element.css("opacity", 1);
                $(window).trigger('loaded.jquery.responsiveimage');
                this.loaded = true;
            }
        }


    };

    // RESPONSIVE IMAGES PLUGIN DEFINITION
    // ===================================
    function Plugin(option) {
        $lazyload = this;
        var count = 0;
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('jquery.responsiveimage');
            var options = typeof option === 'object' && option;

            if (!data) {
                if (!viewport) viewport = new ViewPort();

                if (options && options.breakpoints) options.breakpoints = null;
                options = $.extend({}, $this.data(), options);

                $this.data('jquery.responsiveimage', (data = new ResponsiveImage(this, options)));

                count++;
                if (count == $lazyload.length) {
                    setTimeout('window.scrollBy(0,1)', 3);
                }
            }
            if (typeof option === 'string') data[option]();
        });
    }

    $.fn.responsiveimage = Plugin;
    $.fn.responsiveimage.Constructor = ResponsiveImage;

    // RESPONSIVE IMAGES API
    // =====================
    $(window).on('load.jquery.responsiveimage', function () {
        $('img.lazyload').responsiveimage({
            threshold: 280,
            hiDpi: window.devicePixelRatio >= 2,
            preload: false,
            skip_invisible: true
        });

        // EVENTS
        // ======
        $(window)
            .on('scroll.jquery.responsiveimage', function () {
                $lazyload.responsiveimage('unveil');
            })
            .on('resize.jquery.responsiveimage', function () {
                if (viewport) viewport.update();
                $lazyload.responsiveimage('checkviewport');
            });
    });

}(jQuery);
