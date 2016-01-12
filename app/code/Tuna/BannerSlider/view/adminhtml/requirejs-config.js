var config = {
    map: {
        '*': {
            'tuna/note': 'Tuna_BannerSlider/js/jquery/slider/jquery-ads-note',
        },
    },
    paths: {
        'tuna/flexslider': 'Tuna_BannerSlider/js/jquery/slider/jquery-flexslider-min',
        'tuna/evolutionslider': 'Tuna_BannerSlider/js/jquery/slider/jquery-slider-min',
        'tuna/zebra-tooltips': 'Tuna_BannerSlider/js/jquery/ui/zebra-tooltips',
    },
    shim: {
        'tuna/flexslider': {
            deps: ['jquery']
        },
        'tuna/evolutionslider': {
            deps: ['jquery']
        },
        'tuna/zebra-tooltips': {
            deps: ['jquery']
        },
    }
};
