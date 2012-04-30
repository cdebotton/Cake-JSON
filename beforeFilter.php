<?php
	/**
	 * Include this in your beforeFilter in CakePHP
	 * to route JSON requests to json_action.
	 */
		if($this->RequestHandler->requestedWith('json')) {
			if(function_exists('json_decode')) {
				$jsonData = json_decode(utf8_encode(trim(file_get_contents('php://input'))), true);
			}
			if(!is_null($jsonData) and $jsonData !== false) {
				$this->data = $jsonData;
				$action = $this->RequestHandler->params['action'];
				$prefix = $this->RequestHandler->params['prefix'];
				if(preg_match('/^'.$prefix.'\_(\w+)$/',$action,$matches)) {
					$fn = "ajax_{$matches[1]}";
					if((int)method_exists($this,$fn)) {
						call_user_func_array( array( $this, $fn ), $this->RequestHandler->params['pass']);
						exit();
					}
					
				}
			}
		}