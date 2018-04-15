<?php
namespace ABetterBalance\Plugin;
global $post;

$questions = get_option( PaidSickTime::$questionsOptionName );
#$answers = Answers::getAnswers( $post->ID );
$locations = []; #Locations:getLocations();
$PSTs = PaidSickTime::getPSTs( true );

?>
<h2>Overview of Paid Sick Time Laws in the United States:<br />Comparison Results</h2>

<form>
    <input type="submit" class="btn" value="<?php esc_attr_e('Export page as PDF') ?>" />
</form>


<p class="updated"><em>Updated on September 1, 2017</em></p>

<p><strong>Note: You must check at least one box under Issues and at least one box under location.</strong></p>




<table class="pstl-table">
</table>
