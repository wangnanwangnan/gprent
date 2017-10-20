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
		<h1 class='title'><?= Yii::$service->page->translate->__('Edit Account'); ?></h1>
	</div>
</div>
<?= Yii::$service->page->widget->render('flashmessage'); ?>

<div class="list-block customer-login  customer-register">
	<form method="post" id="form-validate" autocomplete="off" action="<?=  $actionUrl ?>">
		<?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
		<ul>
	        <li>
                    <label for="zmauth"><?= Yii::$service->page->translate->__('Zm Authinfo');?></label>
					<div class="input-box">
                    芝麻信用是依法成立的独立信用评估及管理机构。授权后得到分数越高，代表信用越好。
                            <?php
                        if($zm_scroe) {
                            echo '芝麻信用评估：'.$zm_scroe;
                       }else{
                        ?>
                        <a style='color:#f05b72' id="go-zmauth" href='javascript:void(0)'><?= Yii::$service->page->translate->__('Zm Go Authorize');?></a>
                        <?php } ?>
                    </div>
            </li>
            <li>
                <div class="item-content">
                <?php

                    $requireZMScore = Yii::$app->params['zmScore'];
                     $requireZMScoreLow = Yii::$app->params['zmScoreLow'];
                     if($zm_scroe < $requireZMScore && $zm_scroe >= $requireZMScoreLow){
                         if($is_level==1){
                            echo '<li>您的押金 '.$cash_pledge.'，<a href="/customer/editaccount/pay"  style="color:red">点击这里</a>退押金</li>';
                         }else{
                            echo '<li>您的芝麻分不足，<a href="/customer/editaccount/pay"  style="color:red">点击这里</a>充值信用押金</li>';
                         }
                     }


                ?>
                </div>
            </li>
			<li>
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
							<input type="text" placeholder="<?= Yii::$service->page->translate->__('Email Address');?>"  style="color:#ccc;" readonly="true" id="customer_email" name="editForm[email]" value="<?= $email ?>" title="Email"  class="input-text required-entry" />
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
							<input  placeholder="First name" id="firstname" name="editForm[firstname]" value="<?= $firstname ?>" title="First Name"  class="input-text required-entry" type="text"  />
							<div class="validation-advice" id="required_current_firstname" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
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
							<input  type="text" placeholder="Last name" id="lastname" name="editForm[lastname]" value="<?= $lastname ?>" title="Last Name" maxlength="255" class="input-text required-entry" />
							<div class="validation-advice" id="required_current_lastname" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
						</div>
					</div>
				</div>
			</li>
			
			<li class="control">
				<div class="change_password_label item-content">
					<input name="editForm[change_password]" id="change_password" value="1" onclick="setPasswordForm(this.checked)" title="Change Password" class="checkbox" type="checkbox">
					<label for="change_password"><?= Yii::$service->page->translate->__('Change Password');?></label>
				</div>
			</li>
			
			<li class="fieldset_pass" style="display:none">
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
							<input placeholder="current password" title="Current Password" class="input-text required-entry" name="editForm[current_password]" id="current_password" type="password" />
							<div class="validation-advice" id="required_current_password" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
								
						</div>
					</div>
				</div>
			</li>
			
			<li class="fieldset_pass" style="display:none">
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
							<input placeholder="New password" title="New Password" class="input-text validate-password required-entry" name="editForm[password]" id="password" type="password" />
							<div class="validation-advice" id="required_new_password" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>	
						</div>
					</div>
				</div>
			</li>
			
			<li class="fieldset_pass" style="display:none">
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
							<input placeholder="Confirm New Password"  title="Confirm New Password" class="input-text validate-cpassword required-entry" name="editForm[confirmation]" id="confirmation" type="password"  />
							<div class="validation-advice" id="required_confirm_password" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
						</div>
					</div>
				</div>
			</li>
		</ul>
		<div class="clear"></div>
		<div class="buttons-set">
			<p>
				<a external href="#"  id="js_editBtn" class="button button-fill">
					<?= Yii::$service->page->translate->__('Edit Account'); ?>
				</a>
			</p>
		</div>
		
	</form>
</div>

<div style="display:none" id="dialog-form" title="提交身份信息">
  <form>
    <fieldset>
      <label for="realname">真实姓名</label>
      <input type="text" name="realname" id="realname" value="" class="text ui-widget-content ui-corner-all">
      <label for="identity_card">身份证号</label>
      <input type="text" name="identity_card" id="identity_card" value="" class="text ui-widget-content ui-corner-all">
 
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </fieldset>
  </form>
</div>

<form id='zmauth_submit' style="display:none" action='/customer/zmauth' method='post' target='_blank'>
    <input type="text" name='zm_realname' id='zm_realname'>
    <input type="text" name='zm_identity_card' id='zm_identity_card'>
</form>



<script>
<?php $this->beginBlock('customer_account_info_update') ?> 
	
	function setPasswordForm(arg){
		if(arg){
            $('.fieldset_pass').show();
        }else{
            $('.fieldset_pass').hide();
        }
    }
    function check_edit(){
        $check_current_password = true;
        $check_new_password = true;
        $check_confir_password = true;
		$check_current_firstname = true;
		$check_current_lastname = true;
		
		//$firstname = $('#firstname').val();
		$lastname = $('#lastname').val();
		$check_confir_password_with_pass = true;
		/*
		if($firstname == ''){
		   $('#firstname').addClass('validation-failed');
		   $('#required_current_firstname').show();
		   $check_current_firstname = false;
		}else{
		   $('#firstname').removeClass('validation-failed');
		   $('#required_current_firstname').hide();
		   $check_current_firstname = true;
		}
		*/
		if($lastname == ''){
		   $('#lastname').addClass('validation-failed');
		   $('#required_current_lastname').show();
		   $check_current_lastname = false;
		}else{
		   $('#lastname').removeClass('validation-failed');
		   $('#required_current_lastname').hide();
		   $check_current_lastname = true;
		}
		
        if($('#change_password').is(':checked')){
            $current_password = $('#current_password').val();
            $password = $('#password').val();
            $confirmation = $('#confirmation').val();
            if($current_password == ''){
               $('#current_password').addClass('validation-failed');
               $('.required_current_password').show();
               $check_current_password = false;
            }else{
               $('#current_password').removeClass('validation-failed');
               $('#required_current_password').hide();
               $check_current_password = true;
            }
            if($password == ''){
               $('#password').addClass('validation-failed');
               $('#required_new_password').show().html('This is a required field.');;
               $check_new_password = false;
            }else{
                if(!checkPass($password)){
                    $('#password').addClass('validation-failed');
                    $('#required_new_password').show();
                    $('#required_new_password').html('Must have 6 to 30 characters and no spaces.');
                    $check_new_password = false;
                }else{
                    $('#password').removeClass('validation-failed');
                    $('#required_new_password').hide();
                    $check_new_password = true;
                }
            }
			
            if($confirmation == ''){
               $('#confirmation').addClass('validation-failed');
               $('#required_confirm_password').show().html('This is a required field.');
               $check_confir_password = false;
            }else{
                if(!checkPass($confirmation)){
                    $('#confirmation').addClass('validation-failed');
                    $('#required_confirm_password').show();
                    $('#required_confirm_password').html('Must have 6 to 30 characters and no spaces.');
                    $check_confir_password = false;
                 }else{
					if($password != $confirmation){
						$('#confirmation').addClass('validation-failed');
						$('#required_confirm_password').show();
						$('#required_confirm_password').html('Two password is not the same！');
						$check_confir_password_with_pass = false;
					}else{
						$('#confirmation').removeClass('validation-failed');
						$('#required_confirm_password').hide();
						$check_confir_password = true;
					}
                    
                }
            }
		}
	 
		if( $check_confir_password_with_pass && $check_current_firstname && $check_current_lastname && $check_confir_password && $check_new_password && $check_current_password){
			return true;
		}else{
			return false;
		}
	}
	
	function checkPass(str){
        var re = /^\w{6,30}$/;
         if(re.test(str)){
           return true;
        }else{
           return false;
        }
    }
    function checkEmail(str){  
        var myReg = /^[-_A-Za-z0-9]+@([_A-Za-z0-9]+\.)+[A-Za-z0-9]{2,3}$/; 
        if(myReg.test(str)) return true; 
        return false; 
    } 
	$(document).ready(function(){
		$("#js_editBtn").click(function(){
			if(check_edit()){
				$("#form-validate").submit();
			}
		});
	});
$( function() {
    var dialog, form,
 
      // From http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#e-mail-state-%28type=email%29
      emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
      name = $( "#name" ),
      email = $( "#email" ),
      password = $( "#password" ),
      allFields = $( [] ).add( name ).add( email ).add( password ),
      tips = $( ".validateTips" );
 
    function updateTips( t ) {
      tips
        .text( t )
        .addClass( "ui-state-highlight" );
      setTimeout(function() {
        tips.removeClass( "ui-state-highlight", 1500 );
      }, 500 );
    }
 
    function checkLength( o, n, min, max ) {
      if ( o.val().length > max || o.val().length < min ) {
        o.addClass( "ui-state-error" );
        updateTips( "Length of " + n + " must be between " +
          min + " and " + max + "." );
        return false;
      } else {
        return true;
      }
    }
 
    function checkRegexp( o, regexp, n ) {
      if ( !( regexp.test( o.val() ) ) ) {
        o.addClass( "ui-state-error" );
        updateTips( n );
        return false;
      } else {
        return true;
      }
    }
 
    function zmauth() {
      var valid = true;
      allFields.removeClass( "ui-state-error" );
 /*
      valid = valid && checkLength( name, "username", 3, 16 );
      valid = valid && checkLength( email, "email", 6, 80 );
      valid = valid && checkLength( password, "password", 5, 16 );
 
      valid = valid && checkRegexp( name, /^[a-z]([0-9a-z_\s])+$/i, "Username may consist of a-z, 0-9, underscores, spaces and must begin with a letter." );
      valid = valid && checkRegexp( email, emailRegex, "eg. ui@jquery.com" );
      valid = valid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
 */
    
    if ( valid ) {
        var realname = $('#realname').val();
        var identity_card= $('#identity_card').val();
        
        $('#zm_realname').val(realname);
        $('#zm_identity_card').val(identity_card);
        
        

        
        $.ajax({
            url:'/customer/zmauth/exist',
            type:'POST',
            data:{zm_realname:realname, zm_identity_card:identity_card},
            success:function(is_exist){
                if(is_exist == 1){
                    alert('提交失败！该用户已经被认证过');
                    return false;
                }
                $('#zmauth_submit').submit();
            }                
        })       
        

        //dialog.dialog( "close" );
      }
      return valid;
    }
 
    dialog = $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 400,
      width: 550,
      modal: true,
      buttons: {
        "授权": zmauth,
        "取消": function() {
          dialog.dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
        allFields.removeClass( "ui-state-error" );
      }
    });
 
    form = dialog.find( "form" ).on( "submit", function( event ) {
      event.preventDefault();
      zmauth();
    });
 
    //$( "#create-user" ).button().on( "click", function() {
    $( "#go-zmauth" ).on( "click", function() {
        //dialog.dialog( "open" );
        alert('芝麻认证请在电脑端进入网站操作，认证完成后即可手机操作');
        return false;
    });
});

<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['customer_account_info_update'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

	
