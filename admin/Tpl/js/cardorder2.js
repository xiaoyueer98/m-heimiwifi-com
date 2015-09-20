
(function($){
      
      getData("get","json",{'page':1,'size':15},"admin.php?m=Cardorder&a=getlist2",1);

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
      var CCID = $('#CCID').val();
      var size = $("#size").val();
      var data={'start_time':start_time,'end_time':end_time,'page':page,'size':size,'title':title,'CCID':CCID};
      // 查询当前销售代表的客户列表
      getData("get", "json", data, "admin.php?m=Cardorder&a=getlist2", page);
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
                         if(this.type == 1){
                             var type = "月卡";
                         }else if(this.type == 2){
                            
                             var type = "按月返年卡";
                         }else if(this.type == 3){
                         
                             var type = "年卡";
                         }
                         tmpl=
                             '<tr>'
                             +'<td><input type="checkbox" class="checkboxes" value="'+this.id+'"</td>'                       
                             +'<td>'+this.id+'</td>'
                             +'<td>'+this.title+'</td>'
                             +'<td>'+this.price+'</td>'
                             +'<td>'+this.yhqCode+'</td>'
                             +'<td>'+this.beginTime+'</td>'
                             +'<td>'+this.endTime+'</td>'
                             +'<td>'+this.CCID+'</td>'
                             +'<td>'+type+'</td>'
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
    var title = $('#title').val();
    var CCID = $('#CCID').val();
    location.href="?m=Cardorder&a=export&start_time="+start_time+'&end_time='+end_time+'&title='+title+'&CCID='+CCID;
             
            

})
