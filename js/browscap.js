
function browscap(iniFile) {
	var parse=function(fileContents) {
		var current = {}
			, browserArray = []
			, patternIndex = []
		
		fileContents
			.split(/[\r\n]+/)
			.forEach(function(line) {
				// Skip comments and blank lines
				if (/^\s*(;|$)/.test(line)) {
					return
				}
				
				if (line[0] == '[') {
					var pattern = line.slice(1, -1)
					
					// Convert the pattern into a proper regex
					current = {
						__regex__: new RegExp('^'
							+ pattern.replace(/\./g, '\\.')
											 .replace(/\(/g, '\\(')
											 .replace(/\)/g, '\\)')
											 .replace(/\//g, '\\/')
											 .replace(/\-/g, '\\-')
											 .replace(/\*/g, '.*')
											 .replace(/\?/g, '.?')
							+ '$')
					}
					
					browserArray.push(current) // Push new browser object onto array
					patternIndex.push(pattern) // Push pattern onto pattern index
				} else {
					var parts = line.split('=')
						, name = parts[0]
						, value = parts[1]
					
					if (name == 'Browser' && value[0] == '"' && value.slice(-1) == '"') {
						value = value.slice(1, -1)
					}
					
					if (name != 'Parent') {
						current[name] = value
					} else {
						// Copy properties from the parent's entry
						var i = patternIndex.lastIndexOf(value)
						for (var key in browserArray[i]) {
							if (key != '__regex__' && key != 'Parent') {
								current[key] = browserArray[i][key]
							}
						}
					}
				}
			})
		
		return browserArray
	},
	process = function(browsers,userAgent,func) {
		// Test user agent against each browser regex
		for (var i = 0; i < browsers.length; i++) {
			if (browsers[i].__regex__.test(userAgent)) {
				func(browsers[i]);
				return;
			}
		}
	},
	browsers = [];

	this['getBrowser']=function(userAgent,func) {
		if (!browsers.length) {
			$.ajax({
	 		 url: iniFile,
	 		 type: 'GET',
				dataType:'text',
	 		 success: function(data){
					browsers=parse(data);
					process(browsers,userAgent,func);
				}
	 	 });
		} else {
			process(browsers,userAgent,func);
		}
	}
}

