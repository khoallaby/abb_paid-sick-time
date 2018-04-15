<?php
namespace ABetterBalance\Plugin;
global $post;

$questions = get_option( PaidSickTime::$questionsOptionName );
$PSTs = PaidSickTime::getPSTs( true );
?>

<h2>Overview of Paid Sick Time Laws in the United States:<br />Custom Filtering</h2>

<p>To view a custom chart of data, please use the ﬁlter(s) below and click “Submit”.</p>

<p><strong>Note: You must check at least one box under Issues and at least one box under location.</strong></p>


<form name="pstl-search" method="get" action="" class="flex-row pstl-search">
    <div class="col-1-2 issues-container">
        <h3>Issues</h3>
        <ul>
            <?php foreach ( (array) $questions as $k => $question ) : ?>
                <li class="question">
                    <label>
                        <input type="checkbox" name="questions[]" value="<?php echo $k; ?>" />
                        <span><?php echo nl2br($question); ?></span>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="col-1-2 locations-container">
        <h3>Locations</h3>
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
            <div class="location-<?php echo $slug; ?>">
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
    <p>
        <input type="submit" class="" value="<?php esc_attr_e('Submit') ?>" />
    </p>
</form>
