<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 2015/5/28
 * Time: 13:32
 */

namespace Core;

class EventGenerator extends \Yaf\Controller_Abstract
{

    private $observers = array();

    public function addObserver(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update();
        }
    }

}
