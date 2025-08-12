var FormFileUpload = function () {


    return {
        //main function to initiate the module
        init: function () {


		plupload.addI18n({
			'Select files' : 'Selecione os arquivos',
			'Add files to the upload queue and click the start button.' : 'Adicione arquivos para fila de upload e clique no botão Iniciar',
			'Filename' : 'Nome do arquivo',
			'Status' : 'Status',
			'Size' : 'Tamanho',
			'Add files' : 'Adicionar arquivos',
			'Start upload':'Salvar Arquivos',
			'Stop current upload' : 'Parar processo atual',
			'Start uploading queue' : 'Iniciar processo de upload',
			'Drag files here.' : 'Arraste e solte os arquivos aqui'
		});

		$(".plupload").each(function(){
			var $el = $(this);
			$el.pluploadQueue({
				runtimes : 'html5,gears,flash,silverlight,browserplus',
				url : './lib/upload_logo_pdv.php',
				max_file_size : '5mb',
				chunk_size : '1mb',
				unique_names : true,
				multi_selection: true,
				resize : {width : 1200, height : 1200, quality : 90},
				flash_swf_url : './assets/plugins/plupload/plupload.flash.swf',
				silverlight_xap_url : './assets/plugins/plupload/plupload.silverlight.xap'
			});
			$(".plupload_header").remove();
			var upload = $el.pluploadQueue();
			$(".plupload_progress_container").addClass("progress").addClass('progress-striped');
			$(".plupload_progress_bar").addClass("bar");
			$(".plupload_button").each(function(){
				if($(this).hasClass("plupload_add")){
					$(this).attr("class", 'btn pl_add btn-primary').html("<i class='icon-plus'></i> "+$(this).html());
				} else {
					$(this).attr("class", 'btn pl_start btn-success').html("<i class='icon-cloud-upload'></i> "+$(this).html());
				}
			});
			upload.bind("StateChanged", function(up){
				if (upload.files.length === (upload.total.uploaded + upload.total.failed)) {
					$.bootstrapGrowl("Aguarde a mensagem de retorno!<br/>O arquivo esta sendo processado.", {
						ele: "body",
						type: "warning", // (null, 'info', 'danger', 'success', 'warning')
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						stackup_spacing: 10
					});

					var str = $("#admin_form").serialize();
					jQuery.ajax({
						type: 'post',
						url: 'controller.php',
						data: str,
						success: function( data )
						{
							var response = data.split("#");
							$.bootstrapGrowl(response[0], {
								ele: "body",
								type: response[1],
								offset: {
									from: "top",
									amount: 50
								},
								align: "center",
								width: "auto",
								stackup_spacing: 10
							});
							if(response[2] == "1")
								setTimeout(function(){
									window.location.href=response[3];
								}, 1000);
						}
					});
				}
			});
		});
        }

    };

}();