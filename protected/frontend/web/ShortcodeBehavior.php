<?php

namespace frontend\web;

use usni\UsniAdaptor;
use yii\base\Behavior;
use yii\base\Exception;

/**
 * Class ShortcodeBehavior
 * @package frontend\web
 * Parses every view output and replaces [shortcode] with custom output according to defined types
 */
class ShortcodeBehavior extends Behavior
{
    /*private $_types = [
        self::SHORTCODE_WIDGET,
        self::SHORTCODE_STRING,
        self::SHORTCODE_LAMBDA,
        self::SHORTCODE_CLOSURE,
    ];*/
    
    const SHORTCODE_WIDGET = 'shortcode-widget';
    const SHORTCODE = 'shortcode-string';
    const SHORTCODE_LAMBDA = 'shortcode-lambda';
    const SHORTCODE_CLOSURE = 'shortcode-closure';
    
    public function events()
    {
        return [
            LMView::EVENT_AFTER_RENDER => 'proccessShortcode'
        ];
    }
    
    /**
     * Parse view and replace shortcode with content
     * @param $event
     * @return bool
     */
    public function proccessShortcode($event)
    {
        // Do nothing if event have no output
        if (empty($event->output)) {
            return $event;
        }
        
        // Do nothing if no no shortcode was found in view
        if(!strstr($event->output, '[shortcode')) {
            return $event;
        }
    
        /**
         * Examples:
         * [shortcode]Some string[/shortcode] - Content inside start/end tag (Default)
         * [shortcode attr1 atr2] - Content from view variables ($_params_)
         * [shortcode-widget usni\library\widgets\DetailBrowseDropdown] - From widget output
         * TODO: closure and lambda
         */
        $pattern = $this->getShortcodeRegexp();
        
        if (preg_match_all($pattern, $event->output, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $fullMatch = $this->getFullMatch($match);
                $shortcode = $this->getShortcode($match);
                $attrs = $this->getShortcodeVars($match);
                
                $replacement = !empty($match[3]) ? $match[3] : null;
                if ($fullMatch && $shortcode) {
                    switch ($shortcode) {
                        case self::SHORTCODE_WIDGET:
                            $widgetClass = $this->getWidgetClass($match);
                            if ($widgetClass) {
                                /** @var \yii\base\Widget $widgetClass */
                                try {
                                    $rm = new \ReflectionMethod($widgetClass, 'widget');
                                    if ($rm->isStatic()) {
                                        $replacement .= $widgetClass::widget([]);
                                        $event->output = str_replace($fullMatch, $replacement, $event->output);
                                    }
                                } catch (Exception $e) {
                                    //\Yii::error('[SHORTCODE] Widget ' . $widgetClass . ' error: ' . PHP_EOL . $e->getMessage());
                                    $replacement = '<!-- Widget: ' . $widgetClass . ' Error -->';
                                    $event->output = str_replace($fullMatch, $replacement, $event->output);
                                }
                            }
                            break;
                        case self::SHORTCODE_LAMBDA:
                            //@TODO
                            break;
                        case self::SHORTCODE_CLOSURE:
                            //@TODO
                            break;
                        default:
                            // Check the content from view param specified in shortcode attribute if exists
                            if (!$replacement && !empty($attrs)) {
                                foreach ($attrs as $outputVar) {
                                    if (isset($event->params[$outputVar]) && is_string($event->params[$outputVar])) {
                                        $replacement .= $event->params[$outputVar];
                                    }
                                }
                            }
                            $event->output = str_replace($fullMatch, $replacement, $event->output);
                            break;
                    }
                }
            }
        }
        
        return $event;
    }
    
    /**
     * @param $match
     * @return null
     */
    protected function getFullMatch($match)
    {
        return !empty($match[0]) ? $match[0] : null;
    }
    
    /**
     * @param $match
     * @return array
     */
    protected function getShortcodeVars($match)
    {
        $vars = [];
    
        $substr = !empty($match[2]) ? $match[2] : null;
        if ($substr) {
            $substr = preg_replace('/\s+/', ' ', $substr);
            foreach (explode(' ', $substr) as $item) {
                if (!empty($item)) {
                    if(!strstr($item, '=')) {
                        $vars[] = $item;
                    } else {
                        $parts = explode('=', $item);
                        $vars[$parts[0]] = $parts[1];
                    }
                }
            }
        }
        
        return $vars;
    }
    
    /**
     * @param $match
     * @return null
     */
    protected function getShortcode($match)
    {
        return !empty($match[1]) ? $match[1] : null;
    }
    
    /**
     * @param $match
     * @return string|null
     */
    protected function getWidgetClass($match)
    {
        $class = null;
    
        $substr = !empty($match[2]) ? $match[2] : null;
        if ($substr) {
            $substr = preg_replace('/\s+/', ' ', $substr);
            $parts = explode(' ', $substr);
            if (!empty($parts[1]) && is_string($parts[1])) {
                $class = $parts[1];
            }
        }
        
        return $class;
    }
    
    /**
     * @return string
     */
    public function getShortcodeRegexp()
    {
        return '/\[(' . self::SHORTCODE . '|' . self::SHORTCODE_WIDGET . '|' . self::SHORTCODE_LAMBDA . '|' . self::SHORTCODE_CLOSURE . ')(\s.*?)?\](?:([^\[]+)?\[\/shortcode-string\])?/';
    }
}
