<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\block\editaccount;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index
{
    protected $_customerMemberModelName = '\fecshop\models\mysqldb\customer\Member';
    
    public function getLastData()
    {
 
        $identity = Yii::$app->user->identity;

        //判断芝麻信用
        $requireZMScore = Yii::$app->params['zmScore'];
        $requireZMScoreLow = Yii::$app->params['zmScoreLow'];
        $is_level = 0;//是否缴纳押金
        $cash_pledge = 0;//押金金额
        //如果信用不够 判断是否交押金
        if($identity['zm_scroe'] >= $requireZMScoreLow && $identity['zm_scroe'] <= $requireZMScore)
        {   
            $customerMemberModel = new $this->_customerMemberModelName();
            $customerMemberInfo = $customerMemberModel->find()->where(['customer_id' => $identity['id'],'is_cancel' => 0])->one();
            if($customerMemberInfo){
                $is_level = $customerMemberInfo->level;
                //押金 金额
                $cash_pledge = Yii::$app->params['memberCard']['member_level'][1];
            }
        }

        return [
            'firstname'     => $identity['firstname'],
            'email'         => $identity['email'],
            'lastname'      => $identity['lastname'],
            'zm_scroe'      => $identity['zm_scroe'],
            'invite'        => $identity['invite_code'],
            'is_level'      => $is_level,
            'cash_pledge'   => $cash_pledge,
            'actionUrl'     => Yii::$service->url->getUrl('customer/editaccount'),
            'steamid'       => $identity['steamid'],
        ];
    }
    
    /**
     * @property $editForm|array
     * 保存修改后的用户信息。
     */
    public function saveAccount($editForm)
    {
        if (is_array($editForm) && !empty($editForm)) {
            $editForm = \Yii::$service->helper->htmlEncode($editForm);
            $identity = Yii::$app->user->identity;
            $firstname = $editForm['firstname'] ? $editForm['firstname'] : '';
            $lastname = $editForm['lastname'] ? $editForm['lastname'] : '';
            $current_password = $editForm['current_password'] ? $editForm['current_password'] : '';
            $password = $editForm['password'] ? $editForm['password'] : '';
            $confirmation = $editForm['confirmation'] ? $editForm['confirmation'] : '';
            $change_password = $editForm['change_password'] ? $editForm['change_password'] : '';
/*
            if (!$firstname || !$lastname) {
                Yii::$service->page->message->addError('first name and last name can not empty');

                return;
            }
*/
            if (!$lastname) {
                Yii::$service->page->message->addError('last name can not empty');

                return;
            }


            if ($change_password) {
                if (!$current_password) {
                    Yii::$service->page->message->addError('current password can not empty');

                    return;
                }

                if (!$password || !$confirmation) {
                    Yii::$service->page->message->addError('password and confirmation password can not empty');

                    return;
                }

                if ($password != $confirmation) {
                    Yii::$service->page->message->addError('password and confirmation password  must be equal');

                    return;
                }

                if (!$identity->validatePassword($current_password)) {
                    Yii::$service->page->message->addError('Current password is not right,If you forget your password, you can retrieve your password by forgetting your password in login page');

                    return;
                }
                $identity->setPassword($password);
            }
            $identity->firstname = $firstname;
            $identity->lastname = $lastname;

            if ($identity->validate()) {
                $identity->save();
                Yii::$service->page->message->addCorrect('edit account info success');

                return true;
            } else {
                $errors = $identity->errors;
                if (is_array($errors) && !empty($errors)) {
                    foreach ($errors as $error) {
                        if (is_array($error) && !empty($error)) {
                            foreach ($error as $er) {
                                Yii::$service->page->message->addError($er);
                            }
                        }
                    }
                }
            }
        }
    }
}
