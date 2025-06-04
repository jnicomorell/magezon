/** 
 * DATE: 2023-06-23
 * AUTHOR: William Gomez
 * DESCRIPTION: Changed slider element instance name to sliderOwl
 * Changes made to lines 11 and 45
*/
var config = {
    paths: {
        mgzNumberCounter: 'Magezon_PageBuilder/js/number-counter',
        mgzFotorama: 'Magezon_PageBuilder/vendor/fotorama/fotorama',
        mgzSlider: 'Magezon_PageBuilder/js/sliderOwl',
        mgzOwlSlider: 'Magezon_Builder/js/carousel'
    },
    shim: {
        'Magezon_PageBuilder/vendor/fotorama/fotorama': {
            deps: ['jquery']
        },
        'mgzOwlSlider': {
            deps: ['jquery']
        },
        'mgzSlider': {
            deps: ['jquery']
        },
        'Magezon_Builder/js/carousel': {
            deps: ['jquery']
        },
        'Magezon_PageBuilder/js/common': {
            deps: ['jquery']
        },
        'Magezon_PageBuilder/js/flickr': {
            deps: ['jquery']
        },
        'Magezon_PageBuilder/js/gallery': {
            deps: ['jquery']
        },
        'Magezon_PageBuilder/js/instagram': {
            deps: ['jquery']
        },
        'Magezon_PageBuilder/js/number-counter': {
            deps: ['jquery']
        },
        'Magezon_PageBuilder/js/photoswipe': {
            deps: ['jquery']
        },
        'Magezon_PageBuilder/js/sliderOwl': {
            deps: ['jquery']
        }
    }
};