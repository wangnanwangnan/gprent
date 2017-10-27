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
	<div class="col-main account_center">
		<div class="std">
			<div style="margin:4px 0 0">
				<div class="page-title">
					<h2><?= Yii::$service->page->translate->__('My Dashboard'); ?></h2>
				</div>
                <!--
				<div class="welcome-msg">
					<p class="hello"><strong><?= Yii::$service->page->translate->__('Hello'); ?>,  !</strong></p>
					<p><?= Yii::$service->page->translate->__('From your My Account Dashboard you have the ability to view a snapshot of your recent account activity and update your account information. Select a link below to view or edit information.'); ?></p>
				</div>
                -->
				<div class="box-account box-info">
					<div class="col2-set">
						<div class="col-3">
							<div class="box">
                            <!--
								<div class="box-title">
									<h3><?= Yii::$service->page->translate->__('Contact Information'); ?></h3>
									<a href="<?= $accountEditUrl ?>"><?= Yii::$service->page->translate->__('Edit'); ?></a>
								</div>
                                -->
								<div class="box-content">
									<div>							
										<span style="margin:0 0px;"><?= $email ?></span>
									</div>
								</div>
                                <div class="box-title">
									<h3><?= Yii::$service->page->translate->__('Invite Url'); ?>:<span style="margin:0 10px;color:red;" id='inviteurl'>http://www.gprent.cn/customer/account/login?invite_code=<?= $invite ?></span></h3>
                                    <h5>注：使用您的专属推广链接，通过此链接进行注册并完成首次租赁的用户，您将获得5折租赁优惠券一张！</h5>
								</div>

							</div>
						</div>
					</div>
					<div class="col2-set addressbook">
						<div class="col2-set">
							<div class="col-1">
								<div class="box">
									<div class="box-title">
										<h3>
                                            <?= Yii::$service->page->translate->__('My Address Book'); ?>
                                            <?php if(!empty($trackLink)){
                                                        echo '<a href="'.$trackLink.'" style="color:#0b84d3;" target="_blank"> 前往steam获取交易链接 >></a>';
                                                    }else{
                                                        echo '<a href="/customer/account/login?steam=1" style="color:#0b84d3;" target="_blank"> 绑定steam帐号 >></a>';
                                                    }
                                            ?>
                                        </h3>
                                    </div>
									<div class="box-content">
										<p><?= Yii::$service->page->translate->__('You Can Manager Your Address'); ?>. </p>
										<a href="<?= $accountAddressUrl ?>"><?= Yii::$service->page->translate->__('Manager Addresses'); ?></a>
									</div>
								</div>
							</div>
							<div class="col-2">
								<div class="box">
									<div class="box-title">
										<h3><?= Yii::$service->page->translate->__('My Order'); ?></h3>
									</div>
									<div class="box-content">
										<p><?= Yii::$service->page->translate->__('You Can View Your Order'); ?>. </p>
										<a href="<?= $accountOrderUrl ?>"><?= Yii::$service->page->translate->__('View'); ?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
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
	
