<?php
namespace Home\Controller\User;


use Think\Controller;
use Think\Model;
use Home\Model\PtUserModel;
class UserController extends Controller
{
    private $model;
    
    public function __construct(){
        parent::__construct();//继承父类
        $this->model= new Model("pt_user");//new PtUserModel();//D("PtUser");//三者皆可
    }
    
    
    public function  text(){
        echo  C("aaa");//获取配置
        echo "hello world!!!";
    }
    /**
     * 用户登录验证
     * @param unknown $userName
     * @param unknown $userPass
     */
    public function  login($userName,$userPass){
//         $name=$_POST["userName"];
//         $pass=$_POST["userPass"];
        //echo "$name----------------$pass";
        $name=$userName;
        $pass=$userPass;
        //查询数据
        $users=$this->model->where("username='%s'",$name)->select();
        //print_r($users);
        $user=$users[0];
        $host=$_SESSION["HTTP_HOST"];
        if(count($users)>0){
            if($user["pass"]==$pass){
                $_SESSION["loginUser"]=$user;
                //查询用户拥有的菜单
                $menus=$this->model->table("pt_userjob uj,pt_jobmenu jm,pt_menu m")
                ->field("m.*")
                ->where("uj.jid=jm.jid and jm.mid=m.mid and uj.uid= {$user['uid']} and m.showid=1")->select();
                $_SESSION["menus"]=$menus;
                //密码正确
                $this->assign("HOST",HOST);
                $this->display("easyuiTest");
                //header("location:http://localhost:8080/ThinkPHP/easyuiTest.php");
            }else{
                //密码错误
                $_SESSION["error"]="密码错误";
                header("location:http://localhost:8080/ThinkPHP/PTlogin.php");
            }
        }else {
            //用户不存在
            $_SESSION["error"]="用户不存在";
            header("location:http://localhost:8080/ThinkPHP/PTlogin.php");
        }
    }
    /**
     * 手机用户号验证
     */    
     public function regajax(){
        //注册的时候验证手机号ajax
        $userName = $_POST["userName"];
        $data=$this->model->where("userName = '$userName'")->select();
        
        if(sizeof($data) == 1){
            echo "该帐号已被占用";
        }else{
            echo "该账号可以注册";
        }
    }
    
    public function PT_reg(){
        /**
         * 注册功能处理程序
         */
        $userName = $_POST["userName"];
        $userPass = $_POST["userPass"];
        $trueName = $_POST["trueName"];
        $sex=$_POST["Sex"];
        $visa=$_POST["Visa"];
        
        $users=$this->model->where("userName = '$userName'")->select();
        
        if($users !=null){
            $_SESSION["warn"]="该帐号已被占用";
            header("location:http://localhost:8080/ThinkPHP/PTreg.php");
        }else{
            //上传照片
            //判断上传文件是否合法
            //print_r($_FILES["picture"]);
            if(is_uploaded_file($_FILES["picture"]["tmp_name"])){
                //截取文件后缀名
                $name=$_FILES["picture"]["name"];
                $x=substr($name,strrpos($name, ".") );
                $savename=rand(0,1000).time().$x;
                move_uploaded_file($_FILES["picture"]["tmp_name"], "Public/$savename");
            }
            $a=array(
                "username"=>$userName,
                "pass"=>$userPass,
                "truename"=>$trueName,
                "picture"=>$savename
            );
            $data["username"]=$userName;
            $data["pass"]=$userPass;
            $data["truename"]=$trueName;
            $data["picture"]=$savename;
            $bb = M("pt_user");    
            if($result =$bb->add($data)){
                header("location:http://localhost:8080/ThinkPHP/PTlogin.php");
            }else{
                $_SESSION["warn"]="注册失败，请稍候再试";
                header("location:http://localhost:8080/ThinkPHP/PTreg.php");
            }
        }
    }
    public function PT_loadtable($pageNo=1,$pageSize=10) {
        $total=$this->model->table("pt_user u,pt_card c,pt_integral i,pt_member m")
        ->field("u.*,c.card,m.name")
        ->where("u.uid = c.uid and u.uid = i.uid and m.mid = i.mid and u.isvalid = 1")
        ->getField("count(*)");
        //print_r($total);
        
        $rows=$this->model->table("pt_user u,pt_card c,pt_integral i,pt_member m")
        ->field("u.*,c.card,m.name")
        ->where("u.uid = c.uid and u.uid = i.uid and m.mid = i.mid and u.isvalid = 1")
        ->page($pageNo,$pageSize)->select();
        //print_r($rows);
        $page=array("total"=>$total,"rows"=>$rows,"pageNo"=>$pageNo,"pageSize"=>$pageSize);
        $this->ajaxReturn($page);
        
    }
    
    public function select_usertable($pageNo=1,$pageSize=10){
        $total=$this->model->table("pt_user u,pt_card c,pt_integral i")
        ->field("u.username,md5(u.pass) as pass,u.address,u.telphone,u.e_mail,u.visa,c.card,c.money,i.integral")
        ->where("u.uid = c.uid and u.uid = i.uid and u.isvalid = 1 ")
        ->getField("count(*)");
        
        $rows=$this->model->table("pt_user u,pt_card c,pt_integral i")
        ->field("u.username,md5(u.pass) as pass,u.address,u.telphone,u.e_mail,u.visa,c.card,c.money,i.integral")
        ->where("u.uid = c.uid and u.uid = i.uid and u.isvalid = 1 ")
        ->page($pageNo,$pageSize)->select();
        
        $page=array("total"=>$total,"rows"=>$rows,"pageNo"=>$pageNo,"pageSize"=>$pageSize);
       
        $this->ajaxReturn($page);
    }
    /**
     * 查询用户资料的ID查询功能
     * @param unknown $card
     * @param number $pageNo
     * @param number $pageSize
     */
    public function cardsearch($card,$pageNo=1,$pageSize=10){
        if (isset($card)&&$card!=""){
            $rows=$this->model->table("pt_user u,pt_card c,pt_integral i ")
            ->field("u.username,md5(u.pass) as pass,u.address,u.telphone,u.e_mail,u.visa,c.card,c.money,i.integral")
            ->where("u.uid = c.uid and u.uid = i.uid and c.card = $card")
            ->select();
            $data=array("total"=>1,"rows"=>$rows,"pageNo"=>$pageNo,"pageSize"=>$pageSize);
        }else {
            $data=$this->select_usertable();
        }
        $this->ajaxReturn($data);
    }
    /**
     * 查询用户资料的排序功能
     * @param unknown $sel
     * @param number $pageNo
     * @param number $pageSize
     */
    public function order($sel,$pageNo=1,$pageSize=10){
        
        if(isset($sel)&&$sel!=""){
            $total=$this->model->table("pt_user u,pt_card c,pt_integral i")
            ->field("u.username,md5(u.pass) as pass,u.address,u.telphone,u.e_mail,u.visa,c.card,c.money,i.integral")
            ->where("u.uid = c.uid and u.uid = i.uid and u.isvalid = 1 ")
            ->count();
            
            $rows=$this->model->table("pt_user u,pt_card c,pt_integral i")
            ->field("u.username,md5(u.pass) as pass,u.address,u.telphone,u.e_mail,u.visa,c.card,c.money,i.integral")
            ->where("u.uid = c.uid and u.uid = i.uid and u.isvalid = 1 ")
            ->order("$sel desc")->select();
            $data=array("total"=>$total,"rows"=>$rows,"pageNo"=>$pageNo,"pageSize"=>$pageSize);
        }else {
            $data=$this->select_usertable();
        }
        $this->ajaxReturn($data);    
    }
    /**
     * 报表管理的ID搜索功能
     * @param number $pageNo
     * @param number $pageSize
     * @param unknown $no
     */
    public function dosearch($pageNo=1,$pageSize=10,$no=null){
        //$card= array();
        $card="u.uid = c.uid and u.uid = i.uid and m.mid = i.mid and u.isvalid = 1";
        if($no!=""&&$no!=null){
            $card.=" and c.card  like '%$no%'";
        }
        $total=$this->model->table("pt_user u,pt_card c,pt_integral i,pt_member m")
        ->where($card)->getField("count(*)");
        //print_r($total);
        
        $rows=$this->model->table("pt_user u,pt_card c,pt_integral i,pt_member m")
        ->field("u.*,c.card,m.name")
        ->where($card)
        ->page($pageNo,$pageSize)->select();
        //print_r($rows);
        $page=array("total"=>$total,"rows"=>$rows,"pageNo"=>$pageNo,"pageSize"=>$pageSize);
        $this->ajaxReturn($page);
    }
    /**
     * 报表的增加和修改
     */
    public function save_or_create(){
        $data= $this->model->create();
        if($data["uid"]<0){
            $this->model->field("userName,userPass,trueName")->add();
        }else {
            $this->model->field("userName,userPass,trueName")->where("uid=%d",$data["uid"])->save();
        }
        $this->PT_loadtable();
    }
    /**
     * 用户报表组合查询
     * @param unknown $user
     * @param unknown $telphone
     * @param number $pageNo
     * @param number $pageSize
     */
    public function search($user,$telphone,$pageNo=1,$pageSize=10){
        $x="u.uid = c.uid and u.uid = i.uid and m.mid = i.mid and u.isvalid = 1";
        if($user!=""&&$user!=null){
            $x.=" and u.truename  like '%$user%'";
        }
        if($telphone!=""&&$telphone!=null){
            $x.=" and u.username  like '%$telphone%'";
        }
        $total=$this->model->table("pt_user u,pt_card c,pt_integral i,pt_member m")
        ->where($x)->getField("count(*)");
        //print_r($total);
        
        $rows=$this->model->table("pt_user u,pt_card c,pt_integral i,pt_member m")
        ->field("u.*,c.card,m.name")
        ->where($x)
        ->page($pageNo,$pageSize)->select();
        //print_r($rows);
        $page=array("total"=>$total,"rows"=>$rows,"pageNo"=>$pageNo,"pageSize"=>$pageSize);
        $this->ajaxReturn($page);
    }
    /**
     * 用户报表删除
     * @param unknown $uids
     * @param unknown $isvalid
     */
    public function deleteusers($uids,$isvalid){
        $data=$this->model->create();
        $this->model->where("uid in ($uids)")->save();
        $this->PT_loadtable();
    }
    public function testview(){
        $this->assign("aaa","创维科技");
        $this->assign("bbb",array(1,2,3));
        $this->assign("ccc",["name"=>"张三","pass"=>123456]);
        $ddd=new \stdClass();
        $ddd->name='李四';
        $ddd->pass=123;
        $this->assign("ddd",$ddd);
        $this->assign("time",time());
        
        $this->assign("e",null);
        $this->assign("empty","<span style='color:red'>你的数据没找到</span>");
        $this->display();
    }
    /**
     * 报表管理2同步请求加载数据
     * @param unknown $pageNo
     * @param unknown $pageSize
     */
    public function loadUserList($pageNo=1,$pageSize=10){
        $total=$this->model->table("pt_user ")
        ->field("")
        ->getField("count(*)");
        //print_r($total);
        
        $rows=$this->model->table("pt_user u")
        ->field("")
        ->page($pageNo,$pageSize)->select();
        //print_r($rows);
        $page=array("total"=>$total,"rows"=>$rows,"pageNo"=>$pageNo,"pageSize"=>$pageSize);
        $this->assign("HOST",HOST);
        $this->assign("page",$page);
        $this->display("loadUserList");
    }
    /**
     *报表管理2添加和编辑的功能 
     */
    public function openwindow(){
        $data=$this->model->create();
        if($data["uid"]=="-1"){
           $this->model->field("username,pass,truename,address")->add();            
        }else{
          $sql=$this->model->field("username,pass,truename,address")->where("uid=%d",$data['uid'])->save(); 
        }
        $this->loadUserList();
    }
    
    public function loadUserList2($uid){
        $data=$this->model->find($uid);
        $this->ajaxReturn($data);
    }
}

?>