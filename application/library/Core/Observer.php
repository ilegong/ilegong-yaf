<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 2015/5/28
 * Time: 13:34
 */

namespace Core;

interface Observer
{

    function update($event_info = null);
}
