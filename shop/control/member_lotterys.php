<?php
/**
 * 会员中心--收藏功能
 **@copyright  Copyright (c) 2007-2013 ShopNC Inc.*/


defined('InShopNC') or exit('Access Invalid!');

class member_lotterysControl extends BaseMemberControl{
	public function __construct(){
        parent::__construct();
    }
    /**
     * 我的抽奖列表
     *
     * @param
     * @return
     */
    public function lotteryListOp(){
        $condition_arr = array();
        $condition_arr['member_id'] = $_SESSION['member_id'];
        $condition_arr['is_win'] = 1;
        $condition_arr['is_get'] = trim($_GET['is_get']);
        //分页
        $page	= new Page();
        $page->setEachNum(10);
        $page->setStyle('admin');
        //查询积分日志列表
        $lottery_model = Model('lottery');
        $win_list = $lottery_model->getParticipantList($condition_arr,$page,'*','');
        //信息输出
        self::profile_menu('member_lotterys');
        Tpl::output('show_page',$page->show());
        if($_GET['is_get'] != ""){
            Tpl::output('is_get', trim($_GET['is_get']));
        }
        Tpl::output('win_list',$win_list);
        Tpl::showpage('member_lotterys');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string	$menu_type	导航类型
     * @param string 	$menu_key	当前导航的menu_key
     * @param array 	$array		附加菜单
     * @return
     */
    private function profile_menu($menu_key='',$array=array()) {
        $menu_array = array(
            1=>array('menu_key'=>'member_lotterys',	'menu_name'=>'我的奖品',	'menu_url'=>'index.php?act=member_points')
        );
        if(!empty($array)) {
            $menu_array[] = $array;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
