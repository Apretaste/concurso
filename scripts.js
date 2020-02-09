$(document).ready(function () {
	$('.tabs').tabs();
});

var colors = {
	'azul': '#99F9FF',
	'verde': '#9ADB05',
	'rojo': '#FF415B',
	'morado': '#58235E',
	'naranja': '#F38200',
	'amarillo': '#FFE600'
};

var selectedColor;

var avatars = {
	apretin: {caption: "Apretín", gender: 'M'},
	apretina: {caption: "Apretina", gender: 'F'},
	artista: {caption: "Artista", gender: 'M'},
	bandido: {caption: "Bandido", gender: 'M'},
	belleza: {caption: "Belleza", gender: 'F'},
	chica: {caption: "Chica", gender: 'F'},
	coqueta: {caption: "Coqueta", gender: 'F'},
	cresta: {caption: "Cresta", gender: 'M'},
	deportiva: {caption: "Deportiva", gender: 'F'},
	dulce: {caption: "Dulce", gender: 'F'},
	emo: {caption: "Emo", gender: 'M'},
	oculto: {caption: "Oculto", gender: 'M'},
	extranna: {caption: "Extraña", gender: 'F'},
	fabulosa: {caption: "Fabulosa", gender: 'F'},
	fuerte: {caption: "Fuerte", gender: 'M'},
	ganadero: {caption: "Ganadero", gender: 'M'},
	geek: {caption: "Geek", gender: 'F'},
	genia: {caption: "Genia", gender: 'F'},
	gotica: {caption: "Gótica", gender: 'F'},
	gotico: {caption: "Gótico", gender: 'M'},
	guapo: {caption: "Guapo", gender: 'M'},
	hawaiano: {caption: "Hawaiano", gender: 'M'},
	hippie: {caption: "Hippie", gender: 'M'},
	hombre: {caption: "Hombre", gender: 'M'},
	atento: {caption: "Atento", gender: 'M'},
	libre: {caption: "Libre", gender: 'F'},
	jefe: {caption: "Jefe", gender: 'M'},
	jugadora: {caption: "Jugadora", gender: 'F'},
	mago: {caption: "Mago", gender: 'M'},
	metalero: {caption: "Metalero", gender: 'M'},
	modelo: {caption: "Modelo", gender: 'F'},
	moderna: {caption: "Moderna", gender: 'F'},
	musico: {caption: "Músico", gender: 'M'},
	nerd: {caption: "Nerd", gender: 'M'},
	punk: {caption: "Punk", gender: 'M'},
	punkie: {caption: "Punkie", gender: 'M'},
	rap: {caption: "Rap", gender: 'M'},
	rapear: {caption: "Rapear", gender: 'M'},
	rapero: {caption: "Rapero", gender: 'M'},
	rock: {caption: "Rock", gender: 'M'},
	rockera: {caption: "Rockera", gender: 'F'},
	rubia: {caption: "Rubia", gender: 'F'},
	rudo: {caption: "Rudo", gender: 'M'},
	sencilla: {caption: "Sencilla", gender: 'F'},
	sencillo: {caption: "Sencillo", gender: 'M'},
	sennor: {caption: "Señor", gender: 'M'},
	sennorita: {caption: "Señorita", gender: 'F'},
	sensei: {caption: "Sensei", gender: 'M'},
	surfista: {caption: "Surfista", gender: 'M'},
	tablista: {caption: "Tablista", gender: 'F'},
	vaquera: {caption: "Vaquera", gender: 'F'}
};

// formats a date and time
function formatDateTime(dateStr) {
	var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
	var date = new Date(dateStr);
	var month = date.getMonth();
	var day = date.getDate().toString().padStart(2, '0');
	var hour = (date.getHours() < 12) ? date.getHours() : date.getHours() - 12;
	var minutes = date.getMinutes();
	var amOrPm = (date.getHours() < 12) ? "am" : "pm";
	return day + ' de ' + months[month] + ' a las ' + hour + ':' + minutes + amOrPm;
}

function getAvatar(avatar, serviceImgPath) {
	return "background-image: url(" + serviceImgPath + "/" + avatar + ".png);";
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
