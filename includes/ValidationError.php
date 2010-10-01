<?php

/**
 * Error class.
 * 
 * @since 0.4
 * 
 * @file Validator_Error.php
 * @ingroup Validator
 * 
 * @author Jeroen De Dauw
 */
class ValidationError {
	
	const SEVERITY_MINOR = 0;	// Minor error. ie a decapriation notice
	const SEVERITY_LOW = 1;		// Lower-then-normal severity. ie an unknown parameter
	const SEVERITY_NORMAL = 2;	// Normal severiry. ie an invalid value provided
	const SEVERITY_HIGH = 3;	// Higher-then-normal severity. ie an invalid value for a significant parameter
	const SEVERITY_FATAL = 4;	// Fatal error. Either a missing or an invalid required parameter

	const ACTION_IGNORE = 0;	// Ignore the error
	const ACTION_LOG = 1;		// Log the error
	const ACTION_WARN = 2;		// Warn that there is an error
	const ACTION_SHOW = 3;		// Show the error
	const ACTION_DEMAND = 4;	// Show the error and don't render output
	
	public $message;
	public $severity;
	
	/**
	 * List of 'tags' for the error. This is mainly ment for indicating an error
	 * type, such as 'missing parameter' or 'invalid value', but allows for multiple
	 * such indications.
	 * 
	 * @since 0.4
	 * 
	 * @var array
	 */
	public $tags;
	
	/**
	 * Where the error occured.
	 * 
	 * @since 0.4
	 * 
	 * @var mixed: string or false
	 */
	public $element;
	
	/**
	 * @since 0.4
	 * 
	 * @param string $message
	 * @param integer $severity
	 */
	public function __construct( $message, $severity = self::SEVERITY_NORMAL, $element = false, array $tags = array() ) {
		$this->message = $message;
		$this->severity = $severity;
		$this->element = $element;
		$this->tags = $tags;
	}
	
	/**
	 * Returns the error message describing the error.
	 * 
	 * @since 0.4
	 * 
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}
	
	/**
	 * Returns the severity of the error.
	 * 
	 * @since 0.4
	 * 
	 * @return integer Element of the ValidationError::SEVERITY_ enum
	 */	
	public function getSeverity() {
		return $this->severity;
	}
	
	/**
	 * Returns if the severity is equal to or bigger then the provided one.
	 * 
	 * @since 0.4
	 * 
	 * @return boolean
	 */
	public function hasSeverity( $severity ) {
		return $this->severity >= $severity;
	}
	
	/**
	 * Returns the action associated with the errors severity.
	 * 
	 * @since 0.4
	 * 
	 * @return integer Element of the ValidationError::ACTION_ enum
	 */
	public function getAction() {
		global $egErrorActions;
		
		if ( $this->severity === self::SEVERITY_FATAL ) {
			// This action should not be configurable, as lowering it would break in the Validator class.
			return self::ACTION_DEMAND;
		}
		else if ( array_key_exists( $this->severity, $egErrorActions ) ) {
			return $egErrorActions[$this->severity];
		}
		else {
			throw new Exception( "No action associated with error severity '$this->severity'" );
		}
	}	
	
	/**
	 * Returns if the action associated with the severity is equal to or bigger then the provided one.
	 * 
	 * @since 0.4
	 * 
	 * @return boolean
	 */
	public function hasAction( $action ) {
		return $this->getAction() >= $action;
	}
	
	/**
	 * Returns if the error is fatal.
	 * 
	 * @since 0.4
	 * 
	 * @return boolean
	 */
	public function isFatal() {
		return $this->hasSeverity( self::SEVERITY_FATAL );
	}
	
	/**
	 * Returns if the error should be logged.
	 * 
	 * @since 0.4
	 * 
	 * @return boolean
	 */
	public function shouldLog() {
		return $this->hasAction( self::ACTION_LOG );
	}
	
	/**
	 * Returns if there should be a warning that errors are present.
	 * 
	 * @since 0.4
	 * 
	 * @return boolean
	 */
	public function shouldWarn() {
		return $this->hasAction( self::ACTION_WARN );
	}
	
	/**
	 * Returns if the error message should be shown. 
	 * 
	 * @since 0.4
	 * 
	 * @return boolean
	 */
	public function shouldShow() {
		return $this->hasAction( self::ACTION_SHOW );
	}	
	
	/**
	 * Returns if the error message should be shown, and the output not be rendered. 
	 * 
	 * @since 0.4
	 * 
	 * @return boolean
	 */
	public function shouldDemand() {
		return $this->hasAction( self::ACTION_DEMAND );
	}
	
}