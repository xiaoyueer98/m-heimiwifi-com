(function($){
      
      getData("get","json",{'page':1,'size':15,'pctype':1},"admin.php?m=Product&a=getlist",1);

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
  
      var title = $('#title').val();
      var sta = $("#status").val();
      var price = $("#price").val();
 
      var size = $("#size").val();
      var data={'page':page,'size':size,'sta':sta,'title':title,'pctype':1};
      // 查询当前销售代表的客户列表
      getData("get", "json", data, "admin.php?m=Product&a=getlist", page);
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
                             +'<td>'+this.title+'</td>'
                             +'<td>'+this.price+'</td>'
                             +'<td><a href="admin.php?m=Product&a=upd&id='+this.id+'">修改</a></td>'
                             +'<td><a href="admin.php?m=Product&a=del&id='+this.id+'">删除</a></td>'
                             //+'<td><a href="admin.php?m=Product&a=del&id='+this.id+'">删除</a></td>'
                             if (this.status == 2)
                             tmpl += '<td id="up"'+key+'><a href="admin.php?m=Product&a=setup&id='+this.id+'">置发布</a></td>'
                             else
                             tmpl += '<td id="down"'+key+'><a href="admin.php?m=Product&a=setdown&id='+this.id+'">置下架</a></td>'
                             
                            
                         $("#tb1").append(tmpl);
                  })
                  $("#td7").html(jsondata['page']);
                 
            }
            }
      });
}
