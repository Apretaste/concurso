$(document).ready(function () {
	$('.tabs').tabs();
	$('.modal').modal();
});

var share;
function init(contest) {
	share = {
		text: 'CONCURSO: ' + removeTags(contest.body).substr(0, 50) + '...',
		icon: 'star',
		send: function () {
			apretaste.send({
				command: 'PIZARRA PUBLICAR',
				redirect: false,
				callback: {
					name: 'toast',
					data: 'El concurso fue compartido en Pizarra'
				},
				data: {
					text: $('#message').val(),
					image: '',
					link: {
						command: btoa(JSON.stringify({
							command: 'CONCURSO VER',
							data: {
								id: contest.id
							}
						})),
						icon: share.icon,
						text: share.text
					}
				}
			})
		}
	};
}

function toast(message){
	M.toast({html: message});
}


function removeTags(str) {
	if ((str===null) || (str===''))
		return '';
	else
		str = str.toString();
	return str.replace( /(<([^>]+)>)/ig, '');
}