<?php 

namespace App\Supports\ExtensionsTraitClass;

use App\Supports\Helper\Utils;
use Illuminate\Support\Traits\Macroable;

trait ThemeBlockExtensionTrait {
    use Macroable;

    public function getThumbPathTrait(){
        return public_path('assets-themes/'. $this->getTheme(). '/block-thumbs/' . $this->blockSlug . '/thumb.jpg');
    }
    
    public function getThumbUrlTrait(){
        return Utils::getasset('assets-themes/'. $this->getTheme() . '/block-thumbs/' . $this->blockSlug . '/thumb.jpg');
    }

    public function getTheme() {
        $themeFolder = explode('/', $this->theme->getFolder());
        return end($themeFolder);
    }
}