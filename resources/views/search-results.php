<?php
namespace ABetterBalance\Plugin;
global $post;

// @todo: select only questions (from $_POST) with correct $key in array
$questions = get_option( PaidSickTime::$questionsOptionName );
$args = [];

if( isset($_REQUEST['locations']) && !empty($_REQUEST['locations']) )
    $args['post__in'] = $_REQUEST['locations'];

$PSTs = PaidSickTime::getPSTs( $args );

?>
<h2>Overview of Paid Sick Time Laws in the United States:<br />Comparison Results</h2>

<form>
    <input type="submit" class="btn" value="<?php esc_attr_e('Export page as PDF') ?>" />
</form>


<p class="updated"><em>Updated on September 1, 2017</em></p>

<p><strong>Note: You must check at least one box under Issues and at least one box under location.</strong></p>


<div class="pstl-table-container">
    <table class="pstl-table">
        <thead>
            <tr>
                <td></td>
                <?php
                $pstAnswers = [];
                foreach( $PSTs as $k => $pst ) {
                    $pstAnswers[ $pst->ID ] = Answers::getAnswers( $pst->ID );
                    echo "<td>$pst->post_title</td>\n";
                }
                ?>
            </tr>
        </thead>
        <tbody>
        <?php foreach( $questions as $questionID => $question ) { ?>
            <tr class="question-<?php echo $questionID; ?>">
                <td><?php echo $question; ?></td>
                <?php
                foreach( $PSTs as $k => $pst ) :
                    echo sprintf( '<td class="answer-%d">%s</td>' . "\n",
                        $questionID,
                        isset($pstAnswers[$pst->ID][ $questionID ]) ? $pstAnswers[$pst->ID][ $questionID ] : ''
                    );
                endforeach;
                ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>