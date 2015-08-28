<?php defined('InShopNC') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <form method="get" action="index.php">
    <table class="ncm-search-table">
      <input type="hidden" name="act" value="member_lotterys" />
      <input type="hidden" name="op" value="lotteryList" />
      <tr><td class="w10">&nbsp;</td>
        <th>是否已领奖</th>
        <td class="w100"><select name="is_get">
            <option value="" ><?php echo $lang['nc_please_choose'];?></option>
            <option value="1" <?php if ($output['is_get'] == 1){echo 'selected=selected';}?>>已领奖</option>
            <option value="0" <?php if ($output['is_get'] == 0){echo 'selected=selected';}?>>未领奖</option>
          </select>
        </td>
        <td class="w70 tc">
            <label class="submit-border">
                  <input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" />
            </label>
        </td>
      </tr>
    </table>
  </form>
  <table class="ncm-default-table">
    <thead>
      <tr>
        <th class="w150">中奖编号</th>
        <th class="w150">奖项名称</th>
        <th class="w150">奖品名称</th>
        <th class="w150">中奖时间</th>
        <th class="w150">是否已领奖</th>
        <th class="tl">领奖时间</th>
      </tr>
    </thead>
    <tbody>
      <?php  if (count($output['win_list'])>0) { ?>
      <?php foreach($output['win_list'] as $val) { ?>
      <tr class="bd-line">
        <td><?php echo $val['id'];?></td>
        <td><?php echo $val['awards_name']; ?></td>
        <td><?php echo $val['prize_name']; ?></td>
        <td><?php
	              	switch ($val['is_get']){
	              		case '1':
	              			echo '已领奖';
	              			break;
	              		case '0':
	              			echo '未领奖';
	              			break;
	              	}
	              ?></td>
        <td class="tl"><?php echo  @date('Y-m-d G:i:s',$val['get_time']);?></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record']; ?></span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php  if (count($output['win_list'])>0) { ?>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script> 
