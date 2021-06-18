<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AuthController extends Controller
{
    //criar user
    public function create(Request $request) {
        $array = ['error' => ''];

        //regras para validação
        $rules = [
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ];

        //passa o validador do validator
        $validator = Validator::make($request->all(), $rules);

        //se der algum problema
        if($validator->fails()) {
            $array['error'] = $validator->messages();
            return $array;
        }

        //pega os campos com request
        $email = $request->input('email');
        $password = $request->input('password');

        //Criando novo user
        $newUser = new User(); //cria nova instancia
        $newUser->email = $email; //preenche
        $newUser->password = password_hash($password, PASSWORD_DEFAULT); 
        //$newUser->token = ''; //token inicia como vazio - erro com
        $newUser->save(); //salva

        //logar o user recem criado

        return $array;
    }

    //fazer o processo de login
    public function login(Request $request) {
        $array = ['error' => ''];

        //pego os campos email e senha
        $creds = $request->only('email', 'password');

        //mando as credenciais
        if(Auth::attempt($creds)) {
            //pega o usuario para gerar o token para manter logado
            $user = User::where('email', $creds['email'])->first();

            //cria token
            $item = time().rand(0,9999);
            $token = $user->createToken($item)->plainTextToken;

            //retorna o token para o sistema ou app
            $array['token'] = $token;

        } else {
            $array['error'] = 'E-mail e/ou senha incorretos.';
        }

        return $array;
    }

    public function logout(Request $request) {
        $array = ['error' => ''];

        $user = $request->user();
        $user->tokens()->delete();

        return $array;
    }
}
