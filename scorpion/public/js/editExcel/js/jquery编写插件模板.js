(function($){
	$.fn.extend({
		"xxx" : function( options ){
			var opts = $.extend({},defaults,options);
			this.each(function(){
				var n = function(arr_json){

				}
				opts.a;
				opts.b;
				opts.c(m,function(x){
					n(x);
				})
			})
		}
	})
	// 默认参数
	var defaults = {
		a:'',
		b:'',
		c:function(m,n){
			var x ;
			n(x);
		}
	}
})(window.jQuery);
$("xx").xxx({
		a:'',
		b:'',
		c:function(m,n){
			var x ;
			n(x);
		}
})