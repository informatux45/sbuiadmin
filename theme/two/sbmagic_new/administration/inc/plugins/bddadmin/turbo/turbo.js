/*
	Copyright (c) 2004-2005, The Dojo Foundation
	All Rights Reserved.

	Licensed under the Academic Free License version 2.1 or above OR the
	modified BSD license. For more information on Dojo licensing, see:

		http://dojotoolkit.org/community/licensing.shtml
*/

/*
	This is a compiled version of Dojo, built for deployment and not for
	development. To get an editable version, please visit:

		http://dojotoolkit.org

	for documentation and information on getting the source.
*/

var dj_global=this;
function dj_undef(_1,_2){
if(!_2){
_2=dj_global;
}
return (typeof _2[_1]=="undefined");
}
if(dj_undef("djConfig")){
var djConfig={};
}
var dojo;
if(dj_undef("dojo")){
dojo={};
}
dojo.version={major:0,minor:2,patch:2,flag:"+",revision:Number("$Rev: 2889 $".match(/[0-9]+/)[0]),toString:function(){
with(dojo.version){
return major+"."+minor+"."+patch+flag+" ("+revision+")";
}
}};
dojo.evalObjPath=function(_3,_4){
if(typeof _3!="string"){
return dj_global;
}
if(_3.indexOf(".")==-1){
if((dj_undef(_3,dj_global))&&(_4)){
dj_global[_3]={};
}
return dj_global[_3];
}
var _5=_3.split(/\./);
var _6=dj_global;
for(var i=0;i<_5.length;++i){
if(!_4){
_6=_6[_5[i]];
if((typeof _6=="undefined")||(!_6)){
return _6;
}
}else{
if(dj_undef(_5[i],_6)){
_6[_5[i]]={};
}
_6=_6[_5[i]];
}
}
return _6;
};
dojo.errorToString=function(_8){
return ((!dj_undef("message",_8))?_8.message:(dj_undef("description",_8)?_8:_8.description));
};
dojo.raise=function(_9,_a){
if(_a){
_9=_9+": "+dojo.errorToString(_a);
}
var he=dojo.hostenv;
if((!dj_undef("hostenv",dojo))&&(!dj_undef("println",dojo.hostenv))){
dojo.hostenv.println("FATAL: "+_9);
}
throw Error(_9);
};
dj_throw=dj_rethrow=function(m,e){
dojo.deprecated("dj_throw and dj_rethrow deprecated, use dojo.raise instead");
dojo.raise(m,e);
};
dojo.debug=function(){
if(!djConfig.isDebug){
return;
}
var _e=arguments;
if(dj_undef("println",dojo.hostenv)){
dojo.raise("dojo.debug not available (yet?)");
}
var _f=dj_global["jum"]&&!dj_global["jum"].isBrowser;
var s=[(_f?"":"DEBUG: ")];
for(var i=0;i<_e.length;++i){
if(!false&&_e[i] instanceof Error){
var msg="["+_e[i].name+": "+dojo.errorToString(_e[i])+(_e[i].fileName?", file: "+_e[i].fileName:"")+(_e[i].lineNumber?", line: "+_e[i].lineNumber:"")+"]";
}else{
try{
var msg=String(_e[i]);
}
catch(e){
if(dojo.render.html.ie){
var msg="[ActiveXObject]";
}else{
var msg="[unknown]";
}
}
}
s.push(msg);
}
if(_f){
jum.debug(s.join(" "));
}else{
dojo.hostenv.println(s.join(" "));
}
};
dojo.debugShallow=function(obj){
if(!djConfig.isDebug){
return;
}
dojo.debug("------------------------------------------------------------");
dojo.debug("Object: "+obj);
var _14=[];
for(var _15 in obj){
try{
_14.push(_15+": "+obj[_15]);
}
catch(E){
_14.push(_15+": ERROR - "+E.message);
}
}
_14.sort();
for(var i=0;i<_14.length;i++){
dojo.debug(_14[i]);
}
dojo.debug("------------------------------------------------------------");
};
var dj_debug=dojo.debug;
function dj_eval(s){
return dj_global.eval?dj_global.eval(s):eval(s);
}
dj_unimplemented=dojo.unimplemented=function(_18,_19){
var _1a="'"+_18+"' not implemented";
if((!dj_undef(_19))&&(_19)){
_1a+=" "+_19;
}
dojo.raise(_1a);
};
dj_deprecated=dojo.deprecated=function(_1b,_1c,_1d){
var _1e="DEPRECATED: "+_1b;
if(_1c){
_1e+=" "+_1c;
}
if(_1d){
_1e+=" -- will be removed in version: "+_1d;
}
dojo.debug(_1e);
};
dojo.inherits=function(_1f,_20){
if(typeof _20!="function"){
dojo.raise("superclass: "+_20+" borken");
}
_1f.prototype=new _20();
_1f.prototype.constructor=_1f;
_1f.superclass=_20.prototype;
_1f["super"]=_20.prototype;
};
dj_inherits=function(_21,_22){
dojo.deprecated("dj_inherits deprecated, use dojo.inherits instead");
dojo.inherits(_21,_22);
};
dojo.render=(function(){
function vscaffold(_23,_24){
var tmp={capable:false,support:{builtin:false,plugin:false},prefixes:_23};
for(var x in _24){
tmp[x]=false;
}
return tmp;
}
return {name:"",ver:dojo.version,os:{win:false,linux:false,osx:false},html:vscaffold(["html"],["ie","opera","khtml","safari","moz"]),svg:vscaffold(["svg"],["corel","adobe","batik"]),vml:vscaffold(["vml"],["ie"]),swf:vscaffold(["Swf","Flash","Mm"],["mm"]),swt:vscaffold(["Swt"],["ibm"])};
})();
dojo.hostenv=(function(){
var _27={isDebug:false,allowQueryConfig:false,baseScriptUri:"",baseRelativePath:"",libraryScriptUri:"",iePreventClobber:false,ieClobberMinimal:true,preventBackButtonFix:true,searchIds:[],parseWidgets:true};
if(typeof djConfig=="undefined"){
djConfig=_27;
}else{
for(var _28 in _27){
if(typeof djConfig[_28]=="undefined"){
djConfig[_28]=_27[_28];
}
}
}
var djc=djConfig;
function _def(obj,_2b,def){
return (dj_undef(_2b,obj)?def:obj[_2b]);
}
return {name_:"(unset)",version_:"(unset)",pkgFileName:"__package__",loading_modules_:{},loaded_modules_:{},addedToLoadingCount:[],removedFromLoadingCount:[],inFlightCount:0,modulePrefixes_:{dojo:{name:"dojo",value:"src"}},setModulePrefix:function(_2d,_2e){
this.modulePrefixes_[_2d]={name:_2d,value:_2e};
},getModulePrefix:function(_2f){
var mp=this.modulePrefixes_;
if((mp[_2f])&&(mp[_2f]["name"])){
return mp[_2f].value;
}
return _2f;
},getTextStack:[],loadUriStack:[],loadedUris:[],post_load_:false,modulesLoadedListeners:[],getName:function(){
return this.name_;
},getVersion:function(){
return this.version_;
},getText:function(uri){
dojo.unimplemented("getText","uri="+uri);
},getLibraryScriptUri:function(){
dojo.unimplemented("getLibraryScriptUri","");
}};
})();
dojo.hostenv.getBaseScriptUri=function(){
if(djConfig.baseScriptUri.length){
return djConfig.baseScriptUri;
}
var uri=new String(djConfig.libraryScriptUri||djConfig.baseRelativePath);
if(!uri){
dojo.raise("Nothing returned by getLibraryScriptUri(): "+uri);
}
var _33=uri.lastIndexOf("/");
djConfig.baseScriptUri=djConfig.baseRelativePath;
return djConfig.baseScriptUri;
};
dojo.hostenv.setBaseScriptUri=function(uri){
djConfig.baseScriptUri=uri;
};
dojo.hostenv.loadPath=function(_35,_36,cb){
if((_35.charAt(0)=="/")||(_35.match(/^\w+:/))){
dojo.raise("relpath '"+_35+"'; must be relative");
}
var uri=this.getBaseScriptUri()+_35;
if(djConfig.cacheBust&&dojo.render.html.capable){
uri+="?"+String(djConfig.cacheBust).replace(/\W+/g,"");
}
try{
return ((!_36)?this.loadUri(uri,cb):this.loadUriAndCheck(uri,_36,cb));
}
catch(e){
dojo.debug(e);
return false;
}
};
dojo.hostenv.loadUri=function(uri,cb){
if(this.loadedUris[uri]){
return;
}
var _3b=this.getText(uri,null,true);
if(_3b==null){
return 0;
}
this.loadedUris[uri]=true;
var _3c=dj_eval(_3b);
return 1;
};
dojo.hostenv.loadUriAndCheck=function(uri,_3e,cb){
var ok=true;
try{
ok=this.loadUri(uri,cb);
}
catch(e){
dojo.debug("failed loading ",uri," with error: ",e);
}
return ((ok)&&(this.findModule(_3e,false)))?true:false;
};
dojo.loaded=function(){
};
dojo.hostenv.loaded=function(){
this.post_load_=true;
var mll=this.modulesLoadedListeners;
for(var x=0;x<mll.length;x++){
mll[x]();
}
dojo.loaded();
};
dojo.addOnLoad=function(obj,_44){
if(arguments.length==1){
dojo.hostenv.modulesLoadedListeners.push(obj);
}else{
if(arguments.length>1){
dojo.hostenv.modulesLoadedListeners.push(function(){
obj[_44]();
});
}
}
};
dojo.hostenv.modulesLoaded=function(){
if(this.post_load_){
return;
}
if((this.loadUriStack.length==0)&&(this.getTextStack.length==0)){
if(this.inFlightCount>0){
dojo.debug("files still in flight!");
return;
}
if(typeof setTimeout=="object"){
setTimeout("dojo.hostenv.loaded();",0);
}else{
dojo.hostenv.loaded();
}
}
};
dojo.hostenv.moduleLoaded=function(_45){
var _46=dojo.evalObjPath((_45.split(".").slice(0,-1)).join("."));
this.loaded_modules_[(new String(_45)).toLowerCase()]=_46;
};
dojo.hostenv._global_omit_module_check=false;
dojo.hostenv.loadModule=function(_47,_48,_49){
if(!_47){
return;
}
_49=this._global_omit_module_check||_49;
var _4a=this.findModule(_47,false);
if(_4a){
return _4a;
}
if(dj_undef(_47,this.loading_modules_)){
this.addedToLoadingCount.push(_47);
}
this.loading_modules_[_47]=1;
var _4b=_47.replace(/\./g,"/")+".js";
var _4c=_47.split(".");
var _4d=_47.split(".");
for(var i=_4c.length-1;i>0;i--){
var _4f=_4c.slice(0,i).join(".");
var _50=this.getModulePrefix(_4f);
if(_50!=_4f){
_4c.splice(0,i,_50);
break;
}
}
var _51=_4c[_4c.length-1];
if(_51=="*"){
_47=(_4d.slice(0,-1)).join(".");
while(_4c.length){
_4c.pop();
_4c.push(this.pkgFileName);
_4b=_4c.join("/")+".js";
if(_4b.charAt(0)=="/"){
_4b=_4b.slice(1);
}
ok=this.loadPath(_4b,((!_49)?_47:null));
if(ok){
break;
}
_4c.pop();
}
}else{
_4b=_4c.join("/")+".js";
_47=_4d.join(".");
var ok=this.loadPath(_4b,((!_49)?_47:null));
if((!ok)&&(!_48)){
_4c.pop();
while(_4c.length){
_4b=_4c.join("/")+".js";
ok=this.loadPath(_4b,((!_49)?_47:null));
if(ok){
break;
}
_4c.pop();
_4b=_4c.join("/")+"/"+this.pkgFileName+".js";
if(_4b.charAt(0)=="/"){
_4b=_4b.slice(1);
}
ok=this.loadPath(_4b,((!_49)?_47:null));
if(ok){
break;
}
}
}
if((!ok)&&(!_49)){
dojo.raise("Could not load '"+_47+"'; last tried '"+_4b+"'");
}
}
if(!_49){
_4a=this.findModule(_47,false);
if(!_4a){
dojo.raise("symbol '"+_47+"' is not defined after loading '"+_4b+"'");
}
}
return _4a;
};
dojo.hostenv.startPackage=function(_53){
var _54=_53.split(/\./);
if(_54[_54.length-1]=="*"){
_54.pop();
}
return dojo.evalObjPath(_54.join("."),true);
};
dojo.hostenv.findModule=function(_55,_56){
var lmn=(new String(_55)).toLowerCase();
if(this.loaded_modules_[lmn]){
return this.loaded_modules_[lmn];
}
var _58=dojo.evalObjPath(_55);
if((_55)&&(typeof _58!="undefined")&&(_58)){
this.loaded_modules_[lmn]=_58;
return _58;
}
if(_56){
dojo.raise("no loaded module named '"+_55+"'");
}
return null;
};
if(typeof window=="undefined"){
dojo.raise("no window object");
}
(function(){
if(djConfig.allowQueryConfig){
var _59=document.location.toString();
var _5a=_59.split("?",2);
if(_5a.length>1){
var _5b=_5a[1];
var _5c=_5b.split("&");
for(var x in _5c){
var sp=_5c[x].split("=");
if((sp[0].length>9)&&(sp[0].substr(0,9)=="djConfig.")){
var opt=sp[0].substr(9);
try{
djConfig[opt]=eval(sp[1]);
}
catch(e){
djConfig[opt]=sp[1];
}
}
}
}
}
if(((djConfig["baseScriptUri"]=="")||(djConfig["baseRelativePath"]==""))&&(document&&document.getElementsByTagName)){
var _60=document.getElementsByTagName("script");
var _61=/(__package__|dojo|bootstrap1)\.js([\?\.]|$)/i;
for(var i=0;i<_60.length;i++){
var src=_60[i].getAttribute("src");
if(!src){
continue;
}
var m=src.match(_61);
if(m){
root=src.substring(0,m.index);
if(src.indexOf("bootstrap1")>-1){
root+="../";
}
if(!this["djConfig"]){
djConfig={};
}
if(djConfig["baseScriptUri"]==""){
djConfig["baseScriptUri"]=root;
}
if(djConfig["baseRelativePath"]==""){
djConfig["baseRelativePath"]=root;
}
break;
}
}
}
var dr=dojo.render;
var drh=dojo.render.html;
var drs=dojo.render.svg;
var dua=drh.UA=navigator.userAgent;
var dav=drh.AV=navigator.appVersion;
var t=true;
var f=false;
drh.capable=t;
drh.support.builtin=t;
dr.ver=parseFloat(drh.AV);
dr.os.mac=dav.indexOf("Macintosh")>=0;
dr.os.win=dav.indexOf("Windows")>=0;
dr.os.linux=dav.indexOf("X11")>=0;
drh.opera=dua.indexOf("Opera")>=0;
drh.khtml=(dav.indexOf("Konqueror")>=0)||(dav.indexOf("Safari")>=0);
drh.safari=dav.indexOf("Safari")>=0;
var _6c=dua.indexOf("Gecko");
drh.mozilla=drh.moz=(_6c>=0)&&(!drh.khtml);
if(drh.mozilla){
drh.geckoVersion=dua.substring(_6c+6,_6c+14);
}
drh.ie=(document.all)&&(!drh.opera);
drh.ie50=drh.ie&&dav.indexOf("MSIE 5.0")>=0;
drh.ie55=drh.ie&&dav.indexOf("MSIE 5.5")>=0;
drh.ie60=drh.ie&&dav.indexOf("MSIE 6.0")>=0;
dr.vml.capable=drh.ie;
drs.capable=f;
drs.support.plugin=f;
drs.support.builtin=f;
drs.adobe=f;
if(document.implementation&&document.implementation.hasFeature&&document.implementation.hasFeature("org.w3c.dom.svg","1.0")){
drs.capable=t;
drs.support.builtin=t;
drs.support.plugin=f;
drs.adobe=f;
}else{
if(navigator.mimeTypes&&navigator.mimeTypes.length>0){
var _6d=navigator.mimeTypes["image/svg+xml"]||navigator.mimeTypes["image/svg"]||navigator.mimeTypes["image/svg-xml"];
if(_6d){
drs.adobe=_6d&&_6d.enabledPlugin&&_6d.enabledPlugin.description&&(_6d.enabledPlugin.description.indexOf("Adobe")>-1);
if(drs.adobe){
drs.capable=t;
drs.support.plugin=t;
}
}
}else{
if(drh.ie&&dr.os.win){
var _6d=f;
try{
var _6e=new ActiveXObject("Adobe.SVGCtl");
_6d=t;
}
catch(e){
}
if(_6d){
drs.capable=t;
drs.support.plugin=t;
drs.adobe=t;
}
}else{
drs.capable=f;
drs.support.plugin=f;
drs.adobe=f;
}
}
}
})();
dojo.hostenv.startPackage("dojo.hostenv");
dojo.hostenv.name_="browser";
dojo.hostenv.searchIds=[];
var DJ_XMLHTTP_PROGIDS=["Msxml2.XMLHTTP","Microsoft.XMLHTTP","Msxml2.XMLHTTP.4.0"];
dojo.hostenv.getXmlhttpObject=function(){
var _6f=null;
var _70=null;
try{
_6f=new XMLHttpRequest();
}
catch(e){
}
if(!_6f){
for(var i=0;i<3;++i){
var _72=DJ_XMLHTTP_PROGIDS[i];
try{
_6f=new ActiveXObject(_72);
}
catch(e){
_70=e;
}
if(_6f){
DJ_XMLHTTP_PROGIDS=[_72];
break;
}
}
}
if(!_6f){
return dojo.raise("XMLHTTP not available",_70);
}
return _6f;
};
dojo.hostenv.getText=function(uri,_74,_75){
var _76=this.getXmlhttpObject();
if(_74){
_76.onreadystatechange=function(){
if((4==_76.readyState)&&(_76["status"])){
if(_76.status==200){
_74(_76.responseText);
}
}
};
}
_76.open("GET",uri,_74?true:false);
try{
_76.send(null);
}
catch(e){
if(_75&&!_74){
return null;
}else{
throw e;
}
}
if(_74){
return null;
}
return _76.responseText;
};
dojo.hostenv.defaultDebugContainerId="dojoDebug";
dojo.hostenv._println_buffer=[];
dojo.hostenv._println_safe=false;
dojo.hostenv.println=function(_77){
if(!dojo.hostenv._println_safe){
dojo.hostenv._println_buffer.push(_77);
}else{
try{
var _78=document.getElementById(djConfig.debugContainerId?djConfig.debugContainerId:dojo.hostenv.defaultDebugContainerId);
if(!_78){
_78=document.getElementsByTagName("body")[0]||document.body;
}
var div=document.createElement("div");
div.appendChild(document.createTextNode(_77));
_78.appendChild(div);
}
catch(e){
try{
document.write("<div>"+_77+"</div>");
}
catch(e2){
window.status=_77;
}
}
}
};
dojo.addOnLoad(function(){
dojo.hostenv._println_safe=true;
while(dojo.hostenv._println_buffer.length>0){
dojo.hostenv.println(dojo.hostenv._println_buffer.shift());
}
});
function dj_addNodeEvtHdlr(_7a,_7b,fp,_7d){
var _7e=_7a["on"+_7b]||function(){
};
_7a["on"+_7b]=function(){
fp.apply(_7a,arguments);
_7e.apply(_7a,arguments);
};
return true;
}
dj_load_init=function(){
if(arguments.callee.initialized){
return;
}
arguments.callee.initialized=true;
if(dojo.render.html.ie){
dojo.hostenv.makeWidgets();
}
dojo.hostenv.modulesLoaded();
};
dj_addNodeEvtHdlr(window,"load",dj_load_init);
dojo.hostenv.makeWidgets=function(){
var _7f=[];
if(djConfig.searchIds&&djConfig.searchIds.length>0){
_7f=_7f.concat(djConfig.searchIds);
}
if(dojo.hostenv.searchIds&&dojo.hostenv.searchIds.length>0){
_7f=_7f.concat(dojo.hostenv.searchIds);
}
if((djConfig.parseWidgets)||(_7f.length>0)){
if(dojo.evalObjPath("dojo.widget.Parse")){
try{
var _80=new dojo.xml.Parse();
if(_7f.length>0){
for(var x=0;x<_7f.length;x++){
var _82=document.getElementById(_7f[x]);
if(!_82){
continue;
}
var _83=_80.parseElement(_82,null,true);
dojo.widget.getParser().createComponents(_83);
}
}else{
if(djConfig.parseWidgets){
var _83=_80.parseElement(document.getElementsByTagName("body")[0]||document.body,null,true);
dojo.widget.getParser().createComponents(_83);
}
}
}
catch(e){
dojo.debug("auto-build-widgets error:",e);
}
}
}
};
dojo.hostenv.modulesLoadedListeners.push(function(){
if(!dojo.render.html.ie){
dojo.hostenv.makeWidgets();
}
});
try{
if(dojo.render.html.ie){
document.write("<style>v:*{ behavior:url(#default#VML); }</style>");
document.write("<xml:namespace ns=\"urn:schemas-microsoft-com:vml\" prefix=\"v\"/>");
}
}
catch(e){
}
dojo.hostenv.writeIncludes=function(){
};
dojo.hostenv.byId=dojo.byId=function(id,doc){
if(id&&(typeof id=="string"||id instanceof String)){
if(!doc){
doc=document;
}
return doc.getElementById(id);
}
return id;
};
dojo.hostenv.byIdArray=dojo.byIdArray=function(){
var ids=[];
for(var i=0;i<arguments.length;i++){
if((arguments[i] instanceof Array)||(typeof arguments[i]=="array")){
for(var j=0;j<arguments[i].length;j++){
ids=ids.concat(dojo.hostenv.byIdArray(arguments[i][j]));
}
}else{
ids.push(dojo.hostenv.byId(arguments[i]));
}
}
return ids;
};
dojo.hostenv.conditionalLoadModule=function(_89){
var _8a=_89["common"]||[];
var _8b=(_89[dojo.hostenv.name_])?_8a.concat(_89[dojo.hostenv.name_]||[]):_8a.concat(_89["default"]||[]);
for(var x=0;x<_8b.length;x++){
var _8d=_8b[x];
if(_8d.constructor==Array){
dojo.hostenv.loadModule.apply(dojo.hostenv,_8d);
}else{
dojo.hostenv.loadModule(_8d);
}
}
};
dojo.hostenv.require=dojo.hostenv.loadModule;
dojo.require=function(){
dojo.hostenv.loadModule.apply(dojo.hostenv,arguments);
};
dojo.requireAfter=dojo.require;
dojo.requireIf=function(){
if((arguments[0]===true)||(arguments[0]=="common")||(dojo.render[arguments[0]].capable)){
var _8e=[];
for(var i=1;i<arguments.length;i++){
_8e.push(arguments[i]);
}
dojo.require.apply(dojo,_8e);
}
};
dojo.requireAfterIf=dojo.requireIf;
dojo.conditionalRequire=dojo.requireIf;
dojo.requireAll=function(){
for(var i=0;i<arguments.length;i++){
dojo.require(arguments[i]);
}
};
dojo.kwCompoundRequire=function(){
dojo.hostenv.conditionalLoadModule.apply(dojo.hostenv,arguments);
};
dojo.hostenv.provide=dojo.hostenv.startPackage;
dojo.provide=function(){
return dojo.hostenv.startPackage.apply(dojo.hostenv,arguments);
};
dojo.setModulePrefix=function(_91,_92){
return dojo.hostenv.setModulePrefix(_91,_92);
};
dojo.profile={start:function(){
},end:function(){
},stop:function(){
},dump:function(){
}};
dojo.exists=function(obj,_94){
var p=_94.split(".");
for(var i=0;i<p.length;i++){
if(!(obj[p[i]])){
return false;
}
obj=obj[p[i]];
}
return true;
};
dojo.provide("turbo.lib.json");
JSON={org:"http://www.JSON.org",copyright:"(c)2005 JSON.org",license:"http://www.crockford.com/JSON/license.html",stringify:function(arg){
var c,i,l,s="",v;
switch(typeof arg){
case "object":
if(arg){
if(arg instanceof Array){
for(i=0;i<arg.length;++i){
v=this.stringify(arg[i]);
if(s){
s+=",";
}
s+=v;
}
return "["+s+"]";
}else{
if(typeof arg.toString!="undefined"){
for(i in arg){
v=arg[i];
if(typeof v!="undefined"&&typeof v!="function"){
v=this.stringify(v);
if(s){
s+=",";
}
s+=this.stringify(i)+":"+v;
}
}
return "{"+s+"}";
}
}
}
return "null";
case "number":
return isFinite(arg)?String(arg):"null";
case "string":
l=arg.length;
s="\"";
for(i=0;i<l;i+=1){
c=arg.charAt(i);
if(c>=" "){
if(c=="\\"||c=="\""){
s+="\\";
}
s+=c;
}else{
switch(c){
case "\b":
s+="\\b";
break;
case "\f":
s+="\\f";
break;
case "\n":
s+="\\n";
break;
case "\r":
s+="\\r";
break;
case "\t":
s+="\\t";
break;
default:
c=c.charCodeAt();
s+="\\u00"+Math.floor(c/16).toString(16)+(c%16).toString(16);
}
}
}
return s+"\"";
case "boolean":
return String(arg);
default:
return "null";
}
},parse:function(_99){
var at=0;
var ch=" ";
function error(m){
throw {name:"JSONError",message:m,at:at-1,text:_99};
}
function next(){
ch=_99.charAt(at);
at+=1;
return ch;
}
function white(){
while(ch){
if(ch<=" "){
next();
}else{
if(ch=="/"){
switch(next()){
case "/":
while(next()&&ch!="\n"&&ch!="\r"){
}
break;
case "*":
next();
for(;;){
if(ch){
if(ch=="*"){
if(next()=="/"){
next();
break;
}
}else{
next();
}
}else{
error("Unterminated comment");
}
}
break;
default:
error("Syntax error");
}
}else{
break;
}
}
}
}
function string(){
var i,s="",t,u;
if(ch=="\""){
outer:
while(next()){
if(ch=="\""){
next();
return s;
}else{
if(ch=="\\"){
switch(next()){
case "b":
s+="\b";
break;
case "f":
s+="\f";
break;
case "n":
s+="\n";
break;
case "r":
s+="\r";
break;
case "t":
s+="\t";
break;
case "u":
u=0;
for(i=0;i<4;i+=1){
t=parseInt(next(),16);
if(!isFinite(t)){
break outer;
}
u=u*16+t;
}
s+=String.fromCharCode(u);
break;
default:
s+=ch;
}
}else{
s+=ch;
}
}
}
}
error("Bad string");
}
function array(){
var a=[];
if(ch=="["){
next();
white();
if(ch=="]"){
next();
return a;
}
while(ch){
a.push(value());
white();
if(ch=="]"){
next();
return a;
}else{
if(ch!=","){
break;
}
}
next();
white();
}
}
error("Bad array");
}
function object(){
var k,o={};
if(ch=="{"){
next();
white();
if(ch=="}"){
next();
return o;
}
while(ch){
k=string();
white();
if(ch!=":"){
break;
}
next();
o[k]=value();
white();
if(ch=="}"){
next();
return o;
}else{
if(ch!=","){
break;
}
}
next();
white();
}
}
error("Bad object");
}
function number(){
var n="",v;
if(ch=="-"){
n="-";
next();
}
while(ch>="0"&&ch<="9"){
n+=ch;
next();
}
if(ch=="."){
n+=".";
while(next()&&ch>="0"&&ch<="9"){
n+=ch;
}
}
if(ch=="e"||ch=="E"){
n+="e";
next();
if(ch=="-"||ch=="+"){
n+=ch;
next();
}
while(ch>="0"&&ch<="9"){
n+=ch;
next();
}
}
v=+n;
if(!isFinite(v)){
}else{
return v;
}
}
function word(){
switch(ch){
case "t":
if(next()=="r"&&next()=="u"&&next()=="e"){
next();
return true;
}
break;
case "f":
if(next()=="a"&&next()=="l"&&next()=="s"&&next()=="e"){
next();
return false;
}
break;
case "n":
if(next()=="u"&&next()=="l"&&next()=="l"){
next();
return null;
}
break;
}
error("Syntax error");
}
function value(){
white();
switch(ch){
case "{":
return object();
case "[":
return array();
case "\"":
return string();
case "-":
return number();
default:
return ch>="0"&&ch<="9"?number():word();
}
}
return value();
}};
dojo.provide("dojo.string.common");
dojo.require("dojo.string");
dojo.string.trim=function(str,wh){
if(!str.replace){
return str;
}
if(!str.length){
return str;
}
var re=(wh>0)?(/^\s+/):(wh<0)?(/\s+$/):(/^\s+|\s+$/g);
return str.replace(re,"");
};
dojo.string.trimStart=function(str){
return dojo.string.trim(str,1);
};
dojo.string.trimEnd=function(str){
return dojo.string.trim(str,-1);
};
dojo.string.repeat=function(str,_a7,_a8){
var out="";
for(var i=0;i<_a7;i++){
out+=str;
if(_a8&&i<_a7-1){
out+=_a8;
}
}
return out;
};
dojo.string.pad=function(str,len,c,dir){
var out=String(str);
if(!c){
c="0";
}
if(!dir){
dir=1;
}
while(out.length<len){
if(dir>0){
out=c+out;
}else{
out+=c;
}
}
return out;
};
dojo.string.padLeft=function(str,len,c){
return dojo.string.pad(str,len,c,1);
};
dojo.string.padRight=function(str,len,c){
return dojo.string.pad(str,len,c,-1);
};
dojo.provide("dojo.string");
dojo.require("dojo.string.common");
dojo.provide("dojo.lang.common");
dojo.require("dojo.lang");
dojo.lang.mixin=function(obj,_b7){
var _b8={};
for(var x in _b7){
if(typeof _b8[x]=="undefined"||_b8[x]!=_b7[x]){
obj[x]=_b7[x];
}
}
if(dojo.render.html.ie&&dojo.lang.isFunction(_b7["toString"])&&_b7["toString"]!=obj["toString"]){
obj.toString=_b7.toString;
}
return obj;
};
dojo.lang.extend=function(_ba,_bb){
this.mixin(_ba.prototype,_bb);
};
dojo.lang.find=function(arr,val,_be,_bf){
if(!dojo.lang.isArrayLike(arr)&&dojo.lang.isArrayLike(val)){
var a=arr;
arr=val;
val=a;
}
var _c1=dojo.lang.isString(arr);
if(_c1){
arr=arr.split("");
}
if(_bf){
var _c2=-1;
var i=arr.length-1;
var end=-1;
}else{
var _c2=1;
var i=0;
var end=arr.length;
}
if(_be){
while(i!=end){
if(arr[i]===val){
return i;
}
i+=_c2;
}
}else{
while(i!=end){
if(arr[i]==val){
return i;
}
i+=_c2;
}
}
return -1;
};
dojo.lang.indexOf=dojo.lang.find;
dojo.lang.findLast=function(arr,val,_c7){
return dojo.lang.find(arr,val,_c7,true);
};
dojo.lang.lastIndexOf=dojo.lang.findLast;
dojo.lang.inArray=function(arr,val){
return dojo.lang.find(arr,val)>-1;
};
dojo.lang.isObject=function(wh){
return typeof wh=="object"||dojo.lang.isArray(wh)||dojo.lang.isFunction(wh);
};
dojo.lang.isArray=function(wh){
return (wh instanceof Array||typeof wh=="array");
};
dojo.lang.isArrayLike=function(wh){
if(dojo.lang.isString(wh)){
return false;
}
if(dojo.lang.isFunction(wh)){
return false;
}
if(dojo.lang.isArray(wh)){
return true;
}
if(typeof wh!="undefined"&&wh&&dojo.lang.isNumber(wh.length)&&isFinite(wh.length)){
return true;
}
return false;
};
dojo.lang.isFunction=function(wh){
return (wh instanceof Function||typeof wh=="function");
};
dojo.lang.isString=function(wh){
return (wh instanceof String||typeof wh=="string");
};
dojo.lang.isAlien=function(wh){
return !dojo.lang.isFunction()&&/\{\s*\[native code\]\s*\}/.test(String(wh));
};
dojo.lang.isBoolean=function(wh){
return (wh instanceof Boolean||typeof wh=="boolean");
};
dojo.lang.isNumber=function(wh){
return (wh instanceof Number||typeof wh=="number");
};
dojo.lang.isUndefined=function(wh){
return ((wh==undefined)&&(typeof wh=="undefined"));
};
dojo.provide("dojo.lang.type");
dojo.require("dojo.lang.common");
dojo.lang.whatAmI=function(wh){
try{
if(dojo.lang.isArray(wh)){
return "array";
}
if(dojo.lang.isFunction(wh)){
return "function";
}
if(dojo.lang.isString(wh)){
return "string";
}
if(dojo.lang.isNumber(wh)){
return "number";
}
if(dojo.lang.isBoolean(wh)){
return "boolean";
}
if(dojo.lang.isAlien(wh)){
return "alien";
}
if(dojo.lang.isUndefined(wh)){
return "undefined";
}
for(var _d4 in dojo.lang.whatAmI.custom){
if(dojo.lang.whatAmI.custom[_d4](wh)){
return _d4;
}
}
if(dojo.lang.isObject(wh)){
return "object";
}
}
catch(E){
}
return "unknown";
};
dojo.lang.whatAmI.custom={};
dojo.lang.isNumeric=function(wh){
return (!isNaN(wh)&&isFinite(wh)&&(wh!=null)&&!dojo.lang.isBoolean(wh)&&!dojo.lang.isArray(wh));
};
dojo.lang.isBuiltIn=function(wh){
return (dojo.lang.isArray(wh)||dojo.lang.isFunction(wh)||dojo.lang.isString(wh)||dojo.lang.isNumber(wh)||dojo.lang.isBoolean(wh)||(wh==null)||(wh instanceof Error)||(typeof wh=="error"));
};
dojo.lang.isPureObject=function(wh){
return ((wh!=null)&&dojo.lang.isObject(wh)&&wh.constructor==Object);
};
dojo.lang.isOfType=function(_d8,_d9){
if(dojo.lang.isArray(_d9)){
var _da=_d9;
for(var i in _da){
var _dc=_da[i];
if(dojo.lang.isOfType(_d8,_dc)){
return true;
}
}
return false;
}else{
if(dojo.lang.isString(_d9)){
_d9=_d9.toLowerCase();
}
switch(_d9){
case Array:
case "array":
return dojo.lang.isArray(_d8);
break;
case Function:
case "function":
return dojo.lang.isFunction(_d8);
break;
case String:
case "string":
return dojo.lang.isString(_d8);
break;
case Number:
case "number":
return dojo.lang.isNumber(_d8);
break;
case "numeric":
return dojo.lang.isNumeric(_d8);
break;
case Boolean:
case "boolean":
return dojo.lang.isBoolean(_d8);
break;
case Object:
case "object":
return dojo.lang.isObject(_d8);
break;
case "pureobject":
return dojo.lang.isPureObject(_d8);
break;
case "builtin":
return dojo.lang.isBuiltIn(_d8);
break;
case "alien":
return dojo.lang.isAlien(_d8);
break;
case "undefined":
return dojo.lang.isUndefined(_d8);
break;
case null:
case "null":
return (_d8===null);
break;
case "optional":
return ((_d8===null)||dojo.lang.isUndefined(_d8));
break;
default:
if(dojo.lang.isFunction(_d9)){
return (_d8 instanceof _d9);
}else{
dojo.raise("dojo.lang.isOfType() was passed an invalid type");
}
break;
}
}
dojo.raise("If we get here, it means a bug was introduced above.");
};
dojo.provide("dojo.lang.extras");
dojo.require("dojo.lang.common");
dojo.require("dojo.lang.type");
dojo.lang.setTimeout=function(_dd,_de){
var _df=window,argsStart=2;
if(!dojo.lang.isFunction(_dd)){
_df=_dd;
_dd=_de;
_de=arguments[2];
argsStart++;
}
if(dojo.lang.isString(_dd)){
_dd=_df[_dd];
}
var _e0=[];
for(var i=argsStart;i<arguments.length;i++){
_e0.push(arguments[i]);
}
return setTimeout(function(){
_dd.apply(_df,_e0);
},_de);
};
dojo.lang.getNameInObj=function(ns,_e3){
if(!ns){
ns=dj_global;
}
for(var x in ns){
if(ns[x]===_e3){
return new String(x);
}
}
return null;
};
dojo.lang.shallowCopy=function(obj){
var ret={},key;
for(key in obj){
if(dojo.lang.isUndefined(ret[key])){
ret[key]=obj[key];
}
}
return ret;
};
dojo.lang.firstValued=function(){
for(var i=0;i<arguments.length;i++){
if(typeof arguments[i]!="undefined"){
return arguments[i];
}
}
return undefined;
};
dojo.provide("dojo.io.IO");
dojo.require("dojo.string");
dojo.require("dojo.lang.extras");
dojo.io.transports=[];
dojo.io.hdlrFuncNames=["load","error"];
dojo.io.Request=function(url,_e9,_ea,_eb){
if((arguments.length==1)&&(arguments[0].constructor==Object)){
this.fromKwArgs(arguments[0]);
}else{
this.url=url;
if(_e9){
this.mimetype=_e9;
}
if(_ea){
this.transport=_ea;
}
if(arguments.length>=4){
this.changeUrl=_eb;
}
}
};
dojo.lang.extend(dojo.io.Request,{url:"",mimetype:"text/plain",method:"GET",content:undefined,transport:undefined,changeUrl:undefined,formNode:undefined,sync:false,bindSuccess:false,useCache:false,preventCache:false,load:function(_ec,_ed,evt){
},error:function(_ef,_f0){
},handle:function(){
},abort:function(){
},fromKwArgs:function(_f1){
if(_f1["url"]){
_f1.url=_f1.url.toString();
}
if(_f1["formNode"]){
_f1.formNode=dojo.byId(_f1.formNode);
}
if(!_f1["method"]&&_f1["formNode"]&&_f1["formNode"].method){
_f1.method=_f1["formNode"].method;
}
if(!_f1["handle"]&&_f1["handler"]){
_f1.handle=_f1.handler;
}
if(!_f1["load"]&&_f1["loaded"]){
_f1.load=_f1.loaded;
}
if(!_f1["changeUrl"]&&_f1["changeURL"]){
_f1.changeUrl=_f1.changeURL;
}
_f1.encoding=dojo.lang.firstValued(_f1["encoding"],djConfig["bindEncoding"],"");
_f1.sendTransport=dojo.lang.firstValued(_f1["sendTransport"],djConfig["ioSendTransport"],false);
var _f2=dojo.lang.isFunction;
for(var x=0;x<dojo.io.hdlrFuncNames.length;x++){
var fn=dojo.io.hdlrFuncNames[x];
if(_f2(_f1[fn])){
continue;
}
if(_f2(_f1["handle"])){
_f1[fn]=_f1.handle;
}
}
dojo.lang.mixin(this,_f1);
}});
dojo.io.Error=function(msg,_f6,num){
this.message=msg;
this.type=_f6||"unknown";
this.number=num||0;
};
dojo.io.transports.addTransport=function(_f8){
this.push(_f8);
this[_f8]=dojo.io[_f8];
};
dojo.io.bind=function(_f9){
if(!(_f9 instanceof dojo.io.Request)){
try{
_f9=new dojo.io.Request(_f9);
}
catch(e){
dojo.debug(e);
}
}
var _fa="";
if(_f9["transport"]){
_fa=_f9["transport"];
if(!this[_fa]){
return _f9;
}
}else{
for(var x=0;x<dojo.io.transports.length;x++){
var tmp=dojo.io.transports[x];
if((this[tmp])&&(this[tmp].canHandle(_f9))){
_fa=tmp;
}
}
if(_fa==""){
return _f9;
}
}
this[_fa].bind(_f9);
_f9.bindSuccess=true;
return _f9;
};
dojo.io.queueBind=function(_fd){
if(!(_fd instanceof dojo.io.Request)){
try{
_fd=new dojo.io.Request(_fd);
}
catch(e){
dojo.debug(e);
}
}
var _fe=_fd.load;
_fd.load=function(){
dojo.io._queueBindInFlight=false;
var ret=_fe.apply(this,arguments);
dojo.io._dispatchNextQueueBind();
return ret;
};
var _100=_fd.error;
_fd.error=function(){
dojo.io._queueBindInFlight=false;
var ret=_100.apply(this,arguments);
dojo.io._dispatchNextQueueBind();
return ret;
};
dojo.io._bindQueue.push(_fd);
dojo.io._dispatchNextQueueBind();
return _fd;
};
dojo.io._dispatchNextQueueBind=function(){
if(!dojo.io._queueBindInFlight){
dojo.io._queueBindInFlight=true;
if(dojo.io._bindQueue.length>0){
dojo.io.bind(dojo.io._bindQueue.shift());
}else{
dojo.io._queueBindInFlight=false;
}
}
};
dojo.io._bindQueue=[];
dojo.io._queueBindInFlight=false;
dojo.io.argsFromMap=function(map,_103){
var _104=new Object();
var _105="";
var enc=/utf/i.test(_103||"")?encodeURIComponent:dojo.string.encodeAscii;
for(var x in map){
if(!_104[x]){
_105+=enc(x)+"="+enc(map[x])+"&";
}
}
return _105;
};
dojo.provide("dojo.lang.array");
dojo.require("dojo.lang.common");
dojo.lang.has=function(obj,name){
return (typeof obj[name]!=="undefined");
};
dojo.lang.isEmpty=function(obj){
if(dojo.lang.isObject(obj)){
var tmp={};
var _10c=0;
for(var x in obj){
if(obj[x]&&(!tmp[x])){
_10c++;
break;
}
}
return (_10c==0);
}else{
if(dojo.lang.isArrayLike(obj)||dojo.lang.isString(obj)){
return obj.length==0;
}
}
};
dojo.lang.forEach=function(arr,_10f,_110){
var _111=dojo.lang.isString(arr);
if(_111){
arr=arr.split("");
}
var il=arr.length;
for(var i=0;i<((_110)?il:arr.length);i++){
if(_10f(arr[i],i,arr)=="break"){
break;
}
}
};
dojo.lang.map=function(arr,obj,_116){
var _117=dojo.lang.isString(arr);
if(_117){
arr=arr.split("");
}
if(dojo.lang.isFunction(obj)&&(!_116)){
_116=obj;
obj=dj_global;
}else{
if(dojo.lang.isFunction(obj)&&_116){
var _118=obj;
obj=_116;
_116=_118;
}
}
if(Array.map){
var _119=Array.map(arr,_116,obj);
}else{
var _119=[];
for(var i=0;i<arr.length;++i){
_119.push(_116.call(obj,arr[i]));
}
}
if(_117){
return _119.join("");
}else{
return _119;
}
};
dojo.lang.every=function(arr,_11c,_11d){
var _11e=dojo.lang.isString(arr);
if(_11e){
arr=arr.split("");
}
if(Array.every){
return Array.every(arr,_11c,_11d);
}else{
if(!_11d){
if(arguments.length>=3){
dojo.raise("thisObject doesn't exist!");
}
_11d=dj_global;
}
for(var i=0;i<arr.length;i++){
if(!_11c.call(_11d,arr[i],i,arr)){
return false;
}
}
return true;
}
};
dojo.lang.some=function(arr,_121,_122){
var _123=dojo.lang.isString(arr);
if(_123){
arr=arr.split("");
}
if(Array.some){
return Array.some(arr,_121,_122);
}else{
if(!_122){
if(arguments.length>=3){
dojo.raise("thisObject doesn't exist!");
}
_122=dj_global;
}
for(var i=0;i<arr.length;i++){
if(_121.call(_122,arr[i],i,arr)){
return true;
}
}
return false;
}
};
dojo.lang.filter=function(arr,_126,_127){
var _128=dojo.lang.isString(arr);
if(_128){
arr=arr.split("");
}
if(Array.filter){
var _129=Array.filter(arr,_126,_127);
}else{
if(!_127){
if(arguments.length>=3){
dojo.raise("thisObject doesn't exist!");
}
_127=dj_global;
}
var _129=[];
for(var i=0;i<arr.length;i++){
if(_126.call(_127,arr[i],i,arr)){
_129.push(arr[i]);
}
}
}
if(_128){
return _129.join("");
}else{
return _129;
}
};
dojo.lang.unnest=function(){
var out=[];
for(var i=0;i<arguments.length;i++){
if(dojo.lang.isArrayLike(arguments[i])){
var add=dojo.lang.unnest.apply(this,arguments[i]);
out=out.concat(add);
}else{
out.push(arguments[i]);
}
}
return out;
};
dojo.lang.toArray=function(_12e,_12f){
var _130=[];
for(var i=_12f||0;i<_12e.length;i++){
_130.push(_12e[i]);
}
return _130;
};
dojo.provide("dojo.lang.func");
dojo.require("dojo.lang.common");
dojo.require("dojo.lang.type");
dojo.lang.hitch=function(_132,_133){
if(dojo.lang.isString(_133)){
var fcn=_132[_133];
}else{
var fcn=_133;
}
return function(){
return fcn.apply(_132,arguments);
};
};
dojo.lang.anonCtr=0;
dojo.lang.anon={};
dojo.lang.nameAnonFunc=function(_135,_136){
var nso=(_136||dojo.lang.anon);
if((dj_global["djConfig"])&&(djConfig["slowAnonFuncLookups"]==true)){
for(var x in nso){
if(nso[x]===_135){
return x;
}
}
}
var ret="__"+dojo.lang.anonCtr++;
while(typeof nso[ret]!="undefined"){
ret="__"+dojo.lang.anonCtr++;
}
nso[ret]=_135;
return ret;
};
dojo.lang.forward=function(_13a){
return function(){
return this[_13a].apply(this,arguments);
};
};
dojo.lang.curry=function(ns,func){
var _13d=[];
ns=ns||dj_global;
if(dojo.lang.isString(func)){
func=ns[func];
}
for(var x=2;x<arguments.length;x++){
_13d.push(arguments[x]);
}
var _13f=func.length-_13d.length;
function gather(_140,_141,_142){
var _143=_142;
var _144=_141.slice(0);
for(var x=0;x<_140.length;x++){
_144.push(_140[x]);
}
_142=_142-_140.length;
if(_142<=0){
var res=func.apply(ns,_144);
_142=_143;
return res;
}else{
return function(){
return gather(arguments,_144,_142);
};
}
}
return gather([],_13d,_13f);
};
dojo.lang.curryArguments=function(ns,func,args,_14a){
var _14b=[];
var x=_14a||0;
for(x=_14a;x<args.length;x++){
_14b.push(args[x]);
}
return dojo.lang.curry.apply(dojo.lang,[ns,func].concat(_14b));
};
dojo.lang.tryThese=function(){
for(var x=0;x<arguments.length;x++){
try{
if(typeof arguments[x]=="function"){
var ret=(arguments[x]());
if(ret){
return ret;
}
}
}
catch(e){
dojo.debug(e);
}
}
};
dojo.lang.delayThese=function(farr,cb,_151,_152){
if(!farr.length){
if(typeof _152=="function"){
_152();
}
return;
}
if((typeof _151=="undefined")&&(typeof cb=="number")){
_151=cb;
cb=function(){
};
}else{
if(!cb){
cb=function(){
};
if(!_151){
_151=0;
}
}
}
setTimeout(function(){
(farr.shift())();
cb();
dojo.lang.delayThese(farr,cb,_151,_152);
},_151);
};
dojo.provide("dojo.string.extras");
dojo.require("dojo.string.common");
dojo.require("dojo.lang");
dojo.string.paramString=function(str,_154,_155){
for(var name in _154){
var re=new RegExp("\\%\\{"+name+"\\}","g");
str=str.replace(re,_154[name]);
}
if(_155){
str=str.replace(/%\{([^\}\s]+)\}/g,"");
}
return str;
};
dojo.string.capitalize=function(str){
if(!dojo.lang.isString(str)){
return "";
}
if(arguments.length==0){
str=this;
}
var _159=str.split(" ");
var _15a="";
var len=_159.length;
for(var i=0;i<len;i++){
var word=_159[i];
word=word.charAt(0).toUpperCase()+word.substring(1,word.length);
_15a+=word;
if(i<len-1){
_15a+=" ";
}
}
return new String(_15a);
};
dojo.string.isBlank=function(str){
if(!dojo.lang.isString(str)){
return true;
}
return (dojo.string.trim(str).length==0);
};
dojo.string.encodeAscii=function(str){
if(!dojo.lang.isString(str)){
return str;
}
var ret="";
var _161=escape(str);
var _162,re=/%u([0-9A-F]{4})/i;
while((_162=_161.match(re))){
var num=Number("0x"+_162[1]);
var _164=escape("&#"+num+";");
ret+=_161.substring(0,_162.index)+_164;
_161=_161.substring(_162.index+_162[0].length);
}
ret+=_161.replace(/\+/g,"%2B");
return ret;
};
dojo.string.escape=function(type,str){
var args=[];
for(var i=1;i<arguments.length;i++){
args.push(arguments[i]);
}
switch(type.toLowerCase()){
case "xml":
case "html":
case "xhtml":
return dojo.string.escapeXml.apply(this,args);
case "sql":
return dojo.string.escapeSql.apply(this,args);
case "regexp":
case "regex":
return dojo.string.escapeRegExp.apply(this,args);
case "javascript":
case "jscript":
case "js":
return dojo.string.escapeJavaScript.apply(this,args);
case "ascii":
return dojo.string.encodeAscii.apply(this,args);
default:
return str;
}
};
dojo.string.escapeXml=function(str,_16a){
str=str.replace(/&/gm,"&amp;").replace(/</gm,"&lt;").replace(/>/gm,"&gt;").replace(/"/gm,"&quot;");
if(!_16a){
str=str.replace(/'/gm,"&#39;");
}
return str;
};
dojo.string.escapeSql=function(str){
return str.replace(/'/gm,"''");
};
dojo.string.escapeRegExp=function(str){
return str.replace(/\\/gm,"\\\\").replace(/([\f\b\n\t\r])/gm,"\\$1");
};
dojo.string.escapeJavaScript=function(str){
return str.replace(/(["'\f\b\n\t\r])/gm,"\\$1");
};
dojo.string.summary=function(str,len){
if(!len||str.length<=len){
return str;
}else{
return str.substring(0,len).replace(/\.+$/,"")+"...";
}
};
dojo.string.endsWith=function(str,end,_172){
if(_172){
str=str.toLowerCase();
end=end.toLowerCase();
}
return str.lastIndexOf(end)==str.length-end.length;
};
dojo.string.endsWithAny=function(str){
for(var i=1;i<arguments.length;i++){
if(dojo.string.endsWith(str,arguments[i])){
return true;
}
}
return false;
};
dojo.string.startsWith=function(str,_176,_177){
if(_177){
str=str.toLowerCase();
_176=_176.toLowerCase();
}
return str.indexOf(_176)==0;
};
dojo.string.startsWithAny=function(str){
for(var i=1;i<arguments.length;i++){
if(dojo.string.startsWith(str,arguments[i])){
return true;
}
}
return false;
};
dojo.string.has=function(str){
for(var i=1;i<arguments.length;i++){
if(str.indexOf(arguments[i]>-1)){
return true;
}
}
return false;
};
dojo.string.normalizeNewlines=function(text,_17d){
if(_17d=="\n"){
text=text.replace(/\r\n/g,"\n");
text=text.replace(/\r/g,"\n");
}else{
if(_17d=="\r"){
text=text.replace(/\r\n/g,"\r");
text=text.replace(/\n/g,"\r");
}else{
text=text.replace(/([^\r])\n/g,"$1\r\n");
text=text.replace(/\r([^\n])/g,"\r\n$1");
}
}
return text;
};
dojo.string.splitEscaped=function(str,_17f){
var _180=[];
for(var i=0,prevcomma=0;i<str.length;i++){
if(str.charAt(i)=="\\"){
i++;
continue;
}
if(str.charAt(i)==_17f){
_180.push(str.substring(prevcomma,i));
prevcomma=i+1;
}
}
_180.push(str.substr(prevcomma));
return _180;
};
dojo.provide("dojo.dom");
dojo.require("dojo.lang.array");
dojo.dom.ELEMENT_NODE=1;
dojo.dom.ATTRIBUTE_NODE=2;
dojo.dom.TEXT_NODE=3;
dojo.dom.CDATA_SECTION_NODE=4;
dojo.dom.ENTITY_REFERENCE_NODE=5;
dojo.dom.ENTITY_NODE=6;
dojo.dom.PROCESSING_INSTRUCTION_NODE=7;
dojo.dom.COMMENT_NODE=8;
dojo.dom.DOCUMENT_NODE=9;
dojo.dom.DOCUMENT_TYPE_NODE=10;
dojo.dom.DOCUMENT_FRAGMENT_NODE=11;
dojo.dom.NOTATION_NODE=12;
dojo.dom.dojoml="http://www.dojotoolkit.org/2004/dojoml";
dojo.dom.xmlns={svg:"http://www.w3.org/2000/svg",smil:"http://www.w3.org/2001/SMIL20/",mml:"http://www.w3.org/1998/Math/MathML",cml:"http://www.xml-cml.org",xlink:"http://www.w3.org/1999/xlink",xhtml:"http://www.w3.org/1999/xhtml",xul:"http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul",xbl:"http://www.mozilla.org/xbl",fo:"http://www.w3.org/1999/XSL/Format",xsl:"http://www.w3.org/1999/XSL/Transform",xslt:"http://www.w3.org/1999/XSL/Transform",xi:"http://www.w3.org/2001/XInclude",xforms:"http://www.w3.org/2002/01/xforms",saxon:"http://icl.com/saxon",xalan:"http://xml.apache.org/xslt",xsd:"http://www.w3.org/2001/XMLSchema",dt:"http://www.w3.org/2001/XMLSchema-datatypes",xsi:"http://www.w3.org/2001/XMLSchema-instance",rdf:"http://www.w3.org/1999/02/22-rdf-syntax-ns#",rdfs:"http://www.w3.org/2000/01/rdf-schema#",dc:"http://purl.org/dc/elements/1.1/",dcq:"http://purl.org/dc/qualifiers/1.0","soap-env":"http://schemas.xmlsoap.org/soap/envelope/",wsdl:"http://schemas.xmlsoap.org/wsdl/",AdobeExtensions:"http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/"};
dojo.dom.isNode=function(wh){
if(typeof Element=="object"){
try{
return wh instanceof Element;
}
catch(E){
}
}else{
return wh&&!isNaN(wh.nodeType);
}
};
dojo.dom.getTagName=function(node){
var _184=node.tagName;
if(_184.substr(0,5).toLowerCase()!="dojo:"){
if(_184.substr(0,4).toLowerCase()=="dojo"){
return "dojo:"+_184.substring(4).toLowerCase();
}
var djt=node.getAttribute("dojoType")||node.getAttribute("dojotype");
if(djt){
return "dojo:"+djt.toLowerCase();
}
if((node.getAttributeNS)&&(node.getAttributeNS(this.dojoml,"type"))){
return "dojo:"+node.getAttributeNS(this.dojoml,"type").toLowerCase();
}
try{
djt=node.getAttribute("dojo:type");
}
catch(e){
}
if(djt){
return "dojo:"+djt.toLowerCase();
}
if((!dj_global["djConfig"])||(!djConfig["ignoreClassNames"])){
var _186=node.className||node.getAttribute("class");
if((_186)&&(_186.indexOf)&&(_186.indexOf("dojo-")!=-1)){
var _187=_186.split(" ");
for(var x=0;x<_187.length;x++){
if((_187[x].length>5)&&(_187[x].indexOf("dojo-")>=0)){
return "dojo:"+_187[x].substr(5).toLowerCase();
}
}
}
}
}
return _184.toLowerCase();
};
dojo.dom.getUniqueId=function(){
do{
var id="dj_unique_"+(++arguments.callee._idIncrement);
}while(document.getElementById(id));
return id;
};
dojo.dom.getUniqueId._idIncrement=0;
dojo.dom.firstElement=dojo.dom.getFirstChildElement=function(_18a,_18b){
var node=_18a.firstChild;
while(node&&node.nodeType!=dojo.dom.ELEMENT_NODE){
node=node.nextSibling;
}
if(_18b&&node&&node.tagName&&node.tagName.toLowerCase()!=_18b.toLowerCase()){
node=dojo.dom.nextElement(node,_18b);
}
return node;
};
dojo.dom.lastElement=dojo.dom.getLastChildElement=function(_18d,_18e){
var node=_18d.lastChild;
while(node&&node.nodeType!=dojo.dom.ELEMENT_NODE){
node=node.previousSibling;
}
if(_18e&&node&&node.tagName&&node.tagName.toLowerCase()!=_18e.toLowerCase()){
node=dojo.dom.prevElement(node,_18e);
}
return node;
};
dojo.dom.nextElement=dojo.dom.getNextSiblingElement=function(node,_191){
if(!node){
return null;
}
do{
node=node.nextSibling;
}while(node&&node.nodeType!=dojo.dom.ELEMENT_NODE);
if(node&&_191&&_191.toLowerCase()!=node.tagName.toLowerCase()){
return dojo.dom.nextElement(node,_191);
}
return node;
};
dojo.dom.prevElement=dojo.dom.getPreviousSiblingElement=function(node,_193){
if(!node){
return null;
}
if(_193){
_193=_193.toLowerCase();
}
do{
node=node.previousSibling;
}while(node&&node.nodeType!=dojo.dom.ELEMENT_NODE);
if(node&&_193&&_193.toLowerCase()!=node.tagName.toLowerCase()){
return dojo.dom.prevElement(node,_193);
}
return node;
};
dojo.dom.moveChildren=function(_194,_195,trim){
var _197=0;
if(trim){
while(_194.hasChildNodes()&&_194.firstChild.nodeType==dojo.dom.TEXT_NODE){
_194.removeChild(_194.firstChild);
}
while(_194.hasChildNodes()&&_194.lastChild.nodeType==dojo.dom.TEXT_NODE){
_194.removeChild(_194.lastChild);
}
}
while(_194.hasChildNodes()){
_195.appendChild(_194.firstChild);
_197++;
}
return _197;
};
dojo.dom.copyChildren=function(_198,_199,trim){
var _19b=_198.cloneNode(true);
return this.moveChildren(_19b,_199,trim);
};
dojo.dom.removeChildren=function(node){
var _19d=node.childNodes.length;
while(node.hasChildNodes()){
node.removeChild(node.firstChild);
}
return _19d;
};
dojo.dom.replaceChildren=function(node,_19f){
dojo.dom.removeChildren(node);
node.appendChild(_19f);
};
dojo.dom.removeNode=function(node){
if(node&&node.parentNode){
return node.parentNode.removeChild(node);
}
};
dojo.dom.getAncestors=function(node,_1a2,_1a3){
var _1a4=[];
var _1a5=dojo.lang.isFunction(_1a2);
while(node){
if(!_1a5||_1a2(node)){
_1a4.push(node);
}
if(_1a3&&_1a4.length>0){
return _1a4[0];
}
node=node.parentNode;
}
if(_1a3){
return null;
}
return _1a4;
};
dojo.dom.getAncestorsByTag=function(node,tag,_1a8){
tag=tag.toLowerCase();
return dojo.dom.getAncestors(node,function(el){
return ((el.tagName)&&(el.tagName.toLowerCase()==tag));
},_1a8);
};
dojo.dom.getFirstAncestorByTag=function(node,tag){
return dojo.dom.getAncestorsByTag(node,tag,true);
};
dojo.dom.isDescendantOf=function(node,_1ad,_1ae){
if(_1ae&&node){
node=node.parentNode;
}
while(node){
if(node==_1ad){
return true;
}
node=node.parentNode;
}
return false;
};
dojo.dom.innerXML=function(node){
if(node.innerXML){
return node.innerXML;
}else{
if(typeof XMLSerializer!="undefined"){
return (new XMLSerializer()).serializeToString(node);
}
}
};
dojo.dom.createDocumentFromText=function(str,_1b1){
if(!_1b1){
_1b1="text/xml";
}
if(typeof DOMParser!="undefined"){
var _1b2=new DOMParser();
return _1b2.parseFromString(str,_1b1);
}else{
if(typeof ActiveXObject!="undefined"){
var _1b3=new ActiveXObject("Microsoft.XMLDOM");
if(_1b3){
_1b3.async=false;
_1b3.loadXML(str);
return _1b3;
}else{
dojo.debug("toXml didn't work?");
}
}else{
if(document.createElement){
var tmp=document.createElement("xml");
tmp.innerHTML=str;
if(document.implementation&&document.implementation.createDocument){
var _1b5=document.implementation.createDocument("foo","",null);
for(var i=0;i<tmp.childNodes.length;i++){
_1b5.importNode(tmp.childNodes.item(i),true);
}
return _1b5;
}
return tmp.document&&tmp.document.firstChild?tmp.document.firstChild:tmp;
}
}
}
return null;
};
dojo.dom.prependChild=function(node,_1b8){
if(_1b8.firstChild){
_1b8.insertBefore(node,_1b8.firstChild);
}else{
_1b8.appendChild(node);
}
return true;
};
dojo.dom.insertBefore=function(node,ref,_1bb){
if(_1bb!=true&&(node===ref||node.nextSibling===ref)){
return false;
}
var _1bc=ref.parentNode;
_1bc.insertBefore(node,ref);
return true;
};
dojo.dom.insertAfter=function(node,ref,_1bf){
var pn=ref.parentNode;
if(ref==pn.lastChild){
if((_1bf!=true)&&(node===ref)){
return false;
}
pn.appendChild(node);
}else{
return this.insertBefore(node,ref.nextSibling,_1bf);
}
return true;
};
dojo.dom.insertAtPosition=function(node,ref,_1c3){
if((!node)||(!ref)||(!_1c3)){
return false;
}
switch(_1c3.toLowerCase()){
case "before":
return dojo.dom.insertBefore(node,ref);
case "after":
return dojo.dom.insertAfter(node,ref);
case "first":
if(ref.firstChild){
return dojo.dom.insertBefore(node,ref.firstChild);
}else{
ref.appendChild(node);
return true;
}
break;
default:
ref.appendChild(node);
return true;
}
};
dojo.dom.insertAtIndex=function(node,_1c5,_1c6){
var _1c7=_1c5.childNodes;
if(!_1c7.length){
_1c5.appendChild(node);
return true;
}
var _1c8=null;
for(var i=0;i<_1c7.length;i++){
var _1ca=_1c7.item(i)["getAttribute"]?parseInt(_1c7.item(i).getAttribute("dojoinsertionindex")):-1;
if(_1ca<_1c6){
_1c8=_1c7.item(i);
}
}
if(_1c8){
return dojo.dom.insertAfter(node,_1c8);
}else{
return dojo.dom.insertBefore(node,_1c7.item(0));
}
};
dojo.dom.textContent=function(node,text){
if(text){
dojo.dom.replaceChildren(node,document.createTextNode(text));
return text;
}else{
var _1cd="";
if(node==null){
return _1cd;
}
for(var i=0;i<node.childNodes.length;i++){
switch(node.childNodes[i].nodeType){
case 1:
case 5:
_1cd+=dojo.dom.textContent(node.childNodes[i]);
break;
case 3:
case 2:
case 4:
_1cd+=node.childNodes[i].nodeValue;
break;
default:
break;
}
}
return _1cd;
}
};
dojo.dom.collectionToArray=function(_1cf){
dojo.deprecated("dojo.dom.collectionToArray","use dojo.lang.toArray instead");
return dojo.lang.toArray(_1cf);
};
dojo.dom.hasParent=function(node){
if(!node||!node.parentNode||(node.parentNode&&!node.parentNode.tagName)){
return false;
}
return true;
};
dojo.dom.isTag=function(node){
if(node&&node.tagName){
var arr=dojo.lang.toArray(arguments,1);
return arr[dojo.lang.find(node.tagName,arr)]||"";
}
return "";
};
dojo.provide("dojo.io.BrowserIO");
dojo.require("dojo.io");
dojo.require("dojo.lang.array");
dojo.require("dojo.lang.func");
dojo.require("dojo.string.extras");
dojo.require("dojo.dom");
try{
if((!djConfig["preventBackButtonFix"])&&(!dojo.hostenv.post_load_)){
document.write("");
}
}
catch(e){
}
dojo.io.checkChildrenForFile=function(node){
var _1d4=false;
var _1d5=node.getElementsByTagName("input");
dojo.lang.forEach(_1d5,function(_1d6){
if(_1d4){
return;
}
if(_1d6.getAttribute("type")=="file"){
_1d4=true;
}
});
return _1d4;
};
dojo.io.formHasFile=function(_1d7){
return dojo.io.checkChildrenForFile(_1d7);
};
dojo.io.formFilter=function(node){
var type=(node.type||"").toLowerCase();
return !node.disabled&&node.name&&!dojo.lang.inArray(type,["file","submit","image","reset","button"]);
};
dojo.io.encodeForm=function(_1da,_1db,_1dc){
if((!_1da)||(!_1da.tagName)||(!_1da.tagName.toLowerCase()=="form")){
dojo.raise("Attempted to encode a non-form element.");
}
if(!_1dc){
_1dc=dojo.io.formFilter;
}
var enc=/utf/i.test(_1db||"")?encodeURIComponent:dojo.string.encodeAscii;
var _1de=[];
for(var i=0;i<_1da.elements.length;i++){
var elm=_1da.elements[i];
if(!elm||elm.tagName.toLowerCase()=="fieldset"||!_1dc(elm)){
continue;
}
var name=enc(elm.name);
var type=elm.type.toLowerCase();
if(type=="select-multiple"){
for(var j=0;j<elm.options.length;j++){
if(elm.options[j].selected){
_1de.push(name+"="+enc(elm.options[j].value));
}
}
}else{
if(dojo.lang.inArray(type,["radio","checkbox"])){
if(elm.checked){
_1de.push(name+"="+enc(elm.value));
}
}else{
_1de.push(name+"="+enc(elm.value));
}
}
}
var _1e4=_1da.getElementsByTagName("input");
for(var i=0;i<_1e4.length;i++){
var _1e5=_1e4[i];
if(_1e5.type.toLowerCase()=="image"&&_1e5.form==_1da&&_1dc(_1e5)){
var name=enc(_1e5.name);
_1de.push(name+"="+enc(_1e5.value));
_1de.push(name+".x=0");
_1de.push(name+".y=0");
}
}
return _1de.join("&")+"&";
};
dojo.io.setIFrameSrc=function(_1e6,src,_1e8){
try{
var r=dojo.render.html;
if(!_1e8){
if(r.safari){
_1e6.location=src;
}else{
frames[_1e6.name].location=src;
}
}else{
var idoc;
if(r.ie){
idoc=_1e6.contentWindow.document;
}else{
if(r.moz){
idoc=_1e6.contentWindow;
}else{
if(r.safari){
idoc=_1e6.document;
}
}
}
idoc.location.replace(src);
}
}
catch(e){
dojo.debug(e);
dojo.debug("setIFrameSrc: "+e);
}
};
dojo.io.FormBind=function(args){
this.bindArgs={};
if(args&&args.formNode){
this.init(args);
}else{
if(args){
this.init({formNode:args});
}
}
};
dojo.lang.extend(dojo.io.FormBind,{form:null,bindArgs:null,clickedButton:null,init:function(args){
var form=dojo.byId(args.formNode);
if(!form||!form.tagName||form.tagName.toLowerCase()!="form"){
throw new Error("FormBind: Couldn't apply, invalid form");
}else{
if(this.form==form){
return;
}else{
if(this.form){
throw new Error("FormBind: Already applied to a form");
}
}
}
dojo.lang.mixin(this.bindArgs,args);
this.form=form;
this.connect(form,"onsubmit","submit");
for(var i=0;i<form.elements.length;i++){
var node=form.elements[i];
if(node&&node.type&&dojo.lang.inArray(node.type.toLowerCase(),["submit","button"])){
this.connect(node,"onclick","click");
}
}
var _1f0=form.getElementsByTagName("input");
for(var i=0;i<_1f0.length;i++){
var _1f1=_1f0[i];
if(_1f1.type.toLowerCase()=="image"&&_1f1.form==form){
this.connect(_1f1,"onclick","click");
}
}
},onSubmit:function(form){
return true;
},submit:function(e){
e.preventDefault();
if(this.onSubmit(this.form)){
dojo.io.bind(dojo.lang.mixin(this.bindArgs,{formFilter:dojo.lang.hitch(this,"formFilter")}));
}
},click:function(e){
var node=e.currentTarget;
if(node.disabled){
return;
}
this.clickedButton=node;
},formFilter:function(node){
var type=(node.type||"").toLowerCase();
var _1f8=false;
if(node.disabled||!node.name){
_1f8=false;
}else{
if(dojo.lang.inArray(type,["submit","button","image"])){
if(!this.clickedButton){
this.clickedButton=node;
}
_1f8=node==this.clickedButton;
}else{
_1f8=!dojo.lang.inArray(type,["file","submit","reset","button"]);
}
}
return _1f8;
},connect:function(_1f9,_1fa,_1fb){
if(dojo.evalObjPath("dojo.event.connect")){
dojo.event.connect(_1f9,_1fa,this,_1fb);
}else{
var fcn=dojo.lang.hitch(this,_1fb);
_1f9[_1fa]=function(e){
if(!e){
e=window.event;
}
if(!e.currentTarget){
e.currentTarget=e.srcElement;
}
if(!e.preventDefault){
e.preventDefault=function(){
window.event.returnValue=false;
};
}
fcn(e);
};
}
}});
dojo.io.XMLHTTPTransport=new function(){
var _1fe=this;
this.initialHref=window.location.href;
this.initialHash=window.location.hash;
this.moveForward=false;
var _1ff={};
this.useCache=false;
this.preventCache=false;
this.historyStack=[];
this.forwardStack=[];
this.historyIframe=null;
this.bookmarkAnchor=null;
this.locationTimer=null;
function getCacheKey(url,_201,_202){
return url+"|"+_201+"|"+_202.toLowerCase();
}
function addToCache(url,_204,_205,http){
_1ff[getCacheKey(url,_204,_205)]=http;
}
function getFromCache(url,_208,_209){
return _1ff[getCacheKey(url,_208,_209)];
}
this.clearCache=function(){
_1ff={};
};
function doLoad(_20a,http,url,_20d,_20e){
if((http.status==200)||(http.status==304)||(location.protocol=="file:"&&(http.status==0||http.status==undefined))){
var ret;
if(_20a.method.toLowerCase()=="head"){
var _210=http.getAllResponseHeaders();
ret={};
ret.toString=function(){
return _210;
};
var _211=_210.split(/[\r\n]+/g);
for(var i=0;i<_211.length;i++){
var pair=_211[i].match(/^([^:]+)\s*:\s*(.+)$/i);
if(pair){
ret[pair[1]]=pair[2];
}
}
}else{
if(_20a.mimetype=="text/javascript"){
try{
ret=dj_eval(http.responseText);
}
catch(e){
dojo.debug(e);
dojo.debug(http.responseText);
ret=null;
}
}else{
if(_20a.mimetype=="text/json"){
try{
ret=dj_eval("("+http.responseText+")");
}
catch(e){
dojo.debug(e);
dojo.debug(http.responseText);
ret=false;
}
}else{
if((_20a.mimetype=="application/xml")||(_20a.mimetype=="text/xml")){
ret=http.responseXML;
if(!ret||typeof ret=="string"){
ret=dojo.dom.createDocumentFromText(http.responseText);
}
}else{
ret=http.responseText;
}
}
}
}
if(_20e){
addToCache(url,_20d,_20a.method,http);
}
_20a[(typeof _20a.load=="function")?"load":"handle"]("load",ret,http);
}else{
var _214=new dojo.io.Error("XMLHttpTransport Error: "+http.status+" "+http.statusText);
_20a[(typeof _20a.error=="function")?"error":"handle"]("error",_214,http);
}
}
function setHeaders(http,_216){
if(_216["headers"]){
for(var _217 in _216["headers"]){
if(_217.toLowerCase()=="content-type"&&!_216["contentType"]){
_216["contentType"]=_216["headers"][_217];
}else{
http.setRequestHeader(_217,_216["headers"][_217]);
}
}
}
}
this.addToHistory=function(args){
var _219=args["back"]||args["backButton"]||args["handle"];
var hash=null;
if(!this.historyIframe){
this.historyIframe=window.frames["djhistory"];
}
if(!this.bookmarkAnchor){
this.bookmarkAnchor=document.createElement("a");
(document.body||document.getElementsByTagName("body")[0]).appendChild(this.bookmarkAnchor);
this.bookmarkAnchor.style.display="none";
}
if((!args["changeUrl"])||(dojo.render.html.ie)){
var url=dojo.hostenv.getBaseScriptUri()+"iframe_history.html?"+(new Date()).getTime();
this.moveForward=true;
dojo.io.setIFrameSrc(this.historyIframe,url,false);
}
if(args["changeUrl"]){
hash="#"+((args["changeUrl"]!==true)?args["changeUrl"]:(new Date()).getTime());
setTimeout("window.location.href = '"+hash+"';",1);
this.bookmarkAnchor.href=hash;
if(dojo.render.html.ie){
var _21c=_219;
var lh=null;
var hsl=this.historyStack.length-1;
if(hsl>=0){
while(!this.historyStack[hsl]["urlHash"]){
hsl--;
}
lh=this.historyStack[hsl]["urlHash"];
}
if(lh){
_219=function(){
if(window.location.hash!=""){
setTimeout("window.location.href = '"+lh+"';",1);
}
_21c();
};
}
this.forwardStack=[];
var _21f=args["forward"]||args["forwardButton"];
var tfw=function(){
if(window.location.hash!=""){
window.location.href=hash;
}
if(_21f){
_21f();
}
};
if(args["forward"]){
args.forward=tfw;
}else{
if(args["forwardButton"]){
args.forwardButton=tfw;
}
}
}else{
if(dojo.render.html.moz){
if(!this.locationTimer){
this.locationTimer=setInterval("dojo.io.XMLHTTPTransport.checkLocation();",200);
}
}
}
}
this.historyStack.push({"url":url,"callback":_219,"kwArgs":args,"urlHash":hash});
};
this.checkLocation=function(){
var hsl=this.historyStack.length;
if((window.location.hash==this.initialHash)||(window.location.href==this.initialHref)&&(hsl==1)){
this.handleBackButton();
return;
}
if(this.forwardStack.length>0){
if(this.forwardStack[this.forwardStack.length-1].urlHash==window.location.hash){
this.handleForwardButton();
return;
}
}
if((hsl>=2)&&(this.historyStack[hsl-2])){
if(this.historyStack[hsl-2].urlHash==window.location.hash){
this.handleBackButton();
return;
}
}
};
this.iframeLoaded=function(evt,_223){
var isp=_223.href.split("?");
if(isp.length<2){
if(this.historyStack.length==1){
this.handleBackButton();
}
return;
}
var _225=isp[1];
if(this.moveForward){
this.moveForward=false;
return;
}
var last=this.historyStack.pop();
if(!last){
if(this.forwardStack.length>0){
var next=this.forwardStack[this.forwardStack.length-1];
if(_225==next.url.split("?")[1]){
this.handleForwardButton();
}
}
return;
}
this.historyStack.push(last);
if(this.historyStack.length>=2){
if(isp[1]==this.historyStack[this.historyStack.length-2].url.split("?")[1]){
this.handleBackButton();
}
}else{
this.handleBackButton();
}
};
this.handleBackButton=function(){
var last=this.historyStack.pop();
if(!last){
return;
}
if(last["callback"]){
last.callback();
}else{
if(last.kwArgs["backButton"]){
last.kwArgs["backButton"]();
}else{
if(last.kwArgs["back"]){
last.kwArgs["back"]();
}else{
if(last.kwArgs["handle"]){
last.kwArgs.handle("back");
}
}
}
}
this.forwardStack.push(last);
};
this.handleForwardButton=function(){
var last=this.forwardStack.pop();
if(!last){
return;
}
if(last.kwArgs["forward"]){
last.kwArgs.forward();
}else{
if(last.kwArgs["forwardButton"]){
last.kwArgs.forwardButton();
}else{
if(last.kwArgs["handle"]){
last.kwArgs.handle("forward");
}
}
}
this.historyStack.push(last);
};
this.inFlight=[];
this.inFlightTimer=null;
this.startWatchingInFlight=function(){
if(!this.inFlightTimer){
this.inFlightTimer=setInterval("dojo.io.XMLHTTPTransport.watchInFlight();",10);
}
};
this.watchInFlight=function(){
for(var x=this.inFlight.length-1;x>=0;x--){
var tif=this.inFlight[x];
if(!tif){
this.inFlight.splice(x,1);
continue;
}
if(4==tif.http.readyState){
this.inFlight.splice(x,1);
doLoad(tif.req,tif.http,tif.url,tif.query,tif.useCache);
if(this.inFlight.length==0){
clearInterval(this.inFlightTimer);
this.inFlightTimer=null;
}
}
}
};
var _22c=dojo.hostenv.getXmlhttpObject()?true:false;
this.canHandle=function(_22d){
return _22c&&dojo.lang.inArray((_22d["mimetype"].toLowerCase()||""),["text/plain","text/html","application/xml","text/xml","text/javascript","text/json"])&&dojo.lang.inArray(_22d["method"].toLowerCase(),["post","get","head"])&&!(_22d["formNode"]&&dojo.io.formHasFile(_22d["formNode"]));
};
this.multipartBoundary="45309FFF-BD65-4d50-99C9-36986896A96F";
this.bind=function(_22e){
if(!_22e["url"]){
if(!_22e["formNode"]&&(_22e["backButton"]||_22e["back"]||_22e["changeUrl"]||_22e["watchForURL"])&&(!djConfig.preventBackButtonFix)){
this.addToHistory(_22e);
return true;
}
}
var url=_22e.url;
var _230="";
if(_22e["formNode"]){
var ta=_22e.formNode.getAttribute("action");
if((ta)&&(!_22e["url"])){
url=ta;
}
var tp=_22e.formNode.getAttribute("method");
if((tp)&&(!_22e["method"])){
_22e.method=tp;
}
_230+=dojo.io.encodeForm(_22e.formNode,_22e.encoding,_22e["formFilter"]);
}
if(url.indexOf("#")>-1){
dojo.debug("Warning: dojo.io.bind: stripping hash values from url:",url);
url=url.split("#")[0];
}
if(_22e["file"]){
_22e.method="post";
}
if(!_22e["method"]){
_22e.method="get";
}
if(_22e.method.toLowerCase()=="get"){
_22e.multipart=false;
}else{
if(_22e["file"]){
_22e.multipart=true;
}else{
if(!_22e["multipart"]){
_22e.multipart=false;
}
}
}
if(_22e["backButton"]||_22e["back"]||_22e["changeUrl"]){
this.addToHistory(_22e);
}
var _233=_22e["content"]||{};
if(_22e.sendTransport){
_233["dojo.transport"]="xmlhttp";
}
do{
if(_22e.postContent){
_230=_22e.postContent;
break;
}
if(_233){
_230+=dojo.io.argsFromMap(_233,_22e.encoding);
}
if(_22e.method.toLowerCase()=="get"||!_22e.multipart){
break;
}
var t=[];
if(_230.length){
var q=_230.split("&");
for(var i=0;i<q.length;++i){
if(q[i].length){
var p=q[i].split("=");
t.push("--"+this.multipartBoundary,"Content-Disposition: form-data; name=\""+p[0]+"\"","",p[1]);
}
}
}
if(_22e.file){
if(dojo.lang.isArray(_22e.file)){
for(var i=0;i<_22e.file.length;++i){
var o=_22e.file[i];
t.push("--"+this.multipartBoundary,"Content-Disposition: form-data; name=\""+o.name+"\"; filename=\""+("fileName" in o?o.fileName:o.name)+"\"","Content-Type: "+("contentType" in o?o.contentType:"application/octet-stream"),"",o.content);
}
}else{
var o=_22e.file;
t.push("--"+this.multipartBoundary,"Content-Disposition: form-data; name=\""+o.name+"\"; filename=\""+("fileName" in o?o.fileName:o.name)+"\"","Content-Type: "+("contentType" in o?o.contentType:"application/octet-stream"),"",o.content);
}
}
if(t.length){
t.push("--"+this.multipartBoundary+"--","");
_230=t.join("\r\n");
}
}while(false);
var _239=_22e["sync"]?false:true;
var _23a=_22e["preventCache"]||(this.preventCache==true&&_22e["preventCache"]!=false);
var _23b=_22e["useCache"]==true||(this.useCache==true&&_22e["useCache"]!=false);
if(!_23a&&_23b){
var _23c=getFromCache(url,_230,_22e.method);
if(_23c){
doLoad(_22e,_23c,url,_230,false);
return;
}
}
var http=dojo.hostenv.getXmlhttpObject(_22e);
var _23e=false;
if(_239){
this.inFlight.push({"req":_22e,"http":http,"url":url,"query":_230,"useCache":_23b});
this.startWatchingInFlight();
}
if(_22e.method.toLowerCase()=="post"){
http.open("POST",url,_239);
setHeaders(http,_22e);
http.setRequestHeader("Content-Type",_22e.multipart?("multipart/form-data; boundary="+this.multipartBoundary):(_22e.contentType||"application/x-www-form-urlencoded"));
http.send(_230);
}else{
var _23f=url;
if(_230!=""){
_23f+=(_23f.indexOf("?")>-1?"&":"?")+_230;
}
if(_23a){
_23f+=(dojo.string.endsWithAny(_23f,"?","&")?"":(_23f.indexOf("?")>-1?"&":"?"))+"dojo.preventCache="+new Date().valueOf();
}
http.open(_22e.method.toUpperCase(),_23f,_239);
setHeaders(http,_22e);
http.send(null);
}
if(!_239){
doLoad(_22e,http,url,_230,_23b);
}
_22e.abort=function(){
return http.abort();
};
return;
};
dojo.io.transports.addTransport("XMLHTTPTransport");
};
dojo.provide("dojo.io.cookie");
dojo.io.cookie.setCookie=function(name,_241,days,path,_244,_245){
var _246=-1;
if(typeof days=="number"&&days>=0){
var d=new Date();
d.setTime(d.getTime()+(days*24*60*60*1000));
_246=d.toGMTString();
}
_241=escape(_241);
document.cookie=name+"="+_241+";"+(_246!=-1?" expires="+_246+";":"")+(path?"path="+path:"")+(_244?"; domain="+_244:"")+(_245?"; secure":"");
};
dojo.io.cookie.set=dojo.io.cookie.setCookie;
dojo.io.cookie.getCookie=function(name){
var idx=document.cookie.indexOf(name+"=");
if(idx==-1){
return null;
}
value=document.cookie.substring(idx+name.length+1);
var end=value.indexOf(";");
if(end==-1){
end=value.length;
}
value=value.substring(0,end);
value=unescape(value);
return value;
};
dojo.io.cookie.get=dojo.io.cookie.getCookie;
dojo.io.cookie.deleteCookie=function(name){
dojo.io.cookie.setCookie(name,"-",0);
};
dojo.io.cookie.setObjectCookie=function(name,obj,days,path,_250,_251,_252){
if(arguments.length==5){
_252=_250;
_250=null;
_251=null;
}
var _253=[],cookie,value="";
if(!_252){
cookie=dojo.io.cookie.getObjectCookie(name);
}
if(days>=0){
if(!cookie){
cookie={};
}
for(var prop in obj){
if(prop==null){
delete cookie[prop];
}else{
if(typeof obj[prop]=="string"||typeof obj[prop]=="number"){
cookie[prop]=obj[prop];
}
}
}
prop=null;
for(var prop in cookie){
_253.push(escape(prop)+"="+escape(cookie[prop]));
}
value=_253.join("&");
}
dojo.io.cookie.setCookie(name,value,days,path,_250,_251);
};
dojo.io.cookie.getObjectCookie=function(name){
var _256=null,cookie=dojo.io.cookie.getCookie(name);
if(cookie){
_256={};
var _257=cookie.split("&");
for(var i=0;i<_257.length;i++){
var pair=_257[i].split("=");
var _25a=pair[1];
if(isNaN(_25a)){
_25a=unescape(pair[1]);
}
_256[unescape(pair[0])]=_25a;
}
}
return _256;
};
dojo.io.cookie.isSupported=function(){
if(typeof navigator.cookieEnabled!="boolean"){
dojo.io.cookie.setCookie("__TestingYourBrowserForCookieSupport__","CookiesAllowed",90,null);
var _25b=dojo.io.cookie.getCookie("__TestingYourBrowserForCookieSupport__");
navigator.cookieEnabled=(_25b=="CookiesAllowed");
if(navigator.cookieEnabled){
this.deleteCookie("__TestingYourBrowserForCookieSupport__");
}
}
return navigator.cookieEnabled;
};
if(!dojo.io.cookies){
dojo.io.cookies=dojo.io.cookie;
}
dojo.hostenv.conditionalLoadModule({common:["dojo.io"],rhino:["dojo.io.RhinoIO"],browser:["dojo.io.BrowserIO","dojo.io.cookie"],dashboard:["dojo.io.BrowserIO","dojo.io.cookie"]});
dojo.hostenv.moduleLoaded("dojo.io.*");
dojo.provide("turbo.lib.json_rpc");
dojo.require("turbo.lib.json");
dojo.require("dojo.io.*");
remoteObject=function(_25c,_25d){
this.method="post";
this.contentType="application/x-www-form-urlencoded; charset=utf-8";
this.content="";
this.crypt=false;
this.encrypt=function(){
};
this.decrypt=function(){
};
this.receive=function(){
if(this.content){
try{
eval("this.content="+this.content);
if(this.crypt){
this.decrypt();
}
this.content.trip=(new Date().getTime()-this.content.id)/1000;
}
catch(e){
dojo.debug("eval failed: ["+this.content+"]");
}
}
};
this.syncRpc=function(){
var self=this;
if(!dojo.io.bind({url:this.server,method:this.method,contentType:this.contentType,postContent:this.content,load:function(evt,data){
self.content=data;
},error:function(){
self.content=false;
},sync:true})){
return false;
}
this.receive();
return this.content;
};
this.asyncRpc=function(_261){
var self=this;
return dojo.io.bind({url:this.server,method:this.method,contentType:this.contentType,postContent:this.content,load:function(evt,data){
self.content=data;
self.receive();
_261(self.content);
},error:function(){
_261(null);
}});
};
this.rpc=function(_265,_266,_267){
this.content=JSON.stringify({method:_265,arguments:_266,id:new Date().getTime()});
if(this.crypt){
this.encrypt();
}
return (_267?this.asyncRpc(_267):this.syncRpc());
};
this.discover=function(){
var _268=this.rpc("discover");
if(!_268){
alert("Could not contact service provider "+this.server+"");
}else{
if(!_268.error){
this.addMethods(_268.result);
}else{
turbo.debug(_268.error);
}
this.discoverTime=_268.trip;
}
};
this.addMethod=function(_269){
if(!this[_269]){
this[_269]=function(){
var c=arguments.length;
var _26b=(c&&(typeof (arguments[c-1])=="function")?arguments[--c]:null);
var args=new Array(c);
for(var i=0;i<c;i++){
args[i]=arguments[i];
}
return this.rpc(_269,args,_26b);
};
}
};
this.addMethods=function(_26e){
for(var i in _26e){
this.addMethod(_26e[i]);
}
};
this.server=_25c;
if(_25d){
this.addMethods(_25d);
}else{
this.discover();
}
};
dojo.provide("dojo.math");
dojo.math.degToRad=function(x){
return (x*Math.PI)/180;
};
dojo.math.radToDeg=function(x){
return (x*180)/Math.PI;
};
dojo.math.factorial=function(n){
if(n<1){
return 0;
}
var _273=1;
for(var i=1;i<=n;i++){
_273*=i;
}
return _273;
};
dojo.math.permutations=function(n,k){
if(n==0||k==0){
return 1;
}
return (dojo.math.factorial(n)/dojo.math.factorial(n-k));
};
dojo.math.combinations=function(n,r){
if(n==0||r==0){
return 1;
}
return (dojo.math.factorial(n)/(dojo.math.factorial(n-r)*dojo.math.factorial(r)));
};
dojo.math.bernstein=function(t,n,i){
return (dojo.math.combinations(n,i)*Math.pow(t,i)*Math.pow(1-t,n-i));
};
dojo.math.gaussianRandom=function(){
var k=2;
do{
var i=2*Math.random()-1;
var j=2*Math.random()-1;
k=i*i+j*j;
}while(k>=1);
k=Math.sqrt((-2*Math.log(k))/k);
return i*k;
};
dojo.math.mean=function(){
var _27f=dojo.lang.isArray(arguments[0])?arguments[0]:arguments;
var mean=0;
for(var i=0;i<_27f.length;i++){
mean+=_27f[i];
}
return mean/_27f.length;
};
dojo.math.round=function(_282,_283){
if(!_283){
var _284=1;
}else{
var _284=Math.pow(10,_283);
}
return Math.round(_282*_284)/_284;
};
dojo.math.sd=function(){
var _285=dojo.lang.isArray(arguments[0])?arguments[0]:arguments;
return Math.sqrt(dojo.math.variance(_285));
};
dojo.math.variance=function(){
var _286=dojo.lang.isArray(arguments[0])?arguments[0]:arguments;
var mean=0,squares=0;
for(var i=0;i<_286.length;i++){
mean+=_286[i];
squares+=Math.pow(_286[i],2);
}
return (squares/_286.length)-Math.pow(mean/_286.length,2);
};
dojo.math.range=function(a,b,step){
if(arguments.length<2){
b=a;
a=0;
}
if(arguments.length<3){
step=1;
}
var _28c=[];
if(step>0){
for(var i=a;i<b;i+=step){
_28c.push(i);
}
}else{
if(step<0){
for(var i=a;i>b;i+=step){
_28c.push(i);
}
}else{
throw new Error("dojo.math.range: step must be non-zero");
}
}
return _28c;
};
dojo.provide("dojo.graphics.color");
dojo.require("dojo.lang.array");
dojo.require("dojo.math");
dojo.graphics.color.Color=function(r,g,b,a){
if(dojo.lang.isArray(r)){
this.r=r[0];
this.g=r[1];
this.b=r[2];
this.a=r[3]||1;
}else{
if(dojo.lang.isString(r)){
var rgb=dojo.graphics.color.extractRGB(r);
this.r=rgb[0];
this.g=rgb[1];
this.b=rgb[2];
this.a=g||1;
}else{
if(r instanceof dojo.graphics.color.Color){
this.r=r.r;
this.b=r.b;
this.g=r.g;
this.a=r.a;
}else{
this.r=r;
this.g=g;
this.b=b;
this.a=a;
}
}
}
};
dojo.graphics.color.Color.fromArray=function(arr){
return new dojo.graphics.color.Color(arr[0],arr[1],arr[2],arr[3]);
};
dojo.lang.extend(dojo.graphics.color.Color,{toRgb:function(_294){
if(_294){
return this.toRgba();
}else{
return [this.r,this.g,this.b];
}
},toRgba:function(){
return [this.r,this.g,this.b,this.a];
},toHex:function(){
return dojo.graphics.color.rgb2hex(this.toRgb());
},toCss:function(){
return "rgb("+this.toRgb().join()+")";
},toString:function(){
return this.toHex();
},toHsv:function(){
return dojo.graphics.color.rgb2hsv(this.toRgb());
},toHsl:function(){
return dojo.graphics.color.rgb2hsl(this.toRgb());
},blend:function(_295,_296){
return dojo.graphics.color.blend(this.toRgb(),new Color(_295).toRgb(),_296);
}});
dojo.graphics.color.named={white:[255,255,255],black:[0,0,0],red:[255,0,0],green:[0,255,0],blue:[0,0,255],navy:[0,0,128],gray:[128,128,128],silver:[192,192,192]};
dojo.graphics.color.blend=function(a,b,_299){
if(typeof a=="string"){
return dojo.graphics.color.blendHex(a,b,_299);
}
if(!_299){
_299=0;
}else{
if(_299>1){
_299=1;
}else{
if(_299<-1){
_299=-1;
}
}
}
var c=new Array(3);
for(var i=0;i<3;i++){
var half=Math.abs(a[i]-b[i])/2;
c[i]=Math.floor(Math.min(a[i],b[i])+half+(half*_299));
}
return c;
};
dojo.graphics.color.blendHex=function(a,b,_29f){
return dojo.graphics.color.rgb2hex(dojo.graphics.color.blend(dojo.graphics.color.hex2rgb(a),dojo.graphics.color.hex2rgb(b),_29f));
};
dojo.graphics.color.extractRGB=function(_2a0){
var hex="0123456789abcdef";
_2a0=_2a0.toLowerCase();
if(_2a0.indexOf("rgb")==0){
var _2a2=_2a0.match(/rgba*\((\d+), *(\d+), *(\d+)/i);
var ret=_2a2.splice(1,3);
return ret;
}else{
var _2a4=dojo.graphics.color.hex2rgb(_2a0);
if(_2a4){
return _2a4;
}else{
return dojo.graphics.color.named[_2a0]||[255,255,255];
}
}
};
dojo.graphics.color.hex2rgb=function(hex){
var _2a6="0123456789ABCDEF";
var rgb=new Array(3);
if(hex.indexOf("#")==0){
hex=hex.substring(1);
}
hex=hex.toUpperCase();
if(hex.replace(new RegExp("["+_2a6+"]","g"),"")!=""){
return null;
}
if(hex.length==3){
rgb[0]=hex.charAt(0)+hex.charAt(0);
rgb[1]=hex.charAt(1)+hex.charAt(1);
rgb[2]=hex.charAt(2)+hex.charAt(2);
}else{
rgb[0]=hex.substring(0,2);
rgb[1]=hex.substring(2,4);
rgb[2]=hex.substring(4);
}
for(var i=0;i<rgb.length;i++){
rgb[i]=_2a6.indexOf(rgb[i].charAt(0))*16+_2a6.indexOf(rgb[i].charAt(1));
}
return rgb;
};
dojo.graphics.color.rgb2hex=function(r,g,b){
if(dojo.lang.isArray(r)){
g=r[1]||0;
b=r[2]||0;
r=r[0]||0;
}
function pad(x){
while(x.length<2){
x="0"+x;
}
return x;
}
var ret=dojo.lang.map([r,g,b],function(x){
var s=x.toString(16);
while(s.length<2){
s="0"+s;
}
return s;
});
ret.unshift("#");
return ret.join("");
};
dojo.graphics.color.rgb2hsv=function(r,g,b){
if(dojo.lang.isArray(r)){
b=r[2]||0;
g=r[1]||0;
r=r[0]||0;
}
var h=null;
var s=null;
var v=null;
var min=Math.min(r,g,b);
v=Math.max(r,g,b);
var _2b7=v-min;
s=(v==0)?0:_2b7/v;
if(s==0){
h=0;
}else{
if(r==v){
h=60*(g-b)/_2b7;
}else{
if(g==v){
h=120+60*(b-r)/_2b7;
}else{
if(b==v){
h=240+60*(r-g)/_2b7;
}
}
}
if(h<0){
h+=360;
}
}
h=(h==0)?360:Math.ceil((h/360)*255);
s=Math.ceil(s*255);
return [h,s,v];
};
dojo.graphics.color.hsv2rgb=function(h,s,v){
if(dojo.lang.isArray(h)){
v=h[2]||0;
s=h[1]||0;
h=h[0]||0;
}
h=(h/255)*360;
if(h==360){
h=0;
}
s=s/255;
v=v/255;
var r=null;
var g=null;
var b=null;
if(s==0){
r=v;
g=v;
b=v;
}else{
var _2be=h/60;
var i=Math.floor(_2be);
var f=_2be-i;
var p=v*(1-s);
var q=v*(1-(s*f));
var t=v*(1-(s*(1-f)));
switch(i){
case 0:
r=v;
g=t;
b=p;
break;
case 1:
r=q;
g=v;
b=p;
break;
case 2:
r=p;
g=v;
b=t;
break;
case 3:
r=p;
g=q;
b=v;
break;
case 4:
r=t;
g=p;
b=v;
break;
case 5:
r=v;
g=p;
b=q;
break;
}
}
r=Math.ceil(r*255);
g=Math.ceil(g*255);
b=Math.ceil(b*255);
return [r,g,b];
};
dojo.graphics.color.rgb2hsl=function(r,g,b){
if(dojo.lang.isArray(r)){
b=r[2]||0;
g=r[1]||0;
r=r[0]||0;
}
r/=255;
g/=255;
b/=255;
var h=null;
var s=null;
var l=null;
var min=Math.min(r,g,b);
var max=Math.max(r,g,b);
var _2cc=max-min;
l=(min+max)/2;
s=0;
if((l>0)&&(l<1)){
s=_2cc/((l<0.5)?(2*l):(2-2*l));
}
h=0;
if(_2cc>0){
if((max==r)&&(max!=g)){
h+=(g-b)/_2cc;
}
if((max==g)&&(max!=b)){
h+=(2+(b-r)/_2cc);
}
if((max==b)&&(max!=r)){
h+=(4+(r-g)/_2cc);
}
h*=60;
}
h=(h==0)?360:Math.ceil((h/360)*255);
s=Math.ceil(s*255);
l=Math.ceil(l*255);
return [h,s,l];
};
dojo.graphics.color.hsl2rgb=function(h,s,l){
if(dojo.lang.isArray(h)){
l=h[2]||0;
s=h[1]||0;
h=h[0]||0;
}
h=(h/255)*360;
if(h==360){
h=0;
}
s=s/255;
l=l/255;
while(h<0){
h+=360;
}
while(h>360){
h-=360;
}
if(h<120){
r=(120-h)/60;
g=h/60;
b=0;
}else{
if(h<240){
r=0;
g=(240-h)/60;
b=(h-120)/60;
}else{
r=(h-240)/60;
g=0;
b=(360-h)/60;
}
}
r=Math.min(r,1);
g=Math.min(g,1);
b=Math.min(b,1);
r=2*s*r+(1-s);
g=2*s*g+(1-s);
b=2*s*b+(1-s);
if(l<0.5){
r=l*r;
g=l*g;
b=l*b;
}else{
r=(1-l)*r+2*l-1;
g=(1-l)*g+2*l-1;
b=(1-l)*b+2*l-1;
}
r=Math.ceil(r*255);
g=Math.ceil(g*255);
b=Math.ceil(b*255);
return [r,g,b];
};
dojo.graphics.color.hsl2hex=function(h,s,l){
var rgb=dojo.graphics.color.hsl2rgb(h,s,l);
return dojo.graphics.color.rgb2hex(rgb[0],rgb[1],rgb[2]);
};
dojo.graphics.color.hex2hsl=function(hex){
var rgb=dojo.graphics.color.hex2rgb(hex);
return dojo.graphics.color.rgb2hsl(rgb[0],rgb[1],rgb[2]);
};
dojo.provide("dojo.style");
dojo.require("dojo.graphics.color");
dojo.style.boxSizing={marginBox:"margin-box",borderBox:"border-box",paddingBox:"padding-box",contentBox:"content-box"};
dojo.style.getBoxSizing=function(node){
node=dojo.byId(node);
if(dojo.render.html.ie||dojo.render.html.opera){
var cm=document["compatMode"];
if(cm=="BackCompat"||cm=="QuirksMode"){
return dojo.style.boxSizing.borderBox;
}else{
return dojo.style.boxSizing.contentBox;
}
}else{
if(arguments.length==0){
node=document.documentElement;
}
var _2d8=dojo.style.getStyle(node,"-moz-box-sizing");
if(!_2d8){
_2d8=dojo.style.getStyle(node,"box-sizing");
}
return (_2d8?_2d8:dojo.style.boxSizing.contentBox);
}
};
dojo.style.isBorderBox=function(node){
return (dojo.style.getBoxSizing(node)==dojo.style.boxSizing.borderBox);
};
dojo.style.getUnitValue=function(node,_2db,_2dc){
node=dojo.byId(node);
var _2dd={value:0,units:"px"};
var s=dojo.style.getComputedStyle(node,_2db);
if(s==null||s==""||(s=="auto"&&_2dc)){
return _2dd;
}
if(dojo.lang.isUndefined(s)){
_2dd.value=NaN;
}else{
var _2df=s.match(/(\-?[\d.]+)([a-z%]*)/i);
if(!_2df){
_2dd.value=NaN;
}else{
_2dd.value=Number(_2df[1]);
_2dd.units=_2df[2].toLowerCase();
}
}
return _2dd;
};
dojo.style.getPixelValue=function(node,_2e1,_2e2){
node=dojo.byId(node);
var _2e3=dojo.style.getUnitValue(node,_2e1,_2e2);
if(isNaN(_2e3.value)){
return 0;
}
if((_2e3.value)&&(_2e3.units!="px")){
return NaN;
}
return _2e3.value;
};
dojo.style.getNumericStyle=dojo.style.getPixelValue;
dojo.style.isPositionAbsolute=function(node){
node=dojo.byId(node);
return (dojo.style.getComputedStyle(node,"position")=="absolute");
};
dojo.style.getMarginWidth=function(node){
node=dojo.byId(node);
var _2e6=dojo.style.isPositionAbsolute(node);
var left=dojo.style.getPixelValue(node,"margin-left",_2e6);
var _2e8=dojo.style.getPixelValue(node,"margin-right",_2e6);
return left+_2e8;
};
dojo.style.getBorderWidth=function(node){
node=dojo.byId(node);
var left=(dojo.style.getStyle(node,"border-left-style")=="none"?0:dojo.style.getPixelValue(node,"border-left-width"));
var _2eb=(dojo.style.getStyle(node,"border-right-style")=="none"?0:dojo.style.getPixelValue(node,"border-right-width"));
return left+_2eb;
};
dojo.style.getPaddingWidth=function(node){
node=dojo.byId(node);
var left=dojo.style.getPixelValue(node,"padding-left",true);
var _2ee=dojo.style.getPixelValue(node,"padding-right",true);
return left+_2ee;
};
dojo.style.getContentWidth=function(node){
node=dojo.byId(node);
return node.offsetWidth-dojo.style.getPaddingWidth(node)-dojo.style.getBorderWidth(node);
};
dojo.style.getInnerWidth=function(node){
node=dojo.byId(node);
return node.offsetWidth;
};
dojo.style.getOuterWidth=function(node){
node=dojo.byId(node);
return dojo.style.getInnerWidth(node)+dojo.style.getMarginWidth(node);
};
dojo.style.setOuterWidth=function(node,_2f3){
node=dojo.byId(node);
if(!dojo.style.isBorderBox(node)){
_2f3-=dojo.style.getPaddingWidth(node)+dojo.style.getBorderWidth(node);
}
_2f3-=dojo.style.getMarginWidth(node);
if(!isNaN(_2f3)&&_2f3>0){
node.style.width=_2f3+"px";
return true;
}else{
return false;
}
};
dojo.style.getContentBoxWidth=dojo.style.getContentWidth;
dojo.style.getBorderBoxWidth=dojo.style.getInnerWidth;
dojo.style.getMarginBoxWidth=dojo.style.getOuterWidth;
dojo.style.setMarginBoxWidth=dojo.style.setOuterWidth;
dojo.style.getMarginHeight=function(node){
node=dojo.byId(node);
var _2f5=dojo.style.isPositionAbsolute(node);
var top=dojo.style.getPixelValue(node,"margin-top",_2f5);
var _2f7=dojo.style.getPixelValue(node,"margin-bottom",_2f5);
return top+_2f7;
};
dojo.style.getBorderHeight=function(node){
node=dojo.byId(node);
var top=(dojo.style.getStyle(node,"border-top-style")=="none"?0:dojo.style.getPixelValue(node,"border-top-width"));
var _2fa=(dojo.style.getStyle(node,"border-bottom-style")=="none"?0:dojo.style.getPixelValue(node,"border-bottom-width"));
return top+_2fa;
};
dojo.style.getPaddingHeight=function(node){
node=dojo.byId(node);
var top=dojo.style.getPixelValue(node,"padding-top",true);
var _2fd=dojo.style.getPixelValue(node,"padding-bottom",true);
return top+_2fd;
};
dojo.style.getContentHeight=function(node){
node=dojo.byId(node);
return node.offsetHeight-dojo.style.getPaddingHeight(node)-dojo.style.getBorderHeight(node);
};
dojo.style.getInnerHeight=function(node){
node=dojo.byId(node);
return node.offsetHeight;
};
dojo.style.getOuterHeight=function(node){
node=dojo.byId(node);
return dojo.style.getInnerHeight(node)+dojo.style.getMarginHeight(node);
};
dojo.style.setOuterHeight=function(node,_302){
node=dojo.byId(node);
if(!dojo.style.isBorderBox(node)){
_302-=dojo.style.getPaddingHeight(node)+dojo.style.getBorderHeight(node);
}
_302-=dojo.style.getMarginHeight(node);
if(!isNaN(_302)&&_302>0){
node.style.height=_302+"px";
return true;
}else{
return false;
}
};
dojo.style.setContentWidth=function(node,_304){
node=dojo.byId(node);
if(dojo.style.isBorderBox(node)){
_304+=dojo.style.getPaddingWidth(node)+dojo.style.getBorderWidth(node);
}
if(!isNaN(_304)&&_304>0){
node.style.width=_304+"px";
return true;
}else{
return false;
}
};
dojo.style.setContentHeight=function(node,_306){
node=dojo.byId(node);
if(dojo.style.isBorderBox(node)){
_306+=dojo.style.getPaddingHeight(node)+dojo.style.getBorderHeight(node);
}
if(!isNaN(_306)&&_306>0){
node.style.height=_306+"px";
return true;
}else{
return false;
}
};
dojo.style.getContentBoxHeight=dojo.style.getContentHeight;
dojo.style.getBorderBoxHeight=dojo.style.getInnerHeight;
dojo.style.getMarginBoxHeight=dojo.style.getOuterHeight;
dojo.style.setMarginBoxHeight=dojo.style.setOuterHeight;
dojo.style.getTotalOffset=function(node,type,_309){
node=dojo.byId(node);
var _30a=(type=="top")?"offsetTop":"offsetLeft";
var _30b=(type=="top")?"scrollTop":"scrollLeft";
var _30c=(type=="top")?"y":"x";
var _30d=0;
if(node["offsetParent"]){
if(dojo.render.html.safari&&node.style.getPropertyValue("position")=="absolute"&&node.parentNode==document.body){
var _30e=document.body;
}else{
var _30e=document.body.parentNode;
}
if(_309&&node.parentNode!=document.body){
_30d-=dojo.style.sumAncestorProperties(node,_30b);
}
do{
var n=node[_30a];
_30d+=isNaN(n)?0:n;
node=node.offsetParent;
}while(node!=_30e&&node!=null);
}else{
if(node[_30c]){
var n=node[_30c];
_30d+=isNaN(n)?0:n;
}
}
return _30d;
};
dojo.style.sumAncestorProperties=function(node,prop){
node=dojo.byId(node);
if(!node){
return 0;
}
var _312=0;
while(node){
var val=node[prop];
if(val){
_312+=val-0;
}
node=node.parentNode;
}
return _312;
};
dojo.style.totalOffsetLeft=function(node,_315){
node=dojo.byId(node);
return dojo.style.getTotalOffset(node,"left",_315);
};
dojo.style.getAbsoluteX=dojo.style.totalOffsetLeft;
dojo.style.totalOffsetTop=function(node,_317){
node=dojo.byId(node);
return dojo.style.getTotalOffset(node,"top",_317);
};
dojo.style.getAbsoluteY=dojo.style.totalOffsetTop;
dojo.style.getAbsolutePosition=function(node,_319){
node=dojo.byId(node);
var _31a=[dojo.style.getAbsoluteX(node,_319),dojo.style.getAbsoluteY(node,_319)];
_31a.x=_31a[0];
_31a.y=_31a[1];
return _31a;
};
dojo.style.styleSheet=null;
dojo.style.insertCssRule=function(_31b,_31c,_31d){
if(!dojo.style.styleSheet){
if(document.createStyleSheet){
dojo.style.styleSheet=document.createStyleSheet();
}else{
if(document.styleSheets[0]){
dojo.style.styleSheet=document.styleSheets[0];
}else{
return null;
}
}
}
if(arguments.length<3){
if(dojo.style.styleSheet.cssRules){
_31d=dojo.style.styleSheet.cssRules.length;
}else{
if(dojo.style.styleSheet.rules){
_31d=dojo.style.styleSheet.rules.length;
}else{
return null;
}
}
}
if(dojo.style.styleSheet.insertRule){
var rule=_31b+" { "+_31c+" }";
return dojo.style.styleSheet.insertRule(rule,_31d);
}else{
if(dojo.style.styleSheet.addRule){
return dojo.style.styleSheet.addRule(_31b,_31c,_31d);
}else{
return null;
}
}
};
dojo.style.removeCssRule=function(_31f){
if(!dojo.style.styleSheet){
dojo.debug("no stylesheet defined for removing rules");
return false;
}
if(dojo.render.html.ie){
if(!_31f){
_31f=dojo.style.styleSheet.rules.length;
dojo.style.styleSheet.removeRule(_31f);
}
}else{
if(document.styleSheets[0]){
if(!_31f){
_31f=dojo.style.styleSheet.cssRules.length;
}
dojo.style.styleSheet.deleteRule(_31f);
}
}
return true;
};
dojo.style.getBackgroundColor=function(node){
node=dojo.byId(node);
var _321;
do{
_321=dojo.style.getStyle(node,"background-color");
if(_321.toLowerCase()=="rgba(0, 0, 0, 0)"){
_321="transparent";
}
if(node==document.getElementsByTagName("body")[0]){
node=null;
break;
}
node=node.parentNode;
}while(node&&dojo.lang.inArray(_321,["transparent",""]));
if(_321=="transparent"){
_321=[255,255,255,0];
}else{
_321=dojo.graphics.color.extractRGB(_321);
}
return _321;
};
dojo.style.getComputedStyle=function(node,_323,_324){
node=dojo.byId(node);
var _325=_324;
if(node.style.getPropertyValue){
_325=node.style.getPropertyValue(_323);
}
if(!_325){
if(document.defaultView){
var cs=document.defaultView.getComputedStyle(node,"");
if(cs){
_325=cs.getPropertyValue(_323);
}
}else{
if(node.currentStyle){
_325=node.currentStyle[dojo.style.toCamelCase(_323)];
}
}
}
return _325;
};
dojo.style.getStyle=function(node,_328){
node=dojo.byId(node);
var _329=dojo.style.toCamelCase(_328);
var _32a=node.style[_329];
return (_32a?_32a:dojo.style.getComputedStyle(node,_328,_32a));
};
dojo.style.toCamelCase=function(_32b){
var arr=_32b.split("-"),cc=arr[0];
for(var i=1;i<arr.length;i++){
cc+=arr[i].charAt(0).toUpperCase()+arr[i].substring(1);
}
return cc;
};
dojo.style.toSelectorCase=function(_32e){
return _32e.replace(/([A-Z])/g,"-$1").toLowerCase();
};
dojo.style.setOpacity=function setOpacity(node,_330,_331){
node=dojo.byId(node);
var h=dojo.render.html;
if(!_331){
if(_330>=1){
if(h.ie){
dojo.style.clearOpacity(node);
return;
}else{
_330=0.999999;
}
}else{
if(_330<0){
_330=0;
}
}
}
if(h.ie){
if(node.nodeName.toLowerCase()=="tr"){
var tds=node.getElementsByTagName("td");
for(var x=0;x<tds.length;x++){
tds[x].style.filter="Alpha(Opacity="+_330*100+")";
}
}
node.style.filter="Alpha(Opacity="+_330*100+")";
}else{
if(h.moz){
node.style.opacity=_330;
node.style.MozOpacity=_330;
}else{
if(h.safari){
node.style.opacity=_330;
node.style.KhtmlOpacity=_330;
}else{
node.style.opacity=_330;
}
}
}
};
dojo.style.getOpacity=function getOpacity(node){
node=dojo.byId(node);
if(dojo.render.html.ie){
var opac=(node.filters&&node.filters.alpha&&typeof node.filters.alpha.opacity=="number"?node.filters.alpha.opacity:100)/100;
}else{
var opac=node.style.opacity||node.style.MozOpacity||node.style.KhtmlOpacity||1;
}
return opac>=0.999999?1:Number(opac);
};
dojo.style.clearOpacity=function clearOpacity(node){
node=dojo.byId(node);
var h=dojo.render.html;
if(h.ie){
if(node.filters&&node.filters.alpha){
node.style.filter="";
}
}else{
if(h.moz){
node.style.opacity=1;
node.style.MozOpacity=1;
}else{
if(h.safari){
node.style.opacity=1;
node.style.KhtmlOpacity=1;
}else{
node.style.opacity=1;
}
}
}
};
dojo.style.isVisible=function(node){
node=dojo.byId(node);
return dojo.style.getComputedStyle(node||this.domNode,"display")!="none";
};
dojo.style.show=function(node){
node=dojo.byId(node);
if(node.style){
node.style.display=dojo.lang.inArray(["tr","td","th"],node.tagName.toLowerCase())?"":"block";
}
};
dojo.style.hide=function(node){
node=dojo.byId(node);
if(node.style){
node.style.display="none";
}
};
dojo.style.toggleVisible=function(node){
if(dojo.style.isVisible(node)){
dojo.style.hide(node);
return false;
}else{
dojo.style.show(node);
return true;
}
};
dojo.style.toCoordinateArray=function(_33d,_33e){
if(dojo.lang.isArray(_33d)){
while(_33d.length<4){
_33d.push(0);
}
while(_33d.length>4){
_33d.pop();
}
var ret=_33d;
}else{
var node=dojo.byId(_33d);
var ret=[dojo.style.getAbsoluteX(node,_33e),dojo.style.getAbsoluteY(node,_33e),dojo.style.getInnerWidth(node),dojo.style.getInnerHeight(node)];
}
ret.x=ret[0];
ret.y=ret[1];
ret.w=ret[2];
ret.h=ret[3];
return ret;
};
if(this["dojo"]){
dojo.provide("turbo.turbo");
dojo.require("dojo.dom");
dojo.require("dojo.style");
dojo.require("dojo.string.extras");
dojo.setModulePrefix("turbo","../turbo");
}else{
turbo={};
dojo={provide:function(){
},require:function(){
}};
}
turbo.global=this;
turbo.bind=function(_341,_342){
if(_342){
if(dojo.lang.isString(_342)){
_342=_341[_342];
}
return function(){
return _342.apply(_341,arguments);
};
}else{
dojo.debug("turbo.bind called with null method");
return function(){
};
}
};
turbo.cloneArguments=function(_343,_344){
var l=_343.length;
var s=(_344?_344:0);
var _347=new Array(l-s);
for(var i=s,j=0;i<l;i++,j++){
_347[j]=_343[i];
}
return _347;
};
turbo.bindArgs=function(_349,_34a){
if(!_34a){
dojo.debug("turbo.bindArgs called with null method");
return function(){
};
}
if(dojo.lang.isString(_34a)){
_34a=_349[_34a];
}
var _34b=turbo.cloneArguments(arguments,2);
return function(){
var args=_34b.slice(0);
for(var i=0;i<arguments.length;i++){
args.push(arguments[i]);
}
return _34a.apply(_349,args);
};
};
turbo.$=function(inId,_34f){
return (_34f?_34f:document).getElementById(inId);
};
turbo.defer=function(_350,_351){
window.setTimeout(_350,_351);
};
turbo.marshall=function(){
var id="";
var _353=dj_global;
for(var i=0;i<arguments.length;i++){
id=arguments[i];
if(i==0&&!dojo.lang.isString(i)){
_353=id;
}else{
if(!_353[id]){
_353[id]=turbo.$(id);
}
}
}
return dj_global[id];
};
turbo.stringOf=function(_355,_356){
if(_355<=0){
return "";
}
var _357=new Array(_355);
for(var i=0;i<_355;i++){
_357[i]=_356;
}
return _357.join("");
};
turbo.getCellIndex=function(inTd){
if(inTd.cellIndex){
return inTd.cellIndex;
}
var _35a=inTd.parentNode.cells;
var l=_35a.length;
for(var i=0;i<l;i++){
if(inTd==_35a[i]){
return i;
}
}
return -1;
};
turbo.sparse={count:function(_35d){
var c=0;
for(var i in _35d){
c++;
}
return c;
},map:function(_360,_361){
var l=_360.length;
for(var i=0;i<l;i++){
if(_360[i]){
_361(i);
}
}
},filter:function(_364,_365){
var i=0;
var l=_364.length;
while(i<l){
if(_364[i]&&_365(i)){
_364.splice(i,1);
l--;
}else{
i++;
}
}
}};
turbo.sparseArray=function(){
this.array=[];
this.clear=function(){
this.array=[];
};
this.get=function(_368){
return this.array[_368];
};
this.set=function(_369,_36a){
this.array[_369]=_36a;
};
this.unset=function(_36b){
delete this.array[_36b];
};
this.remove=function(_36c){
this.array.splice(_36c,1);
};
this.insert=function(_36d,_36e){
this.array.splice(_36d,0,_36e);
};
this.empty=function(){
for(var i in this.array){
return false;
}
return true;
};
this.count=function(){
var c=0;
for(var i in this.array){
c++;
}
return c;
};
this.map=function(_372){
var f=function(i,e){
if(e){
_372(i,e);
}
};
var l=this.array.length;
for(var i=0;i<l;i++){
f(i,this.array[i]);
}
};
this.filter=function(_378){
var f=function(i,e){
return (e&&_378(i,e));
};
var i=0;
var l=this.array.length;
while(i<l){
if(f(i,this.array[i])){
this.array.splice(i,1);
l--;
}else{
i++;
}
}
};
this.dump=function(){
dojo.debug("metarows:");
this.map(function(i,e){
dojo.debug(i+"=>");
turbo.debug(e);
});
};
};
turbo.getWindowSize=function(){
if(window.innerWidth){
return {w:window.innerWidth,h:window.innerHeight};
}else{
return {w:document.documentElement.clientWidth,h:document.documentElement.clientHeight};
}
};
turbo.getContentSize=function(_380){
if(_380&&_380!=document.body){
return {w:dojo.style.getContentBoxWidth(_380),h:dojo.style.getContentBoxHeight(_380)};
}else{
return turbo.getWindowSize();
}
};
turbo.getInnerSize=function(_381){
if(_381&&_381!=document.body){
return {w:dojo.style.getBorderBoxWidth(_381),h:dojo.style.getBorderBoxHeight(_381)};
}else{
return turbo.getWindowSize();
}
};
turbo.getOuterSize=function(_382){
if(_382&&_382!=document.body){
return {w:dojo.style.getMarginBoxWidth(_382),h:dojo.style.getMarginBoxHeight(_382)};
}else{
return turbo.getWindowSize();
}
};
turbo.setContentSize=function(_383,inW,inH){
var siz=turbo.getContentSize(_383);
if(inW>0&&inW!=siz.w){
_383.style.width=inW+"px";
}
if(inH>0&&inH!=siz.h){
_383.style.height=inH+"px";
}
};
turbo.setOuterSize=function(_387,inW,inH){
dojo.style.setMarginBoxWidth(_387,inW);
dojo.style.setMarginBoxHeight(_387,inH);
};
turbo.setBounds=function(_38a,inL,inT,inW,inH){
if(_38a){
with(_38a.style){
if(inL>=0){
left=inL+"px";
}
if(inT>=0){
top=inT+"px";
}
}
turbo.setOuterSize(_38a,inW,inH);
}
};
turbo.setStyle=function(_38f,_390,_391){
if(_38f&&_38f.style[_390]!=_391){
_38f.style[_390]=_391;
}
};
turbo.setStyleWidthPx=function(_392,_393){
if(_393>0){
turbo.setStyle(_392,"width",_393+"px");
}
};
turbo.setStyleHeightPx=function(_394,_395){
if(_395>0){
turbo.setStyle(_394,"height",_395+"px");
}
};
turbo.setStyleSizePx=function(_396,_397,_398){
turbo.setStyleWidthPx(_396,_397);
turbo.setStyleHeightPx(_396,_398);
};
turbo.setStyleBoundsPx=function(_399,_39a,_39b,_39c,_39d){
turbo.setVisibility(_399,false);
turbo.setStyle(_399,"left",_39a+"px");
turbo.setStyle(_399,"top",_39b+"px");
turbo.setStyleWidthPx(_399,_39c);
turbo.setStyleHeightPx(_399,_39d);
turbo.setVisibility(_399,true);
};
turbo.setOuterStyleWidthPx=function(_39e,_39f){
if(_39e){
dojo.style.setMarginBoxWidth(_39e,_39f);
}
};
turbo.setOuterStyleHeightPx=function(_3a0,_3a1){
if(_3a0){
dojo.style.setMarginBoxHeight(_3a0,_3a1);
}
};
turbo.capture=function(_3a2){
if(_3a2.setCapture){
_3a2.setCapture();
}else{
document.addEventListener("mousemove",_3a2.onmousemove,true);
document.addEventListener("mouseup",_3a2.onmouseup,true);
}
};
turbo.release=function(_3a3){
if(_3a3.releaseCapture){
_3a3.releaseCapture();
}else{
document.removeEventListener("mousemove",_3a3.onmousemove,true);
document.removeEventListener("mouseup",_3a3.onmouseup,true);
}
};
turbo.time=function(){
return new Date().getTime();
};
turbo.profile=function(_3a4,_3a5){
var t=turbo.time();
if(_3a5){
_3a4.call(_3a5);
}else{
_3a4();
}
return turbo.time()-t;
};
turbo.array_swap=function(_3a7,inI,inJ){
var _3aa=_3a7[inI];
_3a7[inI]=_3a7[inJ];
_3a7[inJ]=_3aa;
};
turbo.supplant=function(s,o){
var i,j;
for(;;){
i=s.lastIndexOf("{");
if(i<0){
break;
}
j=s.indexOf("}",i);
if(i+1>=j){
break;
}
s=s.substring(0,i)+o[s.substring(i+1,j)]+s.substring(j+1);
}
return s;
};
turbo.swiss=function(_3ae,_3af){
for(var i in _3ae){
_3af[i]=_3ae[i];
}
return _3af;
};
turbo.debugOut=function(_3b1){
dojo.debug(_3b1);
};
turbo.debugTop=function(_3b2){
for(var name in _3b2){
var obj=_3b2[name];
s=name;
if(obj!=null&&typeof (obj)=="object"){
turbo.debugOut(s+" = ("+(obj instanceof Array?"array":"object")+")");
}else{
turbo.debugOut(s+" = "+obj);
}
}
};
turbo.debugObject=function(_3b5,_3b6){
if(_3b6==undefined){
_3b6="";
}
if(_3b6.length>6*5){
turbo.debugOut(_3b6+"too deep");
}
for(var name in _3b5){
var obj=_3b5[name];
s=_3b6+"| "+name;
if(obj!=null&&typeof (obj)=="object"){
turbo.debugOut(s+" = ("+(obj instanceof Array?"array":"object")+")");
turbo.debugObject(obj,_3b6+"......");
}else{
turbo.debugOut(s+" = "+obj);
}
}
};
turbo.debug=function(){
var c=arguments.length;
for(var i=0;i<c;i++){
if(dojo.lang.isObject(arguments[i])){
turbo.debugObject(arguments[i]);
}else{
turbo.debugOut(arguments[i]);
}
}
};
turbo.escapeText=function(_3bb){
return dojo.string.escapeXml(String(_3bb)).replace(/\n/g,"<br />");
};
turbo.stringReplace=function(_3bc,_3bd,_3be){
if(!dojo.render.html.safari){
return _3bc.replace(_3bd,_3be);
}
var str=_3bc;
var _3c0=_3be;
var reg=_3bd;
var _3c2=[];
var _3c3=reg.lastIndex;
var re;
while((re=reg.exec(str))!=null){
var idx=re.index;
var args=re.concat(idx,str);
_3c2.push(str.slice(_3c3,idx),_3c0.apply(null,args).toString());
if(!reg.global){
_3c3+=(RegExp.lastMatch?RegExp.lastMatch.length:0);
break;
}else{
_3c3=reg.lastIndex;
}
}
_3c2.push(str.slice(_3c3));
return _3c2.join("");
};
turbo.getScrollbarWidth=function(){
if(turbo["_scrollBarWidth"]){
return turbo._scrollBarWidth;
}
turbo._scrollBarWidth=18;
try{
var e=document.createElement("div");
with(e.style){
width="100px";
height="100px";
overflow="scroll";
position="absolute";
visibility="hidden";
}
document.body.appendChild(e);
turbo._scrollBarWidth=e.offsetWidth-e.clientWidth-(dojo.render.html.moz?1:0);
document.body.removeChild(e);
delete e;
}
catch(ex){
}
return turbo._scrollBarWidth;
};
turbo.preloads=[];
turbo.preloadImage=function(_3c8){
var i=new Image();
i.src=_3c8;
turbo.preloads.push(i);
};
turbo.setCursor=function(_3ca){
document.body.style.cursor=_3ca;
};
turbo.setBusyCursor=function(){
turbo.setCursor("wait");
};
turbo.setDefaultCursor=function(){
turbo.setCursor("default");
};
turbo.addBodyNode=function(_3cb){
document.body.appendChild(_3cb);
};
turbo.addHeadNode=function(_3cc){
document.getElementsByTagName("head").item(0).appendChild(_3cc);
};
turbo.remove=function(_3cd){
if(dojo.lang.isString(_3cd)){
_3cd=turbo.$(_3cd);
}
if(_3cd&&_3cd.parentNode){
_3cd.parentNode.removeChild(_3cd);
}
};
turbo.setShowing=function(_3ce,_3cf){
if(dojo.lang.isString(_3ce)){
_3ce=turbo.$(_3ce);
}
if(_3ce&&_3ce.style){
_3ce.style.display=(_3cf?"":"none");
}
};
turbo.showHide=function(){
var l=arguments.length-1;
var show=arguments[l];
if(show!==true&&show!==false){
show=true;
l++;
}
for(var i=0;i<l;i++){
turbo.setShowing(arguments[i],show);
}
};
turbo.show=turbo.showHide;
turbo.hide=function(){
var l=arguments.length;
for(var i=0;i<l;i++){
turbo.setShowing(arguments[i],false);
}
};
turbo.showing=function(_3d5){
if(!_3d5||(_3d5["style"]&&dojo.style.getComputedStyle(_3d5,"display")=="none")){
return false;
}else{
if(_3d5["parentNode"]&&_3d5.parentNode){
return turbo.showing(_3d5.parentNode);
}else{
return true;
}
}
};
turbo.setVisibility=function(_3d6,_3d7){
if(dojo.lang.isString(_3d6)){
_3d6=turbo.$(_3d6);
}
if(_3d6&&_3d6.style){
_3d6.style.visibility=(_3d7?"":"hidden");
}
};
turbo.pathpop=function(_3d8,_3d9){
var _3da=_3d8.lastIndexOf((_3d9==undefined?"/":_3d9));
return (_3da>=0?_3d8.substring(0,_3da):"");
};
turbo.clean=function(_3db){
if(!_3db){
return;
}
var _3dc=function(inW){
return inW.domNode&&dojo.dom.isDescendantOf(inW.domNode,_3db,true);
};
var ws=dojo.widget.byFilter(_3dc);
for(var i=0;i<ws.length;i++){
var w=ws[i];
if(dojo.widget.widgetIds[w.widgetId]==w){
w.destroy();
}
}
dojo.event.browser.clean(_3db);
};
dojo.provide("turbo.lib.align");
dojo.require("turbo.turbo");
turbo.aligner=new function(){
this.enabled=false;
this.targets=[];
this.getAlignment=function(_3e1){
return _3e1.getAttribute("turboAlign")||_3e1.getAttribute("turboalign");
};
this.visible=function(_3e2){
return (dojo.style.getComputedStyle(_3e2,"display")!="none");
};
this.listChildrenByAlignment=function(_3e3,_3e4){
var _3e5=[];
var node=_3e3.firstChild;
while(node){
if(node.nodeType==1&&this.getAlignment(node)==_3e4&&this.visible(node)){
_3e5.push(node);
}
node=node.nextSibling;
}
return _3e5;
};
this.listAlignedChildren=function(_3e7){
var _3e8={none:[],top:[],left:[],client:[],right:[],bottom:[]};
var node=_3e7.firstChild;
while(node){
if(node.nodeType==1&&this.visible(node)){
var _3ea=this.getAlignment(node);
if(_3ea){
if(_3e8[_3ea]){
_3e8[_3ea].push(node);
}else{
_3e8[_3ea]=[node];
}
}
}
node=node.nextSibling;
}
return _3e8;
};
this.normalizeAlignedElement=function(_3eb){
if(_3eb.style.position!="absolute"){
_3eb.style.position="absolute";
}
};
this.alignElement=function(_3ec,inL,inT,inW,inH){
this.normalizeAlignedElement(_3ec);
turbo.setBounds(_3ec,inL,inT,inW,inH);
this.alignChildren(_3ec);
};
this.alignChildren=function(_3f1){
var _3f2=this.listAlignedChildren(_3f1);
var siz=turbo.getContentSize(_3f1);
var top=dojo.style.getPixelValue(_3f1,"padding-top",true);
var left=dojo.style.getPixelValue(_3f1,"padding-left",true);
var l,r,t,b,w,h,c,aligns;
aligns=_3f2.top;
t=top;
for(var i=0;i<aligns.length;i++){
this.alignElement(aligns[i],-1,t,siz.w);
t+=aligns[i].offsetHeight;
}
aligns=_3f2.bottom;
b=siz.h+top;
c=aligns.length;
for(var i=c-1;i>=0;i--){
b-=aligns[i].offsetHeight;
this.alignElement(aligns[i],-1,b,siz.w);
}
h=b-t;
aligns=_3f2.left;
l=left;
for(var i=0;i<aligns.length;i++){
this.alignElement(aligns[i],l,t,-1,h);
l+=aligns[i].offsetWidth;
}
aligns=_3f2.right;
r=siz.w+left;
c=aligns.length;
for(var i=c-1;i>=0;i--){
r-=aligns[i].offsetWidth;
this.alignElement(aligns[i],r,t,-1,h);
}
w=r-l;
aligns=_3f2.client;
for(var i=0;i<aligns.length;i++){
this.alignElement(aligns[i],l,t,w,h);
break;
}
aligns=_3f2.none;
for(var i=0;i<aligns.length;i++){
this.alignChildren(aligns[i]);
}
};
this.alignTargets=function(){
for(var i in turbo.aligner.targets){
turbo.aligner.alignChildren(turbo.aligner.targets[i]);
}
};
this.alignFrom=function(_3f9){
turbo.aligner.alignChildren(_3f9);
turbo.aligner.alignTargets();
};
this.alignNow=function(){
turbo.aligner.alignFrom(document.body);
};
this.lastAlign=0;
this.align=function(){
if(!turbo.aligner.enabled){
return;
}
turbo.aligner.alignNow();
turbo.aligner.lastAlign=turbo.time();
};
this.enable=function(_3fa){
turbo.aligner.enabled=(_3fa!==false);
};
this.start=function(){
turbo.aligner.enable();
turbo.defer(turbo.aligner.align,500);
};
this.addTarget=function(_3fb){
var e=(dojo.lang.isString(_3fb)?turbo.$(_3fb):_3fb);
e.style.position="relative";
turbo.aligner.targets.push(e);
};
};
dojo.provide("dojo.event");
dojo.require("dojo.lang.array");
dojo.require("dojo.lang.extras");
dojo.event=new function(){
this.canTimeout=dojo.lang.isFunction(dj_global["setTimeout"])||dojo.lang.isAlien(dj_global["setTimeout"]);
function interpolateArgs(args){
var dl=dojo.lang;
var ao={srcObj:dj_global,srcFunc:null,adviceObj:dj_global,adviceFunc:null,aroundObj:null,aroundFunc:null,adviceType:(args.length>2)?args[0]:"after",precedence:"last",once:false,delay:null,rate:0,adviceMsg:false};
switch(args.length){
case 0:
return;
case 1:
return;
case 2:
ao.srcFunc=args[0];
ao.adviceFunc=args[1];
break;
case 3:
if((dl.isObject(args[0]))&&(dl.isString(args[1]))&&(dl.isString(args[2]))){
ao.adviceType="after";
ao.srcObj=args[0];
ao.srcFunc=args[1];
ao.adviceFunc=args[2];
}else{
if((dl.isString(args[1]))&&(dl.isString(args[2]))){
ao.srcFunc=args[1];
ao.adviceFunc=args[2];
}else{
if((dl.isObject(args[0]))&&(dl.isString(args[1]))&&(dl.isFunction(args[2]))){
ao.adviceType="after";
ao.srcObj=args[0];
ao.srcFunc=args[1];
var _400=dojo.lang.nameAnonFunc(args[2],ao.adviceObj);
ao.adviceFunc=_400;
}else{
if((dl.isFunction(args[0]))&&(dl.isObject(args[1]))&&(dl.isString(args[2]))){
ao.adviceType="after";
ao.srcObj=dj_global;
var _400=dojo.lang.nameAnonFunc(args[0],ao.srcObj);
ao.srcFunc=_400;
ao.adviceObj=args[1];
ao.adviceFunc=args[2];
}
}
}
}
break;
case 4:
if((dl.isObject(args[0]))&&(dl.isObject(args[2]))){
ao.adviceType="after";
ao.srcObj=args[0];
ao.srcFunc=args[1];
ao.adviceObj=args[2];
ao.adviceFunc=args[3];
}else{
if((dl.isString(args[0]))&&(dl.isString(args[1]))&&(dl.isObject(args[2]))){
ao.adviceType=args[0];
ao.srcObj=dj_global;
ao.srcFunc=args[1];
ao.adviceObj=args[2];
ao.adviceFunc=args[3];
}else{
if((dl.isString(args[0]))&&(dl.isFunction(args[1]))&&(dl.isObject(args[2]))){
ao.adviceType=args[0];
ao.srcObj=dj_global;
var _400=dojo.lang.nameAnonFunc(args[1],dj_global);
ao.srcFunc=_400;
ao.adviceObj=args[2];
ao.adviceFunc=args[3];
}else{
if(dl.isObject(args[1])){
ao.srcObj=args[1];
ao.srcFunc=args[2];
ao.adviceObj=dj_global;
ao.adviceFunc=args[3];
}else{
if(dl.isObject(args[2])){
ao.srcObj=dj_global;
ao.srcFunc=args[1];
ao.adviceObj=args[2];
ao.adviceFunc=args[3];
}else{
ao.srcObj=ao.adviceObj=ao.aroundObj=dj_global;
ao.srcFunc=args[1];
ao.adviceFunc=args[2];
ao.aroundFunc=args[3];
}
}
}
}
}
break;
case 6:
ao.srcObj=args[1];
ao.srcFunc=args[2];
ao.adviceObj=args[3];
ao.adviceFunc=args[4];
ao.aroundFunc=args[5];
ao.aroundObj=dj_global;
break;
default:
ao.srcObj=args[1];
ao.srcFunc=args[2];
ao.adviceObj=args[3];
ao.adviceFunc=args[4];
ao.aroundObj=args[5];
ao.aroundFunc=args[6];
ao.once=args[7];
ao.delay=args[8];
ao.rate=args[9];
ao.adviceMsg=args[10];
break;
}
if((typeof ao.srcFunc).toLowerCase()!="string"){
ao.srcFunc=dojo.lang.getNameInObj(ao.srcObj,ao.srcFunc);
}
if((typeof ao.adviceFunc).toLowerCase()!="string"){
ao.adviceFunc=dojo.lang.getNameInObj(ao.adviceObj,ao.adviceFunc);
}
if((ao.aroundObj)&&((typeof ao.aroundFunc).toLowerCase()!="string")){
ao.aroundFunc=dojo.lang.getNameInObj(ao.aroundObj,ao.aroundFunc);
}
if(!ao.srcObj){
dojo.raise("bad srcObj for srcFunc: "+ao.srcFunc);
}
if(!ao.adviceObj){
dojo.raise("bad adviceObj for adviceFunc: "+ao.adviceFunc);
}
return ao;
}
this.connect=function(){
var ao=interpolateArgs(arguments);
var mjp=dojo.event.MethodJoinPoint.getForMethod(ao.srcObj,ao.srcFunc);
if(ao.adviceFunc){
var mjp2=dojo.event.MethodJoinPoint.getForMethod(ao.adviceObj,ao.adviceFunc);
}
mjp.kwAddAdvice(ao);
return mjp;
};
this.connectBefore=function(){
var args=["before"];
for(var i=0;i<arguments.length;i++){
args.push(arguments[i]);
}
return this.connect.apply(this,args);
};
this.connectAround=function(){
var args=["around"];
for(var i=0;i<arguments.length;i++){
args.push(arguments[i]);
}
return this.connect.apply(this,args);
};
this._kwConnectImpl=function(_408,_409){
var fn=(_409)?"disconnect":"connect";
if(typeof _408["srcFunc"]=="function"){
_408.srcObj=_408["srcObj"]||dj_global;
var _40b=dojo.lang.nameAnonFunc(_408.srcFunc,_408.srcObj);
_408.srcFunc=_40b;
}
if(typeof _408["adviceFunc"]=="function"){
_408.adviceObj=_408["adviceObj"]||dj_global;
var _40b=dojo.lang.nameAnonFunc(_408.adviceFunc,_408.adviceObj);
_408.adviceFunc=_40b;
}
return dojo.event[fn]((_408["type"]||_408["adviceType"]||"after"),_408["srcObj"]||dj_global,_408["srcFunc"],_408["adviceObj"]||_408["targetObj"]||dj_global,_408["adviceFunc"]||_408["targetFunc"],_408["aroundObj"],_408["aroundFunc"],_408["once"],_408["delay"],_408["rate"],_408["adviceMsg"]||false);
};
this.kwConnect=function(_40c){
return this._kwConnectImpl(_40c,false);
};
this.disconnect=function(){
var ao=interpolateArgs(arguments);
if(!ao.adviceFunc){
return;
}
var mjp=dojo.event.MethodJoinPoint.getForMethod(ao.srcObj,ao.srcFunc);
return mjp.removeAdvice(ao.adviceObj,ao.adviceFunc,ao.adviceType,ao.once);
};
this.kwDisconnect=function(_40f){
return this._kwConnectImpl(_40f,true);
};
};
dojo.event.MethodInvocation=function(_410,obj,args){
this.jp_=_410;
this.object=obj;
this.args=[];
for(var x=0;x<args.length;x++){
this.args[x]=args[x];
}
this.around_index=-1;
};
dojo.event.MethodInvocation.prototype.proceed=function(){
this.around_index++;
if(this.around_index>=this.jp_.around.length){
return this.jp_.object[this.jp_.methodname].apply(this.jp_.object,this.args);
}else{
var ti=this.jp_.around[this.around_index];
var mobj=ti[0]||dj_global;
var meth=ti[1];
return mobj[meth].call(mobj,this);
}
};
dojo.event.MethodJoinPoint=function(obj,_418){
this.object=obj||dj_global;
this.methodname=_418;
this.methodfunc=this.object[_418];
this.before=[];
this.after=[];
this.around=[];
};
dojo.event.MethodJoinPoint.getForMethod=function(obj,_41a){
if(!obj){
obj=dj_global;
}
if(!obj[_41a]){
obj[_41a]=function(){
};
}else{
if((!dojo.lang.isFunction(obj[_41a]))&&(!dojo.lang.isAlien(obj[_41a]))){
return null;
}
}
var _41b=_41a+"$joinpoint";
var _41c=_41a+"$joinpoint$method";
var _41d=obj[_41b];
if(!_41d){
var _41e=false;
if(dojo.event["browser"]){
if((obj["attachEvent"])||(obj["nodeType"])||(obj["addEventListener"])){
_41e=true;
dojo.event.browser.addClobberNodeAttrs(obj,[_41b,_41c,_41a]);
}
}
obj[_41c]=obj[_41a];
_41d=obj[_41b]=new dojo.event.MethodJoinPoint(obj,_41c);
obj[_41a]=function(){
var args=[];
if((_41e)&&(!arguments.length)){
var evt=null;
try{
if(obj.ownerDocument){
evt=obj.ownerDocument.parentWindow.event;
}else{
if(obj.documentElement){
evt=obj.documentElement.ownerDocument.parentWindow.event;
}else{
evt=window.event;
}
}
}
catch(E){
evt=window.event;
}
if(evt){
args.push(dojo.event.browser.fixEvent(evt));
}
}else{
for(var x=0;x<arguments.length;x++){
if((x==0)&&(_41e)&&(dojo.event.browser.isEvent(arguments[x]))){
args.push(dojo.event.browser.fixEvent(arguments[x]));
}else{
args.push(arguments[x]);
}
}
}
return _41d.run.apply(_41d,args);
};
}
return _41d;
};
dojo.lang.extend(dojo.event.MethodJoinPoint,{unintercept:function(){
this.object[this.methodname]=this.methodfunc;
},run:function(){
var obj=this.object||dj_global;
var args=arguments;
var _424=[];
for(var x=0;x<args.length;x++){
_424[x]=args[x];
}
var _426=function(marr){
if(!marr){
dojo.debug("Null argument to unrollAdvice()");
return;
}
var _428=marr[0]||dj_global;
var _429=marr[1];
if(!_428[_429]){
dojo.raise("function \""+_429+"\" does not exist on \""+_428+"\"");
}
var _42a=marr[2]||dj_global;
var _42b=marr[3];
var msg=marr[6];
var _42d;
var to={args:[],jp_:this,object:obj,proceed:function(){
return _428[_429].apply(_428,to.args);
}};
to.args=_424;
var _42f=parseInt(marr[4]);
var _430=((!isNaN(_42f))&&(marr[4]!==null)&&(typeof marr[4]!="undefined"));
if(marr[5]){
var rate=parseInt(marr[5]);
var cur=new Date();
var _433=false;
if((marr["last"])&&((cur-marr.last)<=rate)){
if(dojo.event.canTimeout){
if(marr["delayTimer"]){
clearTimeout(marr.delayTimer);
}
var tod=parseInt(rate*2);
var mcpy=dojo.lang.shallowCopy(marr);
marr.delayTimer=setTimeout(function(){
mcpy[5]=0;
_426(mcpy);
},tod);
}
return;
}else{
marr.last=cur;
}
}
if(_42b){
_42a[_42b].call(_42a,to);
}else{
if((_430)&&((dojo.render.html)||(dojo.render.svg))){
dj_global["setTimeout"](function(){
if(msg){
_428[_429].call(_428,to);
}else{
_428[_429].apply(_428,args);
}
},_42f);
}else{
if(msg){
_428[_429].call(_428,to);
}else{
_428[_429].apply(_428,args);
}
}
}
};
if(this.before.length>0){
dojo.lang.forEach(this.before,_426,true);
}
var _436;
if(this.around.length>0){
var mi=new dojo.event.MethodInvocation(this,obj,args);
_436=mi.proceed();
}else{
if(this.methodfunc){
_436=this.object[this.methodname].apply(this.object,args);
}
}
if(this.after.length>0){
dojo.lang.forEach(this.after,_426,true);
}
return (this.methodfunc)?_436:null;
},getArr:function(kind){
var arr=this.after;
if((typeof kind=="string")&&(kind.indexOf("before")!=-1)){
arr=this.before;
}else{
if(kind=="around"){
arr=this.around;
}
}
return arr;
},kwAddAdvice:function(args){
this.addAdvice(args["adviceObj"],args["adviceFunc"],args["aroundObj"],args["aroundFunc"],args["adviceType"],args["precedence"],args["once"],args["delay"],args["rate"],args["adviceMsg"]);
},addAdvice:function(_43b,_43c,_43d,_43e,_43f,_440,once,_442,rate,_444){
var arr=this.getArr(_43f);
if(!arr){
dojo.raise("bad this: "+this);
}
var ao=[_43b,_43c,_43d,_43e,_442,rate,_444];
if(once){
if(this.hasAdvice(_43b,_43c,_43f,arr)>=0){
return;
}
}
if(_440=="first"){
arr.unshift(ao);
}else{
arr.push(ao);
}
},hasAdvice:function(_447,_448,_449,arr){
if(!arr){
arr=this.getArr(_449);
}
var ind=-1;
for(var x=0;x<arr.length;x++){
if((arr[x][0]==_447)&&(arr[x][1]==_448)){
ind=x;
}
}
return ind;
},removeAdvice:function(_44d,_44e,_44f,once){
var arr=this.getArr(_44f);
var ind=this.hasAdvice(_44d,_44e,_44f,arr);
if(ind==-1){
return false;
}
while(ind!=-1){
arr.splice(ind,1);
if(once){
break;
}
ind=this.hasAdvice(_44d,_44e,_44f,arr);
}
return true;
}});
dojo.require("dojo.event");
dojo.provide("dojo.event.topic");
dojo.event.topic=new function(){
this.topics={};
this.getTopic=function(_453){
if(!this.topics[_453]){
this.topics[_453]=new this.TopicImpl(_453);
}
return this.topics[_453];
};
this.registerPublisher=function(_454,obj,_456){
var _454=this.getTopic(_454);
_454.registerPublisher(obj,_456);
};
this.subscribe=function(_457,obj,_459){
var _457=this.getTopic(_457);
_457.subscribe(obj,_459);
};
this.unsubscribe=function(_45a,obj,_45c){
var _45a=this.getTopic(_45a);
_45a.unsubscribe(obj,_45c);
};
this.publish=function(_45d,_45e){
var _45d=this.getTopic(_45d);
var args=[];
if(arguments.length==2&&(dojo.lang.isArray(_45e)||_45e.callee)){
args=_45e;
}else{
var args=[];
for(var x=1;x<arguments.length;x++){
args.push(arguments[x]);
}
}
_45d.sendMessage.apply(_45d,args);
};
};
dojo.event.topic.TopicImpl=function(_461){
this.topicName=_461;
var self=this;
self.subscribe=function(_463,_464){
var tf=_464||_463;
var to=(!_464)?dj_global:_463;
dojo.event.kwConnect({srcObj:self,srcFunc:"sendMessage",adviceObj:to,adviceFunc:tf});
};
self.unsubscribe=function(_467,_468){
var tf=(!_468)?_467:_468;
var to=(!_468)?null:_467;
dojo.event.kwDisconnect({srcObj:self,srcFunc:"sendMessage",adviceObj:to,adviceFunc:tf});
};
self.registerPublisher=function(_46b,_46c){
dojo.event.connect(_46b,_46c,self,"sendMessage");
};
self.sendMessage=function(_46d){
};
};
dojo.provide("dojo.event.browser");
dojo.require("dojo.event");
dojo_ie_clobber=new function(){
this.clobberNodes=[];
function nukeProp(node,prop){
try{
node[prop]=null;
}
catch(e){
}
try{
delete node[prop];
}
catch(e){
}
try{
node.removeAttribute(prop);
}
catch(e){
}
}
this.clobber=function(_470){
var na;
var tna;
if(_470){
tna=_470.getElementsByTagName("*");
na=[_470];
for(var x=0;x<tna.length;x++){
if(tna[x]["__doClobber__"]){
na.push(tna[x]);
}
}
}else{
try{
window.onload=null;
}
catch(e){
}
na=(this.clobberNodes.length)?this.clobberNodes:document.all;
}
tna=null;
var _474={};
for(var i=na.length-1;i>=0;i=i-1){
var el=na[i];
if(el["__clobberAttrs__"]){
for(var j=0;j<el.__clobberAttrs__.length;j++){
nukeProp(el,el.__clobberAttrs__[j]);
}
nukeProp(el,"__clobberAttrs__");
nukeProp(el,"__doClobber__");
}
}
na=null;
};
};
if(dojo.render.html.ie){
window.onunload=function(){
dojo_ie_clobber.clobber();
try{
if((dojo["widget"])&&(dojo.widget["manager"])){
dojo.widget.manager.destroyAll();
}
}
catch(e){
}
try{
window.onload=null;
}
catch(e){
}
try{
window.onunload=null;
}
catch(e){
}
dojo_ie_clobber.clobberNodes=[];
};
}
dojo.event.browser=new function(){
var _478=0;
this.clean=function(node){
if(dojo.render.html.ie){
dojo_ie_clobber.clobber(node);
}
};
this.addClobberNode=function(node){
if(!node["__doClobber__"]){
node.__doClobber__=true;
dojo_ie_clobber.clobberNodes.push(node);
node.__clobberAttrs__=[];
}
};
this.addClobberNodeAttrs=function(node,_47c){
this.addClobberNode(node);
for(var x=0;x<_47c.length;x++){
node.__clobberAttrs__.push(_47c[x]);
}
};
this.removeListener=function(node,_47f,fp,_481){
if(!_481){
var _481=false;
}
_47f=_47f.toLowerCase();
if(_47f.substr(0,2)=="on"){
_47f=_47f.substr(2);
}
if(node.removeEventListener){
node.removeEventListener(_47f,fp,_481);
}
};
this.addListener=function(node,_483,fp,_485,_486){
if(!node){
return;
}
if(!_485){
var _485=false;
}
_483=_483.toLowerCase();
if(_483.substr(0,2)!="on"){
_483="on"+_483;
}
if(!_486){
var _487=function(evt){
if(!evt){
evt=window.event;
}
var ret=fp(dojo.event.browser.fixEvent(evt));
if(_485){
dojo.event.browser.stopEvent(evt);
}
return ret;
};
}else{
_487=fp;
}
if(node.addEventListener){
node.addEventListener(_483.substr(2),_487,_485);
return _487;
}else{
if(typeof node[_483]=="function"){
var _48a=node[_483];
node[_483]=function(e){
_48a(e);
return _487(e);
};
}else{
node[_483]=_487;
}
if(dojo.render.html.ie){
this.addClobberNodeAttrs(node,[_483]);
}
return _487;
}
};
this.isEvent=function(obj){
return (typeof obj!="undefined")&&(typeof Event!="undefined")&&(obj.eventPhase);
};
this.currentEvent=null;
this.callListener=function(_48d,_48e){
if(typeof _48d!="function"){
dojo.raise("listener not a function: "+_48d);
}
dojo.event.browser.currentEvent.currentTarget=_48e;
return _48d.call(_48e,dojo.event.browser.currentEvent);
};
this.stopPropagation=function(){
dojo.event.browser.currentEvent.cancelBubble=true;
};
this.preventDefault=function(){
dojo.event.browser.currentEvent.returnValue=false;
};
this.keys={KEY_BACKSPACE:8,KEY_TAB:9,KEY_ENTER:13,KEY_SHIFT:16,KEY_CTRL:17,KEY_ALT:18,KEY_PAUSE:19,KEY_CAPS_LOCK:20,KEY_ESCAPE:27,KEY_SPACE:32,KEY_PAGE_UP:33,KEY_PAGE_DOWN:34,KEY_END:35,KEY_HOME:36,KEY_LEFT_ARROW:37,KEY_UP_ARROW:38,KEY_RIGHT_ARROW:39,KEY_DOWN_ARROW:40,KEY_INSERT:45,KEY_DELETE:46,KEY_LEFT_WINDOW:91,KEY_RIGHT_WINDOW:92,KEY_SELECT:93,KEY_F1:112,KEY_F2:113,KEY_F3:114,KEY_F4:115,KEY_F5:116,KEY_F6:117,KEY_F7:118,KEY_F8:119,KEY_F9:120,KEY_F10:121,KEY_F11:122,KEY_F12:123,KEY_NUM_LOCK:144,KEY_SCROLL_LOCK:145};
this.revKeys=[];
for(var key in this.keys){
this.revKeys[this.keys[key]]=key;
}
this.fixEvent=function(evt){
if((!evt)&&(window["event"])){
var evt=window.event;
}
if((evt["type"])&&(evt["type"].indexOf("key")==0)){
evt.keys=this.revKeys;
for(var key in this.keys){
evt[key]=this.keys[key];
}
if((dojo.render.html.ie)&&(evt["type"]=="keypress")){
evt.charCode=evt.keyCode;
}
}
if(dojo.render.html.ie){
if(!evt.target){
evt.target=evt.srcElement;
}
if(!evt.currentTarget){
evt.currentTarget=evt.srcElement;
}
if(!evt.layerX){
evt.layerX=evt.offsetX;
}
if(!evt.layerY){
evt.layerY=evt.offsetY;
}
if(evt.fromElement){
evt.relatedTarget=evt.fromElement;
}
if(evt.toElement){
evt.relatedTarget=evt.toElement;
}
this.currentEvent=evt;
evt.callListener=this.callListener;
evt.stopPropagation=this.stopPropagation;
evt.preventDefault=this.preventDefault;
}
return evt;
};
this.stopEvent=function(ev){
if(window.event){
ev.returnValue=false;
ev.cancelBubble=true;
}else{
ev.preventDefault();
ev.stopPropagation();
}
};
};
dojo.hostenv.conditionalLoadModule({common:["dojo.event","dojo.event.topic"],browser:["dojo.event.browser"],dashboard:["dojo.event.browser"]});
dojo.hostenv.moduleLoaded("dojo.event.*");
dojo.provide("turbo.lib.app");
dojo.require("turbo.lib.align");
dojo.require("dojo.event.*");
turbo.app=new function(){
this.marshall=function(){
var id="";
var _494=dj_global;
for(var i=0;i<arguments.length;i++){
id=arguments[i];
if(!_494[id]){
_494[id]=turbo.$(id);
}
}
return _494[id];
};
this.onresize=function(){
};
this.alignerAlign=function(){
this.onresize();
};
this.resize=function(){
turbo.aligner.align();
};
this._windowResize=function(){
this.resize();
this.resizePending=null;
};
this.windowResize=function(){
if(this.resizePending){
return;
}
this.resizePending=window.setTimeout(turbo.bind(this,"_windowResize"),250);
};
this.display=function(){
turbo.aligner.enable();
this._windowResize();
window.setTimeout(turbo.app.windowResize,500);
turbo.remove("turboCurtain");
};
this.encurtain=function(){
var d=document.createElement("div");
d.innerHTML="<div class='ajax_loader_plugin'><img src='images/ajax_loader_plugin.gif' title='loading plugin' /></div>";
d.id="turboCurtain";
d.style.position="absolute";
d.style.zIndex=9999;
d.style.width="9000px";
d.style.height="9000px";
d.style.backgroundColor="white";
document.body.insertBefore(d,document.body.firstChild);
document.body.style.display="block";
};
this.init=function(){
};
this.initialize=function(){
this.encurtain();
try{
this.init();
}
catch(e){
dojo.debug("turbo.app.init failed: "+e);
turbo.debug(e);
}
window.setTimeout(turbo.bind(this,this.display),100);
dojo.event.connect(turbo.aligner,"align",this,"alignerAlign");
dojo.event.connect(window,"onresize",this,"windowResize");
};
};
dojo.addOnLoad(turbo.app,"initialize");
if(false){
dojo.addOnLoad(function(){
showHideGrids=function(_497){
var _498=dojo.widget.getWidgetsByType("TurboGrid");
for(var i=0;i<_498.length;i++){
turbo.showHide(_498[i].domNode,_497);
}
};
hideGrids=function(){
showHideGrids(false);
};
showGrids=function(){
showHideGrids(true);
};
dojo.event.connect("before",turbo.aligner,"align","hideGrids");
dojo.event.connect("after",turbo.aligner,"align","showGrids");
});
}
dojo.event.topic.registerPublisher("turboresize",turbo.app,"onresize");
dojo.provide("turbo.lib.sql");
turbo.sql={};
turbo.sql.fields=null;
turbo.sql.table="";
turbo.sql.keys=null;
turbo.sql.describeTable=function(_49a,_49b,_49c){
turbo.sql.table=_49a;
turbo.sql.fields=_49b;
turbo.sql.keys=_49c;
};
turbo.sql.templates={createDb:"CREATE DATABASE IF NOT EXISTS {db}",dropDb:"DROP DATABASE {db}",rename:"RENAME TABLE {oldName} TO {newName}",create:"CREATE TABLE IF NOT EXISTS {table} ({columns})",alter:"ALTER TABLE {table} {columns}",drop:"DROP TABLE {table}",insert:"INSERT INTO {table} ({fields}) VALUES({values})",deleteFrom:"DELETE FROM {table} WHERE {where}",update:"UPDATE {table} SET {set} WHERE {where}",select:"SELECT * FROM {table} {where} {orderby}",change:"CHANGE {name} {column} {pos}",dummy:""};
turbo.sql.autoIncTag="%id%";
turbo.sql.eol=";\n";
turbo.sql.mysql=true;
turbo.sql.bq=function(_49d){
if(turbo.sql.mysql){
return "`"+_49d+"`";
}else{
return _49d;
}
};
turbo.sql.no_bq=function(_49e){
return _49e;
};
turbo.sql.valueIsFunction=function(_49f){
return (_49f!==null&&typeof (_49f)=="string"&&_49f.search(/^#/)!=-1);
};
turbo.sql.safeValue=function(_4a0){
if(typeof(_4a0)!='string')
return _4a0;
_4a0=_4a0.replace(/\\/g, "\\\\");	
_4a0=_4a0.replace(/\'/g, "\\'");	
return _4a0;
};
turbo.sql.formatValue=function(_4a1){
if(turbo.sql.valueIsFunction(_4a1)){
return _4a1.replace(/^#/,"");
}else{
return (_4a1===null?"NULL":"'"+turbo.sql.safeValue(_4a1)+"'");
}
};
turbo.sql.buildSetClause=function(_4a2){
var c=_4a2.length;
if(c!=turbo.sql.fields.length){
return "";
}
var _4a4=[];
for(var i=0;i<c;i++){
if(_4a2[i]!==undefined){
_4a4.push(turbo.sql.bq(turbo.sql.fields[i].name)+" = "+turbo.sql.formatValue(_4a2[i]));
}
}
return _4a4.join(", ");
};
turbo.sql.fieldByName=function(_4a6){
for(var i=0;i<turbo.sql.fields.length;i++){
if(turbo.sql.fields[i].name==_4a6){
return i;
}
}
return -1;
};
turbo.sql.buildWherePk=function(_4a8){
var f,v,where=[];
for(var i=0;i<turbo.sql.keys.length;i++){
f=turbo.sql.fieldByName(turbo.sql.keys[i]);
if(f<0){
turbo.debug("turbo.sql.buildWherePk: key/field mismatch for ",turbo.sql.keys[i]," in ",turbo.sql.fields);
}
if(f<0){
throw ("turbo.sql.buildWherePk: key/field mismatch for "+turbo.sql.keys[i]);
}
v=_4a8[f];
if(!dojo.lang.isNumber(v)&&!v&&turbo.sql.fields[f].autoinc){
v=turbo.sql.autoIncTag;
}
where.push(turbo.sql.bq(turbo.sql.keys[i])+" = "+turbo.sql.formatValue(v));
}
return where.join(" AND ");
};
turbo.sql.buildWhereNoPk=function(_4ab){
var _4ac=[];
var c=_4ab.length;
for(var i=0;i<c;i++){
var v=_4ab[i];
v=(v===null?"IS NULL":"= "+turbo.sql.formatValue(v));
_4ac.push(turbo.sql.bq(turbo.sql.fields[i].name)+v);
}
return _4ac.join(" AND ");
};
turbo.sql.buildWhereClause=function(_4b0){
if(turbo.sql.keys.length){
return turbo.sql.buildWherePk(_4b0);
}else{
return turbo.sql.buildWhereNoPk(_4b0);
}
};
turbo.sql.fillTemplate=function(_4b1,_4b2,_4b3){
return turbo.supplant(_4b1,_4b2)+(_4b3?eol:"");
};
turbo.sql.build=function(_4b4,_4b5,_4b6){
if(!_4b5.table){
_4b5.table=turbo.sql.bq(turbo.sql.table);
}
return turbo.sql.fillTemplate(turbo.sql.templates[_4b4],_4b5,_4b6);
};
turbo.sql.buildSelectRowQuery=function(_4b7){
return turbo.sql.build("select",{where:(_4b7?"WHERE "+_4b7:""),orderby:"",limit:" LIMIT 1"});
};
turbo.sql.buildUpdateQuery=function(_4b8,_4b9){
return turbo.sql.build("update",{set:_4b8,where:_4b9})+(turbo.sql.mysql?" LIMIT 1":"");
};
turbo.sql.buildInsertNames=function(_4ba){
var _4bb=[];
for(var i=0;i<turbo.sql.fields.length;i++){
if(_4ba[i]){
_4bb.push(turbo.sql.bq(turbo.sql.fields[i].name));
}
}
return _4bb.join(", ");
};
turbo.sql.buildInsertValues=function(_4bd){
var _4be=[];
for(var i=0;i<_4bd.length;i++){
if(_4bd[i]){
_4be.push(turbo.sql.formatValue(_4bd[i]));
}
}
return _4be.join(", ");
};
turbo.sql.buildInsertQuery=function(_4c0){
return turbo.sql.build("insert",{fields:turbo.sql.buildInsertNames(_4c0),values:turbo.sql.buildInsertValues(_4c0)});
};
turbo.sql.buildColumn=function(_4c1){
var name=turbo.sql.bq(_4c1.name)+" ";
var type=_4c1.type+" ";
var _4c4=(_4c1.values?" ("+_4c1.values+")":"");
var _4c5=_4c1.length+" ";
var def=(_4c1.defaultValue?" DEFAULT '"+_4c1.defaultValue+"'":"");
var _4c7=(!_4c1.allownull?" NOT NULL":"");
var auto=(_4c1.autoinc?" AUTO_INCREMENT":"");
var _4c9=(_4c1.unsigned?" UNSIGNED":"");
var _4ca=(_4c1.zerofill?" ZEROFILL":"");
var _4cb=(_4c1.binary?" BINARY":"");
return name+type+_4c4+_4c5+def+_4c7+auto+_4c9+_4ca+_4cb;
};
turbo.sql.buildChangeClause=function(_4cc,_4cd){
return turbo.sql.build("change",{name:turbo.sql.bq(_4cc.nee),column:turbo.sql.buildColumn(_4cc),pos:(_4cd?"AFTER "+_4cd:"FIRST")});
};
turbo.sql.buildChangeClauses=function(_4ce){
var _4cf=[];
var _4d0=null;
for(var i in _4ce){
var row=_4ce[i];
_4cf.push(turbo.sql.buildChangeClause(row,_4d0));
_4d0=row.name;
}
return _4cf.join(",");
};
turbo.sql.buildAlterQuery=function(_4d3){
return turbo.sql.build("alter",{columns:turbo.sql.buildChangeClauses(_4d3)});
};
turbo.sql.buildDeleteQuery=function(_4d4){
return turbo.sql.build("deleteFrom",{where:_4d4})+(turbo.sql.mysql?" LIMIT 1":"");
};
dojo.provide("turbo.lib.sqlparse");
dojo.require("turbo.lib.sql");
turbo.sql.parser=new function(){
this.debug=false;
this.oob=function(){
return (this.p<0||this.p>=this.l);
};
this.search=function(_4d5){
this.p=this.s.search(_4d5);
if(this.oob()){
return false;
}
this.d=this.s.charAt(this.p);
return true;
};
this.tokenize=function(inD){
this.p+=(inD?inD:0);
if(this.p){
var tok=this.s.substr(0,this.p);
this.t+=tok;
if(this.debug&&this.t&&tok){
out("token: ["+this.t+"]<br>");
}
this.s=this.s.substr(this.p);
this.l=this.s.length;
this.p=0;
}
};
this.pusht=function(){
if(this.t){
this.r.push(this.t);
}
};
this.pushToken=function(inD){
this.tokenize(inD);
this.pusht();
this.t="";
};
this.start=function(inS){
this.s=inS;
this.l=this.s.length;
this.r=[];
this.d="";
this.p=0;
this.t="";
this.analyze();
};
this.finish=function(){
this.t+=this.s;
this.pusht();
};
this.doSeparator=function(){
if(this.debug){
out("doSeparator:<br>");
}
this.tokenize(1);
};
this.doEscape=function(){
if(this.debug){
out("doEscape:<br>");
}
this.tokenize(2);
};
this.doSqlComment=function(){
if(this.debug){
out("doDashComment:<br>");
}
this.tokenize((this.d=="-"?2:1));
var n=1;
if(this.search(/[\n]/)){
this.tokenize(1);
}
};
this.doCComment=function(){
if(this.debug){
out("doCComment:<br>");
}
this.tokenize(2);
var n=1;
while(n&&this.search(/\/\*|\*\//)){
switch(this.d){
case "/":
n++;
break;
case "*":
n--;
break;
}
this.tokenize(2);
}
};
this.doLiteral=function(){
if(this.debug){
out("doLiteral:<br>");
}
this.tokenize(1);
var rx=new RegExp(this.d+"|\\\\");
while(this.search(rx)){
switch(this.d){
case "\\":
this.doEscape();
break;
default:
this.tokenize(1);
return;
}
}
};
this.analyze=function(){
while(this.search(/--|\/\*|[#'"`\\;]/)){
if(this.d=="\\"){
this.doEscape();
}else{
this.pushToken();
switch(this.d){
case ";":
this.doSeparator();
break;
case "/":
this.doCComment();
break;
case "-":
case "#":
this.doSqlComment();
break;
default:
this.doLiteral();
break;
}
this.pushToken();
}
}
this.finish();
};
};
turbo.sql.splitSql=function(_4dd){
var t=";";
turbo.sql.parser.start(_4dd);
var _4df=turbo.sql.parser.r;
result=Array();
x="";
for(var i in _4df){
if(_4df[i]!=t){
x+=_4df[i];
}else{
result.push(x);
x="";
}
}
if(x&&x!=t){
result.push(x);
}
return result;
};
turbo.sql.analyzer=new function(){
this.sqlTokens="";
this.setTokens=function(_4e1){
this.sqlTokens=_4e1;
};
this.tokenIsCommand=function(tok){
return (tok.search(/^(--|\/\*|[#'"`\\;])/)==-1);
};
this.searchCommands=function(inRe){
for(var i in this.sqlTokens){
if(this.tokenIsCommand(this.sqlTokens[i])){
if(inRe.test(this.sqlTokens[i])){
return true;
}
}
}
};
this.hasDropDb=function(){
return this.searchCommands(new RegExp("DROP.*DATABASE","i"));
};
this.hasDropTable=function(){
return this.searchCommands(new RegExp("DROP.*TABLE","i"));
};
this.hasAlterTable=function(){
return this.searchCommands(new RegExp("ALTER.*TABLE","i"));
};
this.hasDelete=function(){
return this.searchCommands(new RegExp("DELETE","i"));
};
this.hasBadSql=function(){
return this.searchCommands(new RegExp("LOAD|DATA\\s|INFILE","i"));
};
};
dojo.provide("dojo.xml.Parse");
dojo.require("dojo.dom");
dojo.xml.Parse=function(){
this.parseFragment=function(_4e5){
var _4e6={};
var _4e7=dojo.dom.getTagName(_4e5);
_4e6[_4e7]=new Array(_4e5.tagName);
var _4e8=this.parseAttributes(_4e5);
for(var attr in _4e8){
if(!_4e6[attr]){
_4e6[attr]=[];
}
_4e6[attr][_4e6[attr].length]=_4e8[attr];
}
var _4ea=_4e5.childNodes;
for(var _4eb in _4ea){
switch(_4ea[_4eb].nodeType){
case dojo.dom.ELEMENT_NODE:
_4e6[_4e7].push(this.parseElement(_4ea[_4eb]));
break;
case dojo.dom.TEXT_NODE:
if(_4ea.length==1){
if(!_4e6[_4e5.tagName]){
_4e6[_4e7]=[];
}
_4e6[_4e7].push({value:_4ea[0].nodeValue});
}
break;
}
}
return _4e6;
};
this.parseElement=function(node,_4ed,_4ee,_4ef){
var _4f0={};
var _4f1=dojo.dom.getTagName(node);
_4f0[_4f1]=[];
if((!_4ee)||(_4f1.substr(0,4).toLowerCase()=="dojo")){
var _4f2=this.parseAttributes(node);
for(var attr in _4f2){
if((!_4f0[_4f1][attr])||(typeof _4f0[_4f1][attr]!="array")){
_4f0[_4f1][attr]=[];
}
_4f0[_4f1][attr].push(_4f2[attr]);
}
_4f0[_4f1].nodeRef=node;
_4f0.tagName=_4f1;
_4f0.index=_4ef||0;
}
var _4f4=0;
for(var i=0;i<node.childNodes.length;i++){
var tcn=node.childNodes.item(i);
switch(tcn.nodeType){
case dojo.dom.ELEMENT_NODE:
_4f4++;
var ctn=dojo.dom.getTagName(tcn);
if(!_4f0[ctn]){
_4f0[ctn]=[];
}
_4f0[ctn].push(this.parseElement(tcn,true,_4ee,_4f4));
if((tcn.childNodes.length==1)&&(tcn.childNodes.item(0).nodeType==dojo.dom.TEXT_NODE)){
_4f0[ctn][_4f0[ctn].length-1].value=tcn.childNodes.item(0).nodeValue;
}
break;
case dojo.dom.TEXT_NODE:
if(node.childNodes.length==1){
_4f0[_4f1].push({value:node.childNodes.item(0).nodeValue});
}
break;
default:
break;
}
}
return _4f0;
};
this.parseAttributes=function(node){
var _4f9={};
var atts=node.attributes;
for(var i=0;i<atts.length;i++){
var _4fc=atts.item(i);
if((dojo.render.html.capable)&&(dojo.render.html.ie)){
if(!_4fc){
continue;
}
if((typeof _4fc=="object")&&(typeof _4fc.nodeValue=="undefined")||(_4fc.nodeValue==null)||(_4fc.nodeValue=="")){
continue;
}
}
var nn=(_4fc.nodeName.indexOf("dojo:")==-1)?_4fc.nodeName:_4fc.nodeName.split("dojo:")[1];
_4f9[nn]={value:_4fc.nodeValue};
}
return _4f9;
};
};
dojo.provide("dojo.widget.Manager");
dojo.require("dojo.lang.array");
dojo.require("dojo.event.*");
dojo.widget.manager=new function(){
this.widgets=[];
this.widgetIds=[];
this.topWidgets={};
var _4fe={};
var _4ff=[];
this.getUniqueId=function(_500){
return _500+"_"+(_4fe[_500]!=undefined?++_4fe[_500]:_4fe[_500]=0);
};
this.add=function(_501){
dojo.profile.start("dojo.widget.manager.add");
this.widgets.push(_501);
if(_501.widgetId==""){
if(_501["id"]){
_501.widgetId=_501["id"];
}else{
if(_501.extraArgs["id"]){
_501.widgetId=_501.extraArgs["id"];
}else{
_501.widgetId=this.getUniqueId(_501.widgetType);
}
}
}
if(this.widgetIds[_501.widgetId]){
dojo.debug("widget ID collision on ID: "+_501.widgetId);
}
this.widgetIds[_501.widgetId]=_501;
dojo.profile.end("dojo.widget.manager.add");
};
this.destroyAll=function(){
for(var x=this.widgets.length-1;x>=0;x--){
try{
this.widgets[x].destroy(true);
delete this.widgets[x];
}
catch(e){
}
}
};
this.remove=function(_503){
var tw=this.widgets[_503].widgetId;
delete this.widgetIds[tw];
this.widgets.splice(_503,1);
};
this.removeById=function(id){
for(var i=0;i<this.widgets.length;i++){
if(this.widgets[i].widgetId==id){
this.remove(i);
break;
}
}
};
this.getWidgetById=function(id){
return this.widgetIds[id];
};
this.getWidgetsByType=function(type){
var lt=type.toLowerCase();
var ret=[];
dojo.lang.forEach(this.widgets,function(x){
if(x.widgetType.toLowerCase()==lt){
ret.push(x);
}
});
return ret;
};
this.getWidgetsOfType=function(id){
dj_deprecated("getWidgetsOfType is depecrecated, use getWidgetsByType");
return dojo.widget.manager.getWidgetsByType(id);
};
this.getWidgetsByFilter=function(_50d){
var ret=[];
dojo.lang.forEach(this.widgets,function(x){
if(_50d(x)){
ret.push(x);
}
});
return ret;
};
this.getAllWidgets=function(){
return this.widgets.concat();
};
this.getWidgetByNode=function(node){
var w=this.getAllWidgets();
for(var i=0;i<w.length;i++){
if(w[i].domNode==node){
return w[i];
}
}
return null;
};
this.byId=this.getWidgetById;
this.byType=this.getWidgetsByType;
this.byFilter=this.getWidgetsByFilter;
this.byNode=this.getWidgetByNode;
var _513={};
var _514=["dojo.widget"];
for(var i=0;i<_514.length;i++){
_514[_514[i]]=true;
}
this.registerWidgetPackage=function(_516){
if(!_514[_516]){
_514[_516]=true;
_514.push(_516);
}
};
this.getWidgetPackageList=function(){
return dojo.lang.map(_514,function(elt){
return (elt!==true?elt:undefined);
});
};
this.getImplementation=function(_518,_519,_51a){
var impl=this.getImplementationName(_518);
if(impl){
var ret=new impl(_519);
return ret;
}
};
this.getImplementationName=function(_51d){
var _51e=_51d.toLowerCase();
var impl=_513[_51e];
if(impl){
return impl;
}
if(!_4ff.length){
for(var _520 in dojo.render){
if(dojo.render[_520]["capable"]===true){
var _521=dojo.render[_520].prefixes;
for(var i=0;i<_521.length;i++){
_4ff.push(_521[i].toLowerCase());
}
}
}
_4ff.push("");
}
for(var i=0;i<_514.length;i++){
var _523=dojo.evalObjPath(_514[i]);
if(!_523){
continue;
}
for(var j=0;j<_4ff.length;j++){
if(!_523[_4ff[j]]){
continue;
}
for(var _525 in _523[_4ff[j]]){
if(_525.toLowerCase()!=_51e){
continue;
}
_513[_51e]=_523[_4ff[j]][_525];
return _513[_51e];
}
}
for(var j=0;j<_4ff.length;j++){
for(var _525 in _523){
if(_525.toLowerCase()!=(_4ff[j]+_51e)){
continue;
}
_513[_51e]=_523[_525];
return _513[_51e];
}
}
}
throw new Error("Could not locate \""+_51d+"\" class");
};
this.resizing=false;
this.onResized=function(){
if(this.resizing){
return;
}
try{
this.resizing=true;
for(var id in this.topWidgets){
var _527=this.topWidgets[id];
if(_527.onResized){
_527.onResized();
}
}
}
finally{
this.resizing=false;
}
};
if(typeof window!="undefined"){
dojo.addOnLoad(this,"onResized");
dojo.event.connect(window,"onresize",this,"onResized");
}
};
dojo.widget.getUniqueId=function(){
return dojo.widget.manager.getUniqueId.apply(dojo.widget.manager,arguments);
};
dojo.widget.addWidget=function(){
return dojo.widget.manager.add.apply(dojo.widget.manager,arguments);
};
dojo.widget.destroyAllWidgets=function(){
return dojo.widget.manager.destroyAll.apply(dojo.widget.manager,arguments);
};
dojo.widget.removeWidget=function(){
return dojo.widget.manager.remove.apply(dojo.widget.manager,arguments);
};
dojo.widget.removeWidgetById=function(){
return dojo.widget.manager.removeById.apply(dojo.widget.manager,arguments);
};
dojo.widget.getWidgetById=function(){
return dojo.widget.manager.getWidgetById.apply(dojo.widget.manager,arguments);
};
dojo.widget.getWidgetsByType=function(){
return dojo.widget.manager.getWidgetsByType.apply(dojo.widget.manager,arguments);
};
dojo.widget.getWidgetsByFilter=function(){
return dojo.widget.manager.getWidgetsByFilter.apply(dojo.widget.manager,arguments);
};
dojo.widget.byId=function(){
return dojo.widget.manager.getWidgetById.apply(dojo.widget.manager,arguments);
};
dojo.widget.byType=function(){
return dojo.widget.manager.getWidgetsByType.apply(dojo.widget.manager,arguments);
};
dojo.widget.byFilter=function(){
return dojo.widget.manager.getWidgetsByFilter.apply(dojo.widget.manager,arguments);
};
dojo.widget.byNode=function(){
return dojo.widget.manager.getWidgetByNode.apply(dojo.widget.manager,arguments);
};
dojo.widget.all=function(n){
var _529=dojo.widget.manager.getAllWidgets.apply(dojo.widget.manager,arguments);
if(arguments.length>0){
return _529[n];
}
return _529;
};
dojo.widget.registerWidgetPackage=function(){
return dojo.widget.manager.registerWidgetPackage.apply(dojo.widget.manager,arguments);
};
dojo.widget.getWidgetImplementation=function(){
return dojo.widget.manager.getImplementation.apply(dojo.widget.manager,arguments);
};
dojo.widget.getWidgetImplementationName=function(){
return dojo.widget.manager.getImplementationName.apply(dojo.widget.manager,arguments);
};
dojo.widget.widgets=dojo.widget.manager.widgets;
dojo.widget.widgetIds=dojo.widget.manager.widgetIds;
dojo.widget.root=dojo.widget.manager.root;
dojo.provide("dojo.widget.Widget");
dojo.provide("dojo.widget.tags");
dojo.require("dojo.lang.func");
dojo.require("dojo.lang.array");
dojo.require("dojo.widget.Manager");
dojo.require("dojo.event.*");
dojo.widget.Widget=function(){
this.children=[];
this.extraArgs={};
};
dojo.lang.extend(dojo.widget.Widget,{parent:null,isTopLevel:false,isModal:false,isEnabled:true,isHidden:false,isContainer:false,widgetId:"",widgetType:"Widget",toString:function(){
return "[Widget "+this.widgetType+", "+(this.widgetId||"NO ID")+"]";
},repr:function(){
return this.toString();
},enable:function(){
this.isEnabled=true;
},disable:function(){
this.isEnabled=false;
},hide:function(){
this.isHidden=true;
},show:function(){
this.isHidden=false;
},onResized:function(){
this.notifyChildrenOfResize();
},notifyChildrenOfResize:function(){
for(var i=0;i<this.children.length;i++){
var _52b=this.children[i];
if(_52b.onResized){
_52b.onResized();
}
}
},create:function(args,_52d,_52e){
this.satisfyPropertySets(args,_52d,_52e);
this.mixInProperties(args,_52d,_52e);
this.postMixInProperties(args,_52d,_52e);
dojo.widget.manager.add(this);
this.buildRendering(args,_52d,_52e);
this.initialize(args,_52d,_52e);
this.postInitialize(args,_52d,_52e);
this.postCreate(args,_52d,_52e);
return this;
},destroy:function(_52f){
this.destroyChildren();
this.uninitialize();
this.destroyRendering(_52f);
dojo.widget.manager.removeById(this.widgetId);
},destroyChildren:function(){
while(this.children.length>0){
var tc=this.children[0];
this.removeChild(tc);
tc.destroy();
}
},getChildrenOfType:function(type,_532){
var ret=[];
var _534=dojo.lang.isFunction(type);
if(!_534){
type=type.toLowerCase();
}
for(var x=0;x<this.children.length;x++){
if(_534){
if(this.children[x] instanceof type){
ret.push(this.children[x]);
}
}else{
if(this.children[x].widgetType.toLowerCase()==type){
ret.push(this.children[x]);
}
}
if(_532){
ret=ret.concat(this.children[x].getChildrenOfType(type,_532));
}
}
return ret;
},getDescendants:function(){
var _536=[];
var _537=[this];
var elem;
while(elem=_537.pop()){
_536.push(elem);
dojo.lang.forEach(elem.children,function(elem){
_537.push(elem);
});
}
return _536;
},satisfyPropertySets:function(args){
return args;
},mixInProperties:function(args,frag){
if((args["fastMixIn"])||(frag["fastMixIn"])){
for(var x in args){
this[x]=args[x];
}
return;
}
var _53e;
var _53f=dojo.widget.lcArgsCache[this.widgetType];
if(_53f==null){
_53f={};
for(var y in this){
_53f[((new String(y)).toLowerCase())]=y;
}
dojo.widget.lcArgsCache[this.widgetType]=_53f;
}
var _541={};
for(var x in args){
if(!this[x]){
var y=_53f[(new String(x)).toLowerCase()];
if(y){
args[y]=args[x];
x=y;
}
}
if(_541[x]){
continue;
}
_541[x]=true;
if((typeof this[x])!=(typeof _53e)){
if(typeof args[x]!="string"){
this[x]=args[x];
}else{
if(dojo.lang.isString(this[x])){
this[x]=args[x];
}else{
if(dojo.lang.isNumber(this[x])){
this[x]=new Number(args[x]);
}else{
if(dojo.lang.isBoolean(this[x])){
this[x]=(args[x].toLowerCase()=="false")?false:true;
}else{
if(dojo.lang.isFunction(this[x])){
var tn=dojo.lang.nameAnonFunc(new Function(args[x]),this);
dojo.event.connect(this,x,this,tn);
}else{
if(dojo.lang.isArray(this[x])){
this[x]=args[x].split(";");
}else{
if(this[x] instanceof Date){
this[x]=new Date(Number(args[x]));
}else{
if(typeof this[x]=="object"){
var _543=args[x].split(";");
for(var y=0;y<_543.length;y++){
var si=_543[y].indexOf(":");
if((si!=-1)&&(_543[y].length>si)){
this[x][_543[y].substr(0,si).replace(/^\s+|\s+$/g,"")]=_543[y].substr(si+1);
}
}
}else{
this[x]=args[x];
}
}
}
}
}
}
}
}
}else{
this.extraArgs[x]=args[x];
}
}
},postMixInProperties:function(){
},initialize:function(args,frag){
return false;
},postInitialize:function(args,frag){
return false;
},postCreate:function(args,frag){
return false;
},uninitialize:function(){
return false;
},buildRendering:function(){
dj_unimplemented("dojo.widget.Widget.buildRendering, on "+this.toString()+", ");
return false;
},destroyRendering:function(){
dj_unimplemented("dojo.widget.Widget.destroyRendering");
return false;
},cleanUp:function(){
dj_unimplemented("dojo.widget.Widget.cleanUp");
return false;
},addedTo:function(_54b){
},addChild:function(_54c){
dj_unimplemented("dojo.widget.Widget.addChild");
return false;
},removeChild:function(_54d){
for(var x=0;x<this.children.length;x++){
if(this.children[x]===_54d){
this.children.splice(x,1);
break;
}
}
return _54d;
},resize:function(_54f,_550){
this.setWidth(_54f);
this.setHeight(_550);
},setWidth:function(_551){
if((typeof _551=="string")&&(_551.substr(-1)=="%")){
this.setPercentageWidth(_551);
}else{
this.setNativeWidth(_551);
}
},setHeight:function(_552){
if((typeof _552=="string")&&(_552.substr(-1)=="%")){
this.setPercentageHeight(_552);
}else{
this.setNativeHeight(_552);
}
},setPercentageHeight:function(_553){
return false;
},setNativeHeight:function(_554){
return false;
},setPercentageWidth:function(_555){
return false;
},setNativeWidth:function(_556){
return false;
},getPreviousSibling:function(){
var idx=this.getParentIndex();
if(idx<=0){
return null;
}
return this.getSiblings()[idx-1];
},getSiblings:function(){
return this.parent.children;
},getParentIndex:function(){
return dojo.lang.indexOf(this.getSiblings(),this,true);
},getNextSibling:function(){
var idx=this.getParentIndex();
if(idx==this.getSiblings().length-1){
return null;
}
if(idx<0){
return null;
}
return this.getSiblings()[idx+1];
}});
dojo.widget.lcArgsCache={};
dojo.widget.tags={};
dojo.widget.tags.addParseTreeHandler=function(type){
var _55a=type.toLowerCase();
this[_55a]=function(_55b,_55c,_55d,_55e,_55f){
return dojo.widget.buildWidgetFromParseTree(_55a,_55b,_55c,_55d,_55e,_55f);
};
};
dojo.widget.tags.addParseTreeHandler("dojo:widget");
dojo.widget.tags["dojo:propertyset"]=function(_560,_561,_562){
var _563=_561.parseProperties(_560["dojo:propertyset"]);
};
dojo.widget.tags["dojo:connect"]=function(_564,_565,_566){
var _567=_565.parseProperties(_564["dojo:connect"]);
};
dojo.widget.buildWidgetFromParseTree=function(type,frag,_56a,_56b,_56c,_56d){
var _56e=type.split(":");
_56e=(_56e.length==2)?_56e[1]:type;
var _56f=_56d||_56a.parseProperties(frag["dojo:"+_56e]);
var _570=dojo.widget.manager.getImplementation(_56e);
if(!_570){
throw new Error("cannot find \""+_56e+"\" widget");
}else{
if(!_570.create){
throw new Error("\""+_56e+"\" widget object does not appear to implement *Widget");
}
}
_56f["dojoinsertionindex"]=_56c;
var ret=_570.create(_56f,frag,_56b);
return ret;
};
dojo.provide("dojo.widget.Parse");
dojo.require("dojo.widget.Manager");
dojo.require("dojo.dom");
dojo.widget.Parse=function(_572){
this.propertySetsList=[];
this.fragment=_572;
this.createComponents=function(_573,_574){
var _575=dojo.widget.tags;
var _576=[];
for(var item in _573){
var _578=false;
try{
if(_573[item]&&(_573[item]["tagName"])&&(_573[item]!=_573["nodeRef"])){
var tn=new String(_573[item]["tagName"]);
var tna=tn.split(";");
for(var x=0;x<tna.length;x++){
var ltn=(tna[x].replace(/^\s+|\s+$/g,"")).toLowerCase();
if(_575[ltn]){
_578=true;
_573[item].tagName=ltn;
var ret=_575[ltn](_573[item],this,_574,_573[item]["index"]);
_576.push(ret);
}else{
if((dojo.lang.isString(ltn))&&(ltn.substr(0,5)=="dojo:")){
dojo.debug("no tag handler registed for type: ",ltn);
}
}
}
}
}
catch(e){
dojo.debug("fragment creation error:",e);
}
if((!_578)&&(typeof _573[item]=="object")&&(_573[item]!=_573.nodeRef)&&(_573[item]!=_573["tagName"])){
_576.push(this.createComponents(_573[item],_574));
}
}
return _576;
};
this.parsePropertySets=function(_57e){
return [];
var _57f=[];
for(var item in _57e){
if((_57e[item]["tagName"]=="dojo:propertyset")){
_57f.push(_57e[item]);
}
}
this.propertySetsList.push(_57f);
return _57f;
};
this.parseProperties=function(_581){
var _582={};
for(var item in _581){
if((_581[item]==_581["tagName"])||(_581[item]==_581.nodeRef)){
}else{
if((_581[item]["tagName"])&&(dojo.widget.tags[_581[item].tagName.toLowerCase()])){
}else{
if((_581[item][0])&&(_581[item][0].value!="")&&(_581[item][0].value!=null)){
try{
if(item.toLowerCase()=="dataprovider"){
var _584=this;
this.getDataProvider(_584,_581[item][0].value);
_582.dataProvider=this.dataProvider;
}
_582[item]=_581[item][0].value;
var _585=this.parseProperties(_581[item]);
for(var _586 in _585){
_582[_586]=_585[_586];
}
}
catch(e){
dojo.debug(e);
}
}
}
}
}
return _582;
};
this.getDataProvider=function(_587,_588){
dojo.io.bind({url:_588,load:function(type,_58a){
if(type=="load"){
_587.dataProvider=_58a;
}
},mimetype:"text/javascript",sync:true});
};
this.getPropertySetById=function(_58b){
for(var x=0;x<this.propertySetsList.length;x++){
if(_58b==this.propertySetsList[x]["id"][0].value){
return this.propertySetsList[x];
}
}
return "";
};
this.getPropertySetsByType=function(_58d){
var _58e=[];
for(var x=0;x<this.propertySetsList.length;x++){
var cpl=this.propertySetsList[x];
var cpcc=cpl["componentClass"]||cpl["componentType"]||null;
if((cpcc)&&(propertySetId==cpcc[0].value)){
_58e.push(cpl);
}
}
return _58e;
};
this.getPropertySets=function(_592){
var ppl="dojo:propertyproviderlist";
var _594=[];
var _595=_592["tagName"];
if(_592[ppl]){
var _596=_592[ppl].value.split(" ");
for(propertySetId in _596){
if((propertySetId.indexOf("..")==-1)&&(propertySetId.indexOf("://")==-1)){
var _597=this.getPropertySetById(propertySetId);
if(_597!=""){
_594.push(_597);
}
}else{
}
}
}
return (this.getPropertySetsByType(_595)).concat(_594);
};
this.createComponentFromScript=function(_598,_599,_59a){
var ltn="dojo:"+_599.toLowerCase();
if(dojo.widget.tags[ltn]){
_59a.fastMixIn=true;
return [dojo.widget.tags[ltn](_59a,this,null,null,_59a)];
}else{
if(ltn.substr(0,5)=="dojo:"){
dojo.debug("no tag handler registed for type: ",ltn);
}
}
};
};
dojo.widget._parser_collection={"dojo":new dojo.widget.Parse()};
dojo.widget.getParser=function(name){
if(!name){
name="dojo";
}
if(!this._parser_collection[name]){
this._parser_collection[name]=new dojo.widget.Parse();
}
return this._parser_collection[name];
};
dojo.widget.createWidget=function(name,_59e,_59f,_5a0){
function fromScript(_5a1,name,_5a3){
var _5a4=name.toLowerCase();
var _5a5="dojo:"+_5a4;
_5a3[_5a5]={dojotype:[{value:_5a4}],nodeRef:_5a1,fastMixIn:true};
return dojo.widget.getParser().createComponentFromScript(_5a1,name,_5a3,true);
}
if(typeof name!="string"&&typeof _59e=="string"){
dojo.deprecated("dojo.widget.createWidget","argument order is now of the form "+"dojo.widget.createWidget(NAME, [PROPERTIES, [REFERENCENODE, [POSITION]]])");
return fromScript(name,_59e,_59f);
}
_59e=_59e||{};
var _5a6=false;
var tn=null;
var h=dojo.render.html.capable;
if(h){
tn=document.createElement("span");
}
if(!_59f){
_5a6=true;
_59f=tn;
if(h){
document.body.appendChild(_59f);
}
}else{
if(_5a0){
dojo.dom.insertAtPosition(tn,_59f,_5a0);
}else{
tn=_59f;
}
}
var _5a9=fromScript(tn,name,_59e);
if(!_5a9[0]||typeof _5a9[0].widgetType=="undefined"){
throw new Error("createWidget: Creation of \""+name+"\" widget failed.");
}
if(_5a6){
if(_5a9[0].domNode.parentNode){
_5a9[0].domNode.parentNode.removeChild(_5a9[0].domNode);
}
}
return _5a9[0];
};
dojo.widget.fromScript=function(name,_5ab,_5ac,_5ad){
dojo.deprecated("dojo.widget.fromScript"," use "+"dojo.widget.createWidget instead");
return dojo.widget.createWidget(name,_5ab,_5ac,_5ad);
};
dojo.provide("dojo.uri.Uri");
dojo.uri=new function(){
this.joinPath=function(){
var arr=[];
for(var i=0;i<arguments.length;i++){
arr.push(arguments[i]);
}
return arr.join("/").replace(/\/{2,}/g,"/").replace(/((https*|ftps*):)/i,"$1/");
};
this.dojoUri=function(uri){
return new dojo.uri.Uri(dojo.hostenv.getBaseScriptUri(),uri);
};
this.Uri=function(){
var uri=arguments[0];
for(var i=1;i<arguments.length;i++){
if(!arguments[i]){
continue;
}
var _5b3=new dojo.uri.Uri(arguments[i].toString());
var _5b4=new dojo.uri.Uri(uri.toString());
if(_5b3.path==""&&_5b3.scheme==null&&_5b3.authority==null&&_5b3.query==null){
if(_5b3.fragment!=null){
_5b4.fragment=_5b3.fragment;
}
_5b3=_5b4;
}else{
if(_5b3.scheme==null){
_5b3.scheme=_5b4.scheme;
if(_5b3.authority==null){
_5b3.authority=_5b4.authority;
if(_5b3.path.charAt(0)!="/"){
var path=_5b4.path.substring(0,_5b4.path.lastIndexOf("/")+1)+_5b3.path;
var segs=path.split("/");
for(var j=0;j<segs.length;j++){
if(segs[j]=="."){
if(j==segs.length-1){
segs[j]="";
}else{
segs.splice(j,1);
j--;
}
}else{
if(j>0&&!(j==1&&segs[0]=="")&&segs[j]==".."&&segs[j-1]!=".."){
if(j==segs.length-1){
segs.splice(j,1);
segs[j-1]="";
}else{
segs.splice(j-1,2);
j-=2;
}
}
}
}
_5b3.path=segs.join("/");
}
}
}
}
uri="";
if(_5b3.scheme!=null){
uri+=_5b3.scheme+":";
}
if(_5b3.authority!=null){
uri+="//"+_5b3.authority;
}
uri+=_5b3.path;
if(_5b3.query!=null){
uri+="?"+_5b3.query;
}
if(_5b3.fragment!=null){
uri+="#"+_5b3.fragment;
}
}
this.uri=uri.toString();
var _5b8="^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\\?([^#]*))?(#(.*))?$";
var r=this.uri.match(new RegExp(_5b8));
this.scheme=r[2]||(r[1]?"":null);
this.authority=r[4]||(r[3]?"":null);
this.path=r[5];
this.query=r[7]||(r[6]?"":null);
this.fragment=r[9]||(r[8]?"":null);
if(this.authority!=null){
_5b8="^((([^:]+:)?([^@]+))@)?([^:]*)(:([0-9]+))?$";
r=this.authority.match(new RegExp(_5b8));
this.user=r[3]||null;
this.password=r[4]||null;
this.host=r[5];
this.port=r[7]||null;
}
this.toString=function(){
return this.uri;
};
};
};
dojo.hostenv.conditionalLoadModule({common:["dojo.uri.Uri",false,false]});
dojo.hostenv.moduleLoaded("dojo.uri.*");
dojo.provide("dojo.widget.DomWidget");
dojo.require("dojo.event.*");
dojo.require("dojo.widget.Widget");
dojo.require("dojo.dom");
dojo.require("dojo.xml.Parse");
dojo.require("dojo.uri.*");
dojo.require("dojo.lang.func");
dojo.widget._cssFiles={};
dojo.widget._templateCache={};
dojo.widget.defaultStrings={dojoRoot:dojo.hostenv.getBaseScriptUri(),baseScriptUri:dojo.hostenv.getBaseScriptUri()};
dojo.widget.buildFromTemplate=function(){
dojo.lang.forward("fillFromTemplateCache");
};
dojo.widget.fillFromTemplateCache=function(obj,_5bb,_5bc,_5bd,_5be){
var _5bf=_5bb||obj.templatePath;
var _5c0=_5bc||obj.templateCssPath;
if(_5bf&&!(_5bf instanceof dojo.uri.Uri)){
_5bf=dojo.uri.dojoUri(_5bf);
dojo.deprecated("templatePath should be of type dojo.uri.Uri");
}
if(_5c0&&!(_5c0 instanceof dojo.uri.Uri)){
_5c0=dojo.uri.dojoUri(_5c0);
dojo.deprecated("templateCssPath should be of type dojo.uri.Uri");
}
var _5c1=dojo.widget._templateCache;
if(!obj["widgetType"]){
do{
var _5c2="__dummyTemplate__"+dojo.widget._templateCache.dummyCount++;
}while(_5c1[_5c2]);
obj.widgetType=_5c2;
}
var wt=obj.widgetType;
if((_5c0)&&(!dojo.widget._cssFiles[_5c0])){
dojo.style.insertCssFile(_5c0);
obj.templateCssPath=null;
dojo.widget._cssFiles[_5c0]=true;
}
var ts=_5c1[wt];
if(!ts){
_5c1[wt]={"string":null,"node":null};
if(_5be){
ts={};
}else{
ts=_5c1[wt];
}
}
if(!obj.templateString){
obj.templateString=_5bd||ts["string"];
}
if(!obj.templateNode){
obj.templateNode=ts["node"];
}
if((!obj.templateNode)&&(!obj.templateString)&&(_5bf)){
var _5c5=dojo.hostenv.getText(_5bf);
if(_5c5){
var _5c6=_5c5.match(/<body[^>]*>\s*([\s\S]+)\s*<\/body>/im);
if(_5c6){
_5c5=_5c6[1];
}
}else{
_5c5="";
}
obj.templateString=_5c5;
if(!_5be){
_5c1[wt]["string"]=_5c5;
}
}
if((!ts["string"])&&(!_5be)){
ts.string=obj.templateString;
}
};
dojo.widget._templateCache.dummyCount=0;
dojo.widget.attachProperties=["dojoAttachPoint","id"];
dojo.widget.eventAttachProperty="dojoAttachEvent";
dojo.widget.onBuildProperty="dojoOnBuild";
dojo.widget.attachTemplateNodes=function(_5c7,_5c8,_5c9){
var _5ca=dojo.dom.ELEMENT_NODE;
function trim(str){
return str.replace(/^\s+|\s+$/g,"");
}
if(!_5c7){
_5c7=_5c8.domNode;
}
if(_5c7.nodeType!=_5ca){
return;
}
var _5cc=_5c7.getElementsByTagName("*");
var _5cd=_5c8;
for(var x=-1;x<_5cc.length;x++){
var _5cf=(x==-1)?_5c7:_5cc[x];
var _5d0=[];
for(var y=0;y<this.attachProperties.length;y++){
var _5d2=_5cf.getAttribute(this.attachProperties[y]);
if(_5d2){
_5d0=_5d2.split(";");
for(var z=0;z<this.attachProperties.length;z++){
if((_5c8[_5d0[z]])&&(dojo.lang.isArray(_5c8[_5d0[z]]))){
_5c8[_5d0[z]].push(_5cf);
}else{
_5c8[_5d0[z]]=_5cf;
}
}
break;
}
}
var _5d4=_5cf.getAttribute(this.templateProperty);
if(_5d4){
_5c8[_5d4]=_5cf;
}
var _5d5=_5cf.getAttribute(this.eventAttachProperty);
if(_5d5){
var evts=_5d5.split(";");
for(var y=0;y<evts.length;y++){
if((!evts[y])||(!evts[y].length)){
continue;
}
var _5d7=null;
var tevt=trim(evts[y]);
if(evts[y].indexOf(":")>=0){
var _5d9=tevt.split(":");
tevt=trim(_5d9[0]);
_5d7=trim(_5d9[1]);
}
if(!_5d7){
_5d7=tevt;
}
var tf=function(){
var ntf=new String(_5d7);
return function(evt){
if(_5cd[ntf]){
_5cd[ntf](dojo.event.browser.fixEvent(evt));
}
};
}();
dojo.event.browser.addListener(_5cf,tevt,tf,false,true);
}
}
for(var y=0;y<_5c9.length;y++){
var _5dd=_5cf.getAttribute(_5c9[y]);
if((_5dd)&&(_5dd.length)){
var _5d7=null;
var _5de=_5c9[y].substr(4);
_5d7=trim(_5dd);
var tf=function(){
var ntf=new String(_5d7);
return function(evt){
if(_5cd[ntf]){
_5cd[ntf](dojo.event.browser.fixEvent(evt));
}
};
}();
dojo.event.browser.addListener(_5cf,_5de,tf,false,true);
}
}
var _5e1=_5cf.getAttribute(this.onBuildProperty);
if(_5e1){
eval("var node = baseNode; var widget = targetObj; "+_5e1);
}
_5cf.id="";
}
};
dojo.widget.getDojoEventsFromStr=function(str){
var re=/(dojoOn([a-z]+)(\s?))=/gi;
var evts=str?str.match(re)||[]:[];
var ret=[];
var lem={};
for(var x=0;x<evts.length;x++){
if(evts[x].legth<1){
continue;
}
var cm=evts[x].replace(/\s/,"");
cm=(cm.slice(0,cm.length-1));
if(!lem[cm]){
lem[cm]=true;
ret.push(cm);
}
}
return ret;
};
dojo.widget.buildAndAttachTemplate=function(obj,_5ea,_5eb,_5ec,_5ed){
this.buildFromTemplate(obj,_5ea,_5eb,_5ec);
var node=dojo.dom.createNodesFromText(obj.templateString,true)[0];
this.attachTemplateNodes(node,_5ed||obj,dojo.widget.getDojoEventsFromStr(_5ec));
return node;
};
dojo.widget.DomWidget=function(){
dojo.widget.Widget.call(this);
if((arguments.length>0)&&(typeof arguments[0]=="object")){
this.create(arguments[0]);
}
};
dojo.inherits(dojo.widget.DomWidget,dojo.widget.Widget);
dojo.lang.extend(dojo.widget.DomWidget,{templateNode:null,templateString:null,preventClobber:false,domNode:null,containerNode:null,addChild:function(_5ef,_5f0,pos,ref,_5f3){
if(!this.isContainer){
dojo.debug("dojo.widget.DomWidget.addChild() attempted on non-container widget");
return null;
}else{
this.addWidgetAsDirectChild(_5ef,_5f0,pos,ref,_5f3);
this.registerChild(_5ef,_5f3);
}
return _5ef;
},addWidgetAsDirectChild:function(_5f4,_5f5,pos,ref,_5f8){
if((!this.containerNode)&&(!_5f5)){
this.containerNode=this.domNode;
}
var cn=(_5f5)?_5f5:this.containerNode;
if(!pos){
pos="after";
}
if(!ref){
ref=cn.lastChild;
}
if(!_5f8){
_5f8=0;
}
_5f4.domNode.setAttribute("dojoinsertionindex",_5f8);
if(!ref){
cn.appendChild(_5f4.domNode);
}else{
if(pos=="insertAtIndex"){
dojo.dom.insertAtIndex(_5f4.domNode,ref.parentNode,_5f8);
}else{
if((pos=="after")&&(ref===cn.lastChild)){
cn.appendChild(_5f4.domNode);
}else{
dojo.dom.insertAtPosition(_5f4.domNode,cn,pos);
}
}
}
},registerChild:function(_5fa,_5fb){
_5fa.dojoInsertionIndex=_5fb;
var idx=-1;
for(var i=0;i<this.children.length;i++){
if(this.children[i].dojoInsertionIndex<_5fb){
idx=i;
}
}
this.children.splice(idx+1,0,_5fa);
_5fa.parent=this;
_5fa.addedTo(this);
delete dojo.widget.manager.topWidgets[_5fa.widgetId];
},removeChild:function(_5fe){
dojo.dom.removeNode(_5fe.domNode);
return dojo.widget.DomWidget.superclass.removeChild.call(this,_5fe);
},getFragNodeRef:function(frag){
if(!frag["dojo:"+this.widgetType.toLowerCase()]){
dojo.raise("Error: no frag for widget type "+this.widgetType+", id "+this.widgetId+" (maybe a widget has set it's type incorrectly)");
}
return (frag?frag["dojo:"+this.widgetType.toLowerCase()]["nodeRef"]:null);
},postInitialize:function(args,frag,_602){
var _603=this.getFragNodeRef(frag);
if(_602&&(_602.snarfChildDomOutput||!_603)){
_602.addWidgetAsDirectChild(this,"","insertAtIndex","",args["dojoinsertionindex"],_603);
}else{
if(_603){
if(this.domNode&&(this.domNode!==_603)){
var _604=_603.parentNode.replaceChild(this.domNode,_603);
}
}
}
if(_602){
_602.registerChild(this,args.dojoinsertionindex);
}else{
dojo.widget.manager.topWidgets[this.widgetId]=this;
}
if(this.isContainer){
var _605=dojo.widget.getParser();
_605.createComponents(frag,this);
}
},buildRendering:function(args,frag){
var ts=dojo.widget._templateCache[this.widgetType];
if((!this.preventClobber)&&((this.templatePath)||(this.templateNode)||((this["templateString"])&&(this.templateString.length))||((typeof ts!="undefined")&&((ts["string"])||(ts["node"]))))){
this.buildFromTemplate(args,frag);
}else{
this.domNode=this.getFragNodeRef(frag);
}
this.fillInTemplate(args,frag);
},buildFromTemplate:function(args,frag){
var _60b=false;
if(args["templatecsspath"]){
args["templateCssPath"]=args["templatecsspath"];
}
if(args["templatepath"]){
_60b=true;
args["templatePath"]=args["templatepath"];
}
dojo.widget.fillFromTemplateCache(this,args["templatePath"],args["templateCssPath"],null,_60b);
var ts=dojo.widget._templateCache[this.widgetType];
if((ts)&&(!_60b)){
if(!this.templateString.length){
this.templateString=ts["string"];
}
if(!this.templateNode){
this.templateNode=ts["node"];
}
}
var _60d=false;
var node=null;
var tstr=this.templateString;
if((!this.templateNode)&&(this.templateString)){
_60d=this.templateString.match(/\$\{([^\}]+)\}/g);
if(_60d){
var hash=this.strings||{};
for(var key in dojo.widget.defaultStrings){
if(dojo.lang.isUndefined(hash[key])){
hash[key]=dojo.widget.defaultStrings[key];
}
}
for(var i=0;i<_60d.length;i++){
var key=_60d[i];
key=key.substring(2,key.length-1);
var kval=(key.substring(0,5)=="this.")?this[key.substring(5)]:hash[key];
var _614;
if((kval)||(dojo.lang.isString(kval))){
_614=(dojo.lang.isFunction(kval))?kval.call(this,key,this.templateString):kval;
tstr=tstr.replace(_60d[i],_614);
}
}
}else{
this.templateNode=this.createNodesFromText(this.templateString,true)[0];
ts.node=this.templateNode;
}
}
if((!this.templateNode)&&(!_60d)){
dojo.debug("weren't able to create template!");
return false;
}else{
if(!_60d){
node=this.templateNode.cloneNode(true);
if(!node){
return false;
}
}else{
node=this.createNodesFromText(tstr,true)[0];
}
}
this.domNode=node;
this.attachTemplateNodes(this.domNode,this);
if(this.isContainer&&this.containerNode){
var src=this.getFragNodeRef(frag);
if(src){
dojo.dom.moveChildren(src,this.containerNode);
}
}
},attachTemplateNodes:function(_616,_617){
if(!_617){
_617=this;
}
return dojo.widget.attachTemplateNodes(_616,_617,dojo.widget.getDojoEventsFromStr(this.templateString));
},fillInTemplate:function(){
},destroyRendering:function(){
try{
delete this.domNode;
}
catch(e){
}
},cleanUp:function(){
},getContainerHeight:function(){
dj_unimplemented("dojo.widget.DomWidget.getContainerHeight");
},getContainerWidth:function(){
dj_unimplemented("dojo.widget.DomWidget.getContainerWidth");
},createNodesFromText:function(){
dj_unimplemented("dojo.widget.DomWidget.createNodesFromText");
}});
dojo.provide("dojo.html");
dojo.require("dojo.lang.func");
dojo.require("dojo.dom");
dojo.require("dojo.style");
dojo.require("dojo.string");
dojo.require("dojo.string.extras");
dojo.require("dojo.uri.Uri");
dojo.lang.mixin(dojo.html,dojo.dom);
dojo.lang.mixin(dojo.html,dojo.style);
dojo.html.clearSelection=function(){
try{
if(window["getSelection"]){
if(dojo.render.html.safari){
window.getSelection().collapse();
}else{
window.getSelection().removeAllRanges();
}
}else{
if(document.selection){
if(document.selection.empty){
document.selection.empty();
}else{
if(document.selection.clear){
document.selection.clear();
}
}
}
}
return true;
}
catch(e){
dojo.debug(e);
return false;
}
};
dojo.html.disableSelection=function(_618){
_618=dojo.byId(_618)||document.body;
var h=dojo.render.html;
if(h.mozilla){
_618.style.MozUserSelect="none";
}else{
if(h.safari){
_618.style.KhtmlUserSelect="none";
}else{
if(h.ie){
_618.unselectable="on";
}else{
return false;
}
}
}
return true;
};
dojo.html.enableSelection=function(_61a){
_61a=dojo.byId(_61a)||document.body;
var h=dojo.render.html;
if(h.mozilla){
_61a.style.MozUserSelect="";
}else{
if(h.safari){
_61a.style.KhtmlUserSelect="";
}else{
if(h.ie){
_61a.unselectable="off";
}else{
return false;
}
}
}
return true;
};
dojo.html.selectElement=function(_61c){
_61c=dojo.byId(_61c);
if(document.selection&&document.body.createTextRange){
var _61d=document.body.createTextRange();
_61d.moveToElementText(_61c);
_61d.select();
}else{
if(window["getSelection"]){
var _61e=window.getSelection();
if(_61e["selectAllChildren"]){
_61e.selectAllChildren(_61c);
}
}
}
};
dojo.html.isSelectionCollapsed=function(){
if(document["selection"]){
return document.selection.createRange().text=="";
}else{
if(window["getSelection"]){
var _61f=window.getSelection();
if(dojo.lang.isString(_61f)){
return _61f=="";
}else{
return _61f.isCollapsed;
}
}
}
};
dojo.html.getEventTarget=function(evt){
if(!evt){
evt=window.event||{};
}
if(evt.srcElement){
return evt.srcElement;
}else{
if(evt.target){
return evt.target;
}
}
return null;
};
dojo.html.getScrollTop=function(){
return document.documentElement.scrollTop||document.body.scrollTop||0;
};
dojo.html.getScrollLeft=function(){
return document.documentElement.scrollLeft||document.body.scrollLeft||0;
};
dojo.html.getDocumentWidth=function(){
dojo.deprecated("dojo.html.getDocument* has been deprecated in favor of dojo.html.getViewport*");
return dojo.html.getViewportWidth();
};
dojo.html.getDocumentHeight=function(){
dojo.deprecated("dojo.html.getDocument* has been deprecated in favor of dojo.html.getViewport*");
return dojo.html.getViewportHeight();
};
dojo.html.getDocumentSize=function(){
dojo.deprecated("dojo.html.getDocument* has been deprecated in favor of dojo.html.getViewport*");
return dojo.html.getViewportSize();
};
dojo.html.getViewportWidth=function(){
var w=0;
if(window.innerWidth){
w=window.innerWidth;
}
if(dojo.exists(document,"documentElement.clientWidth")){
var w2=document.documentElement.clientWidth;
if(!w||w2&&w2<w){
w=w2;
}
return w;
}
if(document.body){
return document.body.clientWidth;
}
return 0;
};
dojo.html.getViewportHeight=function(){
if(window.innerHeight){
return window.innerHeight;
}
if(dojo.exists(document,"documentElement.clientHeight")){
return document.documentElement.clientHeight;
}
if(document.body){
return document.body.clientHeight;
}
return 0;
};
dojo.html.getViewportSize=function(){
var ret=[dojo.html.getViewportWidth(),dojo.html.getViewportHeight()];
ret.w=ret[0];
ret.h=ret[1];
return ret;
};
dojo.html.getScrollOffset=function(){
var ret=[0,0];
if(window.pageYOffset){
ret=[window.pageXOffset,window.pageYOffset];
}else{
if(dojo.exists(document,"documentElement.scrollTop")){
ret=[document.documentElement.scrollLeft,document.documentElement.scrollTop];
}else{
if(document.body){
ret=[document.body.scrollLeft,document.body.scrollTop];
}
}
}
ret.x=ret[0];
ret.y=ret[1];
return ret;
};
dojo.html.getParentOfType=function(node,type){
dojo.deprecated("dojo.html.getParentOfType has been deprecated in favor of dojo.html.getParentByType*");
return dojo.html.getParentByType(node,type);
};
dojo.html.getParentByType=function(node,type){
var _629=dojo.byId(node);
type=type.toLowerCase();
while((_629)&&(_629.nodeName.toLowerCase()!=type)){
if(_629==(document["body"]||document["documentElement"])){
return null;
}
_629=_629.parentNode;
}
return _629;
};
dojo.html.getAttribute=function(node,attr){
node=dojo.byId(node);
if((!node)||(!node.getAttribute)){
return null;
}
var ta=typeof attr=="string"?attr:new String(attr);
var v=node.getAttribute(ta.toUpperCase());
if((v)&&(typeof v=="string")&&(v!="")){
return v;
}
if(v&&v.value){
return v.value;
}
if((node.getAttributeNode)&&(node.getAttributeNode(ta))){
return (node.getAttributeNode(ta)).value;
}else{
if(node.getAttribute(ta)){
return node.getAttribute(ta);
}else{
if(node.getAttribute(ta.toLowerCase())){
return node.getAttribute(ta.toLowerCase());
}
}
}
return null;
};
dojo.html.hasAttribute=function(node,attr){
node=dojo.byId(node);
return dojo.html.getAttribute(node,attr)?true:false;
};
dojo.html.getClass=function(node){
node=dojo.byId(node);
if(!node){
return "";
}
var cs="";
if(node.className){
cs=node.className;
}else{
if(dojo.html.hasAttribute(node,"class")){
cs=dojo.html.getAttribute(node,"class");
}
}
return dojo.string.trim(cs);
};
dojo.html.getClasses=function(node){
node=dojo.byId(node);
var c=dojo.html.getClass(node);
return (c=="")?[]:c.split(/\s+/g);
};
dojo.html.hasClass=function(node,_635){
node=dojo.byId(node);
return dojo.lang.inArray(dojo.html.getClasses(node),_635);
};
dojo.html.prependClass=function(node,_637){
node=dojo.byId(node);
if(!node){
return false;
}
_637+=" "+dojo.html.getClass(node);
return dojo.html.setClass(node,_637);
};
dojo.html.addClass=function(node,_639){
node=dojo.byId(node);
if(!node){
return false;
}
if(dojo.html.hasClass(node,_639)){
return false;
}
_639=dojo.string.trim(dojo.html.getClass(node)+" "+_639);
return dojo.html.setClass(node,_639);
};
dojo.html.setClass=function(node,_63b){
node=dojo.byId(node);
if(!node){
return false;
}
var cs=new String(_63b);
try{
if(typeof node.className=="string"){
node.className=cs;
}else{
if(node.setAttribute){
node.setAttribute("class",_63b);
node.className=cs;
}else{
return false;
}
}
}
catch(e){
dojo.debug("dojo.html.setClass() failed",e);
}
return true;
};
dojo.html.removeClass=function(node,_63e,_63f){
node=dojo.byId(node);
if(!node){
return false;
}
var _63e=dojo.string.trim(new String(_63e));
try{
var cs=dojo.html.getClasses(node);
var nca=[];
if(_63f){
for(var i=0;i<cs.length;i++){
if(cs[i].indexOf(_63e)==-1){
nca.push(cs[i]);
}
}
}else{
for(var i=0;i<cs.length;i++){
if(cs[i]!=_63e){
nca.push(cs[i]);
}
}
}
dojo.html.setClass(node,nca.join(" "));
}
catch(e){
dojo.debug("dojo.html.removeClass() failed",e);
}
return true;
};
dojo.html.replaceClass=function(node,_644,_645){
node=dojo.byId(node);
dojo.html.removeClass(node,_645);
dojo.html.addClass(node,_644);
};
dojo.html.classMatchType={ContainsAll:0,ContainsAny:1,IsOnly:2};
dojo.html.getElementsByClass=function(_646,_647,_648,_649){
_647=dojo.byId(_647);
if(!_647){
_647=document;
}
var _64a=_646.split(/\s+/g);
var _64b=[];
if(_649!=1&&_649!=2){
_649=0;
}
var _64c=new RegExp("(\\s|^)(("+_64a.join(")|(")+"))(\\s|$)");
if(!_648){
_648="*";
}
var _64d=_647.getElementsByTagName(_648);
outer:
for(var i=0;i<_64d.length;i++){
var node=_64d[i];
var _650=dojo.html.getClasses(node);
if(_650.length==0){
continue outer;
}
var _651=0;
for(var j=0;j<_650.length;j++){
if(_64c.test(_650[j])){
if(_649==dojo.html.classMatchType.ContainsAny){
_64b.push(node);
continue outer;
}else{
_651++;
}
}else{
if(_649==dojo.html.classMatchType.IsOnly){
continue outer;
}
}
}
if(_651==_64a.length){
if(_649==dojo.html.classMatchType.IsOnly&&_651==_650.length){
_64b.push(node);
}else{
if(_649==dojo.html.classMatchType.ContainsAll){
_64b.push(node);
}
}
}
}
return _64b;
};
dojo.html.getElementsByClassName=dojo.html.getElementsByClass;
dojo.html.gravity=function(node,e){
node=dojo.byId(node);
var _655=e.pageX||e.clientX+document.body.scrollLeft;
var _656=e.pageY||e.clientY+document.body.scrollTop;
with(dojo.html){
var _657=getAbsoluteX(node)+(getInnerWidth(node)/2);
var _658=getAbsoluteY(node)+(getInnerHeight(node)/2);
}
with(dojo.html.gravity){
return ((_655<_657?WEST:EAST)|(_656<_658?NORTH:SOUTH));
}
};
dojo.html.gravity.NORTH=1;
dojo.html.gravity.SOUTH=1<<1;
dojo.html.gravity.EAST=1<<2;
dojo.html.gravity.WEST=1<<3;
dojo.html.overElement=function(_659,e){
_659=dojo.byId(_659);
var _65b=e.pageX||e.clientX+document.body.scrollLeft;
var _65c=e.pageY||e.clientY+document.body.scrollTop;
with(dojo.html){
var top=getAbsoluteY(_659);
var _65e=top+getInnerHeight(_659);
var left=getAbsoluteX(_659);
var _660=left+getInnerWidth(_659);
}
return (_65b>=left&&_65b<=_660&&_65c>=top&&_65c<=_65e);
};
dojo.html.renderedTextContent=function(node){
node=dojo.byId(node);
var _662="";
if(node==null){
return _662;
}
for(var i=0;i<node.childNodes.length;i++){
switch(node.childNodes[i].nodeType){
case 1:
case 5:
var _664="unknown";
try{
_664=dojo.style.getStyle(node.childNodes[i],"display");
}
catch(E){
}
switch(_664){
case "block":
case "list-item":
case "run-in":
case "table":
case "table-row-group":
case "table-header-group":
case "table-footer-group":
case "table-row":
case "table-column-group":
case "table-column":
case "table-cell":
case "table-caption":
_662+="\n";
_662+=dojo.html.renderedTextContent(node.childNodes[i]);
_662+="\n";
break;
case "none":
break;
default:
if(node.childNodes[i].tagName&&node.childNodes[i].tagName.toLowerCase()=="br"){
_662+="\n";
}else{
_662+=dojo.html.renderedTextContent(node.childNodes[i]);
}
break;
}
break;
case 3:
case 2:
case 4:
var text=node.childNodes[i].nodeValue;
var _666="unknown";
try{
_666=dojo.style.getStyle(node,"text-transform");
}
catch(E){
}
switch(_666){
case "capitalize":
text=dojo.string.capitalize(text);
break;
case "uppercase":
text=text.toUpperCase();
break;
case "lowercase":
text=text.toLowerCase();
break;
default:
break;
}
switch(_666){
case "nowrap":
break;
case "pre-wrap":
break;
case "pre-line":
break;
case "pre":
break;
default:
text=text.replace(/\s+/," ");
if(/\s$/.test(_662)){
text.replace(/^\s/,"");
}
break;
}
_662+=text;
break;
default:
break;
}
}
return _662;
};
dojo.html.setActiveStyleSheet=function(_667){
var i,a,main;
for(i=0;(a=document.getElementsByTagName("link")[i]);i++){
if(a.getAttribute("rel").indexOf("style")!=-1&&a.getAttribute("title")){
a.disabled=true;
if(a.getAttribute("title")==_667){
a.disabled=false;
}
}
}
};
dojo.html.getActiveStyleSheet=function(){
var i,a;
for(i=0;(a=document.getElementsByTagName("link")[i]);i++){
if(a.getAttribute("rel").indexOf("style")!=-1&&a.getAttribute("title")&&!a.disabled){
return a.getAttribute("title");
}
}
return null;
};
dojo.html.getPreferredStyleSheet=function(){
var i,a;
for(i=0;(a=document.getElementsByTagName("link")[i]);i++){
if(a.getAttribute("rel").indexOf("style")!=-1&&a.getAttribute("rel").indexOf("alt")==-1&&a.getAttribute("title")){
return a.getAttribute("title");
}
}
return null;
};
dojo.html.body=function(){
dojo.deprecated("dojo.html.body","use document.body instead");
return document.body||document.getElementsByTagName("body")[0];
};
dojo.html.createNodesFromText=function(txt,trim){
if(trim){
txt=dojo.string.trim(txt);
}
var tn=document.createElement("div");
tn.style.visibility="hidden";
document.body.appendChild(tn);
var _66e="none";
if((/^<t[dh][\s\r\n>]/i).test(dojo.string.trimStart(txt))){
txt="<table><tbody><tr>"+txt+"</tr></tbody></table>";
_66e="cell";
}else{
if((/^<tr[\s\r\n>]/i).test(dojo.string.trimStart(txt))){
txt="<table><tbody>"+txt+"</tbody></table>";
_66e="row";
}else{
if((/^<(thead|tbody|tfoot)[\s\r\n>]/i).test(dojo.string.trimStart(txt))){
txt="<table>"+txt+"</table>";
_66e="section";
}
}
}
tn.innerHTML=txt;
tn.normalize();
var _66f=null;
switch(_66e){
case "cell":
_66f=tn.getElementsByTagName("tr")[0];
break;
case "row":
_66f=tn.getElementsByTagName("tbody")[0];
break;
case "section":
_66f=tn.getElementsByTagName("table")[0];
break;
default:
_66f=tn;
break;
}
var _670=[];
for(var x=0;x<_66f.childNodes.length;x++){
_670.push(_66f.childNodes[x].cloneNode(true));
}
tn.style.display="none";
document.body.removeChild(tn);
return _670;
};
if(!dojo.evalObjPath("dojo.dom.createNodesFromText")){
dojo.dom.createNodesFromText=function(){
dojo.deprecated("dojo.dom.createNodesFromText","use dojo.html.createNodesFromText instead");
return dojo.html.createNodesFromText.apply(dojo.html,arguments);
};
}
dojo.html.isTag=function(node){
node=dojo.byId(node);
if(node&&node.tagName){
var arr=dojo.lang.map(dojo.lang.toArray(arguments,1),function(a){
return String(a).toLowerCase();
});
return arr[dojo.lang.find(node.tagName.toLowerCase(),arr)]||"";
}
return "";
};
dojo.html.placeOnScreen=function(node,_676,_677,_678,_679){
if(dojo.lang.isArray(_676)){
_679=_678;
_678=_677;
_677=_676[1];
_676=_676[0];
}
if(!isNaN(_678)){
_678=[Number(_678),Number(_678)];
}else{
if(!dojo.lang.isArray(_678)){
_678=[0,0];
}
}
var _67a=dojo.html.getScrollOffset();
var view=dojo.html.getViewportSize();
node=dojo.byId(node);
var w=node.offsetWidth+_678[0];
var h=node.offsetHeight+_678[1];
if(_679){
_676-=_67a.x;
_677-=_67a.y;
}
var x=_676+w;
if(x>view.w){
x=view.w-w;
}else{
x=_676;
}
x=Math.max(_678[0],x)+_67a.x;
var y=_677+h;
if(y>view.h){
y=view.h-h;
}else{
y=_677;
}
y=Math.max(_678[1],y)+_67a.y;
node.style.left=x+"px";
node.style.top=y+"px";
var ret=[x,y];
ret.x=x;
ret.y=y;
return ret;
};
dojo.html.placeOnScreenPoint=function(node,_682,_683,_684,_685){
if(dojo.lang.isArray(_682)){
_685=_684;
_684=_683;
_683=_682[1];
_682=_682[0];
}
if(!isNaN(_684)){
_684=[Number(_684),Number(_684)];
}else{
if(!dojo.lang.isArray(_684)){
_684=[0,0];
}
}
var _686=dojo.html.getScrollOffset();
var view=dojo.html.getViewportSize();
node=dojo.byId(node);
var _688=node.style.display;
node.style.display="";
var w=dojo.style.getInnerWidth(node);
var h=dojo.style.getInnerHeight(node);
node.style.display=_688;
if(_685){
_682-=_686.x;
_683-=_686.y;
}
var x=-1,y=-1;
if((_682+_684[0])+w<=view.w&&(_683+_684[1])+h<=view.h){
x=(_682+_684[0]);
y=(_683+_684[1]);
}
if((x<0||y<0)&&(_682-_684[0])<=view.w&&(_683+_684[1])+h<=view.h){
x=(_682-_684[0])-w;
y=(_683+_684[1]);
}
if((x<0||y<0)&&(_682+_684[0])+w<=view.w&&(_683-_684[1])<=view.h){
x=(_682+_684[0]);
y=(_683-_684[1])-h;
}
if((x<0||y<0)&&(_682-_684[0])<=view.w&&(_683-_684[1])<=view.h){
x=(_682-_684[0])-w;
y=(_683-_684[1])-h;
}
if(x<0||y<0||(x+w>view.w)||(y+h>view.h)){
return dojo.html.placeOnScreen(node,_682,_683,_684,_685);
}
x+=_686.x;
y+=_686.y;
node.style.left=x+"px";
node.style.top=y+"px";
var ret=[x,y];
ret.x=x;
ret.y=y;
return ret;
};
dojo.style.insertCssFile=function(URI,doc,_68f){
if(!URI){
return;
}
if(!doc){
doc=document;
}
if(doc.baseURI){
URI=new dojo.uri.Uri(doc.baseURI,URI);
}
if(_68f&&doc.styleSheets){
var loc=location.href.split("#")[0].substring(0,location.href.indexOf(location.pathname));
for(var i=0;i<doc.styleSheets.length;i++){
if(doc.styleSheets[i].href&&URI.toString()==new dojo.uri.Uri(doc.styleSheets[i].href.toString())){
return;
}
}
}
var file=doc.createElement("link");
file.setAttribute("type","text/css");
file.setAttribute("rel","stylesheet");
file.setAttribute("href",URI);
var head=doc.getElementsByTagName("head")[0];
if(head){
head.appendChild(file);
}
};
dojo.html.BackgroundIframe=function(){
if(this.ie){
this.iframe=document.createElement("<iframe frameborder='0' src='about:blank'>");
var s=this.iframe.style;
s.position="absolute";
s.left=s.top="0px";
s.zIndex=2;
s.display="none";
dojo.style.setOpacity(this.iframe,0);
document.body.appendChild(this.iframe);
}else{
this.enabled=false;
}
};
dojo.lang.extend(dojo.html.BackgroundIframe,{ie:dojo.render.html.ie,enabled:true,visibile:false,iframe:null,sizeNode:null,sizeCoords:null,size:function(node){
if(!this.ie||!this.enabled){
return;
}
if(dojo.dom.isNode(node)){
this.sizeNode=node;
}else{
if(arguments.length>0){
this.sizeNode=null;
this.sizeCoords=node;
}
}
this.update();
},update:function(){
if(!this.ie||!this.enabled){
return;
}
if(this.sizeNode){
this.sizeCoords=dojo.html.toCoordinateArray(this.sizeNode,true);
}else{
if(this.sizeCoords){
this.sizeCoords=dojo.html.toCoordinateArray(this.sizeCoords,true);
}else{
return;
}
}
var s=this.iframe.style;
var dims=this.sizeCoords;
s.width=dims.w+"px";
s.height=dims.h+"px";
s.left=dims.x+"px";
s.top=dims.y+"px";
},setZIndex:function(node){
if(!this.ie||!this.enabled){
return;
}
if(dojo.dom.isNode(node)){
this.iframe.zIndex=dojo.html.getStyle(node,"z-index")-1;
}else{
if(!isNaN(node)){
this.iframe.zIndex=node;
}
}
},show:function(node){
if(!this.ie||!this.enabled){
return;
}
this.size(node);
this.iframe.style.display="block";
},hide:function(){
if(!this.ie){
return;
}
var s=this.iframe.style;
s.display="none";
s.width=s.height="1px";
},remove:function(){
dojo.dom.removeNode(this.iframe);
}});
dojo.provide("dojo.widget.HtmlWidget");
dojo.require("dojo.widget.DomWidget");
dojo.require("dojo.html");
dojo.require("dojo.lang.extras");
dojo.require("dojo.lang.func");
dojo.widget.HtmlWidget=function(args){
dojo.widget.DomWidget.call(this);
};
dojo.inherits(dojo.widget.HtmlWidget,dojo.widget.DomWidget);
dojo.lang.extend(dojo.widget.HtmlWidget,{widgetType:"HtmlWidget",templateCssPath:null,templatePath:null,toggle:"plain",toggleDuration:150,animationInProgress:false,initialize:function(args,frag){
},toggleObj:{show:function(node,_69f,_6a0,_6a1){
dojo.style.show(node);
if(dojo.lang.isFunction(_6a1)){
_6a1();
}
},hide:function(node,_6a3,_6a4,_6a5){
dojo.style.hide(node);
if(dojo.lang.isFunction(_6a5)){
_6a5();
}
}},postMixInProperties:function(args,frag){
var _6a8=(dojo.fx&&dojo.fx.html&&dojo.fx.html.toggle[this.toggle.toLowerCase()]);
if(_6a8){
this.toggleObj=_6a8;
}
},getContainerHeight:function(){
dj_unimplemented("dojo.widget.HtmlWidget.getContainerHeight");
},getContainerWidth:function(){
return this.parent.domNode.offsetWidth;
},setNativeHeight:function(_6a9){
var ch=this.getContainerHeight();
},resizeSoon:function(){
if(this.isVisible()){
dojo.lang.setTimeout(this,this.onResized,0);
}
},resizeTo:function(w,h){
dojo.style.setOuterWidth(this.domNode,w);
dojo.style.setOuterHeight(this.domNode,h);
this.onResized();
},createNodesFromText:function(txt,wrap){
return dojo.html.createNodesFromText(txt,wrap);
},destroyRendering:function(_6af){
try{
if(!_6af){
dojo.event.browser.clean(this.domNode);
}
this.domNode.parentNode.removeChild(this.domNode);
delete this.domNode;
}
catch(e){
}
},isVisible:function(){
return dojo.style.isVisible(this.domNode);
},doToggle:function(){
this.isVisible()?this.hide():this.show();
},show:function(){
this.animationInProgress=true;
this.toggleObj.show(this.domNode,this.toggleDuration,this.explodeSrc,dojo.lang.hitch(this,this.onShow));
},onShow:function(){
this.animationInProgress=false;
},hide:function(){
this.animationInProgress=true;
this.toggleObj.hide(this.domNode,this.toggleDuration,this.explodeSrc,dojo.lang.hitch(this,this.onHide));
},onHide:function(){
this.animationInProgress=false;
}});
dojo.hostenv.conditionalLoadModule({common:["dojo.xml.Parse","dojo.widget.Widget","dojo.widget.Parse","dojo.widget.Manager"],browser:["dojo.widget.DomWidget","dojo.widget.HtmlWidget"],dashboard:["dojo.widget.DomWidget","dojo.widget.HtmlWidget"],svg:["dojo.widget.SvgWidget"]});
dojo.hostenv.moduleLoaded("dojo.widget.*");
dojo.provide("turbo.lib.theme");
dojo.require("dojo.io.*");
dojo.require("dojo.html");
dojo.require("turbo.turbo");
turbo.stylesheet={cssFiles:[],links:[],rules:0,loadCost:0,processCost:0,dummy:0};
turbo.stylesheet.create=function(_6b0){
var l=document.createElement("link");
l.setAttribute("rel","stylesheet");
l.setAttribute("type","text/css");
l.setAttribute("href",_6b0+"/base.css");
return l;
};
turbo.stylesheet.getLink=function(_6b2){
var l=turbo.stylesheet.links[_6b2];
if(!l){
l=turbo.stylesheet.create(_6b2);
turbo.addHeadNode(l);
turbo.stylesheet.links[_6b2]=l;
}
return l;
};
turbo.stylesheet.getLinkSheet=function(_6b4){
var s=null;
if(!_6b4.addRule){
turbo.debug("stylesheet has no addRule method");
if(_6b4.styleSheet){
s=_6b4.styleSheet;
}else{
turbo.debug("turbo.stylesheet.getLinkSheet: link has no .styleSheet property");
}
}
return s;
};
turbo.stylesheet.loaded=function(_6b6){
if(!_6b6||turbo.stylesheet.cssFiles[_6b6]){
return true;
}
turbo.stylesheet.cssFiles[_6b6]=true;
return false;
};
turbo.stylesheet.append=function(_6b7){
if(turbo.stylesheet.loaded(_6b7)){
return;
}
var t=turbo.time();
dojo.io.bind({url:_6b7,sync:true,load:function(_6b9,_6ba){
turbo.stylesheet._append(_6b7,_6ba);
},error:function(e,m){
turbo.debug(m.message+": "+_6b7);
}});
};
turbo.stylesheet._append=function(_6bd,_6be){
var s=turbo.stylesheet.getLink(turbo.pathpop(_6bd)).styleSheet;
if(!s||!s.addRule){
return;
}
var _6c0=/(\/\*[\s\S]*?\*\/)/g;
_6be=_6be.replace(_6c0,"");
var _6c0=/[\s]*([^{]*)({[^}]*})/g;
while((result=_6c0.exec(_6be))!=null){
var rule=result[2];
var _6c2=result[1].split(",");
for(var i in _6c2){
s.addRule(_6c2[i],rule);
}
}
};
turbo.stylesheet.importStyleSheet=function(_6c4){
if(turbo.stylesheet.loaded(_6c4)){
return;
}
dojo.style.insertCssFile(_6c4);
};
turbo.stylesheet.importThemeFile=function(_6c5){
if(djConfig["turbo_hand_css"]){
return;
}
if(djConfig["turbo_fine_css"]){
if(dojo.render.html.ie){
turbo.stylesheet.append(_6c5);
}else{
turbo.stylesheet.importStyleSheet(_6c5);
}
}else{
turbo.stylesheet.importStyleSheet(turbo.pathpop(_6c5)+"/theme.css");
}
};
turbo.themes=new function(){
this.theme="";
this.themeable=[];
this.addThemeable=function(_6c6){
this.themeable.push(_6c6);
};
this.setTheme=function(_6c7){
this.theme=(_6c7?_6c7:"default");
for(var i in this.themeable){
this.themeable[i].setTheme(this.theme);
}
if(turbo["aligner"]){
turbo.defer(turbo.aligner.align,250);
turbo.defer(turbo.aligner.align,1000);
}
};
};
dojo.provide("turbo.widgets.TurboWidget");
dojo.require("dojo.widget.*");
dojo.require("turbo.turbo");
dojo.require("turbo.lib.theme");
dojo.require("turbo.lib.align");
turbo.widgetRoot="../turbo/widgets/";
turbo.templateRoot=turbo.widgetRoot+"templates/";
turbo.themeRoot=turbo.widgetRoot+"themes/";
turbo.loadScript=function(_6c9){
var _6ca=document.createElement("script");
_6ca.type="text/javascript";
_6ca.language="JavaScript";
turbo.addHeadNode(_6ca);
_6ca.src=_6c9;
};
turbo.loadJs=function(_6cb){
if(!turbo["js"]){
turbo.js=[];
}
if(turbo.js[_6cb]){
return;
}
turbo.js[_6cb]=true;
turbo.loadScript(dojo.hostenv.getBaseScriptUri()+_6cb);
};
turbo.loadCss=function(_6cc){
var _6cd=dojo.hostenv.getBaseScriptUri()+_6cc;
turbo.stylesheet.importThemeFile(_6cd);
};
turbo.setWidgetType=function(_6ce,_6cf){
if(_6ce.widgetType=="HtmlWidget"){
_6ce.widgetType=_6cf;
}
};
turbo.getFunction=function(_6d0){
var obj=turbo.global;
var _6d2=_6d0.split(".");
var func=_6d2.pop();
while(_6d2.length&&obj){
obj=obj[_6d2.shift()];
}
return (obj?obj[func]:null);
};
dojo.widget.HtmlTurboWidget=function(){
dojo.widget.HtmlWidget.call(this);
this.themeRoot=turbo.themeRoot;
this.templateRoot=turbo.templateRoot;
this.templatePath=dojo.uri.dojoUri(this.templateRoot+this.widgetType+".html");
this.styleRoot=this.widgetType;
this.isContainer=false;
this.style="";
this.theme="";
this.themeable=true;
this.turboalign="";
this.themeJs=false;
this.onCreate=function(){
};
this.initialize=function(){
if(this.widgetId.substr(-2,1)!="_"&&this.widgetId.substr(-3,1)!="_"){
dj_global[this.widgetId]=this;
}
if(this.extraArgs["turboAlign"]){
this.turboalign=this.extraArgs.turboAlign;
}
if(this.turboalign){
this.domNode.setAttribute("turboalign",this.turboalign);
}
if(this.extraArgs["class"]){
this.domNode.className=this.extraArgs["class"];
}
this.domNode.id=this.widgetId;
if(this.themeable){
turbo.themes.addThemeable(this);
if(!this.theme){
this.theme=turbo.themes.theme;
}
this.setTheme(this.theme);
}
};
this.bindArgEvent=function(_6d4,_6d5){
if(_6d5[_6d4]){
this[_6d4]=turbo.getFunction(_6d5[_6d4]);
}
};
this.bindArgEvents=function(_6d6){
var _6d7=[];
for(var arg in _6d6){
if(!_6d7[arg]&&dojo.lang.isFunction(this[arg])){
_6d7[arg]=true;
var _6d9=_6d6[arg];
if(_6d9.search(/[^\w\.]+/i)==-1){
var func=turbo.getFunction(_6d9);
if(func){
this[arg]=func;
}else{
this.debug("bindArgEvents","could not bind \""+arg+"\" to \""+_6d9+"\"");
}
}
}
}
};
this.getWidgetFragment=function(_6db){
return _6db["dojo:"+this.widgetType.toLowerCase()]["nodeRef"];
};
this.parseContent=function(_6dc){
var frag=this.getWidgetFragment(_6dc);
return dojo.widget.getParser().createComponents(frag);
};
this.getStylePath=function(_6de){
return this.themeRoot+(_6de?_6de:"default")+"/"+this.styleRoot+_6de;
};
this.loadStyle=function(_6df){
var p=this.getStylePath(_6df);
turbo.loadCss(p+".css");
if(this.themeJs){
turbo.loadJs(p+"Theme.js");
}
};
this.setStyle=function(_6e1){
this.style = (_6e1 == 'default' ? '' : _6e1);
this.loadStyle(this.style);
this.styleChanged();
};
this.setTheme=function(_6e2){
this.loadStyle("");
if(!this.themeable){
return;
}
if(_6e2&&(_6e2.charAt(0)=="+")){
this.themeable=false;
_6e2=_6e2.substring(1);
}
this.setStyle(_6e2);
};
this.styleChanged=function(){
};
this.setStyledClass=function(_6e3,_6e4){
if(!_6e4){
_6e4="";
}
_6e3.className=this.classTag+_6e4+(this.style?" "+this.classTag+this.style+_6e4:"");
};
this.setClassName=this.setStyledClass;
this.debug=function(_6e5,_6e6){
turbo.debug(this.widgetId+" ["+this.widgetType+"]: "+_6e5+": "+_6e6);
};
this.showHide=function(_6e7){
if(_6e7){
this.show();
}else{
this.hide();
}
};
};
dojo.widget.HtmlTurboValueWidget=function(){
dojo.widget.HtmlTurboWidget.call(this);
this.defaultValue=null;
this.value="";
this.fillInTemplate=function(){
if(this.value){
eval("this.defaultValue = "+this.value);
}
if(this.setValue){
this.setValue(this.defaultValue);
}
};
};
dojo.widget.HtmlTurboNotifier=function(){
dojo.widget.HtmlWidget.call(this);
this.widgetType="TurboNotifier";
this.templateString="<div dojoAttachPoint=\"div\" style=\"display:none;\"></div>";
this.notify="";
this.div=null;
this.fillInTemplate=function(){
if(this.notify){
eval(this.notify+"(this);");
}
};
};
dojo.inherits(dojo.widget.HtmlTurboNotifier,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbonotifier");
dojo.provide("turbo.widgets.TurboButton");
dojo.provide("turbo.widgets.HtmlTurboButton");
dojo.require("turbo.widgets.TurboWidget");
turbo.button=new function(){
this.groups=[];
this.states={normal:0,down:1,disabled:2,over:3,selected:1};
this.resetGroup=function(_6e8){
var g=turbo.button.groups[_6e8];
for(var i in g){
if(g[i].state!=this.states.disabled){
g[i].setState(this.states.normal);
}
}
};
};
dojo.widget.TurboButtonBase=function(){
dojo.widget.HtmlTurboWidget.call(this);
this.states=turbo.button.states;
this.state="normal";
this.group="";
this.toggle="";
this.value="";
this.width="";
this.event=null;
this.type="button";
this.btn=null;
this.onClick=function(_6eb){
};
this.initButton=function(_6ec){
this.bindArgEvents(_6ec);
if(this.state){
if(parseInt(this.state)){
this.state=parseInt(this.state);
}else{
this.state=this.states[this.state];
}
}
if(this.btn&&this.width){
this.btn.style.width=this.width+"px";
}
this.btn.setAttribute("type",this.type);
this.setGroup(this.group);
};
this.setGroup=function(_6ed){
if(_6ed){
this.group=_6ed;
this.toggle=true;
if(!turbo.button.groups[this.group]){
turbo.button.groups[this.group]=[this];
}else{
turbo.button.groups[this.group].push(this);
}
}
};
this.blur=function(){
if(this.btn){
this.btn.blur();
}
};
this.updateButton=function(_6ee){
dojo.debug("abstract function TurboButtonBase.updateButton invoked.");
};
this.setState=function(_6ef){
if(dojo.lang.isString(_6ef)){
_6ef=(_6ef?this.states[_6ef]:this.states.normal);
}
if(this.group&&_6ef==this.states.down){
turbo.button.resetGroup(this.group);
}
this.state=_6ef;
this.delayedState=this.state;
this.updateButton();
};
this.delayedSetState=function(){
if(this.state!=this.delayedState){
this.setState(this.delayedState);
}
this.delayedState=this.state;
};
this.onMouseOver=function(){
if((!this.toggle&&this.state!=this.states.disabled)||(this.state!=this.states.down)){
this.setState(this.states.over);
}
};
this.onMouseOut=function(){
if((!this.toggle&&this.state!=this.states.disabled)||(this.state!=this.states.down)){
this.delayedState=this.states.normal;
window.setTimeout(turbo.bind(this,this.delayedSetState),1);
}
};
this.onMouseDown=function(_6f0){
if(!this.toggle&&this.state!=this.states.disabled){
this.setState(this.states.down);
}
var btn=this.btn;
this.clicked=true;
if(_6f0){
dojo.event.browser.stopEvent(_6f0);
}
};
this.onMouseUp=function(){
if(!this.toggle&&this.state!=this.states.disabled){
this.setState(this.states.normal);
}
};
this.onMouseClick=function(_6f2){
if(!this.clicked){
this.onMouseDown(null);
window.setTimeout(turbo.bind(this,this.onMouseUp),100);
}else{
this.blur();
this.clicked=false;
}
if(this.toggle&&(!this.group||this.state!=this.states.down)){
this.setState(this.state!=this.states.down?this.states.down:this.states.normal);
}
this.event=_6f2;
this.onClick(_6f2);
};
};
dojo.widget.HtmlTurboButton=function(){
turbo.setWidgetType(this,"TurboButton");
dojo.widget.TurboButtonBase.call(this);
this.templatePath=null;
this.templateString="<div><button disabled=\"disabled\" dojoAttachPoint=\"btnLeft\">&#160;</button><button dojoAttachPoint=\"btn\" dojoAttachEvent=\"onMouseOver; onMouseOut; onMouseDown; onMouseUp; onclick: onMouseClick;\">Caption</button><button disabled=\"disabled\" dojoAttachPoint=\"btnRight\">&#160;</button></div>";
this.classTag="turbo-button";
this.hideLeft="";
this.hideRight="";
this.themeJs=true;
this.btn=null;
this.btnLeft=null;
this.btnRight=null;
this.fillInTemplate=function(_6f3,_6f4){
this.initButton(_6f3);
if(this.hideLeft){
this.btnLeft.style.display="none";
}
if(this.hideRight){
this.btnRight.style.display="none";
}
if(!this.value){
var node=this.getWidgetFragment(_6f4);
if(node.innerHTML){
this.value=node.innerHTML;
}
}
if(this.value){
this.setCaption(this.value);
}
};
this.styleChanged=function(){
this.setStyledClass(this.domNode,"");
this.updateButton();
};
this.getButtonClass=function(){
return ["","-down","","-over"][this.state];
};
this.setButtonClasses=function(){
var cn=this.getButtonClass();
this.btnLeft.className=this.classTag+"-left"+cn;
this.btn.className=this.classTag+"-mid"+cn;
this.btnRight.className=this.classTag+"-right"+cn;
};
this.updateButton=function(){
this.btn.disabled=(this.state==this.states.disabled?"disabled":"");
this.setButtonClasses();
};
this.setCaption=function(_6f7){
this.btn.innerHTML=_6f7;
};
this.set=function(_6f8,_6f9,_6fa){
var h=_6f8;
if(_6f9){
h="<img src=\""+(_6fa?"":"images/")+_6f9+"\" align=\"absmiddle\">&#160;"+h;
}
this.btn.innerHTML=h;
};
};
dojo.inherits(dojo.widget.HtmlTurboButton,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turboButton");
dojo.widget.HtmlTurboTab=function(){
this.widgetType="TurboTab";
dojo.widget.HtmlTurboButton.call(this);
this.classTag="turbo-tab";
this.themeJs=true;
this.getButtonClass=function(){
return ["","-down","","-over"][this.state];
};
};
dojo.inherits(dojo.widget.HtmlTurboTab,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turboTab");
dojo.widget.HtmlTurboToolbtn=function(){
this.widgetType="TurboToolbtn";
dojo.widget.TurboButtonBase.call(this);
this.templatePath=null;
this.templateString="<button dojoAttachPoint=\"btn\" dojoAttachEvent=\"onMouseDown; onMouseOver; onMouseOut; onclick: onMouseClick;\"><div dojoAttachPoint=\"div\"><img dojoAttachPoint=\"img\"></div></button>";
this.classTag="turbo_toolbtn";
this.glyph="";
this.image="";
this.caption="";
this.btn=null;
this.span=null;
this.div=null;
this.img=null;
this.fillInTemplate=function(_6fc){
this.initButton();
this.setCaption(this.caption?this.caption:this.value);
this.setGlyph(this.image?this.image:this.glyph);
if(!dojo.render.html.ie&&!dojo.render.html.moz){
this.btn.style.paddingLeft="2px";
this.btn.style.paddingRight="2px";
}
};
this.styleChanged=function(){
this.updateButton();
};
this.updateButton=function(){
this.btn.disabled=(this.state==this.states.disabled?"disabled":"");
this.setStyledClass(this.btn,["","_down","","_over"][this.state]);
};
this.setCaption=function(_6fd){
if(!_6fd){
return;
}
if(!this.span){
this.span=document.createElement("span");
this.btn.appendChild(this.span);
}
this.span.innerHTML=_6fd;
};
this.setGlyph=function(_6fe){
if(_6fe){
this.img.src="images/"+_6fe;
}else{
this.img.style.display="none";
}
};
this.set=function(_6ff,_700){
this.setCaption(_6ff);
this.setGlyph(_700);
};
};
dojo.inherits(dojo.widget.HtmlTurboToolbtn,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbotoolbtn");
dojo.widget.HtmlTurboSimpleButton=function(){
this.widgetType="TurboSimpleButton";
dojo.widget.TurboButtonBase.call(this);
this.classTag="turbo_sbtn";
this.btn=null;
this.fillInTemplate=function(){
this.initButton();
if(this.value){
this.btn.innerHTML=this.value;
}
};
this.styleChanged=function(){
this.updateButton();
};
this.updateButton=function(){
this.btn.disabled=(this.state==this.states.disabled?"disabled":"");
var a=["","Down","","Over"];
this.btn.className=this.classTag+[this.state];
};
};
dojo.inherits(dojo.widget.HtmlTurboSimpleButton,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbosimplebutton");
dojo.widget.HtmlTurboCheckbox=function(){
turbo.setWidgetType(this,"TurboCheckbox");
dojo.widget.TurboButtonBase.call(this);
this.templatePath=null;
this.templateString="<div dojoAttachPoint=\"btnDiv\"><button dojoAttachPoint=\"btn\" dojoAttachEvent=\"onMouseOver; onMouseOut; onclick: onMouseClick;\"><span dojoAttachPoint=\"span\">Checkbox</span></button></div>";
this.classTag="turbo_cbox";
this.btnDiv=null;
this.btn=null;
this.span=null;
this.fillInTemplate=function(){
this.toggle=true;
this.initButton();
if(this.value){
this.span.innerHTML=this.value;
}
};
this.styleChanged=function(){
this.updateButton();
};
this.updateButton=function(){
this.setClassName(this.btnDiv,"");
var a=["Off","Down","Off","Over"];
this.setClassName(this.btn,a[this.state]);
this.btn.disabled=(this.state==this.states.disabled?"disabled":"");
this.setClassName(this.span,"Span"+a[this.state]);
};
};
dojo.inherits(dojo.widget.HtmlTurboCheckbox,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbocheckbox");
dojo.widget.HtmlTurboRadio=function(){
this.widgetType="TurboRadio";
dojo.widget.HtmlTurboCheckbox.call(this);
this.classTag="turbo_radio";
};
dojo.inherits(dojo.widget.HtmlTurboRadio,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turboradio");
dojo.provide("turbo.widgets.TurboToolbar");
dojo.provide("turbo.widgets.HtmlTurboToolbar");
dojo.require("turbo.widgets.TurboWidget");
dojo.require("turbo.widgets.TurboButton");
dojo.widget.HtmlTurboToolbar=function(){
turbo.setWidgetType(this,"TurboToolbar");
dojo.widget.HtmlTurboWidget.call(this);
this.templatePath=null;
this.list=false;
this.classTag="turbo-toolbar";
this.fillInTemplate=function(_703,_704){
var frag=new dojo.xml.Parse().parseElement(this.domNode);
dojo.widget.getParser().createComponents(frag);
};
this.styleChanged=function(){
this.setStyledClass(this.domNode,(this.list?"-list":""));
};
};
dojo.inherits(dojo.widget.HtmlTurboToolbar,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbotoolbar");
dojo.provide("turbo.widgets.TurboTree2");
dojo.provide("turbo.widgets.HtmlTurboTree2");
dojo.require("turbo.widgets.TurboWidget");
turbo.TreeNode=function(_706){
this.tree=_706;
this.id=this.tree.makeNodeId();
this.childCount=0;
this.domNode=null;
this.elements={gutter:0,connector:1,button:1,content:2,children:3};
this.hasChildren=false;
this.childrenInited=false;
this.getNodeElement=function(_707){
return (this.domNode?this.domNode.childNodes[_707]:null);
};
this.getImg=function(_708){
var i=document.createElement("img");
i.src=this.tree.imageRoot+_708;
return i;
};
this.getBar=function(){
return this.getImg("tree_bar.gif");
};
this.getBlank=function(){
return this.getImg("tree_blank.gif");
};
this.buildGutter=function(_70a){
if(_70a){
var g=_70a.getNodeElement(this.elements.gutter).cloneNode(true);
g.appendChild((!g.hasChildNodes()||_70a.isLastChildNode())?this.getBlank():this.getBar());
return g;
}else{
return document.createElement("span");
}
};
this.buildContent=function(){
var s=document.createElement("span");
s.innerHTML="Node";
s.className=this.tree.classTag+"-content";
s.style.cursor="default";
return s;
};
this.buildDomNode=function(_70d){
node=document.createElement("div");
node.appendChild(this.buildGutter(_70d));
node.appendChild(document.createElement("img"));
node.appendChild(this.buildContent());
node.appendChild(document.createElement("div"));
node.setAttribute("turboTreeNode","true");
node.id=this.id;
this.domNode=node;
return node;
};
this.setConnector=function(_70e,_70f){
var i=this.getNodeElement(this.elements.connector);
i.style.backgroundImage="url("+this.tree.imageRoot+(_70e?"tree_root":(_70f?"tree_last_leaf":"tree_leaf"))+".gif"+")";
};
this.isRootNode=function(){
return (this.domNode.parentNode==this.tree.treeDiv);
};
this.isLastChildNode=function(){
return (this.domNode.parentNode&&this.domNode.parentNode.lastChild==this.domNode);
};
this.selectConnector=function(){
this.setConnector(this.isRootNode(),this.isLastChildNode());
};
this.getButton=function(){
return this.getNodeElement(this.elements.button);
};
this.setButton=function(_711,_712){
this.getButton().src=this.tree.imageRoot+(!_711?"tree_blank":(_712?"tree_open":"tree_closed"))+".gif";
};
this.selectButton=function(){
this.setButton(this.hasChildren,this.getOpen());
};
this.getOpen=function(){
var n=this.getNodeElement(this.elements.children);
return (n?n.style.display=="":undefined);
};
this.setOpen=function(_714){
var n=this.getNodeElement(this.elements.children);
if(n){
n.style.display=(_714?"":"none");
}
if(this.getOpen()&&this.hasChildren&&!this.childrenInited){
this.tree.initChildren(this);
}
this.selectButton();
};
this.toggleNode=function(){
this.setOpen(!this.getOpen());
};
this.setContent=function(_716){
this.getNodeElement(this.elements.content).innerHTML=_716;
};
this.appendTo=function(_717){
this.parent=_717;
var p=(_717?_717.getNodeElement(this.elements.children):this.tree.treeDiv);
this.index=p.childNodes.length;
p.appendChild(this.domNode);
var n=this.tree.nodeFromDomNode(this.domNode.previousSibling);
if(n){
n.selectConnector();
}
this.selectConnector();
};
this.setSelected=function(_71a){
if(_71a===undefined){
_71a=true;
}
var c=this.tree.classTag+"-content";
var _71c=this.getNodeElement(this.elements.content);
if(_71c){
_71c.className=c+(_71a?" "+c+"-selected":"");
}
};
};
dojo.widget.HtmlTurboTree2=function(){
this.widgetType="TurboTree2";
dojo.widget.HtmlTurboWidget.call(this);
this.templatePath=null;
this.templateString="<div><div dojoattachpoint=\"treeDiv\" class=\"turbo-tree-scroller\"></div></div>";
this.styleRoot="TurboTree";
this.classTag="turbo-tree";
this.imageRoot=dojo.uri.dojoUri(this.themeRoot+"default/images/");
this.treeDiv=null;
this.nodeId=0;
this.nodes=[];
this.selected=null;
this.onInitNode=function(){
};
this.onInitChildren=function(){
};
this.onCanUnselect=function(_71d){
};
this.onCanSelect=function(_71e){
};
this.onSelect=function(_71f){
};
this.fillInTemplate=function(_720,_721){
this.bindArgEvents(_720);
this.setTheme("");
dojo.event.connect(this.domNode,"onclick",this,"domClick");
};
this.styleChanged=function(){
this.setStyledClass(this.domNode,"");
};
this.makeNodeId=function(){
return this.widgetId+":"+this.nodeId++;
};
this.nodeFromDomNode=function(_722){
return (_722&&_722.id?this.nodes[_722.id]:null);
};
this.clear=function(){
this.nodeId=0;
this.nodes=[];
this.treeDiv.innerHTML="";
};
this.setRootCount=function(_723){
this.clear();
for(var i=0;i<_723;i++){
this.newNode(null);
}
};
this.newTreeNode=function(_725){
var n=new turbo.TreeNode(this);
this.nodes[n.id]=n;
n.buildDomNode(_725);
return n;
};
this.newNode=function(_727){
var n=this.newTreeNode(_727);
n.appendTo(_727);
n.setOpen(false);
this.onInitNode(n);
n.selectButton();
return n;
};
this.initChildren=function(_729){
this.onInitChildren(_729);
var c=_729.childCount;
for(var i=0;i<c;i++){
this.newNode(_729);
}
_729.childrenInited=true;
_729.hasChildren=(c>0);
};
this.selectNode=function(_72c){
if(_72c&&this.onCanSelect(_72c)===false){
return;
}
if(this.selected){
if(this.onCanUnselect(this.selected)===false){
return;
}
this.selected.setSelected(false);
}
this.selected=_72c;
if(this.selected){
this.selected.setSelected(true);
}
this.onSelect(this.selected);
};
this.forEach=function(_72d){
for(var i in this.nodes){
if(_72d(this.nodes[i])===true){
return this.nodes[i];
}
}
return null;
};
this.isTreeNode=function(_72f){
return _72f&&_72f.getAttribute&&_72f.getAttribute("turboTreeNode");
};
this.nodeClick=function(_730,_731){
if(_730){
if(_730.getButton()==_731.target){
_730.toggleNode();
}else{
this.selectNode(_730);
}
}
};
this.domClick=function(_732){
var n=_732.target;
while(n&&!this.isTreeNode(n)){
n=n.parentNode;
}
this.nodeClick((n?this.nodeFromDomNode(n):null),_732);
};
};
dojo.inherits(dojo.widget.HtmlTurboTree2,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbotree2");
dojo.provide("turbo.widgets.TurboGrid");
dojo.provide("turbo.widgets.HtmlTurboGrid");
dojo.require("turbo.widgets.TurboWidget");
dojo.widget.HtmlTurboGrid=function(){
this.widgetType="TurboGrid";
dojo.widget.HtmlTurboWidget.call(this);
this.autobuild=true;
this.autosize=true;
this.autosizing=false;
this.controller={};
this.classTag="turbo-grid";
this.cols=0;
this.colWidth=96;
this.colWidths=[];
this.fixedColWidth=40;
this.rows=0;
this.scrollLeft=0;
this.selectedRow=-1;
this.selectCount=0;
this.sortInfo={column:-1,desc:false};
this.selected=[];
this.rowMarkerClass=[];
this.readyImage="";
this.busyImage="";
this.templatePath=null;
this.templateString="<table dojoAttachPoint=\"GrdTbl\" cellspacing=\"0\" cellpadding=\"0\"><tr><td><div dojoAttachPoint=\"Corner\" class=\"turbo-grid-corner\">&#160;</div><div dojoAttachPoint=\"ColDiv\" class=\"turbo-grid-col\"></div></td><td><div dojoAttachPoint=\"HdrDiv\" class=\"turbo-grid-hdr\"></div><div dojoAttachPoint=\"DtaDiv\" class=\"turbo-grid-dta\"></div></td></tr><tr><td dojoAttachPoint=\"Status\" colspan=\"2\" class=\"turbo-grid-status\">Loading...</td></tr></table>";
this.Corner=null;
this.ColDiv=null;
this.HdrDiv=null;
this.DtaDiv=null;
this.Status=null;
this.GrdTbl=null;
this.DtaTbl=null;
this.HdrTbl=null;
this.ColTbl=null;
var _734=false;
var _735=null;
this.getCell=function(_736,_737){
return (this.controller.getCell?this.controller.getCell(this,_736,_737):_736+", "+_737);
};
this.getColumnTitle=function(_738){
return (this.controller.getColumnTitle?this.controller.getColumnTitle(this,_738):undefined);
};
this.getColumnWidth=function(_739){
var w=(this.controller.getColumnWidth?this.controller.getColumnWidth(this,_739):-1);
return (w>=0?w:this.colWidth);
};
this.getSortInfo=function(){
this.sortInfo=(this.controller.getSortInfo?this.controller.getSortInfo(this):this.sortInfo);
return this.sortInfo;
};
this.onInit=function(){
};
this.onSelectionChange=function(){
};
this.onSelectRow=function(_73b){
if(this.controller.onSelectRow){
this.controller.onSelectRow(this,_73b);
}
};
this.onUnselectRow=function(_73c){
if(this.controller.onUnselectRow){
this.controller.onUnselectRow(this,_73c);
}
};
this.onUpdateRow=function(_73d){
if(this.controller.onUpdateRow){
this.controller.onUpdateRow(this,_73d);
}
};
this.onEditDone=function(_73e){
if(this.controller.onEditDone){
this.controller.onEditDone(this);
}
};
this.onEditRowStart=function(_73f){
if(this.controller.onEditRowStart){
this.controller.onEditRowStart(this,_73f);
}
};
this.onEditRowDone=function(){
if(this.controller.onEditRowDone){
this.controller.onEditRowDone(this);
}
};
this.onKeyDown=function(_740){
if(this.controller.onKeyDown){
this.controller.onKeyDown(this,_740);
}
};
this.onDataClick=function(_741,_742){
if(this.controller.onDataClick){
this.controller.onDataClick(this,_741,_742);
}
};
this.onDataDblClick=function(_743,_744){
if(this.controller.onDataDblClick){
this.controller.onDataDblClick(this,_743,_744);
}
};
this.onHeaderClick=null;
this.fillInTemplate=function(_745){
this.bindArgEvents(_745);
this.bindArgEvent("onHeaderClick",_745);
this.setTheme(this.theme);
this.DtaDiv.onscroll=turbo.bind(this,this.doScroll);
this.onInit();
if(this.rows&&this.cols&&this.autobuild){
this.build();
}
dojo.event.connect(this.DtaDiv,"onkeydown",this,"dataKeyDown");
dojo.event.topic.subscribe("turboresize",this,"turboResize");
};
this.turboResize=function(){
if(turbo.showing(this.domNode.parentNode)){
this.resize();
}
};
this.enableAutoResize=function(){
if(!this.autosizing){
dojo.event.connect(window,"onresize",this,"doResize");
}
this.autosizing=true;
};
this.setElementClass=function(_746,_747){
_747=(_747?this.classTag+"-"+_747:"");
if(_746.className!=_747){
_746.className=_747;
}
};
this.setStyledClass=function(_748,_749){
if(!_749){
_749="";
}
_748.className=this.classTag+_749+(this.style?" "+this.classTag+"-"+this.style+_749:"");
};
this.styleChanged=function(){
this.setStyledClass(this.GrdTbl);
};
this.setStatus=function(_74a,_74b){
var h=(_74b?"<img src=\"images/"+_74b+"\" align=\"absmiddle\"/>":"");
this.Status.innerHTML=h+_74a;
};
this.setReadyStatus=function(){
document.body.style.cursor="default";
this.setStatus("Ready.",this.readyImage);
};
this.setBusyStatus=function(){
this.setStatus("Busy.",this.busyImage);
document.body.style.cursor="wait";
};
this.setSize=function(_74d,_74e){
this.cols=_74d;
this.rows=_74e;
};
this.clearGrid=function(){
this.scrollLeft=0;
this.selected=[];
this.selectCount=0;
this.selectedRow=-1;
this.rowMarkerClass=[];
this.sortInfo={};
this.onSelectionChange();
};
this.teardownRows=function(){
this.clearGrid();
this.DtaDiv.innerHTML="";
this.ColDiv.innerHTML="";
this.DtaTbl=null;
this.ColTbl=null;
};
this.teardown=function(){
this.teardownRows();
this.HdrDiv.innerHTML="";
this.HdrTbl=null;
};
this.build=function(){
var t=turbo.time();
this.cacheColWidths();
this.buildTable();
this.buildFixedColumn();
this.buildHeader();
this.setScrollLeft();
this.setReadyStatus();
window.setTimeout(turbo.bind(this,this.resize),100);
this.debug("build","build time: "+(turbo.time()-t)+"ms");
};
this.refresh=function(){
if(!dojo.render.html.ie){
this.build();
return;
}
this.cacheColWidths();
this.refreshHeader();
this.refreshData();
this.updateRowSizes();
};
this.getCellPos=function(_750){
return {col:turbo.getCellIndex(_750),row:_750.parentNode.rowIndex};
};
this.sameCell=function(inA,inB){
return inA&&inB&&(inA.col==inB.col)&&(inA.row==inB.row);
};
this.goodCell=function(_753){
return (_753.col>=0&&_753.col<this.cols&&_753.row>=0&&_753.row<this.rows);
};
this.getDomCell=function(_754){
return this.DtaTbl.rows[_754.row].cells[_754.col];
};
this.refreshCell=function(_755){
this.getDomCell(_755).innerHTML=this.getCell(_755.col,_755.row);
};
this.setSortInfo=function(_756,_757){
if(this.sortInfo.column==_756&&_757===undefined){
_757=!this.sortInfo.desc;
}
this.sortInfo={column:_756,desc:_757};
};
this.setSortColumn=this.setSortInfo;
this.cacheColWidths=function(){
for(var i=0;i<this.cols;i++){
this.colWidths[i]=this.getColumnWidth(i);
}
};
this.calcColsWidth=function(){
var sum=0;
for(var i=0;i<this.cols;i++){
sum+=this.colWidths[i];
}
return sum;
};
this.calcTableWidth=function(){
return this.calcColsWidth()+this.cols*(1+2+6)+1;
};
this.getRowClass=function(_75b){
if(this.controller.getRowClass){
return this.controller.getRowClass(_75b);
}
var _75c=this.rowMarkerClass[_75b];
if(this.selected[_75b]&&(!_75c||this.selectCount>1)){
_75c="selected";
}
return this.classTag+"-row-"+(_75b&1)+(_75c?" "+this.classTag+"-"+_75c:"");
};
this.getRowHeight=function(_75d){
var row=this.DtaTbl.rows[_75d];
if(dojo.render.html.safari&&row&&row.cells&&row.cells.length>0){
row=row.cells[0];
}
return (row?row.offsetHeight-(dojo.render.html.ie?5:0):0);
};
this.getTable=function(){
return "<table width=\""+this.calcTableWidth()+"\" cellspacing=\"0\">";
};
this.createTable=function(){
var _75f=document.createElement("table");
_75f.cellPadding=0;
_75f.cellSpacing=0;
_75f.width=this.calcTableWidth();
return _75f;
};
this.getHeaderCell=function(_760){
var h=this.getColumnTitle(_760);
if(h===undefined){
var a=Math.floor(_760/26);
var b=_760%26;
var _764=function(c){
return String.fromCharCode("A".charCodeAt(0)+c);
};
h=(a>0?_764(a-1):"")+_764(b);
}
return h;
};
this.getHeaderCellHtml=function(inW,_767){
var cn=(this.sortInfo["column"]!=_767?"":" class=\""+this.classTag+"-sort-"+(this.sortInfo["desc"]?"down":"up")+"\"");
return "<div style=\"width:"+inW+"px;\""+cn+">"+this.getHeaderCell(_767)+"</div>";
};
this.buildHeader=function(){
this.getSortInfo();
var c="",w;
var sep="<td class=\"turbo-separator\"></td>";
for(var i=0;i<this.cols;i++){
w=this.colWidths[i];
c+="<th width=\""+w+"\">"+this.getHeaderCellHtml(w,i)+"</th>";
c+=sep;
}
c+="<th></th>";
var h="<tr>"+c+"</tr>";
c="";
var bv="<th class=\""+this.classTag+"-bevel\" width=\"";
for(var i=0;i<this.cols;i++){
c+=bv+this.getColumnWidth(i)+"\"></th>"+"<td class=\"turbo-separator\"></td>";
}
c+="<th></th>";
h+="<tr>"+c+"</tr>";
this.HdrDiv.innerHTML=this.getTable()+h+"</table>";
this.HdrTbl=this.HdrDiv.firstChild;
this.HdrHeight=this.HdrDiv.clientHeight;
dojo.event.connect(this.HdrTbl,"onmousedown",this,"headerDown");
dojo.event.connect(this.HdrTbl,"onmousemove",this,"headerMove");
dojo.event.connect(this.HdrTbl,"onmouseup",this,"headerUp");
dojo.event.connect(this.HdrTbl,"onmouseover",this,"headerOver");
dojo.event.connect(this.HdrTbl,"onmouseout",this,"headerOut");
dojo.event.connect(this.HdrTbl,"onclick",this,"headerClick");
};
this.refreshHeader=function(){
this.getSortInfo();
var row=this.HdrTbl.rows[0];
for(var i=0;i<this.cols;i++){
var cell=row.cells[i*2];
var w=this.getColumnWidth(i);
cell.width=w;
cell.innerHTML=this.getHeaderCellHtml(w,i);
}
};
this.findEventCell=function(_772,_773){
while(_772.tagName!="TD"&&_772.parentNode&&_772.parentNode!=this.GrdTbl){
_772=_772.parentNode;
}
return (_772&&dojo.dom.isDescendantOf(_772,_773)?_772:null);
};
this.findEventHeaderCell=function(_774,_775){
while(_774.tagName!="TH"&&_774.parentNode&&_774.parentNode!=this.GrdTbl){
_774=_774.parentNode;
}
return (_774&&dojo.dom.isDescendantOf(_774,_775)?_774:null);
};
this.getHeaderCellIndex=function(_776){
return turbo.getCellIndex(_776)>>1;
};
this.isValidHeaderCell=function(_777){
return (this.getHeaderCellIndex(_777)<this.cols);
};
this.headerDown=function(_778){
var _779=this.findEventCell(_778.target,this.HdrTbl);
if(_779&&this.isValidHeaderCell(_779)){
_734=true;
_735=_779;
turbo.capture(_735);
_778.preventDefault();
_778.stopPropagation();
}
};
this.headerMove=function(_77a){
if(_734){
window.status=_77a.clientX+", "+_77a.clientY;
_77a.preventDefault();
_77a.stopPropagation();
}
};
this.headerUp=function(_77b){
if(_734){
_734=false;
turbo.release(_735);
}
};
this.headerOver=function(_77c){
var _77d=this.findEventHeaderCell(_77c.target,this.HdrTbl);
if(_77d&&this.isValidHeaderCell(_77d)){
this.setElementClass(_77d,"over");
this.setElementClass(this.getBevel(_77d),"bevel-over");
}
};
this.headerOut=function(_77e){
var _77f=this.findEventHeaderCell(_77e.target,this.HdrTbl);
if(_77f&&this.isValidHeaderCell(_77f)){
this.setElementClass(_77f,"");
this.setElementClass(this.getBevel(_77f),"bevel");
}
};
this.delayedHeaderClick=function(_780){
var idx=turbo.getCellIndex(_780)>>1;
if(this.onHeaderClick){
this.onHeaderClick(idx);
}else{
if(this.controller.onHeaderClick){
this.controller.onHeaderClick(this,idx);
}
}
};
this.headerClick=function(_782){
if(!this.onHeaderClick&&!this.controller.onHeaderClick){
return;
}
var _783=this.findEventHeaderCell(_782.target,this.HdrTbl);
if(_783&&this.isValidHeaderCell(_783)){
this.setElementClass(_783,"down");
this.setElementClass(this.getBevel(_783),"bevel-over");
this.getScrollLeft();
window.setTimeout(turbo.bindArgs(this,this.delayedHeaderClick,_783),1);
}
};
this.getFixedColClass=function(_784){
return (this.selected[_784]?this.classTag+"-fixed-select":"");
};
this.formatFixedCol=function(_785){
return (this.controller.formatFixedCol?this.controller.formatFixedCol(this,_785):Number(_785)+1);
};
this.buildFixedColumn=function(){
this.Corner.style.width=this.fixedColWidth+"px";
this.ColDiv.style.width=this.fixedColWidth+"px";
var tbl=new Array(this.rows+2);
tbl[0]="<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
for(var c,j=0,k=1;j<this.rows;j++,k++){
c=this.getFixedColClass(j);
c=(c?" class=\""+c+"\"":"");
tbl[k]="<tr height=\""+this.getRowHeight(j)+"\"><td"+c+">"+this.formatFixedCol(j)+"</td></tr>";
}
tbl[k]="<tr><td height=\"64\" style=\"border: none; background-image: none;\"></td></tr>";
this.ColDiv.innerHTML=tbl.join("");
this.ColTbl=this.ColDiv.firstChild;
dojo.event.connect(this.ColTbl,"onclick",this,"fixedTableClick");
};
this.updateFixedColumnRow=function(_788){
var row=this.ColTbl.rows[_788];
var cell=row.cells[0];
cell.className=this.getFixedColClass(_788);
cell.innerHTML=this.formatFixedCol(_788);
};
this.buildCells=function(){
var _78b=new Array(this.cols);
for(var i=0;i<this.cols;i++){
var w=this.getColumnWidth(i);
_78b[i]="<td width=\""+w+"\"><div style=\"width:"+w+"px;\">";
}
return _78b;
};
this.buildTable=function(){
var _78e=this.buildCells();
var tbl=new Array(this.rows);
for(var j=0;j<this.rows;j++){
var row=new Array(this.cols);
for(var i=0;i<this.cols;i++){
row[i]=_78e[i]+this.getCell(i,j)+"</div></td>";
}
tbl[j]="<tr class=\""+this.classTag+"-row-"+(j&1)+"\">"+row.join("")+"<td>&#160;</td></tr>";
}
var h=tbl.join("");
this.DtaDiv.innerHTML=this.getTable()+h+"</table>";
this.DtaTbl=this.DtaDiv.firstChild;
dojo.event.connect(this.DtaTbl,"onmouseover",this,"tableOver");
dojo.event.connect(this.DtaTbl,"onmouseout",this,"tableOut");
dojo.event.connect(this.DtaTbl,"onclick",this,"tableClick");
dojo.event.connect(this.DtaTbl,"ondblclick",this,"tableDblClick");
};
this.buildCols=function(_794){
var cell;
var j=_794.rowIndex;
for(var i=0;i<this.cols;i++){
var w=this.getColumnWidth(i);
cell=_794.insertCell(i);
cell.width=w;
var h="<div style=\"width:"+w+"px;\">"+this.getCell(i,j)+"</div>";
cell.innerHTML=h;
}
cell=_794.insertCell(this.cols);
cell.innerHTML="&#160;";
};
this.buildRow=function(_79a){
_79a.onmouseover=turbo.bindArgs(this,this.dataOver,_79a);
_79a.onmouseout=turbo.bindArgs(this,this.dataOut,_79a);
_79a.className=this.getRowClass(_79a.rowIndex);
this.buildCols(_79a);
};
this.refreshData=function(){
for(var j=0;j<this.rows;j++){
var row=this.DtaTbl.rows[j];
for(var i=0;i<this.cols;i++){
row.style.height="0px";
row.cells[i].innerHTML="<div style=\"width:"+this.colWidths[i]+"px;\">"+this.getCell(i,j)+"</div>";
}
}
};
this.getFirstSelectedRow=function(){
for(var i=0;i<this.rows;i++){
if(this.selected[i]){
return Number(i);
}
}
return -1;
};
this.getNextSelectedRow=function(_79f){
for(var i=_79f+1;i<this.rows;i++){
if(this.selected[i]){
return i;
}
}
return -1;
};
this.hasSelection=function(){
return (this.getFirstSelectedRow()>-1);
};
this.getSelectedRows=function(){
var _7a1=[];
for(var i=0;i<this.rows;i++){
if(this.selected[i]){
_7a1[i]=true;
}
}
return _7a1;
};
this.clearSelection=function(){
this.selected=[];
this.selectedRow=-1;
this.selectCount=0;
this.updateRowClasses();
this.buildFixedColumn();
this.onSelectionChange();
};
this.setRowSelected=function(_7a3,_7a4){
if(_7a3<0){
return;
}
if(_7a4===undefined){
_7a4=true;
}
if(this.selected[_7a3]!=_7a4){
this.selected[_7a3]=_7a4;
this.selectedRow=(_7a4?_7a3:-1);
this.selectCount+=(_7a4?1:-1);
if(_7a4){
this.onSelectRow(_7a3);
}else{
this.onUnselectRow(_7a3);
}
}
this.selectedRow=(_7a4?_7a3:-1);
this.updateFixedColumnRow(_7a3);
this.DtaTbl.rows[_7a3].className=this.getRowClass(_7a3);
};
this.selectRow=function(_7a5){
if(!this.selected[_7a5]){
this.setRowSelected(_7a5,true);
this.updateRowSizes();
}
};
this.deselectRow=function(_7a6){
if(this.selected[_7a6]){
this.setRowSelected(_7a6,false);
this.updateRowSizes();
}
};
this.toggleSelectRow=function(_7a7){
if(this.selected[_7a7]){
this.setRowSelected(_7a7,false);
}else{
this.setRowSelected(_7a7,true);
}
};
this.unselectRows=function(_7a8){
for(var i in this.selected){
if(i!=_7a8&&this.selected[i]){
this.setRowSelected(i,false);
}
}
};
this.clickSelect=function(_7aa,_7ab,_7ac){
if((!_7ab&&!_7ac)){
this.unselectRows(_7aa);
}
if(!_7ac){
if(_7ab){
this.toggleSelectRow(_7aa);
}else{
this.setRowSelected(_7aa,true);
}
this.updateRowClasses();
}else{
var r=(this.selectedRow<0?0:this.selectedRow);
var s=r;
var e=_7aa;
if(s>_7aa){
e=s;
s=_7aa;
}
for(var i=s;i<=e;i++){
this.setRowSelected(i,true);
}
this.updateRowClass(r);
}
window.setTimeout(turbo.bind(this,this.updateRowSizes),100);
this.onSelectionChange();
};
this.offsetMarkers=function(_7b1,_7b2){
var _7b3=[];
for(var i in this.rowMarkerClass){
if(this.rowMarkerClass[i]){
if(i>=_7b1){
_7b3[Number(i)+_7b2]=this.rowMarkerClass[i];
}else{
_7b3[i]=this.rowMarkerClass[i];
}
}
}
this.rowMarkerClass=_7b3;
};
this.setMarker=function(_7b5,_7b6){
this.rowMarkerClass[_7b5]=_7b6;
this.updateRowClass(_7b5);
};
this.clearMarkers=function(){
this.rowMarkerClass=[];
};
this.addRow=function(_7b7){
this.clearSelection();
this.buildRow(this.DtaTbl.insertRow(_7b7));
this.offsetMarkers(_7b7,1);
this.rows++;
this.buildFixedColumn();
this.updateRowSizes();
this.setRowSelected(_7b7,true);
};
this.removeRow=function(_7b8){
this.rowMarkerClass[_7b8]=null;
this.offsetMarkers(_7b8,-1);
this.rows--;
this.DtaTbl.deleteRow(_7b8);
this.clearSelection();
};
this.updateRow=function(_7b9){
this.DtaTbl.deleteRow(_7b9);
this.buildRow(this.DtaTbl.insertRow(_7b9));
};
this.swapRows=function(_7ba,_7bb){
turbo.array_swap(this.rowMarkerClass,_7ba,_7bb);
this.updateRow(_7ba);
this.updateRow(_7bb);
this.updateRowSizes();
};
this.replaceRow=function(_7bc){
this.updateRow(_7bc);
this.updateRowSizes();
};
this.updateRowSizes=function(){
if(!this.ColTbl){
return;
}
for(var j=0;j<this.rows;j++){
turbo.setStyleHeightPx(this.ColTbl.rows[j],this.getRowHeight(j));
}
};
this.updateRowClass=function(_7be){
this.DtaTbl.rows[_7be].className=this.getRowClass(_7be);
};
this.updateRowClasses=function(){
for(var j=0;j<this.rows;j++){
this.DtaTbl.rows[j].className=this.getRowClass(j);
}
};
this.getBevel=function(_7c0){
var _7c1=_7c0.parentNode.parentNode;
if(!_7c1.rows){
_7c1=_7c1.parentNode;
}
return _7c1.rows[1].cells[turbo.getCellIndex(_7c0)];
};
this.dataOver=function(_7c2){
if(!this.selected[_7c2.rowIndex]){
this.setElementClass(_7c2,"row-over");
}
};
this.dataOut=function(_7c3){
_7c3.className=this.getRowClass(_7c3.rowIndex);
};
this.dataClick=function(_7c4,_7c5){
this.onDataClick(_7c4,_7c5);
};
this.dataDblClick=function(_7c6,_7c7){
this.onDataDblClick(_7c6,_7c7);
};
this.dataKeyDown=function(_7c8){
this.onKeyDown(_7c8);
};
this.tableOver=function(_7c9){
var _7ca=this.findEventCell(_7c9.target,this.DtaTbl);
if(_7ca){
this.dataOver(_7ca.parentNode);
}
};
this.tableOut=function(_7cb){
var _7cc=this.findEventCell(_7cb.target,this.DtaTbl);
if(_7cc){
this.dataOut(_7cc.parentNode);
}
};
this.tableClick=function(_7cd){
var _7ce=this.findEventCell(_7cd.target,this.DtaTbl);
if(_7ce){
this.dataClick(_7ce,_7cd);
}
};
this.tableDblClick=function(_7cf){
var _7d0=this.findEventCell(_7cf.target,this.DtaTbl);
if(_7d0){
this.dataDblClick(_7d0,_7cf);
}
};
this.getScrollLeft=function(){
this.scrollLeft=this.DtaDiv.scrollLeft;
};
this.setScrollLeft=function(){
this.HdrDiv.scrollLeft=this.scrollLeft;
this.DtaDiv.scrollLeft=this.scrollLeft;
};
this.doScroll=function(){
this.HdrDiv.scrollLeft=this.DtaDiv.scrollLeft;
this.ColDiv.scrollTop=this.DtaDiv.scrollTop;
};
this.getContentSize=function(){
var siz=turbo.getContentSize(this.GrdTbl.parentNode);
siz.w-=dojo.style.getPaddingWidth(this.GrdTbl)+dojo.style.getBorderWidth(this.GrdTbl);
siz.h-=dojo.style.getPaddingWidth(this.GrdTbl)+dojo.style.getBorderHeight(this.GrdTbl);
siz.w=siz.w-this.fixedColWidth-1;
return siz;
};
this._resize=function(){
var siz=this.getContentSize();
turbo.setStyleWidthPx(this.HdrDiv,siz.w);
turbo.setStyleWidthPx(this.DtaDiv,siz.w);
this.DataWidth=this.calcTableWidth();
siz.w=(siz.w<this.DataWidth?this.DataWidth:siz.w-turbo.getScrollbarWidth());
var _7d3=function(_7d4,_7d5){
if(_7d4&&_7d5>0){
_7d4.width=_7d5;
}
};
_7d3(this.HdrTbl,siz.w+128+64);
_7d3(this.DtaTbl,siz.w);
hh=this.HdrDiv.clientHeight;
turbo.setStyleHeightPx(this.Corner,hh-1);
hh=siz.h-hh-this.Status.clientHeight-1;
turbo.setStyleHeightPx(this.ColDiv,hh);
turbo.setStyleHeightPx(this.DtaDiv,hh);
this.updateRowSizes();
};
this.earliestResize=0;
this.doResize=function(){
if(this.earliestResize<turbo.time()){
this._resize();
this.earliestResize=turbo.time()+100;
}
};
this.resize=this.doResize;
};
dojo.inherits(dojo.widget.HtmlTurboGrid,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbogrid");
dojo.provide("turbo.widgets.TurboSlider");
dojo.provide("turbo.widgets.HtmlTurboSlider");
dojo.require("turbo.widgets.TurboWidget");
turbo.rangemap=function(){
this.minimum=0;
this.maximum=100;
this.getRange=function(){
return (this.maximum-this.minimum);
};
this.getExtentOverRange=function(){
return this.getExtent()/this.getRange();
};
this.setMinMax=function(_7d6,_7d7){
this.minimum=_7d6;
this.maximum=_7d7;
};
this.changePosition=function(inDx){
var p=this.getPosition();
var n=p+inDx;
return (this.setPosition(p+inDx)-p)-inDx;
};
this.setValue=function(_7db){
this.setPosition(Math.round((_7db-this.minimum)*this.getExtentOverRange()));
};
this.getValue=function(){
var eor=this.getExtentOverRange();
return (eor?Math.round(this.getPosition()/eor):0)+this.minimum;
};
};
dojo.widget.HtmlTurboRangebar=function(){
turbo.setWidgetType(this,"TurboRangeBar");
dojo.widget.HtmlTurboWidget.call(this);
turbo.rangemap.call(this);
this.templateString="<div dojoAttachPoint=\"LeftBar\" tabindex=\"1\"><div dojoAttachPoint=\"RightBar\"></div></div>";
this.templatePath=null;
this.LeftBar=null;
this.RightBar=null;
this.classTag="turbo_rangebar";
this.margin=1;
this.fillInTemplate=function(_7dd,_7de){
if(this.extraArgs["value"]){
window.setTimeout(turbo.bindArgs(this,this.setValue,this.extraArgs["value"]),400);
}
};
this.styleChanged=function(){
this.setClassName(this.LeftBar,"Left");
this.setClassName(this.RightBar,"Right");
};
this.getWindow=function(){
return this.margin;
};
this.getExtent=function(){
return this.LeftBar.offsetWidth-this.getWindow();
};
this.getPosition=function(){
return this.RightBar.offsetLeft;
};
this.setPosition=function(_7df){
var _7e0=this.getWindow();
var _7e1=this.getExtent();
var p=(_7df>_7e1?_7e1:(_7df<this.margin?this.margin:_7df));
this.RightBar.style.marginLeft=(p&&p>0?p+"px":0);
var _7e3=this.LeftBar.offsetWidth;
return p;
};
};
dojo.inherits(dojo.widget.HtmlTurboRangebar,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turborangebar");
dojo.widget.HtmlTurboSlider=function(){
this.widgetType="TurboSlider";
dojo.widget.HtmlTurboRangebar.call(this);
this.templateString="<div dojoAttachPoint=\"LeftBar\" tabindex=\"1\"><div dojoAttachPoint=\"RightBar\"><div dojoAttachPoint=\"Thumb\"><div></div></div></div>";
this.snap=false;
this.Thumb=null;
this.classTag="turbo_slider";
this.mouseDown=false;
this.mouseX=0;
this.changing=function(_7e4){
};
this.change=function(_7e5){
};
this.inheritedFillInTemplate=this.fillInTemplate;
this.fillInTemplate=function(_7e6,_7e7){
this.inheritedFillInTemplate(_7e6,_7e7);
dojo.event.connect(this.Thumb,"onmousedown",this,"down");
dojo.event.connect(this.Thumb,"onmouseup",this,"up");
dojo.event.connect(this.Thumb,"onmousemove",this,"move");
dojo.event.connect(this.LeftBar,"onmousewheel",this,"wheel");
};
this.inheritedStyleChanged=this.styleChanged;
this.styleChanged=function(){
this.inheritedStyleChanged();
this.setClassName(this.Thumb,"Thumb");
};
this.getWindow=function(){
return this.Thumb.offsetWidth;
};
this.down=function(_7e8){
this.lastValue=this.getValue();
if(this.LeftBar.focus){
this.LeftBar.focus();
}
this.mouseDown=true;
this.mouseX=_7e8.screenX;
turbo.capture(this.Thumb);
};
this.up=function(_7e9){
if(this.mouseDown){
this.mouseDown=false;
turbo.release(this.Thumb);
if(this.snap){
this.setValue(this.getValue());
}
this.change(this);
}
};
this.move=function(_7ea){
if(this.mouseDown){
var dx=_7ea.screenX-this.mouseX;
this.mouseX=_7ea.screenX+this.changePosition(dx);
if(dojo.render.html.safari&&window.getSelection){
window.getSelection().collapse();
}
this.changing(this);
}
};
this.wheel=function(_7ec){
var v=this.getValue()+Math.round(_7ec.wheelDelta/120);
this.setValue(this.getValue()+Math.round(_7ec.wheelDelta/120));
this.changing(this);
};
};
dojo.inherits(dojo.widget.HtmlTurboSlider,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turboslider");
dojo.provide("turbo.widgets.TurboSplitter");
dojo.provide("turbo.widgets.HtmlTurboSplitter");
dojo.require("turbo.turbo");
dojo.require("turbo.lib.align");
dojo.require("turbo.widgets.TurboWidget");
dojo.widget.HtmlTurboSplitter=function(){
this.widgetType="TurboSplitter";
dojo.widget.HtmlTurboWidget.call(this);
this.templatePath=null;
this.classTag="turbo-splitter";
this.mouseDown=false;
this.mouseX=0;
this.changing=function(){
};
this.change=function(){
};
this.fillInTemplate=function(_7ee,_7ef){
switch(this.turboalign){
case "left":
case "right":
break;
case "top":
case "bottom":
this.vertical=true;
break;
default:
this.turboalign="left";
this.domNode.setAttribute("turboalign","left");
break;
}
dojo.event.connect(this.domNode,"onmousedown",this,"down");
dojo.event.connect(this.domNode,"onmouseup",this,"up");
dojo.event.connect(this.domNode,"onmousemove",this,"move");
dojo.event.connect(this.domNode,"onmouseover",this,"killCapturedEvent");
dojo.event.connect(this.domNode,"onmouseout",this,"killCapturedEvent");
this.loadStyle("");
this.setTheme(this.theme);
this.domNode.style.zIndex=1000;
};
this.styleChanged=function(){
this.domNode.style.cursor=(this.vertical?"n-resize":"e-resize");
this.setStyledClass(this.domNode,(this.vertical?"-v":"-h"));
};
this.getPosition=function(){
return {top:dojo.style.getNumericStyle(this.domNode,"top"),left:dojo.style.getNumericStyle(this.domNode,"left")};
};
this.getSizeNode=function(inDx){
switch(this.turboalign){
case "left":
case "top":
var node=this.domNode.previousSibling;
while(node&&node.nodeType!=1){
node=node.previousSibling;
}
break;
case "right":
case "bottom":
var node=this.domNode.nextSibling;
while(node&&node.nodeType!=1){
node=node.nextSibling;
}
break;
}
return node;
};
this.adjustSize=function(inDx,inDy){
turbo.setOuterSize(this.sizeNode,this.size.w+(this.turboalign=="right"?-inDx:inDx),this.size.h+(this.turboalign=="bottom"?-inDy:inDy));
turbo.aligner.align();
};
this.killCapturedEvent=function(_7f4){
if(this.mouseDown&&_7f4){
dojo.event.browser.stopEvent(_7f4);
}
};
this.down=function(_7f5){
this.sizeNode=this.getSizeNode();
if(!this.sizeNode){
return;
}
this.size=turbo.getOuterSize(this.sizeNode);
this.initialPosition=this.getPosition();
this.position=this.getPosition();
this.mouseDown=true;
this.mouseX=_7f5.screenX;
this.mouseY=_7f5.screenY;
turbo.capture(this.domNode);
document.body.style.cursor=this.domNode.style.cursor;
};
this.up=function(_7f6){
if(this.mouseDown){
this.mouseDown=false;
turbo.release(this.domNode);
this.adjustSize(this.position.left-this.initialPosition.left,this.position.top-this.initialPosition.top);
this.change();
document.body.style.cursor="";
}
};
this.move=function(_7f7){
if(this.mouseDown){
this.killCapturedEvent(_7f7);
if(this.vertical){
this.moveY(_7f7.screenY-this.mouseY);
}else{
this.moveX(_7f7.screenX-this.mouseX);
}
this.mouseX=_7f7.screenX;
this.mouseY=_7f7.screenY;
this.changing();
}
};
this.moveX=function(inDx){
this.position.left+=inDx;
this.domNode.style.left=this.position.left+"px";
};
this.moveY=function(inDy){
this.position.top+=inDy;
this.domNode.style.top=this.position.top+"px";
};
this.resizeX=function(inDx){
this.adjustSize(inDx,0);
};
this.resizeY=function(inDy){
this.adjustSize(0,inDy);
};
};
dojo.inherits(dojo.widget.HtmlTurboSplitter,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbosplitter");
dojo.provide("turbo.widgets.TurboModule");
dojo.provide("turbo.widgets.HtmlTurboModule");
dojo.require("turbo.widgets.TurboWidget");
dojo.require("dojo.io.*");
turbo.onloads=[];
turbo.doOnLoad=function(){
for(var i in turbo.onloads){
turbo.onloads[i]();
}
turbo.onloads=[];
};
dojo.addOnLoad(function(){
dojo.addOnLoad=function(_7fd){
turbo.onloads.push(_7fd);
};
});
dojo.widget.HtmlTurboModule=function(){
this.widgetType="TurboModule";
dojo.widget.HtmlTurboWidget.call(this);
this.templatePath=null;
this.classTag="turbo-module";
this.src="";
this.id="";
this.className="";
this.fit="";
this.loaded=false;
this.sync=true;
this.delayed=false;
this.themeable=false;
this.fillInTemplate=function(){
dojo.html.prependClass(this.domNode,this.classTag);
if(!this.delayed){
this.request();
}
};
this.setSrc=function(_7fe){
this.loaded=false;
this.src=_7fe;
if(!this.delayed){
this.request();
}
};
this.load=function(_7ff){
if(!_7ff){
this.request();
}else{
this.delayed=false;
this.setSrc(_7ff);
}
};
this.request=function(){
if(this.loaded||!this.src){
return;
}
var _800={url:this.src,sync:this.sync,load:turbo.bind(this,this.receive),error:turbo.bind(this,this.error)};
turbo.setBusyCursor();
try{
if(dojo.io.bind(_800)===false){
this.status="unspecified bind error";
turbo.debug(this.status);
}
}
catch(e){
this.status=e;
turbo.debug(e);
}
finally{
turbo.setDefaultCursor();
}
};
this.error=function(type,_802){
turbo.debug(_802);
this.domNode.innerHTML=_802.message;
};
this.receive=function(type,data,evt){
this.loaded=true;
turbo.clean(this.domNode);
this.domNode.innerHTML=this.extractScript(data);
if(dojo.render.html.ie&&this.turboalign){
turbo.setStyleSizePx(this.domNode,1,1);
}
this.executeScript();
this.parseWidgets();
if(this.delayed){
turbo.doOnLoad();
}
};
this.extractScript=function(_806){
var _807=[];
var xml=turbo.stringReplace(_806,/<script[\s\S]*?src="([^"]*)"[^>]*>[\s\S]*?<\/script>/ig,function(w,_80a){
_807.push(_80a);
return "";
});
var _80b=[];
var xml=turbo.stringReplace(_806,/<script[^>]*>([\s\S]*?)<\/script>/ig,function(w,_80d){
_80b.push(_80d);
return "";
});
this.script=_80b;
return xml;
};
this.executeScript=function(){
for(var i=0;i<this.script.length;i++){
var _80f="with (turbo.global) { "+this.script[i]+" }";
try{
eval(_80f);
}
catch(e){
turbo.debug("TurboModule: exception evaluating module script");
turbo.debug("message = "+e.message,"fileName = "+e.fileName,"lineNumber = "+e.lineNumber);
dojo.debug("script = ["+_80f+"]");
}
}
};
this.parseWidgets=function(){
var frag=new dojo.xml.Parse().parseElement(this.domNode);
dojo.widget.getParser().createComponents(frag);
};
};
dojo.inherits(dojo.widget.HtmlTurboModule,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbomodule");
dojo.provide("turbo.widgets.TurboNotebook");
dojo.provide("turbo.widgets.HtmlTurboNotebook");
dojo.require("turbo.widgets.TurboWidget");
dojo.require("dojo.io.*");
dojo.widget.HtmlTurboNotebook=function(){
this.widgetType="TurboNotebook";
dojo.widget.HtmlTurboWidget.call(this);
this.templatePath=null;
this.templateString="<div turboalign=\"client\"></div>";
this.themeable=false;
this.src="";
this.sync=true;
this.delayed=false;
this.classTag="turbo_notebook";
this.count=0;
this.pages=[];
this.modules=[];
this.selected=-1;
this.fillInTemplate=function(_811,_812){
var _813=this.getWidgetFragment(_812);
var i=0;
while(i<_813.childNodes.length){
var n=_813.childNodes[i];
if(n.tagName&&n.tagName.toLowerCase()=="div"){
this.addPage(n,true);
}else{
i++;
}
}
this.count=this.pages.length;
this._selectPage(0);
};
this.addPage=function(_816,_817){
var _818=null;
if(_817){
var _819=document.createElement("div");
_819.appendChild(_816);
var frag=new dojo.xml.Parse().parseElement(_819);
var _81b=dojo.widget.getParser().createComponents(frag);
if(_81b&&_81b.length>1&&_81b[1]&&_81b[1].length>0&&_81b[1][0].widgetType=="TurboModule"){
_818=_81b[1][0];
}
_816=_819.firstChild;
}
this.modules.push(_818);
this.pages.push(_816);
if(this.pages.length>1){
turbo.hide(_816);
}else{
this.selected=0;
}
this.domNode.appendChild(_816);
};
this.goodPage=function(_81c){
return (_81c>=0&&_81c<this.pages.length);
};
this.requestModule=function(_81d){
if(_81d&&_81d.delayed){
_81d.request();
}
};
this.hidePage=function(_81e){
if(this.goodPage(_81e)){
turbo.hide(this.pages[_81e]);
}
};
this.showPage=function(_81f){
if(!this.goodPage(_81f)){
return;
}
this.requestModule(this.modules[_81f]);
turbo.show(this.pages[_81f]);
};
this.showHidePage=function(_820,_821){
if(_821){
this.showPage(_820);
}else{
this.hidePage(_820);
}
};
this._selectPage=function(_822){
this.showPage(_822);
if(this.selected!=_822){
this.hidePage(this.selected);
}
this.selected=_822;
};
this.selectPage=function(_823){
var page=this.pages[_823];
if(!page["turboNotebookShown"]){
turbo.setVisibility(page,false);
}
this._selectPage(_823);
var _825=function(){
turbo.aligner.align();
turbo.setVisibility(page,true);
page.turboNotebookShown=true;
};
turbo.defer(_825,200);
};
};
dojo.inherits(dojo.widget.HtmlTurboNotebook,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbonotebook");
dojo.provide("turbo.widgets.TurboTabbar");
dojo.provide("turbo.widgets.HtmlTurboTabbar");
dojo.require("turbo.widgets.TurboWidget");
dojo.require("turbo.widgets.TurboButton");
dojo.widget.HtmlTurboTabbar=function(){
turbo.setWidgetType(this,"TurboTabbar");
dojo.widget.HtmlTurboWidget.call(this);
this.isContainer=true;
this.templatePath=null;
this.templateString="<div dojoAttachPoint=\"containerNode\"></div>";
this.containerNode=null;
this.classTag="turbo_tabbar";
this.tabIndex=0;
this.lastIndex=-1;
this.canSelectTab=function(_826){
};
this.onSelectTab=function(){
};
this.fillInTemplate=function(_827){
this.bindArgEvents(_827);
};
this.styleChanged=function(){
this.setStyledClass(this.domNode,"");
for(var i in this.children){
this.children[i].setTheme(this.style);
}
};
this.inheritedRegisterChild=this.registerChild;
this.registerChild=function(_829,_82a){
_829.tabIndex=this.children.length;
_829.onClick=this._tabClick;
_829.setGroup(this.widgetId);
_829.setTheme(this.style);
return this.inheritedRegisterChild(_829,_82a);
};
var self=this;
this._tabClick=function(){
self.tabClick(this);
};
this.getTab=function(_82c){
return this.children[_82c];
};
this.tabClick=function(_82d){
if(this.canSelectTab(_82d.inTabIndex)===false){
return this.selectTab(this.tabIndex);
}
this.lastIndex=this.tabIndex;
this.tabIndex=_82d.tabIndex;
this.onSelectTab();
};
this.selectTab=function(_82e){
this.tabIndex=_82e;
this.getTab(this.tabIndex).setState("down");
};
};
dojo.inherits(dojo.widget.HtmlTurboTabbar,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbotabbar");
dojo.provide("turbo.widgets.TurboPagebar");
dojo.provide("turbo.widgets.HtmlTurboPagebar");
dojo.require("turbo.widgets.TurboWidget");
dojo.require("turbo.widgets.TurboButton");
dojo.require("turbo.widgets.TurboTabbar");
dojo.require("turbo.widgets.TurboNotebook");
dojo.widget.HtmlTurboPagebar=function(){
this.widgetType="TurboPagebar";
dojo.widget.HtmlTurboTabbar.call(this);
this.templateString="<div turboalign=\"client\"><div dojoAttachPoint=\"containerNode\" turboalign=\"top\" class=\"turbo-pagebar-tabs\"></div><div dojoAttachPoint=\"pages\" turboAlign=\"client\" class=\"turbo-pagebar-pages\"></div></div>";
this.pages=null;
this.classTag="turbo-pagebar";
this.contentId="";
this.inheritedFillInTemplate=this.fillInTemplate;
this.fillInTemplate=function(_82f,_830){
this.inheritedFillInTemplate(_82f,_830);
turbo.defer(turbo.bind(this,this.installPages),100);
};
this.installPages=function(){
if(this.contentId){
this.content=turbo.$(this.contentId);
}
if(!this.content){
this.content=this.domNode.nextSibling;
}
if(this.content){
this.content.parentNode.removeChild(this.content);
this.pages.appendChild(this.content);
}else{
this.debug("installPages","BAD content - contentId: ("+this.contentId+")");
}
};
};
dojo.inherits(dojo.widget.HtmlTurboPagebar,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbopagebar");
dojo.provide("turbo.widgets.TurboPageButtons");
dojo.provide("turbo.widgets.HtmlTurboPageButtons");
dojo.require("turbo.widgets.TurboWidget");
dojo.widget.HtmlTurboPageButtons=function(){
this.widgetType="TurboPageButtons";
dojo.widget.HtmlTurboWidget.call(this);
this.templatePath=null;
this.pagesNode=null;
this.classTag="turbo-page-buttons";
this.className="";
this.buttonClass=this.classTag+"-button";
this.buttonHighlightClass=this.buttonClass+" "+this.classTag+"-highlight";
this.buttonSelectedClass=this.buttonClass+" "+this.classTag+"-selected";
this.buttonDisabledClass=this.buttonClass+" "+this.classTag+"-disabled";
this.buttonSeparatorClass=this.classTag+"-separator";
this.numPages=1;
this.numButtons=3;
this.onPageChange=function(){
};
this.buttonWidth=30;
this.buttonHeight=20;
this.buttonMargin=6;
this._x=0;
this._i=0;
this.lastValue=1;
this.buttonList="prevN, prev, innerFirst, pages, innerLast, next, nextN";
this.buttonLabels={first:"|&lt;",prevN:"&lt;&lt;",prev:"&lt;",next:"&gt;",nextN:"&gt;&gt;",last:"&gt;|",sep:"..."};
this.page=1;
this.installChildren=function(_831){
dojo.dom.moveChildren(this.getWidgetFragment(_831),this.domNode);
var frag=new dojo.xml.Parse().parseElement(this.domNode);
dojo.widget.getParser().createComponents(frag);
};
this.fillInTemplate=function(_833,_834){
this.bindArgEvents(_833);
dojo.html.disableSelection(this.domNode);
dojo.event.connect(this.domNode,"onclick",this,"pageClick");
dojo.event.connect(this.domNode,"onmouseover",this,"pageOver");
dojo.event.connect(this.domNode,"onmouseout",this,"pageOut");
dojo.event.topic.subscribe("turboresize",this,"turboresize");
this.setButtonList(this.buttonList);
this.createPagesNode();
this.initBuild();
};
this.styleChanged=function(){
this.setStyledClass(this.domNode);
};
this.turboresize=function(){
if(turbo.showing(this.domNode)){
this.build();
}
};
this.build=function(){
this.createPages();
};
this.setNumPages=function(_835){
if(_835!=undefined){
this.numPages=Number(_835);
}
this.build();
};
this.setButtonList=function(_836){
this.buttonList={};
var list=_836.replace(/ /g,"").split(",");
for(var i in list){
if(!Array.prototype[i]){
this.buttonList[list[i]]=true;
}
}
};
this.initBuild=function(_839,_83a){
window.setTimeout(turbo.bindArgs(this,this._initBuild,_839,_83a),10);
};
this._initBuild=function(_83b,_83c){
if(_83b!=undefined){
this.numPages=Number(_83b);
}
this.setPage(_83c!=undefined?_83c:1);
};
this.getNumPages=function(){
return this.numPages;
};
this.getPage=function(){
return this.page;
};
this.setPage=function(_83d){
_83d=Number(_83d);
if(!_83d||_83d<1||_83d>this.numPages){
return;
}
this.lastValue=this.page;
this.page=_83d;
this.build();
};
this.doResize=function(){
this.build();
};
this.pageClick=function(_83e){
var node=_83e.target;
if(node.disabled||!node.page){
return;
}
this.setPage(node.page);
this.onPageChange(this.page);
};
this.pageOver=function(_840){
var node=_840.target;
if(node==this.domNode||node.disabled||!node.page||node.page==this.page){
return;
}
node.className=this.buttonHighlightClass;
};
this.pageOut=function(_842){
var node=_842.target;
if(node==this.domNode||node.disabled||!node.page){
return;
}
node.className=this.getDefaultButtonClass(node);
};
this.getDefaultButtonClass=function(_844){
return (_844.disabled?this.buttonDisabledClass:(_844.page==this.page&&!isNaN(_844.innerHTML)?this.buttonSelectedClass:this.buttonClass));
};
this.getPageRange=function(_845){
var _845=(_845!=undefined?_845:this.numButtons);
if(this.numPages<=_845){
return {start:1,end:Math.max(1,this.numPages)};
}
var rp=Math.floor(Number(_845)/2);
var _847={};
_847.start=Math.max(1,Number(this.page)-rp);
if(this.numPages-_847.start<_845){
_847.start=this.numPages-(_845-1);
}
_847.end=_847.start+_845-1;
return _847;
};
this.inPageRange=function(_848,_849){
var _84a=this.getPageRange(_849);
return (_848>=_84a.start&&_848<=_84a.end);
};
this.numFixedButtons=function(_84b){
var w=0;
for(var i in _84b){
if(i!="pages"&&i!="innerFirst"&&i!="innerLast"){
w++;
}
}
return w;
};
this.calcFixedButtonsWidth=function(_84e){
var w=0;
for(var i in _84e){
w+=(i!="pages"&&i!="innerFirst"&&i!="innerLast")?this.buttonWidth+this.buttonMargin:0;
}
return w;
};
this.calcNumButtons=function(_851){
var s=turbo.getContentSize(this.domNode);
var w=this.calcFixedButtonsWidth(_851);
var bs=this.buttonWidth+this.buttonMargin;
var _855=function(){
return Math.floor((s.w-w)/bs);
};
var _856=_851["innerFirst"];
var _857=(_856==true)&&this.inPageRange(1,_855());
if(_856&&!_857){
w+=bs+bs;
}
if(_851["innerLast"]&&!this.inPageRange(this.numPages,_855())){
w+=bs+bs;
}
if(_857&&!this.inPageRange(1,_855())){
w+=bs+bs;
}
return _855(_851);
};
this.attachButton=function(_858){
this.pagesNode.appendChild(_858);
};
this.createPageButton=function(_859,_85a,_85b,_85c){
var node=(this._i<this.oldNumButtons)?this.pagesNode.childNodes[this._i]:document.createElement("div");
node.page=_859;
node.disabled=(_85b?true:false);
node.innerHTML=(_85a?_85a:_859);
node.className=this.getDefaultButtonClass(node);
var w=(_85c!=undefined?_85c:this.buttonWidth);
node.style.lineHeight=this.buttonHeight+"px";
turbo.setStyleBoundsPx(node,this._x,0,w,this.buttonHeight);
this._x+=w+this.buttonMargin;
this._i++;
if(this._i>=this.oldNumButtons){
this.attachButton(node);
}
};
this.createSep=function(){
var sep=(this._i<this.oldNumButtons)?this.pagesNode.childNodes[this._i]:document.createElement("div");
sep.page=undefined;
sep.disabled=undefined;
sep.className=this.buttonSeparatorClass;
sep.innerHTML=this.buttonLabels.sep;
turbo.setStyleBoundsPx(sep,this._x,0,this.buttonWidth,this.buttonHeight);
this._x+=this.buttonWidth+this.buttonMargin;
this._i++;
if(this._i>=this.oldNumButtons){
this.attachButton(sep);
}
};
this.create_first=function(){
this.createPageButton(1,this.buttonLabels.first,(this.page==1));
};
this.create_last=function(){
this.createPageButton(this.numPages,this.buttonLabels.last,(this.page==this.numPages));
};
this.create_pages=function(){
var _860=this.getPageRange();
for(var i=_860.start;i<=_860.end;i++){
this.createPageButton(i);
}
};
this.create_prev=function(){
var _862=Math.max(1,Number(this.page)-1);
this.createPageButton(_862,this.buttonLabels.prev,(this.page==1));
};
this.create_next=function(){
var _863=Math.min(this.numPages,Number(this.page)+1);
this.createPageButton(_863,this.buttonLabels.next,(this.page==this.numPages));
};
this.create_prevN=function(){
var _864=Math.max(1,Number(this.page)-this.numButtons);
this.createPageButton(_864,this.buttonLabels.prevN,(this.page==1));
};
this.create_nextN=function(){
var _865=Math.min(this.numPages,Number(this.page)+this.numButtons);
this.createPageButton(_865,this.buttonLabels.nextN,(this.page==this.numPages));
};
this.create_innerFirst=function(){
if(!this.inPageRange(1)){
this.createPageButton(1,"1");
this.createSep();
}
};
this.create_innerLast=function(){
if(!this.inPageRange(this.numPages)){
this.createSep();
this.createPageButton(this.numPages,this.numPages);
}
};
this.getButtonList=function(){
var x=0;
for(var i in this.buttonList){
x++;
}
var t=this.calcNumButtons(this.buttonList)+this.numFixedButtons(this.buttonList);
var _869={prev:true,pages:true,next:true};
return (t<x)?_869:this.buttonList;
};
this.createPages=function(){
this._x=0;
this._i=0;
this.oldNumButtons=this.pagesNode.childNodes.length;
var _86a=this.getButtonList();
this.numButtons=this.calcNumButtons(_86a);
for(var i in _86a){
this["create_"+i]();
}
turbo.setStyleSizePx(this.pagesNode,this._x,this.buttonHeight+this.buttonMargin);
this.removeExcessPages();
};
this.removeExcessPages=function(){
for(var i=this._i;i<this.oldNumButtons;i++){
this.pagesNode.removeChild(this.pagesNode.childNodes[this._i]);
}
};
this.createPagesNode=function(){
this.pagesNode=document.createElement("div");
this.pagesNode.style.position="relative";
this.pagesNode.style.top="0";
this.domNode.appendChild(this.pagesNode);
};
};
dojo.inherits(dojo.widget.HtmlTurboPageButtons,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:TurboPageButtons");
dojo.provide("turbo.data.stores");
turbo.data.fields=function(_86d){
var _86e=_86d;
this.defaultField=new _86e();
this.fields=[];
this.clearFields=function(){
this.fields=[];
};
this.mergeField=function(_86f,_870){
for(var i in _86f){
_870[i]=_86f[i];
}
return _870;
};
this.setDefaultField=function(_872){
if(typeof (_872)!="object"){
alert("tubo.data.fields.setDefaultField (stores.js): bad input field object. Are your field definitions included?");
}
turbo.swiss(_872,this.defaultField);
};
this.setField=function(_873,_874){
var f=this.fields[_873];
if(!f){
f=turbo.swiss(this.defaultField,new _86e());
}
for(var i=1;i<arguments.length;i++){
f=turbo.swiss(arguments[i],f);
}
this.fields[_873]=f;
};
this.getField=function(_877){
var _878=(_877>=0?this.fields[_877]:null);
if(!_878){
_878=this.defaultField;
}
_878.index=_877;
return _878;
};
this.setFields=function(_879){
if(_879){
for(i in _879){
var set=_879[i];
if(!dojo.lang.isArray(set)){
this.setField(i,set);
}else{
for(j in set){
this.setField(i,set[j]);
}
}
}
}
};
};
turbo.data.comparator=function(_87b){
return function(a,b){
return (a[_87b]>b[_87b]?1:(a[_87b]==b[_87b]?0:-1));
};
};
turbo.data.field=function(_87e){
this.name=_87e;
this.comparator=turbo.data.comparator;
this.getComparator=function(_87f,_880){
var _881=this.comparator(_87f);
if(!_880){
return _881;
}else{
return function(a,b){
return -_881(a,b);
};
}
};
};
turbo.data.table=function(){
turbo.data.fields.call(this,turbo.data.field);
this.metarows=new turbo.sparseArray();
this.sortIndex=-1;
this.sortField="";
this.sortDesc=false;
this.autoSort=false;
this.getFieldNameArray=function(){
var a=new Array(this.fields.length);
for(var i in this.fields){
a[i]=this.fields[i].name;
}
return a;
};
this.getFieldNameIndex=function(_886){
for(var i in this.fields){
if(this.fields[i].name==_886){
return i;
}
}
return false;
};
this.getColCount=function(){
return this.fields.length;
};
this.hasData=function(){
return (this.fields.length>0);
};
this._setSortField=function(_888,_889){
if(_889){
this.sortDesc=_889;
}else{
if(this.sortField==_888){
this.sortDesc=!this.sortDesc;
}else{
this.sortDesc=false;
}
}
this.sortField=_888;
};
this.setSortIndex=function(_88a,_88b){
if(_88a<0){
this.sortField="";
}else{
this._setSortField(this.getField(_88a).name,_88b);
}
this.sortIndex=_88a;
};
this.setSortField=function(_88c,_88d){
if(_88c=""){
this.setSortIndex(-1);
}else{
this._setSortField(_88c,_88d);
this.sortIndex=this.getFieldNameIndex(this.sortField);
}
};
this.deleteRows=function(_88e,inOk,_890){
inOk();
};
};
turbo.data.store=function(_891,_892){
turbo.data.table.call(this);
this.data=[];
this.hasData=function(){
return (this.data&&this.data.length>0);
};
this.setData=function(_893,_894){
this.data=(_893?_893:[]);
this.setFields(_894);
};
this.getColCount=function(){
var _895=this.fields.length;
var _896=(this.data&&this.data.length?this.data[0].length:0);
return Math.max(_895,_896);
};
this.getRowCount=function(){
return (this.data&&this.data.length?this.data.length:0);
};
this.getDatum=function(_897,_898){
if(djConfig.isDebug&&(_898<0||_898>=this.data.length)){
dojo.debug("turbo.data.arrayStore.getDatum: bad row",_898);
return null;
}
return this.data[_898][_897];
};
this.setDatum=function(_899,_89a,_89b){
this.data[_89a][_899]=_89b;
};
this.getRow=function(_89c){
return this.data[_89c];
};
this.copyRow=function(_89d){
return this.data[_89d].slice(0);
};
this.addRow=function(_89e,_89f){
var c=this.getColCount();
if(!_89f){
_89f=[];
}
for(var i=0;i<c;i++){
if(dojo.lang.isUndefined(_89f[i])){
_89f[i]=this.getField(i).defaultValue;
}
}
if(this.data.length>0){
this.data.splice(_89e,0,_89f);
}else{
this.data=[_89f];
}
this.metarows.insert(_89e,{cache:[],insert:true});
};
this.compareRow=function(_8a2,_8a3){
var c=this.getColCount();
if(!_8a3||_8a3.length!=c){
return false;
}
var row=this.getRow(_8a2);
for(var i=0;i<c;i++){
if(_8a3[i]!==row[i]){
return false;
}
}
return true;
};
this.removeRow=function(_8a7){
this.data.splice(_8a7,1);
this.metarows.remove(_8a7);
};
this.replaceRow=function(_8a8,_8a9){
this.data[_8a8]=_8a9;
};
this.swapRows=function(_8aa,_8ab){
turbo.array_swap(this.data,_8aa,_8ab);
turbo.array_swap(this.metarows,_8aa,_8ab);
};
this.sort=function(){
if(this.sortIndex>=0&&this.hasData()){
this.data.sort(this.getField(this.sortIndex).getComparator(this.sortIndex,this.sortDesc));
}
};
this.cacheRow=function(_8ac){
if(!this.metarows.get(_8ac)){
this.metarows.set(_8ac,{cache:this.copyRow(_8ac)});
}
};
this.getRowBacking=function(_8ad){
var m=this.metarows.get(_8ad);
if(m&&m.cache){
return m.cache;
}else{
return this.getRow(_8ad);
}
};
this.rowChanged=function(_8af){
return !this.compareRow(_8af,this.getRowBacking(_8af));
};
this.restoreRow=function(_8b0){
this.replaceRow(_8b0,this.getRowBacking(_8b0));
};
this.commitRow=function(_8b1,inOk,_8b3){
inOk();
};
this.commitRowChanges=function(_8b4,inOk,_8b6){
var self=this;
var _8b8=function(){
self.metarows.unset(_8b4);
inOk(_8b4);
};
var _8b9=function(){
_8b6(_8b4);
};
this.commitRow(_8b4,_8b8,_8b9);
};
this.setData(_891,_892);
};
turbo.data.paged=function(_8ba){
turbo.data.store.call(this,null,_8ba);
this.totalRows=0;
this.rowsPerPage=50;
this.pageCount=0;
this.pages=[];
this.page=-1;
this.invalidPage=-1;
this.requestPage=function(_8bb){
};
this.setTotalRows=function(_8bc){
var _8bd=this.pageCount;
var _8be=this.page;
this.totalRows=_8bc;
this.paginate();
return (_8bd!=this.pageCount||_8be!=this.page);
};
this.getPageLength=function(_8bf){
var page=(_8bf==this.page?this.data:this.pages[_8bf]);
return (page?page.length:this.rowsPerPage);
};
this.paginate=function(){
this.pageCount=Math.ceil(this.totalRows/this.rowsPerPage);
if(this.page>=this.pageCount){
this.page=this.pageCount-1;
}
};
this.fetchRowCount=function(){
return this.totalRows;
};
this.repaginate=function(){
var rows=this.fetchRowCount();
this.pages=[];
if(this.page>=0){
this.pages[this.page]=this.data;
}
return this.setTotalRows(rows);
};
this.getTopRow=function(_8c2){
var page=(_8c2===undefined?this.page:_8c2);
return page*this.rowsPerPage;
};
this.getBottomRow=function(_8c4){
var row=this.getTopRow(_8c4)+this.getPageLength(_8c4)-1;
return Math.min(row,this.totalRows-1);
};
this.fillPage=function(_8c6,_8c7){
this.pages[_8c6]=_8c7;
};
this.selectPage=function(_8c8){
this.page=_8c8;
if(!this.pages[_8c8]){
this.requestPage(_8c8);
}
this.metarows.clear();
if(this.pages[_8c8]){
this.invalidPage=-1;
this.data=this.pages[_8c8];
}else{
this.invalidPage=_8c8;
this.data=[];
}
};
this.invalidatePage=function(){
this.pages[this.page]=null;
};
this.pageIsValid=function(_8c9){
return (this.pages[_8c9]?true:false);
};
this.reloadPage=function(){
this.pages[this.page]=null;
this.selectPage(this.page);
};
this.reloadPages=function(){
this.pages=[];
this.selectPage(this.page);
};
this.fillNextPage=function(){
for(var i=0;i<this.pageCount;i++){
if(!this.pages[i]){
break;
}
}
if(i==this.pageCount){
return false;
}
dojo.debug(i);
this.requestPageAsync(i);
return true;
};
this.sort=function(){
this.reloadPages();
};
};
dojo.provide("turbo.data.sqltable");
dojo.require("turbo.data.stores");
dojo.require("turbo.lib.sql");
turbo.data.sqltable=function(_8cb,_8cc){
turbo.data.paged.call(this);
this.service=_8cb;
this.adapter=_8cc;
this.clear=function(){
this.setTotalRows(0);
this.pages=[];
this.page=-1;
this.setSortIndex(-1);
};
this.selectTable=function(_8cd,_8ce){
this.clear();
this.db=_8cd;
this.table=_8ce;
this.fetchSchema();
};
this._fetch_schema=function(_8cf){
if(!_8cf.error&&_8cf.result){
this.schema=_8cf.result;
this.adapter.adaptFields(this,this.schema.columns);
this.setTotalRows(this.schema.count);
}else{
turbo.debug(_8cf.error);
}
};
this.fetchSchema=function(){
this._fetch_schema(this.service.get_table_schema(this.table,this.db));
};
this.prepareSql=function(){
turbo.sql.describeTable(this.table,this.fields,this.schema.keys);
};
this.getOrderBy=function(){
return (!this.sortField?"":" ORDER BY "+turbo.sql.bq(this.sortField)+(this.sortDesc?" DESC":""));
};
this._fill_page=function(_8d0){
if(_8d0&&!_8d0.error&&_8d0.result){
if(_8d0.debug){
dojo.debug("RPC: "+_8d0.debug);
}
this.fillPage(this.page,_8d0.result);
}else{
turbo.debug(_8d0?_8d0.error:"sql._fill_page: no response");
}
};
this.requestPage=function(_8d1,_8d2){
if(!this.table||!this.db){
return;
}
this.prepareSql();
var sql=turbo.sql.build("select",{where:"",orderby:this.getOrderBy()});
var _8d4=this.getTopRow(_8d1);
var _8d5=this.rowsPerPage;
dojo.debug(sql,"("+_8d4+","+_8d5+")");
if(_8d2){
this.service.select_limit(sql,_8d5,_8d4,this.db,turbo.bind(this,this._fill_page));
}else{
this._fill_page(this.service.select_limit(sql,_8d5,_8d4,this.db));
}
};
this.requestPageAsync=function(_8d6){
this.requestPage(_8d6,true);
};
this.fetchRowCount=function(){
var _8d7=this.service.get_row_count(this.table,this.db);
if(!_8d7.error){
return _8d7.result;
}else{
return 0;
}
};
this.repaginate=function(){
var rows=this.fetchRowCount();
this.pages=[];
if(this.page>=0){
this.pages[this.page]=this.data;
}
return this.setTotalRows(rows);
};
this.responseError=function(_8d9){
return (_8d9.error||!_8d9.result.length||!_8d9.result[0].length);
};
this.commitError=function(_8da,_8db,_8dc){
dojo.debug("turbo.data.sqltable: commit FAILED for row "+_8da);
this.metarows.get(_8da).error=(_8dc.error?_8dc.error:"reflection sql failed");
_8db(_8da,_8dc);
};
this.commitOk=function(_8dd,inOk,_8df){
dojo.debug("turbo.data.sqltable: commit successful for row "+_8dd);
turbo.debug("turbo.data.sqltable: [",_8df.result[0],"]");
this.replaceRow(_8dd,_8df.result[0]);
inOk(_8dd,_8df);
};
this._commitRow=function(_8e0,inOk,_8e2,_8e3){
if(this.responseError(_8e3)){
this.commitError(_8e0,_8e2,_8e3);
}else{
this.commitOk(_8e0,inOk,_8e3);
}
};
this.commitRow=function(_8e4,_8e5,inOk,_8e7,_8e8,_8e9,_8ea){
dojo.debug(_8e8," : ",_8e9);
var cb=turbo.bindArgs(this,_8ea,_8e4,inOk,_8e7);
if(_8e5){
cb(this.service.update_reflect(_8e8,_8e9,this.db));
}else{
this.service.update_reflect(_8e8,_8e9,this.db,cb);
}
};
this.updateRow=function(_8ec,_8ed,_8ee,inOk,_8f0){
this.prepareSql();
var data=this.getRow(_8ec);
var _8f2=turbo.sql.buildSelectRowQuery(turbo.sql.buildWhereClause(data));
var _8f3=turbo.sql.buildUpdateQuery(turbo.sql.buildSetClause(data),turbo.sql.buildWhereClause(_8ed));
this.commitRow(_8ec,_8ee,inOk,_8f0,_8f3,_8f2,this._commitRow);
};
this.insertOk=function(_8f4,inOk,_8f6){
this.repaginate();
this.invalidatePage();
this.commitOk(_8f4,inOk,_8f6);
};
this._insertRow=function(_8f7,inOk,_8f9,_8fa){
if(this.responseError(_8fa)){
this.commitError(_8f7,_8f9,_8fa);
}else{
this.insertOk(_8f7,inOk,_8fa);
}
};
this.insertRow=function(_8fb,_8fc,inOk,_8fe){
this.prepareSql();
var data=this.getRow(_8fb);
var _900=turbo.sql.buildWhereClause(data);
var _901=turbo.sql.buildSelectRowQuery(_900);
var _902=turbo.sql.buildInsertQuery(data);
this.commitRow(_8fb,_8fc,inOk,_8fe,_902,_901,this._insertRow);
};
this.commitRowChanges=function(_903,inOk,_905){
var e=this.metarows.get(_903);
if(e.insert){
this.insertRow(_903,e.sync,inOk,_905);
}else{
this.updateRow(_903,e.cache,e.sync,inOk,_905);
}
};
this.deleteRows=function(_907,inOk,_909){
var self=this;
var _90b=[];
this.prepareSql();
turbo.sparse.map(_907,function(_90c){
var data=self.getRowBacking(_90c);
var _90e=turbo.sql.buildWhereClause(data);
_90b.push(turbo.sql.buildDeleteQuery(_90e));
});
if(!_90b.length){
return;
}
turbo.debug("turbo.data.sqltable.deleteRows:",_90b);
var _90f=this.service.delete_queue(_90b,this.db);
if(_90f.error){
turbo.debug("turbo.data.sqltable.deleteRows: ",_90f.error);
this.reloadPage();
_909(_90f.error);
}else{
inOk(_907,_90f.result);
}
};
};
dojo.provide("turbo.data.sqlquery");
dojo.require("turbo.data.stores");
turbo.data.sqlquery=function(_910,_911){
turbo.data.paged.call(this);
this._sql="";
this.message="";
this.hasResultData=false;
this.service=_910;
this.adapter=_911;
this.db="";
this.clear=function(){
this.hasResultData=false;
this.setTotalRows(0);
this.pages=[];
this.page=-1;
this.setSortIndex(-1);
};
this.setSql=function(_912){
this._sql=_912;
return true;
};
this.getSql=function(_913){
return this._sql;
};
this.canRequest=function(){
return (this.hasSql());
};
this.hasSql=function(){
return (this.getSql()!="");
};
this.setSchema=function(_914){
this.schema=_914;
this.adapter.adaptFields(this,this.schema.columns);
this.setTotalRows(this.schema.count);
};
this.processResult=function(_915,_916){
this.hasResultData=false;
this.message="";
if(_915&&!_915.error&&_915.result){
if(_915.debug){
dojo.debug("RPC: "+_915.debug);
}
var _917=_915.result;
if(_917.data.length){
this.hasResultData=true;
this.setSchema(_917.schema);
this.fillPage(this.page,_917.data);
if(_917.sql){
this.setSql(_917.sql);
}
}else{
this.message="SQL processed.";
if(_915.result.affectedRows){
this.message+=_915.result.affectedRows+" affected rows.";
}
}
}else{
var _918=(_915?_915.error:"sql.processResult: no response");
turbo.debug(_918);
this.message=_918;
}
if(_916){
_916();
}
};
this.requestPage=function(_919,_91a,_91b){
if(!this.canRequest()){
return;
}
var sql=this.getSql();
var _91d=this.getTopRow(_919);
var _91e=this.rowsPerPage;
dojo.debug(sql.substring(0,Math.min(sql.length,250)),"("+_91d+","+_91e+")");
var self=this;
var _920=function(_921){
self.processResult(_921,_91b);
};
if(_91a){
this.service.execute_untrusted_sql_reflect(sql,_91d,_91e,this.db,_920);
}else{
this.processResult(this.service.execute_untrusted_sql_reflect(sql,_91d,_91e,this.db),_91b);
}
};
this.requestPageAsync=function(_922){
this.requestPage(_922,true);
};
};
dojo.provide("turbo.data.filestores");
dojo.require("turbo.data.stores");
turbo.data.fileStore=function(_923,_924){
if(_924==undefined){
_924=turbo.data.field;
}
turbo.data.fields.call(this,_924);
this.service=_923;
var _925=[];
this.isDirty=function(){
for(var i in _925){
if(_925[i]){
return true;
}
}
};
this.fieldExists=function(_927){
return (this.fieldIndex(_927)!=-1);
};
this.fieldIndex=function(_928){
if(!_928.name){
return -1;
}
for(var i in this.fields){
if(_928.name.toLowerCase()==this.getField(i).name.toLowerCase()){
return i;
}
}
return -1;
};
this.getDirtyFields=function(){
var d=[];
for(var i in _925){
if(_925[i]){
d.push=this.getField(i);
}
}
return d;
};
this.saveFields=function(_92c){
var _92d=(_92c==undefined?this.getDirtyFields():this.fields);
this.service.save_items(_92d,true);
};
this.getFieldCount=function(){
return this.fields.length;
};
this.addField=function(_92e){
var i=this.getFieldCount();
this.setField(i,_92e);
this.service.save_items([this.getField(i)],false);
this.setClean(i);
};
this.editField=function(_930,_931){
this.setField(_930,_931);
this.service.save_items([this.getField(_930)],false);
this.setClean(_930);
};
this.deleteField=function(_932){
if(_932<0||_932>this.getFieldCount()-1){
return;
}
this.service.delete_items([this.getField(_932)]);
this.fields.splice(_932,1);
_925.splice(_932,1);
};
this.loadFields=function(){
var _933=this.service.load_file().result;
this.clearFields();
this.setFields(_933);
this.setAllClean();
};
this.setClean=function(_934){
_925[_934]=false;
};
this.setAllClean=function(){
_925=[];
};
this.setAllDirty=function(){
this.setAllClean();
if(!this.getFieldCount()||!this.fields){
return;
}
for(var i in this.fields){
_925[i]=true;
}
};
this.setDirty=function(_936){
_925[_936]=true;
};
dojo.event.kwConnect({type:"after",srcObj:this,srcFunc:"setFields",targetObj:this,targetFunc:"setAllDirty",once:true});
dojo.event.kwConnect({type:"after",srcObj:this,srcFunc:"setField",targetObj:this,targetFunc:"setDirty",once:true});
this.setAllDirty();
};
dojo.provide("turbo.grid.columns");
turbo.grid.format={};
turbo.grid.format.noformat=function(_937,_938){
return _937;
};
turbo.grid.edit={};
turbo.grid.edit.noedit={edit:function(_939,_93a){
return false;
},getValue:function(){
return null;
}};
turbo.grid.column=function(_93b){
this.name=(_93b?_93b:"");
this.width=96;
this.readonly=false;
this.editor=null;
this.formatter=turbo.grid.format.text;
this.format=function(_93c,_93d){
if(!this.formatter){
dojo.debug("turbo.grid.column: illegal formatter for column ["+this.name+"]");
this.formatter=turbo.grid.format.text;
}
var _93e=(this.readonly||_93d||!this.editor);
return this.formatter.call(this,_93c,_93e);
};
this.getEditor=function(){
if(!this.editor){
return turbo.grid.edit.noedit;
}else{
this.editor.column=this;
return this.editor;
}
};
};
turbo.grid.format.text=function(_93f,_940){
var s="width:"+this.width+"px;";
if(_93f==null){
s+=" color: #CCBBB3;";
_93f="~";
}else{
if(typeof (_93f)=="string"&&_93f.length>255){
_93f="(text: "+_93f.length+" chars)";
}else{
_93f=turbo.escapeText(_93f);
}
}
return "<div style=\""+s+"\">"+_93f+"</div>";
};
turbo.grid.format.line=turbo.grid.format.text;
turbo.grid.edit.line=new function(){
this.createInput=function(_942,_943){
var i=document.createElement("input");
i.value=(_943===undefined?"":String(_943));
i.style.width=_942.clientWidth-10+"px";
_942.innerHTML="";
_942.appendChild(i);
if(i.clientHeight<_942.clientHeight-4){
i.style.height=_942.clientHeight-4+"px";
}
turbo.defer(function(){
i.select();
i.focus();
},10);
return i;
};
this.edit=function(_945,_946){
this.input=this.createInput(_945,_946);
};
this.getValue=function(){
return (this.input.value=="null"?null:(this.input.value=="undefined"?undefined:this.input.value));
};
};
turbo.grid.edit.multiLine=new function(){
this.createInput=function(_947,_948){
var i=document.createElement("textarea");
i.value=String(_948);
i.rows=2;
i.style.width=_947.clientWidth-8+"px";
_947.innerHTML="";
_947.appendChild(i);
if(i.clientHeight<_947.clientHeight-4){
i.style.height=_947.clientHeight-4+"px";
}
i.select();
i.focus();
return i;
};
this.edit=function(_94a,_94b){
this.input=this.createInput(_94a,_94b);
};
this.getValue=function(){
return (this.input.value=="null"?null:this.input.value);
};
};
turbo.grid.format.integer=function(_94c){
var s="";
s+="width:"+(this.width-4)+"px;";
s+=" text-align: right; padding-right: 4px;";
if(_94c==null){
s+=" color: #CCBBB3;";
_94c="~";
}
return "<div style=\""+s+"\">"+_94c+"</div>";
};
turbo.grid.format.bool=function(_94e,_94f){
_94e=(_94e?parseInt(_94e)!=0:false);
return "<div style=\"width:"+this.width+"px; text-align: center\">"+"<input type=\"checkbox\"\""+(_94f?" disabled=\"disabled\"":"")+(_94e?" checked=\"checked\"":"")+"/>"+"</div>";
};
turbo.grid.edit.bool=new function(){
this.edit=function(_950,_951){
while(_950.tagName!="INPUT"){
_950=_950.childNodes[0];
}
this.input=_950;
this.input.focus();
};
this.getValue=function(){
return (this.input.checked?1:0);
};
};
turbo.grid.format.money=function(_952,_953){
var f=parseFloat(_952);
var s=(f<0?-1:1);
f=Math.abs(f);
var i=Math.floor(f).toString();
var l=i.length+(s<0?1:0);
f=(s<0?"&minus;":"")+f.toFixed(2);
l=(this.digits?this.digits:5)-l;
var k="$"+turbo.stringOf(l,"&#160;")+f;
return "<div style=\"width:"+this.width+"px;\" class=\"turbo-grid-money"+(s<0?" turbo-grid-money-neg":"")+"\">"+k+"</div>";
};
turbo.grid.decimalIsOk=function(_959,_95a){
return (_95a=="."&&!/[\.]/.test(_959));
};
turbo.grid.isIntChar=function(_95b){
return (_95b.search(/[-+\0\t\n\r\d]/)!=-1);
};
turbo.grid.edit.integer=new function(){
this.limitToInteger=function(_95c,_95d,_95e,_95f){
turbo.debug("turbo.grid.edit.integer.limitToInteger");
if(!turbo.grid.isIntChar(String.fromCharCode(_95d.charCode))){
_95d.preventDefault();
}
};
this.createInput=turbo.grid.edit.line.createInput;
this.edit=function(_960,_961){
var self=this;
var i=this.createInput(_960,_961);
dojo.event.connect(i,"onkeypress",function(_964){
self.limitToInteger(i,_964,self.column.maxValue,self.column.minValue);
});
this.input=i;
};
this.getValue=turbo.grid.edit.line.getValue;
};
turbo.grid.edit.decimal=new function(){
this.limitToDecimal=function(_965,_966,_967,_968,_969){
var s=String.fromCharCode(_966.charCode);
if(!turbo.grid.isIntChar(s)&&!turbo.grid.decimalIsOk(_965.value,s)){
_966.preventDefault();
}
};
this.createInput=turbo.grid.edit.line.createInput;
this.edit=function(_96b,_96c){
var self=this;
var i=this.createInput(_96b,_96c);
dojo.event.connect(i,"onkeypress",function(_96f){
self.limitToDecimal(i,_96f,self.column.decimals,self.column.maxValue,self.column.minValue);
});
this.input=i;
};
this.getValue=turbo.grid.edit.line.getValue;
};
turbo.grid.columns={};
turbo.grid.columns.basic={width:128,formatter:turbo.grid.format.line,editor:turbo.grid.edit.line};
turbo.grid.columns.bool={width:48,formatter:turbo.grid.format.bool,editor:turbo.grid.edit.bool};
turbo.grid.columns.money={width:96,formatter:turbo.grid.format.money,editor:turbo.grid.edit.decimal};
turbo.grid.format.enumerated=function(_970,_971){
var opts="";
if(this.options){
for(var i=0;i<this.options.length;i++){
opts+="<option"+(this.options[i]==_970?" selected":"")+">"+this.options[i]+"</option>";
}
}else{
opts="<option>"+_970+"</option>";
}
return "<div>"+"<select"+(_971?" disabled=\"disabled\"":"")+">"+opts+"</select>"+"</div>";
};
turbo.grid.edit.enumerated=new function(){
this.edit=function(_974,_975){
while(_974.tagName!="SELECT"){
_974=_974.childNodes[0];
}
this.input=_974;
this.input.focus();
};
this.getValue=function(){
var _976;
for(var i in this.input.childNodes){
if(this.input.childNodes[i].selected){
_976=this.input.childNodes[i].innerHTML;
break;
}
}
return _976;
};
};
turbo.grid.columns.enumerated={width:96,formatter:turbo.grid.format.enumerated,editor:turbo.grid.edit.enumerated};
turbo.grid.format.autoInc=function(_978){
var s="";
s+="width:"+(this.width-4)+"px;";
s+=" text-align: right;";
if(_978===undefined){
s+=" color: #CCBBB3;";
_978="auto";
}
return "<div style=\""+s+"\">"+_978+"</div>";
};
turbo.grid.columns.autoInc={readonly:true,formatter:turbo.grid.format.autoInc,editor:turbo.grid.edit.integer};
dojo.provide("turbo.grid.controllers");
dojo.require("turbo.grid.columns");
turbo.grid.controller=function(_97a,_97b,_97c){
if(_97a){
this.grid=(dojo.lang.isString(_97a)?dojo.widget.manager.getWidgetById(_97a):_97a);
this.grid.controller=this;
this.columns=new turbo.data.fields(turbo.grid.column);
this.readonly=false;
this.setColumns(_97c);
this.setModel(_97b);
}
};
dojo.lang.extend(turbo.grid.controller,{canSort:function(_97d){
},showMessage:function(_97e){
},rowsChanged:function(){
}});
dojo.lang.extend(turbo.grid.controller,{clear:function(){
this.editingCell=null;
this.editingRow=-1;
},build:function(){
this.clear();
this.grid.setSize(this.getColCount(),this.model.getRowCount());
this.grid.build();
},setReadonly:function(_97f){
if(_97f!=this.readonly){
this.readonly=_97f;
this.build();
}
},setModel:function(_980){
this.model=_980;
if(this.model){
this.build();
}
},getColCount:function(){
var _981=this.columns.fields.length;
return (_981?_981:this.model.getColCount());
},getColumn:function(_982){
return this.columns.getField(_982);
},setColumn:function(_983,_984){
this.columns.setField(_983,_984);
},setColumns:function(_985){
this.columns.setFields(_985);
},getColumnWidth:function(_986,_987){
return this.getColumn(_987).width;
},getDatum:function(_988,_989){
return this.model.getDatum(_988,_989);
},getCell:function(_98a,_98b,_98c){
return this.getColumn(_98b).format(this.getDatum(_98b,_98c),this.readonly,_98c);
},getColumnTitle:function(_98d,_98e){
var t=this.getColumn(_98e).name;
return (t?t:this.model.getField(_98e).name);
},getSortInfo:function(_990){
return {column:this.model.sortIndex,desc:this.model.sortDesc};
},onHeaderClick:function(_991,_992){
if(this.canSort(_992)===false){
return;
}
this.applyEdit();
this.model.setSortIndex(_992);
this.model.sort();
this.grid.clearMarkers();
this.grid.refresh();
},getRowClass:function(_993){
var _994=this.grid.rowMarkerClass[_993];
if(this.grid.selected[_993]&&(!_994||this.grid.selectCount>1)){
_994=(this.editingRow==_993?"editing":"selected");
}
return this.grid.classTag+"-row-"+(_993&1)+(_994?" "+this.grid.classTag+"-"+_994:"");
}});
dojo.lang.extend(turbo.grid.controller,{setRow:function(_995,_996){
this.model.replaceRow(_995,_996);
this.grid.updateRow(_995);
},addRow:function(_997,_998){
this.applyEdit();
this.model.addRow(_997,_998);
this.grid.addRow(_997);
this.editingRow=_997;
this.grid.setRowSelected(_997);
this.grid.onSelectionChange();
this.rowsChanged();
},newRow:function(){
var row=this.grid.getFirstSelectedRow()+1;
this.addRow(Math.max(row,0));
},removeRow:function(_99a){
this.model.removeRow(_99a);
this.grid.removeRow(_99a);
},removeSelectedRows:function(){
var rows=this.grid.getSelectedRows();
for(var i=0;i<rows.length;i++){
this.removeRow(rows[i]-i);
}
},swapRows:function(inI,inJ){
this.finishGridEdit();
this.model.swapRows(inI,inJ);
this.grid.swapRows(inI,inJ);
},canMoveRow:function(_99f){
var src=this.grid.selectedRow;
var dst=src+_99f;
return (src>=0&&dst>=0&&dst<this.grid.rows);
},moveRow:function(_9a2){
if(!this.canMoveRow(_9a2)){
return;
}
this.finishGridEdit();
var src=this.grid.selectedRow;
var dst=src+_9a2;
this.swapRows(src,src+_9a2);
this.grid.setRowSelected(src,false);
this.grid.setRowSelected(dst,true);
this.grid.onSelectionChange();
},moveRowUp:function(){
this.moveRow(-1);
},moveRowDown:function(){
this.moveRow(1);
}});
dojo.lang.extend(turbo.grid.controller,{onBeginEdit:function(_9a5){
},onEditRowStart:function(_9a6){
},onEditRowDone:function(){
},editingCell:null,editingRow:-1,editCellDone:function(){
this.editingCell=null;
},cancelEditCell:function(){
if(this.editingCell){
this.grid.refreshCell(this.editingCell);
this.editCellDone();
}
},updateEditCell:function(){
if(this.editingCell&&this.editor){
this.updateCell(this.editingCell,this.editor);
this.grid.refreshCell(this.editingCell);
}
},finishEditCell:function(){
this.updateEditCell();
if(this.editingCell){
this.grid.updateRowClass(this.editingCell.row);
}
this.editCellDone();
},editRowStart:function(_9a7){
if(this.editingRow!=_9a7){
this.model.cacheRow(_9a7);
this.editingRow=_9a7;
this.onEditRowStart(_9a7);
}
},editRowDone:function(){
this.editingRow=-1;
this.onEditRowDone();
},cancelEditRow:function(){
if(this.editingRow>=0){
var row=this.editingRow;
this.editingRow=-1;
this.grid.updateRowClass(row);
this.editRowDone();
}
},finishEditRow:function(){
if(this.editingRow>=0){
var row=this.editingRow;
this.updateRow(row);
this.editingRow=-1;
this.grid.updateRowClass(row);
this.editRowDone();
}
},cancelGridEdit:function(){
this.cancelEditCell();
this.cancelEditRow();
},finishGridEdit:function(){
this.finishEditCell();
this.finishEditRow();
},editCell:function(_9aa,_9ab,_9ac){
if(!_9aa){
return;
}
var cell=this.grid.getCellPos(_9aa);
if(!this.grid.goodCell(cell)||this.grid.sameCell(this.editingCell,cell)){
return;
}
this.finishEditCell();
if(_9ab||_9ac){
this.finishEditRow();
this.grid.clickSelect(cell.row,_9ab,_9ac);
dojo.html.clearSelection();
}else{
this.grid.unselectRows(cell.row);
if(_9aa.parentNode==null){
return;
}
cell=this.grid.getCellPos(_9aa);
this.editor=this.getEditor(cell);
if(this.editor){
this.editingCell=cell;
this.editRowStart(cell.row);
window.setTimeout(turbo.bind(this.grid,this.grid.updateRowSizes),10);
this.onBeginEdit(cell);
}
this.grid.setRowSelected(cell.row,true);
this.grid.onSelectionChange();
}
},onDataClick:function(_9ae,_9af,_9b0){
this.editCell(_9af,_9b0.ctrlKey,_9b0.shiftKey);
}});
dojo.lang.extend(turbo.grid.controller,{getEditor:function(_9b1){
var _9b2=(this.readonly?null:this.columns.getField(_9b1.col).getEditor());
if(_9b2&&_9b2.edit(this.grid.getDomCell(_9b1),this.getDatum(_9b1.col,_9b1.row))===false){
_9b2=null;
}
return _9b2;
},updateCell:function(_9b3,_9b4){
this.model.setDatum(_9b3.col,_9b3.row,_9b4.getValue());
}});
dojo.lang.extend(turbo.grid.controller,{onUnselectRow:function(_9b5,_9b6){
if(_9b6==this.editingRow){
this.applyEdit();
}
},updateRow:function(_9b7){
if(this.model.rowChanged(_9b7)){
this.commitRow(_9b7);
}else{
this.grid.setMarker(_9b7,"");
this.model.metarows.unset(_9b7);
}
},applyEdit:function(){
if(this.editingRow>=0){
this.finishGridEdit();
}
},applyEditSync:function(){
if(this.editingRow>=0){
this.model.metarows.get(this.editingRow).sync=true;
this.finishGridEdit();
}
},cancelEdit:function(){
if(this.editingRow>=0){
var row=this.editingRow;
this.cancelGridEdit();
if(this.model.metarows.get(row).insert){
this.removeRow(row);
}else{
this.model.restoreRow(row);
this.model.metarows.unset(row);
this.grid.updateRow(row);
this.grid.setMarker(row,"");
}
}
}});
dojo.lang.extend(turbo.grid.controller,{_commitError:function(_9b9){
this.grid.setMarker(_9b9,"error");
var e=this.model.metarows.get(_9b9);
dojo.debug("turbo.grid.controller._commitError: \"",e.error);
this.showMessage("A server commit error occured: ["+e.error+"]");
},_commitOk:function(_9bb){
this.model.metarows.unset(_9bb);
this.grid.setMarker(_9bb,"");
this.grid.updateRow(_9bb);
this.rowsChanged();
},commitRow:function(_9bc){
this.grid.setMarker(_9bc,"inflight");
this.model.commitRowChanges(_9bc,turbo.bindArgs(this,this._commitOk,_9bc),turbo.bindArgs(this,this._commitError,_9bc));
}});
dojo.lang.extend(turbo.grid.controller,{_deleteError:function(){
},_deleteOk:function(_9bd,_9be){
var c=_9be.deleted;
turbo.debug("turbo.grid.controller._deleteOk: deleted "+c+" row(s)");
var self=this;
turbo.sparse.filter(_9bd,function(_9c1){
if(--c<0){
return false;
}
self.removeRow(_9c1);
return true;
});
if(this.model.repaginate()){
dojo.debug("repaginate signalled page change, reloading");
this.reloadPage();
}else{
this.model.invalidatePage();
}
this.rowsChanged();
},_deleteRows:function(){
var rows=this.grid.getSelectedRows();
var _9c3=turbo.sparse.count(rows);
if(!confirm("Ok to delete "+(_9c3!=1?_9c3+" rows":"one row")+" from table \""+this.model.table+"\""+"?")){
return;
}
this.cancelGridEdit();
var self=this;
turbo.sparse.filter(rows,function(_9c5){
var e=self.model.metarows.get(_9c5);
if(!e||!e.insert){
return false;
}else{
this.removeRow(_9c5);
return true;
}
});
dojo.debug("turbo.grid.controller._deleteRows: deleting "+turbo.sparse.count(rows)+" row(s)");
this.model.deleteRows(rows,turbo.bind(this,this._deleteOk),turbo.bind(this,this._deleteError));
},deleteRows:function(){
this.grid.setBusyStatus();
try{
this._deleteRows();
}
finally{
this.grid.setReadyStatus();
}
}});
dojo.lang.extend(turbo.grid.controller,{prevEdit:function(){
if(this.editingCell){
var cell={col:this.editingCell.col,row:this.editingCell.row};
if(--cell.col>=0){
this.editCell(this.grid.getDomCell(cell));
}else{
if(--cell.row>=0){
this.editCell(this.grid.getDomCell({col:this.grid.cols-1,row:cell.row}));
}
}
}
},nextEdit:function(){
if(this.editingCell){
var cell={col:this.editingCell.col,row:this.editingCell.row};
if(++cell.col<this.grid.cols){
this.editCell(this.grid.getDomCell(cell));
}else{
if(++cell.row<this.grid.rows){
this.editCell(this.grid.getDomCell({col:0,row:cell.row}));
}
}
}
},onKeyDown:function(_9c9,_9ca){
if(_9ca.altKey||_9ca.ctrlKey||_9ca.metaKey){
return;
}
switch(_9ca.keyCode){
case _9ca.KEY_ESCAPE:
this.cancelEditCell();
break;
case _9ca.KEY_ENTER:
if(!_9ca.shiftKey){
this.finishEditCell();
}
break;
case _9ca.KEY_TAB:
if(this.editingCell){
dojo.event.browser.stopEvent(_9ca);
if(_9ca.shiftKey){
this.prevEdit();
}else{
this.nextEdit();
}
}
break;
}
}});
turbo.grid.controllers.paged=function(_9cb,_9cc,_9cd){
turbo.grid.controller.call(this,_9cb,_9cc,_9cd);
};
dojo.inherits(turbo.grid.controllers.paged,turbo.grid.controller);
dojo.lang.extend(turbo.grid.controllers.paged,{setModel:function(_9ce){
this.model=_9ce;
if(this.model){
this.model.repaginate();
this.model.selectPage(0);
this.build();
}
},_selectPage:function(_9cf,_9d0){
this.model.selectPage(_9cf);
this.build();
this.grid.setReadyStatus();
if(_9d0){
_9d0();
}
},selectPage:function(_9d1,_9d2){
this.grid.setBusyStatus();
this.grid.teardownRows();
window.setTimeout(turbo.bindArgs(this,this._selectPage,_9d1,_9d2),20);
},reloadPage:function(){
this.model.reloadPage();
this.build();
},formatFixedCol:function(_9d3,_9d4){
return Number(_9d4)+1+this.model.getTopRow();
}});
dojo.provide("turbo.grid.adodb");
turbo.adodb={};
turbo.adodb.columnTypes=["tinyint","smallint","mediumint","int","integer","bigint","real","double","float","decimal","numeric","bit","bool","char","varchar","date","time","year","timestamp","datetime","tinyblob","blob","mediumblob","longblob","tinytext","text","mediumtext","longtext","enum","set"];
turbo.adodb.categories={boolTypes:["tinyint","bool","int"],integerTypes:["tinyint","smallint","mediumint","int","integer","bigint"],decimalTypes:["real","double","float","decimal","numeric"],oneLineStringTypes:["char","varchar","tinytext","string"],multiLineStringTypes:["text","mediumtext","longtext"],enumTypes:["enum"],dateTypes:["date","time","year","timestamp","datetime"],blobTypes:["bit","tinyblob","blob","mediumblob","longblob"],setTypes:["set"]};
turbo.adodb.inCategory=function(_9d5,_9d6){
var cat=turbo.adodb.categories[_9d6];
for(var i in cat){
if(_9d5==cat[i]){
return true;
}
}
return false;
};
turbo.adodb.adapter=new function(){
this.colMinWidth=30;
this.colDefaultWidth=80;
this.colMaxWidth=250;
this.charWidth=7;
this.dateTimeWidth=120;
this.getDefaultValue=function(_9d9){
return (_9d9.has_default?_9d9.default_value:(_9d9.not_null?this.manufactureDefault(_9d9):null));
};
this.manufactureDefault=function(_9da){
var _9db="";
if(_9da.auto_increment){
_9db=undefined;
}else{
if(turbo.adodb.inCategory(_9da.type,"integerTypes")||turbo.adodb.inCategory(_9da.type,"boolTypes")){
_9db="0";
}else{
if(turbo.adodb.inCategory(_9da.type,"decimalTypes")){
_9db=(_9da.scale>0?"0.":"0")+turbo.stringOf(_9da.scale,"0");
}
}
}
return _9db;
};
this.adaptField=function(_9dc){
return {pk:(_9dc.primary_key!==null?_9dc.primary_key:0),name:_9dc.name,type:_9dc.type,length:_9dc.max_length,decimals:_9dc.scale,defaultValue:this.getDefaultValue(_9dc),allownull:_9dc.not_null,autoinc:_9dc.auto_increment,unsigned:_9dc.unsigned,zerofill:_9dc.zerofill,binary:_9dc.binary,values:(_9dc.enums?_9dc.enums:null),nee:_9dc.name};
};
this.adaptFields=function(_9dd,_9de){
_9dd.clearFields();
var idx=0;
for(var i in _9de){
_9dd.setField(idx++,this.adaptField(_9de[i]));
}
};
this.getBaseColumn=function(_9e1){
var t=_9e1.type;
if(turbo.adodb.inCategory(t,"boolTypes")&&_9e1.max_length==1){
return turbo.grid.columns.bool;
}else{
if(turbo.adodb.inCategory(t,"integerTypes")){
if(_9e1.auto_increment){
return turbo.grid.columns.autoInc;
}else{
return {formatter:turbo.grid.format.integer,editor:turbo.grid.edit.integer};
}
}else{
if(turbo.adodb.inCategory(t,"decimalTypes")){
return {formatter:turbo.grid.format.integer,editor:turbo.grid.edit.decimal};
}else{
if(turbo.adodb.inCategory(t,"oneLineStringTypes")){
return {editor:turbo.grid.edit.line};
}else{
if(turbo.adodb.inCategory(t,"multiLineStringTypes")){
return {editor:turbo.grid.edit.multiLine};
}else{
if(turbo.adodb.inCategory(t,"enumTypes")){
return {formatter:turbo.grid.format.enumerated,editor:turbo.grid.edit.enumerated,options:_9e1.enums};
}else{
return {};
}
}
}
}
}
}
};
this.getColumnWidth=function(_9e3){
var w=this.colDefaultWidth;
if(_9e3.type=="datetime"){
w=this.dateTimeWidth;
}else{
if(turbo.adodb.inCategory(_9e3.type,"multiLineStringTypes")){
w=this.colMaxWidth;
}else{
if(_9e3.max_length>0){
var _9e5=(_9e3.max_length<96?6:(_9e3.max_length<128?3:1));
w=Math.max(this.colMinWidth,Math.min(this.colMaxWidth,Math.round(_9e3.max_length*_9e5)));
}
}
}
if(turbo.adodb.inCategory(_9e3.type,"enumTypes")){
w+=32;
}
w+=18;
return Math.max(w,_9e3.name.length*this.charWidth);
};
this.adaptColumns=function(_9e6,_9e7){
_9e6.clearFields();
_9e6.setDefaultField(turbo.grid.columns.basic);
var idx=0;
for(var i in _9e7){
var ci=_9e7[i];
_9e6.setField(idx++,this.getBaseColumn(ci),{name:ci.name,width:this.getColumnWidth(ci),maxLength:ci.max_length,decimals:ci.scale});
}
};
};
turbo.adodb.schemaAdapter=new function(){
this.adaptData=function(_9eb){
var data=[];
for(var i in _9eb){
var info=_9eb[i];
data.push([(info.primary_key!==null?info.primary_key:0),info.name,info.type,info.max_length,info.scale,(info.has_default?info.default_value:null),info.not_null,info.auto_increment,info.unsigned,info.zerofill,info.binary,(info.enums?info.enums:null),info.name]);
}
return data;
};
this.defaultRow=[null,"","mediumint",null,null,null,0,0,0,0,0,null];
this.adaptFields=function(_9ef){
_9ef.setField(2,{defaultValue:"int"});
};
this.adaptColumns=function(_9f0){
_9f0.clearFields();
_9f0.setDefaultField(turbo.grid.columns.basic);
_9f0.setField(0,{name:"Primary Key"},turbo.grid.columns.bool);
_9f0.setField(1,{name:"Name",width:148,editor:turbo.grid.edit.line});
_9f0.setField(2,{name:"Type",defaultValue:"int",options:turbo.adodb.columnTypes},turbo.grid.columns.enumerated);
_9f0.setField(3,{name:"Length",width:60,editor:turbo.grid.edit.integer});
_9f0.setField(4,{name:"Decimals",width:60,editor:turbo.grid.edit.integer});
_9f0.setField(5,{name:"Default",width:80,editor:turbo.grid.edit.line});
_9f0.setField(6,{name:"Allow Null",defaultValue:false},turbo.grid.columns.bool);
_9f0.setField(7,{name:"Auto Increment",defaultValue:false},turbo.grid.columns.bool);
_9f0.setField(8,{name:"Unsigned",defaultValue:false},turbo.grid.columns.bool);
_9f0.setField(9,{name:"Zerofill",defaultValue:false},turbo.grid.columns.bool);
_9f0.setField(10,{name:"Binary",defaultValue:false},turbo.grid.columns.bool);
_9f0.setField(11,{name:"Values",width:150});
};
};
dojo.provide("tda.modules.pageControls");
tda.pageControl=function(_9f1){
this.grid=turbo.global[_9f1+"Grid"];
this.slider=turbo.global[_9f1+"PageSlider"];
this.buttons=turbo.global[_9f1+"PageButtons"];
this.info=turbo.$(_9f1+"PageInfo");
this.isSlider=undefined;
this.init=function(){
var _9f2=(this.grid.model.pageCount>0?this.grid.model.pageCount:1);
this.minSliderPages=tdaConfig.minSliderPages?tdaConfig.minSliderPages:50;
var _9f3=this.isSlider;
this.isSlider=(_9f2>=this.minSliderPages);
if(this.isSlider){
this.slider.setMinMax(0,_9f2-1);
if(!this.slider.mouseDown){
this.slider.setValue(this.grid.model.page);
}
}else{
this.buttons.initBuild(_9f2,Math.max(this.grid.model.page+1,1));
}
if(this.isSlider!==_9f3){
this.showControl();
turbo.aligner.align();
}
this.updatePageDisplay();
};
this.showControl=function(){
turbo.showHide(this.slider.domNode.parentNode,this.isSlider);
turbo.showHide(this.buttons.domNode.parentNode,!this.isSlider);
};
this.reShowControl=function(){
this.isSlider=!this.isSlider;
this.showControl();
this.isSlider=!this.isSlider;
this.showControl();
};
this.getPage=function(){
return this.isSlider?this.slider.getValue():this.buttons.page-1;
};
this.getLastValue=function(){
return this.isSlider?this.slider.lastValue:this.buttons.lastValue-1;
};
this.getMaximum=function(){
return this.isSlider?this.slider.maximum+1:this.buttons.numPages;
};
this.setPage=function(_9f4){
if(this.isSlider){
this.slider.setValue(_9f4);
}else{
this.buttons.setPage(_9f4+1);
}
};
this.changing=function(){
this.grid.applyEdit();
this.updatePageDisplay();
};
this.change=function(){
var p=this.getPage();
if(this.grid.model.page==p){
return;
}
if(!tda.app.confirmBuild()){
this.setValue(this.getLastValue());
this.updatePageDisplay();
return;
}
this.grid.selectPage(p,turbo.bind(this,this.updatePageDisplay));
};
this.updatePageDisplay=function(){
var p=this.getPage();
var msg="Page "+(p+1)+" of "+(this.getMaximum())+" - Rows "+(this.grid.model.getTopRow(p)+1)+" to "+(this.grid.model.getBottomRow(p)+1)+"";
if(this.grid.model.page==p&&!this.grid.model.pageIsValid(p)){
msg+="*";
}
this.info.innerHTML=msg;
};
};
dojo.provide("tda.modules.tree2");
tda.tree={};
tda.tree.init=function(){
tda.tree.build();
tda.tree.selectDataNode(tdaConfig.defaultDb,tdaConfig.defaultTable);
};
tda.tree.attach=function(){
tree.onCanSelect=tda.app.canChangeSelection;
tree.onSelect=tda.app.selectionChanged;
tree.onInitNode=tda.tree.initNode;
tree.onInitChildren=tda.tree.initChildren;
};
tda.tree.build=function(){
tda.tree.attach();
tda.tree.servers=null;
var _9f8=tda.app.service.list_servers();
if(_9f8.error){
turbo.debug(_9f8.error);
}else{
tda.tree.servers=_9f8.result;
tree.setRootCount(tda.tree.servers.length);
}
};
tda.tree.refresh=function(){
var d=tda.app.getSelectedDb();
var t=tda.app.getSelectedTable();
tda.tree.build();
tda.tree.selectDataNode(d,t);
};
tda.tree.selectDb=function(inDb){
tda.tree.selectDataNode(inDb);
};
tda.tree.getSelectedDb=function(){
return (tree.selected?tree.selected.db:null);
};
tda.tree.getSelectedTable=function(){
return (tree.selected?tree.selected.table:null);
};
tda.tree.initNode=function(_9fc){
if(_9fc.parent==null){
tda.tree.initServerNode(_9fc);
}else{
_9fc.parent.initChild(_9fc);
}
};
tda.tree.initChildren=function(_9fd){
if(_9fd.parent==null){
tda.tree.initServerChildren(_9fd);
}else{
_9fd.parent.initGrandchildren(_9fd);
}
};
tda.tree.initServerNode=function(_9fe){
_9fe.server=tda.tree.servers[_9fe.index];
_9fe.setContent("<img src=\"images/server.gif\"> "+_9fe.server);
_9fe.hasChildren=true;
_9fe.initChild=tda.tree.initDbNode;
_9fe.initGrandchildren=tda.tree.initDbChildren;
_9fe.setOpen(true);
};
tda.tree.initServerChildren=function(_9ff){
var _a00=tda.app.service.list_databases();
if(_a00.error){
turbo.debug(_a00.error);
}else{
_9ff.dbs=_a00.result;
_9ff.childCount=_9ff.dbs.length;
}
};
tda.tree.initDbNode=function(_a01){
_a01.db=_a01.parent.dbs[_a01.index];
_a01.initChild=tda.tree.initTableNode;
_a01.hasChildren=true;
_a01.setContent("<img src=\"images/database.gif\"> "+_a01.db);
};
tda.tree.initDbChildren=function(_a02){
turbo.setBusyCursor();
var _a03=tda.app.service.list_tables(_a02.db);
turbo.setDefaultCursor();
if(_a03.error){
turbo.debug(_a03.error);
}else{
_a02.tables=_a03.result;
_a02.childCount=_a02.tables.length;
}
};
tda.tree.initTableNode=function(_a04){
_a04.db=_a04.parent.db;
_a04.table=_a04.parent.tables[_a04.index];
_a04.setContent("<img src=\"images/table.gif\"> "+_a04.table);
};
tda.tree.selectDataNode=function(_a05,_a06){
var node=tree.forEach(function(_a08){
return (_a08.db&&_a08.db==_a05);
});
if(!node){
return;
}
node.setOpen(true);
if(_a06){
node=tree.forEach(function(_a09){
return (_a09.db&&_a09.db==_a05&&_a09.table&&_a09.table==_a06);
});
}
if(node){
tree.selectNode(node);
}
};
dojo.provide("tda.modules.schema");
tda.schema={init:function(){
schemaGrid=new turbo.grid.controller(schema);
schemaGrid.setModel(new turbo.data.store());
schemaGrid.commitRow=tda.schema.commitRow;
},selectionChange:function(){
moveUpBtn.setState(schemaGrid.canMoveRow(-1)?"":"disabled");
moveDownBtn.setState(schemaGrid.canMoveRow(1)?"":"disabled");
deleteColumnsBtn.setState(schemaGrid.grid.hasSelection()?"":"disabled");
},displaySchema:function(){
this.columns=turbo.adodb.schemaAdapter.adaptData(dataGrid.model.schema.columns);
schemaGrid.model.setData(this.columns);
turbo.adodb.schemaAdapter.adaptFields(schemaGrid.model);
turbo.adodb.schemaAdapter.adaptColumns(schemaGrid.columns);
schemaGrid.build();
},addRow:function(){
schemaGrid.newRow();
},deleteColumns:function(){
var rows=schemaGrid.grid.getSelectedRows();
if(rows.length){
if(confirm("Ok to delete "+(rows.length!=1?rows.length+" columns":"column")+" from table \""+dataGrid.model.table+"\"?")){
schemaGrid.removeSelectedRows();
}
}
},setModified:function(_a0b){
this.modified=_a0b;
schemaApplyBtn.setState(this.modified?"":"disabled");
schemaCancelBtn.setState(this.modified?"":"disabled");
},moveDown:function(){
schemaGrid.moveRowDown();
this.setModified(true);
},moveUp:function(){
schemaGrid.moveRowUp();
this.setModified(true);
},commitRow:function(_a0c){
dojo.debug("commit schema row change");
this.setModified(true);
},applyEdit:function(){
dojo.debug(tda.sql.buildAlterQuery(this.columns));
dojo.debug("--");
this.setModified(false);
}};
dojo.provide("tda.modules.data");
tda.data={init:function(){
dataGrid=new turbo.grid.controllers.paged(grid);
dataGrid.setModel(new turbo.data.sqltable(tda.app.service,turbo.adodb.adapter));
tda.data.pageControl=new tda.pageControl("data");
if(tdaConfig.rowsPerPage){
dataGrid.model.rowsPerPage=tdaConfig.rowsPerPage;
}
tda.data.attach(dataGrid);
},attach:function(_a0d){
dataGrid.canSort=tda.data.canSort;
dataGrid.rowsChanged=tda.data.rowsChanged;
dataGrid.showMessage=tda.data.showMessage;
dojo.event.connect(dataGrid,"onEditRowStart",tda.data,"enableEditButtons");
dojo.event.connect(dataGrid,"onEditRowDone",tda.data,"disableEditButtons");
dojo.event.connect(dataGrid,"onHeaderClick",tda.data.pageControl,"init");
},showMessage:function(_a0e){
tda.app.message(_a0e);
},canSort:function(){
return tda.app.confirmBuild();
},selectionChange:function(){
deleteRowsBtn.setState(dataGrid.grid.hasSelection()?"":"disabled");
var s=dataGrid.grid.getSelectedRows();
var c=turbo.sparse.count(s);
var m=(c?c+" row"+(c==1?"":"s")+" selected":"");
tda.data.showMessage(m);
},rowsChanged:function(){
tda.data.pageControl.init();
},enableEditButtons:function(){
dataApplyBtn.setState("");
dataCancelBtn.setState("");
},disableEditButtons:function(){
dataApplyBtn.setState("disabled");
dataCancelBtn.setState("disabled");
},applyEdit:function(){
dataGrid.applyEdit();
},cancelEdit:function(){
dataGrid.cancelEdit();
tda.data.pageControl.init();
},addRow:function(){
dataGrid.newRow();
dataGrid.model.pages[dataGrid.model.page]=dataGrid.model.data;
tda.data.enableEditButtons();
},deleteRows:function(){
dataGrid.deleteRows();
}};
dojo.provide("tda.modules.sql");
tda.sql={warnings:{badSql:"SQL contains disallowed commands and will not be executed.",dropDb:"Databases cannot be dropped.",dropTable:"Are you sure you want to drop this table?",alterTable:"Are you sure you want to alter this table?",deleteRecords:"Are you sure you want to delete records from this table?",needName:"Input a name for the SQL to save.",needSql:"Input an SQL statement to save.",nameInUse:"Are you sure you want to update this saved query?",sqlTooLong:function(_a12){
return "SQL is too long. Maximum SQL save length is "+_a12+" characters.";
},explainExecute:"Please enter some SQL to execute."},init:function(){
sqlGrid=new turbo.grid.controllers.paged(sql);
sqlGrid.setModel(new turbo.data.sqlquery(tda.app.service,turbo.adodb.adapter));
sqlGrid.setReadonly(true);
sqlGrid.canSort=function(){
return false;
};
if(tdaConfig.rowsPerPage){
sqlGrid.model.rowsPerPage=tdaConfig.rowsPerPage;
}
tda.sql.pageControl=new tda.pageControl("sql");
tda.sql.saves.init(tda.app.service);
tda.sql.exprt.init();
tda.sql.server="php/sql_server.php";
tda.sql.sqlNode=turbo.$("sqlText");
tda.sql.sqlInputNode=turbo.$("sqlInput");
dojo.event.connect("after",sqlGrid,"build",tda.sql,"showHideGrid");
dojo.event.connect("before",sqlGrid,"build",tda.sql,"adaptColumns");
tda.sql.showHideGrid();
tda.sql.setReadyState();
},adaptColumns:function(){
if(sqlGrid.model.schema){
turbo.adodb.adapter.adaptColumns(sqlGrid.columns,sqlGrid.model.schema.columns);
}
},showHideGrid:function(_a13){
if(_a13==undefined){
_a13=sqlGrid.model.hasResultData;
}
turbo.showHide(sqlGrid.grid.GrdTbl,_a13);
turbo.showHide("sqlResults",_a13);
turbo.showHide("sqlResultsSplitter",_a13);
this.sqlInputNode.style.height="75px";
this.sqlInputNode.setAttribute("turboalign",(_a13)?"top":"client");
turbo.debug(this.sqlInputNode.getAttribute("turboalign"));
turbo.defer(turbo.aligner.align,10);
},sqlExecute:function(){
sqlGrid.clear();
sqlGrid.model.clear();
if(!this.setSql(this.sqlNode.value)){
return;
}
sqlGrid.model.db=tda.app.getSelectedDb()?tda.app.getSelectedDb():"";
if(!sqlGrid.model.canRequest()){
alert(tda.sql.warnings.explainExecute);
}else{
this.setBusyState();
dojo.io.bind({url:this.server,method:"post",contentType:"application/x-www-form-urlencoded; charset=utf-8",postContent:sqlGrid.model.db+"|"+this.sqlNode.value,load:function(type,data,_a16){
tda.sql.processExecute(data);
},error:function(){
turbo.bind(tda.sql,tda.sql.processError);
},sync:false});
}
},processError:function(){
alert("Transport Error");
this.setReadyState();
},processExecute:function(_a17){
try{
var _a18=eval("("+_a17+")");
sqlGrid.model.processResult(_a18,turbo.bind(tda.sql,"processPage"));
}
catch(e){
dojo.debug("eval failed: ["+_a17+"]");
this.setReadyState();
alert("Server Error.");
}
},processPage:function(){
if(sqlGrid.model.hasResultData){
sqlGrid.selectPage(0,turbo.bind(tda.sql,"finishSelectPage"));
}else{
this.showHideGrid(false);
this.finishExecute();
}
},finishSelectPage:function(){
tda.sql.pageControl.init();
this.finishExecute();
},finishExecute:function(){
this.setReadyState();
if(sqlGrid.model.message){
alert(sqlGrid.model.message);
}
},clear:function(){
sqlGrid.model.clear();
this.showHideGrid();
tda.sql.saves.clearSql();
this.sqlNode.focus();
this.abortExecute();
},setBusyState:function(){
sqlExecuteBtn.set("Processing...","snake.gif");
sqlExecuteBtn.setState("disabled");
turbo.setBusyCursor();
},setReadyState:function(){
sqlExecuteBtn.set("Execute SQL","table_sql_run.gif");
sqlExecuteBtn.setState("");
turbo.setDefaultCursor();
},abortExecute:function(){
this.setReadyState();
},setSql:function(_a19){
if(_a19.length>500){
return sqlGrid.model.setSql(_a19);
}
turbo.sql.parser.start(_a19);
var _a1a=turbo.sql.parser.r;
turbo.sql.analyzer.setTokens(_a1a);
if(turbo.sql.analyzer.hasBadSql()){
alert(tda.sql.warnings.badSql);
return false;
}
if(turbo.sql.analyzer.hasDropDb()){
alert(tda.sql.warnings.dropDb);
return false;
}
if(turbo.sql.analyzer.hasDropTable()){
if(!confirm(tda.sql.warnings.dropTable)){
return false;
}
}
if(turbo.sql.analyzer.hasAlterTable()){
if(!confirm(tda.sql.warnings.alterTable)){
return false;
}
}
if(turbo.sql.analyzer.hasDelete()){
if(!confirm(tda.sql.warnings.deleteRecords)){
return false;
}
}
return sqlGrid.model.setSql(_a19);
}};
tda.sql.saves={init:function(_a1b){
this.maxSaveLength=5000;
this.editNode=turbo.$("sqlEdit");
this.selectNode=turbo.$("sqlSelect");
this.sqlNode=turbo.$("sqlText");
this.store=new turbo.data.fileStore(_a1b);
this.loadFromStore();
},canCommit:function(_a1c){
if(_a1c.name==""){
return alert(tda.sql.warnings.needName);
}
if(_a1c.sql==""){
return alert(tda.sql.warnings.needSql);
}
if(_a1c.sql.length>this.maxSaveLength){
return alert(tda.sql.warnings.sqlTooLong(this.maxSaveLength));
}
return true;
},editSql:function(_a1d,_a1e){
if(confirm(tda.sql.warnings.nameInUse)){
this.store.editField(_a1d,_a1e);
this.selectNode.selectedIndex=Number(_a1d)+1;
}
},addSql:function(_a1f){
this.store.addField(_a1f);
this.addToSelect(_a1f);
},addToSelect:function(_a20){
this.selectNode.options[this.selectNode.options.length]=new Option(_a20.name,"");
this.selectNode.selectedIndex=this.selectNode.options.length-1;
},commitSql:function(){
var s={name:this.editNode.value,sql:this.sqlNode.value};
if(!this.canCommit(s)){
return;
}
var i=this.store.fieldIndex(s);
if(i!=-1){
this.editSql(i,s);
}else{
this.addSql(s);
}
},deleteSql:function(){
if(this.selectNode.selectedIndex==0){
return;
}
this.store.deleteField(this.selectNode.selectedIndex-1);
this.selectNode.remove(this.selectNode.selectedIndex);
this.sqlNode.value="";
this.editNode.value="";
},loadSql:function(){
if(this.selectNode.selectedIndex!=0){
var s=this.store.getField(this.selectNode.selectedIndex-1);
this.sqlNode.value=s.sql;
this.editNode.value=s.name;
}else{
this.clearSql();
}
},loadFromStore:function(){
this.store.loadFields();
for(var i in this.store.fields){
this.addToSelect(this.store.getField(i));
}
this.selectNode.selectedIndex=0;
},clearSql:function(){
this.selectNode.selectedIndex=0;
this.editNode.value="";
this.sqlNode.value="";
}};
tda.sql.exprt={init:function(){
this.executePanel=turbo.$("sqlExecutePanel");
this.exportResultsPanel=turbo.$("sqlExportResultsPanel");
this.exportHtmlRb=turbo.$("sqlExportHtml");
this.output=turbo.$("sqlExportText");
this.renderedOutput=turbo.$("sqlExportRendered");
},setBusyState:function(){
sqlExportBtn.setState("disabled");
turbo.setBusyCursor();
},setReadyState:function(){
sqlExportBtn.setState("");
turbo.setDefaultCursor();
},disEnableControl:function(_a25,_a26){
_a25.disabled=!_a26;
},doExport:function(){
var _a27=turbo.bind(this,this.doOutput);
if(this.exportHtmlRb.checked){
tda.app.service.get_query_html(sqlGrid.model.db,sqlGrid.model.getSql(),_a27);
}else{
tda.app.service.get_query_sv(sqlGrid.model.db,sqlGrid.model.getSql(),_a27);
}
this.setBusyState();
},doRender:function(_a28){
_a28=_a28==undefined?this.renderedOutput.style.display=="none":_a28;
this.renderedOutput.innerHTML="";
if(_a28){
this.showRender();
this.renderedOutput.innerHTML=this.output.value;
}else{
this.hideRender();
}
},doOutput:function(_a29){
if(_a29.error){
this.setReadyState();
dojo.debug("Error processing export.");
return;
}
this.setReadyState();
this.showOutput();
this.output.value=_a29.result;
},showRender:function(){
sqlRenderExportBtn.setCaption("Source");
turbo.hide(this.output);
turbo.show(this.renderedOutput);
turbo.aligner.align();
},hideRender:function(){
sqlRenderExportBtn.setCaption("Render");
turbo.hide(this.renderedOutput);
turbo.show(this.output);
turbo.aligner.align();
},showOutput:function(){
turbo.show(sqlRenderExportBtn.domNode,this.exportHtmlRb.checked);
sqlRenderExportBtn.setCaption("Render");
turbo.show(this.output);
turbo.hide(this.renderedOutput);
turbo.hide(this.executePanel);
turbo.show(this.exportResultsPanel);
turbo.aligner.align();
},hideOutput:function(){
this.output.value="";
turbo.hide(this.exportResultsPanel);
turbo.show(this.executePanel);
turbo.aligner.align();
},resetExport:function(){
this.hideOutput();
}};
dojo.provide("tda.modules.exprt");
tda.exprt={init:function(){
tda.exprt.exportGrid=new turbo.grid.controller(exprt);
tda.exprt.exportGrid.setModel(new turbo.data.store());
tda.exprt.service=tda.app.service;
tda.exprt.setupPanel=turbo.$("exportSetupPanel");
tda.exprt.resultsPanel=turbo.$("exportResultsPanel");
tda.exprt.output=turbo.$("exportText");
tda.exprt.sqlRb=turbo.$("exportSql");
tda.exprt.sqlSchemaRb=turbo.$("exportSqlSchema");
tda.exprt.csvRb=turbo.$("exportCsv");
tda.exprt.htmlRb=turbo.$("exportHtml");
tda.exprt.dbPrefixNode=turbo.$("exportDbPrefix");
tda.exprt.tablePrefixNode=turbo.$("exportTablePrefix");
tda.exprt.exportRenderedNode=turbo.$("exportRendered");
tda.exprt.updateSettings();
dojo.event.connect(tda.app,"onSelectionChanged",tda.exprt,"updateSettings");
},disEnableControl:function(_a2a,_a2b){
_a2a.disabled=!_a2b;
_a2a.parentNode.style.color=(_a2b?"black":"silver");
},getExportType:function(){
if(tda.app.getSelectedDb()==null){
return "db";
}
if(tda.app.getSelectedTable()==null){
return "table";
}
return "column";
},getExportFormat:function(){
if(this.sqlRb.checked){
return this.sqlRb.value;
}
if(this.sqlSchemaRb.checked){
return this.sqlSchemaRb.value;
}
if(this.csvRb.checked){
return this.csvRb.value;
}
if(this.htmlRb.checked){
return this.htmlRb.value;
}
},canExport:function(){
return (this.numRowsMarked()||this.sqlSchemaRb.checked);
},updateSettings:function(){
var type=this.getExportType();
var _a2d=(type=="column");
this.disEnableControl(this.htmlRb,_a2d);
this.disEnableControl(this.csvRb,_a2d);
if(_a2d&&(this.htmlRb.checked||this.csvRb.checked)){
this.sqlRb.checked=true;
}
this.disEnableControl(this.dbPrefixNode,type=="db");
this.disEnableControl(this.tablePrefixNode,!_a2d);
this.buildGrid();
},buildGrid:function(){
this.exportGrid.grid.clearSelection();
this.exportGrid.grid.clearGrid();
var type=this.getExportType();
if(type=="db"){
this.fillDbs();
}else{
if(type=="table"){
this.fillTables(tda.app.getSelectedDb());
}else{
this.fillColumns(tda.app.getSelectedDb(),tda.app.getSelectedTable());
}
}
this.exportGrid.build();
},exportColumn:dojo.lang.mixin({name:"Export"},turbo.grid.columns.bool),sourceColumn:{width:145,readonly:true},targetColumn:{width:145,editor:turbo.grid.edit.line},getColumns:function(_a2f){
var n="";
switch(this.getExportType()){
case "db":
n="Database";
break;
case "table":
n="Table";
break;
default:
n="Column";
break;
}
return [this.exportColumn,dojo.lang.mixin({name:n+" Name"},this.sourceColumn),dojo.lang.mixin({name:"Output Name"},this.targetColumn)];
},fillDbs:function(){
var _a31=this.getColumns();
var data=this.service.list_databases().result;
for(var i in data){
data[i]=Array(false,data[i],data[i]);
}
this.exportGrid.model.setData(data,_a31);
this.exportGrid.setColumns(_a31);
},fillTables:function(inDb){
var _a35=this.getColumns();
var data=this.service.list_tables(inDb).result;
for(var i in data){
data[i]=Array(false,data[i],data[i]);
}
this.exportGrid.model.setData(data,_a35);
this.exportGrid.setColumns(_a35);
},fillColumns:function(inDb,_a39){
var _a3a=this.getColumns();
var _a3b=this.service.list_columns(_a39,inDb).result;
var data=[];
var x=0;
for(var i in _a3b){
data[x]=Array(true,_a3b[i].name,_a3b[i].name);
x++;
}
this.exportGrid.model.setData(data,_a3a);
this.exportGrid.setColumns(_a3a);
},markNoneAll:function(_a3f){
this.exportGrid.finishEditCell();
for(var i=0;i<this.exportGrid.model.getRowCount();i++){
this.exportGrid.model.setDatum(0,i,_a3f);
this.exportGrid.grid.updateRow(i);
}
},numRowsMarked:function(){
return this.getCheckedInColumn(0).length;
},getCheckedInColumn:function(_a41){
this.exportGrid.finishEditCell();
var _a42=[];
for(var i=0;i<this.exportGrid.model.getRowCount();i++){
if(this.exportGrid.getDatum(0,i)){
_a42.push(this.exportGrid.getDatum(_a41,i));
}
}
return _a42;
},getSources:function(){
return this.getCheckedInColumn(1);
},getTargets:function(){
return this.getCheckedInColumn(2);
},setBusyState:function(){
exportBtn.setState("disabled");
turbo.setBusyCursor();
},setReadyState:function(){
exportBtn.setState("");
turbo.setDefaultCursor();
},doExport:function(){
if(!this.canExport()){
return alert("Nothing to export");
}
var _a44=this.getExportFormat();
var type=this.getExportType();
var _a46=(this.getExportFormat()!="sqlSchema");
var _a47=turbo.bind(this,this.doOutput);
if(type=="db"){
var _a48=this.applyPrefix(this.dbPrefixNode.value,this.getTargets());
this.service.export_databases_sql(this.getSources(),_a48,this.tablePrefixNode.value,_a46,_a47);
}else{
if(type=="table"){
var _a48=this.applyPrefix(this.tablePrefixNode.value,this.getTargets());
this.service.export_tables_sql(tda.app.getSelectedDb(),this.getSources(),_a48,_a46,_a47);
}else{
if(type=="column"){
if(_a44=="csv"){
this.service.get_table_sv(tda.app.getSelectedDb(),tda.app.getSelectedTable(),this.getSources(),this.getTargets(),_a47);
}else{
if(_a44=="html"){
this.service.get_table_html(tda.app.getSelectedDb(),tda.app.getSelectedTable(),this.getSources(),this.getTargets(),_a47);
}else{
this.service.export_table_sql(tda.app.getSelectedDb(),tda.app.getSelectedTable(),"",this.getSources(),_a46,_a47);
}
}
}
}
}
this.setBusyState();
},doOutput:function(_a49){
if(_a49.error){
this.setReadyState();
dojo.debug("Error processing export.");
return;
}
this.setReadyState();
this.showOutput();
this.output.value=_a49.result;
},showOutput:function(){
renderExportBtn.setCaption("Render");
turbo.show(renderExportBtn.domNode,this.htmlRb.checked);
turbo.hide(this.setupPanel);
turbo.show(this.resultsPanel);
turbo.show(this.output);
turbo.hide(this.exportRenderedNode);
turbo.aligner.align();
},hideOutput:function(){
this.output.value="";
turbo.show(this.setupPanel);
turbo.hide(this.resultsPanel);
turbo.aligner.align();
},resetExport:function(){
this.hideOutput();
},applyPrefix:function(_a4a,_a4b){
result=[];
for(var i in _a4b){
result.push(_a4a+_a4b[i]);
}
return result;
},doRender:function(_a4d){
_a4d=(_a4d==undefined?this.exportRenderedNode.style.display=="none":_a4d);
this.exportRenderedNode.innerHTML="";
if(_a4d){
this.showRender();
this.exportRenderedNode.innerHTML=this.output.value;
}else{
this.hideRender();
}
},showRender:function(){
renderExportBtn.setCaption("Source");
turbo.show(this.exportRenderedNode);
turbo.hide(this.output);
turbo.aligner.align();
},hideRender:function(){
renderExportBtn.setCaption("Render");
turbo.show(this.output);
turbo.hide(this.exportRenderedNode);
turbo.aligner.align();
}};
dojo.provide("tda.includes");
dojo.hostenv.setModulePrefix("turbo","../turbo");
dojo.require("turbo.lib.json_rpc");
dojo.require("turbo.lib.align");
dojo.require("turbo.lib.app");
dojo.require("turbo.lib.sqlparse");
dojo.require("turbo.widgets.TurboToolbar");
dojo.require("turbo.widgets.TurboTree2");
dojo.require("turbo.widgets.TurboGrid");
dojo.require("turbo.widgets.TurboSlider");
dojo.require("turbo.widgets.TurboSplitter");
dojo.require("turbo.widgets.TurboModule");
dojo.require("turbo.widgets.TurboNotebook");
dojo.require("turbo.widgets.TurboPagebar");
dojo.require("turbo.widgets.TurboPageButtons");
dojo.require("turbo.data.stores");
dojo.require("turbo.data.sqltable");
dojo.require("turbo.data.sqlquery");
dojo.require("turbo.data.filestores");
dojo.require("turbo.grid.controllers");
dojo.require("turbo.grid.adodb");
dojo.hostenv.setModulePrefix("tda","../tda2");
dojo.require("tda.modules.pageControls");
dojo.require("tda.modules.tree2");
dojo.require("tda.modules.schema");
dojo.require("tda.modules.data");
dojo.require("tda.modules.sql");
dojo.require("tda.modules.exprt");
dojo.hostenv.writeIncludes();
dojo.provide("tda.tda");
if(!this["tdaConfig"]){
tdaConfig={};
}
var serverPath="php/data_server.php";
tda.strings={confirmAbandonEdits:"Row edits are in progress (yellow) or unsuccessful (red)."+"These unresolved row edits may be lost if you continue. "+"\n\nPress Ok to abandon these edits, or Cancel to return to editing."};
var grid,schema,tree,tabs,notebook,sql,exprt;
tda.app=turbo.app;
tda.app.assertGlobals=function(){
for(var i=0;i<arguments.length;i++){
if(!turbo.global[arguments[i]]){
throw (arguments[i]+" not available");
}
}
};
tda.app.init=function(){
tda.app.marshall("pageInfo","sqlPageInfo","dojoDebug","debugWindow","messages");
if(djConfig.isDebug){
debugWindow.style.display="";
debugSplitter.domNode.style.display="";
}
tda.app.message("Database");
tda.app.service=new remoteObject(serverPath);
turbo.debug("service discovery took "+tda.app.service.discoverTime+"s");
turbo.sql.mysql=(tda.app.service.is_mysql().result?true:false);
tda.app.assertGlobals("grid","schema","tree");
tda.app.disEnableBtns(false);
tda.data.init();
tda.schema.init();
tda.tree.init();
tda.app.setLockState(1);
tda.app.setLockState(0);
};
function clearDebug(){
dojoDebug.innerHTML="";
}
function clearMessages(){
messages.innerHTML="";
}
tda.app.message=function(_a4f){
if(_a4f){
messages.innerHTML="<div><img src=\"images/flag_green.gif\" hspace=\"4\">("+new Date().toLocaleTimeString()+") "+_a4f+"</div>";
}
};
tda.app.selectTab=function(){
tda.data.applyEdit();
notebook.selectPage(tabs.tabIndex);
turbo.defer("tda.data.pageControl.reShowControl()",250);
};
tda.app.canChangeSelection=function(){
return tda.app.confirmBuild();
};
tda.app.selectionChanged=function(){
tda.app._refresh();
tda.app.onSelectionChanged();
};
tda.app.onSelectionChanged=function(){
};
tda.app.getSelectedDb=tda.tree.getSelectedDb;
tda.app.getSelectedTable=tda.tree.getSelectedTable;
tda.app.confirmBuild=function(_a50){
dataGrid.applyEditSync();
if(dataGrid.model.metarows.empty()){
return true;
}else{
var ok=confirm(tda.strings.confirmAbandonEdits);
if(ok){
dataGrid.reloadPage();
}
return ok;
}
};
tda.app.autofill=function(){
window.setTimeout(tda.app._autofill,100);
};
tda.app._autofill=function(){
dataGrid.model.fillNextPage();
tda.app.autofill();
};
tda.app._refresh=function(){
tda.app.viewTable(tda.app.getSelectedDb(),tda.app.getSelectedTable());
};
tda.app.refresh=function(){
if(!tda.app.confirmBuild()){
return;
}
tda.app._refresh();
};
tda.app.disEnableBtns=function(_a52){
var _a53=(_a52?"":"disabled");
createRowBtn.setState(_a53);
dataRefreshBtn.setState(_a53);
schemaRefreshButton.setState(_a53);
};
tda.app._viewTable=function(inDb,_a55){
dataGrid.clear();
dataGrid.model.selectTable(inDb,_a55);
tda.schema.displaySchema();
turbo.adodb.adapter.adaptColumns(dataGrid.columns,dataGrid.model.schema.columns);
tda.data.pageControl.init();
dataGrid.selectPage(0,turbo.bind(tda.data.pageControl,"updatePageDisplay"));
};
tda.app.viewTable=function(inDb,_a57){
grid.teardown();
schema.teardown();
tda.app.disEnableBtns(inDb&&_a57);
if(inDb&&_a57){
turbo.defer(function(){
tda.app._viewTable(inDb,_a57);
},10);
}
};
tda.app.showHideEditBtns=function(_a58){
var d=(_a58?"":"none");
sep1.domNode.style.display=d;
createRowBtn.domNode.style.display=d;
deleteRowsBtn.domNode.style.display=d;
sep2.domNode.style.display=d;
dataApplyBtn.domNode.style.display=d;
dataCancelBtn.domNode.style.display=d;
var d="none";
sep3.domNode.style.display=d;
addColumnBtn.domNode.style.display=d;
deleteColumnsBtn.domNode.style.display=d;
sep4.domNode.style.display=d;
moveUpBtn.domNode.style.display=d;
moveDownBtn.domNode.style.display=d;
sep5.domNode.style.display=d;
schemaApplyBtn.domNode.style.display=d;
schemaCancelBtn.domNode.style.display=d;
};
tda.app.setLockState=function(_a5a){
tda.app.showHideEditBtns(!_a5a);
dataGrid.applyEdit();
dataGrid.readonly=_a5a;
dataLockedBtn.setState(_a5a);
dataLockedBtn.set((_a5a?"Locked":"Unlocked"),(_a5a?"lock.gif":"lock_open.gif"));
schemaGrid.setReadonly(1);
};
tda.app.lockedClick=function(_a5b){
if(!tda.app.confirmBuild()){
_a5b.setState(_a5b.state?0:1);
return;
}
tda.app.setLockState(_a5b.state);
if(dataGrid.model.metarows.empty()){
dataGrid.build();
}else{
tda.app._refresh();
}
};