// 切换
(function(){

function tab(){
	var conlLi = $('.conl li');

	conlLi.bind('click',function(){
		 var _index = $(this).index();
		 $(this).addClass('active').siblings().removeClass('active');
		 $('.conl-cont').hide();
		 $('#conlCont'+_index).show();
	})
}

tab();


})();