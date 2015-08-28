<?php
/**
 * 抽奖
 *
 * 
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc.
 */
defined('InShopNC') or exit('Access Invalid!');
class lotteryModel{
	
	/**
	 * 奖项列表
	 *
	 */
	public function getAwardsList($activity_id){
		$param	= array();
		$param['table']	= 'lottery_awards';
		$param['where']	= " and activity_id=$activity_id ";
		$param['order']	= 'awards_id';
		return Db::select($param);
	}
	
	/**
	 * 查看参与人数
	 *
	 */
	public function getParticipantAmount($activity_id){
		$param	= array();
		$param['activity_id'] = $activity_id;
		return Db::getCount('lottery_participant', $param);
	}
	
	/**
	 * 查看参与人数
	 *
	 */
	public function getWinAmount($activity_id){
		$param	= array();
		$param['activity_id'] = $activity_id;
		$param['is_win'] = 1;
		return Db::getCount('lottery_participant', $param);
	}
	
	/**
	 * 获取奖项信息
	 *
	 */
	public function getAwardsInfo($awards_id){
		$param	= array();
		$param['table']	= 'lottery_awards';
		$param['where']	= " and awards_id=$awards_id ";
		return Db::select($param);
	}
	
	/**
	 * 更新奖项
	 *
	 * @param array $input
	 * @param int $id
	 * @return bool
	 */
	public function updateAwards($input,$id){
		return Db::update('lottery_awards',$input," awards_id='$id' ");
	}

    /**
     * 更新奖项
     *
     * @param array $input
     * @param int $id
     * @return bool
     */
    public function getPrize($input,$id){
        return Db::update('lottery_participant',$input," id='$id' ");
    }
	
	
	/**
	 * 添加抽奖记录
	 *
	 * @param array $input
	 * @return bool
	 */
	public function insertParticipant($input){
		return Db::insert('lottery_participant',$input);
	}

    /**
     * 查询抽奖参与记录
     *
     * @param array $condition 条件数组
     * @param array $page   分页
     * @param array $field   查询字段
     * @param array $page   分页
     */
    public function getParticipantList($condition,$page='',$field='*'){
        $condition_str	= $this->getParticipantCondition($condition);
        $param	= array();
        $param['table']	= 'lottery_participant';
        $param['where']	= $condition_str;
        $param['field'] = $field;
        $param['order'] = $condition['order'] ? $condition['order'] : 'lottery_participant.id desc';
        return Db::select($param,$page);
    }

	/**
	 * 构造查询条件
	 *
	 * @param array $condition 条件数组
	 * @return string
	 */
	private function getCondition($condition){
		$conditionStr	= '';
		if($condition['activity_id'] != ''){
			$conditionStr	.= " and activity_id='{$condition['activity_id']}' ";
		}
		if($condition['activity_type'] != ''){
			$conditionStr	.= " and awards_id='{$condition['awards_id']}' ";
		}
		
		return $conditionStr;
	}

    /**
     * 构造参与记录查询条件
     *
     * @param array $condition 条件数组
     * @return string
     */
    private function getParticipantCondition($condition){
        $conditionStr	= '';
        if($condition['member_id'] != ''){
            $conditionStr	.= " and member_id='{$condition['member_id']}' ";
        }
        if($condition['is_win'] != ''){
            $conditionStr	.= " and is_win='{$condition['is_win']}' ";
        }
        if($condition['is_get'] != ''){
            $conditionStr	.= " and is_get='{$condition['is_get']}' ";
        }

        return $conditionStr;
    }
}