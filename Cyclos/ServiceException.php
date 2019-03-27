<?php namespace Cyclos;

/**
 * Exception thrown when there is some error in a cyclos service call.
 * Normally, clients should handle the statusCode property to determine the error type 
 * e.g internal server error (500), not found error (404), etc. 
 * The $error property contains additional error details.
 */
class ServiceException extends \Exception {
	public $service;
	public $operation;
	public $statusCode;
	public $error;

	public function __construct($service, $operation, $statusCode, $error) {
		parent::__construct("Error calling Cyclos service: ${service}.${operation}: $statusCode");
		$this->service = $service;
		$this->operation = $operation;
		$this->statusCode = $statusCode;
		$this->error = $error;
	}
}
