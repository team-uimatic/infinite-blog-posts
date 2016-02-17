(function ($, window, document, undefined) {

    __log = function () {
        //modify logger
        //in progress
//        console;
    }

    var $window = $(window);
    var $infDefaultCont = "wp-inf-cont"; // this div is needed if we are loading enitre "body" tag from posts
    var $infCommonCont = "inf-post-cont"; // attach common container to repeating sections
    var $infCommonClass = "inf-post-common"; // attach common class to repeating sections
    var $infUniqClass = "inf-post-id-"; // unique classes for identfication

    init = function () {

        if (typeof (inf_settings) == 'undefined') {
            console.warn('Could not load Infinite posts');
        }
        else {
            inf_defaults = $.extend(true, {'scrollSensitivity': 50}, inf_settings);

            // if no container has been assigned, specify own container just inside body and put all content inside of it
            if (typeof (inf_defaults.container) == 'undefined') {
                console.warn("Default container for infinite posts is not defined");
                inf_defaults.container = '.' + $infDefaultCont;
                $('body').wrapInner('<div class="' + $infDefaultCont + '" />');
            }

            // add default classes and href to FIRST div as it will not have these values
            $(inf_defaults.container).addClass($infCommonClass + " " + $infUniqClass + inf_defaults.parent_ID + " " + $infCommonCont);
            $(inf_defaults.container).attr('data-href', inf_defaults.parent_URL);

            // immediately check and load next div to give smooth scroll into the next post
            loadNext();
        }
    }

    // load subsequent divs
    loadNext = function () {

        // get ID and href of next post from hidden field
        __uiNxtPst_URL = $('.inf-next-post:last').data('url');
        __uiNxtPst_ID = $('.inf-next-post:last').data('id');
        console.info("Attempting Loading next post");

        if (typeof (__uiNxtPst_URL) == 'undefined' || __uiNxtPst_URL == "" || $("." + $infUniqClass + __uiNxtPst_ID).length > 0)
            return;

        // if all looks good then fetch the content and append
        fetchAppend();
    }

    fetchAppend = function () {

        //place target div first and then insert data at the end of LAST repeating div
        var $tmp = $('<div/>', {id: $infUniqClass + __uiNxtPst_ID});  // id  =  unique-class-90
        var $lastInfPost = $('.' + $infCommonCont + ":last");
        $tmp.addClass($infCommonCont).insertAfter($lastInfPost);

        // optimize URL for "jquery-load" function by adding the target div into the URL
        // if the container is default it means we are loading entire 'body' via ajax call and no need to add target div
        var $loadAddress = __uiNxtPst_URL;

        if ("." + $infDefaultCont != inf_defaults.container)
            $loadAddress += " " + inf_defaults.container; // http://address.com .targetDivClass [mind the space after address]

        var $tmpVarURL = __uiNxtPst_URL;
        console.log(inf_defaults)
        console.info("Loading " + $loadAddress + "  " + "." + $infDefaultCont + "-- " + inf_defaults.container);
        $("#" + $infUniqClass + __uiNxtPst_ID).load($loadAddress, function () {
            //now the data has been loaded, we add our classes and additonal data to the elements
            $(this).children("div:first").attr('data-href', $tmpVarURL).addClass($infCommonClass + " " + $infUniqClass + __uiNxtPst_ID);
        });
    }
    switchURL = function (URL) {
        window.history.pushState("", "", URL);
    }

    var lastScrollTop = 0;
    $window.scroll(function (event) {

        var dir, screenTop = $(this).scrollTop();
        if (screenTop > lastScrollTop)
            dir = "down";
        else
            dir = "up";
        lastScrollTop = screenTop;

        // handle url change on scroll 
        var $infPages = $('.' + $infCommonClass);
        $infPages.each(function (index, value) {
            var itemOffsetTop = Math.abs($(this).offset().top);
            var height = $(this).height();
            var $currURL = $(this).attr('data-href');
//            console.log(screenTop + " " + itemOffsetTop + " " + $currURL)
            // change url to next post when going down

            if (dir == "down" &&
                    (screenTop - itemOffsetTop < inf_defaults.scrollSensitivity && screenTop - itemOffsetTop > (-inf_defaults.scrollSensitivity))) {
                var URLBefore = window.location.href;
                switchURL($currURL);
                var URLAfter = window.location.href;

                console.info("At this time we are advancing to the next post so let's load the next post");
                if (URLBefore != URLAfter)
                    loadNext();
            }

            // change url to previous post when going up
            if ((dir == "up") &&
                    ((screenTop - itemOffsetTop - height) < inf_defaults.scrollSensitivity && (screenTop - itemOffsetTop - height) > 0)) {
                switchURL($currURL);
            }
        });
    });
    // wait till window load to for settings to take affect
    $(window).load(init);
})(jQuery, window, document);