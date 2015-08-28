<?php
/**
 * 默认展示页面
 *
 *
 **@copyright  Copyright (c) 2007-2013 ShopNC Inc.*/


defined('InShopNC') or exit('Access Invalid!');
class indexControl extends BaseHomeControl{
	public function indexOp(){
		Language::read('home_index_index');
		Tpl::output('index_sign','index');

		//抢购专区
		Language::read('member_groupbuy');
        $model_groupbuy = Model('groupbuy');
        $group_list = $model_groupbuy->getGroupbuyCommendedList(4);
		Tpl::output('group_list', $group_list);

		//限时折扣
        $model_xianshi_goods = Model('p_xianshi_goods');
        $xianshi_item = $model_xianshi_goods->getXianshiGoodsCommendList(4);
		Tpl::output('xianshi_item', $xianshi_item);

		//板块信息
		$model_web_config = Model('web_config');
		$web_html = $model_web_config->getWebHtml('index');
		Tpl::output('web_html',$web_html);

		Model('seo')->type('index')->show();
		Tpl::showpage('index');
	}

	//json输出商品分类
	public function josn_classOp() {
		/**
		 * 实例化商品分类模型
		 */
		$model_class		= Model('goods_class');
		$goods_class		= $model_class->getGoodsClassListByParentId(intval($_GET['gc_id']));
		$array				= array();
		if(is_array($goods_class) and count($goods_class)>0) {
			foreach ($goods_class as $val) {
				$array[$val['gc_id']] = array('gc_id'=>$val['gc_id'],'gc_name'=>htmlspecialchars($val['gc_name']),'gc_parent_id'=>$val['gc_parent_id'],'commis_rate'=>$val['commis_rate'],'gc_sort'=>$val['gc_sort']);
			}
		}
		/**
		 * 转码
		 */
		if (strtoupper(CHARSET) == 'GBK'){
			$array = Language::getUTF8(array_values($array));//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
		} else {
			$array = array_values($array);
		}
		echo $_GET['callback'].'('.json_encode($array).')';
	}

    /**
     * json输出地址数组 原data/resource/js/area_array.js
     */
    public function json_areaOp()
    {
        echo $_GET['callback'].'('.json_encode(Model('area')->getAreaArrayForJson()).')';
    }

	//判断是否登录
	public function loginOp(){
		echo ($_SESSION['is_login'] == '1')? '1':'0';
	}

	/**
	 * 头部最近浏览的商品
	 */
	public function viewed_infoOp(){
	    $info = array();
		if ($_SESSION['is_login'] == '1') {
		    $member_id = $_SESSION['member_id'];
		    $info['m_id'] = $member_id;
		    if (C('voucher_allow') == 1) {
		        $time_to = time();//当前日期
    		    $info['voucher'] = Model()->table('voucher')->where(array('voucher_owner_id'=> $member_id,'voucher_state'=> 1,
    		    'voucher_start_date'=> array('elt',$time_to),'voucher_end_date'=> array('egt',$time_to)))->count();
		    }
    		$time_to = strtotime(date('Y-m-d'));//当前日期
    		$time_from = date('Y-m-d',($time_to-60*60*24*7));//7天前
		    $info['consult'] = Model()->table('consult')->where(array('member_id'=> $member_id,
		    'consult_reply_time'=> array(array('gt',strtotime($time_from)),array('lt',$time_to+60*60*24),'and')))->count();
		}
		$goods_list = Model('goods_browse')->getViewedGoodsList($_SESSION['member_id'],5);
		if(is_array($goods_list) && !empty($goods_list)) {
		    $viewed_goods = array();
		    foreach ($goods_list as $key => $val) {
		        $goods_id = $val['goods_id'];
		        $val['url'] = urlShop('goods', 'index', array('goods_id' => $goods_id));
		        $val['goods_image'] = thumb($val, 60);
		        $viewed_goods[$goods_id] = $val;
		    }
		    $info['viewed_goods'] = $viewed_goods;
		}
		if (strtoupper(CHARSET) == 'GBK'){
			$info = Language::getUTF8($info);
		}
		echo json_encode($info);
	}
	/**
	 * 查询每月的周数组
	 */
	public function getweekofmonthOp(){
	    import('function.datehelper');
	    $year = $_GET['y'];
	    $month = $_GET['m'];
	    $week_arr = getMonthWeekArr($year, $month);
	    echo json_encode($week_arr);
	    die;
	}

    /**
     * 抽奖
     */
    public function lotteryOp(){
        $result = array();
        if (!$_SESSION['member_id']){
            $result["code"] = 1;
            echo json_encode($result);
            return;
        }
        $member_id  = $_SESSION['member_id'];
        $member_name  = $_SESSION['member_name'];
        $must_win = $_GET["must_win"];

        $member_info = $this->getMemberAndGradeInfo(true);
        $available_predeposit = $member_info['available_predeposit'];
        if($available_predeposit < 20){
            $result["code"] = 2;
            echo json_encode($result);
            return;
        }

        $model_lottery	= Model('lottery');
        $activity_id = '1';//活动id
        $awards_list = $model_lottery->getAwardsList($activity_id);

        $range=1000000;
        $end = 0;
        //计算每个奖项的取值范围（1 - 1000000）
        for($i = 0; $i < count($awards_list); $i++){
            $end = bcmul($awards_list[$i]["win_rate"],$range) + $end;
            $awards_list[$i]["end"] = $end;
        }
        $random = rand(1, $range);
        //判断是否在范围内
        for($i = 0; $i < count($awards_list); $i++){
            $end = $awards_list[$i]["end"];
            if($random <= $end ){ //中奖了
                if($awards_list[$i]["prize_amount"] > $awards_list[$i]["win_amount"]){
                    $winAwardsId = $awards_list[$i]["awards_id"];
                    $winAwardsName = $awards_list[$i]["awards_name"];
                    $winPrizeName = $awards_list[$i]["prize_name"];
                }
                break;
            }
        }

        if(!isset($winAwardsId)){ //如果未中
            if($must_win == 1){ //必中
                for($i = count($awards_list)-1; $i >= 0; $i--){
                    if($awards_list[$i]["prize_amount"] > $awards_list[$i]["win_amount"]){
                        $winAwardsId = $awards_list[$i]["awards_id"];
                        $winAwardsName = $awards_list[$i]["awards_name"];
                        $winPrizeName = $awards_list[$i]["prize_name"];
                        break;
                    }
                }
            }
        }

        if(isset($winAwardsId)){
            $params['is_win'] = 1;
            $params['awards_id'] = $winAwardsId;
            $params['awards_name'] = $winAwardsName;
            $params['prize_name'] = $winPrizeName;
        }else{
            $params['is_win'] = 0;
        }

        $params['member_id'] = $member_id;
        $params['member_name'] = $member_name;
        $params['participant_time'] = date("Y-m-d H:i:s");;
        $params['activity_id'] = 1;
        $params['is_get'] = 0;

        $participant_id = $model_lottery->insertParticipant($params);

        if(isset($winAwardsId)){ //如果中奖，则修改奖项对于的已中奖数量
            $input = array();
            $input["win_amount"] = array('sign'=>'increase', 'value'=>1);
            $model_lottery->updateAwards($input,$winAwardsId);
        }

        $model_predeposit = Model('predeposit');
        $log_array = array();
        $log_array['member_id'] = $member_id;
        $log_array['member_name'] = $member_name;
        $log_array['participant_id'] = $participant_id;
        $log_array['amount'] = 20;//抽奖扣除用户预备金金额
        $state = $model_predeposit->changePd('lottery', $log_array);//抽奖扣除用户预备金

        $points_model = Model('points');
        $points_model->savePointsLog('other',array('pl_memberid'=>$member_id,'pl_membername'=>$member_name,'pl_points'=>intval(20/10),'pl_desc'=>('抽奖送积分，抽奖号：'.$participant_id)),true);

        if(isset($winAwardsId)){ //中奖
            $result["is_win"] = 1;
            $result["awards_name"] = $winAwardsName;
            $result['prize_name'] = $winPrizeName;
        }else{
            $result["is_win"] = 0;
        }
        $result["code"] = 200;
        echo json_encode($result);
        die;
    }
}
