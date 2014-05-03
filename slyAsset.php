<?php
/**
 * @link http://www.frenzel.net/
 * @author Philipp Frenzel <philipp@frenzel.net> 
 */

namespace philippfrenzel\yii2sly;

use yii\web\AssetBundle;

class yii2imagesliderAsset extends AssetBundle
{
    public $sourcePath = '@philippfrenzel/yii2sly/assets';
    public $css = [];
    public $js = array(
        'js/sly.min.js'
    );
    public $depends = array(
        'yii\web\JqueryAsset',
    );
}
