/*
 * 功能：公共js验证
 * 版权所有：山西蒲公英生活商贸有限公司，并保留所有权利。
 * ----------------------------------------------------------------------------
 * 开发团队：蒲公英技术部
 */
//是否为空
function is_null(obj, info){
	if(obj.value == '' || obj.value.length < 1){
		alert('注意：' + info + '不得为空！');
		obj.focus();
		return true;
	}
	return false;
}

//是否为整数
function is_integer(obj, info){
	var reg = "^[0-9]+$";
	var reg_match = new RegExp(reg);
	if(obj.value.match(reg_match)){
		return true;
	}

	alert('注意：' + info + '只能是整数！');
	obj.focus();
	return false;
}

//是否为大于0的数字
function is_great_zero_number(obj, info){
	
	var reg = /^\d+(\.\d+)?$/;

	if(reg.test(obj.value)){
		if(Number(obj.value) > 0){
			return true;
		}
		else{
			alert('注意：' + info + '必须大于0！');
			obj.focus();
			return false;
		}
		
	}

	alert('注意：' + info + '只能是数字！');
	obj.focus();
	return false;
}

//是否为数字
function is_number(obj, info){
	
	var reg = /^\d+(\.\d+)?$/;

	if(reg.test(obj.value)){
		return true;
	}

	alert('注意：' + info + '只能是数字！');
	obj.focus();
	return false;
}

//最小长度
function is_min_length(obj, info, param){
	if(obj.value.length < param){
		alert('注意：' + info + '不得小于 ' + param + ' 位！');
		obj.focus();
		return true;
	}
	return false;
}

//最大长度
function is_max_length(obj, info, param){
	if(obj.value.length > param){
		alert('注意：' + info + '不得大于 ' + param + ' 位！');
		obj.focus();
		return true;
	}
	return false;
}

//是否为手机格式
function is_mobile(obj){
	
	var reg=/^[1][34578]\d{9}$/;

	if(reg.test(obj.value)){
		return true;
	}

	alert('注意：手机格式不正确！');
	obj.focus();
	return false;
}

//是否为邮箱格式
function is_email(obj){
	
	var reg = /^[a-zA-Z0-9_-]+(\.([a-zA-Z0-9_-])+)*@[a-zA-Z0-9_-]+[.][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*$/;

	if(reg.test(obj.value)){
		return true;
	}

	alert('注意：邮箱格式不正确！');
	obj.focus();
	return false;
}

//是否为电话格式
function is_phone(obj){
	
	var reg = /^(([0\+]\d{2,3})?(0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;

	if(reg.test(obj.value)){
		return true;
	}

	alert('注意：电话格式不正确！');
	obj.focus();
	return false;
}

//是否为qq格式
function is_qq(obj){
	
	var reg = /^[1-9][0-9]{4,}$/;

	if(reg.test(obj.value)){
		return true;
	}

	alert('注意：qq格式不正确！');
	obj.focus();
	return false;
}

//是否为msn格式
function is_msn(obj){
	
	var reg = /^[a-zA-Z0-9_-]+(\.([a-zA-Z0-9_-])+)*@[a-zA-Z0-9_-]+[.][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*$/;

	if(reg.test(obj.value)){
		return true;
	}

	alert('注意：msn格式不正确！');
	obj.focus();
	return false;
}

//是否为姓名格式
function is_realname(obj){
	
	var reg = /^[\w\u0391-\uFFE5]+$/gi;

	if(reg.test(obj.value)){
		return true;
	}

	alert('注意：姓名存在非法字符！');
	obj.focus();
	return false;
}


//是否为邮政编码格式
function is_zipcode(obj){
	
	var reg = /^[0-9]{6}$/

	if(reg.test(obj.value)){
		return true;
	}

	alert('注意：邮政编码格式不正确！');
	obj.focus();
	return false;
}

//是否为身份证
function is_idcart(obj){
	
	var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;

	if(reg.test(obj.value)){
		return true;
	}

	alert('注意：身份证格式不正确！');
	obj.focus();
	return false;
}

//是否为图片类型
function is_image_type(obj){
	var ext = obj.value.split(".");
	var type = ext[ext.length - 1];
	var flag = 0;
	//全部变成小写
	type= type.toLowerCase();

	if(type.match(/jpg|gif|png|bmp/i)) {
		return true;  
	}
 
	return false;
}

/*
 *********************************************************************
 * 没有弹窗说明的验证
 *********************************************************************
 */



//王:数据是否相等
function is_equal(source,destination)
{
   if(source==destination)
   {
   	  return true;
   }
   return false;
}

//王：把标签符替换成空
function tag_Replace(val)
{
	return val.replace(/<\/?[^>]*>/g, '');
}


//王：验非空   长度  特殊字符替换成空
function check(ob,title,tip,mindata,maxdata)
{

	ob.value=tag_Replace(ob.value);//替换标签
   
    if ($.trim(ob.value)=='') 
    {           
        $("#"+tip).html("请输入"+title);
        return false;
    } 
    
    if(mindata!=null)
    {
        if(is_min_length_ajax(ob, mindata))
        {
            $("#"+tip).html(title+" 不能少于 "+mindata+" 位");
            return false;
        }
    }
    
    if(maxdata!=null)
    {
        if(is_max_length_ajax(ob, maxdata))
        {
            $("#"+tip).html(title+" 不能超过 "+maxdata+" 位");
            return false;
        }
    }


    $("#"+tip).html("");
    return true;
    
}



//验邮箱及提示
function check_email_tip(ob,tip)
{
    if(!check(ob,'联系邮箱',tip,null,null))
    {
        return false;
    }
    if(!is_email_ajax(ob))
    {
        $("#"+tip).html("请输入正确的联系邮箱");
        return false;
    }
    $("#"+tip).html("");
    return true;
}


//验证电话号码及提示
function check_tel_tip(ob,tip)
{
	if(is_null_ajax(ob))
	{
		$("#"+tip).html('请输入您的联系电话');
		return false;
	}
	if(!is_mobile_ajax(ob))
	{
		$("#"+tip).html('请输入正当的手机号码');
		return false;
	}
    $("#"+tip).html('');
    return true;

}



//是否为空
function is_null_ajax(obj){
	if(obj.value == '' || obj.value.length < 1){
		return true;
	}
	return false;
}

//是否为整数
function is_integer_ajax(obj){
	var reg = "^[0-9]+$";
	var reg_match = new RegExp(reg);
	if(obj.value.match(reg_match)){
		return true;
	}
	return false;
}

//是否为字符
function is_char_ajax(obj){
	var reg = "^[a-zA-Z]+$";
	var reg_match = new RegExp(reg);
	if(obj.value.match(reg_match)){
		return true;
	}
	return false;
}

/*var reg = "^(?!.*?[\~\`\·\！\!@\#\￥\$%\……\^&\*\(\)\（\）\_\-\——\+\=\【\】\[\]\{\}\|\、\\\：\:\;\；\"\”\“\’\'\'\<\>\《\》\,\，\。\.\?\？\/]).*$";*/
//正则匹配特殊字符
function is_special_symbol(obj){
	var reg = "/\/|\~|\!|\@|\#|\\$|\%|\^|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/i";
	var reg_match = new RegExp(reg);
	if(obj.value.match(reg_match)){
		return true;
	}
	return false;
}




//是否为大于0的数字
function is_great_zero_number_ajax(obj){
	
	var reg = /^\d+(\.\d+)?$/;

	if(reg.test(obj.value)){
		if(Number(obj.value) > 0)
		{
			return true;
		}
		else{
			return false;
		}
		
	}
	return false;
}


//是否为数字
function is_number_ajax(obj){
	
	var reg = /^\d+(\.\d+)?$/;

	if(reg.test(obj.value)){
		return true;
	}

	return false;
}

//最小长度
function is_min_length_ajax(obj, param){
	if(obj.value.length < param){
		return true;
	}
	return false;
}

//最大长度
function is_max_length_ajax(obj, param){
	if(obj.value.length > param){
		return true;
	}
	return false;
}

//是否为手机格式
function is_mobile_ajax(obj){
	
	var reg=/^[1][3456789]\d{9}$/;
	     // /^[1][2345789]\d{9}$/

	if(reg.test(obj.value)){
		return true;
	}

	return false;
}

//是否为邮箱格式
function is_email_ajax(obj){
	
	var reg = /^[a-zA-Z0-9_-]+(\.([a-zA-Z0-9_-])+)*@[a-zA-Z0-9_-]+[.][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*$/;

	if(reg.test(obj.value)){
		return true;
	}

	return false;
}

//是否为电话格式
function is_phone_ajax(obj){
	
	var reg = /^(([0\+]\d{2,3})?(0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;

	if(reg.test(obj.value)){
		return true;
	}

	return false;
}

//是否营业执照编号格式
function is_licenceno_ajax(obj){
	var reg = /^\d{15}$/;

	if(reg.test(obj.value)){
		return true;
	}

	return false;
}

//是否为qq格式
function is_qq_ajax(obj){
	
	var reg = /^[1-9][0-9]{4,}$/;

	if(reg.test(obj.value)){
		return true;
	}

	return false;
}

//是否为msn格式
function is_msn_ajax(obj){
	
	var reg = /^[a-zA-Z0-9_-]+(\.([a-zA-Z0-9_-])+)*@[a-zA-Z0-9_-]+[.][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*$/;

	if(reg.test(obj.value)){
		return true;
	}

	return false;
}


//是否为中文、英文、数字
function is_NotSpecialChar_ajax(obj){
	
	var reg = /^[0-9a-zA-Z\u0391-\uFFE5]+$/gi;

	if(reg.test(obj.value)){
		return true;
	}
	return false;
}

//是否为姓名格式 --中文
function is_realname_ajax(obj){
	
	var reg = /^[\u0391-\uFFE5]+$/gi;

	if(reg.test(obj.value)){
		return true;
	}

	return false;
}


//是否为姓名格式 -- 中文或者英文
function is_Chin_Eng_ajax(obj){
	
	var reg = /^[a-zA-Z\u0391-\uFFE5]+$/gi;

	if(reg.test(obj.value)){
		return true;
	}

	return false;
}

//是否为年龄范围 -- 例如：18-99
function is_Age_Range_ajax(obj){

	
	//var reg = /^([\u4e00-\u9fa5]*[a-z]*[A-Z0-9]*[~-]*)+$/;
	//var reg = /^([0-9]+)|([0-9]+[-~][0-9]+)$/;
	var reg = /^([0-9]+[-~][0-9]+)|\d+$/;
	
	if(reg.test(obj.value)){
		var str = obj.value;
		var strs = str.split("-");
		for(var i=0;i<strs.length;i++)
		{
			if(strs[i] >= 18 && strs[i] < 100)
			{
				continue;
			}else{
				return false;
			}
		}
		return true;
	}

	return false;
}

//是否为薪资要求格式 -- 例如：2000、2000~3000、
function is_Salary_Range_ajax(obj){

	
	//var reg = /^([\u4e00-\u9fa5]*[a-z]*[A-Z0-9]*[~-]*)+$/;
	//var reg = /^([0-9]+)|([0-9]+[-~][0-9]+)$/;
	var reg = /^([0-9]+[-~][0-9]+)|\d+$/;

	if(reg.test(obj.value)){

		return true;
	}

	return false;
}

//是否为时间要求格式 -- 例如：12:00-16:00、12:00~16:00
function is_Date_Range_ajax(obj){
	
	//var reg = /^([\u4e00-\u9fa5]*[a-z]*[A-Z0-9]*[:]*[\u4e00-\u9fa5]*[a-z]*[A-Z0-9]*[~-]*)+$/;
	var reg = /^\d{2}:\d{2}[-~]\d{2}:\d{2}$/;

	if(reg.test(obj.value)){
		return true;
	}
	
	return false;
}


/*// 验证中文
public static function checkChn($_data) 
{
	if (preg_match('/^[u4E00-u9FA5]+$/',$_data)) return true;
	return false;
}*/

//是否为中文
function is_Chinese_ajax(obj)
{
	var reg = /^[u4E00-u9FA5]+$/;

	if(reg.test(obj.value)){
		return true;
	}

	return false;
}

//是否为邮政编码格式
function is_zipcode_ajax(obj){
	
	var reg = /^[0-9]{6}$/

	if(reg.test(obj.value)){
		return true;
	}

	return false;
}

//是否为身份证
function is_idcart_ajax(obj){
	
	var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;

	if(reg.test(obj.value)){
		return true;
	}

	return false;
}

//是否为图片类型
function is_image_type(obj){
	var ext = obj.value.split(".");
	var type = ext[ext.length - 1];
	var flag = 0;
	//全部变成小写
	type= type.toLowerCase();

	if(type.match(/jpg|gif|png|bmp/i)) {
		return true;  
	}
 
	return false;
}

/*
 * 功能：限制上传文件大小
 * 参数：
 * inputFile上传文件的表单名
 * info提示信息
 * filesSize限制大小,单位为M
 */
function is_upload_file(inputFile,info,filesSize){

	if(inputFile.value == '' || inputFile.value.length < 1){
		alert('注意：请上传' + info + '！');
		inputFile.focus();
		return false;
	}

	if(!lglp_is_image_type(inputFile)){
		alert("注意：只能上传jpg,gif,png,bmp类型的图片！");
		return false;
	}

	var isIE = /msie/i.test(navigator.userAgent) && !window.opera;         

	var fileSize = 0;          
	if (isIE && !inputFile.files){   
		var filePath = inputFile.value;      
		var fileSystem = new ActiveXObject("Scripting.FileSystemObject");         
		var file = fileSystem.GetFile(filePath);      
		fileSize = file.Size;     
	} 
	else {     
		fileSize = inputFile.files[0].size;      
	}    

	var size = fileSize / 1024/1024;    
	if(size>(filesSize)){   
		alert("注意：" + info + "大小不能超过&nbsp;" + filesSize + "&nbsp;M！");
		return false;

	}   
	
	return true;
}

