"use strict";
/****************************
*  SOME COMMON SVG CONSTANT *
****************************/
var SVG_LOADER = '<svg viewBox="0 0 20 20" class="Polaris-Spinner Polaris-Spinner--colorInkLightest Polaris-Spinner--sizeSmall" aria-label="Loading" role="status"><path d="M7.229 1.173a9.25 9.25 0 1 0 11.655 11.412 1.25 1.25 0 1 0-2.4-.698 6.75 6.75 0 1 1-8.506-8.329 1.25 1.25 0 1 0-.75-2.385z"></path></svg>';
/****************************
 *  SOME COMMON SVG CONSTANT*
 ****************************/

/*
 * 
 * @param string $message
 * @returns {undefined} show flash message
 */
function flashNotice($message, $class) {
    $class = ($class != undefined) ? $class : '';
    var flashMsgHtml = '<div class="inline-flash-wrapper animated bounceInUp inline-flash-wrapper--is-visible ourFlashMsg"><div class="inline-flash ' + $class + '  "><p class="inline-flash__message">' + $message + '</p></div></div>';
    if ($('.ourFlashMsg').length) {
        $('.ourFlashMsg').remove();
    }
    $("body").append(flashMsgHtml);
    setTimeout(function () {
        if ($('.ourFlashMsg').length) {
            $('.ourFlashMsg').remove();
        }
    }, 3000);
}

/*
 * @param {string} $className
 * @returns {undefined} show loader
 */
function loading_show($selector) {
    $($selector).addClass("Polaris-Button--loading").html('<span class="Polaris-Button__Content"><span class="Polaris-Button__Spinner">' + SVG_LOADER + '</span><span>Loading</span></span>').fadeIn('fast').attr('disabled', 'disabled');
}

/**
 * @param {string} $className
 * @param {string} $buttonName
 * @returns {undefined} hide loader
 */
function loading_hide($selector, $buttonName, $buttonIcon) {
    if ($buttonIcon != undefined) {
        $buttonIcon = '<span class="Polaris-Button__Icon"><span class="Polaris-Icon">' + $buttonIcon + '</span></span>'
    } else {
        $buttonIcon = '';
    }

    $($selector).removeClass("Polaris-Button--loading").html('<span class="Polaris-Button__Content">' + $buttonIcon + '<span>' + $buttonName + '</span></span>').removeAttr("disabled");
}

$(document).on('click', '.close-message', function () {
    $('.remove-sucees-message').hide();
});



function removeCode(thisObj, data_key) {
    var current = $(thisObj);
    var btnText = current.html();
    loading_show(current);
    var deleteAjax = function deleteAjax(){
        loading_show(thisObj);
        $.ajax({
            url: "ajax_actions.php",
            type: "post",
            dataType: "json",
            data: {method_name: 'remove_code', data_key: data_key, shop: shop},
            success: function (response) {
                if (response['result'] == 'success') {
                    flashNotice(response['msg']);
                    $('.remove-sucees-message').show();
                    $('.remove-sucees-message').css({'display': 'flex'});
                }
                loading_hide(current, btnText);
            },
            error: function () {
                flashNotice('Please try again!','error');
            }
        });
    }
    
    if(mode == 'live'){
        ShopifyApp.Modal.confirm({
            title: 'Uninstall',
            message: 'Are you sure you want to remove? This action cannot be reversed.',
            okButton: 'Delete',
            cancelButton: 'Cancel',
            style: "danger"
        }, function (result) {
            if (result) {
                $('.ui-button.close-modal.btn-destroy-no-hover').addClass("ui-button ui-button--destructive js-btn-loadable is-loading disabled");
                deleteAjax();
            }
        });
    }else{
        var r = confirm('Are you sure you want to remove? This action cannot be reversed.');
        if (r == true) {
            deleteAjax();
        }
    }
}

function toggleCheckedVal(switchval,userdomain,useremail,datakey){
        $.ajax({
            url: "ajax_actions.php",
            type: "post",
            dataType: "json",
            data: {method_name: 'change_appStatus', data_status: switchval, user_name:userdomain, user_email:useremail, data_key:datakey, shop: shop},
            beforeSend: function(){
                $('#myonoffswitch').prop('disabled', true);
            },
            complete: function(){
                $('#myonoffswitch').prop('disabled', false);
            },
            success: function (response) {
                if (response['result'] == 'success') {
                     $(".enable-banner").html(response['msg']); 
                     $('#user_key').val(response['key']);
                }
            },
            error: function () {
                flashNotice('Please try again!','error');
            }
        });

}

