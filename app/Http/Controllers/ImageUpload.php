<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageUpload extends Controller
{
    public static function uploadImage($file,$path = 'uploads/images',$fileName = null,$disk = 'public')
    {
           if(!$file || !$file->isValid()){
             return null;
           }
           $fileName = $fileName ?? time().
           '_'.uniqid().'.'.$file->getClientOriginalExtension();
           $imagePath = $file->storeAs($path,$fileName,$disk);

              return $imagePath;


    }
}
