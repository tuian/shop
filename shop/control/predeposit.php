<?php
/**
 * 预存款管理
 **@copyright  Copyright (c) 2007-2013 ShopNC Inc.*/


defined('InShopNC') or exit('Access Invalid!');

class predepositControl extends BaseMemberControl {
    public function __construct(){
        parent::__construct();
        Language::read('member_member_predeposit');
    }

    /**
     * 充值添加
     */
    public function recharge_addOp(){
        if (!chksubmit()){
            //信息输出
            self::profile_menu('recharge_add','recharge_add');
            Tpl::showpage('member_pd.add');
            exit();
        }
        $pdr_amount = abs(floatval($_POST['pdr_amount']));
        if ($pdr_amount <= 0) {
            showMessage(Language::get('predeposit_recharge_add_pricemin_error'),'','html','error');
        }
        $model_pdr = Model('predeposit');
        $data = array();
        $data['pdr_sn'] = $pay_sn = $model_pdr->makeSn();
        $data['pdr_member_id'] = $_SESSION['member_id'];
        $data['pdr_member_name'] = $_SESSION['member_name'];
        $data['pdr_amount'] = $pdr_amount;
        $data['pdr_add_time'] = TIMESTAMP;
        $insert = $model_pdr->addPdRecharge($data);
        if ($insert) {
            //转向到商城支付页面
            redirect('index.php?act=buy&op=pd_pay&pay_sn='.$pay_sn);
        }
    }

    /**
     * 平台充值卡
     */
    public function rechargecard_addOp()
    {
        if (!chksubmit()) {
            self::profile_menu('rechargecard_add','rechargecard_add');
            Tpl::showpage('member_rechargecard.add');
            return;
        }

        $sn = (string) $_POST['rc_sn'];
        if (!$sn || strlen($sn) > 50) {
            showMessage('平台充值卡卡号不能为空且长度不能大于50', '', 'html', 'error');
            exit;
        }

        try {
            model('predeposit')->addRechargeCard($sn, $_SESSION);
            showMessage('平台充值卡使用成功', urlShop('predeposit', 'rcb_log_list'));
        } catch (Exception $e) {
            showMessage($e->getMessage(), '', 'html', 'error');
            exit;
        }
    }

    /**
     * 积分同步
     */
    public function jftbOp(){
        if (!chksubmit()){
            //信息输出
            self::profile_menu('jftb','jftb');
            Tpl::output('member_info',$this->member_info);
            Tpl::showpage('member_jftb');
            exit();
        }
        $member_info = array();
        $member_info['member_id'] = trim($_POST['member_id']);
        $member_info['member_name'] = trim($_POST['member_name']);
        $member_info['member_truename'] = trim($_POST['user_truename']);
        $member_info['member_identity'] = trim($_POST['user_identity']);
        $member_info['jfxt_password'] = trim($_POST['jfxt_password']);
        //验证注册输入信息是否与积分系统一致
        $isPointSystemMember = $this->isPointSystemMember($member_info);
        if(!$isPointSystemMember){
            showMessage('与积分系统的会员信息不一致', '', 'html', 'error');
        }
        $model_member = Model('member');
        $update_info = array(
            'member_truename'=>  $member_info['member_truename'],
            'member_identity'=>  $member_info['member_identity'],
            'jfxt_password'=>  $member_info['jfxt_password'],
            'member_login_ip'=> getIp(),
            'member_old_login_ip'=> $member_info['member_login_ip']
        );
        $model_member->editMember(array('member_id'=>$member_info['member_id']),$update_info);
        //从积分系统获取预备金信息
        $result = $this->getPredeposit($member_info);
        if($result == -1){
            showMessage('同步充值失败', '', 'html', 'error');
        }else if($result == 0){
            showMessage("从积分系统同步充值成功，总金额为".$result."元", urlShop('predeposit', 'index'));
        }else if($result > 0){
            showMessage("从积分系统同步充值成功，总金额为".$result."元", urlShop('predeposit', 'index'));
        }
    }

    private function getPredeposit($member_info = array()){
        if (!$member_info['member_identity']) return;
        $result1 = $this->getPredepositFromPointSystem($member_info, "13800");
//        $result2 = $this->getPredepositFromPointSystem($member_info, "3800");
//        if($result1 == -1 && $result2 == -1){ //如果两个系统同步积分都失败，则失败
//            $result = -1;
//        }
//        else{
//            $result = $result1 < 0 ? 0 : $result1 + $result2 < 0 ? 0 : $result2;
//        }
//        return $result;
        return $result1;
    }

    private function getPredepositFromPointSystem($member_info = array(), $systype){
        $member_identity = $member_info['member_identity'];
        if($systype == "13800"){
            $getPointUrl = "www.tjsyds.com/lx-api/api-13800.asp?user_identity=".$member_identity;
            $synchronousSuccessUrl = "www.tjsyds.com/lx-api/Synchronous-success-13800.asp?user_identity=".$member_identity;
        }else{
            $getPointUrl = "www.tgsyds.com/lx-api/api-3800.asp?user_identity=".$member_identity;
            $synchronousSuccessUrl = "www.tjsyds.com/lx-api/Synchronous-success-3800.asp?user_identity=".$member_identity;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $getPointUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
//        echo $systype."系统积分接口返回结果：".$response;
        curl_close($ch);
        $json = json_decode($response);
        $code = $json -> code;
        $model_pdr = Model('predeposit');
        $model_member	= Model('member');
        $amount = 0;
        if($code == "200"){
            $pointArr = $json -> detail;
            for($i=0;$i<count($pointArr);$i++){
                $point = $pointArr[$i];
                $data = array();
                $data['pdr_sn'] = $point -> id;
                $data['pdr_member_id'] = $member_info['member_id'];
                $data['pdr_member_name'] = $member_info['member_name'];
                $data['pdr_amount'] =  $point -> jsje;
                $amount += $point -> jsje;
                $data['pdr_add_time'] = TIMESTAMP;
                $data['pdr_payment_state'] = '1';
                $insert = $model_pdr->addPdRecharge($data);
                $data_pd['available_predeposit'] = array('exp','available_predeposit+'.$data['pdr_amount']);
                $update = $model_member->editMember(array('member_id'=>$data['pdr_member_id']),$data_pd);
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $synchronousSuccessUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
//            echo $systype."系统同步成功接口返回结果：".$response;
            curl_close($ch);
        }else if($code == "0"){
            $amount = 0;
        }else {
            $amount = -1;
        }
        return $amount;
    }

    /**
     * 验证注册输入信息是否与积分系统一致
     *
     */
    private function isPointSystemMember($member_info) {
//        $user_no = $member_info['user_no'];
//        $user_type = $member_info['user_type'];
        $user_truename = urlencode($member_info['member_truename']);
        $user_identity = $member_info['member_identity'];
        $jfxt_password = $member_info['jfxt_password'];
        $url = "www.tjsyds.com/lx-api/checkRegistUser-13800.asp?"
            ."user_truename=$user_truename&user_identity=$user_identity&user_password=$jfxt_password";
//        echo $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
//        echo "13800返回结果：".$response;
        curl_close($ch);
        $json = json_decode($response);
        $code = $json -> code;
        if($code == "200"){
            return true;
        }

        $url = "www.tgsyds.com/lx-api/checkRegistUser-3800.asp?"
            ."user_truename=$user_truename&user_identity=$user_identity&user_password=$jfxt_password";
        echo $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
//        echo "3800返回结果：".$response;
        curl_close($ch);
        $json = json_decode($response);
        $code = $json -> code;
        if($code == "200"){
            return true;
        }
        return false;
    }

    /**
     * 充值列表
     */
    public function indexOp(){
        $condition = array();
        $condition['pdr_member_id']	= $_SESSION['member_id'];
        if (!empty($_GET['pdr_sn'])) {
            $condition['pdr_sn'] = $_GET['pdr_sn'];
        }

        $model_pd = Model('predeposit');
        $list = $model_pd->getPdRechargeList($condition,20,'*','pdr_id desc');

        self::profile_menu('log','recharge_list');
        Tpl::output('list',$list);
        Tpl::output('show_page',$model_pd->showpage());

        Tpl::showpage('member_pd.list');
    }

    /**
     * 查看充值详细
     *
     */
    public function recharge_showOp(){
        $pdr_id = intval($_GET["id"]);
        if ($pdr_id <= 0){
            showDialog(Language::get('predeposit_parameter_error'),'','error');
        }

        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdr_member_id'] = $_SESSION['member_id'];
        $condition['pdr_id'] = $pdr_id;
        $condition['pdr_payment_state'] = 1;
        $info = $model_pd->getPdRechargeInfo($condition);
        if (!$info){
            showDialog(Language::get('predeposit_record_error'),'','error');
        }
        Tpl::output('info',$info);
        self::profile_menu('rechargeinfo','rechargeinfo');
        Tpl::showpage('member_pd.info');
    }

    /**
     * 删除充值记录
     *
     */
    public function recharge_delOp(){
        $pdr_id = intval($_GET["id"]);
        if ($pdr_id <= 0){
            showDialog(Language::get('predeposit_parameter_error'),'','error');
        }

        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdr_member_id'] = $_SESSION['member_id'];
        $condition['pdr_id'] = $pdr_id;
        $condition['pdr_payment_state'] = 0;
        $result = $model_pd->delPdRecharge($condition);
        if ($result){
            showDialog(Language::get('nc_common_del_succ'),'reload','succ','CUR_DIALOG.close()');
        }else {
            showDialog(Language::get('nc_common_del_fail'),'','error');
        }
    }

    /**
     * 预存款变更日志
     */
    public function pd_log_listOp(){
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['lg_member_id'] = $_SESSION['member_id'];
        $list = $model_pd->getPdLogList($condition,20,'*','lg_id desc');

        //信息输出
        self::profile_menu('log','loglist');
        Tpl::output('show_page',$model_pd->showpage());
        Tpl::output('list',$list);
        Tpl::showpage('member_pd_log.list');
    }

    /**
     * 充值卡余额变更日志
     */
    public function rcb_log_listOp()
    {
        $model = Model();
        $list = $model->table('rcb_log')->where(array(
            'member_id' => $_SESSION['member_id'],
        ))->page(20)->order('id desc')->select();

        //信息输出
        self::profile_menu('log', 'rcb_log_list');
        Tpl::output('show_page', $model->showpage());
        Tpl::output('list', $list);
        Tpl::showpage('member_rcb_log.list');
    }

    /**
     * 申请提现
     */
    public function pd_cash_addOp(){
        if (chksubmit()){
            $obj_validate = new Validate();
            $pdc_amount = abs(floatval($_POST['pdc_amount']));
            $validate_arr[] = array("input"=>$pdc_amount, "require"=>"true",'validator'=>'Compare','operator'=>'>=',"to"=>'0.01',"message"=>Language::get('predeposit_cash_add_pricemin_error'));
            $validate_arr[] = array("input"=>$_POST["pdc_bank_name"], "require"=>"true","message"=>Language::get('predeposit_cash_add_shoukuanbanknull_error'));
            $validate_arr[] = array("input"=>$_POST["pdc_bank_no"], "require"=>"true","message"=>Language::get('predeposit_cash_add_shoukuanaccountnull_error'));
            $validate_arr[] = array("input"=>$_POST["pdc_bank_user"], "require"=>"true","message"=>Language::get('predeposit_cash_add_shoukuannamenull_error'));
            $validate_arr[] = array("input"=>$_POST["password"], "require"=>"true","message"=>'请输入支付密码');
            $obj_validate -> validateparam = $validate_arr;
            $error = $obj_validate->validate();
            if ($error != ''){
                showDialog($error,'','error');
            }

            $model_pd = Model('predeposit');
            $model_member = Model('member');
            $member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
            //验证支付密码
            if (md5($_POST['password']) != $member_info['member_paypwd']) {
                showDialog('支付密码错误','','error');
            }
            //验证金额是否足够
            if (floatval($member_info['available_predeposit']) < $pdc_amount){
                showDialog(Language::get('predeposit_cash_shortprice_error'),'index.php?act=predeposit&op=pd_cash_list','error');
            }
            try {
                $model_pd->beginTransaction();
                $pdc_sn = $model_pd->makeSn();
                $data = array();
                $data['pdc_sn'] = $pdc_sn;
                $data['pdc_member_id'] = $_SESSION['member_id'];
                $data['pdc_member_name'] = $_SESSION['member_name'];
                $data['pdc_amount'] = $pdc_amount;
                $data['pdc_bank_name'] = $_POST['pdc_bank_name'];
                $data['pdc_bank_no'] = $_POST['pdc_bank_no'];
                $data['pdc_bank_user'] = $_POST['pdc_bank_user'];
                $data['pdc_add_time'] = TIMESTAMP;
                $data['pdc_payment_state'] = 0;
                $insert = $model_pd->addPdCash($data);
                if (!$insert) {
                    throw new Exception(Language::get('predeposit_cash_add_fail'));
                }
                //冻结可用预存款
                $data = array();
                $data['member_id'] = $member_info['member_id'];
                $data['member_name'] = $member_info['member_name'];
                $data['amount'] = $pdc_amount;
                $data['order_sn'] = $pdc_sn;
                $model_pd->changePd('cash_apply',$data);
                $model_pd->commit();
                showDialog(Language::get('predeposit_cash_add_success'),'index.php?act=predeposit&op=pd_cash_list','succ','CUR_DIALOG.close()');
            } catch (Exception $e) {
                $model_pd->rollback();
                showDialog($e->getMessage(),'index.php?act=predeposit&op=pd_cash_list','error');
            }
        }
    }

    /**
     * 提现列表
     */
    public function pd_cash_listOp(){
        $condition = array();
        $condition['pdc_member_id'] =  $_SESSION['member_id'];
        if (!empty($_GET['sn_search'])) {
            $condition['pdc_sn'] = $_GET['sn_search'];
        }
        if (isset($_GET['paystate_search'])){
            $condition['pdc_payment_state'] = intval($_GET['paystate_search']);
        }
        $model_pd = Model('predeposit');
        $cash_list = $model_pd->getPdCashList($condition,30,'*','pdc_id desc');

        self::profile_menu('log','cashlist');
        Tpl::output('list',$cash_list);
        Tpl::output('show_page',$model_pd->showpage());
        Tpl::showpage('member_pd_cash.list');
    }

    /**
     * 提现记录详细
     */
    public function pd_cash_infoOp(){
        $pdc_id = intval($_GET["id"]);
        if ($pdc_id <= 0){
            showMessage(Language::get('predeposit_parameter_error'),'index.php?act=predeposit&op=pd_cash_list','html','error');
        }
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdc_member_id'] = $_SESSION['member_id'];
        $condition['pdc_id'] = $pdc_id;
        $info = $model_pd->getPdCashInfo($condition);
        if (empty($info)){
            showMessage(Language::get('predeposit_record_error'),'index.php?act=predeposit&op=pd_cash_list','html','error');
        }

        self::profile_menu('cashinfo','cashinfo');
        Tpl::output('info',$info);
        Tpl::showpage('member_pd_cash.info');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string	$menu_type	导航类型
     * @param string 	$menu_key	当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key=''){
        $menu_array	= array(
            array('menu_key'=>'loglist','menu_name'=>Language::get('nc_member_path_predeposit_loglist'),	'menu_url'=>'index.php?act=predeposit&op=pd_log_list'),
            array('menu_key'=>'recharge_list','menu_name'=>Language::get('nc_member_path_predeposit_rechargelist'),	'menu_url'=>'index.php?act=predeposit&op=index'),
//			array('menu_key'=>'cashlist','menu_name'=>Language::get('nc_member_path_predeposit_cashlist'),	'menu_url'=>'index.php?act=predeposit&op=pd_cash_list'),
            array(
                'menu_key' => 'rcb_log_list',
                'menu_name' => '充值卡余额',
                'menu_url' => 'index.php?act=predeposit&op=rcb_log_list',
            ),
        );
        switch ($menu_type) {
            case 'rechargeinfo':
                $menu_array[] = array('menu_key'=>'rechargeinfo','menu_name'=>Language::get('nc_member_path_predeposit_rechargeinfo'),	'menu_url'=>'');
                break;
            case 'recharge_add':
                $menu_array[] = array('menu_key'=>'recharge_add','menu_name'=>'在线充值',	'menu_url'=>'');
                break;
            case 'rechargecard_add':
                $menu_array[] = array('menu_key'=>'rechargecard_add','menu_name'=>'充值卡充值','menu_url'=>'javascript:;');
                break;
            case 'log':
                break;
            case 'cashadd':
                $menu_array[] = array('menu_key'=>'cashadd','menu_name'=>Language::get('nc_member_path_predeposit_cashadd'),	'menu_url'=>'index.php?act=predeposit&op=pd_cash_add');
                break;
            case 'cashinfo':
                $menu_array[] = array('menu_key'=>'cashinfo','menu_name'=>Language::get('nc_member_path_predeposit_cashinfo'),	'menu_url'=>'');
                break;
            case 'jftb':
                $menu_array[] = array('menu_key'=>'jftb','menu_name'=>'同步充值',	'menu_url'=>'');
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
