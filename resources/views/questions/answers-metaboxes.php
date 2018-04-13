<?php
namespace ABetterBalance\Plugin;

global $post;

$questions = get_option( Questions::$optionName );
#$answers = Answers::getAnswers( $post->ID );
$answers = [ 'answer 1', 'answer 2' ];
?>

<div class="wrap">
    <form name="form1" method="post" action="">
        <table id="repeatable-fieldset-one" width="100%">
            <tbody>
            <?php foreach ( (array) $questions as $k => $question ) : ?>
                <tr>
                    <td width="25%"><?php echo $question; ?></td>
                    <td width="75%">
                        <textarea placeholder="" rows="5" name="answers[]" style="width: 100%;"><?php echo !empty($answers[$k]) ? esc_attr( $answers[$k] ) : ''; ?></textarea>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>


        <p>
            <?php wp_nonce_field( Questions::$nonce, Questions::$nonce ); ?>
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
        </p>

    </form>
</div>
