    define(
    [
        'jquery',
        'uiComponent'
    ],
    function ($, Component) {
        'use strict';

        $(document).ready(function(){
            alert('Test JS');
        });
        return Component.extend({});
    });