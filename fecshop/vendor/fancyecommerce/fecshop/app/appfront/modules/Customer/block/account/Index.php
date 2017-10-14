<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\block\account;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index
{
    public function getLastData()
    {
        $identity = Yii::$app->user->identity;
        
        $account = $identity['email'];
        $trackLink = '';
        if(!empty($identity['steamid'])){
            $account = 'steamID:'.$identity['steamid'];
            $trackLink = 'https://steamcommunity.com/profiles/'.$identity['steamid'].'/tradeoffers/privacy#trade_offer_access_url';
        }

        return [
            'accountEditUrl'    => Yii::$service->url->getUrl('customer/editaccount'),
            'email'             => $account,
            'invite'            => $identity['invite_code'],
            'accountAddressUrl' => Yii::$service->url->getUrl('customer/address'),
            'accountOrderUrl'   => Yii::$service->url->getUrl('customer/order'),
            'trackLink'         => $trackLink,
        ];
    }
}
