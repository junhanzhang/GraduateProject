kola("bricks.clay.wheel.plugin.Circular",["jquery.Core","kola.lang.Class"],function(c,a){var b=a.create({cycLength:1,interval:2000,autoStart:true,inverted:false,scrollLength:1,stopOnOver:true,_init:function(d){c.extend(this,d||{})},init:function(d){var e=this;e.switchable=d;d.prev=function(){e.prev()};d.next=function(){e.next()};d.start=function(){e.start()};d.start=function(){e.start()};if(e.autoStart){e.start()}e.initEvents()},initEvents:function(){var d=this;if(d.stopOnOver){d.switchable.ct.mouseenter(function(){d.stop()}).mouseleave(function(){d.start()})}},scroll:function(){var f=this,e=f.switchable.index,d=f.switchable.length;if(f.inverted){if(e==0){e=d-f.cycLength}else{e-=f.scrollLength}}else{if(e>=d-f.cycLength){e=0}else{e+=f.scrollLength}}f.switchable.switchTo(e,function(){f.start()})},start:function(){var d=this;if(d.switchable.length<=d.cycLength){return}d.stop();d.task=setTimeout(function(){d.scroll()},d.interval)},stop:function(){if(this.task){clearTimeout(this.task)}},prev:function(){this.stop();var d=this.switchable;if(d.index>0){d.switchTo(d.index-this.scrollLength)}this.start()},next:function(){this.stop();var d=this.switchable;if(d.index<d.length-this.cycLength){d.switchTo(d.index+this.scrollLength)}this.start()}});return b});kola("bricks.clay.wheel.plugin.Effect",["jquery.Core","kola.lang.Class"],function(c,b){var a=b.create({animate:"slideIn",duration:"normal",_init:function(d){c.extend(this,d||{})},init:function(d){var e=this;e.switchable=d;d.switchTo=function(g,f,h){if(g<0){g=0}else{if(g>d.length-1){g=d.length-1}}e.switchTo(g,function(){d.index=g;if(f){f.call(h)}})}},switchTo:function(){this[this.animate].apply(this,arguments)},marginTop:function(e,d){var f;if(typeof this.itemHeight==="undefined"){f=0;this.switchable.items.each(function(g,h){if(g==e){return false}f-=c(h).outerHeight(true)})}else{f=-this.itemHeight*e}this.switchable.ct.animate({marginTop:f},this.duration,d)},marginLeft:function(e,d){var f;if(typeof this.itemHeight==="undefined"){f=0;this.switchable.items.each(function(g,h){if(g==e){return false}f-=c(h).outerWidth(true)})}else{f=-this.itemHeight*e}this.switchable.ct.animate({marginLeft:f},this.duration,d)},slideIn:function(e,d){},slideOut:function(e,d){},fadeIn:function(e,d){},fadeOut:function(e,d){}});return a});