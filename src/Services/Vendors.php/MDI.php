<?php

namespace Ympact\FluxIcons\Services\Vendors;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class MDI
{
    /**
     * 
     * @param int $size size of the resource icon
     * @return string
     */
    public static function outlineFilter($file, array &$icons = null){
        // if the icon name ends with -outline it is an outline icon
        $filename = pathinfo($file, PATHINFO_FILENAME);
        if (Str::contains($filename, '-outline')) {
            return true;
        }
        // if there is an -outline variant of the current icon, then the icon is solid and we return false
        // insert -outline before .svg extension to $file and check if this file exists
        if(File::exists(Str::of($file)->before('.svg') . '-outline.svg')){
            // if there is an outline variant of the icon, in case $icons is passed, we add the icon to the icons array and remove $filename from it
            if(in_array( $filename, $icons)){
                $key = array_search($filename, $icons);
                unset($icons[$key]);
                $icons[] = $filename.'-outline';
            }
            return false;
        };
        return true;
    }

    /**
     * 
     * @param int $size size of the resource icon
     * @return string
     */
    public static function solidFilter($file){
        $filename = pathinfo($file, PATHINFO_FILENAME);
        if (Str::contains($filename,'-outline')) {
            return false;
        }
        return File::exists(Str::of($file)->before('.svg') . '-outline.svg') ? true : false;
    }
}