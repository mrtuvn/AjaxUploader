/**
 * Magestore
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Sociallogin
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
/*jshint browser:true jquery:true*/
define([
    "jquery",
    "jquery/ui"
], function($){
    "use strict";

    $.widget('mage.popupSocials', {
        options: {
            SocialloginPopup: '#social_login_popup',
            CloseButtonPopup: '#sociallogin-close',
            ShowOtherSocialPopup: '#sociallogin-other-a-popup',
            ShowAllOtherSocial: '#sociallogin-other-button-popup'
        },
        _create: function () {
            $(this.options.ShowOtherSocialPopup).on('click', $.proxy(function () {

                if($('#sociallogin-other-a-popup').hasClass('active')){

                    $('#sociallogin-other-a-popup').removeClass('active');
                    $(this.options.ShowAllOtherSocial).hide();
                }else{

                    $('#sociallogin-other-a-popup').addClass('active');
                    $(this.options.ShowAllOtherSocial).show();
                }

            }, this));

            $(this.options.CloseButtonPopup).on('click', $.proxy(function () {
                $(this.options.SocialloginPopup).hide();
            }, this));
        }
    });

    return $.mage.popupSocials;
});