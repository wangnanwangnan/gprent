<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Catalog\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;
use fecshop\models\mysqldb\product\ProductSteam;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProductController extends AppfrontController
{
    public function init()
    {
        parent::init();
        Yii::$service->page->theme->layoutFile = 'product_view.php';
    }

    // 产品详细页面
    public function actionIndex()
    {
        $data = $this->getBlock()->getLastData();
        if(is_array($data)){
            return $this->render($this->action->id, $data);
        }
    }
    /**
     * Yii2 behaviors 可以参看地址：http://www.yiichina.com/doc/guide/2.0/concept-behaviors
     * 这里的行为的作用为添加page cache（整页缓存）。
     */
    public function behaviors()
    {
        $primaryKey = Yii::$service->product->getPrimaryKey();
        $product_id = Yii::$app->request->get($primaryKey);
        $cacheName = 'product';
        if (Yii::$service->cache->isEnable($cacheName)) {
            $timeout = Yii::$service->cache->timeout($cacheName);
            $disableUrlParam = Yii::$service->cache->timeout($cacheName);
            $cacheUrlParam = Yii::$service->cache->cacheUrlParam($cacheName);
            $get_str = '';
            $get = Yii::$app->request->get();
            // 存在无缓存参数，则关闭缓存
            if (isset($get[$disableUrlParam])) {
                return [
                    [
                        'enabled' => false,
                        'class' => 'yii\filters\PageCache',
                        'only' => ['index'],

                    ],
                ];
            }
            if (is_array($get) && !empty($get) && is_array($cacheUrlParam)) {
                foreach ($get as $k=>$v) {
                    if (in_array($k, $cacheUrlParam)) {
                        if ($k != 'p' && $v != 1) {
                            $get_str .= $k.'_'.$v.'_';
                        }
                    }
                }
            }
            $store = Yii::$service->store->currentStore;
            $currency = Yii::$service->page->currency->getCurrentCurrency();

            return [
                [
                    'enabled' => true,
                    'class' => 'yii\filters\PageCache',
                    'only' => ['index'],
                    'duration' => $timeout,
                    'variations' => [
                        $store, $currency, $get_str, $product_id,
                    ],
                    //'dependency' => [
                    //	'class' => 'yii\caching\DbDependency',
                    //	'sql' => 'SELECT COUNT(*) FROM post',
                    //],
                ],
            ];
        }

        return [];
    }

    // ajax 得到产品加入购物车的价格。
    public function actionGetcoprice()
    {
        $custom_option_sku = Yii::$app->request->get('custom_option_sku');
        $product_id = Yii::$app->request->get('product_id');
        $qty = Yii::$app->request->get('qty');
        $cart_price = 0;
        $custom_option_price = 0;
        $product = Yii::$service->product->getByPrimaryKey($product_id);
        $cart_price = Yii::$service->product->price->getCartPriceByProductId($product_id, $qty, $custom_option_sku);
        if (!$cart_price) {
            return;
        }
        $price_info = [
            'price' => $cart_price,
        ];

        $priceView = [
            'view'    => 'catalog/product/index/price.php',
        ];
        $priceParam = [
            'price_info' => $price_info,
        ];

        echo  json_encode([
            'price' =>Yii::$service->page->widget->render($priceView, $priceParam),
        ]);
        exit;
    }

    public function actionUpdatesteam()
    {
        $gameidArr = ['730', '578080'];
        $steamid = '76561198350673503';

        $productSteam = new ProductSteam();
        $productSteam->deleteNoRent();
        
        foreach($gameidArr as $gameid){
            $c = file_get_contents('http://steamcommunity.com/inventory/'.$steamid.'/'.$gameid.'/2');
            $arr = json_decode($c);
        
            
            $descriptionsArr = $arr->descriptions;
            $steamProduct = array();
            foreach($descriptionsArr as $dInfo){
                $steamProduct[$dInfo->classid] = array(
                                            'name' => $dInfo->name,
                                            'pic' => $dInfo->icon_url_large,
                                        );
            }

            $assetsArr = $arr->assets;
            foreach($assetsArr as $assetInfo){
                $productSteam = new ProductSteam();
                $r = $productSteam->findByAssetid($assetInfo->assetid);
                if(!empty($r)){
                    $r->gameid = $assetInfo->appid;
                }

                $productSteam->gameid = $assetInfo->appid;    
                $productSteam->steamid = $steamid;    
                $productSteam->classid = $assetInfo->classid;    
                $productSteam->assetid = $assetInfo->assetid;
                $productSteam->name = $steamProduct[$assetInfo->classid]['name'];
                $productSteam->pic = $steamProduct[$assetInfo->classid]['pic'];
                
                $productSteam->save();
            }
        }
        return true;
    }

    public function actionSteamrobot(){
        $twofa = 'T2NP4';
        $res = Yii::$app->steam->robootLogin($twofa);
        print_r($res);exit;
    }
    
    public function actionSteam(){
        $session = Yii::$app->session;
        $steamid = $session['steamid'];
    
        $url = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=3DC2EC41F468ADAB42B8A549A1BB0CF3&steamids='.$steamid;
        $contents = file_get_contents($url);
        print_r($contents);exit;
    }

    public function actionSteamsend(){                                                                                                            
        $get = Yii::$app->request->get();
        $session = Yii::$app->session;
        
        //$a = file_get_contents('https://pubg.me/');
        //print_r($a);exit;
    
        //print_r($session);exit;
        if(isset($session['steamid']) && !empty($session['steamid'])){
    
            //获取用户库存
            //$inventory = Yii::$app->steam->getinventory();
            //print_r($inventory);exit;
    
            //发起交易
            $appId = 578080;//游戏id[steam启动游戏的ID]
            $assetid = "2950281171442995516";//物品id[通过解析网页中div标签上为item_x_sssss中的ssss部分的数值]
            $token="IJBy9XRG";//第三方交易秘钥[第三方交易链接上token的那个值]
            $partner="462592369";//被交易者id[第三方交易链接上partner的那个值]
    
            $json=json_encode(array(
                'newversion' => false,
                'version' => 2, 
                'me' => array("assets"=>[],"currency"=>[],"ready"=>false), 
                'them' => array("assets"=> [array("appid"=>$appId,"contextid"=>"2","amount"=>1,"assetid"=>$assetid)],"currency"=> [],"ready"=>    
false), 
            ),true);//交易参数
            
            $id = Yii::$app->steam->send($token, $json, $partner);
            echo $id;exit;
        
        }else{
            if(isset($get['login'])){
                echo Yii::$app->steam->login();
            }
   
            echo "<a href='?login'><img src='http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_large_border.png'></a>";
        }
    }

}
