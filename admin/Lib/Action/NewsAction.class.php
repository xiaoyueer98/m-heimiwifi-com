<?php
class NewsAction extends  Action{

function   add(){
				          
      $newsTypeOb=new NewstypeModel;
	  $arr=$newsTypeOb->getNewsTypeList("fid!=0");
	  $this->assign("arr",$arr);
	  $this->display();
}
function  addAjax(){
      $newsOb=new NewsModel;
	  $title=$_GET['title'];
	  $num=$newsOb->getNewsCount("title={$title}");
	  if($num>0){
	        echo "1";
	  }else{
			echo "0";
	  }
}
function  addSave(){
	  $newsOb=new NewsModel;
	  $_POST['addtime']=date("Y-m-d h:i:s");
	  $re=$newsOb->addd($_POST);
	  if($re!==false){
	       
            $this->assign("jumpUrl","__URL__/lister");
			$this->assign("waitSeconds","3");
			$this->success();
	  }else{
			$this->assign("jumpUrl","__URL__/lister");
			$this->assign("waitSeconds","3");
			//$this->error();
	  }
					 
}
				
function   lister(){
				
	  $newsOb=new NewsModel;
	  $newsTypeOb=new NewstypeModel;
	  $arr=$newsTypeOb->getNewsTypeList();
	  //var_dump($arr);
	  $keyword=$_GET['keyword'];
	  $type=$_GET['type'];
	  $where="";
	  if($keyword!=''){
	        $where.=" title like '%$keyword%'";
	  }
	  if($type!=''){
			if($keyword!=''){
			      $where.="and type='$type'";
			}else{
				  $where=" type='$type'";
			}
							
	  }
	  //echo $where;
	  import("ORG.Util.Page");
	  $pageOb=new Page(10,$newsOb->getNewsCount($where));
	  $start=$pageOb->getStartStr();
	  $nArr=$newsOb->getNewsList($start,10,$where);
	  $pageStr=$pageOb->getPageStr1(1);
	  //var_dump($arr);
	  $this->assign("arr",$arr);
	  $this->assign("nArr",$nArr);
	  $this->assign("pageStr",$pageStr);
							
	  $this->display();
}

function   update(){
	  $id=$_GET['id'];
	  $newsTypeOb=new NewstypeModel;
	  $arr=$newsTypeOb->getNewsTypeList("fid!=0");
	  $newsOb=new NewsModel;
	  $nArr=$newsOb->getNewsList($start=0,$len=1,$where="id={$id}");
	  $this->assign("arr",$arr);
	  $this->assign("nArr",$nArr[0]);
	  $this->display();
}

function   updateSave(){
      
      $newsOb=new NewsModel;
	  $_POST['addtime']=date("Y-m-d h:i:s");
	  $re=$newsOb->upd($_POST);
	  if($re){
	        $this->assign("jumpUrl","__URL__/lister");
			$this->assign("waitSeconds","3");
			$this->success();
	  }else{
		    $this->assign("jumpUrl","__URL__/lister");
			$this->assign("waitSeconds","3");
			$this->error();
	  }
}

function   del(){
	
      $id=$_GET['id'];
	  $arr=explode(",",$id);
	  $newsOb=new NewsModel;
	  foreach($arr as $vid){
	        
            $re=$newsOb->del($vid);
	  }
							
	  if($re){
			
            $this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
			$this->assign("waitSeconds","3");
		    $this->success();
	  }else{
			$this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
			$this->assign("waitSeconds","3");
			$this->error();
	  
	  }



}
}
