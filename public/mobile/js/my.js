// 切换
(function(){

function tab(){
	var conlLi = $('.conl li');

	conlLi.bind('touchstart',function(){
		 var _index = $(this).index();
		 $(this).addClass('active').siblings().removeClass('active');
		 $('.conl-cont').hide();
		 $('#conlCont'+_index).show();
	})
}

tab();



// 提示框



})();