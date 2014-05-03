<?php

 /**
 * This class is merely used to publish a TOC based upon the headings within a defined container
 * @copyright Frenzel GmbH - www.frenzel.net
 * @link http://www.frenzel.net
 * @author Philipp Frenzel <philipp@frenzel.net>
 *
 */

namespace philippfrenzel\yii2sly;

use Yii;

use yii\base\Model;
use yii\web\View;
use yii\base\InvalidConfigException;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

use yii\base\Widget as Widget;

class yii2sly extends Widget
{

    /**
     * @var array list of slides in the imageslider. Each array element represents a single
     * slide with the following structure:
     *
     * ```php
     * array(
     *     // required, slide content (HTML), such as an image tag
     *     'content' => '<img src="http://twitter.github.io/bootstrap/assets/img/bootstrap-mdo-sfmoma-01.jpg"/>',
     *     // optional, the caption (HTML) of the slide
     *     'caption' => '<h4>This is title</h4><p>This is the caption text</p>',
     *     // optional the HTML attributes of the slide container
     *     'options' => array(),
     * )
     * ```
     */
    public $items = array();

    /**
    * @var array the HTML attributes (name-value pairs) for the field container tag.
    * The values will be HTML-encoded using [[Html::encode()]].
    * If a value is null, the corresponding attribute will not be rendered.
    */
    public $options = array(
        'class' => 'frame',
    );


    /**
    * can contain all configuration options
    * @var array all attributes that be accepted by the plugin, check docs!
    * visible_items
    * scrolling_items
    * orientation
    * circular
    * autoscroll
    * interval
    * direction
    */
    public $clientOptions = array(
    );

    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();
        //checks for the element id
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo Html::beginTag('div', $this->options) . "\n";
            echo $this->renderControls('begin') . "\n";
            echo $this->renderItems() . "\n";        
            //echo $this->renderControls('end') . "\n";
        echo Html::endTag('div') . "\n";
        $this->registerPlugin();
    }

    /**
    * Registers a specific yii2sly widget and the related events
    * @param string $name the name of the yii2sly plugin
    */
    protected function registerPlugin()
    {
        $id = $this->options['id'];
        //get the displayed view and register the needed assets
        $view = $this->getView();
        slyAsset::register($view);

        $js = array();
        $className = $this->options['class'];
        $options = empty($this->clientOptions) ? '' : Json::encode($this->clientOptions);
        $js[] = "var sly$id = new Sly('#$id',$options).init();";
        
        $view->registerJs(implode("\n", $js),View::POS_READY);
    }

    /**
     * Renders carousel items as specified on [[items]].
     * @return string the rendering result
     */
    public function renderItems()
    {
        $items = array();
        for ($i = 0, $count = count($this->items); $i < $count; $i++) {
            $items[] = $this->renderItem($this->items[$i], $i);
        }
        return Html::tag('div', implode("\n", $items));
    }

    /**
     * Renders a single carousel item
     * @param string|array $item a single item from [[items]]
     * @param integer $index the item index as the first item should be set to `active`
     * @return string the rendering result
     * @throws InvalidConfigException if the item is invalid
     */
    public function renderItem($item, $index)
    {
        if (is_string($item)) {
            $content = $item;
        } elseif (isset($item['content'])) {
            $content = $item['content'];            
        } else {
            throw new InvalidConfigException('The "content" option is required.');
        }
        return Html::tag('div', $content, ['class'=>'slidee']);
    }

    /**
     * Renders previous and next control buttons.
     * @throws InvalidConfigException if [[controls]] is invalid.
     */
    public function renderControls($position='begin')
    {
        if ($position === 'begin') 
        {
            $scroller = Html::tag('div',' ',array(
                'class' => 'handle'
            ));
            return  "<div class='scrollbar'>".$scroller."</div>\n";
        } 
        /*else {
            //<span class="als-next"><img src="images/thin_right_arrow_333.png" alt="next" title="next" /></span>
            $icon = Html::Tag('i',' ',array(
                        'class' => 'fa fa-arrow-right fa-3',
                        'title' => 'next',
                    ));
            return  "<span class='als-next'>".$icon."</span>\n";
        }*/
    }

}
