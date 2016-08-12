<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 2015/5/28
 * Time: 14:51
 */

namespace Observer;

use Core\Observer;

class UserAdd2 implements Observer
{

    public function update($event_info = null)
    {
        echo __FILE__;
    }

}
