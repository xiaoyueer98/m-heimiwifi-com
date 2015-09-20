<?php
class    SiteAction   extends  Action{

    function  upd(){

        $sOb = M("site");
        $arr = $sOb ->where("`type`=1")-> order("id desc") -> limit(1) ->select();
        if(isset($arr) && !empty($arr)){
            $this -> assign("arr",$arr[0]);
            $this ->display();
        }

    } 
    function  pcupd(){

        $root = C("WURL");
        $sOb = M("site");
        $arr = $sOb ->where("`type`=2")-> order("id desc") -> limit(1) ->select();
								$this->assign('root',	$root);
        if(isset($arr) && !empty($arr)){
            $this -> assign("arr",$arr[0]);
        }else{
												$arr = $sOb ->query("select * from __TABLE__ order by id desc");
												$arr[0]['id'] = $arr[0]['id'] + 1;
												$arr[0]['new'] = 1;
            $this -> assign("arr",$arr[0]);
								}
        $this ->display();

    } 

    function  updSave(){
        
        $id = $_GET['id'];
        $root = C("ROOT");
        import("ORG.Net.UploadFile");
        $upload=new  UploadFile();
        $upload->maxSize=4000*1024; //上传文件的最大值20K
        $upload->allowExts=array("jpg","gif","png","jpeg");    //允许上传的文件扩展名
        $upload->allowTypes=array("image/jpg", "image/gif", "image/png", "image/jpeg");  //允许上传的文件类型 
        $upload->savePath=$root."/index/Upload/Site/";
//        var_dump($upload->upload());die;
        $upload->saveRule="uniqid";  //指定命名规则
        if(!$upload->upload()){  //文件上传

            $upload->getErrorMsg();    //获取错误信息
            $this->assign("waitSecond",5);
            $this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
            $this->error("图片上传失败");   

        }else{
            $info = $upload->getUploadFileInfo();  //获取保存的文件的相关信息
            $pathv = str_replace($root,"",$info[0]['savepath']); 
            $updArr[$info[0]['key']] = $pathv.$info[0]['savename'];
            $updArr['id'] = $id;
            $sOb = M("site");
            $re = $sOb->data($updArr)->save();
            //echo $proOb->getLastSql();die;
            if($re){
                $this->assign("jumpUrl","admin.php?m=Site&a=upd");
                $this->assign("waitSeconds","5");
                $this->success();  
            }else{
                $this->assign("jumpUrl","admin.php?m=Site&a=upd");
                $this->assign("waitSeconds","5");
                $this->error();
            }

        }   
    }
    function  pcupdSave(){
        
        $id = $_GET['id'];
        $new = $_GET['new'];
        $root = C("PCROOT");
        import("ORG.Net.UploadFile");
        $upload=new  UploadFile();
        $upload->maxSize=4000*1024; //上传文件的最大值20K
        $upload->allowExts=array("jpg","gif","png","jpeg");    //允许上传的文件扩展名
        $upload->allowTypes=array("image/jpg", "image/gif", "image/png", "image/jpeg");  //允许上传的文件类型 
        $upload->savePath=$root."/index/Upload/Site/";
//								echo $upload->savePath;
//        var_dump($upload->upload());die;
        $upload->saveRule="uniqid";  //指定命名规则
        if(!$upload->upload()){  //文件上传

            $upload->getErrorMsg();    //获取错误信息
            $this->assign("waitSecond",5);
            $this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
            $this->error("图片上传失败");   

        }else{
            $info = $upload->getUploadFileInfo();  //获取保存的文件的相关信息
            $pathv = str_replace($root,"",$info[0]['savepath']); 
            $updArr[$info[0]['key']] = $pathv.$info[0]['savename'];
												$updArr['id'] = $id;
            $updArr['type'] = 2;
            $sOb = M("site");
												if($new == 1){
																$re = $sOb->data($updArr)->add();
												}else{
																$re = $sOb->data($updArr)->save();
												}
//												var_dump($updArr);
//            echo $sOb->getLastSql();die;
            if($re){
                $this->assign("jumpUrl","admin.php?m=Site&a=pcupd");
                $this->assign("waitSeconds","5");
                $this->success();  
            }else{
                $this->assign("jumpUrl","admin.php?m=Site&a=pcupd");
                $this->assign("waitSeconds","5");
                $this->error();
            }

        }   
    }




}
