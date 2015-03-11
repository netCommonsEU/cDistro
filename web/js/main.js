$( document ).ready(function() {
	window.setTimeout(function() { $(".flash").alert('close'); }, 2000);
});

function node2color(divnode,value){
	values = [ {'value':0, 'bgcolor': '#00BF96', 'color':'#000'},
						{'value':0.100, 'bgcolor': '#19A98A', 'color':'#000'},
					  {'value':0.200, 'bgcolor': '#32947F', 'color':'#000'},
						{'value':0.300, 'bgcolor': '#4C7F74', 'color':'#fff'},
						{'value':0.400, 'bgcolor': '#656A69', 'color':'#fff'},
						{'value':0.500, 'bgcolor': '#7F545D', 'color':'#fff'},
						{'value':0.600, 'bgcolor': '#983F52', 'color':'#fff'},
						{'value':0.700, 'bgcolor': '#B22A47', 'color':'#000'},
						{'value':0.800, 'bgcolor': '#CB153C', 'color':'#000'},
						{'value':1, 'color': '#E50031'}
					 ];

	for(x=0; x < values.length; x++){
		v=values[x];
		if (v.value >= value) {
			$(divnode+" td").css({
		 		'background-color' : v.bgcolor,
				'color' :  v.color
			});
			$(divnode+" td.scan").text(100 - parseInt(value*100));
			break;
		}
	}
}

function SQoS(page){

	$('#tableSerf').hide();
	$('#tableSerfAjax').show();
	tservice.destroy();

	$.getJSON(page,function(data){
			$.each( data, function( key, val ) {
					node2color(".node-"+val.node, val.acktime);
			});
			tservice = $('.table-data').DataTable( { "language": { "url": "/lang/"+LANG+".table.json"} });
			$('#tableSerfAjax').hide();
			$('#tableSerf').show();
	});



}
