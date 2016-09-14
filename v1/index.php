<?php

/*
ver maiores detalhes em http://www.androidhive.info/2014/01/how-to-create-rest-api-for-android-app-using-php-slim-and-mysql-day-23/
Parte deste webservice se baseou na estrutura criada por  Ravi Tamada.
Algumas funcionalidades foram otimizadas para melhor funcionamento em conjunto com o app android
*/

//require_once './../include/DBHandler.php';
//require_once '../include/PassHash.php';
//require_once '../include/Utils.php';
require_once '../include/classes.php';
require '../libs/Slim/Slim/Slim.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=utf-8');

//teste do SLIM
$app->get('/', function (){
    echo "SlimFramework Funcionando";
});


// Variável global. Vem do DB.
$user_id = NULL;

//removida function verifyRequiredParams($required_fields)


/**
 * Imprime a resposta pro cliente no formato json
 * @param int $status_code Codigo de resposta HTTP
 * @param String $response - Array com a resposta
 */
function echoResponse($status_code, $response) {
    $app = \Slim\Slim::getInstance();

    $app->status($status_code);
    $app->contentType('application/json');

    echo json_encode($response);
}

/**
 * Registro de usuario
 * url - /register
 * method - POST
 * @param - login, email, password
 */
$app->post('/register', function() use ($app) {

            $request = \Slim\Slim::getInstance()->request();
            $user = json_decode($request->getBody());

            $response = array();

            /*
            $db = new DbHandler();
            $res = $db->createUser($user->login, $user->email, $user->senha);

            if (is_array($res)) {
                //na resposta retorna o id do usuário e a sua api_key para poder usar as funções do app
                $response["error"] = false;
                $response["message"] = "Cadastro efetuado com sucesso";
                $response["inserted_id"] = $res["inserted_id"];
                $response["api_key"] = $res["api_key"];
                echoResponse(201, $response);
            } else if ($res == USER_CREATE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! Ocorreu um erro durante o registro";
                echoResponse(200, $response);
            } else if ($res == USER_ALREADY_EXIST) {
                $response["error"] = true;
                $response["message"] = "Desculpe, este email já existe";
                echoResponse(200, $response);
            }
            */
        });



/**
 * Login de usuario
 * url - /login
 * method - POST
 * params - email, password
 */
$app->post('/login', function() use ($app) {

            $request = \Slim\Slim::getInstance()->request();
            $body = json_decode($request->getBody());
           // var_dump($request);

            $response = array();

            $usuario = new Usuario($body->email, $body->senha);
            // verifica se o email e senha conferem com os dados no BD
            if ($usuario->verificaLogin()) {
                
                    $response['error'] = false;
                    $response['id'] = 1;
                    $response['nomeCompleto'] = "Pokemon PDM Master";
                    $response['api_key'] = md5("hash");
                    //$response['foto_usuario'] = "pathPhoto";
                
            } else {
                // Erro de credenciais
                $response['error'] = true;
                $response['message'] = 'Login falhou. Credenciais incorretas';
            }

            echoResponse(200, $response);
        });



/**
 * Dados do usuario
 * url - /usuario
 * method - POST
 * params - id do usuario
 */
$app->get('/usuario/:id', 'authenticate', function($id) {

            $response = array();
            $db = new DbHandler();

            // busca as informacoes do usuario pelo email
            $user = $db->getUserData($id);

            if ($user != NULL) {
                //monta a array para retornar os dados ao client
                $response['error'] = false;
                //$response['id'] = $user['id'];
                //$response['login'] = $user['login'];
                //$response['api_key'] = $user['api_key'];
                $response['nomeCompleto'] = $user['nomeCompleto'];
                $response['email'] = $user['email'];
                $response['foto_usuario'] = $user["foto"];
            } else {
                // Erro desconhecido
                $response['error'] = true;
                $response['message'] = "Ocorreu um erro inesperado. Por favor, tente novamente";
            }

            echoResponse(200, $response);
        });



/**
 * Funcionalidade otimizada por questoes de seguranca. O metodo original verificava apenas se a api_key era valida
 * fazendo uma consulta ao banco, contudo, a api_key poderia ser quebrada, sem a necessidade de verificar se ela
 * pertencia ao usuario ou nao.
 *
 * Cria uma camada intermediaria para qualquer request que necessite de autenticacao.
 * verfica se os headers 'Authorization' e 'id' sao validos
 */
function authenticate(\Slim\Route $route) {
    // Pega os headers

    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();

    if (isset($headers['Authorization']) && isset($headers['User_id'])) {
        $db = new DbHandler();

        // pega a api_key e o $id setados no header
        $api_key = $headers['Authorization'];
        $id = $headers['User_id'];
        // validando a api key
        if (!$db->isValidApiKey($api_key, $id)) {
            // erro. A api key nao bate com o id do usuario
            $response["error"] = true;
            $response["message"] = "Acesso negado. Api key inválida";
            echoResponse(401, $response);
            $app->stop();
        } else {
            global $user_id;
            $user_id = $id;
        }
    } else {
        // nao foi encontrada a api key no header
        $response["error"] = true;
        $response["message"] = "Api key não encontrada no header";
        echoResponse(400, $response);
        $app->stop();
    }

}



$app->run();



?>