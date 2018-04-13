<?php
namespace ABetterBalance\Plugin;


$questions = get_option( PaidSickTime::$questionsOptionName );
?>

<script type="text/javascript">
    jQuery(document).ready(function( $ ){
        $( '.add-row' ).on('click', function() {
            var row = $( '.empty-row.screen-reader-text' ).clone(true);
            row.removeClass( 'empty-row screen-reader-text' );
            row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
            return false;
        });

        $( '.remove-row' ).on('click', function() {
            $(this).parents('tr').remove();
            return false;
        });
    });
</script>

<div class="wrap">
    <form name="form1" method="post" action="">
        <h1><?php echo __( 'Questions' ); ?></h1>

        <p><a class="add-row button" href="#">Add another</a></p>
        <table id="repeatable-fieldset-one" width="100%">
            <tbody>
            <?php
            if ( $questions ) :
                foreach ( $questions as $field ) {
                ?>
                <tr>
                    <td>
                        <textarea placeholder="" rows="5" name="questions[]" style="width: 100%;"><?php echo !empty($field) ? esc_attr( $field ) : ''; ?></textarea>
                    </td>
                    <td><a class="button remove-row" href="#">Remove</a></td>
                </tr>
                <?php
                }
            else :
                // show a blank one
                ?>
                <tr>
                    <td>
                        <textarea placeholder="" rows="5" name="questions[]" style="width: 100%;"></textarea>
                    </td>
                    <td><a class="button cmb-remove-row-button button-disabled" href="#">Remove</a></td>
                </tr>
            <?php endif; ?>

            <!-- empty hidden one for jQuery -->
            <tr class="empty-row screen-reader-text">
                <td>
                    <textarea placeholder="" rows="5" name="questions[]" style="width: 100%;"></textarea>
                </td>
                <td><a class="button remove-row" href="#">Remove</a></td>
            </tr>
            </tbody>
        </table>
        <p><a class="add-row button" href="#">Add another</a></p>


        <p class="submit">
            <?php wp_nonce_field( Questions::$nonce, Questions::$nonce ); ?>
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
        </p>

    </form>
</div>
