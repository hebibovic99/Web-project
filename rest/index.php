<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS, post, get');
header("Access-Control-Max-Age", "3600");
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
header("Access-Control-Allow-Credentials", "true");

require_once 'vendor/autoload.php';

require_once 'Config.class.php';

require_once './routes/UserRoutes.php';
require_once './routes/StudentsRoutes.php';
require_once './routes/ContactRoutes.php';
require_once './routes/DictionaryRoutes.php';
require_once './routes/StudentInfoRoutes.php';

use Firebase\JWT\JWT;

// middleware method for login
Flight::route('/*', function(){
    // perform JWT decode
    $path = Flight::request()->url;
    if ($path == '/login' || $path == '/docs.json') {
        return true;
    }
  
    $headers = getallheaders();
    if (!isset($headers['Authorization']) || empty($headers['Authorization'])) {
        Flight::json(["message" => "Authorization is missing"], 403);
        return false;
    } else {
        try {
            $decoded = (array) JWT::decode($headers['Authorization'], Config::JWT_SECRET(), ['HS256']);
            Flight::set('user', $decoded);
            return true;
        } catch (\Exception $e) {
            Flight::json(["message" => "Authorization token is not valid"], 403);
            return false;
        }
    }
});

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit;
}

/* REST API documentation endpoint */
Flight::route('GET /docs.json', function(){
    $openapi = \OpenApi\scan('routes');
    header('Content-Type: application/json');
    echo $openapi->toJson();
  });

  
Flight::start();

?>