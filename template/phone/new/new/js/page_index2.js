var $activer = null;
$('.toggler').click(function(){

	// console.log($('.toggler :not(button)'))
	// console.log(window.event);
	var e = window.event;
	console.log(e.target.tagName)
	if(e.target.tagName == 'BUTTON')
	{
		console.log('?')
		e.stopPropagation();
		return;
	}


	if($activer)
	{
		$activer.removeClass('tableActive');
	}
	if($(this).attr('data-open') == 'false')
	{
		$(this).find('i').removeClass('fa-toggle-right').addClass('fa-toggle-down')
		$(this).attr('data-open','true')
	}else
	{$(this).attr('data-open','false')
		$(this).find('i').removeClass('fa-toggle-down').addClass('fa-toggle-right')
	}
})

$('table tbody td').click(function(){
	$activer = $(this).parent();
	$(this).parent().addClass('tableActive').siblings().removeClass('tableActive');
})


