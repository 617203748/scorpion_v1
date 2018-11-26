/*
*****公式编辑器*****
* 调用 editTips()方法
* editTips({
*    	triggerCharacterArr : ["$","@"], // 指定触发初始检索字符数组
*       levelCharacter: '.', // 指定分层触发检索字符
*		textareaHeight:    // 输入框高度 默认 auto
*		dropdownWidth: 	   // 下拉提示框宽度 默认150px
*		keyPressAction:function(selectVal,callbacktips){  
*		// 参数为selectVal 返回 arr_json 数组 调用回调函数 callbacktips(arr_json)
*			var arr_json;
*			callbacktips(arr_json);
*		}			
*   });
* 
*/

(function ($) {
	$.fn.extend({
	    "editTips": function (options) {
		    var opts = $.extend({}, defaults, options);
		    return this.each(function(e){
	    		var kingwolfofsky = {  
				    getInputPositon: function (elem) {  
				        if (document.selection) { 
				            elem.focus();  
				            var Sel = document.selection.createRange();  
				            return {  
				                left: Sel.boundingLeft,  
				                top: Sel.boundingTop,  
				                bottom: Sel.boundingTop + Sel.boundingHeight  
				            };  
				        } else {  
				            var that = this;  
				            var cloneDiv = '{$clone_div}', cloneLeft = '{$cloneLeft}', cloneFocus = '{$cloneFocus}', cloneRight = '{$cloneRight}';  
				            var none = '<span style="white-space:pre-wrap;"> </span>';  
				            var div = elem[cloneDiv] || document.createElement('div'), focus = elem[cloneFocus] || document.createElement('span');  
				            var text = elem[cloneLeft] || document.createElement('span');  
				            var offset = that._offset(elem), index = this._getFocus(elem), focusOffset = { left: 0, top: 0 };  
				  
				            if (!elem[cloneDiv]) {  
				                elem[cloneDiv] = div, elem[cloneFocus] = focus;  
				                elem[cloneLeft] = text;  
				                div.appendChild(text);  
				                div.appendChild(focus);  
				                document.body.appendChild(div);  
				                focus.innerHTML = '|';  
				                focus.style.cssText = 'display:inline-block;width:0px;overflow:hidden;z-index:-100;word-wrap:break-word;word-break:break-all;';  
				                div.className = this._cloneStyle(elem);  
				                div.style.cssText = 'visibility:hidden;display:inline-block;position:absolute;z-index:-100;word-wrap:break-word;word-break:break-all;overflow:hidden;';  
				            };  
				            div.style.left = this._offset(elem).left + "px";  
				            div.style.top = this._offset(elem).top + "px";  
				            var strTmp = elem.value.substring(0, index).replace(/</g, '<').replace(/>/g, '>').replace(/\n/g, '<br/>').replace(/\s/g, none);  
				            text.innerHTML = strTmp;  				  
				            focus.style.display = 'inline-block';  
				            try { focusOffset = this._offset(focus); } catch (e) { };  
				            focus.style.display = 'none';  
				            return {  
				                left: focusOffset.left,  
				                top: focusOffset.top,  
				                bottom: focusOffset.bottom  
				            };  
				        }  
				    },
				    _cloneStyle: function (elem, cache) {  
				        if (!cache && elem['${cloneName}']) return elem['${cloneName}'];  
				        var className, name, rstyle = /^(number|string)$/;  
				        var rname = /^(content|outline|outlineWidth)$/;
				        var cssText = [], sStyle = elem.style;  
				  
				        for (name in sStyle) {  
				            if (!rname.test(name)) {  
				                val = this._getStyle(elem, name);  
				                if (val !== '' && rstyle.test(typeof val)) { 
				                    name = name.replace(/([A-Z])/g, "-$1").toLowerCase();  
				                    cssText.push(name);  
				                    cssText.push(':');  
				                    cssText.push(val);  
				                    cssText.push(';');  
				                };  
				            };  
				        };  
				        cssText = cssText.join('');  
				        elem['${cloneName}'] = className = 'clone' + (new Date).getTime();  
				        this._addHeadStyle('.' + className + '{' + cssText + '}');  
				        return className;  
				    },  
				    _addHeadStyle: function (content) {  
				        var style = this._style[document];  
				        if (!style) {  
				            style = this._style[document] = document.createElement('style');  
				            document.getElementsByTagName('head')[0].appendChild(style);  
				        };  
				        style.styleSheet && (style.styleSheet.cssText += content) || style.appendChild(document.createTextNode(content));  
				    },  
				    _style: {},  
				    _getStyle: 'getComputedStyle' in window ? function (elem, name) {  
				        return getComputedStyle(elem, null)[name];  
				    } : function (elem, name) {  
				        return elem.currentStyle[name];  
				    },  
				    _getFocus: function (elem) {  
				        var index = 0;  
				        if (document.selection) {
				            elem.focus();  
				            var Sel = document.selection.createRange();  
				            if (elem.nodeName === 'TEXTAREA') {
				                var Sel2 = Sel.duplicate();  
				                Sel2.moveToElementText(elem);  
				                var index = -1;  
				                while (Sel2.inRange(Sel)) {  
				                    Sel2.moveStart('character');  
				                    index++;  
				                };  
				            }  
				            else if (elem.nodeName === 'INPUT') {
				                Sel.moveStart('character', -elem.value.length);  
				                index = Sel.text.length;  
				            }  
				        }  
				        else if (elem.selectionStart || elem.selectionStart == '0') {
				            index = elem.selectionStart;  
				        }  
				        return (index);  
				    },  
				    _offset: function (elem) {  
				        var box = elem.getBoundingClientRect(), doc = elem.ownerDocument, body = doc.body, docElem = doc.documentElement;  
				        var clientTop = docElem.clientTop || body.clientTop || 0, clientLeft = docElem.clientLeft || body.clientLeft || 0;  
				        var top = box.top + (self.pageYOffset || docElem.scrollTop) - clientTop, left = box.left + (self.pageXOffset || docElem.scrollLeft) - clientLeft;  
				        return {  
				            left: left,  
				            top: top,  
				            right: left + box.width,  
				            bottom: top + box.height  
				        };  
				    }  
				};
		    	var _this = $(this);
			    var e = event || window.event; 
			    var searchStart = false;
			    var checkCharacter = false;		    
			    var oldCurrentPos = '';
			    var currentPos = '';
			    var selectVal = '';
			    var pos = '';
			    var enterCharacter = ''; 
			    var dotVal; 
				var dotDollerPos;
				var dotSelectVal;
				var isTriggerCharacter;
				var dropDownZindex;
				var dotSearchStart = false;
			 	_this.dropdown = $('<ul class="editTips" style="display:none;"></ul>');
			 	_this.attr("init","true");
		      	$("body").after(_this.dropdown);			      	
				_this.dropdown.css({
					'width':opts.dropdownWidth,
					'position':'absolute',
				});
				_this.css({
		      		'position': 'relative',

		      	});
				var getStart =function() {			
					var all_range = '';
					if (navigator.userAgent.indexOf("MSIE") > -1) {
						if( _this.get(0).tagName == "TEXTAREA" ){ 
							all_range = document.body.createTextRange();
							all_range.moveToElementText(_this.get(0));
						} else {
							all_range = _this.get(0).createTextRange();
						}
						_this.focus();
						var cur_range = document.selection.createRange();
						cur_range.moveEnd('character',-cur_range.text.length)
						cur_range.setEndPoint("StartToStart",all_range);
						currentPos = cur_range.text.length;
					} else {
						_this.focus();
						currentPos = _this.get(0).selectionStart;
					}
					return currentPos;
				};
				var getOldCurrentPos = function(){
					getStart(); 
					oldCurrentPos = currentPos;
					console.log(oldCurrentPos);
				}
				var endSearch = function(){
					_this.dropdown.find("li").remove();
					_this.dropdown.hide();
					searchStart = false;
					dotSearchStart = false;
					enterCharacter='';
				}
				var setCarePosition = function(start,end) {
					if(navigator.userAgent.indexOf("MSIE") > -1){
						var all_range = '';
						if( _this.get(0).tagName == "TEXTAREA" ){ 
							all_range = document.body.createTextRange();
							all_range.moveToElementText(_this.get(0));
						} else {
							all_range = _this.get(0).createTextRange();
						}
						_this.focus();
						all_range.moveStart('character',start);
						all_range.moveEnd('character',-(all_range.text.length-(end-start)));
						all_range.select();
					}else{
						_this.focus();
						_this.get(0).setSelectionRange(start,end);
					}
				};
				var getSelectVal = function(e){
					var val = _this.val();
					var e = event || window.event;
					if( searchStart == true ){
						selectVal = val.substring(oldCurrentPos-1,currentPos);
					}
					if( dotSearchStart == true ){
						dotVal = val.slice(0,currentPos);
						var isNumArr = [];
						for(var i=0;i<opts.triggerCharacterArr.length;i++){
							var isNum =  dotVal.lastIndexOf(opts.triggerCharacterArr[i]);
							if( isNum != -1 ){
								isNumArr.push(isNum);
							}
						}
						isNumArr = isNumArr.sort();
						isTriggerCharacter = dotVal.substring(isNumArr[isNumArr.length-1],isNumArr[isNumArr.length-1]+1);
						console.log(isNumArr);	
						dotDollerPos = dotVal.lastIndexOf(isTriggerCharacter);
						dotSelectVal = dotVal.substring(dotDollerPos,currentPos);
						selectVal = dotSelectVal;
						console.log("到当前下标的字符串为："+dotVal);
						console.log("到当前下标最近的$下标是："+dotDollerPos);
						console.log("输入 . 时检索值为："+dotSelectVal);
					}				
					console.log("获取的值区域为："+oldCurrentPos+"-"+currentPos);
					if( e.keyCode == 8 && oldCurrentPos == currentPos ){
						var oldCharacter = val.substring(oldCurrentPos-1, currentPos);
						console.log(oldCharacter);
						var checkVal = opts.triggerCharacterArr.join('');
						var checkOldCharacter = checkVal.indexOf(oldCharacter);
						if( oldCharacter == '' || checkOldCharacter == -1 ){
							endSearch()
						}						
					}				
				}
				var changeValue = function(){
					var val = _this.val();	
					var liTxt = _this.dropdown.find(".active").text();
					var liTxtLength = liTxt.length;
					var valLength = val.length;
					if( enterCharacter == opts.levelCharacter ){
						var beforeSelectVal = val.substring(0,currentPos);				
					}
					else{
						var beforeSelectVal = val.substring(0,oldCurrentPos);
					}
					var beforeSelectValLength = beforeSelectVal.length;
					var afterSelectVal = val.substring(currentPos,valLength);
					var pos = liTxtLength + beforeSelectValLength;
					val = beforeSelectVal+liTxt+afterSelectVal;
					_this.val(val);
					setCarePosition(pos,pos);
					endSearch();
					console.log("文本长度:"+beforeSelectVal.length);
					console.log("li文本为:"+liTxt);
					console.log("前部为:"+beforeSelectVal);
					console.log("后部分为:"+afterSelectVal);
					return false;
				}
				var callbacktips = function(arr_json){
					_this.dropdown.hide();
					_this.dropdown.find("li").remove();
					if( arr_json ){
						if( arr_json.length > 0 ){
							for( i=0;i<arr_json.length;i++ ){
									_this.dropdown.append('<li>'+arr_json[i]+'</li>');									
							};
							var indexObj = _this.closest(".layui-layer");
						 	if( indexObj.length > 0){
						 		dropDownZindex = indexObj.css("z-index")+1;
						 	}else{
						 		dropDownZindex = "999";
						 	};			      	
							_this.dropdown.css({
								"z-index":dropDownZindex
							});
							_this.dropdown.show();
							_this.dropdown.find("li:first-child").addClass("active");
							_this.dropdown.find("li").css({	
								'width':'100%',
							});
						}						
					}	        		
					
		        };
				_this.click(function(){
					getOldCurrentPos()
				});
				_this.keydown(function(e){
					var dropdownIsshow = _this.dropdown.css("display");
					if( dropdownIsshow == "block" ){
						if( e.keyCode == 37 || e.keyCode == 38 || e.keyCode == 39 || e.keyCode == 40 || e.keyCode == 13 ){
							e.preventDefault();
						}
					}
				})
				_this.keyup(function(e){
					var val = _this.val();	
					var n = _this.dropdown.find(".active").index();
					var n_max = _this.dropdown.find("li").length;			
					getStart();
					if( e.keyCode == 38 ){						
						if(n-1>=0){
							_this.dropdown.scrollTop(30*(n-1));
							_this.dropdown.find('li').eq(n-1).addClass("active").siblings().removeClass("active");
						}
						if( n == 0){
							_this.dropdown.scrollTop(30*(n_max-1));
							_this.dropdown.find('li').eq(n_max-1).addClass("active").siblings().removeClass("active");
						}
						return false;
					}				
					if( e.keyCode == 40 ){
						if(n<n_max-1){
							_this.dropdown.scrollTop(30*n);
							_this.dropdown.find('li').eq(n+1).addClass("active").siblings().removeClass("active");	
						}
						if( n+1 == n_max ){
							_this.dropdown.scrollTop(0);
							_this.dropdown.find('li').eq(0).addClass("active").siblings().removeClass("active");
						}
						return false;
					}	
					if( e.keyCode != 37 && e.keyCode != 38 && e.keyCode != 39 && e.keyCode != 40 ){
						var reg = new RegExp("[`~!#^&*()=|{}':;',\\[\\]<>/?~！#￥……&*（）——|{}【】‘；：”“'。，、？]");
						enterCharacter = val.substring(currentPos-1,currentPos);
						if( reg.test(enterCharacter) == false && enterCharacter != " "){ 
							checkCharacter = true;
						}else{
							checkCharacter = false;
							endSearch()
							console.log("输入了不合法字符");				
						}						
					}					
					console.log("当前输入的字符是:"+enterCharacter);
					if( enterCharacter == opts.levelCharacter ){
						searchStart = false;
						dotSearchStart = true;
						getOldCurrentPos();
					}
					if( $.isArray(opts.triggerCharacterArr) ){
						var triggerNum;
						for(var i=0;i<opts.triggerCharacterArr.length;i++){
							triggerNum = opts.triggerCharacterArr[i].indexOf(enterCharacter);
							if( triggerNum != -1 ){
								searchStart = true;
								dotSearchStart = false;
								getOldCurrentPos();
							}
						}						
					}
					getSelectVal();
					if( (searchStart == true || dotSearchStart == true) && checkCharacter == true && e.keyCode != 13 ){
						console.log("获取的值:"+selectVal);
						if( $.isFunction(opts.keyPressAction) ){						
							opts.keyPressAction(selectVal, function(arr_json){
								callbacktips(arr_json);						
							});
						}
					}
					if( e.keyCode == 13 ){
						var dropdownIsshow = _this.dropdown.css("display");
						if( dropdownIsshow == "block" ){
							changeValue();
							console.log("这是点击enter后searchStart："+searchStart);
						}
					}
					console.log("这是整个事件执行完成以后："+searchStart);
				});
				_this.dropdown.on('mouseenter','li',function(){
					$(this).addClass("active").siblings().removeClass("active");
				});
				$(document).click(function(e){
					var e = event || window.event;
					var el = e.target.localName;
					el == "li" ? changeValue() : endSearch();
				})
				$(this).focus(function(){
					show(this);
				});				
				function show(elem) {  
				    var p = kingwolfofsky.getInputPositon(elem);  
				    var s = _this.dropdown.get(0); 
				    var ttop = parseInt(_this.css("marginTop"));
				    var tleft = parseInt(_this.css("marginLeft"));
				    s.style.top = p.bottom-ttop+10+'px';  
				    s.style.left = p.left-tleft + 'px';                    
				}
		    })

		}

	})	
	var	defaults = {	
		triggerCharacterArr : ["$","@"],
		levelCharacter: '.',
        dropdownWidth:'150px',
        dropdownHeight:'120px'
	};
})(window.jQuery);
