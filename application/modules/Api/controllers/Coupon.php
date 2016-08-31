<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 16/8/30
 * Time: 上午11:13
 */
class CouponController extends \Core\Api\MyCon
{
    public function getAction()
    {
        \Help\Loader::model('Api','Coupon');
        $couponModel = new CouponModel();
    }

    /**
     * @param product_list 商品使用范围
     * @param valid_begin 开始时间
     * @param valid_end 结束时间
     * @param last_updator
     * @param reduced_price 红包面值
     * @param least_price 最低消费金额才能使用红包
     */
    public function addAction()
    {
        \Help\Loader::model('Api','Coupon');
        $couponModel = new CouponModel();
        $date = date('Y-m-d H:i:s');
        $data = [
            'name' => $_POST['name'],
            'brand_id' => $_POST['brand_id'],
            'product_list' => $_POST['product_list'],
            'category_id' => $_POST['category_id'],
            'status' => 1,
            'valid_begin' => $_POST['valid_begin'],
            'valid_end' => $_POST['valid_end'],
            'published' => 1,
            'last_updator' => $this->_userId,
            'deleted' => 0,
            'created' => $date,
            'modified' => $date,
            'reduced_price' => $_POST['reduced_price'],
            'type' => $_POST['type'],
            'least_price' => $_POST['least_price'],
            'shop_id' => $this->_userId
        ];
        $id = $couponModel->insert($data);
        $res = ['ok' => 1,'msg'=>'failed'];
        if($id)
        {
            $res = ['ok' => 0,'msg'=>"{$id}"];
        }
        \Help\Out::ajaxReturn($res);
    }

    /**
     * @param id
     */
    public function delAction()
    {
        \Help\Loader::model('Api','Coupon');
        $couponModel = new CouponModel();
        $where = [
            'AND' => [
                'id' => $_GET['id'],
                'shop_id' => $this->_userId
            ],
            'LIMIT' => 1
        ];
        $data = [
            'deleted' => 1
        ];
        $res = ['ok' => 1,'msg'=>'failed'];
        if($couponModel->update($data,$where))
        {
            $res = ['ok' => 0,'msg'=>'success'];
        }
        \Help\Out::ajaxReturn($res);
    }

    /**
     * @param id
     * @param shop_id
     * @param num
     */
    public function setNumAction()
    {
        \Help\Loader::model('Api','Coupon');
        $couponModel = new CouponModel();
        $data = [
            'last_updator' => $this->_userId,
            'num' => $_POST['num']
        ];
        $where = [
            'AND' => [
                'id' => $_POST['id'],
                'shop_id' => $this->_userId
            ],
            'LIMIT' => 1
        ];
        $res = ['ok' => 1,'msg'=>'failed'];

        if( $couponModel->update($data,$where)){
            $res = ['ok' => 0,'msg'=>'success'];
        }
        \Help\Out::ajaxReturn($res);
    }

    /**
     * @param shop_id
     */

    public function getAllByShopIdAction()
    {
        \Help\Loader::model('Api','Coupon');
        $couponModel = new CouponModel();
        $data = date('Y-m-d H:i:s');
        $where = [
            'AND' => [
                'shop_id' => $_GET['shop_id'],
                'num[>]' => 0,
                'valid_begin[<=]' => $data,
                'valid_end[>=]' => $data,
                'status' => 1,
                'deleted' => 0,
                'published' => 1
            ]
        ];
        $res = $couponModel->gets($where);
        \Help\Out::ajaxReturn(['ok' => 0 , 'msg'=> $res]);
    }
    
    
}