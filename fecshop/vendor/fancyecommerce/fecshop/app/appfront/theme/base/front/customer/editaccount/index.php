<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>

<style>
    label, input { display:block; }
    input.text { margin-bottom:12px; width:95%; padding: .4em; }
    fieldset { padding:0; border:0; margin-top:25px; }
    h1 { font-size: 1.2em; margin: .6em 0; }
    div#users-contain { width: 350px; margin: 20px 0; }
    div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
    div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
    .ui-dialog .ui-state-error { padding: .3em; }
    .validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>

<div class="main container two-columns-left">
<?= Yii::$service->page->widget->render('flashmessage'); ?>
	<div class="col-main account_center">
		<div class="std">
			<div style="margin:4px 0 0">
				<div class="page-title">
					<h2><?= Yii::$service->page->translate->__('Edit Account Information');?></h2>
				</div>
				<form method="post" id="form-validate" autocomplete="off" action="<?=  $actionUrl ?>">
					<?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
					<div class="">
						<ul class="">
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
                        
                            <?php
                             $requireZMScore = Yii::$app->params['zmScore'];
                             $requireZMScoreLow = Yii::$app->params['zmScoreLow'];
                             if($zm_scroe < $requireZMScore && $zm_scroe >= $requireZMScoreLow){
                                 if($is_level){
                                    echo '<li>您的押金 '.$cash_pledge.'，<a href="javascript:;" onclick="backmoney()"  style="color:red">点击这里</a>退押金</li>';
                                 }else{
                                    echo '<li>恭喜您！您已具备我们的租赁条件，请快去挑选喜爱的道具吧，<a href="/customer/editaccount/pay"  style="color:red">点击这里</a>充值信用押金</li>';
                                 }
                             }
                             ?>
                             <div style="font-size:80%;color:#0b84d3;">
                                        注:芝麻信用分700分以上免押金租借总价值2000元以下道具<br>
                                        &nbsp;&nbsp;&nbsp;&nbsp;芝麻信用分640-700分可免押金租借总价值500元以下道具<br>
                                        &nbsp;&nbsp;&nbsp;&nbsp;缴纳198元押金（无正在租借道具订单时刻随时申请退回）<br>
                                        &nbsp;&nbsp;&nbsp;&nbsp;芝麻信用分640-700分可免押金租借总价值2000元以下道具
                            </div>
                            <?php
                                if(!empty($steamid)){
                            ?>
							<li>
								<label for="email">steamID</label>
								<div class="input-box">
                                    <?= $steamid ?>
								</div>
							</li>
							<li>
								<label for="email">steam用户名</label>
								<div class="input-box">
                                    <?= $lastname ?>
								</div>
							</li>

                            <?php
                                }else{
                            ?>
							<li>
								<label for="email" class="required"><?= Yii::$service->page->translate->__('Email Address');?></label>
								<div class="input-box">
									<input style="color:#ccc;" readonly="true" id="customer_email" name="editForm[email]" value="<?= $email ?>" title="Email" maxlength="255" class="input-text required-entry" type="text">
								</div>
							</li>
							<li>
								<div class="field name-lastname">
                                    <label for="lastname" class="required"><?= Yii::$service->page->translate->__('Last Name');?></label>
                                    <div class="input-box">
                                        <input id="lastname" name="editForm[lastname]" value="<?= $lastname ?>" title="Last Name" maxlength="255" class="input-text required-entry" type="text">
                                        <div class="validation-advice" id="required_current_lastname" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
                                    </div>
                                </div>
							</li>
                            <?php } ?>
                            <!--
							<li class="">
                                <div class="field name-firstname">
                                    <label for="firstname" class="required"><?= Yii::$service->page->translate->__('First Name');?></label>
                                    <div class="input-box">
                                        <input id="firstname" name="editForm[firstname]" value="<?= $firstname ?>" title="First Name" maxlength="255" class="input-text required-entry" type="text">
                                        <div class="validation-advice" id="required_current_firstname" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
                                    </div>
                                </div>
							</li>
							<li class="control">
								<input name="editForm[change_password]" id="change_password" value="1" onclick="setPasswordForm(this.checked)" title="Change Password" class="checkbox" type="checkbox">
								<label style="display:inline;" for="change_password"><?= Yii::$service->page->translate->__('Change Password');?></label>
							</li>
                            -->
						</ul>
					</div>
				
					<div class="" id="fieldset_pass" style="display:none;">
						
						<ul class="form-list">
							<li>
								<label style="font-weight:100;" for="current_password" class="required"><?= Yii::$service->page->translate->__('Current Password');?></label>
								<div class="input-box">
									<input title="Current Password" class="input-text required-entry" name="editForm[current_password]" id="current_password" type="password">
									<div class="validation-advice" id="required_current_password" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
								</div>
							</li>
							<li class="fields">
								<div class="field">
									<label style="font-weight:100;" for="password" class="required"><?= Yii::$service->page->translate->__('New Password');?></label>
									<div class="input-box">
										<input title="New Password" class="input-text validate-password required-entry" name="editForm[password]" id="password" type="password">
										<div class="validation-advice" id="required_new_password" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
									</div>
								</div>
								<div class="field">
									<label style="font-weight:100;" for="confirmation" class="required"><em>*</em><?= Yii::$service->page->translate->__('Confirm New Password');?></label>
									<div class="input-box">
										<input title="Confirm New Password" class="input-text validate-cpassword required-entry" name="editForm[confirmation]" id="confirmation" type="password">
										<div class="validation-advice" id="required_confirm_password" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
									</div>
								</div>
								<div class="clear"></div>
							</li>
						</ul>
					</div>
                    <?php
                        if(empty($steamid)){
                    ?>
					<div class="buttons-set">
						<button type="submit" title="Save" class="button" onclick="return check_edit()"><span><span><?= Yii::$service->page->translate->__('Submit');?></span></span></button>
					</div>
                    <?php } ?>
				</form>
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
<div style="display:none" id="dialog-form" title="提交身份信息">
  <form>
    <fieldset>
      <label for="realname">真实姓名</label>
      <input type="text" name="realname" id="realname" value="" class="text ui-widget-content ui-corner-all">
      <label for="identity_card">身份证号</label>
      <input type="text" name="identity_card" id="identity_card" value="" class="text ui-widget-content ui-corner-all" style="text-transform: uppercase;">
 
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </fieldset>
  </form>
</div>

<form id='zmauth_submit' style="display:none" action='/customer/zmauth' method='post' onsubmit="return false" <!-- target='_blank' -->>
    <input type="text" name='zm_realname' id='zm_realname'>
    <input type="text" name='zm_identity_card' id='zm_identity_card'>
</form>

<script>
<?php $this->beginBlock('customer_account_info_update') ?> 
	function setPasswordForm(arg){
        if(arg){
            $('#fieldset_pass').show();
        }else{
            $('#fieldset_pass').hide();
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
               $('#required_current_password').show();
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
                var zm_window = window.open();
                zm_window.location = '/customer/zmauth?zm_realname=' + $('#realname').val() + '&zm_identity_card=' + $('#identity_card').val();
                //$('#zmauth_submit').submit();
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
        dialog.dialog( "open" );
        return false;
    });
});

function backmoney()
{
    if(confirm('确定申请退回押金?')){
        self.location = "/customer/editaccount/backmoney";
    }
}
 
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['customer_account_info_update'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

	
