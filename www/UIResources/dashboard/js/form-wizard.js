$(function() {
	'use strict'
	
	$('#wizard1').steps({
		headerTag: 'h3',
		bodyTag: 'section',
		autoFocus: true,
		titleTemplate: '<span class="number">#index#<\/span> <span class="title">#title#<\/span>',
		onStepChanged: function (event, currentIndex, priorIndex) { 
			if (currentIndex === 3) { //if last step
				//remove default #finish button
				$('#wizard1').find('a[href="#finish"]').remove(); 
				//append a submit type button
				$('#wizard1 .actions li:last-child').append('<button type="submit" name="soumettre" id="submit" class="btn btn-primary my-2 btn-icon-text">CREER LA LICENCE</button>');
			 }else{
				$('#wizard1').find('#submit').remove(); 
			 }
		}, 
		onFinished: function (event, currentIndex) {
			//$(this).submit();
			console.log(currentIndex)
		  },

		  labels: {
			cancel: "Cancel",
			current: "current step:",
			pagination: "Pagination",
			finish: "Finish",
			next: "Suivant",
			previous: "Précédent",
			loading: "Loading ..."
		}
	});
	
	$('#wizard3').steps({
		headerTag: 'h3',
		bodyTag: 'section',
		autoFocus: true,
		titleTemplate: '<span class="number">#index#<\/span> <span class="title">#title#<\/span>',
		stepsOrientation: 1
	});
	
	//accordion-wizard
	var options = {
		mode: 'wizard',
		autoButtonsNextClass: 'btn btn-primary float-right',
		autoButtonsPrevClass: 'btn btn-secondary',
		stepNumberClass: 'badge badge-primary mr-1',
		onSubmit: function() {
		  alert('Form submitted!');
		  return true;
		}
	}
	$( "#form" ).accWizard(options);
});