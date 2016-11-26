<?php
class AddCheckoutPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Začínáme
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_addcheckout_page' ) );
        add_action( 'admin_init', array( $this, 'addcheckout_page_init' ) );
    }

    /**
     * Přidáme stránku s nastavením
     */
    public function add_addcheckout_page()
    {
        // Tato stránka bude pod "Nové Trendy"
        add_submenu_page(
            'nt-admin-page',
             __('Settings Add Checkout','add-checkout'),
            'Add Checkout',
            'manage_options',
            'add_checkout',
            array( $this, 'nt_create_admin_page' )
        );
        // REMOVE THE SUBMENU CREATED BY add_menu_page
            global $submenu;
            unset( $submenu['nt-admin-page'][0] );
    }

    /**
     * Options page callback
     */
    public function nt_create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'nt_notes' );
        ?>
        <script>jQuery(document).ready(function(){document.getElementById("check_nt_note_1").checked?jQuery(".nt_note_1").show(1e3):jQuery(".nt_note_1").hide(1e3),jQuery("#check_nt_note_1").click(function(){jQuery(".nt_note_1").toggle(1e3)}),document.getElementById("check_nt_note_2").checked?jQuery(".nt_note_2").show(1e3):jQuery(".nt_note_2").hide(1e3),jQuery("#check_nt_note_2").click(function(){jQuery(".nt_note_2").toggle(1e3)})});</script>
        <div class="wrap">
            <h2><?php _e('Add Checkout - Plugin settings', 'add-checkout')?></h2>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'nt_notes_option_group' );
                do_settings_sections( 'add_checkout' );
                submit_button();
            ?>
            </form>
        </div>

        <?php  }


    /**
     * Registrujeme a přidáme nastavení
     */
    public function addcheckout_page_init()
    {

        register_setting(
            'nt_notes_option_group', // Groupa nastavení
            'nt_notes', // Název nastavení
            array( $this, 'sanitize' ) // Sanitizujeme
        );

        add_settings_section(
            'setting_section_nt_notes', // ID
             __('Settings notes and buttons in Cart page','add-checkout'), // Title
            array( $this, 'print_section_info' ), // Callback
            'add_checkout' // Page
        );

        add_settings_field(
            'check_nt_note_1', // Checkbox Poznamka 1
             __('Enable Note 1 ?','add-checkout'), // Title
            array( $this, 'check_nt_note_1_callback' ), // Callback
            'add_checkout', // Page
            'setting_section_nt_notes' // Section
        );

        add_settings_field(
            'nt_note_1', // Poznamka 1
            __('Note 1:','add-checkout'), // Title
            array( $this, 'nt_note_1_callback' ), // Callback
            'add_checkout', // Page
            'setting_section_nt_notes', // Section
            array( 'class' => 'nt_note_1' )
        );

        add_settings_field(
            'check_nt_note_2', // Checkbox Poznamka 2
            __('Enable Note 2 ?','add-checkout'), // Title
            array( $this, 'check_nt_note_2_callback' ), // Callback
            'add_checkout', // Page
            'setting_section_nt_notes' // Section
        );

        add_settings_field(
            'nt_note_2', // Poznamka 2
            __('Note 2:','add-checkout'),
            array( $this, 'nt_note_2_callback' ),
            'add_checkout',
            'setting_section_nt_notes',
            array( 'class' => 'nt_note_2', 'Poznámka 2' )
        );

        add_settings_field(
            'nt_empty_cart', // Vyprazdnit kosik
            __('Show button "Empty Cart"?','add-checkout'),
            array( $this, 'nt_empty_cart_callback'),
            'add_checkout',
            'setting_section_nt_notes'
        );

        add_settings_field(
            'nt_button_continue_shopping', // Pokracovat v nakupu
            __('Show button "Continue Shopping"?','add-checkout'),
            array( $this, 'nt_button_continue_shopping_callback'),
            'add_checkout',
            'setting_section_nt_notes'
        );



    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['nt_note_1'] ) )
            $new_input['nt_note_1'] = sanitize_text_field( $input['nt_note_1'] );

        if( isset( $input['check_nt_note_1'] ) )
            $new_input['check_nt_note_1'] = (int)( $input['check_nt_note_1'] );

        if( isset( $input['nt_note_2'] ) )
            $new_input['nt_note_2'] = sanitize_text_field( $input['nt_note_2'] );

        if( isset( $input['check_nt_note_2'] ) )
            $new_input['check_nt_note_2'] = (int)( $input['check_nt_note_2'] );

        if( isset( $input['nt_empty_cart'] ) )
            $new_input['nt_empty_cart'] = (int)( $input['nt_empty_cart'] );

        if( isset( $input['nt_button_continue_shopping'] ) )
            $new_input['nt_button_continue_shopping'] = (int)( $input['nt_button_continue_shopping'] );

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        _e('Enter a notes that will appear in the shopping cart:','add-checkout');
    }

    /**
     * Get the settings option checkbox for NT Note 1
     */
    public function check_nt_note_1_callback()
    {
        ?>
        <input id="check_nt_note_1" name="nt_notes[check_nt_note_1]" type="checkbox" value="1" <?php checked( isset( $this->options['check_nt_note_1'] ) );?> />
        <?php
    }

    /**
     * Get the settings option checkbox for NT Note 2
     */
    public function check_nt_note_2_callback()
    {
        ?>
        <input id="check_nt_note_2" name="nt_notes[check_nt_note_2]" type="checkbox" value="1" <?php checked( isset( $this->options['check_nt_note_2'] ) );?> />
        <?php
    }


    /**
     * Get the settings option array and print one of its values
     */
    public function nt_note_1_callback()
    {
        printf(
            '<input type="text" name="nt_notes[nt_note_1]" value="%s" />',
            isset( $this->options['nt_note_1'] ) ? esc_attr( $this->options['nt_note_1']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function nt_note_2_callback()
    {
        printf(
            '<input type="text" id="nt_note_2" name="nt_notes[nt_note_2]" value="%s" />',
            isset( $this->options['nt_note_2'] ) ? esc_attr( $this->options['nt_note_2']) : ''
        );

    }

    /**
     * Get the settings option checkbox for Empty Cart Button
     */
    public function nt_empty_cart_callback()
    {
        ?>
        <input name="nt_notes[nt_empty_cart]" type="checkbox" value="1" <?php checked( isset( $this->options['nt_empty_cart'] ) );?> />
        <?php
    }


    /**
     * Get the settings option array and print one of its values
     */
    public function nt_button_continue_shopping_callback()
    {
    ?>
    <input name="nt_notes[nt_button_continue_shopping]" type="checkbox" value="1" <?php checked( isset( $this->options['nt_button_continue_shopping'] ) );?> />
    <?php
    }

}
?>