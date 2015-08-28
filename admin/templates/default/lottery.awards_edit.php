<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>抽奖管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=lottery&op=index"><span>管理</span></a></li>
        <li><a href="javascript:;" class="current"><span>编辑奖项</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
    <?php
    function floattostr( $val )
    {
        preg_match( "#^([+-]|)([0-9]*)(.([0-9]*?)|)(0*)$#", trim($val), $o );
        return $o[1].sprintf('%d',$o[2]).($o[3]!='.'?$o[3]:'');
    }
    ?>
  <form id="add_form" method="post" enctype="multipart/form-data" action="index.php?act=lottery&op=edit_awards">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="submit_type" id="submit_type" value="" />
	<input type="hidden" name="awards_id" id="awards_id" value="<?php echo $output['awards_info']['awards_id'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="" for="class_name">奖项名称<?php echo $lang['nc_colon']; ?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <?php echo $output['awards_info']['awards_name'];?>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><span style="color:red;margin-left:0;">*</span><label for="class_sort" class="">奖品名称<?php echo $lang['nc_colon']; ?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="prize_name" name="prize_name" type="text" class="txt" value="<?php echo $output['awards_info']['prize_name'];?>" /></td>
        </tr>
		<tr>
          <td colspan="2" class="required"><span style="color:red;margin-left:0;">*</span><label for="class_sort" class="">中奖率<?php echo $lang['nc_colon']; ?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop">
			<input id="win_rate" name="win_rate" type="text" class="txt" value="<?php echo floattostr($output['awards_info']['win_rate']);?>" /><span>格式：0.01</span>
		  </td>
        </tr>
		<tr>
          <td colspan="2" class="required"><span style="color:red;margin-left:0;">*</span><label for="class_sort" class="">奖品数量<?php echo $lang['nc_colon']; ?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop "><input id="prize_amount" name="prize_amount" type="text" class="txt" value="<?php echo $output['awards_info']['prize_amount'];?>" /></td>
        </tr>
		<tr>
          <td colspan="2" class="required"><label for="class_sort" class="">已中数量<?php echo $lang['nc_colon']; ?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['awards_info']['win_amount'];?></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2">
            <a id="submit" href="javascript:void(0)" class="btn"><span>提交</span></a>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#submit').click(function(){
        $('#add_form').submit();
    });

    $('#add_form').validate({
        errorPlacement: function(error, element){
            error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        success: function(label){
            label.addClass('valid');
        },
        rules : {
            prize_name: {
                required : true,
                maxlength : 20
            },
            win_rate: {
                required : true,
                range:[0,1]
            },
			prize_amount: {
                required : true,
                digits: true,
                min:<?php echo $output['awards_info']['win_amount'];?>
            }
        },
        messages : {
            prize_name: {
                required : "奖项名称不能为空",
                maxlength : "奖项名称长度最多20个字符"
            },
			win_rate: {
                required : "中奖率不能为空",
                range : "中奖率为0到1之间的小数"
            },
            prize_amount: {
                required : "奖品数量不能为空",
                digits: "奖品数量必须是数字",
                min : "奖品数量不能小于已中数量"
            }
        }
    });
});
</script>
