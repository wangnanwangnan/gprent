<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\models\mysqldb\product;

use yii\db\ActiveRecord;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProductSteam extends ActiveRecord
{
    const STATUS_ACTIVE = 0;

    public static function tableName()
    {
        return 'product_steam';
    }

    public static function findByAssetid($assetid)
    {

        return static::findOne(['assetid' => $assetid]);
    }

    public static function deleteNoRent($gameid = 0)
    {
        $condition = 'status='.self::STATUS_ACTIVE;
        if($gameid != 0){
            $condition .= ' and gameid='.$gameid;
        }

        return static::deleteAll($condition);
    }
}
