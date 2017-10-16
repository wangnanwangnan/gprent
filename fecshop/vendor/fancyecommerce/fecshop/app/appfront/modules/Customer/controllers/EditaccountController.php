<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class EditaccountController extends AppfrontController
{
    //protected $_registerSuccessRedirectUrlKey = 'customer/account';

    protected $_customerMemberModelName = '\fecshop\models\mysqldb\customer\Member';
    
    public function init()
    {
        parent::init();
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account/login');
        }
        $editForm = Yii::$app->request->post('editForm');
        if (!empty($editForm)) {
            $this->getBlock()->saveAccount($editForm);
        }
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    public function actionPay()
    {
        $genarateStatus = Yii::$service->order->generateMemberCardOrder();

        $startUrl = Yii::$service->payment->getStandardStartUrl('alipay_standard');
        Yii::$service->url->redirect($startUrl);
    }   
    public function actionBackmoney()
    {
        //判断是否有在享受会员待遇 如果有则不退款 如果没有则标记申请退款
        $config_levels = Yii::$app->params['level'];
        
        $identity = Yii::$app->user->identity;
        $level = $identity->level;
        $summation_cost = $identity->summation_cost;

        $config_rent_price = $config_levels[0]['rent_price'];
        
        if($summation_cost > $config_rent_price){
            Yii::$service->page->message->addError('请将手中饰品退还后退款');
        }else{

            //修改等级订单的状态
            $customerMemberModel = new $this->_customerMemberModelName();
            $customerMemberInfo = $customerMemberModel->find()->where(['customer_id' => $identity['id'],'is_cancel' => 0])->one();
            
            if($customerMemberInfo){
                $customerMemberInfo->is_cancel = 1;
                if($customerMemberInfo->save()){
                    Yii::$service->page->message->addError('退款申请成功');
                }
            }
        }
        Yii::$service->url->redirect('/customer/editaccount');
    } 
}
