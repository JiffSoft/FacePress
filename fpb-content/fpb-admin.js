/**
 * fpb-admin.js
 * FacePress admin JavaScript functions
 */

$('#fpb-admin-popup').hide();

fpb_admin_ResetResizePopup = function(options, callback) {
    // reset the div
    $('#fpb-admin-popup').css("left","0px");
    $('#fpb-admin-popup').css("top","25px");
    $('#fpb-admin-popup').css("height","0px");
    $('#fpb-admin-popup').css("width","0px");
    $('#fpb-admin-popup').css("opacity","0");
    $('#fpb-admin-popup-content').html('<div class="loading-img"><p>Loading...</p></div>');
    $('#fpb-admin-popup-content').css('opacity','1');
    $('#fpb-admin-popup').show();
    // and resize
    setTimeout(function() {
        $("#fpb-admin-popup").animate({opacity: 1, left: (($(window).width() - options.w) / 2),
                top: (($(window).height() - options.h) / 2), height: options.h + "px", width: options.w + "px"},"slow",null,callback);
    },0.5);
    setTimeout(function() {
        $("#fpb-admin-blackout").show().animate({opacity: 0.5, display: 'block'},"slow");
    },0.5);
    $('#fpb-admin-blackout').click(function() {
        fpb_admin_ClosePopup();
    });
};

fpb_admin_ClosePopup = function() {
    $('#fpb-admin-blackout').click(function() { /* (.Y.) */ });
    setTimeout(function() {
        $('#fpb-admin-popup').hide('explode');
    },0.5);
    setTimeout(function() {
        $('#fpb-admin-blackout').animate({opacity: 0}, 'slow', null, function() {
            $('#fpb-admin-blackout').hide();
        })
    },0.5);
};

fpb_admin_LoadPopupContents = function(a, o) {
    var d = '';
    if (o != null)
        d = o.serialize();
    $.ajax({
        url: '/fpb-admin/index.php',
        type: 'POST',
        data: 'action=' + a + d,
        success: function(msg) {
            if (msg.match(/^HTTP\/[0-9\.]+\s403/gi)) /* reload if unauthorized */
                window.location.reload();
            else
                $('#fpb-admin-popup-content').animate({opacity: 0}, 'fast', null, function() {
                    $('#fpb-admin-popup-content').html(msg);
                    $('#fpb-admin-popup-content').animate({opacity: 1}, 'fast');
                });
        }
    });
};

fpb_admin_Dashboard = function() {
    var targets = {h: 400, w: 450};
    fpb_admin_ResetResizePopup(targets, fpb_admin_Dashboard_Show);
};

fpb_admin_Dashboard_Show = function() {
    /* load the dashboard  */
    fpb_admin_LoadPopupContents('dashboard');
};

fpb_admin_Posts = function() {
    var targets = {h: 500, w: 700};
    fpb_admin_ResetResizePopup(targets, fpb_admin_Posts_Show);
};

fpb_admin_Posts_Show = function() {
    /* load the posts menu */
    fpb_admin_LoadPopupContents('posts');
};

fpb_admin_Plugins = function() {
    var targets = {h: 500, w: 700};
    fpb_admin_ResetResizePopup(targets, fpb_admin_Plugins_Show);
};

fpb_admin_Plugins_Show = function() {
    /* load the plugins menu */
    fpb_admin_LoadPopupContents('plugins');
}