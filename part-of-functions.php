<?php
add_action( 'gform_after_submission_27', 'send_entry_from_form_27_to_26', 10, 2 );
function send_entry_from_form_27_to_26( $entry, $form ) {
    add_entry_from_gfform_27_to_26($entry);
}

function add_entry_from_gfform_27_to_26($entry): void {
    $form_id                       = 26;
    $input_values                  = array();
    $input_values['input_3']       = 'A';
    $input_values['input_24']      = '';
    $input_values['input_8']       = rgar( $entry, '29' );
    $input_values['input_26_10']   = rgar( $entry, '10' );
    $input_values['input_26_20']   = rgar( $entry, '20' );
    $input_values['input_26_21']   = rgar( $entry, '21' );
    $input_values['input_26_22']   = rgar( $entry, '22' );
    $input_values['input_26_11']   = rgar( $entry, '11' );
    $input_values['input_26_15']   = rgar( $entry, '15' );
    $input_values['input_26_14']   = rgar( $entry, '14' );
    $input_values['input_26_13']   = rgar( $entry, '13' );
    $input_values['input_26_12']   = rgar( $entry, '12' );
    $input_values['input_16']      = rgar( $entry, '16' );
    $input_values['input_26_28']   = rgar( $entry, '28' );
     
    $result = GFAPI::submit_form( $form_id, $input_values );
    //return;
}
?>
