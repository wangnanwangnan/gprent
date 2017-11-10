<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Sales\block\orderinfo;

use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use Yii;
use fecshop\models\mysqldb\Customer;
use fecshop\models\mysqldb\customer\Member;
/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manager extends AppadminbaseBlock implements AppadminbaseBlockInterface
{
    /**
     * init param function ,execute in construct.
     */
    public function init()
    {
        /*
         * edit data url
         */
        $this->_editUrl = CUrl::getUrl('sales/orderinfo/manageredit');
        /*
         * delete data url
         */
        $this->_deleteUrl = CUrl::getUrl('sales/orderinfo/managerdelete');
        /*
         * service component, data provider
         */
        $this->_service = Yii::$service->order;
        parent::init();
    }

    public function getLastData()
    {
        $this->_param['is_delete'] = 0;
        $this->_param['is_membercard'] = 0;
        $oStatus = Yii::$app->request->get('s');
        if($oStatus){
            $this->_param['order_status'] = $oStatus;
        }
        $is_membercard = Yii::$app->request->get('ismember');
        if($is_membercard){
            $this->_param['is_membercard'] = $is_membercard;
            $this->_param['order_status'] = 'processing';
        }
        // hidden section ,that storage page info
        $pagerForm = $this->getPagerForm();
        // search section
        $searchBar = $this->getSearchBar();
        // edit button, delete button,
        $editBar = $this->getEditBar();
        // table head
        $thead = $this->getTableThead();
        // table body
        $tbody = $this->getTableTbody();
        // paging section
        $toolBar = $this->getToolBar($this->_param['numCount'], $this->_param['pageNum'], $this->_param['numPerPage']);

        return [
            'pagerForm'        => $pagerForm,
            'searchBar'        => $searchBar,
            'editBar'        => $editBar,
            'thead'        => $thead,
            'tbody'        => $tbody,
            'toolBar'    => $toolBar,
        ];
    }

    /**
     * get search bar Arr config.
     */
    public function getSearchArr()
    {
        $data = [

            [    // 字符串类型
                'type'=>'inputtext',
                'title'=>'是否删除',
                'name'=>'is_delete',
                'columns_type' =>'int',
            ],
            [    // 字符串类型
                'type'=>'inputtext',
                'title'=>'订单状态',
                'name'=>'order_status',
                'columns_type' =>'string',
            ],
            [    // 字符串类型
                'type'=>'inputtext',
                'title'=>'押金订单',
                'name'=>'is_membercard',
                'columns_type' =>'string',
            ],
            [    // 字符串类型
                'type'=>'inputtext',
                'title'=>'订单号',
                'name'=>'increment_id',
                'columns_type' =>'string',
            ],
            [    // 时间区间类型搜索
                'type'=>'inputdatefilter',
                'name'=> 'created_at',
                'columns_type' =>'int',
                'value'=>[
                    'gte'=>'创建时间开始',
                    'lt' =>'创建时间结束',
                ],
            ],
        ];

        return $data;
    }

    /**
     * config function ,return table columns config.
     */
    public function getTableFieldArr()
    {
        $table_th_bar = [
            [
                'orderField'    => $this->_primaryKey,
                'label'            => 'ID',
                'width'            => '50',
                'align'        => 'center',

            ],
            [
                'orderField'    => 'zm_scroe',
                'label'            => '芝麻分',
                'width'            => '20',
                'align'        => 'left',
                //'lang'			=> true,
            ],
            [
                'orderField'    => 'increment_id',
                'label'            => '订单号',
                'width'            => '50',
                'align'        => 'left',
                //'lang'			=> true,
            ],

            [
                'orderField'    => 'created_at',
                'label'            => '创建时间',
                'width'            => '50',
                'align'        => 'left',
                'convert'        => ['int' => 'datetime'],
                //'lang'			=> true,
            ],

            [
                'orderField'    => 'order_status',
                'label'            => '订单状态',
                'width'            => '50',
                'align'        => 'left',
                //'lang'			=> true,
            ],

            [
                'orderField'    => 'items_count',
                'label'            => '总天数',
                'width'            => '50',
                'align'        => 'left',
                //'lang'			=> true,
            ],
/*
            [
                'orderField'    => 'total_weight',
                'label'            => '总重量',
                'width'            => '50',
                'align'        => 'left',
                //'lang'			=> true,
            ],
*/
            [
                'orderField'    => 'base_grand_total',
                'label'            => '总金额',
                'width'            => '50',
                'align'        => 'left',
                //'lang'			=> true,
            ],

/*
            [
                'orderField'    => 'payment_method',
                'label'            => '支付方式',
                'width'            => '50',
                'align'        => 'left',
                //'lang'			=> true,
            ],
            [
                'orderField'    => 'shipping_method',
                'label'            => '货运方式',
                'width'            => '50',
                'align'        => 'left',
                //'lang'			=> true,
            ],

            [
                'orderField'    => 'base_shipping_total',
                'label'            => '运费（美元）',
                'width'            => '50',
                'align'        => 'left',
                //'lang'			=> true,
            ],

*/
            [
                'orderField'    => 'customer_lastname',
                'label'            => '用户姓',
                'width'            => '50',
                'align'        => 'left',
                //'lang'			=> true,
            ],
            [
                'orderField'    => 'customer_telephone',
                'label'            => '电话',
                'width'            => '50',
                'align'        => 'left',
                //'lang'			=> true,
            ],
            [
                'orderField'    => 'order_items',
                'label'            => '商品信息',
                'width'            => '50',
                'align'        => 'left',
                //'lang'			=> true,
            ],

        ];

        return $table_th_bar;
    }

    /**
     * rewrite parent getTableTbodyHtml($data).
     */
    public function getTableTbodyHtml($data)
    {
        $fileds = $this->getTableFieldArr();
        $str .= '';
        $csrfString = \fec\helpers\CRequest::getCsrfString();
        $user_ids = [];
        foreach ($data as $one) {
            $user_ids[] = $one['created_person'];
        }
        $blacklist = Yii::$app->params['blacklist'];
        $Customer = new Customer();
        $users = Yii::$service->adminUser->getIdAndNameArrByIds($user_ids);
        
        $member = new Member();

        foreach ($data as $one) {
            //获取订单商品信息
            $order_items_arr = [];
            $order_items = $this->_service->getInfoByIncrementId($one['increment_id']);
            if($order_items['items']){
                foreach($order_items['items'] as $key => $info){
                    $order_items_arr[$key] = $info['name'].'-'.$info['qty'].'天';
                }
            }
            //判断是否黑名单
            $style = '';
            $nameStr = '';
            $one['zm_scroe'] = 0; // 芝麻信用分
            $user_info = $Customer->find()->where(['id' => $one['customer_id']])->one();
            if(in_array($user_info['identity_card'],$blacklist)){
                $style = 'style="color:red"';
                $nameStr = '(诈骗犯)';

            }

            //是否押金订单 并且判断是否申请退押金
            $memberStart = "";
            if($one['is_membercard'] == 1 && $one['order_status'] != 'back_money'){
                //查询押金订单状态
                $member_info = $member->find()->where(['order_id' => $one['order_id']])->one();
                if($member_info['is_cancel'] == 1){
                    $style = 'style="color:red"';
                    $nameStr .= "(退款申请)";
                }
            }

            $one['zm_scroe'] = $user_info['zm_scroe'];
            $one['order_items'] = implode(',',$order_items_arr);
            $str .= '<tr '.$style.' target="sid_user" rel="'.$one[$this->_primaryKey].'">';
            $str .= '<td><input name="'.$this->_primaryKey.'s" value="'.$one[$this->_primaryKey].'" type="checkbox">'.$nameStr.'</td>';
            foreach ($fileds as $field) {
                $orderField = $field['orderField'];
                $display = $field['display'];
                $val = $one[$orderField];
                if ($orderField == 'created_person') {
                    $val = isset($users[$val]) ? $users[$val] : $val;
                    $str .= '<td>'.$val.'</td>';
                    continue;
                }
                if ($val) {
                    if (isset($field['display']) && !empty($field['display'])) {
                        $display = $field['display'];
                        $val = $display[$val] ? $display[$val] : $val;
                    }
                    if (isset($field['convert']) && !empty($field['convert'])) {
                        $convert = $field['convert'];
                        foreach ($convert as $origin =>$to) {
                            if (strstr($origin, 'mongodate')) {
                                if (isset($val->sec)) {
                                    $timestramp = $val->sec;
                                    if ($to == 'date') {
                                        $val = date('Y-m-d', $timestramp);
                                    } elseif ($to == 'datetime') {
                                        $val = date('Y-m-d H:i:s', $timestramp);
                                    } elseif ($to == 'int') {
                                        $val = $timestramp;
                                    }
                                }
                            } elseif (strstr($origin, 'date')) {
                                if ($to == 'date') {
                                    $val = date('Y-m-d', strtotime($val));
                                } elseif ($to == 'datetime') {
                                    $val = date('Y-m-d H:i:s', strtotime($val));
                                } elseif ($to == 'int') {
                                    $val = strtotime($val);
                                }
                            } elseif ($origin == 'int') {
                                if ($to == 'date') {
                                    $val = date('Y-m-d', $val);
                                } elseif ($to == 'datetime') {
                                    $val = date('Y-m-d H:i:s', $val);
                                } elseif ($to == 'int') {
                                    $val = $val;
                                }
                            } elseif ($origin == 'string') {
                                if ($to == 'img') {
                                    $t_width = isset($field['img_width']) ? $field['img_width'] : '100';
                                    $t_height = isset($field['img_height']) ? $field['img_height'] : '100';
                                    $val = '<img style="width:'.$t_width.'px;height:'.$t_height.'px" src="'.$val.'" />';
                                }
                            }
                        }
                    }else{
                        $orderStatusArr = Yii::$service->order->getStatusArr();
                        if(isset($orderStatusArr[$val])){
                            $val = $orderStatusArr[$val];
                        }else{
                            $val = $val;
                        }
                    }

                    if (isset($field['lang']) && !empty($field['lang'])) {
                        //var_dump($val);
                        //var_dump($orderField);
                        $val = Yii::$service->fecshoplang->getDefaultLangAttrVal($val, $orderField);
                    }
                }
                $str .= '<td>'.$val.'</td>';
            }
            $str .= '<td>
						<a title="编辑" target="dialog" class="btnEdit" mask="true" drawable="true" width="1000" height="580" href="'.$this->_editUrl.'?'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" >编辑</a>
						<a title="删除" target="ajaxTodo" href="'.$this->_deleteUrl.'?'.$csrfString.'&'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" class="btnDel">删除</a>
						
					</td>';
            $str .= '</tr>';
        }

        return $str;
    }
}
