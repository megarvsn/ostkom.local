$(document).ready(function() {

	$('.quest-item .quest a').on('click', function() {
		$(this).parent().next('div.answer').toggle('normal');
		return false;
	});
});