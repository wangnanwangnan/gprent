<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Payment\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;

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
        $post = \Yii::$app->request->post();
        if (is_array($post) && !empty($post)) {
            file_put_contents('/tmp/t.txt',json_encode($post).'----99999999999wap');
            $data = $this->getBlock()->getLastData();
            if($data){
                $post = \Yii::$service->helper->htmlEncode($post);
                $ipnStatus = \Yii::$service->payment->alipay->receiveIpn($post);
                echo 'success';
                return;

            }
        }
    }

}
