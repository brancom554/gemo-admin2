$(function() {
	'use strict';

    $.ajax({
        type: "POST",
        url: "/dashboard/camembertChart",
        success:function(datas) { 
            const v = JSON.parse(datas)
            console.log(v)
	
	new Morris.Donut({
		element: 'morrisDonut2',
		data: [{
			label: 'TELEPHONIQUES',
			value: v[0]
		}, {
			label: 'FINANCIERS',
			value: v[1]
		}],
		labelColor: '#77778e',
		colors: ['#00A8F3', '#FE7701'],
		resize: true,
	});

}})
});