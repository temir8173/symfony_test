<?php
/**
 * Faster tables filter plugin
 * ===========================
 * Useful when there's way too many tables than it shoud be and Adminer Tables Filter is slow
 *
 * @author Martin Macko, https://github.com/linkedlist
 * @license http://http://opensource.org/licenses/MIT, The MIT License (MIT)
 *
 * Modified 201802 - updated for Adminer 4.6.0 compatibility
 */
class FasterTablesFilter {

	function tablesPrint($tables) { ?>

  <p class="jsonly"><input id="filter-field">
  <style>
      #filter-field { width: 100%; }
      .select-text {
        margin-right: 5px;
      }
  </style>
  <p id='tables'></p>
  <script<?php echo nonce(); ?>>
	function readCookie(name) {
		name = name.replace(/([.*+?^=!:${}()|[\]\/\\])/g, '\\$1');
		var regex = new RegExp('(?:^|;)\\s?' + name + '=(.*?)(?:;|$)','i'),
			match = document.cookie.match(regex);
		return match && unescape(match[1]);
	}
	var filterf = function () {
		var liProto = document.createElement('li');
		var space = document.createTextNode('\u00A0')
		var aProto = document.createElement('a');
		var tableList = document.getElementById("tables");
		function appendTables() {
			var fragment = document.createDocumentFragment();
			var item;
			for (var i = 0, len = tempTables.length; i < len; i++) {
				item = tempTables[i];
				var li = liProto.cloneNode();
				var aSelect = aProto.cloneNode();
				aSelect.href = hMe+"select="+item;
				aSelect.text = langSelect;
				aSelect.className = "select";
				var aName = aProto.cloneNode();
				aName.href = hMe+"table="+item;
				aName.text = item;
				li.appendChild(aSelect);
				li.appendChild(space.cloneNode());
				li.appendChild(aName);
				fragment.appendChild(li);
			}
			tableList.appendChild(fragment);
		}
		var tables = [<?php foreach($tables as $table => $type) { echo "'".urlencode($table) ."'". ",";}?>];
		var tempTables = tables;
		var hMe = "<?php echo h(ME) ?>";
		hMe = hMe.replace(/&amp;/g, '&');
		var langSelect = "<?php echo lang('select');?>";
		var filterCookie = readCookie('tableFilter');
		var filter = document.getElementById("filter-field");
		if(filterCookie !== '') {
			filter.value=filterCookie;
		}

		function swapObject(obj) {
            var ret = {};
            for (var key in obj) {
                ret[obj[key]] = key;
            }
            return ret;
        }

        var replaceToRu = {
            'q': 'й', 'w': 'ц', 'e': 'у', 'r': 'к', 't': 'е', 'y': 'н', 'u': 'г',
            'i': 'ш', 'o': 'щ', 'p': 'з', '[': 'х', ']': 'ъ', 'a': 'ф', 's': 'ы',
            'd': 'в', 'f': 'а', 'g': 'п', 'h': 'р', 'j': 'о', 'k': 'л', 'l': 'д',
            ';': 'ж', "'": 'э', 'z': 'я', 'x': 'ч', 'c': 'с', 'v': 'м', 'b': 'и',
            'n': 'т', 'm': 'ь', ',': 'б', '.': 'ю', '/': '.'
        };
		var replaceToEn = swapObject(replaceToRu);

		function translateSymbols(str, symbols) {
            for (var i = 0; i < str.length; i++) {
                var lowerSym = str[i].toLowerCase();
                if (symbols[lowerSym]) {
                    var replace = '';
                    if (str[i] === lowerSym) {
                        replace = symbols[lowerSym];
                    } else {
                        replace = symbols[lowerSym].toUpperCase();
                    }
                    str = str.replace(str[i], replace);
                }
            }
            return str;
        }

		function filterTableList() {
			document.cookie = "tableFilter="+filter.value
			while(tableList.firstChild) {
				tableList.removeChild(tableList.firstChild);
			}
			tempTables = [];
			var value = filter.value.toLowerCase();
			var translatedValueRu = translateSymbols(value, replaceToRu);
			var translatedValueEn = translateSymbols(value, replaceToEn);
			var item;
			for (var i = 0, len = tables.length; i < len; i++) {
				item = tables[i];
				var lower = item.toLowerCase();
				if(
                    lower.indexOf(value) !== -1
                    || lower.indexOf(translatedValueRu) !== -1
                    || lower.indexOf(translatedValueEn) !== -1
                ) {
					tempTables.push(item);
				}
			}
			appendTables();
		};
		filter.oninput = function(event) {
			filterTableList();
		}
		filterTableList();
	}

	// фокусировка на поле ввода по двойному шифту
    var dblShiftKey = 0;
	var body = document.getElementsByTagName('body')[0];
	var focusFilter = function () {
        var input = document.getElementById('filter-field');
        input.focus();
        input.select();
    };
    body.addEventListener('keyup', function(event) {
        if (event.keyCode === 16) {
            if (dblShiftKey !== 0) {
                focusFilter();
            } else {
                dblShiftKey = setTimeout(function () {
                    dblShiftKey = 0;
                }, 300);
            }
        }
    });
	window.onload=filterf;
</script>
<?php return true;}}

return new FasterTablesFilter();
