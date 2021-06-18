<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Todo;

class ApiController extends Controller
{
    //criar tarefa
    public function createTodo(Request $request) {
        $array = ['error' => ''];

        //regras para validação
        $rules = [
            'title' => 'required|min:3'
        ];

        //passa o validador do validator
        $validator = Validator::make($request->all(), $rules);

        //se der algum problema
        if($validator->fails()) {
            $array['error'] = $validator->messages();
            return $array;
        }

        //pega os campos com request
        $title = $request->input('title');

        //insere informações no bd
        $todo = new Todo; //cria nova instancia
        $todo->title = $title; //preenche
        $todo->save(); //salva

        return $array;
    }

    //ler todas as tarefas
    public function readAllTodos() {
        $array = ['error' => ''];
        
       //com paaginação
       $todos = Todo::simplePaginate(2);

       $array['list'] = $todos->items();
       $array['current_page'] = $todos->currentPage();

        return $array;
    }

    //ler uma única tarefa
    public function readTodo($id) {
        $array = ['error', ''];

        $todo = Todo::find($id);

        if($todo) {
            $array['todo'] = $todo;
        } else {
            $array['error'] = 'A tarefa '.$id.' não existe';
        }

        return $array;
    }

    //atualizar tarefa
    public function updateTodo(Request $request, $id) {
        $array = ['error', ''];

        //1º - Faz a validação 
        //regras para validação
        $rules = [
            'title' => 'min:3',
            'done' => 'boolean'
        ];

        //passa o validador do validator
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            $array['error'] = $validator->messages();
            return $array;
        } 

        //pega os campos com request
        $title = $request->input('title');
        $done = $request->input('done');

        //2º - Faz a atualização do item 
        $todo = Todo::find($id);
        if($todo) {

            if($title) {
                $todo->title = $title;
            }
            if($done !== NULL) {
                $todo->done = $done;
            }

            $todo->save();

        } else {
            $array['error'] = 'Tarefa '.$id.' não existe, logo, não pode ser atualizada!';
        }

        return $array;
    }

    //deletar tarefas
    public function deleteTodo($id) {
        $array = ['error', ''];

        $todo = Todo::find($id);
        $todo->delete();

        return $array;
    }
}
