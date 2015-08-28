<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="wrap">
    <div class="tabmenu">
        <?php include template('layout/submenu');?>
    </div>
    <div class="ncm-default-form">
        <form method="post" id="jftb_form" action="index.php">
            <?php Security::getToken();?>
            <input type="hidden" name="form_submit" value="ok" />
            <input type="hidden" name="act" value="predeposit" />
            <input type="hidden" name="op" value="jftb" />
            <input type="hidden" name="member_id" id="member_id" value="<?php echo $output['member_info']['member_id']; ?>" />
            <input type="hidden" name="member_name" id="member_name" value="<?php echo $output['member_info']['member_name']; ?>" />
            <!--    <dl>
        <dt><i class="required">*</i>平台充值卡号<?php /*echo $lang['nc_colon']; */?></dt>
        <dd>
          <input name="rc_sn" type="text" class="text w400" id="rc_sn" maxlength="50" /><span></span></dd>
      </dl>-->
            <dl>
                <dt><i class="required">*</i>身份证号码<?php echo $lang['nc_colon']; ?></dt>
                <dd>
                    <input type="text" id="user_identity" name="user_identity" value="<?php echo $output['member_info']['member_identity']; ?>" class="text tip" title="请输入您在积分系统的身份证号码" maxlength="30" style="width: 200px; height: 20px;"/>
                    <span></span>
                </dd>
            </dl>
            <dl>
                <dt><i class="required">*</i>真实姓名<?php echo $lang['nc_colon']; ?></dt>
                <dd>
                    <input type="text" id="user_truename" name="user_truename" value="<?php echo $output['member_info']['member_truename']; ?>" class="text tip" title="请输入您在积分系统的真实姓名" maxlength="20" style="width: 200px; height: 20px;"/>
                    <span></span>
                </dd>
            </dl>
            <dl>
                <dt><i class="required">*</i>积分系统密码<?php echo $lang['nc_colon']; ?></dt>
                <dd>
                    <input type="password" id="jfxt_password" name="jfxt_password" value="<?php echo $output['member_info']['jfxt_password']; ?>" class="text tip" title="请输入您在积分系统的密码" style="width: 200px; height: 20px;"/>
                    <span></span>
                </dd>
            </dl>
            <dl class="bottom">
                <dt>&nbsp; </dt>
                <dd>
                    <label class="submit-border"><input type="button" class="submit" id="submitButton" value="同步积分" onclick="submitForm();" /></label>
                </dd>
            </dl>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('#jftb_form').validate({
            errorPlacement: function(error, element){
                var error_td = element.parent('dd').children('span');
                error_td.append(error);
            },
            rules : {
                user_truename : {
                    required : true,
                    maxlength: 20
                },
                user_identity : {
                    required : true,
                    minlength: 18,
                    maxlength: 25,
                    remote   : {
                        url :'index.php?act=login&op=check_member_identity',
                        type:'get',
                        data:{
                            user_identity : function(){
                                return $('#user_identity').val();
                            },
                            user_id : function(){
                                return $('#member_id').val();
                            }
                        }
                    }
                },
                jfxt_password : {
                    required : true,
                    minlength: 6,
                    maxlength: 20
                }
            },
            messages : {
                /*rc_sn : {
                 required :'<i class="icon-exclamation-sign"></i>请输入平台充值卡号',
                 maxlength :'<i class="icon-exclamation-sign"></i>平台充值卡号长度小于50'
                 },*/
                user_truename  : {
                    required : '真实姓名不能为空',
                    maxlength: '真实姓名长度不能超过20'
                },
                user_identity  : {
                    required : '身份证号码不能为空',
                    minlength: '身份证号码长度最少为18位',
                    maxlength: '身份证号码长度最大为25位',
                    remote	 : '该身份证号码已被其他用户使用'
                },
                jfxt_password  : {
                    required : '积分系统密码不能为空',
                    minlength: '积分系统密码长度不能少于6',
                    maxlength: '积分系统密码长度不能超过20'
                }
            }
        });
    });

    var isSubmitIng = false;
    function submitForm(){
        if(isSubmitIng){
           return;
        }
        isSubmitIng = true;
        document.getElementById("jftb_form").submit();
    }
</script>
