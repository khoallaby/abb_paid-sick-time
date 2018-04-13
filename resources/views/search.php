<?php
namespace ABetterBalance\Plugin;
global $post;

$questions = get_option( PaidSickTime::$questionsOptionName );
#$answers = Answers::getAnswers( $post->ID );
?>
<h2>
Overview of Paid Sick Time Laws in the United States:<br />
Custom Filtering
</h2>

<p>To view a custom chart of data, please use the ﬁlter(s) below and click “Submit”.</p>

<p><strong>Note: You must check at least one box under Issues and at least one box under location.</strong></p>




<form name="pstl-search" method="post" action="" class="pstl-search">
    <ul>
    <?php foreach ( (array) $questions as $k => $question ) : ?>
        <li class="question">
            <label>
                <input type="checkbox" name="questions[]" value="<?php echo $k; ?>" /><?php echo nl2br($question); ?>
            </label>
        </li>
    <?php endforeach; ?>
    </ul>
    <p>
        <input type="submit" name="Submit" class="" value="<?php esc_attr_e('Submit') ?>" />
    </p>
</form>
