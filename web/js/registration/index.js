$(document).ready(function($){
    var nsp = AdminManager;
    var view = AdminManager.container.get('RegistrationView');
    var repository = AdminManager.container.get('RegistrationRepository');

    view.controller();

   
    repository.subscribe(event=>{

		
		
	});


	view.subscribe(event=>{

    	
        
    });

});