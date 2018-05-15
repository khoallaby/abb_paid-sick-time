<?php
namespace ABetterBalance\Plugin;
global $post;
?>
<h2>Overview of Paid Sick Time Laws in the United States:<br /><?php echo $post->post_title; ?></h2>

<p class="updated"><em>Updated on <?php the_modified_date('F j, Y'); ?></em></p>

<?php foreach( $locations as $location ) { ?>
<div class="pstl-table-container" style="page-break-after: always;">
    <table class="pstl-table">
        <thead>
        <tr>
            <td></td>
            <td class="pst-title"><?php echo get_the_title( $location ); ?></td>
        </tr>
        </thead>
        <tbody>
        <?php foreach( $questions as $questionID => $question ) { ?>
            <tr class="question-<?php echo $questionID; ?>">
                <td><?php echo $question; ?></td>
                <?php
                # if we're expecting a multi dimensional array from these pages
                if( (is_singular( PaidSickTime::$cptName ) && is_single('search')) || is_post_type_archive( PaidSickTime::$cptName ) ) {
                    $answer = isset($answers[ $location ][ $questionID ]) ? $answers[ $location ][ $questionID ] : '';
                } else {
                    $answer = isset($answers[ $questionID ]) ? $answers[ $questionID ] : '';
                }
                ?>
                <td class="answer-<?php echo $questionID; ?>"><?php echo $answer; ?></td>
                </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php } ?>