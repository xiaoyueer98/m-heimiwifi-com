<?php
class    ProductAction   extends  Action{
    function  add(){

        $this->display();
    }
    function  pcadd(){

        $this->display();
    }
				function pcaddSave(){
        $root = C("PCROOT");
        import("ORG.Net.UploadFile");
        $upload=new  UploadFile();
        $upload->maxSize=4000*1024; //上传文件的最大值20K
        $upload->allowExts=array("jpg","gif","png","jpeg");    //允许上传的文件扩展名
        $upload->allowTypes=array("image/jpg", "image/gif", "image/png", "image/jpeg");  //允许上传的文件类型 
        $upload->savePath=$root."/index/Upload/Product/";
        //var_dump($upload->upload());die;
        $upload->saveRule="uniqid";  //指定命名规则
        if(!$upload->upload()){  //文件上传

            $upload->getErrorMsg();    //获取错误信息
            $this->assign("waitSecond",5);
            $this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
            $this->error("图片上传失败");   
        }else{
            $info=$upload->getUploadFileInfo();  //获取保存的文件的相关信息

            //echo "<pre>";
            //var_dump($info);
            //echo "</pre>";
            //die;
            foreach($info as $k=>$v){
                $k1=$v['key']+1;

                $pathv = str_replace($root,"",$v['savepath']); 
                $arr["img".$k1]=$pathv.$v['savename']; 

            }

            //echo "<pre>";
            //var_dump($arr);
            //echo "</pre>";die;

            if(isset($arr['img1']) && !empty($arr['img1'])){
                $arr1['thumbimg']=$arr['img1'];
            }
            if(isset($arr['img2']) && !empty($arr['img2'])){
                $arr1['imgban']=$arr['img2'];
            }
            if(isset($arr['img3']) && !empty($arr['img3'])){
                $arr1['img1']=$arr['img3'];
            }
            if(isset($arr['img4']) && !empty($arr['img4'])){
                $arr1['img2']=$arr['img4'];
            }
            if(isset($arr['img5']) && !empty($arr['img5'])){
                $arr1['img3']=$arr['img5'];
            }
            if(isset($arr['img6']) && !empty($arr['img6'])){
                $arr1['img4']=$arr['img6'];
            }
            if(isset($arr['img7']) && !empty($arr['img7'])){
                $arr1['img5']=$arr['img7'];
            }
            $newArr=array_merge($_POST,$arr1);
            //var_dump($newArr);die;
            if($newArr['discount']!=0){
                $newArr['isDiscount'] = 1;
            }else{
                $newArr['isDiscount'] = 0;
            }
												$newArr['pctype'] = 2;
            //var_dump($newArr);die;
            $ob = M("product");
            $re = $ob->data($newArr)->add();
            //echo $ob -> getLastSql();die();
            if($re){
                $this->assign("waitSecond",5);
                $this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
                $this->success("添加成功");   
            }else{
                $this->assign("waitSecond",5);
                $this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
                $this->error("添加失败");   
            }
        }

    }
				function  addSave(){
        $root = C("ROOT");
        import("ORG.Net.UploadFile");
        $upload=new  UploadFile();
        $upload->maxSize=4000*1024; //上传文件的最大值20K
        $upload->allowExts=array("jpg","gif","png","jpeg");    //允许上传的文件扩展名
        $upload->allowTypes=array("image/jpg", "image/gif", "image/png", "image/jpeg");  //允许上传的文件类型 
        $upload->savePath=$root."/index/Upload/Product/";
        //var_dump($upload->upload());die;
        $upload->saveRule="uniqid";  //指定命名规则
        if(!$upload->upload()){  //文件上传

            $upload->getErrorMsg();    //获取错误信息
            $this->assign("waitSecond",5);
            $this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
            $this->error("图片上传失败");   
        }else{
            $info=$upload->getUploadFileInfo();  //获取保存的文件的相关信息

            //echo "<pre>";
            //var_dump($info);
            //echo "</pre>";
            //die;
            foreach($info as $k=>$v){
                $k1=$v['key']+1;
                $root = C('ROOT');

                $pathv = str_replace($root,"",$v['savepath']); 
                $arr["img".$k1]=$pathv.$v['savename']; 

            }

            //echo "<pre>";
            //var_dump($arr);
            //echo "</pre>";die;

            if(isset($arr['img1']) && !empty($arr['img1'])){
                $arr1['thumbimg']=$arr['img1'];
            }
            if(isset($arr['img2']) && !empty($arr['img2'])){
                $arr1['imgban']=$arr['img2'];
            }
            if(isset($arr['img3']) && !empty($arr['img3'])){
                $arr1['img1']=$arr['img3'];
            }
            if(isset($arr['img4']) && !empty($arr['img4'])){
                $arr1['img2']=$arr['img4'];
            }
            if(isset($arr['img5']) && !empty($arr['img5'])){
                $arr1['img3']=$arr['img5'];
            }
            if(isset($arr['img6']) && !empty($arr['img6'])){
                $arr1['img4']=$arr['img6'];
            }
            if(isset($arr['img7']) && !empty($arr['img7'])){
                $arr1['img5']=$arr['img7'];
            }
            $newArr=array_merge($_POST,$arr1);
            //var_dump($newArr);die;
            if($newArr['discount']!=0){
                $newArr['isDiscount'] = 1;
            }else{
                $newArr['isDiscount'] = 0;
            }
            //var_dump($newArr);die;
            $ob = M("product");
            $re = $ob->data($newArr)->add();
            //echo $ob -> getLastSql();die();
            if($re){
                $this->assign("waitSecond",5);
                $this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
                $this->success("添加成功");   
            }else{
                $this->assign("waitSecond",5);
                $this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
                $this->error("添加失败");   
            }
        }

    }
    function   getlist(){
        $page = 1;
        $size = 15;
								$pctype = 1;
								if(isset($_GET['pctype'])){
												$pctype = $_GET['pctype'];
								}
        if($_GET['page'] != ""){

            $page = $_GET['page'];
        }
        if($_GET['size'] != ""){

            $size = $_GET['size'];
        }
        import("ORG.Util.Page1");


        $proOb=new ProductModel;
        $sum=$proOb->getProductCount(" pctype={$pctype}");
        $pageOb=new Page($page,$size,$sum);
        $start=$pageOb->getStartStr();
        //$arr=$proOb->getProductList($start,10);
        //$pTypeOb=new producttypeModel;  
        //$typearr=$pTypeOb->getListType();
        //搜索相关
        $title=$_GET['title'];
        $type=$_GET['type'];
        $sontype=$_GET['sontype'];
        $price=$_GET['price'];
        $status=$_GET['status'];
        $where=" (status=1 or status=2)"." and pctype={$pctype} ";
        if(!empty($title)){
            $where.=" and title like  '%{$title}%'";
        }
        if(!empty($type)){
            $where.=" and type={$type}";
        }
        if(!empty($sontype)){
            $where.=" and sontype={$sontype}";
        }
        if(!empty($status)){
            $where.=" and status='{$status}'";
        }
        if(!empty($price)){
            switch($price){
                case 1:
                    $where.=" and price<1000";
                    break;
                case 2:
                    $where.=" and price between  1000 and 2000";
                    break;
                case 3:
                    $where.=" and price between  2000 and 3000";
                    break;
                case 4:
                    $where.=" and price between  3000 and 4000";
                    break;
                case 5:
                    $where.=" and price>4000";
                    break;
                default:
                    $where.="";	   
            }
        }
        $arr=$proOb->getProductList($start,$size,$where);
        //var_dump($arr);
        $pageStr=$pageOb->getPagestr1(3);
        $arr=array(
                'page' => $pageStr,
                'data' => $arr,        
                );//var_dump($data);
        $data=json_encode($arr);
        echo $data;

    }
    function lister(){
        $this -> display();
    }
    function pclister(){
        $this -> display();
    }
    function userlist(){
        $this -> display();
    }
    function  pcupd(){

        $id = $_GET['id'];
        $pOb=new ProductModel;
        $arr=$pOb->getProductList(0,1,"id=$id and status=1"); //echo $proOb->getLastSql();
        if(!empty($arr)){

            $this->error("该产品未下架，不能修改");

        }else{
            unset($arr);
        }
        $oOb = new OrderModel;
        $arr=$oOb->getList("pid=$id and (status=1 or status=2 or status=3)",0,1);
        if(!empty($arr))
        {
            $this->error("该产品已在用户订单中存在，不能修改"); 
        }
        else
        {
            unset($arr);
            unset($oOb);
        }

        $pOb=new ProductModel;
        $arr=$pOb->getProductList(0,1,"id=$id"); //echo $proOb->getLastSql();
        //var_dump($arr[0]);
        $this->assign("arr",$arr[0]);
        $this->display();
    }
    function  upd(){

        $id = $_GET['id'];
        $pOb=new ProductModel;
        $arr=$pOb->getProductList(0,1,"id=$id and status=1"); //echo $proOb->getLastSql();
        if(!empty($arr)){

            $this->error("该产品未下架，不能修改");

        }else{
            unset($arr);
        }
        $oOb = new OrderModel;
        $arr=$oOb->getList("pid=$id and (status=1 or status=2 or status=3)",0,1);
        if(!empty($arr))
        {
            $this->error("该产品已在用户订单中存在，不能修改"); 
        }
        else
        {
            unset($arr);
            unset($oOb);
        }

        $pOb=new ProductModel;
        $arr=$pOb->getProductList(0,1,"id=$id"); //echo $proOb->getLastSql();
        //var_dump($arr[0]);
        $this->assign("arr",$arr[0]);
        $this->display();
    }
    function  updSave(){
        $root = C("ROOT");
        import("ORG.Net.UploadFile");
        $upload=new  UploadFile();
        $upload->maxSize=4000*1024; //上传文件的最大值20K
        $upload->allowExts=array("jpg","gif","png","jpeg");    //允许上传的文件扩展名
        $upload->allowTypes=array("image/jpg", "image/gif", "image/png", "image/jpeg");  //允许上传的文件类型 
        $upload->savePath=$root."/index/Upload/Product/";
        //var_dump($upload->upload());die;
        $upload->saveRule="uniqid";  //指定命名规则
        if(!$upload->upload()){  //文件上传

            $upload->getErrorMsg();    //获取错误信息
            //$this->assign("waitSecond",5);
            //$this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
            //$this->error("图片上传失败");   
            if($_POST['discount']!=0){
                $_POST['isDiscount'] = 1;

            }                        

            $proOb=new ProductModel;
            $re=$proOb->update($_POST);
            //echo $proOb->getLastSql();die;
            if($re){
                $this->assign("jumpUrl","admin.php?m=Product&a=lister");
                $this->assign("waitSeconds","5");
                $this->success();  
            }else{
                $this->assign("jumpUrl","admin.php?m=Product&a=lister");
                $this->assign("waitSeconds","5");
                $this->error();
            }
        }else{
            $info=$upload->getUploadFileInfo();  //获取保存的文件的相关信息


            //echo "<pre>";
            //var_dump($info);
            //echo "</pre>";

            foreach($info as $k=>$v){
                $k1=$v['key']+1;
                $root = C('ROOT');

                $pathv = str_replace($root,"",$v['savepath']);; 
                $arr["img".$k1]=$pathv.$v['savename']; 

            }

            //echo "<pre>";
            //var_dump($arr);
            //echo "</pre>";die;

            if(isset($arr['img1']) && !empty($arr['img1'])){
                $arr1['thumbimg']=$arr['img1'];
            }
            if(isset($arr['img2']) && !empty($arr['img2'])){
                $arr1['imgban']=$arr['img2'];
            }
            if(isset($arr['img3']) && !empty($arr['img3'])){
                $arr1['img1']=$arr['img3'];
            }
            if(isset($arr['img4']) && !empty($arr['img4'])){
                $arr1['img2']=$arr['img4'];
            }
            if(isset($arr['img5']) && !empty($arr['img5'])){
                $arr1['img3']=$arr['img5'];
            }
            if(isset($arr['img6']) && !empty($arr['img6'])){
                $arr1['img4']=$arr['img6'];
            }
            if(isset($arr['img7']) && !empty($arr['img7'])){
                $arr1['img5']=$arr['img7'];
            }
            $newArr=array_merge($_POST,$arr1);
            if($newArr['discount']!=0){
                $newArr['isDiscount'] = 1;

            }                        
            //var_dump($newArr);
            $proOb=new ProductModel;
            $re=$proOb->update($newArr);
            //echo $proOb->getLastSql();die;
            if($re){
                $this->assign("jumpUrl","admin.php?m=Product&a=lister");
                $this->assign("waitSeconds","5");
                $this->success();  
            }else{
                $this->assign("jumpUrl","admin.php?m=Product&a=lister");
                $this->assign("waitSeconds","5");
                $this->error();
            }

        }   
    }
    function  pcupdSave(){
        $root = C("PCROOT");
        import("ORG.Net.UploadFile");
        $upload=new  UploadFile();
        $upload->maxSize=4000*1024; //上传文件的最大值20K
        $upload->allowExts=array("jpg","gif","png","jpeg");    //允许上传的文件扩展名
        $upload->allowTypes=array("image/jpg", "image/gif", "image/png", "image/jpeg");  //允许上传的文件类型 
        $upload->savePath=$root."/index/Upload/Product/";
        //var_dump($upload->upload());die;
        $upload->saveRule="uniqid";  //指定命名规则
        if(!$upload->upload()){  //文件上传

            $upload->getErrorMsg();    //获取错误信息
            //$this->assign("waitSecond",5);
            //$this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
            //$this->error("图片上传失败");   
            if($_POST['discount']!=0){
                $_POST['isDiscount'] = 1;

            }                        

            $proOb=new ProductModel;
            $re=$proOb->update($_POST);
            //echo $proOb->getLastSql();die;
            if($re){
                $this->assign("jumpUrl","admin.php?m=Product&a=pclister");
                $this->assign("waitSeconds","5");
                $this->success();  
            }else{
                $this->assign("jumpUrl","admin.php?m=Product&a=pclister");
                $this->assign("waitSeconds","5");
                $this->error();
            }
        }else{
            $info=$upload->getUploadFileInfo();  //获取保存的文件的相关信息


            //echo "<pre>";
            //var_dump($info);
            //echo "</pre>";

            foreach($info as $k=>$v){
                $k1=$v['key']+1;
                $root = C('PCROOT');

                $pathv = str_replace($root,"",$v['savepath']);; 
                $arr["img".$k1]=$pathv.$v['savename']; 

            }

            //echo "<pre>";
            //var_dump($arr);
            //echo "</pre>";die;

            if(isset($arr['img1']) && !empty($arr['img1'])){
                $arr1['thumbimg']=$arr['img1'];
            }
            if(isset($arr['img2']) && !empty($arr['img2'])){
                $arr1['imgban']=$arr['img2'];
            }
            if(isset($arr['img3']) && !empty($arr['img3'])){
                $arr1['img1']=$arr['img3'];
            }
            if(isset($arr['img4']) && !empty($arr['img4'])){
                $arr1['img2']=$arr['img4'];
            }
            if(isset($arr['img5']) && !empty($arr['img5'])){
                $arr1['img3']=$arr['img5'];
            }
            if(isset($arr['img6']) && !empty($arr['img6'])){
                $arr1['img4']=$arr['img6'];
            }
            if(isset($arr['img7']) && !empty($arr['img7'])){
                $arr1['img5']=$arr['img7'];
            }
            $newArr=array_merge($_POST,$arr1);
            if($newArr['discount']!=0){
                $newArr['isDiscount'] = 1;

            }                        
            //var_dump($newArr);
            $proOb=new ProductModel;
            $re=$proOb->update($newArr);
            //echo $proOb->getLastSql();die;
            if($re){
                $this->assign("jumpUrl","admin.php?m=Product&a=pclister");
                $this->assign("waitSeconds","5");
                $this->success();  
            }else{
                $this->assign("jumpUrl","admin.php?m=Product&a=pclister");
                $this->assign("waitSeconds","5");
                $this->error();
            }

        }   
    }
    function  del(){
        $id=$_GET['id'];
        $proOb=new ProductModel;
        $arr = array("id"=>$id,"status"=>"3");
        $re=$proOb->update($arr);
        //var_dump($re);
        if($re){
            $this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
            $this->assign("waitSeconds","2");
            $this->success();
        }else{
            $this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
            $this->assign("waitSeconds","2");
            $this->error();
        }
    }

    function  setup(){

        $id=$_GET['id']; 
        $proOb=new ProductModel;
        $re=$proOb->setStatus($id,2,1);
        if($re!==false){
            $this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
            $this->assign("waitSeconds","2");
            $this->success();
        }else{
            $this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
            $this->assign("waitSeconds","2");
            $this->error();
        }
    }
    function  setdown(){

        $id=$_GET['id']; 
        $proOb=new ProductModel;
        $re=$proOb->setStatus($id,1,2);
        if($re!==false){
            $this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
            $this->assign("waitSeconds","2");
            $this->success();
        }else{
            $this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
            $this->assign("waitSeconds","2");
            $this->error();
        }
    }


							function getUsers(){
//用户ID、手机号、设备号（未绑定缺省）、配额类型、流量有效期
//											r_memberbd,r_cardorder
								$page = 1;
								$size = 15;
								if($_GET['page'] != ""){

													$page = $_GET['page'];
//													echo $page;
													}
								if($_GET['size'] != ""){

													$size = $_GET['size'];
													}
								$startn = ($page-1)*$size;
								import("ORG.Util.Page1");
								$sum=1000;
								$pageOb=new Page($page,$size,$sum);
//						$start=$pageOb->getStartStr();
								
											$m=new Model();
											if($_GET['title'] != ''){
															$ccid = $_GET['title'];
															$sql="select mb.uid,mb.weixinid,mb.CCID,mb.tel,rs.deadTime,rs.`type`,rs.tel as tel1 from r_memberbd as mb left join r_simhard as rs on mb.CCID = rs.CCID  where mb.CCID='{$ccid}'";
											}else{
																$sql="select mb.uid,mb.weixinid,mb.CCID,mb.tel,rs.deadTime,rs.`type`,rs.tel as tel1 from r_memberbd as mb left join r_simhard as rs on mb.CCID = rs.CCID order by rs.deadTime desc limit {$startn},{$size}";
											}
											$arr=$m->query($sql);
//											var_dump($arr);
											
						$pageStr=$pageOb->getPagestr1(3);
                        $arr=array(
                            'page' => $pageStr,
                            'data' => $arr,        
                        );
//						var_dump($arr);exit;
								$data=json_encode($arr);
//						var_dump($data);exit;
								echo $data;
							}
				  

}
