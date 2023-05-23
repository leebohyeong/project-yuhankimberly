<?php
/** ==============================================================================
 * File: Config Setting (config.php)
 * Date: 2018-07-27 오후 2:34
 * git original : https://github.com/maximebf/php-debugbar
 * Created by @Mojito.DevGroup
 * Copyright 2018, travelweek.visitkorea.or.kr(Group IDD). All rights are reserved
 * ==============================================================================*/
namespace Groupidd\Library;

use DebugBar\StandardDebugBar;

class Debugbar{
    public $debugbar;
    public $renderer;
    private $enabled;

    var $_attach_onm = array();

    public function __construct($ENVIRONMENT){
        $this->enabled = false;

        if ($ENVIRONMENT == 'inspect'){
            $this->enabled = true;
        }

        if ($this->enabled){
            $this->debugbar = new StandardDebugBar();
            $this->renderer = $this->debugbar->getJavascriptRenderer();
        }
    }

    public function render($output = true){
        if (!$this->enabled) return '';

        return $this->renderer->render($output);
    }

    public function renderHead(){
        if (!$this->enabled) return '';

        return $this->renderer->renderHead();
    }

    public function addMessage($data){
        if (!$this->enabled) return '';

        $this->debugbar['messages']->addMessage($data);
    }
}