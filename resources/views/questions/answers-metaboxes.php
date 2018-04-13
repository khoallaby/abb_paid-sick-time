<?php
namespace ABetterBalance\Plugin;
global $post;

$questions = get_option( PaidSickTime::$questionsOptionName );
$answers = Answers::getAnswers( $post->ID );
?>

<div class="question">
<?php foreach ( (array) $questions as $k => $question ) : ?>
    <p><?php echo nl2br($question); ?></p>
    <textarea placeholder="" rows="5" name="answers[]" style="width: 100%;"><?php echo !empty($answers[$k]) ? sanitize_textarea_field( $answers[$k] ) : ''; ?></textarea>
<?php endforeach; ?>
</div>
<p>
    <?php wp_nonce_field( PaidSickTime::$nonce, PaidSickTime::$nonce ); ?>
    <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>
