var liwidth = parseInt($(".container li").width());
var num_ber = 40;
var index_ber = 0;
var timers2 = setInterval(timer,2000);
$('.container').mouseenter(function(){
	clearInterval(timers2);
}).mouseleave(function(){
	timers2 = setInterval(timer,2000);
})

function timer(){
 	if(num_ber <= liwidth * 2){
 		num_ber += liwidth;
 		$('.container .roll_ul').stop().animate({left:-num_ber + 'px'});
 		index_ber +=1;
 	}else if(num_ber >= liwidth * 2){
  		num_ber = 40;
 		$('.container .roll_ul').stop().animate({left:-num_ber + 'px'});
 		index_ber =0;		
 	}
 }
