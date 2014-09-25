<?php

 /**
 * yii2 extension for the amazing jQuery Plugin: http://darsa.in/sly/
 * @version 0.9 (beta)
 * @copyright Frenzel GmbH - www.frenzel.net
 * @link http://www.frenzel.net
 * @author Philipp Frenzel <philipp@frenzel.net>
 */

namespace philippfrenzel\yii2sly;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

class yii2sly extends \yii\base\Widget
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
    public $items = [];

    /**
    * @var array the HTML attributes (name-value pairs) for the field container tag.
    * The values will be HTML-encoded using [[Html::encode()]].
    * If a value is null, the corresponding attribute will not be rendered.
    */
    public $options = [];

    /**
     * one per frame item or all items in a row
     * @var boolean
     */
    public $onePerFrame = NULL;

    /**
     * set the orientation for the slides
     * @var string horizontal|vertical
     */
    public $orientation = 'horizontal';

    /**
     * can contain all configuration options
     * @var array all attributes that be accepted by the plugin, check docs!
     * @param visible_items
     * @param scrolling_items
     * @param orientation
     * @param circular
     * @param autoscroll
     * @param interval
     * @param direction
     */
    public $clientOptions = array(
        'horizontal' => 1, // Switch to horizontal mode.

        // Item based navigation
        'itemNav' =>      'centered', // Item navigation type. Can be' => 'basic', 'centered', 'forceCentered'.
        //'itemSelector' => null, // Select only items that match this selector.
        'smart' =>        0,    // Repositions the activated item to help with further navigation.
        //'activateOn' =>   null, // Activate an item on this event. Can be' => 'click', 'mouseenter', ...
        //'activateMiddle' => 0,  // Always activate the item in the middle of the FRAME. forceCentered only.

        // Scrolling
        //'scrollSource' => null, // Element for catching the mouse wheel scrolling. Default is FRAME.
        'scrollBy' =>     0,    // Pixels or items to move per one mouse scroll. 0 to disable scrolling.
        'scrollHijack' => 300,  // Milliseconds since last wheel event after which it is acceptable to hijack global scroll.

        // Dragging
        //'dragSource' =>    null, // Selector or DOM element for catching dragging events. Default is FRAME.
        'mouseDragging' => 1,    // Enable navigation by dragging the SLIDEE with mouse cursor.
        'touchDragging' => 1,    // Enable navigation by dragging the SLIDEE with touch events.
        'releaseSwing' =>  1,    // Ease out on dragging swing release.
        'swingSpeed' =>    0.2,  // Swing synchronization speed, where' => 1 = instant, 0 = infinite.
        'elasticBounds' => 0,    // Stretch SLIDEE position limits when dragging past FRAME boundaries.
        //'interactive' =>   null, // Selector for special interactive elements.

        // Scrollbar
        //'scrollBar' =>     null, // Selector or DOM element for scrollbar container.
        'dragHandle' =>    0,    // Whether the scrollbar handle should be draggable.
        'dynamicHandle' => 0,    // Scrollbar handle represents the ratio between hidden and visible content.
        'minHandleSize' => 50,   // Minimal height or width (depends on sly direction) of a handle in pixels.
        'clickBar' =>      0,    // Enable navigation by clicking on scrollbar.
        'syncSpeed' =>     0.5,  // Handle => SLIDEE synchronization speed, where' => 1 = instant, 0 = infinite.

        // Pagesbar
        //'pagesBar' =>       null, // Selector or DOM element for pages bar container.
        //'activatePageOn' => null, // Event used to activate page. Can be' => click, mouseenter, ...
        /*'pageBuilder' =>          // Page item generator.
            function (index) {
                return '<li>' + (index + 1) + '</li>';
        },*/

        // Navigation buttons
        //'forward' =>  null, // Selector or DOM element for "forward movement" button.
        //'backward' => null, // Selector or DOM element for "backward movement" button.
        //'prev' =>     null, // Selector or DOM element for "previous item" button.
        //'next' =>     null, // Selector or DOM element for "next item" button.
        //'prevPage' => null, // Selector or DOM element for "previous page" button.
        //'nextPage' => null, // Selector or DOM element for "next page" button.

        // Automated cycling
        'cycleBy' =>       null, // Enable automatic cycling by 'items' or 'pages'.
        'cycleInterval' => 5000, // Delay between cycles in milliseconds.
        'pauseOnHover' =>  0,    // Pause cycling when mouse hovers over the FRAME.
        'startPaused' =>   0,    // Whether to start in paused sate.

        // Mixed options
        'moveBy' =>        300,     // Speed in pixels per second used by forward and backward buttons.
        'speed' =>         0,       // Animations speed in milliseconds. 0 to disable animations.
        'easing' =>        'swing', // Easing for duration based (tweening) animations.
        'startAt' =>       0,       // Starting offset in pixels or items.
        //'keyboardNavBy' => null,    // Enable keyboard navigation by 'items' or 'pages'.

        // Classes
        'draggedClass' =>  'dragged',  // Class for dragged elements (like SLIDEE or scrollbar handle).
        'activeClass' =>   'active',   // Class for active items and pages.
        'disabledClass' => 'disabled'  // Class for disabled navigation elements.
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

        //general settings
        if (!isset($this->clientOptions['smart']))
        {            
            $this->clientOptions['smart'] = 1;
        }

        if (!isset($this->clientOptions['horizontal']))
        {            
            $this->clientOptions['horizontal'] = 1;
        }

        if (!isset($this->clientOptions['activateMiddle']))
        {            
            $this->clientOptions['activateMiddle'] = 1;
        }

        if (!isset($this->clientOptions['activateOn']))
        {            
            $this->clientOptions['activateOn'] = 'click';
        }

        if (!isset($this->clientOptions['mouseDragging']))
        {            
            $this->clientOptions['mouseDragging'] = 1;
        }

        if (!isset($this->clientOptions['touchDragging']))
        {            
            $this->clientOptions['touchDragging'] = 1;
        }

        if (!isset($this->clientOptions['releaseSwing']))
        {            
            $this->clientOptions['releaseSwing'] = 1;
        }

        if (!isset($this->clientOptions['startAt']))
        {            
            $this->clientOptions['startAt'] = 1;
        }

        if (!isset($this->clientOptions['scrollBy']))
        {            
            $this->clientOptions['scrollBy'] = 1;
        }
        
        if (!isset($this->clientOptions['dragHandle']))
        {            
            $this->clientOptions['dragHandle'] = 1;
        }

        if (!isset($this->clientOptions['dynamicHandle']))
        {            
            $this->clientOptions['dynamicHandle'] = 1;
        }

        if (!isset($this->clientOptions['scrollBar'])) {
            $this->clientOptions['scrollBar'] = '#'.$this->getId().'scrollbar';
        }
        
        /*if (!isset($this->clientOptions['pagesBar'])) {
            $this->clientOptions['pagesBar'] = '#'.$this->getId().'controlls';
        }*/
        
        /*if (!isset($this->clientOptions['scrollSource'])) {
            $this->clientOptions['scrollSource'] = 'frame'.$this->orientation;
        }*/
        
        //navigation bar
        if (!isset($this->clientOptions['itemNav']))
        {            
            $this->clientOptions['itemNav'] = 'centered';
        }

        if (!isset($this->clientOptions['next']))
        {            
            $this->clientOptions['next'] = '#'.$this->options['id'].'btn_next';
        }
        
        if (!isset($this->clientOptions['prev']))
        {            
            $this->clientOptions['prev'] = '#'.$this->options['id'].'btn_prev';
        } 

        //add the requierd css classes to the div container element
        Html::addCssClass($this->options,'frame');
        Html::addCssClass($this->options,$this->orientation);
        
        if(!is_null($this->onePerFrame) && $this->onePerFrame)
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

        $js = [];
        
        $options = empty($this->clientOptions) ? '' : Json::encode($this->clientOptions);
        $js[] = "var sly" . $id . " = new Sly('#".$id."',$options).init();";
        
        $view->registerJs(implode("\n", $js),\yii\web\View::POS_READY);
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
        return Html::tag('ul',implode("\n", $items)); //, ['class'=>'slidee']
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
                'class' => 'handle '.$this->orientation
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
