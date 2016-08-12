<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 16/2/4
 * Time: 下午2:12
 */
class ShareController extends \Core\Wap\MyCon
{
    public function indexAction()
    {
        $thread_id = intval($this->getRequest()->getParam('id'));
        \Help\Loader::model('Wap' , 'Thread');
        $threadModel = new ThreadModel();

        $where = [
            'thread_id' => $thread_id
        ];
        $params = ['follower' , 'title' , 'pic'];

        $info = $threadModel->gets($where , $params);

        $info[0]['pic'] = explode(',' , $info[0]['pic']);

        $b = $info[0]['follower'] % 10000;
        $d = floor($info[0]['follower'] / 100000000);
        $c = ( $info[0]['follower'] - $b - $d*100000000 ) / 10000;
        $res = "";
        if($d)
        {
            $res = $res.$d."亿";
        }
        if($c)
        {
            $res = $res.$c."万";
        }
        if($b)
        {
            $res = $res.$b;
        }
        if($res == "")
        {
            $res = 0;
        }

        $info[0]['follower'] = $res;

        $info[0]['url'] = 'http://www.84000app.com/index/link';
        $this->getView()->assign("info", $info[0]);

        $this->out();
    }
}