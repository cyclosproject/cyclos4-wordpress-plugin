<?php namespace Cyclos;

/**
 * Base class for Cyclos service proxies
 */
class Service {
	private $urlSuffix;

	protected function __construct($urlSuffix) {
		$this->urlSuffix = $urlSuffix;
	}		
	
	/**
	 * Performs a POST request	 
	 */
	protected function post($operation = NULL, $pathVariables = NULL, $queryParameters = NULL, $body = NULL) {		
		return $this->run($operation, $pathVariables, $queryParameters, 'POST', $body);
	}
	
	/**
	 * Performs a GET request
	 */
	protected function get($operation = NULL, $pathVariables = NULL, $queryParameters = NULL, $body = NULL) {
		return $this->run($operation, $pathVariables, $queryParameters, 'GET', $body);
	}
	
	/**
	 * Performs a PUT request
	 */
	protected function put($operation = NULL, $pathVariables = NULL, $queryParameters = NULL, $body = NULL) {
		return $this->run($operation, $pathVariables, $queryParameters, 'PUT', $body);
	}
	
	/**
	 * Performs a DELETE request
	 */
	protected function delete($operation = NULL, $pathVariables = NULL, $queryParameters = NULL, $body = NULL) {
		return $this->run($operation, $pathVariables, $queryParameters, 'DELETE', $body);
	}
	
	/**
	 * Appends with curl the given body as post field	 
	 */
	private function postParams(&$curl, $body) {
		if ($body != NULL) {
			curl_setopt($curl, CURLOPT_POSTFIELDS, \json_encode($body));
		}
	}		
	
	/**
	 * Performs an HTTP request with the given parameters and method (GET, POST, PUT, DELETE)	 
	 */
	private function run($operation, $pathVariables, $queryParameters, $method, $body) {

		$debug = Configuration::isDebug();
		
		// Setup curl
		$url = Configuration::url($this->urlSuffix . (empty($operation) ? '' : '/' . $operation) , $pathVariables, $queryParameters);	
		$curl = \curl_init($url);
		
		switch ($method) {
			case 'POST':
				curl_setopt($curl, CURLOPT_POST, 1);
				$this->postParams($curl, $body);
				break;
			case 'PUT':
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				$this->postParams($curl, $body);
				break;
			default:
				break;
		}		
		$options = Configuration::curlOptions();			
		\curl_setopt_array ($curl, $options);			
		
		if($debug) {
			$verbose = $this->prepareDebug($curl);
		}
		
		// Execute the request
		$response = \curl_exec($curl);
		
		if($debug) {
			$this->debugResponse($curl, $response, $verbose);
		}
		
		$code = \curl_getinfo($curl, CURLINFO_HTTP_CODE);		
		$contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
		if ($code >= 200 && $code < 300) {
			if ($code == 204 || $contentType == NULL) {
				return NULL;
			} elseif ($this->isJson($response)) {
				return \json_decode($response);
			} else {
				return $response;
			}
		}
		
		$error = $response;
		if ($error == NULL) {
			$error = new \stdclass();
			$error->code = 'UNKNOWN';
		} elseif ($this->isJson($response)) {
			$error = \json_decode($response);
		}
		throw new ServiceException($this->urlSuffix, $operation, $code, $error);		
	}
	
	/**
	 * Prepares CURL for debug
	 */
	private function prepareDebug($curl) {
		\curl_setopt($curl, CURLOPT_VERBOSE, true);
		$verbose = fopen('php://temp', 'w+');
		\curl_setopt($curl, CURLOPT_STDERR, $verbose);
		return $verbose;
	}
	
	/**
	 * Prints detailed CURL response
	 */
	private function debugResponse($curl, $response, $verbose) {
		$url = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);	
		
		if ($response === FALSE) {
			printf("cUrl error (#%d): %s<br>\n", \curl_errno($curl),
			htmlspecialchars(\curl_error($curl)));
		}
		
		rewind($verbose);
		$verboseLog = stream_get_contents($verbose);
		
		echo "	Service URL:\n<pre>" . $url ."</pre>\n		
				Request information:\n<pre>", htmlspecialchars($verboseLog), "</pre>\n
				Response:\n<pre>". $response ."</pre>";
		
	}
	
	/**
	 * Returns if the given string is a valid JSON
	 */
	private function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
}