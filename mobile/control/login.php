<?php
/**
 * 前台登录 退出操作
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.shopnc.net)
 * @license    http://www.shopnc.net
 * @link       http://www.shopnc.net
 * @since      File available since Release v1.1
 */

use Shopnc\Tpl;

defined('InShopNC') or exit('Access Invalid!');

class loginControl extends mobileHomeControl {

	public function __construct(){
		parent::__construct();
	}

	/**
	 * 登录
	 */
	public function indexOp(){
        if(empty($_POST['username']) || empty($_POST['password']) || !in_array($_POST['client'], $this->client_type_array)) {
            output_error('登录失败');
        }

		$model_member = Model('member');

        $array = array();
        $array['member_name']	= $_POST['username'];
        $array['member_passwd']	= md5($_POST['password']);
        $member_info = $model_member->getMemberInfo($array);

        if(!empty($member_info)) {			if($member_info['member_identity'] != null && $member_info['member_verifycode'] != null){                //从积分系统获取预备金信息                $this->getPredeposit($member_info);            }
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'key' => $token));
            } else {
                output_error('登录失败');
            }
        } else {
            output_error('用户名密码错误');
        }
    }		private function getPredeposit($member_info = array()){        if (!$member_info['member_identity']) return;        $this->getPredepositFromPointSystem($member_info, "13800");//        $this->getPredepositFromPointSystem($member_info, "3800");    }    private function getPredepositFromPointSystem($member_info = array(), $systype){        $member_identity = $member_info['member_identity'];        if($systype == "13800"){            $getPointUrl = "www.tjsyds.com/lx-api/api-13800.asp?user_identity=".$member_identity;            $synchronousSuccessUrl = "www.tjsyds.com/lx-api/Synchronous-success-13800.asp?user_identity=".$member_identity;        }else{            $getPointUrl = "www.tgsyds.com/lx-api/api-3800.asp?user_identity=".$member_identity;            $synchronousSuccessUrl = "www.tjsyds.com/lx-api/Synchronous-success-3800.asp?user_identity=".$member_identity;        }        $ch = curl_init();        curl_setopt($ch, CURLOPT_URL, $getPointUrl);        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);        curl_setopt($ch, CURLOPT_HEADER, false);        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        $response = curl_exec($ch);//        echo $systype."系统积分接口返回结果：".$response;        curl_close($ch);        $json = json_decode($response);        $code = $json -> code;        $model_pdr = Model('predeposit');        $model_member	= Model('member');        if($code == "200"){            $pointArr = $json -> detail;            for($i=0;$i<count($pointArr);$i++){                $point = $pointArr[$i];                $data = array();                $data['pdr_sn'] = $point -> id;                $data['pdr_member_id'] = $member_info['member_id'];                $data['pdr_member_name'] = $member_info['member_name'];                $data['pdr_amount'] =  $point -> jsje;                $data['pdr_add_time'] = TIMESTAMP;                $data['pdr_payment_state'] = '1';                $insert = $model_pdr->addPdRecharge($data);                $data_pd['available_predeposit'] = array('exp','available_predeposit+'.$data['pdr_amount']);                $update = $model_member->editMember(array('member_id'=>$data['pdr_member_id']),$data_pd);            }            $ch = curl_init();            curl_setopt($ch, CURLOPT_URL, $synchronousSuccessUrl);            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);            curl_setopt($ch, CURLOPT_HEADER, false);            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);            $response = curl_exec($ch);//            echo $systype."系统同步成功接口返回结果：".$response;            curl_close($ch);        }    }

    /**
     * 登录生成token
     */
    private function _get_token($member_id, $member_name, $client) {
        $model_mb_user_token = Model('mb_user_token');

        //重新登录后以前的令牌失效
        //暂时停用
        //$condition = array();
        //$condition['member_id'] = $member_id;
        //$condition['client_type'] = $_POST['client'];
        //$model_mb_user_token->delMbUserToken($condition);

        //生成新的token
        $mb_user_token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
        $mb_user_token_info['member_id'] = $member_id;
        $mb_user_token_info['member_name'] = $member_name;
        $mb_user_token_info['token'] = $token;
        $mb_user_token_info['login_time'] = TIMESTAMP;
        $mb_user_token_info['client_type'] = $_POST['client'];

        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);

        if($result) {
            return $token;
        } else {
            return null;
        }

    }

	/**
	 * 注册
	 */
	public function registerOp(){
		$model_member	= Model('member');

        $register_info = array();
        $register_info['username'] = $_POST['username'];
        $register_info['password'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['email'] = $_POST['email'];				$register_info['user_identity'] = $_POST['user_identity'];        $register_info['user_verifycode'] = $_POST['user_verifycode'];
        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'key' => $token));
            } else {
                output_error('注册失败');
            }
        } else {
			output_error($member_info['error']);
        }

    }
}
