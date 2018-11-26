
<link href="{<$smarty.const.PUBLIC_ROOT>}alert/webapp/delete_confirm/css/css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
		/*
			一、带确认取消的弹窗用法：
				参数说明：
					1.提示标题
					2.提示内容
					3.确认回调函数，执行自己的代码
					4.取消回调函数，可以为不填。
				my_confirm(
					提示标题,
					提示内空, 
					function(){
						确认后的处理代码
					},
					function(){
						取消后的处理代码
					}
				);
			二、带确认的弹窗用法：
				参数说明：
					1.提示标题
					2.提示内容
					3.确认回调函数，执行自己的代码
				my_alert(
					提示标题,
					提示内空, 
					function(){
						确认后的处理代码
					}
				);
		*/

		// 带确认取消按钮的弹窗
		function my_confirm(myContent, callback_confirm, callback_cancel){

			//$('#my-confirm-title').html(myTitle);
			//$('#kwindow_c').html(myContent);

             
			$('.kwindow').show();
			$('.kwindow_bod').show();
		    
			$("#my-confirm-button").click(function(){
				$('.kwindow').hide();
				$('.kwindow_bod').hide();
				
				 if(callback_confirm && typeof callback_confirm == "function"){
					callback_confirm();
				 }
			});

			$("#my-cancel-button").click(function(){
				$('.kwindow').hide();
				$('.kwindow_bod').hide();

				if(callback_cancel && typeof callback_cancel == "function"){
					callback_cancel();
				 }
			});
		}
	

</script>


<!--弹窗start-->
<div class="kwindow"></div>
<div class="kwindow_bod">
	<div class="kwindow_cont">
		<span class="kwindow_c">确定要删除吗？</span>
        <div class="kwindow_b">
        	<span class="kwindow_l" id="my-cancel-button">取消</span>
            <span id="my-confirm-button">确认</span>
            <div class="clear_both"></div>
        </div>
	</div>
</div>
<!--弹窗end-->

