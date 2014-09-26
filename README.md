yii2sly
=======

This extension is a wrapper for the amazing jquery slider "sly" which can be found here:

http://darsa.in/sly/

Pls. take a closer look at all the plugin options, which can be passed over by adding them to
the "clientOptions" parameter as shown below.

A demo of the extension can be found here:

http://yii2fullcalendar.beeye.org/index.php/site/sly

Installation
============

Add this to your composer.json require section

```json
  "philippfrenzel/yii2sly": "*",
```

And finaly the view should look like this:

```php

<?php

use yii\helpers\Url;

?>

<h1><?php echo Html::encode($this->title); ?></h1>

<?= philippfrenzel\yii2sly\yii2sly::widget([
    'id' => 'sp_slider',
    'items'=> [
        ['content' => '<img src="' . Url::to('@web/img/sportster_forty-eight_sly.jpg') . '"></img>'],
        ['content' => '<img src="' . Url::to('@web/img/softtail_heritage-classic_sly.jpg') . '"></img>'],
        ['content' => '<img src="' . Url::to('@web/img/dyna_wide-glide_sly.jpg') . '"></img>'],
        ['content' => '<img src="' . Url::to('@web/img/cvo_limited_sly.jpg') . '"></img>'],
        ['content' => '<img src="' . Url::to('@web/img/v-rod_muscle_sly.jpg') . '"></img>']
    ],
    'options' => [
        'style' => "height:400px;"
    ],
    'clientOptions' => [
        'horizontal' => 1,
        'activateMiddle' => 1,
        ...
    ]
]); ?>

```

Currently it cause issues with having multiple sly's on one page, but we know this issue and are working on it.