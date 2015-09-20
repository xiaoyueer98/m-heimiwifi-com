(function($){
      
      getData("get","json",{'page':1,'size':15},"admin.php?m=Buyedcard&a=getlist2",1);

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
      var CCID = $('#CCID').val();
      var left = $('#left').val();
      var type = $('#type').val();
      var size = $("#size").val();
      var sta = $("#sta").val();
      var data={'start_time':start_time,'end_time':end_time,'page':page,'size':size,'CCID':CCID,'sta':sta,'left':left,'type':type}
      // 查询当前销售代表的客户列表
      getData("get", "json", data, "admin.php?m=Buyedcard&a=getlist2", page);
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
                         if(this.status == 1){
                             status = "未出库";
                         }else if(this.status == 2 || this.status == 3){
                             status = "启用";
                         }else if(this.status == 4){
                             status = "暂停";
                         }else if(this.status == 5){
                             status = "冻结";
                         }
                         if(this.type == 1){
                             type = "月卡";
                         }else if(this.type == 2){
                             type = "按月返年卡";
                         }else if(this.type == 3){
                             type = "年卡";
                         }
                        
                         tmpl=
                             '<tr>'
                             +'<td><input type="checkbox" class="checkboxes" value="'+this.id+'"</td>'                       
                             +'<td>'+this.CCID+'</td>'
                             +'<td>'+this.updated_at+'</td>'
                             +'<td>'+status+'</td>'
                             +'<!--<td>'+this.weixin+'</td>-->'
                             +'<td>'+this.left+'</td>'
                             +'<td>'+type+'</td>'
                             +'<td>'+this.deadTime+'</td>'
                             +'<td>'+this.leftOrder+'</td>'
                             +'<td><input type="button" value="编辑" ></td>'
                             +'</tr>';
                         $("#tb1").append(tmpl);
                  })
                  $("#td7").html(jsondata['page']);
                 
            }
            }
      });
}

function setchongzhi(id){

     if(id==""){
     var str="";
     $(".checkboxes").each(function(k){
            if(this.checked==true){
                  str+=","+this.value;
            }         
     })
     }else{
             str=","+id;
     }
     if(str!=""){
            str=str.substr(1);
     
            $.ajax({
            
                  url:"?m=Cardorder&a=setchongzhi",
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
