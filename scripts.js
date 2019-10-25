$(document).ready(function(){
	$('.tabs').tabs();
});

var colors = {
	'Azul': '#99F9FF',
	'Verde': '#9ADB05',
	'Rojo': '#FF415B',
	'Morado': '#58235E',
	'Naranja': '#F38200',
	'Amarillo': '#FFE600'
};
var avatars = {
	'Rockera': 'F',
	'Tablista': 'F',
	'Rapero': 'M',
	'Guapo': 'M',
	'Bandido': 'M',
	'Encapuchado': 'M',
	'Rapear': 'M',
	'Inconformista': 'M',
	'Coqueta': 'F',
	'Punk': 'M',
	'Metalero': 'M',
	'Rudo': 'M',
	'Señor': 'M',
	'Nerd': 'M',
	'Hombre': 'M',
	'Cresta': 'M',
	'Emo': 'M',
	'Fabulosa': 'F',
	'Mago': 'M',
	'Jefe': 'M',
	'Sensei': 'M',
	'Rubia': 'F',
	'Dulce': 'F',
	'Belleza': 'F',
	'Músico': 'M',
	'Rap': 'M',
	'Artista': 'M',
	'Fuerte': 'M',
	'Punkie': 'M',
	'Vaquera': 'F',
	'Modelo': 'F',
	'Independiente': 'F',
	'Extraña': 'F',
	'Hippie': 'M',
	'Chica Emo': 'F',
	'Jugadora': 'F',
	'Sencilla': 'F',
	'Geek': 'F',
	'Deportiva': 'F',
	'Moderna': 'F',
	'Surfista': 'M',
	'Señorita': 'F',
	'Rock': 'F',
	'Genia': 'F',
	'Gótica': 'F',
	'Sencillo': 'M',
	'Hawaiano': 'M',
	'Ganadero': 'M',
	'Gótico': 'M'
};

// formats a date and time
function formatDateTime(dateStr) {
	var months = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
	var date = new Date(dateStr);
	var month = date.getMonth();
	var day = date.getDate().toString().padStart(2, '0');
	var hour = (date.getHours() < 12) ? date.getHours() : date.getHours() - 12;
	var minutes = date.getMinutes();
	var amOrPm = (date.getHours() < 12) ? "am" : "pm";
	return day + ' de ' + months[month] + ' a las ' + hour + ':' + minutes + amOrPm;
}

function getAvatar(avatar, serviceImgPath, size) {
	var index = Object.keys(avatars).indexOf(avatar);
	var fullsize = size * 7;
	var x = index % 7 * size;
	var y = Math.floor(index / 7) * size;
	return "background-image: url(" + serviceImgPath + "/avatars.png);" + "background-size: " + fullsize + "px " + fullsize + "px;" + "background-position: -" + x + "px -" + y + "px;";
}

function _typeof(obj) {
	if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
		_typeof = function _typeof(obj) {
			return typeof obj;
		};
	} else {
		_typeof = function _typeof(obj) {
			return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
		};
	}
	return _typeof(obj);
}

if (!Object.keys) {
	Object.keys = function () {
		'use strict';

		var hasOwnProperty = Object.prototype.hasOwnProperty,
			hasDontEnumBug = !{
				toString: null
			}.propertyIsEnumerable('toString'),
			dontEnums = ['toString', 'toLocaleString', 'valueOf', 'hasOwnProperty', 'isPrototypeOf', 'propertyIsEnumerable', 'constructor'],
			dontEnumsLength = dontEnums.length;
		return function (obj) {
			if (_typeof(obj) !== 'object' && (typeof obj !== 'function' || obj === null)) {
				throw new TypeError('Object.keys called on non-object');
			}

			var result = [],
				prop,
				i;

			for (prop in obj) {
				if (hasOwnProperty.call(obj, prop)) {
					result.push(prop);
				}
			}

			if (hasDontEnumBug) {
				for (i = 0; i < dontEnumsLength; i++) {
					if (hasOwnProperty.call(obj, dontEnums[i])) {
						result.push(dontEnums[i]);
					}
				}
			}

			return result;
		};
	}();
}
