<?php
/*
	Copyright (c) 2015 Ben Gribaudo, LLC <www.bengribaudo.com>.
*/
class SuperSimpleSpamStopper {
	private $form_field_id = 'sssstopper-answer';
	
	public function __construct() {
		if ( $this->is_question_configured() ) {		
			add_action( 'comment_form_after_fields', array( $this,'add_prompt') );
			add_filter( 'preprocess_comment', array( $this, 'verify_prompt') );
		}
	}
	
	public function is_question_configured() {
		return (true == $this->get_option( 'question' ) );
	}
	
	public function add_prompt() {
		echo "\t" . '<p class="comment-form-ssspamstopper"><label for="' . $this->form_field_id . '">' . $this->get_option( 'question' )  . '<span class="required">*</span></label> <input type="text" name="' . $this->form_field_id . '" id="' . $this->form_field_id . '" size="30" aria-required="true" required="required" /></p>' . "\n";
	}
	
	public function verify_prompt( $data ) {
		// Only process regular comments (indicated by a blank comment_type)
		// for non-logged in users (indicated by a blank user_ID).
		if ( '' != $data['comment_type'] || '' != $data['user_ID'] ) {
			return $data;
		}
		
		$response = $this->sanatize_input( $_POST[ $this->form_field_id ] );
		$correct_answer = $this->get_option( 'answer' );
		$case_sensitive = $this->get_option( 'answer_case_sensitive' );

		if ( !$this->get_option( 'answer_preserve_whitespace' ) ) {
			$response = trim( $response );
		}
		
		$comparer = ( $this->get_option( 'answer_regex' ) ) ? 'compare_regex' : 'compare_string';
				
		if ( !$this->$comparer( $correct_answer, $response, $case_sensitive ) ) {
			wp_die('<strong>ERROR</strong>: Please correctly answer the following question: ' . $this->get_option( 'question' ) );
		}
		
		return $data;
	}
	
	private function compare_string( $correct_answer, $response, $case_sensitive ) {		
		if ( !$case_sensitive ) {
			$response = strtolower( $response );
			$correct_answer = strtolower( $correct_answer );
		}
		
		return ( $response === $correct_answer );
	}
	
	private function compare_regex( $correct_answer, $response, $case_sensitive ) {
		$modifiers = ( !$case_sensitive ) ? 'i' : null;
		$expression = '/' . str_replace( '/', '\/', $correct_answer ) . '/' . $modifiers;
	
		return ( preg_match( $expression, $response ) === 1 );
	}
		
	private function get_option( $key ) {
		return get_option( SSSPAMSTOPPER_OPTIONS_PREFIX . $key );
	}
	
	private function sanatize_input( $input ) {
		return str_replace( array('<', '>'), '', $input );
	}
}