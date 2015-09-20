<?php
class Page{
     
	 
	  public $pageSize = 0;
	  public $curPage = 1;
	  public $pageNum = 0;
	  public $lastPage = 1;
	  public $nextPage;
      public $num;
      function  Page($page=1,$length,$num){
            $this -> num = $num;
	        $this -> pageSize = $length;
            $this -> pageNum = ceil($num / ($this -> pageSize));
		    if($page > $this -> pageNum){
		          
                  $page = $this -> pageNum;
		    }
            if($page >= 1){
		    
                  $this -> curPage = $page;
            }
		    if($this -> curPage == 1){
		         
                  $this -> lastPage = 1;
		    
            }else{
		          
                  $this -> lastPage = $this -> curPage-1;
		    }
		    if($this -> curPage == $this -> pageNum){
		          
                  $this -> nextPage = $this -> pageNum;
		    
            }else{
		          
                  $this->nextPage=$this->curPage+1;
		    }
		 
		 
	 }
	 
	 
	 function  getStartStr(){
	       
           $pageStart = (($this -> curPage - 1) * $this -> pageSize);
		   return $pageStart;
	 
	 }
	 function  getPageStr1($pn){//$pn为一共分为几页
          $count = "共&nbsp;<span class='count'>".$this->num."</span>&nbsp;条";
	      $str1=
		  "<style>
		  *{margin:0;padding:0;}
          .count{border:width:100px;}
          .page{margin:auto;width:400px;text-align:center;}
          .page input {margin:0 5px;padding:0 5px;underline:none; text-decoration:none;}
          .page .current{background: #999999;}
		  </style>";
		  $strl="<input type='button' id='first' value='首页' onclick='goFirst();'><input type='button' id='prev' value='上一页' onclick='goPrev();'>";
		  $strn="<input type='button' id='next' value='下一页' onclick='goNext();'><input type='button' id='end' value='末页' onclick='goEnd();'>";
	       
	      if($this -> pageNum <= $pn * 2 + 1){
		        
                $str0 = "<div class=\"page\">";
		        for($i = 1;$i <= $this -> pageNum;$i++){
                      if($i == $this -> curPage)	{		  
                            $str .= "<input type='button'  class='current' value='$i' onclick='goCurrent(this.value);'>";
				      }else{
				            $str .= "<input type='button' value='$i' onclick='goCurrent(this.value);'>";
				      }
			    }
			    $stre .= "</div>";
			 
			    return $str1.$str0.$count.$strl.$str.$strn.$stre;                       
		       
		   }else{
		        if($this -> curPage <= $pn + 1){
				      $str0 = "<div class=\"page\">";
				      for($i = 1;$i <= $pn * 2 + 1;$i++){
					        if($i == $this -> curPage)	{		  
			                      $str .= "<input type='button' class='current' value='$i' onclick='goCurrent(this.value);'>";
				            }else{
				                  $str .= "<input type='button' value='$i' onclick='goCurrent(this.value);'>";
				            }                                  
					   
					  }
					  $stre .= "</div>";
					  return $str1.$str0.$count.$strl.$str.$strn.$stre;
					   
				}elseif(($this -> pageNum - $this -> curPage) <= $pn){
				       $str0 = "<div class=\"page\">";
				       for($i = $this -> pageNum - $pn * 2;$i <= $this -> pageNum;$i++){
					       if($i == $this -> curPage)	{		  
			                       $str .= "<input type='button' class='current' value='$i' onclick='goCurrent(this.value);'>";
				           }else{
				                   $str .= "<input type='button' value='$i' onclick='goCurrent(this.value);'>";
				           }
					   
					   }
					   $stre .= "</div>";
				       return $str1.$str0.$count.$strl.$str.$strn.$stre;
				}else{
				       $str0 = "<div class=\"page\">";
				       for($i = $this -> curPage - $pn;$i <= $this -> curPage + $pn;$i++){
					        if($i == $this -> curPage)	{		  
			                       $str .= "<input type='button' class='current' value='$i' onclick='goCurrent(this.value);'>";
				            }else{
				                   $str .= "<input type='button' value='$i' onclick='goCurrent(this.value);'>";
				            }
					   
			 	       }
			           $stre .= "</div>";
				       return $str1.$str0.$count.$strl.$str.$strn.$stre;
		        }    
	      }     
	 }
}
