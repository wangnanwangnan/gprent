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
		<a external class="button button-link button-nav pull-left" href="<?= Yii::$service->url->getUrl('customer/address'); ?>">
			<span class="icon icon-left"></span>
		</a>
		<h1 class='title'><?= Yii::$service->page->translate->__('Address Book'); ?></h1>
	</div>
</div>
<?= Yii::$service->page->widget->render('flashmessage'); ?>	
<div class="list-block customer-login  customer-register">
	<form class="addressedit" action="<?= Yii::$service->url->getUrl('customer/address/edit'); ?>" id="form-validate" method="post">
		<?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
		<input name="address[address_id]" value="<?= $address_id; ?>" type="hidden">
					
		<ul>
			<li>
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
							<input class="input-text required-entry" maxlength="255" placeholder="Email" value="<?= $email ?>" name="address[email]" id="customer_email"   type="text">
						</div>
					</div>
				</div>
			</li>				
	<!--		
			<li>
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
							<input class="input-text required-entry" maxlength="255" placeholder="First Name" title="First Name" value="<?= $first_name ?>" name="address[first_name]" id="firstname" type="text">
						</div>
					</div>
				</div>
			</li>	
		-->	
			<li>
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
							<input class="input-text required-entry" maxlength="255" placeholder="Last Name"  title="Last Name" value="<?= $last_name ?>" name="address[last_name]" id="lastname" type="text">
						</div>
					</div>
				</div>
			</li>	
			
			<li>
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
							<input class="input-text required-entry" maxlength="255" placeholder="Telephone"  title="telephone" value="<?= $telephone ?>" name="address[telephone]" id="telephone" type="text">
						</div>
					</div>
				</div>
			</li>	
		<!--	
			<li>
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
							<select id="address:country" class="address_country validate-select" placeholder="Country"   title="Country" name="address[country]">
								<?= $countrySelect;  ?>
							</select>
						</div>
					</div>
				</div>
			</li>	
			
			<li>
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input state_html">
							<?= $stateHtml;  ?>
							
						</div>
					</div>
				</div>
			</li>	
			
			<li>
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
							<input class="input-text required-entry" maxlength="255" placeholder="City" title="City" value="<?= $city ?>" name="address[city]" id="city" type="text" />
						</div>
					</div>
				</div>
			</li>	
			
			
			<li>
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
							<input class="input-text required-entry" maxlength="255" placeholder="street1" value="<?= $street1 ?>" name="address[street1]" id="street1" type="text" />
						</div>
					</div>
				</div>
			</li>	
			
			<li>
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
							<input class="input-text required-entry" maxlength="255" placeholder="street2" value="<?= $street2 ?>" name="address[street2]" id="street2" type="text" />
						</div>
					</div>
				</div>
			</li>		
			
			-->
			<li>
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
						    <input class="input-text required-entry"  placeholder="steam交易链接"  maxlength="255" title="SteamLink" value="<?= $steam_link ?>" name="address[steam_link]" id="SteamLink" type="text">
                            <?php
                                if(!empty($trackLink)){
                                    echo '<a href="'.$trackLink.'" style="color:#0b84d3;" target="_black"> 前往steam获取交易链接 >></a>';
								}
                            ?>
						</div>
					</div>
				</div>
			</li>	
			
			<li class="control">
				<div class="change_password_label item-content">
					<input name="address[is_default]" value="1" title="Save in address book" id="address:is_default" class="address_is_default checkbox" <?= $is_default_str; ?> type="checkbox">
						<label for="address:is_default" style="display:inline;"><?= Yii::$service->page->translate->__('Is Default');?></label>
						
				</div>
			</li>
		</ul>	
		<div class="clear"></div>
		<div class="buttons-set">
			<p>
				<a external href="javascript:void(0)" onclick="submit_address()"   id="js_registBtn" class="button button-fill">
					<?= Yii::$service->page->translate->__('Save Address'); ?>
				</a>
			</p>
		</div>
	</form>
</div>

<script>
<?php $this->beginBlock('editCustomerAddress') ?>
	$(document).ready(function(){
		$(".address_country").change(function(){
			//alert(111);
			ajaxurl = "<?= Yii::$service->url->getUrl('customer/address/changecountry') ?>";
			country = $(this).val();
			$.ajax({
				async:false,
				timeout: 8000,
				dataType: 'json', 
				type:'get',
				data: {
						'country':country,
				},
				url:ajaxurl,
				success:function(data, textStatus){ 
					$(".state_html").html(data.state);
				},
				error:function (XMLHttpRequest, textStatus, errorThrown){
						
				}
			});
			
		});

	});	
	function submit_address(){
		i = 1;
		$(".addressedit input").each(function(){
			type = $(this).attr("type");
			if(type != "hidden"){
				value = $(this).val();
				if(!value){
					//alert($(this).hasClass('optional'));
					if(!$(this).hasClass('optional')){
						i = 0;
					}
				}
			}
		});
		var phone = $("#telephone").val();
		if(phone){
            var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
            if(!myreg.test(phone)) 
            { 
                    alert('请输入有效的手机号码！'); 
                    return false; 
            } 
        }

		$(".addressedit select").each(function(){
			value = $(this).val();
			if(!value){
				i = 0;
			}
		});
		if(i){
			$(".addressedit").submit();
		}else{
			alert("请将信息填写完整");
		}
	}
	
	
<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['editCustomerAddress'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

</script>
