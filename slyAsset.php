<?php
/**
 * @link http://www.frenzel.net/
 * @author Philipp Frenzel <philipp@frenzel.net> 
 */

namespace philippfrenzel\yii2sly;

use yii\web\AssetBundle;

class slyAsset extends AssetBundle
{
    public $sourcePath = '@philippfrenzel/yii2sly/assets';
    public $css = [
        'css/sly.css'
    ];
    public $js = array(
        'js/sly.min.js'
    );
    public $depends = array(
        'yii\web\JqueryAsset',
    );
}
