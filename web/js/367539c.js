var AdminManager = AdminManager || {};

(function(nsp){

	// namespace initial de l'espace d'administration
	nsp.container;
	nsp.fn = {};
	nsp.plugins = {};
	nsp.utilis = {
	  	merge:function(target={},source={}){
	   		for(let i in source){
	    		target[i] = source[i];
	   		}
	   		return target;
	  	}
	};

	nsp.initialize = function(){

		nsp.container.set('EntityManager',nsp.EntityManager);
		nsp.container.set('EventDispatcher',nsp.EventDispatcher);
		nsp.container.set('Scroller',nsp.Scroller);

		for(var i in nsp.fn){
			nsp.container.set(i,nsp.fn[i]);
		}
	}

	/**
	* Base Objet des events 
	* @return {Event}              
	*/
	nsp.Event = (function(){
		function Event(type,params){
	   		this.type = type;
	   		this.params = {};
	   		this.isPropagationStoped = false;
	   		nsp.utilis.merge(this.params,params);
		};

	  	/**
	  	* permet de stopper la propagation d'un événement
	  	*
	  	* @return {null}
	   	*/
	  	Event.prototype.stopPropagation = function(){
	   		this.isPropagationStoped = true;
	  	};

	  	return Event;
	 })();

	/**
	* EventDispatcherSubscriber est le soucripteur d'evenement
	* @return {EventDispatcherSubscriber}              
	*/
	nsp.EventDispatcherSubscriber = (function(){
		function EventDispatcherSubscriber(dispatcher){
	   		this.dispatcher = dispatcher;
	  	};

	  	/**
	   	* annulation de souscription au gestionnaire d'évenements
	   	* 
	   	* @return {null}
	   	*/
	  	EventDispatcherSubscriber.prototype.unsubscribe = function(){
	   		this.dispatcher.remove(this);
	  	};
	  
	  	return EventDispatcherSubscriber;
	})();

	/**
	* EventDispatcher est le gestionnaire d'evenement
	* @return {EventDispatcher}              
	*/
	nsp.EventDispatcher = (function(){
	  	function EventDispatcher(){
	   		this.$_data = new Map();
	  	};

	  	/**
	    * souscription au gestionnaire d'évenements
	    *
	    * @param  {Function} cbk callback à appeler à chaque nouvel évenement
	    * @return {null}
	   	*/
	  	EventDispatcher.prototype.subscribe = function(cbk){
	   		var subscriber = new nsp.EventDispatcherSubscriber(this);
	   		this.$_data.set(subscriber,cbk);
	   		return subscriber;
	  	};
	  
	  	/**
	    * supprime un souscripteur d'évenement
	    *
	    * @param  {EventDispatcherSubscriber} subscriber
	    * @return {null}         
	   	*/
	  	EventDispatcher.prototype.remove = function(subscriber){
	   		this.$_data.delete(subscriber);
	  	};

	  	/**
	    * emetteur d'évenements
	    *
	    * @param  {Event} event
	    * @return {null}         
	   	*/
	  	EventDispatcher.prototype.emit = function(event){
	   		for(let [i,cbk] of this.$_data){
	    		cbk.call(null,event);
	    		if(event.isPropagationStoped) break;
	   		}
	  	};
	  	return EventDispatcher;
	})();


	/**
	* EntityManager 
	* @return {EntityManager}              
	*/
	nsp.EntityManager = (function(){

		function EntityManager(params){
			nsp.EventDispatcher.call(this);

	   		this.params = {
	   			endpoint:null
	   		};
	   		this.xhr;
	   		nsp.utilis.merge(this.params,params);
		};

		Object.assign(EntityManager.prototype,nsp.EventDispatcher.prototype);

	  	/**
	  	* permet d'initialiser les repositories
	  	*
	  	* @return {null}
	   	*/
	  	EntityManager.prototype.init = function(params){
	  		nsp.utilis.merge(this.params,params);
	  	};

	  
	  	EntityManager.prototype.persist = function(params){

	  	};

	  	EntityManager.prototype.remove = function(params){

	  	};

	  	EntityManager.prototype.request = function(options){
	  		options = options || {};

	  		var params = {
	  			url: this.endpoint,
	  			dataType:'json',
	  			method:'POST',
	  			success:function(data){
					resolve(data);
				},
				error:function(xhr,textStatus,errorThrown){
					reject(textStatus);
				}
	  		};

	  		for(var i in options){
	  			if(typeof params[i] == "function") continue;
	  			params[i] = options[i];
	  		}

	  		return $.ajax(params);
		};

	  	return EntityManager;
	})();


	/**
	* Base Objet les repositories 
	* @return {AbstractRepository}              
	*/
	nsp.AbstractRepository = (function(){

		function AbstractRepository(type,params){
			nsp.EventDispatcher.call(this);

	   		this.params = {
	   			endpoint:null
	   		};
	   		this.current;
	   		this.xhr;
	   		nsp.utilis.merge(this.params,params);
		};

		Object.assign(AbstractRepository.prototype,nsp.EventDispatcher.prototype);

	  	/**
	  	* permet d'initialiser les repositories
	  	*
	  	* @return {null}
	   	*/
	  	AbstractRepository.prototype.init = function(params){
	  		nsp.utilis.merge(this.params,params);
	  	};


	  	AbstractRepository.prototype.setCurrent = function(user){
	  		this.current = user;
	  	};

	  	/**	
	  	* les methodes abstraites
	  	*/
	  	AbstractRepository.prototype.find = function(id){};
	  	AbstractRepository.prototype.findAll = function(){};
	  	AbstractRepository.prototype.findBy = function(params,orderBy,limit,offset){};
	  	AbstractRepository.prototype.findOneBy = function(params){};

	  	/**
	  	* les methodes public
	  	*/
	  	AbstractRepository.prototype.request = function(options){
	  		options = options || {};

	  		var params = {
	  			url: this.endpoint,
	  			dataType:'json',
	  			method:'GET',
	  			
	  		};

	  		for(var i in options){
	  			params[i] = options[i];
	  		}

	  		return $.ajax(params);
		};

	  	return AbstractRepository;
	})();

	nsp.Repository = (function(){

		function Repository(params){
			nsp.AbstractRepository.call(this,params);
		};

		Object.assign(Repository.prototype, nsp.AbstractRepository.prototype);

		Repository.prototype.find = function(id){

			return this.findBy({
				data:{
					id:id
				}
			});
		};
	  	Repository.prototype.findAll = function(){
	  		return this.findBy();
	  	};
	  	Repository.prototype.findBy = function(params = {},orderBy = {},limit,offset){
	  		
	  		if(!params.hasOwnProperty('data')){
	  			params.data = {};
	  		}

	  		if(limit){
	  			params["data"].limit = limit;
	  			params["data"].offset = offset;
	  		}

	  		return new Promise((resolve,reject)=>{
	  			this.request(params)
		  		.done(data=>{
		  			resolve(data);
		  		})
		  		.fail(msg=>{
		  			console.log(msg)
		  			reject(msg);
		  		});
	  		});
	  	};
	  	Repository.prototype.findOneBy = function(params){
	  		return this.findBy(params,null,1,0);
	  	};

		return Repository;
	})();


	/**	
	* Base class pour les vues
	*/
	nsp.View = (function(){

		function View(){
			nsp.EventDispatcher.call(this);
			this._data = new Map();
			this.params = {
				$tpl:{}
			};
		};

		Object.assign(View.prototype,nsp.EventDispatcher.prototype);

		View.prototype.vars = function(params){
	  		nsp.utilis.merge(this.params,params);
	  		return this;
	  	};

	  	/**
	  	* methode abstraite
	  	* demarre la logic de la vue
	  	*/
	  	View.prototype.controller = function(){};

		View.prototype.render = function(view,model,options){
			return Mustache.render(view,model,options);
		}
		return View;
	})();

	/**	
	* le container de service
	*/
	nsp.Container = (function(){

		function Container(){
			nsp.EventDispatcher.call(this);
			this._data = new Map();
		};

		Object.assign(Container.prototype,nsp.EventDispatcher.prototype);

		Container.prototype.has = function(key){
			return this._data.has(key);
		}

		Container.prototype.set = function(name,_constructor){
			this._data.set(name,new _constructor());
		}

		Container.prototype.get = function(value){
			return this._data.get(value);
		}

		return Container;
	})();


	/**
	* evenement de filereader
	*/
	nsp.FileReaderEvent = (function(){
		function FileReaderEvent(params){
			nsp.Event.call(this,'upload',params);
		};
		Object.assign(FileReaderEvent.prototype, nsp.Event.prototype);
		return FileReaderEvent;
	})();

	/**
	* evenement de traduction
	*/
	nsp.TranslationEvent = (function(){
		function TranslationEvent(params){
			nsp.Event.call(this,'translate',params);
		};
		Object.assign(TranslationEvent.prototype, nsp.Event.prototype);
		return TranslationEvent;
	})();

	/**
	* evenement de upload
	*/
	nsp.UploadEvent = (function(){
		function UploadEvent(params){
			nsp.Event.call(this,'upload',params);
		};
		Object.assign(UploadEvent.prototype, nsp.Event.prototype);
		return UploadEvent;
	})();

	/**
	* evenement de scrolling dynamique
	*/
	nsp.ScrollerEvent = (function(){
		function ScrollerEvent(params){
			nsp.Event.call(this,'scroll',params);
		};
		Object.assign(ScrollerEvent.prototype, nsp.Event.prototype);
		return ScrollerEvent;
	})();

	/**
	* evenement de scrolling dynamique
	*/
	nsp.InfiniteScrollEvent = (function(){
		function InfiniteScrollEvent(params){
			nsp.Event.call(this,'infinite-scroll',params);
		};
		Object.assign(InfiniteScrollEvent.prototype, nsp.Event.prototype);
		return InfiniteScrollEvent;
	})();

	/**	
	* l'infinite scrolling
	*/
	nsp.Scroller = (function(){

		function Scroller(){
			nsp.EventDispatcher.call(this);
		};

		Object.assign(Scroller.prototype,nsp.EventDispatcher.prototype);

		Scroller.prototype.forWindow = function(key){

			var doc = $(document);
			var win = $(window);
			var oldPos = win.scrollTop();

			win.on({
				scroll:e=>{
					var tHeight = doc.height() - win.height();
					var scrollTop = win.scrollTop();
					var pos = tHeight-scrollTop;
					var percent = (pos*100)/tHeight;
					var dir = scrollTop > oldPos ? "ttb":"btt";
					oldPos = scrollTop;

					var ev = {
						scrollTop:scrollTop,
						pos:pos,
						percent:percent,
						dir:dir
					};
					this.emit(new nsp.ScrollerEvent(nsp.utilis.merge({state:"scrolling"},ev)));

					if (tHeight == scrollTop) {
						this.emit(new nsp.ScrollerEvent(nsp.utilis.merge({state:"end"},ev)));
					}
					else if (scrollTop == 0) {
						this.emit(new nsp.ScrollerEvent(nsp.utilis.merge({state:"start"},ev)));
					}

				}
			});
		}

		return Scroller;
	})();

	(function(){
		nsp.container = new nsp.Container();
	})();

})(AdminManager);


$(document).ready(function($){
	AdminManager.initialize();
});
var AdminManager = AdminManager || {};

(function(nsp){

    nsp.fn.RegistrationRepository = (function(){

        function RegistrationRepository(params){
            nsp.Repository.call(this,params);
        };

        Object.assign(RegistrationRepository.prototype, nsp.Repository.prototype);

        RegistrationRepository.prototype.downloadListRequest = function(event){

            return new Promise((resolve,reject)=>{
                  this.request({
                      url:`/${event.params.langId}/programmes/${event.params.movieId}/downloads/${event.params.downloadType}`,
                      method:"GET",
                      dataType:"text"
                  })
                  .done(data=>{
                      resolve(data);
                  })
                  .fail(msg=>{
                      reject(msg);
                  });
              });
        };
        return RegistrationRepository;
    })();


	nsp.fn.RegistrationView = (function(){
		function RegistrationView(params){
			nsp.View.call(this,params);

			this.vars({
                renderAs:1,
                copyDefaultImage:null,
                uploadExt:['jpg','jpeg','png'],
                $tpl:{  }
            });
		};

		Object.assign(RegistrationView.prototype, nsp.View.prototype);

		RegistrationView.prototype.controller = function(){


            $('body').on("click",".has-collection .collection-add",
                e=>{
                    e.preventDefault();

                    var obj = $(e.target);
                    var parent = obj.parent();
                    var parent_collection = obj.parents(".has-collection:first");

                    var parent_prototype  = parent_collection.find('[data-prototype]');

                    var tpl = parent_prototype.data('prototype');
                    var index = parent_prototype.find(" > div.form-group ").length;

                    tpl = tpl.replace(/__name__/g,index);

                    if(parent_collection.hasClass('has-collection-multiple-fields')){
                        li = $(tpl);
                    }
                    else{
                        a.find('button').on({
                            click:e=>{
                                li.remove();
                            }
                        });
                    }

                    li.find('label').remove();
                    //li.insertBefore(parent.find('a'));
                    parent_prototype.append(li);

            });

            this.saveDefaultImgs();

            $('body').on("click",".has-collection-multiple-fields button.delete",
                e=>{
                e.preventDefault();

                var obj = $(e.target);
                obj.parents('div[id]:first').parent().remove();
            });



            $('body').on('change','.dropper input[type=file]',(e)=> {
                  this.previewImage(e.target.files,e);
            });

            $('body').on('click','.dropper .trigger-file',(e)=> {
                  $(e.target).parents(".dropper").find('input[type=file]').click();
            });


            $("body").on("click",".dropper .reset-file",(e)=> {
                e.preventDefault();
                var parent = $(e.target).parents('.dropper');
                var img = parent.find('.dropper-target');
                var input  = parent.find('input[type=file]');
                var files = input.get()[0].files;

                if(files.length){
                    input.val('');

                }

              
                img.css('background-image',this.copyDefaultImage);        
            });
			

            document.addEventListener("dragenter",e=>{
                e.preventDefault();

                if(~e.dataTransfer.types.indexOf('Files')) {
                    $(document.body).addClass('dragenter');
                }
            });
            document.addEventListener("dragover",e=>{
                e.preventDefault();

                if(~e.dataTransfer.types.indexOf('Files')){
                    $(document.body).addClass('dragenter');
                }
            });
            document.addEventListener("dragleave",e=>{
                e.preventDefault();

                if(~e.dataTransfer.types.indexOf('Files')){
                    if(e.currentTarget == document){
                        $(document.body).removeClass('dragenter');
                    }
                }
            });

            document.addEventListener("drop",e=>{
                e.preventDefault();
                   $(document.body).removeClass('dragenter');
            });

            // les vignettes du programme
            $("body").on('drop','.dropper',e=>{
                e.preventDefault();

                $(document.body).removeClass('dragenter');                
                var files = e.originalEvent.dataTransfer.files;
                if(!files.length) return;

                var file = files[0];
                var ext = file.name.split('.');
                ext = ext.slice(-1);
                ext = ext[0];
                ext = ext.toLowerCase();

                if (~this.params.uploadExt.indexOf(ext)){
                    this.previewImage(files,e);
                }
            });

            this.subscribe(event=>{

                
            });

			return this;
		}


        RegistrationView.prototype.saveDefaultImgs = function(){
            $('.dropper .dropper-target').each((i,el)=>{
               this.copyDefaultImage = $(el).css('background-image');
            });
        }


        RegistrationView.prototype.previewImage = function(files, event){
            if(!files.length) return;

            var file = files[0];
            var filenames = file.name;

            var ext = file.name.split('.');
            ext = ext.slice(-1);
            ext = ext[0];
            ext = ext.toLowerCase();
            if (this.params.uploadExt.indexOf(ext) == -1){
                return;
            }

            var reader = new FileReader();

            reader.addEventListener('load', ()=> {
                var img = $(event.target).parents('.dropper').find('.dropper-target');
                img.css('background-image',`url(${reader.result})`);
                this.emit(new nsp.FileReaderEvent({state:"load",files:files,target:img}));
            });

            reader.addEventListener('error', ()=> {
                this.emit(new nsp.FileReaderEvent({state:"error",files:files}));
            });

            reader.addEventListener('loadend', ()=> {
                this.emit(new nsp.FileReaderEvent({state:"end",files:files}));
            });

            reader.addEventListener('progress', ()=> {
                this.emit(new nsp.FileReaderEvent({state:"progress",files:files}));
            });

            reader.addEventListener('abort', ()=> {
                this.emit(new nsp.FileReaderEvent({state:"abort",files:files}));
            });

            this.emit(new nsp.FileReaderEvent({state:"start",files:files}));

            reader.readAsDataURL(file);
        }

		return RegistrationView;
	})();

})(AdminManager);
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