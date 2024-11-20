<?php
include('UserModel.php');  // Incluir el modelo de usuarios
include('conexion.php');

/*************************************************++
 CORS
*****************************************************/
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: X-APIKEY,Origin,X-Requested-With, Content-Type, Accept,Access-Control-Request-Method,Access-Request-Headers,Authorization');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('content-type: application/json; charset=utf-8');
    header('HTTP/1.1 200 OK');
    die();
}

class UserController {

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        error_log("Método de solicitud: $method");  // Debugging message

        switch ($method) {
            case 'GET':
                $this->getUser();
                break;
            case 'POST':
                $this->createUser();
                break;  
            case 'PUT':
                $this->updateUser();
                break;
            case 'DELETE':
                $this->deleteUser();
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                echo json_encode(['mensaje' => 'Método no permitido']);
                break;
        }
    }

    private function getUser() {
        error_log("Iniciando GET de usuario");  // Debugging message
        $userModel = new UserModel();
        
        if (isset($_GET['id'])) {
            $result = $userModel->getUserById($_GET['id']);
            echo json_encode($result);
            error_log("Resultado de GET por ID: " . print_r($result, true));  // Debugging message
        } else {
            $result = $userModel->getAllUsers();
            echo json_encode($result);
            error_log("Resultado de GET de todos los usuarios: " . print_r($result, true));  // Debugging message
        }
    }

    private function createUser() {
        error_log("Iniciando POST de usuario");  // Debugging message
        $userModel = new UserModel();
        $params = json_decode(file_get_contents("php://input"));

        if (empty($params->username) || empty($params->password)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['mensaje' => 'Faltan datos obligatorios.']);
            error_log("Datos incompletos recibidos para crear usuario: " . print_r($params, true));  // Debugging message
            exit;
        }

        $result = $userModel->createUser($params->username, $params->password);
        echo json_encode($result);
        error_log("Resultado de POST crear usuario: " . print_r($result, true));  // Debugging message
    }

    private function updateUser() {
        error_log("Iniciando PUT de usuario");  // Debugging message
        $userModel = new UserModel();
        $params = json_decode(file_get_contents("php://input"));
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['mensaje' => 'El ID es requerido para actualizar el registro.']);
            error_log("ID no proporcionado para actualizar usuario.");  // Debugging message
            exit;
        }

        if (empty($params->username) || empty($params->password)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['mensaje' => 'Datos incompletos. email y password son requeridos.']);
            error_log("Datos incompletos recibidos para actualizar usuario: " . print_r($params, true));  // Debugging message
            exit;
        }

        $result = $userModel->updateUser($id, $params->username, $params->password);
        echo json_encode($result);
        error_log("Resultado de PUT actualizar usuario: " . print_r($result, true));  // Debugging message
    }

    private function deleteUser() {
        error_log("Iniciando DELETE de usuario");  // Debugging message
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['mensaje' => 'El ID es obligatorio para eliminar un registro.']);
            error_log("ID no proporcionado para eliminar usuario.");  // Debugging message
            exit;
        }

        $userModel = new UserModel();
        $result = $userModel->deleteUser($_GET['id']);
        echo json_encode($result);
        error_log("Resultado de DELETE eliminar usuario: " . print_r($result, true));  // Debugging message
    }
}

$controller = new UserController();
$controller->handleRequest();
