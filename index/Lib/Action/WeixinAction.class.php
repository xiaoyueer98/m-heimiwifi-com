<?php
//ob_end_clean();
//header("content-type:text/html;charset=utf-8");
class  WeixinAction extends  Action{
    /*判断微信帐号是否已存在；
     * $wxid:微信帐号
     */
    private function judgewx($wxid){
        $mm=M('memberbd');
        $wxid = mysql_escape_string($wxid);
//        echo $wxid;
//        $wxid=-1;
        $sql='select weixinid,wxstatus from __TABLE__ where weixinid="'.$wxid.'"';
        $r1=$mm->query($sql);
        $r=$r1[0];
//        echo $mm->getLastSql();
//        var_dump($r);
        if(!empty($r['weixinid'])){
            $wxstatus=$r['wxstatus'];
            if(!empty($r['weixinid']) && $wxstatus==0){
//                有这个用户，但解绑了
                return 2;
            }else{
//                有这个用户，未解绑
                return 1;
            }
        }else{
//            没这个用户；
            return 0;
        }
    }
    private function getcoo(){
        
//        $ch = curl_init();
//        // 设置URL和相应的选项
//        curl_setopt($ch, CURLOPT_URL, "http://weixin.heimiwifi.com/coo.php");
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
//        // 抓取URL并把它传递给浏览器
//        $s=curl_exec($ch);
//        // 关闭cURL资源，并且释放系统资源
//        curl_close($ch);
//        var_dump($s);
//        return $s;
    }
//    判断是不是已经来过了
    public function getED(){
        $n=0;
        if(isset($_GET)&&isset($_GET['wxid'])){
            $wxid = $_GET['wxid'];
            $wxid = mysql_escape_string($wxid);
												$wxewm = $_GET['wxewm'];
            $mm=M('memberbd');
            $sql='select id from __TABLE__ where weixinid="'.$wxid.'"';
            $r1=$mm->query($sql);
            if(isset($r1[0])){
                $r=$r1[0];
                $n=$r['id'];
            }
												if($wxewm != -1){
																if($n > 1){
																				$sql = "update __TABLE__ set wxerweima='{$wxewm}' where weixinid='{$wxid}'";
																}else{ 
																				$time = date('Y-m-d H:i:s');
																				$sql = "insert into __TABLE__ set wxerweima='{$wxewm}',weixinid='{$wxid}',uid='{$wxid}',ctime='{$time}'";
																}
																$mm->execute($sql);
												}
        }
        echo $n;
    }
    public function getWX(){
//        var_dump($_GET);
        $n=0;
//        var_dump($_GET);exit;
        if(isset($_GET)&&isset($_GET['wxid'])&&isset($_GET['wxtoken'])){
            $wxid=$_GET['wxid'];
            $wxtoken=$_GET['wxtoken'];
            if($wxtoken=='wxid13579'){
//            echo $wxtoken;
                $n=$this->judgewx($wxid);
//            echo '>>>'.$n.'===='.$wxtoken;
                if($n==1){
                    $wxid = mysql_escape_string($wxid);
                    $mm=M('memberbd');
//                    $r=$mm->where('weixinid='.$wxid)->field('CCID')->find();
                    $sql='select CCID from __TABLE__ where weixinid="'.$wxid.'"';
                    $r1=$mm->query($sql);
                    $r=$r1[0];
                    $n=$r['CCID'];
                }
            }
        }
        echo $n;
        return $n;
    }
    /*绑定微信帐号接口；
     * $wxid:微信帐号
     * $wxnn:微信nickname
     * $tel:用户手机号
     * $ccid:heimi盒子号
     */
    public function wxbind(){
//								echo date('Y-m-d H:i:s');
//        $wxid=-1;
        if(isset($_GET['wxid'])){
            $wxid=$_GET['wxid'];
            $wxnn=  urldecode($_GET['wxnn']);
            $this->assign('r',array('wxid'=>$wxid,'wxnn'=>$wxnn));
        }else{
            if(!empty($_POST)&&isset($_POST['html'])&&isset($_POST['wxid'])&&isset($_POST['wxnn'])&&isset($_POST['tel'])&&isset($_POST['ccid'])){
                $wxid= mysql_escape_string(urldecode($_POST['wxid']));
            }
        }
//        echo $wxid;
        $wx=$this->judgewx($wxid);
        $wxtime= date('Y-m-d H:i:s');
								
        if(!empty($_POST)&&isset($_POST['html'])&&isset($_POST['wxid'])&&isset($_POST['wxnn'])&&isset($_POST['tel'])&&isset($_POST['ccid'])){
            $html= mysql_escape_string(urldecode($_POST['html']));
            $wxid= mysql_escape_string(urldecode($_POST['wxid']));
            $wxnn= mysql_escape_string(urldecode($_POST['wxnn']));
            $tel= mysql_escape_string(urldecode($_POST['tel']));
            $ccid= mysql_escape_string(urldecode($_POST['ccid']));
            $mm = M('memberbd');
            if($html==1){
                if($wx==2){
//                有这个用户，但解绑了
                    $sql="update __TABLE__ set wxname='{$wxnn}',tel='{$tel}',ctime='{$wxtime}',wxstatus=1,CCID='{$ccid}' where weixinid='{$wxid}'";
                }else if($wx===0){
                    $sql="insert into __TABLE__ set weixinid='{$wxid}',wxname='{$wxnn}',tel='{$tel}',ctime='{$wxtime}',wxstatus=1,CCID='{$ccid}'";
                }else{
                    $sql = -1;
                }
                if($sql != -1){
                    $d9 = file_get_contents("http://weixin.heimiwifi.com/wxusers.php?wxid={$wxid}&wxst=1&tk=wxtktk&ccid={$ccid}");
                    $n=$mm->execute($sql);
                    echo $n;
                    exit;
                }else{
                    echo '非法操作';
                    exit;
                }
                $this->redirect('wxbind2', array('wxid'=>$wxid,'wxnn'=>$wxnn,'ccid'=>$ccid,'rf'=>'wxbind11'),0);
            }else if($wx==1){
                $this->redirect('wxunbind', array('wxid'=>$wxid,'wxnn'=>$wxnn,'ccid'=>$ccid,'rf'=>'wxbind11'),0);
            }
            header('Location: http://m.heimiwifi.com/index.php?m=Weixin&a=wxbind&wxid='.$wxid.'&wxnn='.urlencode($wxnn));
                exit;
        }else{
            if($wx==1){
                
                header('Location: http://m.heimiwifi.com/index.php?m=Weixin&a=wxbind&wxid='.$wxid.'&wxnn='.urlencode($wxnn));
                $this->redirect('wxunbind', array('wxid'=>$wxid,'wxnn'=>$wxnn,'ccid'=>$ccid,'rf'=>'wxbind11'),0);
            }
            $this->display();
                exit;
        }
    }
    public function wxbind2($wx,$tel,$ccid){
        //var_dump($l);
        $this->display();
    }
    /*
     * 微信解绑
     * $wxid:要解绑的微信号
     */
    public function wxunbind(){
//        $wxid=-11;
        $mm = M('memberbd');
        if(isset($_POST)&&isset($_POST['html'])){
//            微信传来的
//            $wxid = mysql_escape_string($wxid);
//            $r=$mm->where('weixinid='.$wxid)->field('weixinid,wxstatus,CCID,wxname')->find();
            $html=  mysql_escape_string($_POST['html']);
            if($html==1){
                $wxidym=  mysql_escape_string(urldecode($_POST['wxid']));
                $ccidym=  mysql_escape_string(urldecode($_POST['ccid']));
                $r2=  $this->judgewx($wxidym);
                if($r2==1){
																				$wxtime= date('Y-m-d H:i:s');
                    $sql ="update __TABLE__ set wxstatus=0,ctime='{$wxtime}' where weixinid='{$wxidym}' and CCID='{$ccidym}'";
                    $mm->execute($sql);
                    $d9 = file_get_contents("http://weixin.heimiwifi.com/wxusers.php?wxid={$wxidym}&wxst=2&tk=wxtktk&ccid={$ccid}");
                    echo 1;
                    exit;
                }else if($r2==2){
                    echo 1;
                    exit;
                }else{
                    echo '非法操作';
                    exit;
                }
            }else{
                $this->assign('r', $r);
                $this->display();
                exit;
            }
        }else if(!empty ($_GET)&&isset ($_GET['rf'])){
            $rf=$_GET['rf'];
            if($rf=='wxbind11'){
//                说明是由wxbind跳转来的。已有帐号
                $wxid = mysql_escape_string($_GET['wxid']);
//                $r=$mm->where('weixinid='.$wxid)->field('weixinid,wxstatus,CCID,wxname')->find();
                $sql='select weixinid,wxstatus,CCID,wxname from __TABLE__ where weixinid="'.$wxid.'"';
                $r1=$mm->query($sql);
                $r=$r1[0];
                $this->assign('r', $r);
                $this->display();
                exit;
            }
            
        }
        $this->assign('r', $r);
        $this->display();
    }
    /*
     * 微信解绑
     * $wx:要解绑的微信号
     */
    public function wxunbind2($wx){
        $this->display();
    }
}

