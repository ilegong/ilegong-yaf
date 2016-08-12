<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 2015/5/28
 * Time: 14:47
 */

namespace Observer;

use Core\Observer;

class UserAdd1 implements Observer
{

    public function update($event_info = null)
    {
        echo __FILE__;
    }

}
