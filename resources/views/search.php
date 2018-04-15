<?php
namespace ABetterBalance\Plugin;
global $post;

$questions = get_option( PaidSickTime::$questionsOptionName );
#$answers = Answers::getAnswers( $post->ID );
$locations = []; #Locations:getLocations();
$PSTs = PaidSickTime::getPSTs( true );

?>
<h2>Overview of Paid Sick Time Laws in the United States:<br />Custom Filtering</h2>

<p>To view a custom chart of data, please use the ﬁlter(s) below and click “Submit”.</p>

<p><strong>Note: You must check at least one box under Issues and at least one box under location.</strong></p>




<form name="pstl-search" method="post" action="" class="flex-row pstl-search">
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

            <div class="location-states">
                <?php if( isset($PSTs['state']) ) : ?>
                <h4>States</h4>
                <ul>
                    <?php foreach ( $PSTs['state'] as $state ) : ?>
                        <li class="location">
                            <label>
                                <input type="checkbox" name="locations[]" value="<?php echo $state->ID; ?>" />
                                <span><?php echo $state->post_title; ?></span>
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


    </div>
    <p>
        <input type="submit" name="Submit" class="" value="<?php esc_attr_e('Submit') ?>" />
    </p>
</form>
