<?php
class Page{
     
	 
	 public $pageSize=0;
	 public $curPage=1;
	 public $pageNum=0;
	 public $lastPage=1;
	 public $nextPage;
     function Page($length,$num){
	      $this->pageSize=$length;
		  //$this->totalPage=$num;
		  $page=$_GET['page'];
	      if(empty($page) || $page<1){
		     $page=1;
		  }
		  
		  $this->pageNum=ceil($num/($this->pageSize));
		  if($page>$this->pageNum){
		     $page=$this->pageNum;
		  }
		  $this->curPage=$page;
		   if($this->curPage==1){
		          $this->lastPage=1;
		  }else{
		          $this->lastPage=$this->curPage-1;
		  }
		  if($this->curPage==$this->pageNum){
		          $this->nextPage=$this->pageNum;
		  }else{
		          $this->nextPage=$this->curPage+1;
		  }
		 // echo $this->pageNum;
		 
	 }
	 
	 function getPageStr(){
	       //var_dump($_GET);
		  foreach($_GET as $key => $value){
		      $str1.="&".$key."=".$value;
		  }
		  $str1=substr($str,1);
		 
		  $str="<a href='?page={$this->lastPage}{$str1}'>上一页</a><a href='?page={$this->nextPage}{$str1}'>下一页</a>";
	      return $str;
	 }
	 function getStartStr(){
	       $pageStart=(($this->curPage-1)*$this->pageSize);
		   return $pageStart;
	 
	 }
	 function getPagestr1($pn){//$pn为一共分为几页
	      $str1=
		  "<style>
		  *{margin:0;padding:0;}
          .page{margin:auto;width:400px;text-align:center;}
          .page a {margin:0 5px; border:1px black solid;padding:0 5px;underline:none; text-decoration:none;}
          .page .current{background: #0099FF;}
		  </style>";
		  //var_dump( $_GET);die();
		 
		  
		  foreach($_GET as $key => $value){
		     if($key!='page'){
		         $strr.="&".$key."=".$value;
			 }
		  }
		  //$str2=substr($strr,1);
		  //echo "<br/>",$strr;
		  $strl="<a href='?page={$this->lastPage}{$strr}'>上一页</a>";
		  $strn="<a href='?page={$this->nextPage}{$strr}'>下一页</a>";
	       //echo $this->pageNum;
	       if($this->pageNum<=$pn*2+1){
		      $str0="<div class=\"page\">";
		      for($i=1;$i<=$this->pageNum;$i++){
                  if($i==$this->curPage)	{		  
			      $str.="<a href='?page=$i{$strr}' class='current'>$i</a>";
				  }else{
				  $str.="<a href='?page=$i{$strr}'>$i</a>";
				  }
			  }
			  $stre.="</div>";
			 
			  return $str1.$str0.$strl.$str.$strn.$stre;                       
		       
		   }else{ //echo $this->pageNum;
		          //echo "金";
				 //echo $this->curPage;
		           if($this->curPage<=$pn+1){
				       $str0="<div class=\"page\">";
				       for($i=1;$i<=$pn*2+1;$i++){
					       if($i==$this->curPage)	{		  
			                       $str.="<a href='?page=$i{$strr}' class='current'>$i</a>";
				           }else{
				                   $str.="<a href='?page=$i{$strr}'>$i</a>";
				           }
					   
					   }
					   $stre.="</div>";
					   return $str1.$str0.$strl.$str.$strn.$stre;
					   
				   }elseif(($this->pageNum-$this->curPage)<=$pn){
				       $str0="<div class=\"page\">";
				       for($i=$this->pageNum-$pn*2;$i<=$this->pageNum;$i++){
					       if($i==$this->curPage)	{		  
			                       $str.="<a href='?page=$i{$strr}' class='current'>$i</a>";
				           }else{
				                   $str.="<a href='?page=$i{$strr}'>$i</a>";
				           }
					   
					   }
					   $stre.="</div>";
				        return $str1.$str0.$strl.$str.$strn.$stre;
				   }else{
				       $str0="<div class=\"page\">";
				       for($i=$this->curPage-$pn;$i<=$this->curPage+$pn;$i++){
					       if($i==$this->curPage)	{		  
			                       $str.="<a href='?page=$i{$strr}' class='current'>$i</a>";
				           }else{
				                   $str.="<a href='?page=$i{$strr}'>$i</a>";
				           }
					   
					   }
					   $stre.="</div>";
				       return $str1.$str0.$strl.$str.$strn.$stre;
				   }
		   
		   }
		 
		  
	 
	 }

}
//$p=new Page(10,110);
//echo $p->getPagestr1(4);
//echo $p->getPageStr();
