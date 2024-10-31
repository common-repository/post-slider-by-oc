jQuery(document).ready(function () {
    var $container = jQuery(".insta_masonry_grid");

    $container.imagesLoaded(function () {
        $container.masonry();
    });
});