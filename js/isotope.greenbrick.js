jQuery(document).ready(function($){
    $isotope = $('#projects-grid').isotope({
        percentPosition: true,
        itemSelector: '.grid-item',
    });

        $('.grid-filter').on('click',function(el){
            $filterTargets = $(this).data('filter');
            $isotope.isotope({
                filter: $filterTargets
            });
            $('.grid-filter.is-checked').removeClass('is-checked');
            $(this).addClass( 'is-checked' );
        })
        
});