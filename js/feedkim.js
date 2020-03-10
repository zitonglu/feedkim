$(document).ready(function() {
	//将网站菜单中自定义a改为button按钮
	$('#feeds-div .menu-item-type-custom>a').each(function() {
		var $feedButtonVal = $(this).attr('href');
		$(this).before('<img src="'+feedkim_findICO($feedButtonVal)
				+'" onerror="javascript:this.src=\''+$('#myico').text()
				+'\';" alt="favicon.ico" class="favicon-ico">');
		if ($(this).parent().hasClass('menu-item-has-children')) {
			//有子集时
			$childrenUrls = $(this).parent().find(".menu-item-type-custom>a");
			for (var i = $childrenUrls.length - 1; i >= 0; i--) {
				var $thisUrl = $($childrenUrls[i]).attr('href');
				$feedButtonVal = $feedButtonVal + ',' + $thisUrl;
			}
		}
		$(this).after('<button type="submit" class="btn btn-link" name="feedbutton" value="'
			+$feedButtonVal+'">'+$(this).text()+'</button>');
		$(this).hide();
		$(this).removeAttr('href');
		//console.log('x');
	});

	//按钮控制input值
	$('button[name=\"feedbutton\"]').each(function(){
		$(this).click(function() {
			var $thisUrl = $(this).val();
			$('input[name=\"feedUrl\"]').val($thisUrl);
		});
	});
	//侧栏跟随滚动
	$('.sidebar').theiaStickySidebar({
	      additionalMarginTop:70
	});
	//无限下拉测试,参考
	//https://blog.csdn.net/qq_42249896/article/details/85343901
	//https://www.cnblogs.com/pink-chen/p/10629642.html
	$('#pager').mouseover(function() {
		pullOnLoad();
	});

})

//查找网址的ICO图标
function feedkim_findICO($url) {
	var $n = $url.indexOf('/');
	$n = $n + 2;
	$url = $url.substring($n);//del http
	var $n = $url.indexOf('/');
	if ($n>0) {
		$url = $url.substring(0,$n);
	}
	$url = '//'+$url+'/'+'favicon.ico';
	return $url;
}
//无限下拉相关
function pullOnLoad() {
	//var feedUrl = $('#feedUrl').val();
	$list = $('#feedList').val();
	$feedUrl = $('#feedUrl').val();
	$ullis = $('#indexListUl').length();
    setTimeout(function () {
        $.ajax({
        	url:$list,
            type:'post',
            dataType:'html',
            data:{feedKimPaged:8,feedUrl:$feedUrl},
            success:function(data){
            	console.log(data);
            }
        });
    },500);
}