
(function($){
      
      getData("get","json",{'page':1,'size':15},"admin.php?m=Yhq&a=getlist",1);

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
      var code = $('#code').val();
      var type = $('#type').val();
      var inPrice = $('#inPrice').val();//alert(price);
      var sta = $('#status').val();
      var size = $("#size").val();
      var data={'start_time':start_time,'end_time':end_time,'page':page,'size':size,'code':code,'inPrice':inPrice,'type':type,'status':sta};
      // 查询当前销售代表的客户列表
      getData("get", "json", data, "admin.php?m=Yhq&a=getlist", page);
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
                         tmpl=
                             '<tr>'
                             +'<td><input type="checkbox" class="checkboxes" value="'+this.id+'"</td>'                       
                             +'<td>'+this.id+'</td>'
                             +'<td>'+this.code+'</td>'
                             +'<td>'+this.inPrice+'元</td>'
                             +'<td>'+this.deadTime+'</td>'
                             +'<td>'+this.type+'</td>'
                             +'<td>'+this.status+'</td>'
                             +'<td>'+this.createTime+'</td>'
                             +'<td>'+this.updateTime+'</td>'   
                             +'</tr>';
                         $("#tb1").append(tmpl);
                  })
                  $("#td7").html(jsondata['page']);
                 
            }
            }
      });
}
$("#out").click(function(){
      var start_time = $('#start_time').val();
      var end_time = $('#end_time').val();
      var code = $('#code').val();
      var type = $('#type').val();
      var status = $('#status').val();
      var inPrice = $('#inPrice').val();
      
      location.href="?m=Yhq&a=export&start_time="+start_time+'&end_time='+end_time+'&code='+code+'&type='+type+'&status='+status+'&inPrice='+inPrice;
               
              

})

$("#btn").click(function(){
      var num=$("#num").val();
      var inPrice=$("#inPrice").val();
      var deadTime=$("#end_time").val();
      var type=$("#type").val();
      $.ajax({
            url:"?m=Yhq&a=createYhqAjax",
            data:"num="+num+"&inPrice="+inPrice+"&deadTime="+deadTime+"&type="+type,
            dataType:"text",
            type:"GET",
            success:function(re){
                  if($.trim(re)=="1"){
                         alert("生成优惠券成功"); 
                  }else{
                         alert("生成优惠券失败");
                    }
             }
       })
       

})
$("#yfb").click(function(){

     var str="";
     $(".checkboxes").each(function(k){
            if(this.checked==true){
                  str+=","+this.value;
            }         
     })
     
    if(str!=""){
            str=str.substr(1);
     
            $.ajax({
            
                  url:"?m=Yhq&a=zhiyifabu",
                  data:"str="+str,
                  dataType:"text",
                  type:'GET',
                  success:function(re){
                  
                        if($.trim(re)==="1"){
                              alert("设置成功");
                              //location.reload();
                        }else{
                              alert("设置失败");
                        }
                  }
     
           })
      }else{
           alert("请选中设置项");
      }
})
$("#delYhq").click(function(){

     var str="";
     $(".checkboxes").each(function(k){
            if(this.checked==true){
                  str+=","+this.value;
            }         
     })
     
    if(str!=""){
            str=str.substr(1);
     
            $.ajax({
            
                  url:"?m=Yhq&a=delYhq",
                  data:"str="+str,
                  dataType:"text",
                  type:'GET',
                  success:function(re){
                  
                        if($.trim(re)==="1"){
                              alert("删除成功");
                              //location.reload();
                        }else{
                              alert("删除失败");
                        }
                  }
     
           })
      }else{
           alert("请选中删除项");
      }
})
