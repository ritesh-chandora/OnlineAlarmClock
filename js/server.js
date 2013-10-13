// Updates things on the server.
var server = {
	// Send changes to the server
	apply: function(data, callback) {
		if(data.length == 0) return;
		var callback = callback;
		$.ajax({
			url: "change.php",
			dataType: 'json',
			// type: 'POST',
			type: 'GET',
			data: {json:JSON.stringify(data)},
			success: function(response) {
			if (!response.success)
				{
				if(data[0]['type'] == 'ping') return;	
					dialog('Server error. Your changes may not be saved.\n\n' + response.error, 'error');
					console.log(response);
					console.dir(data);
					console.log(JSON.stringify(data));
				}
				if (callback) callback(response);
			},
			error: function(response) {
				if(data[0]['type'] == 'ping') return;	
				dialog('Server error. Your changes may not be saved.\n\n', 'error');
				console.log(response);
				console.dir(data);
				console.log(JSON.stringify(data));
			}
		});
	},
	
};