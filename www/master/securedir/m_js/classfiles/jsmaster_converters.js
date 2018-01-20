/**
 *
 * CONTAINS ALL CONVERSION FUNCTIONS
 * @returns
 */
function JS_CONVERTER(){};

/**
 *
 * CONVERTS XML OBJECT TO JSON OBJECT
 * @param xml THE XML OBJECT
 * @returns {} THE JSON OBJECT
 */
JS_CONVERTER.prototype.xmltojson=function(xml){
	var obj = {};
	var conv_obj=new JS_CONVERTER();

	if (xml.nodeType == 1) { // element
		// do attributes
		if (xml.attributes.length > 0) {
		obj["@attributes"] = {};
			for (var j = 0; j < xml.attributes.length; j++) {
				var attribute = xml.attributes.item(j);
				obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
			}
		}
	} else if (xml.nodeType == 3) { // text
		obj = xml.nodeValue;
	};

	// do children
	if (xml.hasChildNodes()) {
		for(var i = 0; i < xml.childNodes.length; i++) {
			var item = xml.childNodes.item(i);
			var nodeName = item.nodeName;
			if (typeof(obj[nodeName]) == "undefined") {
				obj[nodeName] = conv_obj.xmltojson(item);
			} else {
				if (typeof(obj[nodeName].push) == "undefined") {
					var old = obj[nodeName];
					obj[nodeName] = [];
					obj[nodeName].push(old);
				};
				obj[nodeName].push(conv_obj.xmltojson(item));
			}
		}
	};
	return obj;
};

/**
 *
 * CONVERTS JSON OBJECT TO XML STRING
 * @param json THE JSON OBJECT
 * @returns THE XML STRING
 */
JS_CONVERTER.prototype.jsontoxml=function(json){
	o=json;
	if (typeof o == 'object' && o.constructor == Object && len(o) == 1) {
		for (var a in o) {
			return toXML(a, o[a]);
		}
	} else {
	};
	function len(o) {
		var n = 0;
		for (var a in o) {
			n++;
		};
		return n;
	};
	
	function toXML(tag, o) {
		var doc = '<' + tag;
		if (typeof o === 'undefined' || o === null) {
			doc += '/>';
			return doc;
		};
		if (typeof o !== 'object') {
			doc += '>' + safeXMLValue(o) + '</' + tag + '>';
			return doc;
		};
		if (o.constructor == Object) {
			for (var a in o) {
				if (a.charAt(0) == '@') {
					if (typeof o[a] !== 'object') {
						doc += ' ' + a.substring(1) + '="' + o[a] + '"';
						delete o[a];
					} else {
						throw new Error((typeof o[a])
								+ ' being attribute is not supported.');
					}
				}
			};
			if (len(o) === 0) {
				doc += '/>';
				return doc;
			} else {
				doc += '>';
			};
			if (typeof o['#text'] !== 'undefined') {
				if (typeof o['#text'] !== 'object') {
					doc += o['#text'];
					delete o['#text'];
				} else {
					throw new Error((typeof o['#text'])
							+ ' being #text is not supported.');
				};
			};
			for (var b in o) {
				if (o[b].constructor == Array) {
					for (var i = 0; i < o[b].length; i++) {
						if (typeof o[b][i] !== 'object'
								|| o[b][i].constructor == Object) {
							doc += toXML(b, o[b][i]);
						} else {
							throw new Error((typeof o[b][i])
									+ ' is not supported.');
						}
					}
				} else if (o[b].constructor == Object
						|| typeof o[b] !== 'object') {
					doc += toXML(b, o[b]);
				} else {
					throw new Error((typeof o[b]) + ' is not supported.');
				}
			};
			doc += '</' + tag + '>';
			return doc;
		}
	};
	function safeXMLValue(value) {
		var s = value.toString();
		s = s.replace('/\&/g', '&amp;');
		s = s.replace('/\"/g', '&quot;');
		s = s.replace('/</g', '&lt;');
		s = s.replace('/>/g', '&gt;');
		return s;
	}
};