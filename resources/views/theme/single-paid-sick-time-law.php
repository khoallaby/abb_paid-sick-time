<?php
namespace ABetterBalance\Plugin;
global $post;

$questions = get_option( PaidSickTime::$questionsOptionName );
$answers = Answers::getAnswers( $post->ID );
?>
<h2>Overview of Paid Sick Time Laws in the United States:<br /><?php echo $post->post_title; ?></h2>

<form>
    <input type="submit" class="btn" value="<?php esc_attr_e('Export page as PDF') ?>" />
</form>


<p class="updated"><em>Updated on <?php the_modified_date('F j, Y'); ?></em></p>


<div class="pstl-table-container">
    <table class="pstl-table">
        <thead>
        <tr>
            <td></td>
            <td class="pst-title"><?php echo $post->post_title; ?></td>
        </tr>
        </thead>
        <tbody>
        <?php foreach( $questions as $questionID => $question ) { ?>
            <tr class="question-<?php echo $questionID; ?>">
                <td><?php echo $question; ?></td>
                <td class="answer-<?php echo $questionID; ?>"><?php echo isset($answers[ $questionID ]) ? $answers[ $questionID ] : ''; ?></td>
                </tr>
        <?php } ?>
        </tbody>
    </table>
</div>