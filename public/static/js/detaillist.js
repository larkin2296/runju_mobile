

$('#h_price').click(function(){
    $('#h_price_detail').animate({position:'absolute',left:'0px'},500);
})
$('#h_select').click(function(){
    $('#h_select_detail').animate({position:'absolute',left:'0px'},500);
})
$('#h_type').click(function(){
    $('#house_type_list').animate({position:'absolute',left:'0px'},500);
})

	$('#h_map').click(function(){
        $('#list_area_detail').animate({position:'absolute',left:'0px'},1000);

	})
	$('#list_underground').click(function(){
		if($('#line_area_detail').css('display') == 'block'){
			$('#line_area_detail').css('display','none');			
		}else if($('#line_area_detail').css('display') == 'none'){
			$('#line_area_detail').css('display','block');			
		}

	})


	$('#h_price_detail ul li').click(function(){
        $('#h_price_detail ul li').css('color','black');
        $('#h_price_detail ul li').removeClass('check');
        $(this).addClass('check');
		$(this).css('color','orange');
	})

	$('#line').click(function(){
        $('#area_left').css('display','none');
        $('#line_left').css('display','block');
        $('#area').css('color','darkgrey');
        $('#line').css('color','orange');
	})
	$('#area').click(function(){
		$('#area_left').css('display','block');
        $('#line_left').css('display','none');
		$('#line').css('color','darkgrey');
		$('#area').css('color','orange');
	})