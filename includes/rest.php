<?php

class Rest {
    protected $request;
    //protected $service_name;
    protected $data;
    protected $userId;
    
    public function __construct() {
        $this->request = json_encode(INPUT);
    }
    
    public function validateData($fieldName, $value, $datatype, $required = true) {
        if($required && (empty($value))) {
            $this->throwError(NO_CONTENT, "Param $fieldName is required.");
            http_response_code(NO_CONTENT);
        }
        
        switch ($datatype) {
            case BOOLEAN:
                if(!is_bool($value)) {
                    $this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype of $fieldName should be a boolean.");
                    http_response_code(VALIDATE_PARAMETER_DATATYPE);
                }
                
                break;
            case INTEGER:
                if(!is_numeric($value)) {
                    $this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype of $fieldName should be an integer.");
                    http_response_code(VALIDATE_PARAMETER_DATATYPE);
                    
                }
                
                break;
            case STRING:
                if(!is_string($value)) {
                    $this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype of $fieldName should be a string.");
                    http_response_code(VALIDATE_PARAMETER_DATATYPE);
                }
                
                break;
        }
        
        return $value;
    }
    
    public function getAuthorizationHeader() {
        $headers = null;
        if(isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER['Authorization']);
        } else if(isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER['HTTP_AUTHORIZATION']);
        } else if(function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            
            $requestHeaders = array_combine(
                array_map(
                    'ucwords',
                    array_keys($requestHeaders)
                    ),
                array_values($requestHeaders)
                );
            
            if(isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        
        return $headers;
    }
    
    public function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        
        if(!empty($headers)) {
            if(preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        $this->throwError(AUTHORIZATION_HEADER_NOT_FOUND, "Access token not found.");
        http_response_code(AUTHORIZATION_HEADER_NOT_FOUND);
    }
    
    public function validateToken() {
        $token = $this->getBearerToken();
        $payload = \Firebase\JWT\JWT::decode($token, SECRET_KEY, ['HS256']);
        $userId = $payload->userId;
    }
    
    public function throwError($code, $message) {
        echo json_encode(['error' => ['status'=>$code, 'message'=>$message]]);
        exit;
    }
    
    public function response($code, $message) {
        echo json_encode(['response' => ['status'=>$code, 'message'=>$message]]);
        exit;
    }
}