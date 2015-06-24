<?php
/*
Copyright (c) 2015 Ben Gribaudo, LLC <www.bengribaudo.com>
*/
 class SuperSimpleSpamStopper_Admin {
	 const OPTIONS_GROUP = 'custom-anti-spam-settings-main';
	 const MENU_SLUG = 'custom-anti-spam-settings';
	 
	function __construct() {
		add_action( 'admin_menu', array( $this, 'register_options_page' ) );				 
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}
	 
	private function is_unconfigured() {
		$s = new SuperSimpleSpamStopper();
		return !$s->is_question_configured();
	}
	 
	public function display_unconfigured_notice() {
		if ( $this->is_unconfigured() ) {
			echo '<div class="update-nag"><p>Super Simple SPAM Stopper needs to be configured with the question it should ask in order for it to filter comments.</p></div>';
		}
	}
	 
	public function register_options_page() {
		add_options_page( 'Super Simple SPAM Stopper Plugin Settings', 'Super Simple SPAM Stopper', 'manage_options', self::MENU_SLUG, array( $this, 'display_options_page' ) );
	}
	
	public function register_settings() {
		$this->register_setting( 'question', 'question_required' );
		$this->register_setting( 'answer', 'answer_required' );
		$this->register_setting( 'answer_case_sensitive' );
		$this->register_setting( 'answer_preserve_whitespace' );
		$this->register_setting( 'answer_regex' );
	}
	
	private function register_setting( $option, $callback = null ) {
		if ( $callback ) {
			$callback = array( $this, $callback );
		}
		
		register_setting( self::OPTIONS_GROUP, $this->option_name( $option ), $callback );
	}
	
	private function validate_required( $value, $setting, $error_message ) {
		$full_setting_name = SSSPAMSTOPPER_OPTIONS_PREFIX . $setting;
		
		if ( '' == trim( $value ) && !get_settings_errors( $full_setting_name )  ) {
			add_settings_error( 
			  SSSPAMSTOPPER_OPTIONS_PREFIX . $setting, 
			  SSSPAMSTOPPER_OPTIONS_PREFIX . $setting . '-error', 
			  $error_message
			);
		}
		
		return $value;
	}
	
	public function question_required( $value ) {
		return $this->validate_required( $value, 'question', 'You must specify a question.' );
	}
	
	public function answer_required( $value ) {
		return $this->validate_required( $value, 'answer', 'You must provide an answer.' );
	}

	private function plugin_uri() {
		$plugin_data = get_plugin_data( SSSPAMSTOPPER_PLUGIN_DATAFILE );
		return $plugin_data['PluginURI'];
	}
	
	function display_options_page() {
		if ( !current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		
		static $section_id = 'sssstopper-main';
		
		add_settings_section( $section_id, null, null, self::MENU_SLUG ); 
		add_settings_field( 'sssstopper-question', 'Question', array( $this, 'admin_question' ), self::MENU_SLUG, $section_id ); 
		add_settings_field( 'sssstopper-answer', 'Answer', array( $this, 'admin_answer' ), self::MENU_SLUG, $section_id ); 
		
		if ( $this->is_unconfigured() ) {
			$this->display_unconfigured_notice();
		}
    
	?>
	<div class="wrap">
		<h2>Super Simple SPAM Stopper<sup>TM</sup> Plugin Settings</h2>
		
		<p>This plugin attempts to prevent automated SPAM by requiring visitors to answer a question of your choosing in order for their comment to be accepted. Presumably, spambots will be unable to correctly answer this question and so comments they attempt to post will be rejected.</p>
		
		<p>Only regular comments from non-logged-in users are filtered. Pingbacks, trackbacks and authenticated users will not be prompted to answer the verification question.</p>

		<p>Brought to you by <a href="http://bengribaudo.com/" target="_blank">Ben Gribaudo, LLC</a>. To check for plugin updates, visit <a href="<?php echo esc_url( $this->plugin_uri() ); ?>" target="_blank"><?php echo esc_url( $this->plugin_uri() ); ?></a>.</p>
		
		<p><strong>Privacy Policy:</strong> This plugin does not call home or communicate with any external servers or services. All processing is done locally within your WordPress site.</p>
				
		<form method="post" action="options.php">

			<?php settings_fields( self::OPTIONS_GROUP ); ?>
			<?php do_settings_sections( self::MENU_SLUG ); ?> 
			
			<?php submit_button(); ?>
		</form>
	</div>
<?php
	}
	
	private function option_name( $key ) {
		return SSSPAMSTOPPER_OPTIONS_PREFIX . $key;
	}
	
	private function get_option( $key ) {
		return get_option( $this->option_name( $key ) );
	}
	
	private function textbox_field( $id, $option, $description = null, $size = 40 ) {		
		printf(
            '<input type="text" id="%s" name="%s" value="%s" size="%d" />',
            $id, $this->option_name( $option ), esc_attr( $this->get_option( $option ) ), $size
        );
		
		if ( $description ) {
			printf(
				'<p class="description" id="%s-description">%s</p>',
				$id, $description
			);
		}
	}
	
	private function checkbox_field( $id, $option, $label = null, $description = null) {		
		if ( $label ) {
			printf( '<label for="%s">', $id );
		}
		
		printf(
            '<input type="checkbox" id="%s" name="%s" value="1" ' .
			checked( $this->get_option( $option ), 1, false ) .
			'/>',
            $id, $this->option_name($option )
        );
		
		if ( $label ) {
			printf( '%s</label>', $label );
		}		
		
		if ( $description ) {
			printf( '<p class="description" id="%s-description">%s</p>', $id, $description );
		}
	}
	
	function admin_question() {
		return $this->textbox_field( 'sssstopper-question', 'question', 'Visitors will be required to correctly answer this question in order to post a comment. HTML tags allowed.' );
	}
	
	function admin_answer() {
		$this->textbox_field( 'sssstopper-answer', 'answer', 'Defines the answer to the above question. Any less than (<code>&lt;</code>) or greater than (<code>&gt;</code>) characters in visitor responses will be removed before being compared to the above answer.' );
		echo '<br />';
		$this->checkbox_field( 'sssstopper-answer-case-sensitive', 'answer_case_sensitive', 'Case-sensitive', "Requires that responses match the answer's capitalization. If unchecked, comparison between the two is case insensitive." );
		echo '<br />';
		$this->checkbox_field( 'sssstopper-answer-preserve-whitespace', 'answer_preserve_whitespace', 'Preserve response whitespace', 'By default, leading and trailing whitespace is removed from responses before they are compared to the answer. Enabling this option turns off this trimming.');
		echo '<br />';
		$this->checkbox_field( 'sssstopper-answer-regex', 'answer_regex', 'Answer is regular expression', 'Interprets the answer field as a  <a href="http://php.net/manual/en/reference.pcre.pattern.syntax.php" target="_blank">PHP Perl-compatible regular expression (PCRE)</a>. When enabled, specify the desired regular expression <strong>without</strong> <a href="http://php.net/manual/en/regexp.reference.delimiters.php" target="_blank">surrounding delimiters</a> or <a href="http://php.net/manual/en/reference.pcre.pattern.modifiers.php" target="_blank">modifiers</a> in the answer field. sage examples: <code>^(Jim|James)$</code> (correct); <code>/^(Jim|James)$/i</code> (incorrect; delimiters and modifiers not permitted).');
	}
 }