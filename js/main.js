$(function()
{
	
});

function renderTemplate(id, data)
{
	if (renderTemplate.cache == undefined)
		renderTemplate.cache = {};
		
	if (renderTemplate.cache[id] == undefined) {
		renderTemplate.cache[id] = Handlebars.compile($('#' + id).html());	
	}
	
	var template = renderTemplate.cache[id];
	return template(data);
}




