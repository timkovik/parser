var EOAll;
$(function(){
	var $result = $('#result');
	var $hidden = $('#hidden');
	var $button = $('#button');
	var $loading = $('#loading');
	var $cachename = $('#cachename');

	$loading.hide();
	$button.click(function(){
		$loading.show();
		$result.html('');
		var $link = $('#link').val();
		var $pages = $('#pages').val();
		$.post('parse.php',{link:$link,pages:$pages},function(data){
			var jsondata = JSON.parse(data);
			console.log(jsondata);
			var i = 0;
			EOAll = jsondata.links.length-1;
			var lInterval = setInterval(function(){
				parseItemPage(jsondata.links[i++],i);
				if (i >= EOAll) clearInterval(lInterval);
			},700);
		});
		return false;
	});
function parseItemPage(sublink,num){
	var fullLink = 'http://www.mc.ru' + sublink;
	$.post('parseItem.php',{url:fullLink},function(data){
		var props = JSON.parse(data);
		var str = '';

		console.log(num + '/' + EOAll + ': 	' + fullLink);
		if (props.itemName.length < 1) console.log('Пусто');
		str += props.itemName + ';';
		str += props['Артикул'] + ';';
		str += props['Артикул расширенный'] + ';';
		str += props['Активное сопротивление полюса R, мОм'] + ';';		
		str += props['Время срабатывания расцепителя в зоне КЗ tm, c'] + ';';
		str += props['Высота, мм'] + ';';
		str += props['Глубина, мм'] + ';';
		str += props['Ширина, мм'] + ';';		
		str += props['Ед.измерения'] + ';';
		str += props['Исполнение'] + ';';
		str += props['Климатическое исполнение'] + ';';
		str += props['Код товара'] + ';';
		str += props['Количество модулей DIN'] + ';';
		str += props['Количество силовых полюсов'] + ';';
		str += props['Коэффициент гарантированного несрабатывания, o.e.'] + ';';
		str += props['Коэффициент гарантированного срабатывания, о.е.'] + ';';
		str += props['Кратность уставки расцепителя Km, о.е.'] + ';';
		str += props['Максимальное сечение подключаемого кабеля, мм2'] + ';';
		str += props['Масса, кг'] + ';';
		str += props['Модульный'] + ';';
		str += props['Название раздела'] + ';';
		str += props['Наименование (тип)'] + ';';
		str += props['Наименование в прайсе производителя'] + ';';
		str += props['Наименование в составе НКУ'] + ';';
		str += props['Наличие взрывозащиты'] + ';';
		str += props['Наличие дифференциального расцепителя'] + ';';
		str += props['Наличие кратности тока для времени tm (для функции I2t)'] + ';';
		str += props['Наличие теплового расцепителя'] + ';';
		str += props['Наличие электромагнитного расцепителя'] + ';';
		str += props['Наличие электронного расцепителя'] + ';';
		str += props['Номинальная отключающая способность, кA (AC) (IEC/EN 60898)'] + ';';
		str += props['Номинальное напряжение, В'] + ';';
		str += props['Номинальный ток,А'] + ';';
		str += props['Нормативный документ'] + ';';
		str += props['Описание'] + ';';
		str += props['Предельная отключающая способность, кA'] + ';';
		str += props['Предельная отключающая способность, кA (DC)'] + ';';
		str += props['Производитель'] + ';';
		str += props['Реактивное сопротивление полюса X, мОм'] + ';';
		str += props['Род тока'] + ';';
		str += props['Серия'] + ';';
		str += props['Сертификат'] + ';';
		str += props['Способ задания уставки расцепителя'] + ';';
		str += props['Способ монтажа'] + ';';
		str += props['Степень защиты'] + ';';
		str += props['Страна'] + ';';
		str += props['Сфера применения'] + ';';
		str += props['Тип изделия'] + ';';
		str += props['Тип монтажной рейки'] + ';';
		str += props['Токи уставки расцепителя в зоне перегрузки Ir, А'] + ';';
		str += props['Упаковки'] + ';';
		str += props['Характеристика эл.магнитного расцепителя'] + ';';
		str += props['Электродинамическая стойкость Icm, кА'] + ';';
		str += props['price'] + ';';
		str += 'RUB;';
		str +=  translit(props.itemName) + '_2;';
		str +=  ';'; // Изображение

		str += 'Дополнительное оборудование;';
		str += 'optional_equipment;';
		str += 'Низковольтное оборудование;';
		str += 'nizkovoltnoe_oborudovanie;';

		str += 'Пускатели, контакторы и аксессуары к ним;';
		str += 'puskateli_kontaktory_i_aksessuary_k_nim;';

		str += 'Реле для контакторов;';
		str += 'rele_dlya_kontaktorov;';

		str = str.replace(/undefined/g,'');
		$result.append(str + "<br>");
		var nameInCache = $cachename.val();
		localStorage[nameInCache] += str;		
	});
	if (num >= EOAll) $loading.hide();
}
// Транслит
function translit(str){
	var space = '-'; 
	var text = str;

	var transl = {
		'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh', 
		'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
		'о': 'o', 'п': 'p', 'р': 'r','с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h',
		'ц': 'c', 'ч': 'ch', 'ш': 'sh', 'щ': 'sh','ъ': space, 'ы': 'y', 'ь': space, 'э': 'e', 'ю': 'yu', 'я': 'ya',
		' ': space, '_': space, '`': space, '~': space, '!': space, '@': space,
		'#': space, '$': space, '%': space, '^': space, '&': space, '*': space, 
		'(': space, ')': space,'-': space, '\=': space, '+': space, '[': space, 
		']': space, '\\': space, '|': space, '/': space,'.': space, ',': space,
		'{': space, '}': space, '\'': space, '"': space, ';': space, ':': space,
		'?': space, '<': space, '>': space, '№':space
	}
	var result = '';
	var curent_sim = '';
	for(i=0; i < text.length; i++) {
	    // Если символ найден в массиве то меняем его
	    if(transl[text[i]] != undefined) {
	        if(curent_sim != transl[text[i]] || curent_sim != space){
	            result += transl[text[i]];
	            curent_sim = transl[text[i]];
	        }                                                                            
	    }
	    else {
	        result += text[i];
	        curent_sim = text[i];
	    }                              
	}                      
	result = TrimStr(result);               
	return result;    
}
function TrimStr(s) {
    s = s.replace(/^-/, '');
    return s.replace(/-$/, '');
}


















});