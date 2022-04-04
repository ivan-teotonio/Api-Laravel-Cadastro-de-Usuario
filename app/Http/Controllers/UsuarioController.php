<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Usuario;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    
   public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario;
    } 
  
    public function index()
    {
        $usuario = $this->usuario->all();
        return response()->json($usuario,200);
        
    } 

  
    public function store(Request $request)
    {
       // $user = Usuario::create($request->all());
       $request->validate($this->usuario->rules(),$this->usuario->feedback());

       $photo = $request->file('photo');
       $photo_urn = $photo->store('imagens','public');
       $usuario = $this->usuario->create([
           'nome' => $request->nome,
           'email' => $request->email,
           'phone' => $request->phone,
           'password' => $request->password,
           'photo' => $photo_urn,

       ]);
      // $usuario = $this->usuario->create($request->all());
       return response()->json($usuario,201);
    } 

  
   public function show($id)
    {
        $usuario = $this->usuario->find($id);
        if($usuario === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        return response()->json($usuario,200);
    } 

   

   
    public function update(Request $request, $id)
    {
       $usuario = $this->usuario->find($id);

       ///dd($request->file('photo'));

       if($usuario === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
       }

       if($request->method() === 'PATCH'){
            $regrasDinamicas = array();
            foreach($usuario->rules() as $input => $regra){
                if(array_key_exists($input,$request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas,$usuario->feedback());
       }else{
            $request->validate($usuario->rules(),$usuario->feedback());
       }

       if($request->file('photo')){
           Storage::disk('public')->delete($usuario->photo);
       }

       $photo = $request->file('photo');
       $photo_urn = $photo->store('imagens','public');

       $usuario->update([
           'nome' => $request->nome,
           'email' => $request->email,
           'phone' => $request->phone,
           'password' => $request->password,
           'photo' => $photo_urn,

       ]);

      

       //$usuario->update($request->all());
       return response()->json($usuario,200);
    } 

   
    public function destroy($id)
    {
        $usuario = $this->usuario->find($id);
        if($usuario === null){
            return response()->json(['erro' => 'impossivél realizar a exclusão. o recurso solicitado não existe'],404);
        }

        
        Storage::disk('public')->delete($usuario->photo);
        

        $usuario->delete();
        return response()->json(['msg' => 'A marca foi removida com sucesso']);
    } 

 
}
