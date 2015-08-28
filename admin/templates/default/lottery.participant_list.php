<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>抽奖管理</h3>
      <ul class="tab-base">
          <li><a href="index.php?act=lottery&op=index"><span>管理</span></a></li>
          <li><a href="index.php?act=lottery&op=win_list"><span>中奖列表</span></a></li>
          <li><a href="javascript:void(0);" class="current"><span>参与记录</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
    <form method="get" name="formSearch">
        <input type="hidden" name="act" value="lottery">
        <input type="hidden" name="op" value="participant_list">
        <table class="tb-type1 noborder search">
            <tbody>
            <tr>
                <th><label>是否中奖</label></th>
                <td><select name="is_win">
                        <option value="" <?php if (empty($output['is_win'])){echo 'selected=selected';}?>><?php echo $lang['nc_please_choose'];?></option>
                        <option value="1" <?php if ($output['is_win'] == '1'){echo 'selected=selected';}?>>中奖</option>
                        <option value="0" <?php if ($output['is_win'] == '0'){echo 'selected=selected';}?>>未中奖</option>
                    </select>
                </td>
                <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo $lang['nc_query']; ?>">&nbsp;</a></td>
            </tr>
            </tbody>
        </table>
    </form>
  <table class="table tb-type2 nobdb">
    <thead>
      <tr class="thead">
        <th>抽奖编号</th>
        <th>会员名称</th>
        <th>是否中奖</th>
        <th>中奖时间</th>
        <th>奖项名称</th>
        <th>奖品名称</th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['participant_list']) && is_array($output['participant_list'])){ ?>
      <?php foreach($output['participant_list'] as $k => $v){ ?>
      <tr class="hover">
        <td ><?php echo $v['id']; ?></td>
        <td ><?php echo $v['member_name']; ?></td>
        <td >
              <?php
              switch ($v['is_win']){
                  case '1':
                      echo '中奖';
                      break;
                  case '0':
                      echo '未中奖';
                      break;
              }
              ?>
        </td>
        <td ><?php echo $v['participant_time']; ?></td>
        <td ><?php echo $v['awards_name']; ?></td>
        <td ><?php echo $v['prize_name']; ?></td>
       <!-- <td >
            <?php
/*            switch ($v['is_get']){
                case '1':
                    echo '已领奖';
                    break;
                case '0':
                    echo '未领奖';
                    break;
            }
            */?>
        </td>
        <td ><?php /*echo $v['get_time']; */?></td>-->

      </tr>
      <?php } ?>
      <?php }else { ?>
      <tr class="no_data">
        <td colspan="6"><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php } ?>
    </tbody>
      <tfoot>
      <?php  if (count($output['participant_list'])>0) { ?>
          <tr>
              <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
          </tr>
      <?php } ?>
      </tfoot>
  </table>
</div>

