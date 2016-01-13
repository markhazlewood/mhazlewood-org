<?php

class API
{
   /**
     * Property: method
     * The HTTP method this request was made in, either GET, POST, PUT or DELETE
     */
   protected $method = '';

   /**
     * Property: endpoint
     * The Model requested in the URI. eg: /files
     */
   protected $endpoint = '';

   /**
     * Property: verb
     * An optional additional descriptor about the endpoint, used for things that can
     * not be handled by the basic methods. eg: /files/process
     */
   protected $verb = '';

   /**
    * Property: args
    * Any additional URI components after the endpoint and verb have been removed, in our
    * case, an integer ID for the resource. eg: /<endpoint>/<verb>/<arg0>/<arg1>
    * or /<endpoint>/<arg0>
    */
   protected $args = Array();

   /**
     * Property: file
     * Stores the input of the PUT request
     */
   protected $file = Null;

   /**
     * Constructor: __construct
     * Allow for CORS, assemble and pre-process the data
     */
   public function __construct($request)
   {
      // Allow cross-origin resource sharing (CORS)
      header("Access-Control-Allow-Origin: *");
      header("Access-Control-Allow-Methods: *");

      // Specify that echo'd output will be in JSON format
      header("Content-Type: application/json");

      // trim any trailing slashes from the end of the request path
      $rawPath = rtrim($request, '/');

      // collect request path into an args array
      $this->args = explode('/', $rawPath);

      // pop the first element from args and assign it as $endpoint
      $this->endpoint = array_shift($this->args);

      // If there is another URI parameters, and it's not numeric
      // store is as the request $verb
      if (array_key_exists(0, $this->args) && !is_numeric($this->args[0]))
      {
         // pop first element and assign it as verb
         $this->verb = array_shift($this->args);
      }

      // Populate method, handling PUT and DELETE appropriately (they
      // are hidden inside POSTs using the HTTP_X_HTTP_METHOD header)
      $this->method = $_SERVER['REQUEST_METHOD'];
      if (  $this->method == 'POST' &&
            array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER))
      {
         if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE')
         {
            $this->method = 'DELETE';
         }
         else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT')
         {
            $this->method = 'PUT';
         }
         else
         {
            throw new Exception("Unexpected Header");
         }
       }

       // handle the request method by calling appropriate function(s)
       switch ($this->method)
       {
          case 'DELETE':
          case 'POST':
          {
             $this->request = $this->_cleanInputs($_POST);
             break;
          }
          case 'GET':
          {
             $this->request = $this->_cleanInputs($_GET);
             break;
          }
          case 'PUT':
          {
             $this->request = $this->_cleanInputs($_GET);
             $this->file = file_get_contents("php://input");
             break;
          }
          default:
          {
             $this->response('Invalid Method', 405);
             break;
          }
       }
   }

   /**
     * processAPI()
     *
     * Calls the concrete method defined by $endpoint, if it has been defined,
     * and returns the result. If a method to handle that endpoint doesn't exist,
     * returns 404.
     */
   public function processAPI()
   {
      if (method_exists($this, $this->endpoint))
      {
         return $this->_response($this->{$this->endpoint}($this->args));
      }
      return $this->_response("No Endpoint: $this->endpoint", 404);
   }

   /**
     * _response()
     *
     * Forms a JSON header and response based on data passed in
     */
   private function _response($data, $status = 200)
   {
      header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
      return json_encode($data);
   }

   private function _cleanInputs($data)
   {
      $clean_input = Array();
      if (is_array($data))
      {
         foreach ($data as $k => $v)
         {
            $clean_input[$k] = $this->_cleanInputs($v);
         }
      }
      else
      {
         $clean_input = trim(strip_tags($data));
      }
   }

   private function _requestStatus($code)
   {
      $status = array(
         200 => 'OK',
         404 => 'Not Found',
         405 => 'Method Not Allowed',
         500 => 'Internal Server Error'
      );

      return ($status[$code]) ? $status[$code] : $status[500];
   }

}

?>
