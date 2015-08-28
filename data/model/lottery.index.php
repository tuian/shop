<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>抽奖管理</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span>管理</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table class="table tb-type2">
     <tbody>
        <tr class="noborder">
            <td style="font-weight: bold;width: 100px;"><label>活动名称:</label></td>
            <td colspan="2">砸金蛋</td>
        </tr>
        <tr class="noborder">
            <td style="font-weight: bold;width: 100px;"><label>参与总人数:</label></td>
            <td style="width: 100px;"><?php echo $output['participantAmount']; ?></td>
            <td><a href="index.php?act=lottery&op=edit&awards_id=<?php echo $v['awards_id']; ?>">查看</a></td>
        </tr>
        <tr class="noborder">
            <td style="font-weight: bold;width: 100px;"><label>中奖总人数:</label></td>
            <td style="width: 100px;">100</td>
            <td><a href="index.php?act=lottery&op=edit&awards_id=<?php echo $v['awards_id']; ?>">查看</a></td>
        </tr>
     </tbody>
  </table>
  <table class="table tb-type2 nobdb">
    <thead>
      <tr class="thead">
        <th>奖项名称</th>
        <th>奖品名称</th>
        <th>中奖几率</th>
        <th>奖品数量</th>
        <th>已中数量</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['awards_list']) && is_array($output['awards_list'])){ ?>
      <?php foreach($output['awards_list'] as $k => $v){ ?>
      <tr class="hover">
        <td ><?php echo $v['awards_name']; ?></td>
        <td ><?php echo $v['prize_name']; ?></td>
        <td ><?php echo (float)$v['win_rate']; ?></td>
        <td ><?php echo $v['prize_amount']; ?></td>
        <td ><?php echo $v['win_amount']; ?></td>
        <td class="w96"><a href="index.php?act=lottery&op=edit&awards_id=<?php echo $v['awards_id']; ?>">编辑</a></td>
      </tr>
      <?php } ?>
      <?php }else { ?>
      <tr class="no_data">
        <td colspan="6"><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
