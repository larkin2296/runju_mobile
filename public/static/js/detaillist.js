

$('#h_price').click(function(){
    $('#h_price_detail').animate({position:'absolute',left:'0px'},500);
})
$('#h_select').click(function(){
    $('#h_select_detail').animate({position:'absolute',left:'0px'},500);
})
$('#h_type').click(function(){
    $('#house_type_list').animate({position:'absolute',left:'0px'},500);
})
	$('#line_area_detail .area_detail_left li').click(function(){
		$('#line_area_detail .area_detail_left li').removeClass('active');
		$(this).addClass('active');
		$.ajax({  
                type: "POST",  
                url:"../../../../../index.php/index/detail_list/get_station",  
                data:{'id':$(this).val()},// 序列化表单值  
                dataType: "json", 
                success: function(data) {
                			var ob='';
					$.each(data,function(i,value){
						ob = ob + '<li data-*="'+value.u_id+'">'+value.underground_name+'</li>';
						})
					$('#line_area_detail .area_detail_right').html(ob);						
                }  
           }); 
	});
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
	$('#line_area_detail .area_detail_right').on('click','li',function(){
		$.ajax({  
	        type: "POST",  
	        url:"../../../../../index.php/index/detail_list/get_house_list",  
	        data:{'id':$(this).data('*'),'type_1':2},// 序列化表单值  
	        dataType: "json", 
	        success: function(data) {
	        		$('.main_list ul').empty();
	        		var ob = '';
	        		$.each(data,function(i,value){
	        		ob = ob+"<li><div class='list_detail'  onclick='gotodetail("+value.id+")'><div class='list_detail_left'><img src='../../../../../public/uploads/"+value.pic_1+"'  /></div><div class='list_detail_right'><div class='list_detail_title' ><span>"+value.title.substring(0,30)+"..."+"</span></div><div class='list_detail_content'>"+value.house_type_name+"<br />"+value.address+"</div><div class='list_detail_price'>"+value.price+"</div></div></div></li>"	
	        		})
	        		$('.main_list ul').append(ob);
					$('.list').css('left','-100%');
	        }  
   		});
	})


	$('#h_price_detail ul li').click(function(){
        $('#h_price_detail ul li').css('color','black');
        $('#h_price_detail ul li').removeClass('check');
        $(this).addClass('check');
		$(this).css('color','orange');
	})

	