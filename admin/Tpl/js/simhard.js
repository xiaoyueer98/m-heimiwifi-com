(function($){
      
      getData("get","json",{'page':1,'size':15},"admin.php?m=Simhard&a=getlist",1);

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
      //alert(parseInt(nowPage));
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
      var tel = $('#tel').val();
      var CCID = $('#CCID').val();
      var size = $("#size").val();
      var data={'start_time':start_time,'end_time':end_time,'page':page,'size':size,'tel':tel,'CCID':CCID};
      // 查询当前销售代表的客户列表
      getData("get", "json", data, "admin.php?m=Simhard&a=getlist", page);
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
                         if(this.status==1){
                             sta='未使用';
                         }else if(this.status==2){
                             sta='已发出';  
                         }else if(this.status==3){
                        	    sta='已送出';
                         }else{
                             sta='异常';  
                            }
                         if(this.type==1){
                             type='月卡';
                         }else if(this.type==2){
                             type='按月返年卡';  
                         }else if(this.type==3){
                        	    type='年卡';
                         }else{
                             type='无配额';  
                            }
                         tmpl=
                             '<tr>'
                             +'<td><input type="checkbox" class="checkboxes" value="'+this.id+'"</td>'                       
                             +'<td>'+this.sid+'</td>'
                             +'<td>'+this.CCID+'</td>'
                             +'<td>'+this.IP+'</td>'
                             +'<td>'+this.simBatch+'</td>'
                             +'<td>'+this.createTime+'</td>'
                             +'<td>'+this.ssid+'</td>'
                             +'<td>'+this.password+'</td>'
                             +'<td>'+sta+'</td>'
                             +'<td>'+this.deadTime+'</td>'
                             +'<td>'+type+'</td>'
                             +'<td>'+this.other+'</td>'
                             +'<!--<td>'+sta+'</td>-->'
                             +'</tr>';
                         $("#tb1").append(tmpl);
                  })
                  $("#td7").html(jsondata['page']);
                 
            }
            }
      });
}
//导入
$("#in").click(function(){
           
      $("#zzc").css('display','block'); 
      $("#filek").css('display','block'); 
})
//文件选择的取消按钮
$("#cancel").click(function(){
       
        $("#zzc").css('display','none'); 
        $("#filek").css('display','none'); 
})
//文件选择的导入按钮
$("#start").click(function(){
       
        filepath=$("#file").val();
        if(filepath!=""){
               $("#upload-form").submit();
         }
})


//导入更改设备

//导入
$("#in1").click(function(){
           
      $("#zzc1").css('display','block'); 
      $("#filek1").css('display','block'); 
})
//文件选择的取消按钮
$("#cancel1").click(function(){
       
        $("#zzc1").css('display','none'); 
        $("#filek1").css('display','none'); 
})
//文件选择的导入按钮
$("#start1").click(function(){
       
        filepath=$("#file1").val();
        if(filepath!=""){
               $("#upload-form1").submit();
         }
})
