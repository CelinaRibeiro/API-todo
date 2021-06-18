<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::get('/ping', function() {
    return ['pong' => true];
});

Route::get('/unauthenticated', function() {
    return ['error' => 'Usuário não logado!'];
})->name('login');

Route::post('/user', [AuthController::class, 'create']);
Route::middleware('auth:sanctum')->get('/auth/logout', [AuthController::class, 'logout']);
Route::post('/auth', [AuthController::class, 'login']);

//POST /todo = Inserir uma tarefa no sistema
//GET /todos = Ler todas as tarefas no sistema
//GET /todo/2 = Ler uma tarefas específica no sistema
//PUT /todo/2 = atualizar uma tarefa no sistema
//DELETE /todo/2 = deletar uma tarefa no sistema

Route::middleware('auth:sanctum')->post('/todo', [ApiController::class, 'createTodo']); //rota para user logado
Route::get('/todos', [ApiController::class, 'readAllTodos']);
Route::get('/todo/{id}', [ApiController::class, 'readTodo']);
Route::middleware('auth:sanctum')->put('/todo/{id}', [ApiController::class, 'updateTodo']);
Route::middleware('auth:sanctum')->delete('/todo/{id}', [ApiController::class, 'deleteTodo']);