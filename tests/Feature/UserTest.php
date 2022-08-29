<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{

    /** Casos de test
     * Registro con datos incorrectos
     * Registro con datos correctos
     * Segundo intento de registro con los mismos datos
     * 
     * Intento de acceso a lista de fotos sin inicio de sesión
     * Login con credenciales erróneas
     * Login correcto
     * 
     * Cierre de sesión
     */


    public function test_register()
    {

        //Migrar la base de datos
        Artisan::call('migrate');

        //El formulario carga
        $carga = $this->get(route('register'));
        $carga->assertStatus(200)->assertSee('Registrarse');

        //Intento de registro con datos que no cumplen los criterios
        $registroMal = $this->post(route('do-register'),['email'=>"aaa",'password'=>"123"]);
        $registroMal->assertStatus(302)->assertRedirect(route('register'))->assertSessionHasErrors([
                'email' => __('validation.email',['attribute'=>'email']),
                'name' => __('validation.required',['attribute'=>'name']),
                'password' => __('validation.min.string',['attribute'=>'password','min'=>6])
        ]);

        
        //Registro con datos correctos
        $registroBien = $this->post(route('do-register'),['email'=>"test@testing.es",'password'=>"Password1","name"=>"Testing"]);
        $registroBien->assertStatus(302)->assertRedirect(route('home'));
        $this->assertDatabaseHas('users',['email'=>"test@testing.es"]);
        
        //Repetir registro con los mismos datos
        $registroMal = $this->post(route('do-register'),['email'=>"test@testing.es",'password'=>"Password1","name"=>"Testing"]);
        $registroMal->assertStatus(302)->assertRedirect(route('register'))->assertSessionHasErrors([
                'email' => __('validation.unique',['attribute'=>'email']),
                'name' => __('validation.unique',['attribute'=>'name'])
        ]);
    }

    public function test_login(){
        //Migrar la base de datos
        Artisan::call('migrate');
        //Cargar un usuario
        User::create(["name"=>"Test","email"=>"test@testing.es","password"=>Hash::make('Password1')]);
        //El formulario carga
        $carga = $this->get(route('login'));
        $carga->assertStatus(200)->assertSee('Iniciar sesión');
        //Intento de acceso no autorizado
        $accesoMal = $this->get(route('home'));
        $accesoMal->assertStatus(302)->assertRedirect(route('login'));
        //Error en credenciales
        $credencialesMal = $this->post(route('do-login'),["name"=>"Test","password"=>"No es"]);
        $credencialesMal->assertStatus(302)->assertRedirect(route('login'))->assertSessionHasErrors([
                'name' => 'Las credenciales introducidas no son correctas.'
        ]);

        //Acceso correcto
        $accesoBien = $this->post(route('do-login'),["name"=>"Test","password"=>"Password1"]);
        $accesoBien->assertStatus(302)->assertRedirect(route('home'));
        //Vista del listado de fotos
        $listado = $this->get(route('home'));
        $listado->assertStatus(200)->assertSee('Las fotos de Test');
        //Logout
        $logout = $this->get('/logout');
        $logout->assertStatus(302)->assertRedirect(route('login'));
    }
}
