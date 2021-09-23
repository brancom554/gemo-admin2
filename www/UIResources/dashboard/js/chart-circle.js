/* Examples */
(function($) {
  
  // $.ajax({
  //   type: "POST",
  //   url: "/dashboard/camembertChart",
  //   success:function(chart2data) { 
        
        // const v = JSON.parse(chart2data)
        // console.log(chart2data);

  var c4 = $('.forth.circle');

  c4.circleProgress({
    startAngle: -Math.PI / 2 * 3,
    value: 0.5,
    lineCap: 'round',
	emptyFill: '#FE7701',
    fill: {color: '#00A8F3'},
	lineCap: 'round'
  });

// }})
  setTimeout(function() { c4.circleProgress('value', 0.7); }, 1000);
  setTimeout(function() { c4.circleProgress('value', 1.0); }, 1100);
  setTimeout(function() { c4.circleProgress('value', 0.5); }, 2100);


})(jQuery);