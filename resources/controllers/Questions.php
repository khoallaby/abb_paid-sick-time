<?php
namespace ABetterBalance\Plugin;

class Questions extends Base {
    public static $capability = 'edit_theme_options';
    public static $metaField = '_questions';


    public function init() {
        parent::init();

        add_action( 'admin_init', [ $this, 'addRepeatableMetaBoxes' ], 2 );
        add_action( 'save_post', [ $this, 'saveRepeatableMetaBoxes' ] );
        add_action( 'admin_menu', [ $this, 'addSubmenu' ] );

    }



    public function addSubmenu() {
        # $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '' ) {
        add_submenu_page(
            'edit.php?post_type=' . PaidSickTime::$cptName,
            __( PaidSickTime::$cptFullName . ' Questions' ),
            __( 'Questions' ),
            static::$capability,
            'questions',
            [ $this, 'submenuPage' ]
        );
    }



    public function submenuPage() {
        echo 'questions page';
    }



    /**
     * Add repeatable meta boxes
     * https://stackoverflow.com/questions/44653777/creating-a-repeater-meta-box-without-a-plugin-in-wordpress
     */
    public function addRepeatableMetaBoxes() {
        add_meta_box(
            'gpminvoice-group',
            'Custom Repeatable',
            [
                $this,
                'displayRepeatableMetaBoxes'
            ],
            PaidSickTime::$cptName,
            'normal',
            'default'
        );
    }

    public function displayRepeatableMetaBoxes() {
        global $post;
        $gpminvoice_group = get_post_meta($post->ID, 'customdata_group', true);
        wp_nonce_field( 'pst_repeatable_meta_box_nonce', 'pst_repeatable_meta_box_nonce' );
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function( $ ){
                $( '#add-row' ).on('click', function() {
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
        <table id="repeatable-fieldset-one" width="100%">
            <tbody>
            <?php
            if ( $gpminvoice_group ) :
                foreach ( $gpminvoice_group as $field ) {
                    ?>
                    <tr>
                        <td width="15%">
                            <input type="text"  placeholder="Title" name="TitleItem[]" value="<?php if($field['TitleItem'] != '') echo esc_attr( $field['TitleItem'] ); ?>" /></td>
                        <td width="70%">
                            <textarea placeholder="Description" cols="55" rows="5" name="TitleDescription[]"> <?php if ($field['TitleDescription'] != '') echo esc_attr( $field['TitleDescription'] ); ?> </textarea></td>
                        <td width="15%"><a class="button remove-row" href="#1">Remove</a></td>
                    </tr>
                    <?php
                }
            else :
                // show a blank one
                ?>
                <tr>
                    <td>
                        <input type="text" placeholder="Title" title="Title" name="TitleItem[]" /></td>
                    <td>
                        <textarea  placeholder="Description" name="TitleDescription[]" cols="55" rows="5">  </textarea>
                    </td>
                    <td><a class="button  cmb-remove-row-button button-disabled" href="#">Remove</a></td>
                </tr>
            <?php endif; ?>

            <!-- empty hidden one for jQuery -->
            <tr class="empty-row screen-reader-text">
                <td>
                    <input type="text" placeholder="Title" name="TitleItem[]"/></td>
                <td>
                    <textarea placeholder="Description" cols="55" rows="5" name="TitleDescription[]"></textarea>
                </td>
                <td><a class="button remove-row" href="#">Remove</a></td>
            </tr>
            </tbody>
        </table>
        <p><a id="add-row" class="button" href="#">Add another</a></p>
        <?php
    }

    /**
     * Save post
     * @param $post_id
     */
    public function saveRepeatableMetaBoxes($post_id) {
        if( !isset( $_POST['pst_repeatable_meta_box_nonce'] ) ||
             !wp_verify_nonce( $_POST['pst_repeatable_meta_box_nonce'], 'pst_repeatable_meta_box_nonce' ) )
            return;

        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        if(!current_user_can('edit_post', $post_id))
            return;

        $old          = get_post_meta( $post_id, 'customdata_group', true );
        $new          = array();
        $invoiceItems = $_POST['TitleItem'];
        $prices       = $_POST['TitleDescription'];
        $count        = count( $invoiceItems );

        for( $i = 0; $i < $count; $i ++ ) {
            if ( $invoiceItems[ $i ] != '' ) :
                $new[ $i ]['TitleItem']        = stripslashes( strip_tags( $invoiceItems[ $i ] ) );
                $new[ $i ]['TitleDescription'] = stripslashes( $prices[ $i ] ); // and however you want to sanitize
            endif;
        }
        if( !empty( $new ) && $new != $old ) {
            update_post_meta( $post_id, 'customdata_group', $new );
        } elseif( empty( $new ) && $old ) {
            delete_post_meta( $post_id, 'customdata_group', $old );
        }


    }



}

add_action( 'init', array( \ABetterBalance\Plugin\Questions::get_instance(), 'init' ));
