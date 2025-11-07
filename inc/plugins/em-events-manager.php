<?php
add_filter('em_event_output_single', function ($format, $object, $target) {

ob_start();

$spots_available = $object->get_bookings()->get_available_spaces();


$spots_open_tagline = '';

if ($spots_available > 1) {
    $spots_open_tagline = '<div class="em-item-meta-line em-spots-open"><span class="em-icon-checkmark em-icon"></span>'. $spots_available . ' spots available</div>';
} else if ($spots_available === 1) {
    $spots_open_tagline = '<div class="em-item-meta-line em-spots-open"><span class="em-icon-checkmark em-icon"></span>'. $spots_available . ' spot available</div>';
}

?>
<div class="em-item-meta">
    <section class="em-item-meta-column">
        <h1 class="em-item-title"><?php the_title();?></h1>
        <section class="em-event-when">
            <div class="em-item-meta-line em-event-date em-event-meta-datetime">
                <span class="em-icon-calendar em-icon"></span>
                #_EVENTDATES    
            </div>
            <div class="em-item-meta-line em-event-time em-event-meta-datetime">
                <span class="em-icon-clock em-icon"></span>
                #_EVENTTIMES
            </div>
            
            {has_location_venue}
            <section class="em-event-where">
                <br/>
                <div class="em-item-meta-line em-event-location">
                    <span class="em-icon-location em-icon"></span>
                    <div>
                        #_LOCATIONLINK<br>
                        #_LOCATIONFULLLINE
                    </div>
                </div>
            </section>
            {/has_location_venue}
            {has_event_location}
            <section class="em-event-where">
                <br/>
                <div class="em-item-meta-line em-event-location">
                    <span class="em-icon-at em-icon"></span>
                    #_EVENTLOCATION
                </div>
            </section>
            {/has_event_location}
            
            {has_taxonomy}
            <section class="em-item-taxonomies">
                <h3>Event Type</h3>
                {has_category}
                <div class="em-item-meta-line em-item-taxonomy em-event-categories">
                    <span class="em-icon-category em-icon"></span>
                    <div>#_EVENTCATEGORIES</div>
                </div>
                {/has_category}
                {has_tag}
                <div class="em-item-meta-line em-item-taxonomy em-event-tags">
                    <span class="em-icon-tag em-icon"></span>
                    <div>#_EVENTTAGS</div>
                </div>
                {/has_tag}
            </section>
            {/has_taxonomy}

            #_EVENTADDTOCALENDAR
        </section>

        {is_past}
        <em>
        <p>
            This event has already taken place.
        </p>
        </em>
        {/is_past}

        {is_current}
        <em>
        <div class="em-item-meta-line">
            This event is currently taking place.
        </div>
        </em>
        {/is_current}

        <?php
        
        $third_party_link = get_post_meta(get_the_id(), 'third_party_link', true);
        $third_party_price = get_post_meta(get_the_id(), 'third_party_price', true);
        $third_party_event_info = get_post_meta(get_the_id(), 'third_party_event_info', true);

        if ($third_party_link) {

            if ($third_party_event_info) : ?>
                <p><?php echo $third_party_event_info; ?></p>
            <?php endif;

            if ($third_party_price) : ?>
                <div class="em-item-meta-line em-event-prices">
                    <span class="em-icon-ticket em-icon"></span>
                    <?php echo $third_party_price; ?>
                </div>
                <br/>
            <?php endif; ?>
    
            <a class="button" href="<?php echo esc_url($third_party_link['url']); ?>" target="<?php echo $third_party_link['target']; ?>">
                <?php echo $third_party_link['title']; ?>
            </a>
        <?php } else { ?>

            {has_bookings}

                {bookings_closed}
                    {is_future}
                    <p> The event is fully booked.</p>
                    {/is_future}
                {/bookings_closed}

                {bookings_open}

                    <section class="em-event-bookings-meta">


                        <h2>Reserve a Spot</h2>
                        <?php echo $spots_open_tagline;?>
                        {not_free}
                        <div class="em-item-meta-line em-event-prices">
                            <span class="em-icon-ticket em-icon"></span>
                            #_EVENTPRICERANGE
                        </div>
                        {/not_free}

                        <button data-a11y-dialog-show="sbtl_em_booking" type="button">Sign Up</button>
                        <!---
                        <a href="#em-event-booking-form" class="button input with-icon-right">
                                                <span class="em-icon-ticket em-icon"></span>
                        </a>-->


                    </section>
                {/bookings_open}
            {/has_bookings}

        <?php } ?>
    </section>
</div>
<section class="em-event-content">
    #_EVENTNOTES

    <div role="dialog" class="dialog-container" id="em-sbtl-booking-form" aria-labelledby="sbtl_booking_form_title" aria-hidden="true" data-a11y-dialog="sbtl_em_booking">
        <div data-a11y-dialog-hide class="dialog-overlay"></div>
        
        <div role="document" class="em-event-bookings">
            <button aria-label="Close booking form" data-a11y-dialog-hide class="dialog-close">⨯</button>
                <a name="em-event-booking-form"></a>
                <h2 id="sbtl_booking_form_title">Reserve a spot for #_EVENTNAME</h2>
                #_BOOKINGFORM
            </div>
        </div>
</section>

<?php

$format = ob_get_clean();

return $object->output($format, $target);

}, 10, 3);

add_filter('em_event_output_placeholder','sbtl_em_placeholder_mod',1,3);
function sbtl_em_placeholder_mod($replace, $EM_Location, $result){

if ( $result == '#_EVENTADDTOCALENDAR' ) {
    
    $rand_id = rand();

    //Create a var that replaces the first <button> tag with a new <button> tag
    $newBtn = '<button type="button" class="accordion" aria-expanded="false" aria-controls="em-event-add-to-colendar-content-'.$rand_id.'">Add To Calendar '.sbtl_caret_svg().'</button>';
    //Get regex 
    $regex = '/<button.*?>(.*?)<\/button>/';
    //Replace the first <button> tag with the new <button> tag
    $replace = preg_replace($regex, $newBtn, $replace, 1);

    $newOpenDiv = '<nav class="accordion-body em-event-add-to-calendar-content" aria-label="Add to Calendar"><div id="em-event-add-to-colendar-content-'.$rand_id.'">';
    //get regex of first opening div tag
    $divRegex = '/<div.*?>/';
    //Replace the first <div> tag with the new <div> tag
    $replace = preg_replace($divRegex, $newOpenDiv, $replace, 1) . '</nav>';

}

if ( $result == '#_BOOKINGFORM' ) { 

    //Replace all strings that contain em-booking-section-title with nothing
    $replace = preg_replace('/class="em-booking-section-title"/', '', $replace);

    //Replace all strings that contain em-booking-form with nothing
    $replace = preg_replace('/class="em-booking-form"/', '', $replace);
}
return $replace;
}

function sbtl_em_event_list_item() {
ob_start(); ?>

<div class="em-event em-item" style="--default-border:#_CATEGORYCOLOR;">
    <div class="em-item-info">
        <h2 class="em-item-title"><a class="em-listing-link" href="#_EVENTURL">#_EVENTNAME</a></h2>
        <div class="em-event-meta em-item-meta">
            <div class="em-item-meta-line em-event-date em-event-meta-datetime">
                <span class="em-icon-calendar em-icon"></span>
                #_EVENTDATES    
            </div>
            <div class="em-item-meta-line em-event-time em-event-meta-datetime">
                <span class="em-icon-clock em-icon"></span>
                #_EVENTTIMES
            </div>
            {has_location_venue}
            <div class="em-item-meta-line em-event-location">
                <span class="em-icon-location em-icon"></span>
                #_LOCATIONNAME
            </div>
            {/has_location_venue}
            {has_event_location}
            <div class="em-item-meta-line em-event-location">
                <span class="em-icon-at em-icon"></span>
                #_EVENTLOCATION
            </div>
            {/has_event_location}
            {has_category}
            <div class="em-item-meta-line em-item-taxonomy em-event-categories">
                <span class="em-icon-category em-icon"></span>
                <div>#_EVENTCATEGORIES</div>
            </div>
            {/has_category}
            {has_tag}
            <div class="em-item-meta-line em-item-taxonomy em-event-tags">
                <span class="em-icon-tag em-icon"></span>
                <div>#_EVENTTAGS</div>
            </div>
            {/has_tag}
        </div>
        <div class="em-item-desc">
            #_EVENTEXCERPT{25}
        </div>
        <br/>
        
        <div class="em-item-actions input">
            <a class="read-more em-listing-link" tabindex="-1" href="#_EVENTURL">More Info <span class="cta">&#x2192;</span></a>
        </div>
    </div>
    <div class="em-item-image {is_past}is-past{/is_past} {no_image}has-placeholder{/no_image}">
        {has_image}
        <a class="em-listing-link img-link" tabindex="-1" href="#_EVENTURL">
            #_EVENTIMAGE{medium}
        </a>
        <span class="datestamp">
            <span class="month">#M</span>
            <span class="day">#d</span>
            <span class="time">#_12HSTARTTIME</span>
        </span>
        {/has_image}
        {no_image}
        <div class="em-item-image-placeholder">
            <div class="date">
                <span class="day">#d</span>
                <span class="month">#M</span>
            </div>
        </div>
        {/no_image}
    </div>
</div>
<?php
$format = ob_get_clean();
return $format;
}

add_filter('em_event_list_item_format', 'sbtl_em_event_list_item', 10, 2);

add_filter('em_events_output_args', function($args) {

$args['format'] = sbtl_em_event_list_item();
return $args;

}, 10, 2);

function sbtl_em_past_events_shortcode() {

$past_event_args = array(
    'post_type' => 'event',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'meta_query' => array(
        array (
            'key' => '_event_start_date',
            'value' => date('Y-m-d'),
            'compare' => '<',
            'type' => 'DATE'
        )
    ),
    'order' => 'DESC',
    'orderby' => 'meta_value',
);

$past_events = new WP_Query($past_event_args);


if ($past_events->have_posts())  {
    

    ob_start(); ?>

    <div class="em em-view-container">
        <div class="em em-list em-events-list size-large">
        <?php

            while ($past_events->have_posts()) : $past_events->the_post();
                
                $past_event = em_get_event(get_the_id(), 'post_id');

                echo $past_event->output(sbtl_em_event_list_item(), 'html');
            
            endwhile;
            wp_reset_postdata();


?>
        </div>
    </div>
    <?php 		
    return ob_get_clean();
} else {
    return '<p>No past events found.</p>';	
}
}

add_shortcode('sbtl_past_events_listing', 'sbtl_em_past_events_shortcode');

add_filter('em_content_events_args', function($args) {

ob_start(); 

block_template_part('no-upcoming-events');

$args['no_results_msg'] = ob_get_clean();

return $args;

}, 10);