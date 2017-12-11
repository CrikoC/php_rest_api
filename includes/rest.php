<?php

class Rest {
    protected $request;
    //protected $service_name;
    protected $data;
    protected $userId;
    
    public function __construct() {
        $this->request = json_encode(INPUT);
    }
    
//     public function validateRequest() {
//         if(CT != 'application/json') {
//             $this->throwError(REQUEST_CONTENT_TYPE_NOT_VALID, "Request content type is not valid.");
//         }
        
//         $data = json_decode($this->request);
        
// //         if(!isset($data["name"]) || $data["name"] == "") {
// //             $this->throwError(API_NAME_REQUIRED, "Api name required.");
// //         }
// //         $this->service_name = $data["name"];
        
//         if(!isset($data) || $data == "") {
//             $this->throwError(API_PARAM_REQUIRED, "Api parameters required.");
//         }
//         $this->data = $data;
//     }
    
//     public function processApi() {
//         $api = new Api;
//         $rMethod = new ReflectionMethod('Api', $this->service_name);
        
//         if(!method_exists($api, $this->service_name)) {
//             $this->throwError(API_DOES_NOT_EXIST, "Api does not exist");
//         }
        
//         $rMethod->invoke($api);
//     }
    
    public function validateParameter($fieldName, $value, $datatype, $required = true) {
        if($required && (empty($value))) {
            $this->throwError(VALIDATE_PARAMETER_REQUIRED, "Param $fieldName is required.");
        }
        
        switch ($datatype) {
            case BOOLEAN:
                if(!is_bool($value)) {
                    $this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype of $fieldName should be a boolean.");
                }
                
                break;
            case INTEGER:
                if(!is_numeric($value)) {
                    $this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype of $fieldName should be an integer.");
                }
                
                break;
            case STRING:
                if(!is_string($value)) {
                    $this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype of $fieldName should be a string.");
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
    
    public function response($code, $data) {
        echo json_encode(['response' => ['status'=>$code, 'result'=>$data]]);
        exit;
    }
}