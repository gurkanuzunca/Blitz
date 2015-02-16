/**
 * Site genel javascript dosyası
 * 
 * @php-module <sss>
 */


/*var base_url		= document.getElementsByTagName('base')[0].href;*/
var query_string	= (function(a) {if (a === "") return {};var b = {};for (var i = 0; i < a.length; ++i){var p=a[i].split('=');if (p.length !== 2) continue;b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));}return b;})(window.location.search.substr(1).split('&'));



// Alert mesajları gösterme
function alertbox(element, type, message){
	$(".alert", element.parent()).remove();
	element.before('<div class="alert alert-'+ type +'">'+ message +'</div>');
}

// Document ready
$(function() {

	
	// Alert kapatma
	$(document).on('click', '.alert', function(){
		$(this).fadeOut();
	});
	
	
	// Margin temizleme
	$('.nth-child').each(function(){
		var offset = parseInt($(this).data('offset'));
		$(this).children(':nth-child('+ offset +'n+'+ offset +')').css("margin-right", 0);
	});
	

	
	
});
