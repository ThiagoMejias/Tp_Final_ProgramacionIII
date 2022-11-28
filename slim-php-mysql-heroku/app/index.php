<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';

require_once './middlewares/AutentificadorJWT.php';

require_once './middlewares/checkPerfil.php';
require_once './middlewares/checkParams.php';
require_once './middlewares/checkParamsMesa.php';
require_once './middlewares/checkParamsProducto.php';
require_once './middlewares/checkParamsPedido.php';
require_once './middlewares/CheckToken.php';
require_once './middlewares/CheckTokenSocio.php';
require_once './middlewares/CheckTokenMozo.php';
require_once './middlewares/checkEstadosProductosPedidos.php';
require_once './middlewares/checkEstadosMesas.php';










require_once './controllers/UsuarioController.php';
require_once './controllers/MesaController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidosController.php';
require_once './controllers/ProductoPedidoController.php';



// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {


  $group->post('/login', \UsuarioController::class . ':login')->add(new CheckParams());
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  $group->post('[/]', \UsuarioController::class . ':CargarUno')->add(new CheckTokenSocio())->add(new CheckPerfil())->add(new CheckParams());
});

$app->group('/mesas', function (RouteCollectorProxy $group) {

  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->post('[/]', \MesaController::class . ':CargarUno')->add(new CheckTokenSocio())->add(new CheckParamsMesa());
  $group->put('[/]', \MesaController::class . ':modificarUno')->add(new checkEstadosMesas())->add(new CheckTokenMozo())->add(new CheckParamsMesa());
});
$app->group('/productos', function (RouteCollectorProxy $group) {

  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->post('/generarCsv', \ProductoController::class . ':generarCsv');
  $group->post('/cargarCsv', \ProductoController::class . ':cargarCsv');
  $group->post('[/]', \ProductoController::class . ':CargarUno')->add(new CheckTokenSocio())->add(new CheckPerfil())->add(new CheckParamsProducto());
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidosController::class . ':TraerTodos')->add(new CheckTokenSocio());
  $group->post('[/]', \PedidosController::class . ':CargarUno')->add(new CheckTokenMozo())->add(new CheckParamsPedido());
  $group->post('/productos', \PedidosController::class . ':CargarProductos')->add(new CheckTokenMozo());
  $group->get('/{idMesa}/{idPedido}', \PedidosController::class . ':obtenerDemora');
  $group->get('/obtenerCuenta/{idMesa}/{idPedido}', \PedidosController::class . ':obtenerCuenta')->add(new CheckTokenMozo());
  $group->post('/setImg', \PedidosController::class . ':CargarFoto')->add(new CheckTokenMozo());
});

$app->group('/productosPedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoPedidoController::class . ':TraerTodos')->add(new CheckToken());
  $group->put('[/]', \ProductoPedidoController::class . ':modificarUno')->add(new CheckToken())->add(new CheckEstadosProductosPedidos());
});



$app->get('[/]', function (Request $request, Response $response) {
  $payload = json_encode(array("mensaje" => "Slim Framework 4 PHP"));

  $response->getBody()->write($payload);
  return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
