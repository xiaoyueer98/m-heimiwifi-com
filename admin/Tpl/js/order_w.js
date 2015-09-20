(function($){
      
      getData("get","json",{'page':1,'size':15},"admin.php?m=Order&a=getlist3",1);

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
      var orderId = $("#orderId").val();
      var tel = $("#tel").val();
      var name = $("#name").val();
      var size = $("#size").val();
      var data={'start_time':start_time,'end_time':end_time,'page':page,'size':size,'sta':sta,'title':title,'name':name,'orderId':orderId,'tel':tel};
      // 查询当前销售代表的客户列表
      getData("get", "json", data, "admin.php?m=Order&a=getlist3", page);
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
                         tmpl=
                             '<tr>'
                             +'<td><input type="checkbox" class="checkboxes" value="'+this.id+'"</td>'                       
                             +'<td>'+this.id+'</td>'
                             +'<td>'+this.orderId+'</td>'
                             +'<td>'+this.title+'</td>'
                             +'<td>'+this.price+'</td>'
                             +'<td>'+this.yhq+'</td>'
                             +'<td>'+this.name+'</td>'
                             +'<td>'+this.tel+'</td>'
                             +'<td>'+this.time+'</td>'
                             +'<td>'+this.other+'</td>'
                            
                         $("#tb1").append(tmpl);
                  })
                  $("#td7").html(jsondata['page']);
                 
            }
            }
      });
}

function setTimeout(){

     var str="";
     $(".checkboxes").each(function(k){
            if(this.checked==true){
                  str+=","+this.value;
            }         
     })
    if(str!=""){
            str=str.substr(1);
     
            $.ajax({
            
                  url:"?m=Order&a=setTimeout",
                  data:"str="+str,
                  dataType:"text",
                  type:'GET',
                  success:function(re){
                  //alert(re);
                        if($.trim(re)==="1"){
                              alert("设置成功");
                              location.reload();
                        }else{
                              alert("设置失败");
                        }
                  }
     
           })
      }else{
           alert("请选中设置项");
      }
}
