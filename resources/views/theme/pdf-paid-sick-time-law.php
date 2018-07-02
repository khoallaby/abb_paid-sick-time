<?php
namespace ABetterBalance\Plugin;
global $post;
?>
<h2>Overview of Paid Sick Time Laws in the United States:<br /><?php echo $post->post_title; ?></h2>

<p class="updated"><em>Updated on <?php the_modified_date('F j, Y'); ?></em></p>

<?php
foreach( array_chunk($locations, 4) as $chunk ) {
	$style = $location === end($locations) ? '' : 'page-break-after: always;';
?>
<div class="pstl-table-container pstl-pdf" style="<?php echo $style; ?>">
    <table class="pstl-table" autosize="1">
        <thead>
        <tr>
            <td></td>
            <?php foreach( $chunk as $location ) { ?>
            <td class="location-title"><?php echo get_the_title( $location ); ?></td>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach( $questions as $questionID => $question ) { ?>
            <tr class="question-<?php echo $questionID; ?>">
                <td class="question"><?php echo $question; ?></td>
	            <?php
	            foreach( $chunk as $k => $location ) :
                    # if we're expecting a multi dimensional array from these pages
                    if( (is_singular( PaidSickTime::$cptName ) && is_single('search')) || is_post_type_archive( PaidSickTime::$cptName ) ) {
                        $answer = isset($answers[ $location ][ $questionID ]) ? $answers[ $location ][ $questionID ] : '';
                    } else {
                        $answer = isset($answers[ $questionID ]) ? $answers[ $questionID ] : '';
                    }
                    ?>
                    <td class="answer answer-<?php echo $questionID; ?>"><?php echo $answer; ?></td>
                    <?php endforeach; ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php } ?>