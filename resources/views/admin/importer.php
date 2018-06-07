<?php
namespace ABetterBalance\Plugin;
?>
<div class="wrap">
    <h1><?php echo get_admin_page_title(); ?></h1>

    <form method="post" action="<?php echo admin_url( 'admin.php' ); ?>" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo PaidSickTime::$cptName; ?>-importer" />
        <input type="hidden" name="page" value="importer" />

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="pstl-import-file">Import <?php echo PaidSickTime::$cptFullName; ?></label></th>
                    <td><input type="file" name="pstl-import-file" id="pstl-import-file"></td>
                </tr>
            </tbody>
        </table>

        <p class="submit"><input name="submit" id="submit" class="button button-primary" value="Import" type="submit"></p>
    </form>


</div>