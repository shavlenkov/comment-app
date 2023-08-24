<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ImagesOrFiles implements Rule
{
    public function passes($attribute, $value)
    {
         if (strpos($value->getMimeType(), 'image') !== false) {
            $image = getimagesize($value->getRealPath());
            $width = $image[0];
            $height = $image[1];
            return $width >= 320 && $height >= 240;
        }

        if (strpos($value->getMimeType(), 'text') !== false) {
            $fileSize = $value->getSize();
            return $fileSize <= 100000; // 100 КБ
        }

        return false;
    }

    public function message() {}
}


?>
