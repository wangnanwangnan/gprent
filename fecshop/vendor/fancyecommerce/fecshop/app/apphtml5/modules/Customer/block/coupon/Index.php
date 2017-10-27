<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Customer\block\coupon;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index
{
    protected $numPerPage = 10;
    protected $pageNum;
    protected $orderBy;
    protected $customer_id;
    protected $_page = 'p';

    /**
     * 初始化类变量.
     */
    public function initParam()
    {
        if (!Yii::$app->user->isGuest) {
            $identity = Yii::$app->user->identity;
            $this->customer_id = $identity;
        }
        $this->pageNum = (int) Yii::$app->request->get('p');
        $this->pageNum = ($this->pageNum >= 1) ? $this->pageNum : 1;
        $this->orderBy = ['add_time' => SORT_DESC];
    }

    public function getLastData()
    {
        $this->initParam();
        $return_arr = [];
        if ($this->customer_id) {
            $customer_id = $this->customer_id->id;
            $filter = [
                'numPerPage'    => $this->numPerPage,
                'pageNum'        => $this->pageNum,
                'orderBy'        => $this->orderBy,
                'where'            => [
                    ['customer_id' => $customer_id],
                ],
                'asArray' => true,
            ];

            $customer_coupon_list = Yii::$service->customer->coupon->coll($filter);

            $return_arr['coupon_list'] = $customer_coupon_list['coll'];
            $count = $customer_coupon_list['count'];
            $pageToolBar = $this->getProductPage($count);
            $return_arr['pageToolBar'] = $pageToolBar;
        }

        return $return_arr;
    }

    protected function getProductPage($countTotal)
    {
        if ($countTotal <= $this->numPerPage) {
            return '';
        }
        $config = [
            'class'        => 'fecshop\app\appfront\widgets\Page',
            'view'        => 'widgets/page.php',
            'pageNum'        => $this->pageNum,
            'numPerPage'    => $this->numPerPage,
            'countTotal'    => $countTotal,
            'page'            => $this->_page,
        ];

        return Yii::$service->page->widget->renderContent('category_product_page', $config);
    }
}
