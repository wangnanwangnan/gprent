<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main container two-columns-left">
<?= Yii::$service->page->widget->render('flashmessage'); ?>
	<div class="col-main account_center">
		<div class="std">
			<div style="margin:4px 0 0">
				<div class="page-title">
					<h2>我的优惠码</h2>
				</div>
				<table id="my-orders-table" class="edit_order">
					<thead>
						<tr class="first last">
							<th>id </th>
							<th>优惠码</th>
							<th>状态</th>
							<th>过期时间</span></th>
							<th>来源</span></th>
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
								<td><?= $coupon['id'] ?></td>
								<td><span class="nobr"><?= $coupon['coupon'] ?></span></td>
								<td><?= $status[$coupon['status']] ?></td>
								<td><?= date('Y-m-d H:i:s',$coupon['expiration_date']) ?></td>
								<td><?= $coupon['coupon_msg'] ?></td>
								<td><em><?= $coupon['add_time'] ?></em></td>
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
	
	<div class="col-left ">
		<?php
			$leftMenu = [
				'class' => 'fecshop\app\appfront\modules\Customer\block\LeftMenu',
				'view'	=> 'customer/leftmenu.php'
			];
		?>
		<?= Yii::$service->page->widget->render($leftMenu,$this); ?>
	</div>
	<div class="clear"></div>
</div>
	
