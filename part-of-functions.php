<?php
add_action( 'ucp_custom_gf_background_processor_27', 'send_entry_from_form_27_to_26', 10, 2 );
function send_entry_from_form_27_to_26( $entry, $form ) {
    //add_entry_from_gfform_27_to_26($entry);
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

if ( ! class_exists( 'GF_Background_Process' ) ) {
    require_once GF_PLUGIN_DIR_PATH . 'includes/libraries/gf-background-process.php';
}

// Rename the class to something unique and more meaningful to your use case.
class UCP_Custom_GF_Background_Processor extends GF_Background_Process {

    // Rename the action to something unique and more meaningful to your use case.
    protected $action = 'ucp_custom_gf_background_processor';

    /**
     * Null or the current instance of the class.
     *
     * @var null|self
     */
    private static $_instance;

    /**
     * Returns the current instance of the class.
     */
    public static function get_instance() {
        if ( null === self::$_instance ) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Initializes the background processor.
     */
    public function __construct() {
        parent::__construct();
        add_action( 'init', array( $this, 'init' ), 11 );
    }

    /**
     * Add any hooks.
     *
     * @return void
     */
    public function init() {
        add_action( 'gform_after_submission', array( $this, 'after_submission' ), 10, 2 );
        add_action( 'gform_uninstalling', array( $this, 'uninstalling' ) );
    }

    /**
     * Adds the entry to the background processing queue.
     *
     * @param array $entry The entry that was created by the submission.
     * @param array $form  The form that was submitted.
     *
     * @return void
     */
    public function after_submission( $entry, $form ) {
        if ( rgar( $entry, 'status' ) === 'spam' ) {
            return;
        }

        $entry_id = absint( rgar( $entry, 'id' ) );
        GFCommon::log_debug( __METHOD__ . sprintf( '(): Adding entry #%d to the async processing queue.', $entry_id ) );

        $this->push_to_queue( array(
            'entry_id' => $entry_id,
            'form_id'  => absint( rgar( $form, 'id' ) ),
        ) );
        $this->save()->dispatch();
    }

    /**
     * Performs some cleanup tasks when the plugin is uninstalled.
     *
     * @return void
     */
    public function uninstalling() {
        $this->clear_scheduled_events();
        $this->clear_queue( true );
        $this->unlock_process();
    }

    /**
     * Processes the background task.
     *
     * @param array $item The task arguments.
     *
     * @return bool|array
     */
    protected function task( $item ) {
        $entry = GFAPI::get_entry( rgar( $item, 'entry_id' ) );
        if ( is_wp_error( $entry ) ) {
            GFCommon::log_debug( __METHOD__ . sprintf( '(): Aborting; Entry #%d not found.', rgar( $item, 'entry_id' ) ) );

            return false;
        }

        $form = GFAPI::get_form( rgar( $item, 'form_id' ) );
        if ( empty( $form ) ) {
            GFCommon::log_debug( __METHOD__ . sprintf( '(): Aborting; Form #%d not found.', rgar( $item, 'form_id' ) ) );

            return false;
        }

        // Allow the form to be hydrated by add-ons e.g. populating the field inputs/choices properties.
        $form = $this->filter_form( $form, $entry );

        GFCommon::log_debug( __METHOD__ . '(): Processing => ' . print_r( $item, true ) );

        // process the $entry here
        // return false to remove the item from the queue
        // return $item to keep it in the queue for another attempt
        add_entry_from_gfform_27_to_26($entry);

        return false;
    }

}

// Update the class name to match your new class name above.
UCP_Custom_GF_Background_Processor::get_instance();
?>
