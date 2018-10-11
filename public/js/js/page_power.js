// 侧滑导航部分
$('span.toggle').click(function(){
	$(this).toggleClass('outside');
	
	if($(this).hasClass('outside'))
	{
		var ulWidth = $('#main>ul').width();
		$('#main').css({'transform': 'translateX(1.5rem)'})
		// $('#main>ul').css({'marginLeft': '-1.5rem'})

	}
	else
	{$('#main').css({'transform': 'translateX(0)'})
		// $('#main>ul').css({'marginLeft':'0'})
	}
})


// 侧边导航跳转
$('#main>ul li').click(function(){
	
	$(this).addClass('nav_acitve').siblings().removeClass('nav_acitve');
	

})