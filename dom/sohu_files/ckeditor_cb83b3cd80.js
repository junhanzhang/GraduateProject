kola("ckeditor.CKEditorLoader",["jquery.Core","newt.Config","newt.net.NetCommon"],function(d,a,e){window.CKEDITOR_BASEPATH=a.CKEDITOR_BASEPATH;var c=null;var b=false;return{getInstance:function(f){f=d.extend({complete:function(){}},f);if(b){(function(g){setInterval(function(){if(b==false){g.complete(c)}},100)})(f)}if(c){f.complete(c)}else{b=true;(function(g){e.request({dataType:"script",cache:true,url:a.CKEDITOR_URI,success:function(){c=CKEDITOR;b=false;g.complete(c)}})})(f)}}}});