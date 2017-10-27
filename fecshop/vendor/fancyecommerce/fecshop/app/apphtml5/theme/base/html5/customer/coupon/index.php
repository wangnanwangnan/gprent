<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="account-ds">
	<div class="bar bar-nav account-top-m">
		<a external class="button button-link button-nav pull-left" href="<?= Yii::$service->url->getUrl('customer/account/index'); ?>">
			<span class="icon icon-left"></span>
		</a>
		<h1 class='title'>我的优惠券</h1>
	</div>
</div>
<?= Yii::$service->page->widget->render('flashmessage'); ?>

<div class="order_list">
<?= Yii::$service->page->widget->render('flashmessage'); ?>

	<div class="col-main account_center">
		<div class="std">
			<div style="margin:4px 0 0">
				
				<table id="my-orders-table" class="edit_order">
					<thead>
						<tr class="first last">
							<th>优惠码</th>
							<th>过期时间</span></th>
							<th>添加时间</span></th>

						</tr>
					</thead>
					<tbody>
					<?php  if(is_array($coupon_list) && !empty($coupon_list)):  
                            $status = ['未使用','已使用','已过期'];
                    ?>
						<?php foreach($coupon_list as $coupon): 
							
						?>
							<tr class="first odd">
								<td>
									<b><?= $coupon['coupon'] ?></b><br/>
									<span class="order-status "><?= $status[$coupon['status']] ?></span>
								</td>
								<td><span class="nobr"><?= date('Y-m-d H:i:s',$coupon['expiration_date']) ?></span></td>
								<td>
									<b><?= $coupon['coupon_msg'] ?></b><br/>
									<span class="order-status "><?= $coupon['add_time'] ?></span>
								</td>
							</tr>
						
						<?php endforeach; ?>
					<?php endif; ?>
						
					</tbody>
				</table>
				<?php if($pageToolBar): ?>
					<div class="pageToolbar">
						<label class="title"><?= Yii::$service->page->translate->__('Page:');?></label><?= $pageToolBar ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	
	<div class="clear"></div>
</div>
	
