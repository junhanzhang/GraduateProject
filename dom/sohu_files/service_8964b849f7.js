kola("mvc.service.ServiceBuilder",["newt.net.NetCommon"],function(e){var b={};var a=0;var c={DEFAULT_BACKEND_INTERCEPTER:function(f){return f}};var d={create:function(){function g(){}g.prototype.toString=(function(h){return function(){return h}})(++a);g.STATIC_VALS={};g.VALS={};g.FRONTEND_INTERCEPTER=function(){return true};g.BACKEND_INTERCEPTERS={};g.prototype.getStaticVal=function(h){return g.STATIC_VALS[h]};g.prototype.clone=function(){var h=this;return d.clone({service:h})};var f=new g();b[f]={serviceClass:g};return f},addNetService:function(f){var g=b[f.service].serviceClass;g.prototype[f.name]=function(l){if(f.skipFrontEndIntercept!=true){if(g.FRONTEND_INTERCEPTER.apply(this,arguments)==false){return}}l=l?l:{};var j=l._ajax?l._ajax:{};delete l._ajax;for(var k in f.callbacks){var h=j[k];if(h){j[k]=function(){h.apply(this,arguments);f.callbacks[k].apply(this,arguments)}}else{j[k]=f.callbacks[k]}}if(f.skipBackEndIntercept!=true){for(var i in g.BACKEND_INTERCEPTERS){var h=j[i];var m=g.BACKEND_INTERCEPTERS[i];if(h){j[i]=function(){if(m.apply(this,arguments)!=false){h.apply(this,arguments)}}}}}e.request(f.processer(l,j))}},addStaticVal:function(f){var g=b[f.service].serviceClass;if(g.STATIC_VALS[f.name]===undefined){g.STATIC_VALS[f.name]=f.value;g.prototype[f.name]=function(){var k=g.STATIC_VALS[f.name];for(var j=0,h=arguments.length;j<h;++j){if(k.hasOwnProperty(arguments[j])){k=k[arguments[j]]}else{return undefined}}return k}}else{g.STATIC_VALS[f.name]=f.value}},clone:function(f){return new b[f.service].serviceClass()},addVal:function(f){var h=f.name.charAt(0).toUpperCase()+f.name.substr(1);var i=b[f.service].serviceClass;var j=i.prototype;if(i.VALS[f.service]===undefined){i.VALS[f.service]={}}var g=i.VALS[f.service];j["get"+h]=function(){if(g.hasOwnProperty(f.name)){return g[f.name]}else{g[f.name]=f.value;return g[f.name]}};j["set"+h]=function(k){return g[f.name]=k}},addVals:function(f){for(var g in f.vals){d.addVal({service:f.service,name:g,value:f.vals[g]})}},addStaticVals:function(g){for(var f in g.staticVals){d.addStaticVal({service:g.service,name:f,value:g.staticVals[f]})}},addUrls:function(f){d.addStaticVal({service:f.service,name:"url",value:f.urls})},addFrontEndIntercepter:function(f){b[f.service].serviceClass.FRONTEND_INTERCEPTER=f.intercepter},addBackEndIntercepter:function(f){b[f.service].serviceClass.BACKEND_INTERCEPTERS=f.intercepters},addService:function(f){var g=b[f.service].serviceClass;g.prototype[f.name]=function(){if(f.skipFrontEndIntercept!=true){if(g.FRONTEND_INTERCEPTER.apply(this,arguments)==false){return}}var h=c.DEFAULT_BACKEND_INTERCEPTER;if(f.skipBackEndIntercept!=true){h=g.BACKEND_INTERCEPTERS.localServiceReturn||h}return h(f.processer.apply(this,arguments))}}};d.addXHRService=d.addNetService;return d});