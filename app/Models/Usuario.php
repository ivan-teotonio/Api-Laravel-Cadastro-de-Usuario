<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $fillable= [
        'nome',
        'email',
        'phone',
        'password',
        'photo'
    ];

    public function rules(){
        return [
            'nome' => 'required',
            'email' => "required|unique:usuarios,email,'.$this->id.'",
            'photo' => 'file|mimes:png,jpeg'
        ];
    }

    public function feedback(){
       return [
           'require' => 'O campo :attribute é obrigatório',
           'email.unique' => 'E-mail já cadastrado',
           'photo.mimes' => 'Arquivo dever ser do tipo png ou jpeg'
        ];
    }
}
