<?php
namespace ABetterBalance\Plugin;


$PSTs = PaidSickTime::getPSTsByLocation();
?>
<div class="flex-row">
    <div class="col-2-3">
        <h1>Overview of Paid Sick Time Laws in the United States</h1>
        <p>In the United States, 8 states, 29 cities, 2 counties, and Washington D.C. have paid sick time laws on the books. This special section of the website provides an overview and comparison of these 40 laws</p>
    </div>
    <div class="col-1-3">
        <form>
            <input type="submit" class="btn" value="<?php esc_attr_e('Export page as PDF') ?>" />
        </form>
    </div>
</div>

<h2>Click on a state, city or county to see their Paid Sick Time Laws</h2>



<div class="flex-row">
    <?php
    $locations = [
        'state' => 'States',
        #'city' => 'Cities',
        #'county' => 'Counties'
        'cali' => 'California Cities',
        'non-cali' => 'Other Counties & Cities (Outside California)'
    ];

    foreach( $locations as $slug => $title ) {
        ?>
        <div class="col-1-3 location-<?php echo $slug; ?>">
            <?php if( isset($PSTs[$slug]) ) : ?>
                <h4><?php echo $title; ?></h4>
                <ul>
                    <?php foreach ( $PSTs[$slug] as $location ) : ?>
                        <li class="location">
                            <label>
                                <input type="checkbox" name="locations[]" value="<?php echo $location->ID; ?>" />
                                <span><?php echo $location->post_title; ?></span>
                            </label>
                        </li>
                    <?php endforeach; ?>
                    <li class="location">
                        <label>
                            <input type="checkbox" class="location-all" />
                            <span><em>All the above</em></span>
                        </label>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    <?php } ?>
</div>