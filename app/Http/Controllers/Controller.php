<?php
/*
Copyright (c) 2015, Purvesh
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
and/or other materials provided with the distribution.

* Neither the name of Mage2 nor the names of its
  contributors may be used to endorse or promote products derived from
  this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
    OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
namespace App\Http\Controllers;

use App\Admin\ProductsTextValue;
use App\Admin\ProductsVarcharValue;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public function getAttributeValueModel($attribute) {
        if($attribute->type == "textarea") {
            return new ProductsTextValue();
        }
        if($attribute->type == "text" || $attribute->type == "select" || $attribute->type == "radio" || $attribute->type == "checkbox") {
            return new ProductsVarcharValue();
        }

        return false;
    }


    /*
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile   $image
     *
     */
    public function uploadImage(UploadedFile $image, $for = 'products')
    {

        $random = implode('/', str_split(substr(str_shuffle(implode("", range('a', 'z'))), -3)));


        $path = base_path() . '/public/images/catalog/' . $for . "/" . $random . "/";
        $relativePath = '/public/images/catalog/' . $for . "/" . $random . "/";
        //Upload Images
        $imageName = $image->getClientOriginalName();
        $image->move($path, $imageName);

        return $relativePath . $imageName;

    }
}
