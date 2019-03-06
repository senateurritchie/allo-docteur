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