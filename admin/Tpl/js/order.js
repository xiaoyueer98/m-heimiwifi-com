(function($){
      
      getData("get","json",{'page':1,'size':15},"admin.php?m=Order&a=getlist",1);

})(jQuery);

$("#sou").click(function(){

      getCondition(1);
})
// 选择每页显示行数
function getSize(){
      
      getCondition(1);
}
// 向上翻页
function goPrev(){      
 
      nowPage = $('.page').find('.current').val();
      getCondition(parseInt(nowPage) - 1);
}
// 向下翻页
function  goNext(){
      
      nowPage = $('.page').find('.current').val();
      // alert(parseInt(nowPage)+1);
      getCondition(parseInt(nowPage) + 1);
}

// 点击页码
function  goCurrent(val){
      
      nowPage = val;
      getCondition(nowPage);
}
//点击首页
function  goFirst(){
    
      getCondition(1);
} 
//点击末页
function  goEnd(){
      getCondition(999999999);
}
//点击全选
$(".checkboxall").change(function(){
      
      $(".checkboxes").each(function(){
            this.checked=$(".checkboxall")[0].checked;
      })
                              
})
// 根据条件显示页面数据
function getCondition(page) {
      var start_time = $('#start_time').val();
      var end_time = $('#end_time').val();
      var title = $('#title').val();
      var sta = $("#status").val();
      var name = $("#name").val();
      var orderId = $("#orderId").val();
      var tel = $("#tel").val();
      var yhq = $("#yhq").val();
      var size = $("#size").val();
      var data={'start_time':start_time,'end_time':end_time,'page':page,'size':size,'sta':sta,'title':title,'name':name,'tel':tel,'orderId':orderId,'yhq':yhq};
      // 查询当前销售代表的客户列表
      getData("get", "json", data, "admin.php?m=Order&a=getlist", page);
}
function  getData(type, dataType, data, url, nowPage){
      jQuery.ajax({
			   	  type : type,
				    dataType : dataType,
				    data : data,
				    url : url,
				    success : function(jsondata) {
					  var len = 0
					  try {
						      // 如果没有数据可能会抛出异常
						      len = jsondata['data'].length;
					  } catch (e) {
						      // 直接返回0
						      len = 0;
					  }
				  	if (len == 0) {
						
					       	$("#tb1").html("");
                  $("#td7").html("<center><div>共&nbsp;0&nbsp;页<input type='button' value='上一页'><input type='button' value='下一页'></div></center>");
						
					  } else { 
                  $("#tb1").empty(); 
                  $(jsondata['data']).each(function(key){
                             if(this.payType==1){
                                   var payType="支付宝支付";
                             }  
                             if(this.status==1){
                                   var status="未付款";
                             }else if(this.status==2){
                                   status="已付款";
                             }else if(this.status==3){
                                   status="确认订单"; 
                             }else if(this.status==4){
                                   status="已发获";
                             }else if(this.status==5){
                                   status="交易完成";
                             }else if(this.status==6){
                                   status="取消订单";
                             }else{ 
                                   status="订单异常";
                             }
                             jQuery.ajax({

                                   url:"?m=Order&a=selAddress",
                                   data:"addrId="+this.address_id,
                                   type:"GET",
                                   dataType:"text",
                                   success:function(re){
                                              
                                          $("#addr"+key).html(re);
                                   }
                             })   
                         if(this.transfer!=null){
                               transfer=this.transfer
                         }else{
                               transfer="";
                         }
                         if(this.transferPay!=null){
                               transferPay=this.transferPay
                         }else{
                               transferPay="";
                         }
                         if(this.cancelCause!=null){
                               cancelCause=this.cancelCause;
                         }else{
                               cancelCause="";
                         }
                         var id = this.id;
                         var other = this.other;
                         tmpl=
                             '<tr>'
                             +'<td><input type="checkbox" class="checkboxes" value="'+this.id+'"</td>'                       
                             +'<td>'+this.id+'</td>'
                             +'<td>'+this.orderId+'</td>'
                             +'<td>'+this.title+'</td>'
                             +'<td>'+this.price+'</td>'
                             +'<td>'+this.yhq+'</td>'
                             +'<td id=\'addr'+key+'\'></td>'
                             +'<td><input type="button" value="编辑"  onclick="otherSee('+this.id+',1);"><br/><span id="name'+this.id+'">'+this.name+'</span></td>'
                             +'<td><input type="button" value="编辑"  onclick="otherSee('+this.id+',2);"><br/><span id="tel'+this.id+'">'+this.tel+'</span></td>'
                             +'<td>'+this.time+'</td>'
                             +'<td><input type="button" value="编辑"  onclick="otherSee('+this.id+',3);"><br/><span id="other'+this.id+'" style="color:red;">'+this.other+'</span></td>'
                             +'<td><input type="button" value="确认发货" onclick="orderSure('+this.id+','+this.uid+');"></td>'
                             +'</tr>';
                         $("#tb1").append(tmpl);
                  })
                  $("#td7").html(jsondata['page']);
                 
            }
            }
      });
}

//确认订单
function  orderSure(id){
   
         $(".zzc").css("display","block"); 
         $(".orInfo").css("display","block");
         $.ajax({
             
               url:"?m=Order&a=getSinggleOrder",
               data:"id="+id,
               dataType:"json",
               type:"GET",
               success:function(re){
                     $("#id1").empty();
                     $("#title1").empty(); 
                     $("#id1").append(re[0].id);
                     $("#title1").append(re[0].title);
               }
         })
}
var reHard=false;
$("#CCID1").blur(function(){
    
         CCID=$("#CCID1").val();
         $.ajax({
           
               url:"?m=Order&a=isHard",
               data:"CCID="+CCID,
               dataType:"text",
               type:"GET",
               success:function(re){
                      
                      if($.trim(re)==="1"){
                            reHard=true;             
                      }else if($.trim(re)==="0"){
                            reHard=false;
                            alert("设备号不存在或已使用");
                      }
               }
         })
})
var retranCode=false;
$("#transferCode1").blur(function(){
        
         transferCode=$("#transferCode1").val();
         var reg =/^\d{10}$/;
         var reg1 =/^\d{12}$/;
         if(transferCode==""){
               retranCode=false;
               alert("快递单号不能为空");
         }else if(!reg.test($.trim(transferCode)) && !reg1.test($.trim(transferCode))){
               retranCode=false;
               alert("快递单号不符合规则");
         }else{
         /*	 
         $.ajax({
           
               url:"?m=Order&a=issetTransferCode",
               data:"transferCode="+transferCode,
               dataType:"text",
               type:"GET",
               success:function(re){
                      
                      if($.trim(re)==="0"){
                            retranCode = false; 
                            alert("该快递单号已经填写过");
                      }else{
                            retranCode = true;
                      }
               }
         })
         */
              retranCode = true;
         }

})
//确定
$("#ok").click(function(){
         
        if(reHard===false){
            return false;
        }else{
            CCID=$("#CCID1").val();
        }
        if(retranCode===false){
            return false;
        }else{
            transferCode = $("#transferCode1").val();
        }    
        transfer=$("#transfer1").val(); 
        id=$("#id1").html();
        //alert(transfer);alert(transferCode);alert(bd);alert(CCID);
        if(transfer!="" && transferCode!="" && CCID!="" ){
              $.ajax({
                
                    url:"?m=Order&a=okSave",
                    data:"id="+id+"&transfer="+transfer+"&transferCode="+transferCode+"&CCID="+CCID,
                    dataType:"text",
                    type:"GET",
                    success:function(re){//alert(re);
                           if($.trim(re)==="1"){
                                 alert("确认成功且短信发送成功");
                                 $(".orInfo").css("display","none");
                                 $(".zzc").css("display","none");
                                 location.reload();
                           }else if($.trim(re)==="2"){
                        	         alert("确认成功但短信发送失败，请手动重新发送短信");
                                  $(".orInfo").css("display","none");
                                  $(".zzc").css("display","none");
                                  location.reload();
                           }else{
                                 alert("确认失败");
                           }
                    }
              })
        }
    
})

$("#cancel").click(function(){
        
        $("#transfer1").val("");  
        $("#transferCode1").val("");
        $("#CCID1").val("");  
        $(".zzc").css("display","none"); 
        $(".orInfo").css("display","none");
})
//绑定操作
function  funbd(id,uid){
      $.ajax({
            
            url:"?m=Order&a=bdshebei",
            data:"id="+id+"&uid="+uid,
            dataType:"text",
            type:'GET',
            success:function(re){
                  //alert(re);
                  if($.trim(re)==="1"){
                        alert("绑定成功");
                  }else{
                        alert("绑定失败");
                  }
                  //location.href="?m=Member&a=payMethod";
            }
      })                 
}
function setyifahuo(){
     var str="";
     $(".checkboxes").each(function(k){
            if(this.checked==true){
                  str+=","+this.value;
            }         
      })
     if(str!=""){
            str=str.substr(1);
     
            $.ajax({
            
                  url:"?m=Order&a=setyifahuo",
                  data:"str="+str,
                  dataType:"text",
                  type:'GET',
                  success:function(re){
                  //alert(re);
                        if($.trim(re)==="1"){
                              alert("设置成功");
                        }else{
                              alert("设置失败");
                        }
                  }
     
           })
      }else{
           alert("请选中设置项");
      }                 
}

function  transferSave(id){
       var val=$("#transfer"+id).val();
       
            $.ajax({
            
                  url:"?m=Order&a=transferSave",
                  data:"id="+id+"&val="+val,
                  dataType:"text",
                  type:'GET',
                  success:function(re){
                  //alert(re);
                        if($.trim(re)==="1"){
                              alert("设置成功");
                        }else{
                              alert("设置失败");
                        }
                  }
     
           })
}
function  transferPaySave(id){
       var val=$("#transferPay"+id).val();
       
            $.ajax({
            
                  url:"?m=Order&a=transferPaySave",
                  data:"id="+id+"&val="+val,
                  dataType:"text",
                  type:'GET',
                  success:function(re){
                  //alert(re);
                        if($.trim(re)==="1"){
                              alert("设置成功");
                        }else{
                              alert("设置失败");
                        }
                  }
     
           })
}
function  cancelCauseSave(id){
       var val=$("#cancelCause"+id).val();
       
            $.ajax({
            
                  url:"?m=Order&a=cancelCauseSave",
                  data:"id="+id+"&val="+val,
                  dataType:"text",
                  type:'GET',
                  success:function(re){
                  //alert(re);
                        if($.trim(re)==="1"){
                              alert("设置成功");
                        }else{
                              alert("设置失败");
                        }
                  }
     
           })
}
function  otherSee(id,type){
      $(".zzc").css("display","block");    
      $(".order_upd").css("display","block");   
      if(type==1){
          
          var name = $("#name"+id).text();
          $("#updIn").val(name);
          $("#type").val(1);
      }else if(type==2){
          var tel = $("#tel"+id).text();
          $("#updIn").val(tel);
          $("#type").val(2);
      }else if(type==3){
          var other = $("#other"+id).text();
          $("#updIn").val(other);
          $("#type").val(3);
      }
      $("#orderId").val(id);
}
$(document).ready(function(){

$("#cancel").click(function(){
      $("#updIn").val("");
      $("#orderId").val("");
      $(".zzc").css("display","none"); 
      $(".order_upd").css("display","none");
})
$("#cancel_x").click(function(){
      $("#updIn").val("");
      $("#orderId").val("");
      $(".zzc").css("display","none"); 
      $(".order_upd").css("display","none");
})

$("#bdupd").click(function(){
          
            other=$("#updIn").val();
            id=$("#orderId").val();
            type=$("#type").val();
            /*if(other ==""){
                  alert("不能为空");
                  return;
            }*/
            $.ajax({
                   
                   url:"?m=Order&a=otherSave",
                   data:"other="+other+"&id="+id+"&type="+type,
                   dataType:"text",
                   type:"GET",
                   success:function(re){
                         if($.trim(re)==="1"){
                              
                               alert("编辑成功");
                               location.reload();
                         }else{
                               alert("编辑失败");
                               location.reload();
                         }


                   }

           })
            
})
})

