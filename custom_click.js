require(['jquery', 'jquery/ui'], function($) {
    $('.nav-1 > a,.nav-4 > a,.nav-6 > a,.nav-7 > a').attr("href", "#");
    $('.lum-static-content').parents('.right-side-wrap').siblings('.toolbar-container,.products,.tollbar-bottom').css('display', 'none');
    if (window.location.href.indexOf("?") != -1) {
        $('.right-side-wrap').addClass('showclass');
        $('.showclass').siblings('.toolbar-container,.products,.tollbar-bottom').css('display', 'block');
    }
    if (window.location.href.indexOf("aspx") > -1) {
        var name = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
        var variable3 = name.substring(0, 3);
        $("#narrow-by-list").find(".filter-options-item .filter-options-title").each(function() {
            var title = $(this).html();
            if (title == "Manufacturer") {
                var condition = $(this).siblings('.filter-options-content').find('li.item');
                condition.each(function() {
                    condition.css('display', 'none');
                    var manu = $(this).children('a').text();
                    var variable2 = manu.replace(/\s/g, '').substring(0, 3).toLowerCase();
                    if (variable2 != variable3) {
                        condition.css('display', 'block');
                    }
                    if (variable2 == variable3) {
                        $(this).css('display', 'block');
                        return false;
                    }
                });
            }
        });
    }
	$("#narrow-by-list").find(".filter-options-item .filter-options-title").each(function() {
    var title = $(this).html();
    if (title == "Colors") {
    $(".filter-options-content").css("display","block");
    }
});
});