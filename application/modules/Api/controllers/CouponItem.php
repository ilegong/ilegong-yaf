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

    /**
     * @param coupon_id
     * @param source
     */
    public function addAction()
    {
        \Help\Loader::model('Api','CouponItem');
        $couponItemModel = new CouponItemModel();
        //是否领过红包
        $where = [
            'AND' => [
                'bind_user' => $this->_userId,
                'coupon_id' => $_REQUEST['coupon_id']
            ]
        ];
        if($couponItemModel->count($where))
        {
            \Help\Out::ajaxReturn(['ok'=>1,'msg'=>'已经领过红包']);
        }
        //红包是否可领
        \Help\Loader::model('Api','Coupon');
        $couponModel = new CouponModel();

        $where = [
            'AND' => [
                'id' => $_REQUEST['coupon_id'],
                'deleted' => 0,
                'status' => 1
            ]
        ];
        $coupon = $couponModel->gets($where);
        $coupon = $coupon[0];
        if($coupon['num'] == 0)//红包已发完
        {
            \Help\Out::ajaxReturn(['ok' => 1, 'msg'=>'红包已经发完']);
        }
        $date = date('Y-m-d H:i:s');
        if($coupon['valid_end'] < $date)
        {
            \Help\Out::ajaxReturn(['ok' => 1, 'msg'=>'红包已经过期']);
        }

        $data = [
            'code',
            'bind_user' => $this->_userId,
            'coupon_id' => $_REQUEST['coupon_id'],
            'status' => 1,
            'last_updator' => $this->_userId,
            'deleted' => 0,
            'created' => $date,
            'modified' => $date,
            'applied_order' => 0,
            'applied_time' => '',
            'source' => $_REQUEST['source'],
            'sent_message_status' => 0,
            'shop_id' => $coupon['shop_id'],
            'valid_begin' => $coupon['valid_begin'],
            'valid_end' => $coupon['valid_end']
        ];
        $insert_id = 0;
        $couponItemModel->action(function () use ($data,$couponItemModel,$couponModel,$insert_id){
            $insert_id = $couponItemModel->add($data);
            $d = [
                'num[-]' => 1
            ];
            $where = [
                'AND' => [
                    'id' => $data['coupon_id'],
                    'num[>]' => 0
                ]
            ];
            $r = $couponModel->update($d,$where);
            if(!($insert_id OR $r))
            {
                $insert_id = 0;
                return false;
            }
        });

        \Help\Out::ajaxReturn(['ok' => $insert_id > 0 ? 0 : 1 , 'msg' => $insert_id]);
    }

    public function delAction()
    {
        \Help\Loader::model('Api','CouponItem');
        $couponItemModel = new CouponItemModel();

        $where = [
            'AND' => [
                'id' => $_POST['id'],
                'bind_user' => $this->_userId
            ]
        ];
        $data = [
            'deleted' => 1
        ];
        $res = ['ok' => 1,'msg'=>'failed'];
        if($couponItemModel->update($data,$where))
        {
            $res = ['ok' => 0,'msg'=>"success"];
        }
        \Help\Out::ajaxReturn($res);
    }

    /**
     * @param id
     */
    public function getOneByIdAction()
    {
        \Help\Loader::model('Api','CouponItem');
        $couponItemModel = new CouponItemModel();
        \Help\Out::ajaxReturn(['ok' => 0 , 'msg' => $couponItemModel->getById($_REQUEST['id'])]);
    }

    /**
     * @method get
     * @param shop_id 团长id
     */
    public function getAllByShopIdAction()
    {
        \Help\Loader::model('Api','CouponItem');
        $couponItemModel = new CouponItemModel();
        $where = [
            'AND' => [
                'shop_id' => $_GET['shop_id'],
                'status' => 1, //status 1 未消费 2已消费
                'deleted' => 0,
                'valid_end[>]' => date('Y-m-d H:i:s')
            ]
        ];
        $res = $couponItemModel->gets($where);
        \Help\Out::ajaxReturn(['ok' => 0,'msg'=>$res]);
    }

    /**
     * @param id
     * @param applied_order
     */
    public function consumeAction()
    {
        \Help\Loader::model('Api','CouponItem');
        $couponItemModel = new CouponItemModel();
        $date = date('Y-m-d H:i:s');
        $where = [
            'AND' => [
                'id' => $_REQUEST['id'],
                'status' => 1,
                'deleted' => 0,
                'applied_order' => 0,
                'valid_begin[<=]' => $date,
                'valid_end[>=]' => $date
            ]
        ];

        $data = [
            'applied_order' => $_REQUEST['applied_order'],
            'status' => 2,
            'applied_time' => $date
        ];

        $res = ['ok' => 1,'msg'=>'failed'];
        if($couponItemModel->update($data,$where))
        {
            $res = ['ok' => 0,'msg'=>'success'];
        }
        \Help\Out::ajaxReturn($res);
    }


}