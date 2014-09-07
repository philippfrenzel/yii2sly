<?php

 /**
 * yii2 extension for the amazing jQuery Plugin: http://darsa.in/sly/
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
     * [
     *     // required, slide content (HTML), such as an image tag
     *     'content' => '<img src="http://twitter.github.io/bootstrap/assets/img/bootstrap-mdo-sfmoma-01.jpg"/>',
     * ]
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
     * one per frame item or all items in a row
     * @var boolean
     */
    public $onePerFrame = NULL;


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
        'horizontal' => 1,
        'smart' => 1,
        'scrollBy' => 1,
        'itemNav' => 'forceCentered',
        'activateMiddle'=> '1',
        'mouseDragging'=> '1',
        'touchDragging'=> '1',
        'releaseSwing'=> '1',
        'startAt'=> '0',
        'speed'=> '300',
        'elasticBounds'=> '1',
        'easing' => 'easeOutExpo',
        'dragHandle' => 1,
        'clickBar' => 1
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
        
        if (!isset($this->clientOptions['scrollBar'])) {
            $this->clientOptions['scrollBar'] = '#'.$this->getId().'scrollbar';
        }
        
        /*if (!isset($this->clientOptions['pagesBar'])) {
            $this->clientOptions['pagesBar'] = '#'.$this->getId().'controlls';
        }*/
        
        if (!isset($this->clientOptions['scrollSource'])) {
            $this->clientOptions['scrollSource'] = '#'.$this->getId();
        }
        
        //navigation bar
        if (!isset($this->clientOptions['next']))
        {            
            $this->clientOptions['next'] = '#'.$this->options['id'].'btn_next';
        }
        
        if (!isset($this->clientOptions['prev']))
        {            
            $this->clientOptions['prev'] = '#'.$this->options['id'].'btn_prev';
        }

        if(!is_null($this->onePerFrame))
        {
            Html::addCssClass($this->options,'oneperframe');
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo $this->renderControls('begin') . "\n";
        echo Html::beginTag('div', $this->options) . "\n";            
            echo $this->renderItems() . "\n";        
        echo Html::endTag('div') . "\n";
        echo $this->renderControls('end') . "\n";
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
        return Html::tag('ul',implode("\n", $items), ['class'=>'slidee']);
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

        if($index==0){
            return Html::tag('li', $content,['class'=>'active']);
        }
        else
        {
            return Html::tag('li', $content);
        }        
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
            return "<div id='".$this->options['id']."scrollbar' class='scrollbar'>".$scroller."</div>\n";
        } 
        else {
            $nav = "";
            $nav .= Html::tag('button','prev',['id'=>$this->options['id'].'btn_prev','class'=>'btn btn-default pull-left']);
            $nav .= Html::tag('button','next',['id'=>$this->options['id'].'btn_next','class'=>'btn btn-default pull-right']);
            return "<div id='".$this->options['id']."controlls' class='controls'>".$nav."</div>\n";
        }
    }

}
