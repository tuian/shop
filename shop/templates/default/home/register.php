<?php defined('InShopNC') or exit('Access Invalid!');?>
<style type="text/css">
.public-top-layout, .head-app, .head-search-bar, .head-user-menu, .public-nav-layout, .nch-breadcrumb-layout, #faq {
	display: none !important;
}
.public-head-layout {
	margin: 10px auto -10px auto;
}
.wrapper {
	width: 1000px;
}
#footer {
	border-top: none!important;
	padding-top: 30px;
}
.required{
	color:red;
}
</style>
<div class="nc-login-layout">
  <div class="nc-login">
    <div class="nc-login-title">
      <h3><?php echo $lang['login_register_join_us'];?></h3>
    </div>
    <div class="nc-login-content">
      <form id="register_form" method="post" action="<?php echo SHOP_SITE_URL;?>/index.php?act=login&op=usersave">
      <?php Security::getToken();?>
        <dl>
          <dt><i class="required">*</i><?php echo $lang['login_register_username'];?></dt>
          <dd style="min-height:54px;">
            <input type="text" id="user_name" name="user_name" class="text tip" title="<?php echo $lang['login_register_username_to_login'];?>" />
            <label></label>
          </dd>
        </dl>
         
        <dl>
          <dt><i class="required">*</i><?php echo $lang['login_register_pwd'];?></dt>
          <dd style="min-height:54px;">
            <input type="password" id="password" name="password" class="text tip" title="<?php echo $lang['login_register_password_to_login'];?>" />
            <label></label>
          </dd>
        </dl>
        <dl>
          <dt><i class="required">*</i><?php echo $lang['login_register_ensure_password'];?></dt>
          <dd style="min-height:54px;">
            <input type="password" id="password_confirm" name="password_confirm" class="text tip" title="<?php echo $lang['login_register_input_password_again'];?>"/>
            <label></label>
          </dd>
        </dl>
        <dl>
          <dt><i class="required">*</i><?php echo $lang['login_register_email'];?></dt>
          <dd style="min-height:54px;">
            <input type="text" id="email" name="email" class="text tip" title="<?php echo $lang['login_register_input_valid_email'];?>" />
            <label></label>
          </dd>
        </dl>
        <?php if(C('captcha_status_register') == '1') { ?>
        <dl>
          <dt><i class="required">*</i><?php echo $lang['login_register_code'];?></dt>
          <dd style="min-height:54px;">
            <input type="text" id="captcha" name="captcha" class="text w50 fl tip" maxlength="4" size="10" title="<?php echo $lang['login_register_input_code'];?>" />
            <img src="index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>" title="" name="codeimage" border="0" id="codeimage" class="fl ml5"/> <a href="javascript:void(0)" class="ml5" onclick="javascript:document.getElementById('codeimage').src='index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();"><?php echo $lang['login_register_click_to_change_code'];?></a>
            <label></label>
          </dd>
        </dl>
        <?php } ?>
		<dl style="margin-top: -10px; ">
          <dt style="width:100%;text-align:left;">
			  <span style="
				padding-left: 24px;
				line-height: 40px;
				padding-bottom: 20px;
				color: #555;
				font-weight: bold;">双赢会员，请输入以下信息</span>
		  </dt>
       </dl>
		<!-- <dl>
              <dt>会员编号</dt>
              <dd style="min-height:54px;">
                  <input type="text" id="user_no" name="user_no" class="text tip" title="与积分系统会员编号一致" maxlength="10" />
                  <label></label>
              </dd>
          </dl>-->
          <!--<dl>
              <dt>会员体系</dt>
              <dd style="min-height:54px;">
                  <select id="user_type" name="user_type" class="text tip" style="height: 30px;" title="请选择您在积分系统的会员体系">
                    <option value="13800">13800</option>
                    <option value="3800">3800</option>
                  </select>
                  <label></label>
              </dd>
          </dl>
          <dl>
              <dt>真实姓名</dt>
              <dd style="min-height:54px;">
                  <input type="text" id="user_truename" name="user_truename" class="text tip" title="请输入您在积分系统的真实姓名" maxlength="20" />
                  <label></label>
              </dd>
          </dl>-->
          <dl>
              <dt>身份证号</dt>
              <dd style="min-height:54px;">
                  <input type="text" id="user_identity" name="user_identity" class="text tip" title="请输入您在积分系统的身份证号" maxlength="30" />
                  <label></label>
              </dd>
          </dl>
          <!--<dl>
              <dt>积分系统密码</dt>
              <dd style="min-height:54px;">
                  <input type="password" id="user_password" name="user_password" class="text tip" title="请输入您在积分系统的密码" />
                  <label></label>
              </dd>
          </dl>-->
		  <dl>
              <dt>验证码</dt>
              <dd style="min-height:54px;">
                  <input type="text" id="user_verifycode" name="user_verifycode" class="text w50 fl tip" title="请输入您在积分系统的验证码" />
                  <label></label>
              </dd>
          </dl>
        <dl>
          <dt>&nbsp;</dt>
          <dd>
            <input type="submit" id="Submit" value="<?php echo $lang['login_register_regist_now'];?>" class="submit" title="<?php echo $lang['login_register_regist_now'];?>" />
            <input name="agree" type="checkbox" class="vm ml10" id="clause" value="1" checked="checked" />
            <span for="clause" class="ml5"><?php echo $lang['login_register_agreed'];?><a href="<?php echo urlShop('document', 'index',array('code'=>'agreement'));?>" target="_blank" class="agreement" title="<?php echo $lang['login_register_agreed'];?>"><?php echo $lang['login_register_agreement'];?></a></span>
            <label></label>
          </dd>
        </dl>
        <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
        <input name="nchash" type="hidden" value="<?php echo getNchash();?>" />
        <input type="hidden" name="form_submit" value="ok" />
      </form>
      <div class="clear"></div>
    </div>
    <div class="nc-login-bottom"></div>
  </div>
  <div class="nc-login-left">
    <h3><?php echo $lang['login_register_after_regist'];?></h3>
    <ol>
      <li class="ico05"><i></i><?php echo $lang['login_register_buy_info'];?></li>
      <li class="ico01"><i></i><?php echo $lang['login_register_openstore_info'];?></li>
      <li class="ico03"><i></i><?php echo $lang['login_register_sns_info'];?></li>
      <li class="ico02"><i></i><?php echo $lang['login_register_collect_info'];?></li>
      <li class="ico06"><i></i><?php echo $lang['login_register_talk_info'];?></li>
      <li class="ico04"><i></i><?php echo $lang['login_register_honest_info'];?></li>
      <div class="clear"></div>
    </ol>
    <h3 class="mt20"><?php echo $lang['login_register_already_have_account'];?></h3>
    <div class="nc-login-now mt10"><span class="ml20"><?php echo $lang['login_register_login_now_1'];?><a href="index.php?act=login&ref_url=<?php echo urlencode($output['ref_url']); ?>" title="<?php echo $lang['login_register_login_now'];?>" class="register"><?php echo $lang['login_register_login_now_2'];?></a></span><span><?php echo $lang['login_register_login_now_3'];?><a class="forget" href="index.php?act=login&op=forget_password"><?php echo $lang['login_register_login_forget'];?></a></span></div>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script> 
<script>
//注册表单提示
$('.tip').poshytip({
	className: 'tip-yellowsimple',
	showOn: 'focus',
	alignTo: 'target',
	alignX: 'center',
	alignY: 'top',
	offsetX: 0,
	offsetY: 5,
	allowTipHover: false
});

//注册表单验证
$(function(){
		jQuery.validator.addMethod("lettersonly", function(value, element) {
			return this.optional(element) || /^[^:%,'\*\"\s\<\>\&]+$/i.test(value);
		}, "Letters only please"); 
		jQuery.validator.addMethod("lettersmin", function(value, element) {
			return this.optional(element) || ($.trim(value.replace(/[^\u0000-\u00ff]/g,"aa")).length>=3);
		}, "Letters min please"); 
		jQuery.validator.addMethod("lettersmax", function(value, element) {
			return this.optional(element) || ($.trim(value.replace(/[^\u0000-\u00ff]/g,"aa")).length<=15);
		}, "Letters max please");
    $("#register_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.find('label').hide();
            error_td.append(error);
        },
        onkeyup: false,
        rules : {
            user_name : {
                required : true,
                lettersmin : true,
                lettersmax : true,
                lettersonly : true,
                remote   : {
                    url :'index.php?act=login&op=check_member&column=ok',
                    type:'get',
                    data:{
                        user_name : function(){
                            return $('#user_name').val();
                        }
                    }
                }
            },
           /* user_no : {
                required : true,
                minlength: 6,
                maxlength: 6,
                remote   : {
                    url :'index.php?act=login&op=check_member_no',
                    type:'get',
                    data:{
                        user_no : function(){
                            return $('#user_no').val();
                        }
                    }
                }
            },
            user_type : {
                required : true
            },
            user_truename : {
                required : true,
                maxlength: 20
            },*/
            user_identity : {
                /*required : true,*/
                minlength: 18,
                maxlength: 25,
                remote   : {
                    url :'index.php?act=login&op=check_member_identity',
                    type:'get',
                    data:{
                        user_identity : function(){
                            return $('#user_identity').val();
                        }
                    }
                }
            },
            /*user_password : {
                required : true,
                minlength: 6,
				maxlength: 20
            },*/
			user_verifycode : {
                /*required : true,*/
                minlength: 6,
				maxlength: 6
            },
            password : {
                required : true,
                minlength: 6,
				maxlength: 20
            },
            password_confirm : {
                required : true,
                equalTo  : '#password'
            },
            email : {
                required : true,
                email    : true,
                remote   : {
                    url : 'index.php?act=login&op=check_email',
                    type: 'get',
                    data:{
                        email : function(){
                            return $('#email').val();
                        }
                    }
                }
            },
			<?php if(C('captcha_status_register') == '1') { ?>
            captcha : {
                required : true,
                remote   : {
                    url : 'index.php?act=seccode&op=check&nchash=<?php echo getNchash();?>',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#captcha').val();
                        }
                    },
                    complete: function(data) {
                        if(data.responseText == 'false') {
                        	document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();
                        }
                    }
                }
            },
			<?php } ?>
            agree : {
                required : true
            }
        },
        messages : {
            user_name : {
                required : '<?php echo $lang['login_register_input_username'];?>',
                lettersmin : '<?php echo $lang['login_register_username_range'];?>',
                lettersmax : '<?php echo $lang['login_register_username_range'];?>',
				lettersonly: '<?php echo $lang['login_register_username_lettersonly'];?>',
				remote	 : '<?php echo $lang['login_register_username_exists'];?>'
            },
            /*user_no  : {
                required : '会员编号不能为空',
                minlength: '会员编号长度为6',
				maxlength: '会员编号长度为6',
                remote	 : '该会员编号已注册'
            },
            user_type  : {
                required : '会员体系不能为空'
            },
            user_truename  : {
                required : '真实姓名不能为空',
                maxlength: '真实姓名长度不能超过20'
            },*/
            user_identity  : {
               /*required : '身份证号不能为空',*/
                minlength: '身份证号长度最少为18位',
                maxlength: '身份证号长度最大为25位',
                remote	 : '该身份证号已注册'
            },
            /*user_password  : {
                required : '积分系统密码不能为空',
                minlength: '积分系统密码长度不能少于6',
				maxlength: '积分系统密码长度不能超过20'
            },*/
			user_verifycode  : {
                /*required : '积分系统密码不能为空',*/
                minlength: '积分系统验证码长度为6位',
				maxlength: '积分系统验证码长度为6位'
            },
            password  : {
                required : '<?php echo $lang['login_register_input_password'];?>',
                minlength: '<?php echo $lang['login_register_password_range'];?>',
				maxlength: '<?php echo $lang['login_register_password_range'];?>'
            },
            password_confirm : {
                required : '<?php echo $lang['login_register_input_password_again'];?>',
                equalTo  : '<?php echo $lang['login_register_password_not_same'];?>'
            },
            email : {
                required : '<?php echo $lang['login_register_input_email'];?>',
                email    : '<?php echo $lang['login_register_invalid_email'];?>',
				remote	 : '<?php echo $lang['login_register_email_exists'];?>'
            },
			<?php if(C('captcha_status_register') == '1') { ?>
            captcha : {
                required : '<?php echo $lang['login_register_input_text_in_image'];?>',
				remote	 : '<?php echo $lang['login_register_code_wrong'];?>'
            },
			<?php } ?>
            agree : {
                required : '<?php echo $lang['login_register_must_agree'];?>'
            }
        }
    });
});
</script>