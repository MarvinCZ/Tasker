(function( $, window, undefined ) {
  $.uploader = $.extend( {}, {
    
    addFile: function(id, i, file){
		var template = '<div id="uploader-file' + i + '">' +
		                   file.name + ' <span class="uploader-file-size">(' + $.uploader.humanizeSize(file.size) + ')</span> - Status: <span class="uploader-file-status">Waiting to upload</span>'+
		                   '<div class="progress progress-striped active">'+
		                       '<div class="progress-bar" role="progressbar" style="width: 0%;">'+
		                           '<span class="sr-only">0% Hotovo</span>'+
		                       '</div>'+
		                   '</div>'+
		               '</div>';
		var i = $(id).attr('file-counter');

		if (!i){
			
			i = 0;
		}
		
		i++;
		
		$(id).attr('file-counter', i);
		console.log($(id));
		
		$(id).append(template);
	},
	
	updateFileStatus: function(i, status, message){
		$('#uploader-file' + i).find('span.uploader-file-status').html(message).addClass('uploader-file-status-' + status);
	},
	
	reformat: function(i, html){
		$('#uploader-file' + i).remove();
		$('#files-box').append(html);
	},
	
	updateFileProgress: function(i, percent){
		$('#uploader-file' + i).find('div.progress-bar').width(percent);
		
		$('#uploader-file' + i).find('span.sr-only').html(percent + ' Hotovo');
	},
	
	humanizeSize: function(size) {
      var i = Math.floor( Math.log(size) / Math.log(1024) );
      return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
    }

  }, $.danidemo);
})(jQuery, this);
$(document).ready(function(){
      $('#drag-and-drop-zone').dmUploader({
        url: window.location.pathname,
        dataType: 'json',
        allowedTypes: '*',
        onBeforeUpload: function(id){

          $.uploader.updateFileStatus(id, 'default', 'Nahravání...');
        },
        onNewFile: function(id, file){
          $.uploader.addFile('#files-box', id, file);
        },
        onComplete: function(){
        },
        onUploadProgress: function(id, percent){
          var percentStr = percent + '%';

          $.uploader.updateFileProgress(id, percentStr);
        },
        onUploadSuccess: function(id, data){
          $.uploader.reformat(id, data.html);
        },
        onUploadError: function(id, message){
          $.uploader.updateFileStatus(id, 'error', message);
        },
        onFileTypeError: function(file){
          //$.danidemo.addLog('#demo-debug', 'error', 'File \'' + file.name + '\' cannot be added: must be an image');
        },
        onFileSizeError: function(file){
          //$.danidemo.addLog('#demo-debug', 'error', 'File \'' + file.name + '\' cannot be added: size excess limit');
        }
      });
});