var $activer = null;
$('.toggler').click(function(){
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


