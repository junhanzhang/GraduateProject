kola("newt.ui.window.dialog.realname.AddAccount",["jquery.Core","newt.dialog.Notice","newt.ca.ca","newt.util.Validator","newt.widget.plus.Form","newt.net.interface.user","newt.widget.plus.Input","newt.data.Synchronizer"],function(f,b,n,d,g,l,i,j){var h=['<div class="form form6">','<form method="post" onsubmit="return false;" class="js-submitform">',"<fieldset>",'<div class="frm js-field-newAccount"> ','<p class="frmT"><em><label for="label_2">需要关联的帐号：</label></em></p>','<div class="frmC"><i class="txt"><input type="text" id="label_2" class="blur js-init-placeholder validate-required" placeholder="如abc@sohu.com" name="newAccount"></i><p class="frmTip"><i class="i"></i><b class="js-newAccount-tiptxt"></b></p></div>',"</div>",'<div class="frm js-field-newAccountPassword">','<p class="frmT"><em><label for="label_3">需要关联的帐号密码：</label></em></p>','<div class="frmC"><i class="txt"><input type="password" id="label_3" class="js-init-placeholder validate-required" placeholder="输入需要关联的帐号密码" name="newAccountPassword"></i><p class="frmTip"><i class="i"></i><b class="js-newAccountPassword-tiptxt"></b></p></div>',"</div>","</fieldset>","<fieldset>",'<div class="frm js-field-password">','<p class="frmT"><em><label for="label_1">当前账号密码：</label></em></p>','<div class="frmC"><i class="txt"><input type="password" id="label_1" class="js-init-placeholder validate-required" placeholder="输入当前帐号密码" name="password"></i><p class="frmTip"><i class="i"></i><b class="js-password-tiptxt"></b></p></div>',"</div>",'<div class="frm frmSubmit frmSubmit2">','<div class="frmC"><p class="btns"><a href="javascript: void(0);" class="btn js-start-add"><b>立即添加</b></a></p></div>',"</div>","</fieldset>","</form>","</div>"].join("");var m=[].join("");var k=null;var a=null;var c={init:function(t){if(!a){k=tw.window.getWindow("tw_empty_window",t);k.setTitle("添加关联账号");k.setId("tw_addaccount_box")}k.setHtml(h);a=k.getElement();a.find("div.mwFt").empty();a.addClass("mw");k.show();var s=a.find("form.js-submitform:first");var q=a.find("input[name]");i.INIT_PLACEHOLDERS({inputs:s.find("input.js-init-placeholder")});var p=new g({form:s,interFace:l.addAccount,_ajax:{beforeSend:function(){s.find("a.js-start-add:first").addClass("btn_dis btn_loading");q.attr("disabled","disabled")},error:function(){kola("newt.dialog.Notice",function(){tw.notice.alert("额。。服务器出错了。。。")})},success:function(x){if(x.status==0){o();if(t.success){t.success(x)}kola("newt.dialog.Notice",function(){tw.notice.showTip("添加成功")});for(var w=0,v=x.data.userInfo.length;w<v;++w){j.saveSet("add_account_list",x.data.userInfo[w].uid,x.data.userInfo[w],t.syncName);j.delSetItem("del_account_list",x.data.userInfo[w].uid,t.syncName)}}else{var y=x.statusText.split("_");if(y.length>1){switch(y[0]){case"当前帐号密码":r("password",y[1]);break;case"需要关联的帐号用户名":r("newAccount",y[1]);break;case"需要关联的帐号密码":r("newAccountPassword",y[1]);break}}else{kola("newt.dialog.Notice",function(){tw.notice.alert(y[0])})}}},complete:function(){s.find("a.js-start-add:first").removeClass("btn_dis btn_loading");q.removeAttr("disabled")}}});var u=new d({form:s,validateListener:function(y,w,A,B){delete w.VID;var x=true;for(var v in w){for(var z in w[v]){switch(z){case"required":if(w[v][z]==false){switch(v){case"password":r(v,"请填写当前账号密码");break;case"newAccount":r(v,"请填写关联的账号");break;case"newAccountPassword":r(v,"请填写关联账号密码");break}x=false}else{r(v,null)}break}}}if(x){o();p.ajaxSubmit()}}});function o(){s.find("input[name]").each(function(){var v=f(this);r(v.attr("name"),null)})}function r(w,v){if(v==null){s.find("div.js-field-"+w).removeClass("frm_err").addClass("frm_ok");s.find("b.js-"+w+"-tiptxt").html("")}else{s.find("div.js-field-"+w).removeClass("frm_ok").addClass("frm_err");s.find("b.js-"+w+"-tiptxt").html(v)}}s.find("a.js-start-add:first").bind("click",function(){var v=f(this);if(v.hasClass("btn_dis")){return}u.validateForm()});s.bind("keyup",function(v){if(v.keyCode==13){s.find("a.js-start-add:first").trigger("click");v.stopPropagation();return false}})},destory:function(){if(k){k.destory()}},show:function(o){c.init(o||{})}};return c});kola("newt.ui.window.dialog.realname.ValidIDCard",["jquery.Core","newt.dialog.Notice","newt.view.template.core.engine","newt.ca.ca"],function(h,g,b,c){var d=['<div class="ttl">',"<h6>你的粉丝已超过10000，请验证你的身份证，以保证你的真实身份不被人盗用。</h6>","</div>",'<div class="mwBc">','<div class="form form6">',"<form>","<fieldset>",'<div class="frm frm_err">','<p class="frmT"><em><label for="label_1">真实姓名：</label></em></p>','<div class="frmC"><i class="txt"><input type="text" id="label_1" class="blur" value="输入真实姓名"></i><p class="frmTip"><i class="i"></i><b>密码错误</b></p></div>',"</div>",'<div class="frm frm_ok">','<p class="frmT"><em><label for="label_2">身份证号：</label></em></p>','<div class="frmC"><i class="txt"><input type="text" id="label_2" class="blur" value="输入身份证号"></i><p class="frmTip"><i class="i"></i><b></b></p></div>',"</div>",'<div class="frm">','<p class="frmT"><em><label for="label_3">手机号码：</label></em></p>','<div class="frmC"><i class="txt"><input type="text" id="label_3" class="blur" value="输入手机号"></i><p class="frmTip"><i class="i"></i><b></b></p></div>',"</div>",'<div class="frm frmSubmit">','<div class="frmC"><p class="btns"><a href="#" class="btn"><b>立即验证</b></a></p></div>',"</div>","</fieldset>","</form>","</div>","</div>",'<div class="mwtip">',"<h6>小贴士：搜狐微博会帮你保密身份信息，请放心提交。</h6>","</div>"].join("");var f=[].join("");var i=null;var a={init:function(k){i=tw.window.getWindow("tw_empty_window",k);i.setTitle("验证身份证");i.setId("tw_valididcard_box");i.setHtml(d);var j=i.getElement();j.find("div.mwFt").empty();j.addClass("wm");i.show()},show:function(j){if(i==null){a.init(j)}i.show()}};return a});kola("newt.ui.window.dialog.realname.ValidIDCardModel",["jquery.Core","newt.widget.plus.Input","newt.widget.plus.Form","mvc.view.TemplateEngine","newt.net.interface.user","newt.util.Validator","newt.dialog.Notice","newt.cr.cr","newt.ca.ca","kola.net.Ajax","newt.data.Rule"],function(d,n,g,s,c,h,q,b,i,p,k){var a=null;var u=null;var f;var w;if(typeof window.authblock=="undefined"){w="new"}else{w=window.authblock?"new":"old"}var o=0;var l={};function m(t){var x=l[t.VID];x=x?x:{};d.extend(x,t);l[t.VID]=x}function r(t){return l[t]}var j=true;var v={init:function(t){t.expose={color:"#000000",opacity:0.2,loadSpeed:500};t.oneInstance=false;t.customID="tw_win_bb";if(!a){a=tw.window.getWindow("tw_empty_window",t);if(t.style=="plain"){d(a.getElement()).find(".crJs_window_title").html("真实身份信息认证")}else{d(a.getElement()).addClass("mwAdChk3").find("a.close");d(a.getElement()).find(".crJs_window_title").remove()}d(a.getElement()).find("a.close").attr("data-ca","realName327_md_x_"+w);a.setId("tw_regist_valididcard")}s.getTemplate({name:t.tmpl||"template.main.regist.valididcardmodule",complete:function(E){a.setHtml(E.render({data:{src:t.src||"regist"}}));u=a.getElement();u.find("div.mwHd").css("cursor","auto");u.find("div.mwFt").remove();u.addClass("mw");a.show();u.attr("style",u.attr("style").replace("9999","9999!important"));var z=u.next();z.attr("style",z.attr("style").replace("9998","9998!important"));var x=u.find("form.js-submitform:first");var C=u.find("input[name]");n.INIT_PLACEHOLDERS({inputs:x.find("input.js-init-placeholder")});var B=new h({form:x,validateTriggers:"submit|blur",beforeSubmit:function(){u.find("a.js-submit").addClass("btn_loading btn_dis").data("dis",true)},formPassValidate:function(H){F()},validateListener:function(H,P,L,K){m(P);var M=P.VID;var J=r(M);if(J){d.extend(P,J)}var O=true;for(var Q in P){if(Q=="VID"){continue}var I=false;for(var N in P[Q]){if(I==true){break}switch(N){case"required":if(P[Q][N]==false){switch(Q){case"id":A(Q,"请输入身份证号");break;case"name":A(Q,"请输入姓名");break;case"captchaCode":A(Q,"请输入验证码");break}O=false;I=true}else{if(P[Q][N]==true){A(Q,null)}}break;case"remote":if(P[Q][N]==false){switch(Q){case"captchaCode":A(Q,"验证码错误");break}O=false;I=true}else{if(P[Q][N]==true){A(Q,null)}}break;case"idcard":if(P[Q][N]==false){switch(Q){case"id":A(Q,"格式错误");break}O=false;I=true}else{if(P[Q][N]==true){A(Q,null)}}break;case"chinesename":if(P[Q][N]==false){switch(Q){case"name":A(Q,"请输入正确姓名");break}O=false;I=true}else{if(P[Q][N]==true){A(Q,null)}}break;case"requiredchinesename":if(P[Q][N]==false){switch(Q){case"name":A(Q,"请输入姓名");break}O=false;I=true}else{if(P[Q][N]==true){A(Q,null)}}break}}}if(O){G()}}});function y(H,I){if(H==0){setTimeout(function(){a.show(null,null,e.data)},1000);u.find("#view_input").removeClass("noDis");u.find("#view_err").addClass("noDis")}else{if(H==1){i.q("realName327_md_succ_"+w);if(tw&&tw.realNameBlock){tw.realNameBlock.turnOff()}u.find("#view_input").addClass("noDis");u.find("#view_ok").removeClass("noDis").find("span.js_vipcode").html(I);u.find("p.tip").addClass("noDis");if(t.succHandle){t.succHandle.call(t.succHandleScope||window)}}else{i.q("realName327_md_fail_"+w);u.find("#view_input").addClass("noDis");u.find("#view_err").removeClass("noDis").find(".js-errText").html(I);u.find("p.tip").addClass("noDis")}}}function D(I){I=d.extend({success:function(){u.find("b.js-captchaCode-tiptxt").html("")}},I||{});var H=u.find("img.js-code");if(H.size()<=0){return}c.flushValidCode(H,{beforeSend:function(){B.set("captchaCode",{remote:{enabled:false}})},success:function(J){u.find("div.js-field-captchaCode").removeClass("frm_ok frm_err").find(".txt input").val("");if(typeof(I.success)=="function"){I.success()}u.find("input[name='captchaCode']").val("");f=J.data;B.set("captchaCode",{remote:{enabled:true,url:c.URL.VALID_VALIDCODE,params:"{captchaCode}&captchaSeed="+J.data,dataType:"json",test:function(K){return K.status==0}}})}})}u.find("div.js-field-captchaCode").find(".txt input").bind("focus",function(){if(u.find("div.js-field-captchaCode").hasClass("frm_err")){D({success:function(){}})}});u.find("input[name='captchaCode']").data("parser",{remote:function(H){return d.cr.encodeUTF(H)}});u.find("p.js-flush-code:first").bind("click",function(){D()}).trigger("click");function G(){}function A(J,H){u.find("p.tip").addClass("noDis");if(H==null){x.find("div.js-field-"+J).removeClass("frm_err").addClass("frm_ok");x.find("b.js-"+J+"-tiptxt").html("")}else{if(J=="id"){var I=x.find("input[name="+J+"]");if(I.val()==I.attr("placeholder")){H="请输入身份证号"}}x.find("div.js-field-"+J).removeClass("frm_ok").addClass("frm_err");x.find("b.js-"+J+"-tiptxt").html(H)}}u.find("a.js-submit").click(function(){var H=d("a.js-submit");if(H.data("dis")==true){return}H.addClass("btn_loading btn_dis").data("dis",true);B.remoteAllDone(function(){H.removeClass("btn_loading btn_dis").data("dis",false)});x.submit()});function F(){var J=u.find("#label_2").val();var H=d.cr.encodeUTF(u.find("#label_1").val());var I=u.find("#captchaCode").val();p.request("http://"+location.host+"/jsauth/personalAuth",{method:"post",format:"json",data:"id="+J+"&name="+H+"&captchaCode="+d.cr.encodeUTF(I)+"&captchaSeed="+f,succ:function(K){if(K.status==0){y(1,K.statusText)}else{y(2,K.statusText)}}})}u.find("a.overlayCanB").add("a.js_close").bind("click",function(){a.close();if(typeof t.closeHandle=="function"){t.closeHandle()}});u.find("a.js_reValid").bind("click",function(){y(0)});u.bind("keyup",function(H){if(H.keyCode==13){u.find("a.js-submit").trigger("click")}})}})},destory:function(){if(a){a.destory()}},show:function(t){o++;v.init(t||{})}};return v});kola("newt.ui.window.dialog.realname.ValidIDCardSuccess",["jquery.Core","newt.dialog.Notice","newt.view.template.core.engine","newt.ca.ca"],function(i,h,b,c){var d=['<div class="ttl">',"<h6>你的粉丝已超过10000，请验证你的身份证，以保证你的真实身份不被人盗用。</h6>","</div>",'<div class="mwBc">','<div class="hint hintOk">','<div class="hintT"><i class="i"></i><em>验证身份成功，<br>你现在可以享受搜狐微博的更多服务</em></div>','<div class="hintC"><p class="btns"><a class="btn" href="javascript: void(0);"><b>关闭</b></a></p></div>',"</div>","</div>",'<div class="mwtip">',"<h6>小贴士：搜狐微博会帮你保密身份信息，请放心提交。</h6>","</div>"].join("");var g=[].join("");var a=null;var f={init:function(k){a=tw.window.getWindow("tw_empty_window",k);a.setTitle("验证身份证");a.setId("tw_valididcard_box");a.setHtml(d);var j=a.getElement();j.find("div.mwFt").empty();j.addClass("wm wm4");a.show()},show:function(j){if(a==null){f.init(j)}a.show()}};return f});kola("newt.ui.window.dialog.realname.ValidIDCardTip",["jquery.Core","newt.dialog.Notice","newt.view.template.core.engine","newt.ca.ca"],function(h,g,b,c){var d=['<div class="ttl">',"<h6>你的粉丝已超过10000，请验证你的身份证，以保证你的真实身份不被人盗用。</h6>","</div>",'<div class="mwBc">','<div class="hint hint2">','<div class="hintT"><i class="i"></i><em>你的粉丝已超过10000，为了保证你的真实身份证不被人盗用，请验证身份证号码。</em></div>','<div class="hintC">通过第三方身份认证机构验证你的身份<p class="btns"><a class="btn btnB2" href="#"><b>立即验证</b></a></p></div>',"</div>","</div>",'<div class="mwtip">',"<h6>小贴士：搜狐微博会帮你保密身份信息，请放心提交。</h6>","</div>"].join("");var f=[].join("");var i=null;var a={init:function(k){i=tw.window.getWindow("tw_empty_window",k);i.setTitle("验证身份证");i.setId("tw_valididcard_box");i.setHtml(d);var j=i.getElement();j.find("div.mwFt").empty();j.addClass("wm wm4");i.show()},show:function(j){if(i==null){a.init(j)}i.show()}};return a});