<?php
namespace ABetterBalance\Plugin;
global $post;

$questions = get_option( PaidSickTime::$questionsOptionName );
$answers = Answers::getAnswers( $post->ID );
?>

<?php foreach ( (array) $questions as $k => $question ) : ?>
<div class="question" style="margin-bottom: 50px;">
    <h3><?php echo nl2br($question); ?></h3>
    <textarea placeholder="" rows="5" name="answers[]" style="width: 100%;"><?php echo !empty($answers[$k]) ? sanitize_textarea_field( $answers[$k] ) : ''; ?></textarea>
</div>
<?php endforeach; ?>
<p>
    <?php wp_nonce_field( PaidSickTime::$nonce, PaidSickTime::$nonce ); ?>
    <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>
