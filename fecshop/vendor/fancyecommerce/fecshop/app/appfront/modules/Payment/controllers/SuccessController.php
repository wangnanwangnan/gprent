<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Payment\controllers;

use fecshop\app\appfront\modules\AppfrontController;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class SuccessController extends AppfrontController
{
    public function actionIndex()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
    //支付成功后异步通知地址
    public function actionIpn()
    {
        $data = $this->getBlock()->getLastData();
        if($data){
            echo 'success';
            return;

        }
        //return $this->render($this->action->id, $data);
    }

}
