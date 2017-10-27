<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\customer;

use fecshop\services\Service;
use Yii;
/**
 * Coupon  child services. 废弃，coupon现在在cart services中
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Coupon extends Service
{

    protected $_couponModelName = '\fecshop\models\mysqldb\customer\Coupon';
    protected $_couponModel;

    public function __construct(){
        list($this->_couponModelName,$this->_couponModel) = \Yii::mapGet($this->_couponModelName);  
    }
    
    protected function actionGetPrimaryKey()
    {
        return 'id';
    }


    /**
     * @property $filter|array
     * @return Array;
     *              通过过滤条件，得到coupon的集合。
     *              example filter:
     *              [
     *                  'numPerPage' 	=> 20,
     *                  'pageNum'		=> 1,
     *                  'orderBy'	    => ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     *                  'where'			=> [
     *                      ['>','price',1],
     *                      ['<=','price',10]
     * 			            ['sku' => 'uk10001'],
     * 		            ],
     * 	                'asArray' => true,
     *              ]
     * 根据$filter 搜索参数数组，返回满足条件的订单数据。
     */
    protected function actionColl($filter = '')
    {
        $query  = $this->_couponModel->find();
        $query  = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        $coll   = $query->all();
        
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }
   
}
