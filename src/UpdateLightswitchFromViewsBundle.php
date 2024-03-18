<?php

namespace modules\updatelightswitchfromviews;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class UpdateLightswitchFromViewsBundle extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = '@updatelightswitchfromviews/resources'; // Path to your resources
        $this->depends = [CpAsset::class]; // Depend on CP assets

        $this->js = [
            //'jquery-3.7.0.min.js',
            'app.js'
        ];
        $this->css = [
            'app.css'
        ];

        parent::init();
    }
}
