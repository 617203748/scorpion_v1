
	<link href="{<$smarty.const.PUBLIC_ROOT>}alert/pc/orange_light/style/confirm.css" rel='stylesheet' type='text/css' />
	
	<script type="text/javascript">
	function my_confirm(myContent, myTitle, callback_confirm, callback_cancel){
		
		
		$('#my-confirm-content').html(myContent);

		$('.window_bod').show();
		$('.window_alert').show();
	
		$(".window_r").click(function(){
			$(".window_bod").hide();
			$(".window_alert").hide();
			
			if(callback_confirm && typeof callback_confirm == "function"){
				callback_confirm();
			}
		});

		$(".window_l").click(function(){
			$(".window_bod").hide();
			$(".window_alert").hide();

			if(callback_cancel && typeof callback_cancel == "function"){
				callback_cancel();
			}
		});
	}
	function my_fadeOut(content){

		if(typeof content != 'undefined'){

			$('.window_succ').html(content);
		}

		$(".window_succ").show().delay(1000).fadeOut(function(){
			location.reload();
		});
	}
	// alert提示窗
	function my_alert(myContent,myTitle,callback_confirm){
					
		$('.window_succ').html(myContent);
	
		$(".window_succ").show().delay(1000).fadeOut(function(){
			
			if(callback_confirm && typeof callback_confirm == "function"){
				callback_confirm();
			}
		});
	}
	</script>

	<!--弹窗start-->
	<div class="window_bod"></div>     
		<div class="window_alert">
			<div class="window_cont">
				<span class="window_c"><font id="my-confirm-content"></font></span>
				<div class="window_b">
					<span class="window_l">取消</span>
					<span class="window_r">确认</span>
				<div class="clear_both"></div>
			</div>
		</div>
	</div>
	<!--弹窗end-->

	<!--提示删除成功start-->
	<div class="window_succ"></div>
	<!--提示删除成功end-->