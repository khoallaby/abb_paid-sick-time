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
        <p class="alignright">
            <a href="?export" class="btn" target="_blank"><?php esc_attr_e('Export page as PDF') ?></a>
        </p>
    </div>
</div>

<h2>Click on a state, city or county to see their Paid Sick Time Laws</h2>



<div class="flex-row">
    <?php
    $locations = Locations::getLocationSlugTitles();

    foreach( $locations as $slug => $title ) {
        ?>
        <div class="col-1-3 location-<?php echo $slug; ?>">
            <?php if( isset($PSTs[$slug]) ) : ?>
                <h4><?php echo $title; ?></h4>
                <ul>
                <?php foreach ( $PSTs[$slug] as $pst ) : ?>
                    <li class="location">
                        <?php echo sprintf( '<a href="%s">%s</a>', get_permalink( $pst->ID ), $pst->post_title ); ?>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php } ?>
</div>