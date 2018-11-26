
	<link href="{<$smarty.const.PUBLIC_ROOT>}alert/pc/orange/style/confirm.css" rel='stylesheet' type='text/css' />
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
			二、带确认取消的弹窗用法：
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
		function my_confirm(myContent, myTitle, callback_confirm, callback_cancel){
			
			if(typeof myTitle != 'undefined'){

				$('#my-confirm-title').html(myTitle);
			}
			$('#my-confirm-content').html(myContent);

			$('#my-mask').show();
			$('#my-confirm-window').show();
		
			$("#my-confirm-button").click(function(){
				$('#my-mask').hide();
				$('#my-confirm-window').hide();
				
				 if(callback_confirm && typeof callback_confirm == "function"){
					callback_confirm();
				 }
			});

			$("#my-cancel-button").click(function(){
				$('#my-mask').hide();
				$('#my-confirm-window').hide();

				if(callback_cancel && typeof callback_cancel == "function"){
					callback_cancel();
				 }
			});
		}

		// alert提示窗
		function my_alert(myContent,myTitle,callback_confirm){
						
			$('#my-alert-content').html(myContent);

			if(typeof myTitle != 'undefined'){

				$('#my-alert-title').html(myTitle);
			}

			$('#my-mask').show();
			$('#my-alert-window').show();
		
			$("#my-alert-button").click(function(){
				$('#my-mask').hide();
				$('#my-alert-window').hide();

				 if(callback_confirm && typeof callback_confirm == "function"){
					callback_confirm();
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

	</script>

	<div id="my-mask"></div>
	<div id="my-confirm-window">
		<h2 id="my-confirm-title">提示</h2>
		<div id="my-confirm-content">
			提示信息
		</div>
		<p>
			<span id="my-cancel-button">取消</span>
			<span id="my-confirm-button">确认</span>
			
		</p>
	</div>
	<div id="my-alert-window">
		<h2 id="my-alert-title">提示</h2>
		<div id="my-alert-content">
			提示信息
		</div>
		<p>
			<span id="my-alert-button">确认</span>
		</p>
	</div>

	<!--提示删除成功start-->
	<div class="window_succ"></div>
	<!--提示删除成功end-->