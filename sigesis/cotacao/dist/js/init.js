/** *************Init JS*********************

    TABLE OF CONTENTS
	---------------------------
	1.Ready function
	2.Load function
	3.Full height function
	4.philbert function
	5.Chat App function
	6.Resize function
 ** ***************************************/

 "use strict";

	if($('.moeda')){
		$('.moeda').maskMoney({symbol:'R$ ', thousands:'.', decimal:',', symbolStay: true, allowNegative: true});
	};

	if($('.data').length > 0){
		$('.data').mask('99/99/9999');
	};

	if ($('.calendario').length > 0) {
		$('.calendario').datepicker({
			orientation: "left",
			language: "pt-BR",
			format: "dd/mm/yyyy",
			todayBtn: "linked",
			autoclose: true
		});
	};

	//COTACAO - Salvar cotação
    if($('#salvarcotacao').length > 0) {
        $('#salvarcotacao').click(function(){
			var str = $("#admin_form").serialize();
			jQuery.ajax({
				type: 'POST',
				url: '../controller.php',
				data: 'processarCotacaoFornecedor=1&'+str,
				success: function( data )
				{
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1],
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						stackup_spacing: 10
					});
					setTimeout(function(){
						window.location.reload();
					}, 1000);
				}
			});
			return false;

		});
    };

/***** Full height function start *****/
var setHeightWidth = function () {
	var height = $(window).height();
	var width = $(window).width();
	$('.full-height').css('height', (height));
	$('.page-wrapper').css('min-height', (height));

	/*Right Sidebar Scroll Start*/
	if(width<=1007){
		$('#chat_list_scroll').css('height', (height - 270));
		$('.fixed-sidebar-right .chat-content').css('height', (height - 279));
		$('.fixed-sidebar-right .set-height-wrap').css('height', (height - 219));

	}
	else {
		$('#chat_list_scroll').css('height', (height - 204));
		$('.fixed-sidebar-right .chat-content').css('height', (height - 213));
		$('.fixed-sidebar-right .set-height-wrap').css('height', (height - 153));
	}
	/*Right Sidebar Scroll End*/

	/*Vertical Tab Height Cal Start*/
	var verticalTab = $(".vertical-tab");
	if( verticalTab.length > 0 ){
		for(var i = 0; i < verticalTab.length; i++){
			var $this =$(verticalTab[i]);
			$this.find('ul.nav').css(
			  'min-height', ''
			);
			$this.find('.tab-content').css(
			  'min-height', ''
			);
			height = $this.find('ul.ver-nav-tab').height();
			$this.find('ul.nav').css(
			  'min-height', height + 40
			);
			$this.find('.tab-content').css(
			  'min-height', height + 40
			);
		}
	}
	/*Vertical Tab Height Cal End*/
};
/***** Full height function end *****/

/***** philbert function start *****/
var $wrapper = $(".wrapper");

var boxLayout = function() {
	if((!$wrapper.hasClass("rtl-layout"))&&($wrapper.hasClass("box-layout")))
		$(".box-layout .fixed-sidebar-right").css({right: $wrapper.offset().left + 300});
		else if($wrapper.hasClass("box-layout rtl-layout"))
			$(".box-layout .fixed-sidebar-right").css({left: $wrapper.offset().left});
}
boxLayout();

/***** Resize function start *****/
$(window).on("resize", function () {
	setHeightWidth();
	boxLayout();
}).resize();
/***** Resize function end *****/

