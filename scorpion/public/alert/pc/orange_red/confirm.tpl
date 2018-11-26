	<link href="{<$smarty.const.PUBLIC_ROOT>}alert/pc/orange_red/style/confirm.css" rel='stylesheet' type='text/css' />
	<script type="text/javascript">
		function my_confirm(myContent, myTitle, callback_confirm, callback_cancel){

			if(typeof myTitle != 'undefined'  && myTitle !=''){

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

			if(typeof myTitle != 'undefined' && myTitle !=''){

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
			确认删除吗？
		</div>
		<p>
			<span id="my-cancel-button">取消</span>
			<span id="my-confirm-button">确认</span>
		</p>
	</div>


	<div id="my-alert-window">
		<h2 id="my-alert-title">提示</h2>
		<div id="my-alert-content">
			确认删除吗？
		</div>
		<p>
        	<span id="my-alert-button">确认</span>
		</p>
	</div>

	<!--提示删除成功start-->
	<div class="window_succ"></div>
	<!--提示删除成功end-->