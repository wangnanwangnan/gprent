<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php  $address_list = $parentThis['address_list'];   ?>
<?php  $cart_address_id = $parentThis['cart_address_id'];   ?>
<?php  $country_select = $parentThis['country_select'];   ?>
<?php  $state_html = $parentThis['state_html'];   ?>
<?php  $cart_address = $parentThis['cart_address'];   ?>

<div id="billing_address">		
	<ul>
		<li>
			<p class="onestepcheckout-numbers onestepcheckout-numbers-1"><?= Yii::$service->page->translate->__('Steam Account');?></p>
		</li>
		<li>
			<div>
				<select name="address_id" class="address_list">
					<?php  	if(is_array($address_list) && !empty($address_list)):    ?>
					<?php  	    foreach($address_list as $address_id => $info):  ?>
					<?php  	        if($cart_address_id == $address_id ): 
                                        $str = 'selected="true;"';
                                    else:  
                                        $str = ''; 
                                    endif;
					?>
					<option <?= $str  ?> value="<?= $address_id ?>"><?= $info['address'] ?></option>
					
					<?php       endforeach;  ?>
					<?php  endif;  ?>
					<option value=""> <?= Yii::$service->page->translate->__('New Address');?> </option>
				</select>
				<ul id="billing_address_list" class="billing_address_list_new" style="display:none;">			
					<li class="clearfix">
						<div class="input-box input-steam">
							<label for="billing:steam_link"><?= Yii::$service->page->translate->__('Steam Link');?><span class="required">*</span></label>
							<input value="<?= $cart_address['steam_link'] ?>" id="billing:steam_link" name="billing[steam_link]" class="required-entry input-text" type="text">
						<?php
                            //if(!empty($cart_address['trackLink'])){
                                echo '<br><a href="'.$cart_address[trackLink].'" style="color:#0b84d3;" target="_black"> 前往steam获取交易链接 >></a>';
                            //}
                        ?>
                        </div>
						<div class="clear"></div>
					</li>
					<li class="clearfix">
                        <!--
						<div class="input-box input-firstname">
							<label for="billing:firstname"><?= Yii::$service->page->translate->__('First Name');?><span class="required">*</span></label>
							<input value="<?= $cart_address['first_name'] ?>" id="billing:firstname" name="billing[first_name]" class="required-entry input-text" type="text">
						</div>
                        -->
						<div class="input-box input-lastname">
							<label for="billing:lastname"><?= Yii::$service->page->translate->__('Last Name');?> <span class="required">*</span></label>
							<input value="<?= $cart_address['last_name'] ?>" id="billing:lastname" name="billing[last_name]" class="required-entry input-text" type="text">
						</div>
						<div class="clear"></div>
					</li>
					<li class="clearfix">
						<div style="width:100%;" class="  input-box input-email">
							<label for="billing:email"><?= Yii::$service->page->translate->__('Email Address');?> <span class="required">*</span></label>
							<input style="width:83%;" value="<?= $cart_address['email'] ?>" class="validate-email required-entry input-text" title="Email Address" id="billing:email" name="billing[email]" type="text">
							<div class="customer_email_validation">
							
							</div>
						</div>
					</li>
					<li>
						<div style="width:100%;" class="input-box input-telephone">
							<label for="billing:telephone"><?= Yii::$service->page->translate->__('Telephone');?> <span class="required">*</span></label>
							<input style="width:83%;" value="<?= $cart_address['telephone'] ?>" id="billing:telephone" class="required-entry input-text" title="Telephone" name="billing[telephone]" type="text">
						</div>
					</li>
                    <!--
					<li class="clearfix">
						<div class="input-box input-address">
							<label for="billing:street1"><?= Yii::$service->page->translate->__('Street');?><span class="required">*</span></label>
							<input value="<?= $cart_address['street1'] ?>" class="required-entry input-text onestepcheckout-address-line" id="billing:street1" name="billing[street1]" title="Street Address 1" type="text">
							<br>
							<input value="<?= $cart_address['street2'] ?>" class="input-text onestepcheckout-address-line" id="billing:street2" name="billing[street2]" title="Street Address 2" type="text">
						</div>
					</li>
					<li class="clearfix">
						<div class="input-box input-country">
							<label for="billing:country"><?= Yii::$service->page->translate->__('Country');?> <span class="required">*</span></label>
									<select title="Country" class="billing_country validate-select" id="billing:country" name="billing[country]">
										<?=  $country_select ?>
									</select>
							</div>
					</li>
					<li class="clearfix">
						<div class="input-box input-state"><label for="billing:state" class="required"><?= Yii::$service->page->translate->__('State');?> <span class="required">*</span></label>
							<div class="state_html">
							<?=  $state_html ?>
							</div>
						</div>
					</li>
					<li class="clearfix">
						<div class="input-box input-city">
							<label for="billing:city"><?= Yii::$service->page->translate->__('City');?> <span class="required">*</span></label>
							<input value="<?= $cart_address['city'] ?>" id="billing:city" class="required-entry input-text" title="City" name="billing[city]" type="text">
						</div>
					</li>
					<li class="clearfix">
						<div class="input-box input-zip">
							<label for="billing:zip"><?= Yii::$service->page->translate->__('Zip Code');?> <span class="required">*</span></label>
							<input value="<?= $cart_address['zip'] ?>" class="validate-zip-international required-entry input-text" id="billing:zip" name="billing[zip]" title="Zip Code" type="text">
						</div>
						
					</li>
                    -->
					<!--
					<li class="control">
						<input class="save_in_address_book checkbox" id="billing:save_in_address_book" title="Save in address book" value="1" name="billing[save_in_address_book]" checked="checked" type="checkbox"><label for="billing:save_in_address_book">Save in address book</label>
					</li>   
					-->					
				</ul>							
			</div>
		</li>
		
	</ul>
</div>
