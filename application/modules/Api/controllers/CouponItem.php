<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 16/8/30
 * Time: 上午10:05
 */
class CouponItemController extends \Core\Api\MyCon
{
    protected $_login = false;
    public function addAction()
    {
        \Help\Loader::model('Api','CouponItem');
        $couponItemModel = new CouponItemModel();
    }

    public function delAction()
    {
        
    }

    public function getAllItemsByShopIdAction()
    {
        \Help\Loader::model('Api','CouponItem');
        $couponItemModel = new CouponItemModel();
        $where = [
            'shop_id' => '',
            'status' => '',
            'deleted' => '',
        ];
        $res = $couponItemModel->gets($where);
        var_dump($res);
        die;
    }

    public function updateAction()
    {

    }

    public function testAction()
    {
        \Help\Loader::model('Api','CouponItem');
        $couponItemModel = new CouponItemModel();
        $where = [
            'id' => 16
        ];
        $res = $couponItemModel->gets($where);
        \Help\Out::ajaxReturn($res);
    }
}