<?php
header("content-type:text/html;charset=utf-8");
class  ProductAction extends  Action{

    function  test(){

        $this->display();
    }
    function  detail_buy(){

        //$id=$_GET['id'];
        $pOb = M("product");
        $pArr = $pOb -> where("status=1 and pctype=1") ->order("id desc") ->limit(1) ->select();//发布状态下的最后一个产品
        $id=$pArr[0]['id'];
        if(!empty($id)){

            $this->assign("pArr",$pArr);

            $this->display();
        }else{

            header("location:?m=Index&a=index");
        }

    }
    function  productSetcookie(){

        setcookie("pArr",serialize($_GET),time()+24*3600,"/");
        echo ($_GET['pid']);
    }
    
    function  wifi_list(){

        $pOb=M("product");
        $arr=$pOb->where("status=1 and pctype=1")->select();
        if(isset($arr) && !empty($arr)){

            $this->assign("product",$arr);
            $this->display();
        }
    }
    
    function  card_detail(){
        $id=$_GET['id'];
        if($id!=""){

            $cOb=M("card");
            $arr=$cOb->where("id=$id and status=1 and pctype=1")->select();
        
            $this->assign("card",$arr[0]);
            $monthBegin=date("n");
            if($monthBegin==12){
                $nextMonth=1;
            }else{
                $nextMonth=$monthBegin+1;
            }
            $monthEnd=$monthBegin-1;
            $arrMonth=array("1"=>"31","2"=>"28","3"=>"31","4"=>"30","5"=>"31","6"=>"30","7"=>"31","8"=>"31","9"=>"30","10"=>"31","11"=>"30","12"=>"31");
            $beginTimeArr = explode(" ",$arr[0]['beginTime']);
            $endTimeArr = explode(" ",$arr[0]['endTime']);
            $beginTime = $beginTimeArr[0];
            $endTime = $endTimeArr[0];
            $this->assign("beginTime",$beginTime);
            $this->assign("endTime",$endTime);
            $this->assign("monthBegin",$monthBegin);
            $this->assign("yearMonthBegin",$monthBegin-1);
            $this->assign("nextMonth",$nextMonth);
            $this->assign("monthEnd",$monthEnd);
            $this->assign("arrMonth",$arrMonth);

            $this->display();

        }

    }
    function  card_detailwx(){
        $id=$_GET['id'];
        $wxid=$_GET['wxid'];
        if($id!=""){

            $cOb=M("card");
            $arr=$cOb->where("id=$id and status=1 and pctype=1")->select();
        
            $this->assign("card",$arr[0]);
            $monthBegin=date("n");
            if($monthBegin==12){
                $nextMonth=1;
            }else{
                $nextMonth=$monthBegin+1;
            }
            $monthEnd=$monthBegin-1;
            $arrMonth=array("1"=>"31","2"=>"28","3"=>"31","4"=>"30","5"=>"31","6"=>"30","7"=>"31","8"=>"31","9"=>"30","10"=>"31","11"=>"30","12"=>"31");
            $beginTimeArr = explode(" ",$arr[0]['beginTime']);
            $endTimeArr = explode(" ",$arr[0]['endTime']);
            $beginTime = $beginTimeArr[0];
            $endTime = $endTimeArr[0];
            $this->assign("beginTime",$beginTime);
            $this->assign("endTime",$endTime);
            $this->assign("monthBegin",$monthBegin);
            $this->assign("yearMonthBegin",$monthBegin-1);
            $this->assign("nextMonth",$nextMonth);
            $this->assign("monthEnd",$monthEnd);
            $this->assign("arrMonth",$arrMonth);
            $this->assign("wxid",$wxid);

            $this->display();

        }

    }

    function  card_list(){
        //echo "充值服务暂停";die;
        $sOb = M("site");
        $sArr = $sOb ->where("`type`=1")-> order("id desc ")->limit(1) ->select();
        if(isset($sArr) && !empty($sArr)){
            $this->assign("sArr",$sArr[0]);
            //$this->display(); 
        }
        $cOb=M("card");
        $arr=$cOb->where("status=1 and pctype=1 order by price asc")->select();

        if(isset($arr) && !empty($arr)){

            $this->assign("card",$arr);
            $this->display();
        }
    }
    function  card_listwx(){
        $wxid = $_GET['wxid'];

        $cOb=M("card");
        $arr=$cOb->where("status=1 and pctype=1 order by price asc")->select();
        if(isset($arr) && !empty($arr)){

            $this->assign("wxid",$wxid);
            $this->assign("card",$arr);
            $this->display();
        }
    }
    function  verify(){

        import("ORG.Util.ImageZ");
    }




}
