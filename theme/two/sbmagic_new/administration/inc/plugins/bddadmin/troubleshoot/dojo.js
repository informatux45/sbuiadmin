/* Copyright (c) 2004-2005 The Dojo Foundation, Licensed under the Academic Free License version 2.1 or above */var dj_global=this;
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
dojo.version={major:0,minor:1,patch:0,flag:"+",revision:Number("$Rev: 1651 $".match(/[0-9]+/)[0]),toString:function(){
with(dojo.version){
return major+"."+minor+"."+patch+flag+" ("+revision+")";
}
}};
dojo.evalObjPath=function(_3,_4){
if(typeof _3!="string"){
return dj_global;
}
if(_3.indexOf(".")==-1){
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
dojo.raise=function(_9,_10){
if(_10){
_9=_9+": "+dojo.errorToString(_10);
}
var he=dojo.hostenv;
if(dj_undef("hostenv",dojo)&&dj_undef("println",dojo)){
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
var _14=arguments;
if(dj_undef("println",dojo.hostenv)){
dojo.raise("dojo.debug not available (yet?)");
}
var _15=dj_global["jum"]&&!dj_global["jum"].isBrowser;
var s=[(_15?"":"DEBUG: ")];
for(var i=0;i<_14.length;++i){
if(!false&&_14[i] instanceof Error){
var msg="["+_14[i].name+": "+dojo.errorToString(_14[i])+(_14[i].fileName?", file: "+_14[i].fileName:"")+(_14[i].lineNumber?", line: "+_14[i].lineNumber:"")+"]";
}else{
var msg=_14[i];
}
s.push(msg);
}
if(_15){
jum.debug(s.join(" "));
}else{
dojo.hostenv.println(s.join(" "));
}
};
var dj_debug=dojo.debug;
function dj_eval(s){
return dj_global.eval?dj_global.eval(s):eval(s);
}
dj_unimplemented=dojo.unimplemented=function(_18,_19){
var _20="'"+_18+"' not implemented";
if((typeof _19!="undefined")&&(_19)){
_20+=" "+_19;
}
dojo.raise(_20);
};
dj_deprecated=dojo.deprecated=function(_21,_22){
var _23="DEPRECATED: "+_21;
if((typeof _22!="undefined")&&(_22)){
_23+=" "+_22;
}
dojo.debug(_23);
};
dojo.inherits=function(_24,_25){
if(typeof _25!="function"){
dojo.raise("superclass: "+_25+" borken");
}
_24.prototype=new _25();
_24.prototype.constructor=_24;
_24.superclass=_25.prototype;
_24["super"]=_25.prototype;
};
dj_inherits=function(_26,_27){
dojo.deprecated("dj_inherits deprecated, use dojo.inherits instead");
dojo.inherits(_26,_27);
};
dojo.render=(function(){
function vscaffold(_28,_29){
var tmp={capable:false,support:{builtin:false,plugin:false},prefixes:_28};
for(var x in _29){
tmp[x]=false;
}
return tmp;
}
return {name:"",ver:dojo.version,os:{win:false,linux:false,osx:false},html:vscaffold(["html"],["ie","opera","khtml","safari","moz"]),svg:vscaffold(["svg"],["corel","adobe","batik"]),swf:vscaffold(["Swf","Flash","Mm"],["mm"]),swt:vscaffold(["Swt"],["ibm"])};
})();
dojo.hostenv=(function(){
var _32={isDebug:false,baseScriptUri:"",baseRelativePath:"",libraryScriptUri:"",iePreventClobber:false,ieClobberMinimal:true,preventBackButtonFix:true,searchIds:[],parseWidgets:true};
if(typeof djConfig=="undefined"){
djConfig=_32;
}else{
for(var _33 in _32){
if(typeof djConfig[_33]=="undefined"){
djConfig[_33]=_32[_33];
}
}
}
var djc=djConfig;
function _def(obj,_36,def){
return (dj_undef(_36,obj)?def:obj[_36]);
}
return {name_:"(unset)",version_:"(unset)",pkgFileName:"__package__",loading_modules_:{},loaded_modules_:{},addedToLoadingCount:[],removedFromLoadingCount:[],inFlightCount:0,modulePrefixes_:{dojo:{name:"dojo",value:"src"}},setModulePrefix:function(_38,_39){
this.modulePrefixes_[_38]={name:_38,value:_39};
},getModulePrefix:function(_40){
var mp=this.modulePrefixes_;
if((mp[_40])&&(mp[_40]["name"])){
return mp[_40].value;
}
return _40;
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
var _43=uri.lastIndexOf("/");
djConfig.baseScriptUri=djConfig.baseRelativePath;
return djConfig.baseScriptUri;
};
dojo.hostenv.setBaseScriptUri=function(uri){
djConfig.baseScriptUri=uri;
};
dojo.hostenv.loadPath=function(_44,_45,cb){
if((_44.charAt(0)=="/")||(_44.match(/^\w+:/))){
dojo.raise("relpath '"+_44+"'; must be relative");
}
var uri=this.getBaseScriptUri()+_44;
try{
return ((!_45)?this.loadUri(uri,cb):this.loadUriAndCheck(uri,_45,cb));
}
catch(e){
dojo.debug(e);
return false;
}
};
dojo.hostenv.loadUri=function(uri,cb){
if(dojo.hostenv.loadedUris[uri]){
return;
}
var _47=this.getText(uri,null,true);
if(_47==null){
return 0;
}
var _48=dj_eval(_47);
return 1;
};
dojo.hostenv.getDepsForEval=function(_49){
if(!_49){
_49="";
}
var _50=[];
var tmp;
var _51=[/dojo.hostenv.loadModule\(.*?\)/mg,/dojo.hostenv.require\(.*?\)/mg,/dojo.require\(.*?\)/mg,/dojo.requireIf\(.*?\)/mg,/dojo.hostenv.conditionalLoadModule\([\w\W]*?\)/mg];
for(var i=0;i<_51.length;i++){
tmp=_49.match(_51[i]);
if(tmp){
for(var x=0;x<tmp.length;x++){
_50.push(tmp[x]);
}
}
}
return _50;
};
dojo.hostenv.loadUriAndCheck=function(uri,_52,cb){
var ok=true;
try{
ok=this.loadUri(uri,cb);
}
catch(e){
dojo.debug("failed loading ",uri," with error: ",e);
}
return ((ok)&&(this.findModule(_52,false)))?true:false;
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
dojo.addOnLoad=function(obj,_55){
if(arguments.length==1){
dojo.hostenv.modulesLoadedListeners.push(obj);
}else{
if(arguments.length>1){
dojo.hostenv.modulesLoadedListeners.push(function(){
obj[_55]();
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
dojo.hostenv.moduleLoaded=function(_56){
var _57=dojo.evalObjPath((_56.split(".").slice(0,-1)).join("."));
this.loaded_modules_[(new String(_56)).toLowerCase()]=_57;
};
dojo.hostenv.loadModule=function(_58,_59,_60){
var _61=this.findModule(_58,false);
if(_61){
return _61;
}
if(dj_undef(_58,this.loading_modules_)){
this.addedToLoadingCount.push(_58);
}
this.loading_modules_[_58]=1;
var _62=_58.replace(/\./g,"/")+".js";
var _63=_58.split(".");
var _64=_58.split(".");
for(var i=_63.length-1;i>0;i--){
var _65=_63.slice(0,i).join(".");
var _66=this.getModulePrefix(_65);
if(_66!=_65){
_63.splice(0,i,_66);
break;
}
}
var _67=_63[_63.length-1];
if(_67=="*"){
_58=(_64.slice(0,-1)).join(".");
while(_63.length){
_63.pop();
_63.push(this.pkgFileName);
_62=_63.join("/")+".js";
if(_62.charAt(0)=="/"){
_62=_62.slice(1);
}
ok=this.loadPath(_62,((!_60)?_58:null));
if(ok){
break;
}
_63.pop();
}
}else{
_62=_63.join("/")+".js";
_58=_64.join(".");
var ok=this.loadPath(_62,((!_60)?_58:null));
if((!ok)&&(!_59)){
_63.pop();
while(_63.length){
_62=_63.join("/")+".js";
ok=this.loadPath(_62,((!_60)?_58:null));
if(ok){
break;
}
_63.pop();
_62=_63.join("/")+"/"+this.pkgFileName+".js";
if(_62.charAt(0)=="/"){
_62=_62.slice(1);
}
ok=this.loadPath(_62,((!_60)?_58:null));
if(ok){
break;
}
}
}
if((!ok)&&(!_60)){
dojo.raise("Could not load '"+_58+"'; last tried '"+_62+"'");
}
}
if(!_60){
_61=this.findModule(_58,false);
if(!_61){
dojo.raise("symbol '"+_58+"' is not defined after loading '"+_62+"'");
}
}
return _61;
};
dojo.hostenv.startPackage=function(_68){
var _69=_68.split(/\./);
if(_69[_69.length-1]=="*"){
_69.pop();
}
return dojo.evalObjPath(_69.join("."),true);
};
dojo.hostenv.findModule=function(_70,_71){
if(this.loaded_modules_[(new String(_70)).toLowerCase()]){
return this.loaded_modules_[_70];
}
var _72=dojo.evalObjPath(_70);
if((typeof _72!=="undefined")&&(_72)){
return _72;
}
if(_71){
dojo.raise("no loaded module named '"+_70+"'");
}
return null;
};
if(typeof window=="undefined"){
dojo.raise("no window object");
}
(function(){
if(((djConfig["baseScriptUri"]=="")||(djConfig["baseRelativePath"]==""))&&(document&&document.getElementsByTagName)){
var _73=document.getElementsByTagName("script");
var _74=/(__package__|dojo)\.js$/i;
for(var i=0;i<_73.length;i++){
var src=_73[i].getAttribute("src");
if(_74.test(src)){
var _76=src.replace(_74,"");
if(djConfig["baseScriptUri"]==""){
djConfig["baseScriptUri"]=_76;
}
if(djConfig["baseRelativePath"]==""){
djConfig["baseRelativePath"]=_76;
}
break;
}
}
}
var dr=dojo.render;
var drh=dojo.render.html;
var dua=drh.UA=navigator.userAgent;
var dav=drh.AV=navigator.appVersion;
var t=true;
var f=false;
drh.capable=t;
drh.support.builtin=t;
dr.ver=parseFloat(drh.AV);
dr.os.mac=dav.indexOf("Macintosh")>=0;
dr.os.win=dav.indexOf("Windows")>=0;
drh.opera=dua.indexOf("Opera")>=0;
drh.khtml=(dav.indexOf("Konqueror")>=0)||(dav.indexOf("Safari")>=0);
drh.safari=dav.indexOf("Safari")>=0;
drh.mozilla=drh.moz=(dua.indexOf("Gecko")>=0)&&(!drh.khtml);
drh.ie=(document.all)&&(!drh.opera);
drh.ie50=drh.ie&&dav.indexOf("MSIE 5.0")>=0;
drh.ie55=drh.ie&&dav.indexOf("MSIE 5.5")>=0;
drh.ie60=drh.ie&&dav.indexOf("MSIE 6.0")>=0;
dr.svg.capable=f;
dr.svg.support.plugin=f;
dr.svg.support.builtin=f;
dr.svg.adobe=f;
if(document.createElementNS&&drh.moz&&parseFloat(dua.substring(dua.lastIndexOf("/")+1,dua.length))>1){
dr.svg.capable=t;
dr.svg.support.builtin=t;
dr.svg.support.plugin=f;
dr.svg.adobe=f;
}else{
if(navigator.mimeTypes&&navigator.mimeTypes.length>0){
var _83=navigator.mimeTypes["image/svg+xml"]||navigator.mimeTypes["image/svg"]||navigator.mimeTypes["image/svg-xml"];
if(_83){
dr.svg.capable=t;
dr.svg.support.plugin=t;
dr.svg.adobe=_83&&_83.enabledPlugin&&_83.enabledPlugin.description&&(_83.enabledPlugin.description.indexOf("Adobe")>-1);
}
}else{
if(drh.ie&&dr.os.win){
var _83=f;
try{
var _84=new ActiveXObject("Adobe.SVGCtl");
_83=t;
}
catch(e){
}
if(_83){
dr.svg.capable=t;
dr.svg.support.plugin=t;
dr.svg.adobe=t;
}
}else{
dr.svg.capable=f;
dr.svg.support.plugin=f;
dr.svg.adobe=f;
}
}
}
})();
dojo.hostenv.startPackage("dojo.hostenv");
dojo.hostenv.name_="browser";
dojo.hostenv.searchIds=[];
var DJ_XMLHTTP_PROGIDS=["Msxml2.XMLHTTP","Microsoft.XMLHTTP","Msxml2.XMLHTTP.4.0"];
dojo.hostenv.getXmlhttpObject=function(){
var _85=null;
var _86=null;
try{
_85=new XMLHttpRequest();
}
catch(e){
}
if(!_85){
for(var i=0;i<3;++i){
var _87=DJ_XMLHTTP_PROGIDS[i];
try{
_85=new ActiveXObject(_87);
}
catch(e){
_86=e;
}
if(_85){
DJ_XMLHTTP_PROGIDS=[_87];
break;
}
}
}
if(!_85){
return dojo.raise("XMLHTTP not available",_86);
}
return _85;
};
dojo.hostenv.getText=function(uri,_88,_89){
var _90=this.getXmlhttpObject();
if(_88){
_90.onreadystatechange=function(){
if((4==_90.readyState)&&(_90["status"])){
if(_90.status==200){
dojo.debug("LOADED URI: "+uri);
_88(_90.responseText);
}
}
};
}
_90.open("GET",uri,_88?true:false);
_90.send(null);
if(_88){
return null;
}
return _90.responseText;
};
function dj_last_script_src(){
var _91=window.document.getElementsByTagName("script");
if(_91.length<1){
dojo.raise("No script elements in window.document, so can't figure out my script src");
}
var _92=_91[_91.length-1];
var src=_92.src;
if(!src){
dojo.raise("Last script element (out of "+_91.length+") has no src");
}
return src;
}
if(!dojo.hostenv["library_script_uri_"]){
dojo.hostenv.library_script_uri_=dj_last_script_src();
}
dojo.hostenv.defaultDebugContainerId="dojoDebug";
dojo.hostenv.println=function(_93){
try{
var _94=document.getElementById(djConfig.debugContainerId?djConfig.debugContainerId:dojo.hostenv.defaultDebugContainerId);
if(!_94){
_94=document.getElementsByTagName("body")[0]||document.body;
}
var div=document.createElement("div");
div.innerHTML = _93;
//div.appendChild(document.createTextNode(_93));
_94.appendChild(div);
}
catch(e){
try{
document.write("<div>"+_93+"</div>");
}
catch(e2){
window.status=_93;
}
}
};
function dj_addNodeEvtHdlr(_96,_97,fp,_99){
var _100=_96["on"+_97]||function(){
};
_96["on"+_97]=function(){
fp.apply(_96,arguments);
_100.apply(_96,arguments);
};
return true;
}
dj_addNodeEvtHdlr(window,"load",function(){
if(dojo.render.html.ie){
dojo.hostenv.makeWidgets();
}
dojo.hostenv.modulesLoaded();
});
dojo.hostenv.makeWidgets=function(){
if((djConfig.parseWidgets)||(djConfig.searchIds.length>0)){
if(dojo.evalObjPath("dojo.widget.Parse")){
try{
var _101=new dojo.xml.Parse();
var sids=djConfig.searchIds;
if(sids.length>0){
for(var x=0;x<sids.length;x++){
if(!document.getElementById(sids[x])){
continue;
}
var frag=_101.parseElement(document.getElementById(sids[x]),null,true);
dojo.widget.getParser().createComponents(frag);
}
}else{
if(djConfig.parseWidgets){
var frag=_101.parseElement(document.getElementsByTagName("body")[0]||document.body,null,true);
dojo.widget.getParser().createComponents(frag);
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
if(!window["djConfig"]||!window.djConfig["preventBackButtonFix"]){
document.write("");
}
if(dojo.render.html.ie){
document.write("<style>v:*{ behavior:url(#default#VML); }</style>");
document.write("<xml:namespace ns=\"urn:schemas-microsoft-com:vml\" prefix=\"v\"/>");
}
}
catch(e){
}
dojo.hostenv.writeIncludes=function(){
};
dojo.hostenv.conditionalLoadModule=function(_104){
var _105=_104["common"]||[];
var _106=(_104[dojo.hostenv.name_])?_105.concat(_104[dojo.hostenv.name_]||[]):_105.concat(_104["default"]||[]);
for(var x=0;x<_106.length;x++){
var curr=_106[x];
if(curr.constructor==Array){
dojo.hostenv.loadModule.apply(dojo.hostenv,curr);
}else{
dojo.hostenv.loadModule(curr);
}
}
};
dojo.hostenv.require=dojo.hostenv.loadModule;
dojo.require=function(){
dojo.hostenv.loadModule.apply(dojo.hostenv,arguments);
};
dojo.requireIf=function(){
if((arguments[0]=="common")||(dojo.render[arguments[0]].capable)){
var args=[];
for(var i=1;i<arguments.length;i++){
args.push(arguments[i]);
}
dojo.require.apply(dojo,args);
}
};
dojo.conditionalRequire=dojo.requireIf;
dojo.kwCompoundRequire=function(){
dojo.hostenv.conditionalLoadModule.apply(dojo.hostenv,arguments);
};
dojo.hostenv.provide=dojo.hostenv.startPackage;
dojo.provide=function(){
dojo.hostenv.startPackage.apply(dojo.hostenv,arguments);
};
dojo.profile={start:function(){
},end:function(){
},dump:function(){
}};
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
},parse:function(text){
var at=0;
var ch=" ";
function error(m){
throw {name:"JSONError",message:m,at:at-1,text:text};
}
function next(){
ch=text.charAt(at);
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
if(dojo){
dojo.provide("turbo.lib.des");
}
function des(key,_118,_119,mode,iv){
var _122=new Array(16843776,0,65536,16843780,16842756,66564,4,65536,1024,16843776,16843780,1024,16778244,16842756,16777216,4,1028,16778240,16778240,66560,66560,16842752,16842752,16778244,65540,16777220,16777220,65540,0,1028,66564,16777216,65536,16843780,4,16842752,16843776,16777216,16777216,1024,16842756,65536,66560,16777220,1024,4,16778244,66564,16843780,65540,16842752,16778244,16777220,1028,66564,16843776,1028,16778240,16778240,0,65540,66560,0,16842756);
var _123=new Array(-2146402272,-2147450880,32768,1081376,1048576,32,-2146435040,-2147450848,-2147483616,-2146402272,-2146402304,-2147483648,-2147450880,1048576,32,-2146435040,1081344,1048608,-2147450848,0,-2147483648,32768,1081376,-2146435072,1048608,-2147483616,0,1081344,32800,-2146402304,-2146435072,32800,0,1081376,-2146435040,1048576,-2147450848,-2146435072,-2146402304,32768,-2146435072,-2147450880,32,-2146402272,1081376,32,32768,-2147483648,32800,-2146402304,1048576,-2147483616,1048608,-2147450848,-2147483616,1048608,1081344,0,-2147450880,32800,-2147483648,-2146435040,-2146402272,1081344);
var _124=new Array(520,134349312,0,134348808,134218240,0,131592,134218240,131080,134217736,134217736,131072,134349320,131080,134348800,520,134217728,8,134349312,512,131584,134348800,134348808,131592,134218248,131584,131072,134218248,8,134349320,512,134217728,134349312,134217728,131080,520,131072,134349312,134218240,0,512,131080,134349320,134218240,134217736,512,0,134348808,134218248,131072,134217728,134349320,8,131592,131584,134217736,134348800,134218248,520,134348800,131592,8,134348808,131584);
var _125=new Array(8396801,8321,8321,128,8396928,8388737,8388609,8193,0,8396800,8396800,8396929,129,0,8388736,8388609,1,8192,8388608,8396801,128,8388608,8193,8320,8388737,1,8320,8388736,8192,8396928,8396929,129,8388736,8388609,8396800,8396929,129,0,0,8396800,8320,8388736,8388737,1,8396801,8321,8321,128,8396929,129,1,8192,8388609,8193,8396928,8388737,8193,8320,8388608,8396801,128,8388608,8192,8396928);
var _126=new Array(256,34078976,34078720,1107296512,524288,256,1073741824,34078720,1074266368,524288,33554688,1074266368,1107296512,1107820544,524544,1073741824,33554432,1074266112,1074266112,0,1073742080,1107820800,1107820800,33554688,1107820544,1073742080,0,1107296256,34078976,33554432,1107296256,524544,524288,1107296512,256,33554432,1073741824,34078720,1107296512,1074266368,33554688,1073741824,1107820544,34078976,1074266368,256,33554432,1107820544,1107820800,524544,1107296256,1107820800,34078720,0,1074266112,1107296256,524544,33554688,1073742080,524288,0,1074266112,34078976,1073742080);
var _127=new Array(536870928,541065216,16384,541081616,541065216,16,541081616,4194304,536887296,4210704,4194304,536870928,4194320,536887296,536870912,16400,0,4194320,536887312,16384,4210688,536887312,16,541065232,541065232,0,4210704,541081600,16400,4210688,541081600,536870912,536887296,16,541065232,4210688,541081616,4194304,16400,536870928,4194304,536887296,536870912,16400,536870928,541081616,4210688,541065216,4210704,541081600,0,541065232,16,16384,541065216,4210704,16384,4194320,536887312,0,541081600,536870912,4194320,536887312);
var _128=new Array(2097152,69206018,67110914,0,2048,67110914,2099202,69208064,69208066,2097152,0,67108866,2,67108864,69206018,2050,67110912,2099202,2097154,67110912,67108866,69206016,69208064,2097154,69206016,2048,2050,69208066,2099200,2,67108864,2099200,67108864,2099200,2097152,67110914,67110914,69206018,69206018,2,2097154,67108864,67110912,2097152,69208064,2050,2099202,69208064,2050,67108866,69208066,69206016,2099200,0,2,69208066,0,2099202,69206016,2048,67108866,67110912,2048,2097154);
var _129=new Array(268439616,4096,262144,268701760,268435456,268439616,64,268435456,262208,268697600,268701760,266240,268701696,266304,4096,64,268697600,268435520,268439552,4160,266240,262208,268697664,268701696,4160,0,0,268697664,268435520,268439552,266304,262144,266304,262144,268701696,4096,64,268697664,4096,266304,268439552,64,268435520,268697600,268697664,268435456,262144,268439616,0,268701760,262208,268435520,268697600,268439552,268439616,0,268701760,266240,266240,4160,4160,262208,268435456,268701696);
var keys=des_createKeys(key);
var m=0,i,j,temp,temp2,right1,right2,left,right,looping;
var _131,cbcleft2,cbcright,cbcright2;
var _132,loopinc;
var len=_118.length;
var _134=0;
var _135=keys.length==32?3:9;
if(_135==3){
looping=_119?new Array(0,32,2):new Array(30,-2,-2);
}else{
looping=_119?new Array(0,32,2,62,30,-2,64,96,2):new Array(94,62,-2,32,64,2,30,-2,-2);
}
_118+="\x00\x00\x00\x00\x00\x00\x00\x00";
result="";
tempresult="";
if(mode==1){
_131=(iv.charCodeAt(m++)<<24)|(iv.charCodeAt(m++)<<16)|(iv.charCodeAt(m++)<<8)|iv.charCodeAt(m++);
cbcright=(iv.charCodeAt(m++)<<24)|(iv.charCodeAt(m++)<<16)|(iv.charCodeAt(m++)<<8)|iv.charCodeAt(m++);
m=0;
}
while(m<len){
left=(_118.charCodeAt(m++)<<24)|(_118.charCodeAt(m++)<<16)|(_118.charCodeAt(m++)<<8)|_118.charCodeAt(m++);
right=(_118.charCodeAt(m++)<<24)|(_118.charCodeAt(m++)<<16)|(_118.charCodeAt(m++)<<8)|_118.charCodeAt(m++);
if(mode==1){
if(_119){
left^=_131;
right^=cbcright;
}else{
cbcleft2=_131;
cbcright2=cbcright;
_131=left;
cbcright=right;
}
}
temp=((left>>>4)^right)&252645135;
right^=temp;
left^=(temp<<4);
temp=((left>>>16)^right)&65535;
right^=temp;
left^=(temp<<16);
temp=((right>>>2)^left)&858993459;
left^=temp;
right^=(temp<<2);
temp=((right>>>8)^left)&16711935;
left^=temp;
right^=(temp<<8);
temp=((left>>>1)^right)&1431655765;
right^=temp;
left^=(temp<<1);
left=((left<<1)|(left>>>31));
right=((right<<1)|(right>>>31));
for(j=0;j<_135;j+=3){
_132=looping[j+1];
loopinc=looping[j+2];
for(i=looping[j];i!=_132;i+=loopinc){
right1=right^keys[i];
right2=((right>>>4)|(right<<28))^keys[i+1];
temp=left;
left=right;
right=temp^(_123[(right1>>>24)&63]|_125[(right1>>>16)&63]|_127[(right1>>>8)&63]|_129[right1&63]|_122[(right2>>>24)&63]|_124[(right2>>>16)&63]|_126[(right2>>>8)&63]|_128[right2&63]);
}
temp=left;
left=right;
right=temp;
}
left=((left>>>1)|(left<<31));
right=((right>>>1)|(right<<31));
temp=((left>>>1)^right)&1431655765;
right^=temp;
left^=(temp<<1);
temp=((right>>>8)^left)&16711935;
left^=temp;
right^=(temp<<8);
temp=((right>>>2)^left)&858993459;
left^=temp;
right^=(temp<<2);
temp=((left>>>16)^right)&65535;
right^=temp;
left^=(temp<<16);
temp=((left>>>4)^right)&252645135;
right^=temp;
left^=(temp<<4);
if(mode==1){
if(_119){
_131=left;
cbcright=right;
}else{
left^=cbcleft2;
right^=cbcright2;
}
}
tempresult+=String.fromCharCode((left>>>24),((left>>>16)&255),((left>>>8)&255),(left&255),(right>>>24),((right>>>16)&255),((right>>>8)&255),(right&255));
_134+=8;
if(_134==512){
result+=tempresult;
tempresult="";
_134=0;
}
}
return result+tempresult;
}
function des_createKeys(key){
pc2bytes0=new Array(0,4,536870912,536870916,65536,65540,536936448,536936452,512,516,536871424,536871428,66048,66052,536936960,536936964);
pc2bytes1=new Array(0,1,1048576,1048577,67108864,67108865,68157440,68157441,256,257,1048832,1048833,67109120,67109121,68157696,68157697);
pc2bytes2=new Array(0,8,2048,2056,16777216,16777224,16779264,16779272,0,8,2048,2056,16777216,16777224,16779264,16779272);
pc2bytes3=new Array(0,2097152,134217728,136314880,8192,2105344,134225920,136323072,131072,2228224,134348800,136445952,139264,2236416,134356992,136454144);
pc2bytes4=new Array(0,262144,16,262160,0,262144,16,262160,4096,266240,4112,266256,4096,266240,4112,266256);
pc2bytes5=new Array(0,1024,32,1056,0,1024,32,1056,33554432,33555456,33554464,33555488,33554432,33555456,33554464,33555488);
pc2bytes6=new Array(0,268435456,524288,268959744,2,268435458,524290,268959746,0,268435456,524288,268959744,2,268435458,524290,268959746);
pc2bytes7=new Array(0,65536,2048,67584,536870912,536936448,536872960,536938496,131072,196608,133120,198656,537001984,537067520,537004032,537069568);
pc2bytes8=new Array(0,262144,0,262144,2,262146,2,262146,33554432,33816576,33554432,33816576,33554434,33816578,33554434,33816578);
pc2bytes9=new Array(0,268435456,8,268435464,0,268435456,8,268435464,1024,268436480,1032,268436488,1024,268436480,1032,268436488);
pc2bytes10=new Array(0,32,0,32,1048576,1048608,1048576,1048608,8192,8224,8192,8224,1056768,1056800,1056768,1056800);
pc2bytes11=new Array(0,16777216,512,16777728,2097152,18874368,2097664,18874880,67108864,83886080,67109376,83886592,69206016,85983232,69206528,85983744);
pc2bytes12=new Array(0,4096,134217728,134221824,524288,528384,134742016,134746112,16,4112,134217744,134221840,524304,528400,134742032,134746128);
pc2bytes13=new Array(0,4,256,260,0,4,256,260,1,5,257,261,1,5,257,261);
var _136=key.length>=24?3:1;
var keys=new Array(32*_136);
var _137=new Array(0,0,1,1,1,1,1,1,0,1,1,1,1,1,1,0);
var _138,righttemp,m=0,n=0,temp;
for(var j=0;j<_136;j++){
left=(key.charCodeAt(m++)<<24)|(key.charCodeAt(m++)<<16)|(key.charCodeAt(m++)<<8)|key.charCodeAt(m++);
right=(key.charCodeAt(m++)<<24)|(key.charCodeAt(m++)<<16)|(key.charCodeAt(m++)<<8)|key.charCodeAt(m++);
temp=((left>>>4)^right)&252645135;
right^=temp;
left^=(temp<<4);
temp=((right>>>-16)^left)&65535;
left^=temp;
right^=(temp<<-16);
temp=((left>>>2)^right)&858993459;
right^=temp;
left^=(temp<<2);
temp=((right>>>-16)^left)&65535;
left^=temp;
right^=(temp<<-16);
temp=((left>>>1)^right)&1431655765;
right^=temp;
left^=(temp<<1);
temp=((right>>>8)^left)&16711935;
left^=temp;
right^=(temp<<8);
temp=((left>>>1)^right)&1431655765;
right^=temp;
left^=(temp<<1);
temp=(left<<8)|((right>>>20)&240);
left=(right<<24)|((right<<8)&16711680)|((right>>>8)&65280)|((right>>>24)&240);
right=temp;
for(i=0;i<_137.length;i++){
if(_137[i]){
left=(left<<2)|(left>>>26);
right=(right<<2)|(right>>>26);
}else{
left=(left<<1)|(left>>>27);
right=(right<<1)|(right>>>27);
}
left&=-15;
right&=-15;
_138=pc2bytes0[left>>>28]|pc2bytes1[(left>>>24)&15]|pc2bytes2[(left>>>20)&15]|pc2bytes3[(left>>>16)&15]|pc2bytes4[(left>>>12)&15]|pc2bytes5[(left>>>8)&15]|pc2bytes6[(left>>>4)&15];
righttemp=pc2bytes7[right>>>28]|pc2bytes8[(right>>>24)&15]|pc2bytes9[(right>>>20)&15]|pc2bytes10[(right>>>16)&15]|pc2bytes11[(right>>>12)&15]|pc2bytes12[(right>>>8)&15]|pc2bytes13[(right>>>4)&15];
temp=((righttemp>>>16)^_138)&65535;
keys[n++]=_138^temp;
keys[n++]=righttemp^(temp<<16);
}
}
return keys;
}
function stringToHex(s){
var r="";
var _141=new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f");
for(var i=0;i<s.length;i++){
r+=_141[s.charCodeAt(i)>>4]+_141[s.charCodeAt(i)&15];
}
return r;
}
dojo.provide("dojo.lang");
dojo.provide("dojo.lang.Lang");
dojo.lang.mixin=function(obj,_142){
var tobj={};
for(var x in _142){
if(typeof tobj[x]=="undefined"){
obj[x]=_142[x];
}
}
return obj;
};
dojo.lang.extend=function(ctor,_145){
this.mixin(ctor.prototype,_145);
};
dojo.lang.extendPrototype=function(obj,_146){
this.extend(obj.constructor,_146);
};
dojo.lang.setTimeout=function(func,_148){
var _149=window,argsStart=2;
if(typeof _148=="function"){
_149=func;
func=_148;
_148=arguments[2];
argsStart++;
}
var args=[];
for(var i=argsStart;i<arguments.length;i++){
args.push(arguments[i]);
}
return setTimeout(function(){
func.apply(_149,args);
},_148);
};
dojo.lang.isObject=function(wh){
return typeof wh=="object"||dojo.lang.isArray(wh)||dojo.lang.isFunction(wh);
};
dojo.lang.isArray=function(wh){
return (wh instanceof Array||typeof wh=="array");
};
dojo.lang.isFunction=function(wh){
return (wh instanceof Function||typeof wh=="function");
};
dojo.lang.isString=function(wh){
return (wh instanceof String||typeof wh=="string");
};
dojo.lang.isNumber=function(wh){
return (wh instanceof Number||typeof wh=="number");
};
dojo.lang.isBoolean=function(wh){
return (wh instanceof Boolean||typeof wh=="boolean");
};
dojo.lang.isUndefined=function(wh){
return ((wh==undefined)&&(typeof wh=="undefined"));
};
dojo.lang.isAlien=function(wh){
return !dojo.lang.isFunction()&&/\{\s*\[native code\]\s*\}/.test(String(wh));
};
dojo.lang.find=function(arr,val,_153){
if(_153){
for(var i=0;i<arr.length;++i){
if(arr[i]===val){
return i;
}
}
}else{
for(var i=0;i<arr.length;++i){
if(arr[i]==val){
return i;
}
}
}
return -1;
};
dojo.lang.inArray=function(arr,val){
if((!arr||arr.constructor!=Array)&&(val&&val.constructor==Array)){
var a=arr;
arr=val;
val=a;
}
return dojo.lang.find(arr,val)>-1;
};
dojo.lang.getNameInObj=function(ns,item){
if(!ns){
ns=dj_global;
}
for(var x in ns){
if(ns[x]===item){
return new String(x);
}
}
return null;
};
dojo.lang.has=function(obj,name){
return (typeof obj[name]!=="undefined");
};
dojo.lang.isEmpty=function(obj){
var tmp={};
var _157=0;
for(var x in obj){
if(obj[x]&&(!tmp[x])){
_157++;
break;
}
}
return (_157==0);
};
dojo.lang.forEach=function(arr,_158,_159){
var il=arr.length;
for(var i=0;i<((_159)?il:arr.length);i++){
if(_158(arr[i])=="break"){
break;
}
}
};
dojo.lang.map=function(arr,obj,_161){
if((typeof obj=="function")&&(!_161)){
_161=obj;
obj=dj_global;
}
for(var i=0;i<arr.length;++i){
_161.call(obj,arr[i]);
}
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
dojo.lang.delayThese=function(farr,cb,_164,_165){
if(!farr.length){
if(typeof _165=="function"){
_165();
}
return;
}
if((typeof _164=="undefined")&&(typeof cb=="number")){
_164=cb;
cb=function(){
};
}else{
if(!cb){
cb=function(){
};
if(!_164){
_164=0;
}
}
}
setTimeout(function(){
(farr.shift())();
cb();
dojo.lang.delayThese(farr,cb,_164,_165);
},_164);
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
dojo.provide("dojo.string");
dojo.require("dojo.lang");
dojo.string.trim=function(_166){
if(arguments.length==0){
_166=this;
}
if(typeof _166!="string"){
return _166;
}
if(!_166.length){
return _166;
}
return _166.replace(/^\s*/,"").replace(/\s*$/,"");
};
dojo.string.paramString=function(str,_168,_169){
if(typeof str!="string"){
_168=str;
_169=_168;
str=this;
}
for(var name in _168){
var re=new RegExp("\\%\\{"+name+"\\}","g");
str=str.replace(re,_168[name]);
}
if(_169){
str=str.replace(/%\{([^\}\s]+)\}/g,"");
}
return str;
};
dojo.string.capitalize=function(str){
if(typeof str!="string"||str==null){
return "";
}
if(arguments.length==0){
str=this;
}
var _171=str.split(" ");
var _172="";
var len=_171.length;
for(var i=0;i<len;i++){
var word=_171[i];
word=word.charAt(0).toUpperCase()+word.substring(1,word.length);
_172+=word;
if(i<len-1){
_172+=" ";
}
}
return new String(_172);
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
var _174=escape(str);
var _175,re=/%u([0-9A-F]{4})/i;
while((_175=_174.match(re))){
var num=Number("0x"+_175[1]);
var _177=escape("&#"+num+";");
ret+=_174.substring(0,_175.index)+_177;
_174=_174.substring(_175.index+_175[0].length);
}
ret+=_174.replace(/\+/g,"%2B");
return ret;
};
dojo.provide("dojo.io.IO");
dojo.require("dojo.string");
dojo.io.transports=[];
dojo.io.hdlrFuncNames=["load","error"];
dojo.io.Request=function(url,_179,_180,_181){
if((arguments.length==1)&&(arguments[0].constructor==Object)){
this.fromKwArgs(arguments[0]);
}else{
this.url=url;
if(arguments.length>=2){
this.mimetype=_179;
}
if(arguments.length>=3){
this.transport=_180;
}
if(arguments.length>=4){
this.changeUrl=_181;
}
}
};
dojo.lang.extend(dojo.io.Request,{url:"",mimetype:"text/plain",method:"GET",content:undefined,transport:undefined,changeUrl:undefined,formNode:undefined,sync:false,bindSuccess:false,useCache:false,load:function(type,data,evt){
},error:function(type,_185){
},fromKwArgs:function(_186){
if(_186["url"]){
_186.url=_186.url.toString();
}
if(!_186["method"]&&_186["formNode"]&&_186["formNode"].method){
_186.method=_186["formNode"].method;
}
if(!_186["handle"]&&_186["handler"]){
_186.handle=_186.handler;
}
if(!_186["load"]&&_186["loaded"]){
_186.load=_186.loaded;
}
if(!_186["changeUrl"]&&_186["changeURL"]){
_186.changeUrl=_186.changeURL;
}
if(!_186["encoding"]){
if(!dojo.lang.isUndefined(djConfig["bindEncoding"])){
_186.encoding=djConfig.bindEncoding;
}else{
_186.encoding="";
}
}
var _187=dojo.lang.isFunction;
for(var x=0;x<dojo.io.hdlrFuncNames.length;x++){
var fn=dojo.io.hdlrFuncNames[x];
if(_187(_186[fn])){
continue;
}
if(_187(_186["handle"])){
_186[fn]=_186.handle;
}
}
dojo.lang.mixin(this,_186);
}});
dojo.io.Error=function(msg,type,num){
this.message=msg;
this.type=type||"unknown";
this.number=num||0;
};
dojo.io.transports.addTransport=function(name){
this.push(name);
this[name]=dojo.io[name];
};
dojo.io.bind=function(_189){
if(!(_189 instanceof dojo.io.Request)){
try{
_189=new dojo.io.Request(_189);
}
catch(e){
dojo.debug(e);
}
}
var _190="";
if(_189["transport"]){
_190=_189["transport"];
if(!this[_190]){
return _189;
}
}else{
for(var x=0;x<dojo.io.transports.length;x++){
var tmp=dojo.io.transports[x];
if((this[tmp])&&(this[tmp].canHandle(_189))){
_190=tmp;
}
}
if(_190==""){
return _189;
}
}
this[_190].bind(_189);
_189.bindSuccess=true;
return _189;
};
dojo.io.argsFromMap=function(map,_192){
var _193=new Object();
var _194="";
var enc=/utf/i.test(_192||"")?encodeURIComponent:dojo.string.encodeAscii;
for(var x in map){
if(!_193[x]){
_194+=enc(x)+"="+enc(map[x])+"&";
}
}
return _194;
};
dojo.provide("dojo.dom");
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
dojo.dom.getTagName=function(node){
var _197=node.tagName;
if(_197.substr(0,5).toLowerCase()!="dojo:"){
if(_197.substr(0,4).toLowerCase()=="dojo"){
return "dojo:"+_197.substring(4).toLowerCase();
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
var _199=node.className||node.getAttribute("class");
if((_199)&&(_199.indexOf("dojo-")!=-1)){
var _200=_199.split(" ");
for(var x=0;x<_200.length;x++){
if((_200[x].length>5)&&(_200[x].indexOf("dojo-")>=0)){
return "dojo:"+_200[x].substr(5);
}
}
}
}
}
return _197.toLowerCase();
};
dojo.dom.getUniqueId=function(){
do{
var id="dj_unique_"+(++arguments.callee._idIncrement);
}while(document.getElementById(id));
return id;
};
dojo.dom.getUniqueId._idIncrement=0;
dojo.dom.getFirstChildElement=function(_202){
var node=_202.firstChild;
while(node&&node.nodeType!=dojo.dom.ELEMENT_NODE){
node=node.nextSibling;
}
return node;
};
dojo.dom.getLastChildElement=function(_203){
var node=_203.lastChild;
while(node&&node.nodeType!=dojo.dom.ELEMENT_NODE){
node=node.previousSibling;
}
return node;
};
dojo.dom.getNextSiblingElement=function(node){
if(!node){
return null;
}
do{
node=node.nextSibling;
}while(node&&node.nodeType!=dojo.dom.ELEMENT_NODE);
return node;
};
dojo.dom.getPreviousSiblingElement=function(node){
if(!node){
return null;
}
do{
node=node.previousSibling;
}while(node&&node.nodeType!=dojo.dom.ELEMENT_NODE);
return node;
};
dojo.dom.moveChildren=function(_204,_205,trim){
var _207=0;
if(trim){
while(_204.hasChildNodes()&&_204.firstChild.nodeType==dojo.dom.TEXT_NODE){
_204.removeChild(_204.firstChild);
}
while(_204.hasChildNodes()&&_204.lastChild.nodeType==dojo.dom.TEXT_NODE){
_204.removeChild(_204.lastChild);
}
}
while(_204.hasChildNodes()){
_205.appendChild(_204.firstChild);
_207++;
}
return _207;
};
dojo.dom.copyChildren=function(_208,_209,trim){
var _210=_208.cloneNode(true);
return this.moveChildren(_210,_209,trim);
};
dojo.dom.removeChildren=function(node){
var _211=node.childNodes.length;
while(node.hasChildNodes()){
node.removeChild(node.firstChild);
}
return _211;
};
dojo.dom.replaceChildren=function(node,_212){
dojo.dom.removeChildren(node);
node.appendChild(_212);
};
dojo.dom.removeNode=function(node){
if(node&&node.parentNode){
node.parentNode.removeChild(node);
}
};
dojo.dom.getAncestors=function(node){
var _213=[];
while(node){
_213.push(node);
node=node.parentNode;
}
return _213;
};
dojo.dom.isDescendantOf=function(node,_214,_215){
if(_215&&node){
node=node.parentNode;
}
while(node){
if(node==_214){
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
dojo.dom.createDocumentFromText=function(str,_216){
if(!_216){
_216="text/xml";
}
if(typeof DOMParser!="undefined"){
var _217=new DOMParser();
return _217.parseFromString(str,_216);
}else{
if(typeof ActiveXObject!="undefined"){
var _218=new ActiveXObject("Microsoft.XMLDOM");
if(_218){
_218.async=false;
_218.loadXML(str);
return _218;
}else{
dojo.debug("toXml didn't work?");
}
}else{
if(document.createElement){
var tmp=document.createElement("xml");
tmp.innerHTML=str;
if(document.implementation&&document.implementation.createDocument){
var _219=document.implementation.createDocument("foo","",null);
for(var i=0;i<tmp.childNodes.length;i++){
_219.importNode(tmp.childNodes.item(i),true);
}
return _219;
}
return tmp.document&&tmp.document.firstChild?tmp.document.firstChild:tmp;
}
}
}
return null;
};
dojo.dom.insertBefore=function(node,ref,_221){
if(_221!=true&&(node===ref||node.nextSibling===ref)){
return false;
}
var _222=ref.parentNode;
_222.insertBefore(node,ref);
return true;
};
dojo.dom.insertAfter=function(node,ref,_223){
var pn=ref.parentNode;
if(ref==pn.lastChild){
if(_223!=true&&node===ref){
return false;
}
pn.appendChild(node);
}else{
return this.insertBefore(node,ref.nextSibling,_223);
}
return true;
};
dojo.dom.insertAtPosition=function(node,ref,_225){
switch(_225.toLowerCase()){
case "before":
dojo.dom.insertBefore(node,ref);
break;
case "after":
dojo.dom.insertAfter(node,ref);
break;
case "first":
if(ref.firstChild){
dojo.dom.insertBefore(node,ref.firstChild);
}else{
ref.appendChild(node);
}
break;
default:
ref.appendChild(node);
break;
}
};
dojo.dom.insertAtIndex=function(node,ref,_226){
var pn=ref.parentNode;
var _227=pn.childNodes;
var _228=false;
for(var i=0;i<_227.length;i++){
if((_227.item(i)["getAttribute"])&&(parseInt(_227.item(i).getAttribute("dojoinsertionindex"))>_226)){
dojo.dom.insertBefore(node,_227.item(i));
_228=true;
break;
}
}
if(!_228){
dojo.dom.insertBefore(node,ref);
}
};
dojo.dom.textContent=function(node,text){
if(text){
dojo.dom.replaceChildren(node,document.createTextNode(text));
return text;
}else{
var _229="";
if(node==null){
return _229;
}
for(var i=0;i<node.childNodes.length;i++){
switch(node.childNodes[i].nodeType){
case 1:
case 5:
_229+=dojo.dom.textContent(node.childNodes[i]);
break;
case 3:
case 2:
case 4:
_229+=node.childNodes[i].nodeValue;
break;
default:
break;
}
}
return _229;
}
};
dojo.dom.collectionToArray=function(_230){
var _231=new Array(_230.length);
for(var i=0;i<_230.length;i++){
_231[i]=_230[i];
}
return _231;
};
dojo.provide("dojo.io.BrowserIO");
dojo.require("dojo.io");
dojo.require("dojo.lang");
dojo.require("dojo.dom");
try{
if((!djConfig.preventBackButtonFix)&&(!dojo.hostenv.post_load_)){
document.write("");
}
}
catch(e){
}
dojo.io.checkChildrenForFile=function(node){
var _232=false;
var _233=node.getElementsByTagName("input");
dojo.lang.forEach(_233,function(_234){
if(_232){
return;
}
if(_234.getAttribute("type")=="file"){
_232=true;
}
});
return _232;
};
dojo.io.formHasFile=function(_235){
return dojo.io.checkChildrenForFile(_235);
};
dojo.io.encodeForm=function(_236,_237){
if((!_236)||(!_236.tagName)||(!_236.tagName.toLowerCase()=="form")){
dj_throw("Attempted to encode a non-form element.");
}
var enc=/utf/i.test(_237||"")?encodeURIComponent:dojo.string.encodeAscii;
var _238=[];
for(var i=0;i<_236.elements.length;i++){
var elm=_236.elements[i];
if(elm.disabled){
continue;
}
var name=enc(elm.name);
var type=elm.type.toLowerCase();
if(type=="select-multiple"){
for(var j=0;j<elm.options.length;j++){
_238.push(name+"="+enc(elm.options[j].value));
}
}else{
if(dojo.lang.inArray(type,["radio","checkbox"])){
if(elm.checked){
_238.push(name+"="+enc(elm.value));
}
}else{
if(!dojo.lang.inArray(type,["file","submit","reset","button"])){
_238.push(name+"="+enc(elm.value));
}
}
}
}
return _238.join("&")+"&";
};
dojo.io.setIFrameSrc=function(_240,src,_241){
try{
var r=dojo.render.html;
if(!_241){
if(r.safari){
_240.location=src;
}else{
frames[_240.name].location=src;
}
}else{
var idoc=(r.moz)?_240.contentWindow:_240;
idoc.location.replace(src);
dojo.debug(_240.contentWindow.location);
}
}
catch(e){
dojo.debug("setIFrameSrc: "+e);
}
};
dojo.io.XMLHTTPTransport=new function(){
var _243=this;
this.initialHref=window.location.href;
this.initialHash=window.location.hash;
this.moveForward=false;
var _244={};
this.useCache=false;
this.historyStack=[];
this.forwardStack=[];
this.historyIframe=null;
this.bookmarkAnchor=null;
this.locationTimer=null;
function getCacheKey(url,_245,_246){
return url+"|"+_245+"|"+_246.toLowerCase();
}
function addToCache(url,_247,_248,http){
_244[getCacheKey(url,_247,_248)]=http;
}
function getFromCache(url,_250,_251){
return _244[getCacheKey(url,_250,_251)];
}
this.clearCache=function(){
_244={};
};
function doLoad(_252,http,url,_253,_254){
if((http.status==200)||(location.protocol=="file:"&&http.status==0)){
var ret;
if(_252.method.toLowerCase()=="head"){
var _255=http.getAllResponseHeaders();
ret={};
ret.toString=function(){
return _255;
};
var _256=_255.split(/[\r\n]+/g);
for(var i=0;i<_256.length;i++){
var pair=_256[i].match(/^([^:]+)\s*:\s*(.+)$/i);
if(pair){
ret[pair[1]]=pair[2];
}
}
}else{
if(_252.mimetype=="text/javascript"){
try{
ret=dj_eval(http.responseText);
}
catch(e){
dojo.debug(e);
ret=false;
}
}else{
if((_252.mimetype=="application/xml")||(_252.mimetype=="text/xml")){
ret=http.responseXML;
if(!ret||typeof ret=="string"){
ret=dojo.dom.createDocumentFromText(http.responseText);
}
}else{
ret=http.responseText;
}
}
}
if(_254){
addToCache(url,_253,_252.method,http);
}
if(typeof _252.load=="function"){
_252.load("load",ret,http);
}
}else{
var _258=new dojo.io.Error("XMLHttpTransport Error: "+http.status+" "+http.statusText);
if(typeof _252.error=="function"){
_252.error("error",_258,http);
}
}
}
function setHeaders(http,_259){
if(_259["headers"]){
for(var _260 in _259["headers"]){
if(_260.toLowerCase()=="content-type"&&!_259["contentType"]){
_259["contentType"]=_259["headers"][_260];
}else{
http.setRequestHeader(_260,_259["headers"][_260]);
}
}
}
}
this.addToHistory=function(args){
var _261=args["back"]||args["backButton"]||args["handle"];
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
var _263=_261;
var lh=null;
var hsl=this.historyStack.length-1;
if(hsl>=0){
while(!this.historyStack[hsl]["urlHash"]){
hsl--;
}
lh=this.historyStack[hsl]["urlHash"];
}
if(lh){
_261=function(){
if(window.location.hash!=""){
setTimeout("window.location.href = '"+lh+"';",1);
}
_263();
};
}
this.forwardStack=[];
var _266=args["forward"]||args["forwardButton"];
var tfw=function(){
if(window.location.hash!=""){
window.location.href=hash;
}
if(_266){
_266();
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
this.historyStack.push({"url":url,"callback":_261,"kwArgs":args,"urlHash":hash});
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
this.iframeLoaded=function(evt,_268){
var isp=_268.href.split("?");
if(isp.length<2){
if(this.historyStack.length==1){
this.handleBackButton();
}
return;
}
var _270=isp[1];
if(this.moveForward){
this.moveForward=false;
return;
}
var last=this.historyStack.pop();
if(!last){
if(this.forwardStack.length>0){
var next=this.forwardStack[this.forwardStack.length-1];
if(_270==next.url.split("?")[1]){
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
doLoad(tif.req,tif.http,tif.url,tif.query,tif.useCache);
this.inFlight.splice(x,1);
if(this.inFlight.length==0){
clearInterval(this.inFlightTimer);
this.inFlightTimer=null;
}
}
}
};
var _274=dojo.hostenv.getXmlhttpObject()?true:false;
this.canHandle=function(_275){
return _274&&dojo.lang.inArray(_275["mimetype"],["text/plain","text/html","application/xml","text/xml","text/javascript"])&&dojo.lang.inArray(_275["method"].toLowerCase(),["post","get","head"])&&!(_275["formNode"]&&dojo.io.formHasFile(_275["formNode"]));
};
this.bind=function(_276){
if(!_276["url"]){
if(!_276["formNode"]&&(_276["backButton"]||_276["back"]||_276["changeUrl"]||_276["watchForURL"])&&(!djConfig.preventBackButtonFix)){
this.addToHistory(_276);
return true;
}
}
var url=_276.url;
var _277="";
if(_276["formNode"]){
var ta=_276.formNode.getAttribute("action");
if((ta)&&(!_276["url"])){
url=ta;
}
var tp=_276.formNode.getAttribute("method");
if((tp)&&(!_276["method"])){
_276.method=tp;
}
_277+=dojo.io.encodeForm(_276.formNode,_276.encoding);
}
if(!_276["method"]){
_276.method="get";
}
if(_276["content"]){
_277+=dojo.io.argsFromMap(_276.content,_276.encoding);
}
if(_276["postContent"]&&_276.method.toLowerCase()=="post"){
_277=_276.postContent;
}
if(_276["backButton"]||_276["back"]||_276["changeUrl"]){
this.addToHistory(_276);
}
var _280=_276["sync"]?false:true;
var _281=_276["useCache"]==true||(this.useCache==true&&_276["useCache"]!=false);
if(_281){
var _282=getFromCache(url,_277,_276.method);
if(_282){
doLoad(_276,_282,url,_277,false);
return;
}
}
var http=dojo.hostenv.getXmlhttpObject();
var _283=false;
if(_280){
this.inFlight.push({"req":_276,"http":http,"url":url,"query":_277,"useCache":_281});
this.startWatchingInFlight();
}
if(_276.method.toLowerCase()=="post"){
http.open("POST",url,_280);
setHeaders(http,_276);
http.setRequestHeader("Content-Type",_276["contentType"]||"application/x-www-form-urlencoded");
http.send(_277);
}else{
var _284=url;
if(_277!=""){
_284+=(url.indexOf("?")>-1?"&":"?")+_277;
}
http.open(_276.method.toUpperCase(),_284,_280);
setHeaders(http,_276);
http.send(null);
}
if(!_280){
doLoad(_276,http,url,_277,_281);
}
return;
};
dojo.io.transports.addTransport("XMLHTTPTransport");
};
dojo.provide("dojo.io.cookie");
dojo.io.cookie.setCookie=function(name,_285,days,path){
var _288=-1;
if(typeof days=="number"&&days>=0){
var d=new Date();
d.setTime(d.getTime()+(days*24*60*60*1000));
_288=d.toGMTString();
}
_285=escape(_285);
document.cookie=name+"="+_285+";"+(_288!=-1?" expires="+_288+";":"")+"path="+(path||"/");
};
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
dojo.io.cookie.deleteCookie=function(name){
dojo.io.cookie.setCookie(name,"-",0);
};
dojo.io.cookie.setObjectCookie=function(name,obj,days,path,_292){
var _293=[],cookie,value="";
if(!_292){
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
_293.push(escape(prop)+"="+escape(cookie[prop]));
}
value=_293.join("&");
}
dojo.io.cookie.setCookie(name,value,days,path);
};
dojo.io.cookie.getObjectCookie=function(name){
var _295=null,cookie=dojo.io.cookie.getCookie(name);
if(cookie){
_295={};
var _296=cookie.split("&");
for(var i=0;i<_296.length;i++){
var pair=_296[i].split("=");
var _297=pair[1];
if(isNaN(_297)){
_297=unescape(pair[1]);
}
_295[unescape(pair[0])]=_297;
}
}
return _295;
};
dojo.io.cookie.isSupported=function(){
if(typeof navigator.cookieEnabled!="boolean"){
dojo.io.cookie.setCookie("__TestingYourBrowserForCookieSupport__","CookiesAllowed",90,null);
var _298=dojo.io.cookie.getCookie("__TestingYourBrowserForCookieSupport__");
navigator.cookieEnabled=(_298=="CookiesAllowed");
if(navigator.cookieEnabled){
this.deleteCookie("__TestingYourBrowserForCookieSupport__");
}
}
return navigator.cookieEnabled;
};
if(!dojo.io.cookies){
dojo.io.cookies=dojo.io.cookie;
}
dojo.hostenv.conditionalLoadModule({common:["dojo.io",false,false],rhino:["dojo.io.RhinoIO",false,false],browser:[["dojo.io.BrowserIO",false,false],["dojo.io.cookie",false,false]]});
dojo.hostenv.moduleLoaded("dojo.io.*");
dojo.provide("turbo.lib.json_rpc");
dojo.require("turbo.lib.json");
dojo.require("turbo.lib.des");
dojo.require("dojo.io.*");
remoteObject=function(_299,_300){
this.method="post";
this.contentType="text/plain; charset=US-ASCII";
this.content="";
this.key="pickapeckofpickledpeppers";
this.crypt=false;
this.encrypt=function(){
this.content="DES"+stringToHex(des(this.key,this.content,true));
};
this.decrypt=function(){
this.content=des(this.key,this.content,false);
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
this.asyncRpc=function(_302){
var self=this;
return dojo.io.bind({url:this.server,method:this.method,contentType:this.contentType,postContent:this.content,load:function(evt,data){
self.content=data;
self.receive();
_302(self.content);
},error:function(){
_302(null);
}});
};
this.rpc=function(_303,_304,_305){
this.content=JSON.stringify({method:_303,arguments:_304,id:new Date().getTime()});
if(this.crypt){
this.encrypt();
}
return (_305?this.asyncRpc(_305):this.syncRpc());
};
this.discover=function(){
var _306=this.rpc("discover");
if(!_306){
alert("Could not contact service provider "+this.server+"");
}else{
if(!_306.error){
this.addMethods(_306.result);
}else{
turbo.debug(_306.error);
}
this.discoverTime=_306.trip;
}
};
this.addMethod=function(_307){
if(!this[_307]){
this[_307]=function(){
var c=arguments.length;
var _308=(c&&(typeof (arguments[c-1])=="function")?arguments[--c]:null);
var args=new Array(c);
for(var i=0;i<c;i++){
args[i]=arguments[i];
}
return this.rpc(_307,args,_308);
};
}
};
this.addMethods=function(_309){
for(var i in _309){
this.addMethod(_309[i]);
}
};
this.server=_299;
if(_300){
this.addMethods(_300);
}else{
this.discover();
}
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
var _310=new dojo.uri.Uri(arguments[i].toString());
var _311=new dojo.uri.Uri(uri.toString());
if(_310.path==""&&_310.scheme==null&&_310.authority==null&&_310.query==null){
if(_310.fragment!=null){
_311.fragment=_310.fragment;
}
_310=_311;
}else{
if(_310.scheme==null){
_310.scheme=_311.scheme;
if(_310.authority==null){
_310.authority=_311.authority;
if(_310.path.charAt(0)!="/"){
var path=_311.path.substring(0,_311.path.lastIndexOf("/")+1)+_310.path;
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
_310.path=segs.join("/");
}
}
}
}
uri="";
if(_310.scheme!=null){
uri+=_310.scheme+":";
}
if(_310.authority!=null){
uri+="//"+_310.authority;
}
uri+=_310.path;
if(_310.query!=null){
uri+="?"+_310.query;
}
if(_310.fragment!=null){
uri+="#"+_310.fragment;
}
}
this.uri=uri.toString();
var _313="^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\\?([^#]*))?(#(.*))?$";
var r=this.uri.match(new RegExp(_313));
this.scheme=r[2]||(r[1]?"":null);
this.authority=r[4]||(r[3]?"":null);
this.path=r[5];
this.query=r[7]||(r[6]?"":null);
this.fragment=r[9]||(r[8]?"":null);
if(this.authority!=null){
_313="^((([^:]+:)?([^@]+))@)?([^:]*)(:([0-9]+))?$";
r=this.authority.match(new RegExp(_313));
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
dojo.provide("dojo.graphics.color");
dojo.require("dojo.lang");
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
this.a=b||1;
}else{
this.r=r;
this.g=g;
this.b=b;
}
}
};
dojo.graphics.color.Color.prototype.toRgb=function(){
return [this.r,this.g,this.b];
};
dojo.graphics.color.Color.prototype.toHex=function(){
return dojo.graphics.color.rgb2hex(this.toRgb());
};
dojo.graphics.color.Color.prototype.toCss=function(){
return "rgb("+this.toRgb().join()+")";
};
dojo.graphics.color.Color.prototype.toString=function(){
return this.toHex();
};
dojo.graphics.color.named={white:[255,255,255],black:[0,0,0],red:[255,0,0],green:[0,255,0],blue:[0,0,255],navy:[0,0,128],gray:[128,128,128],silver:[192,192,192]};
dojo.graphics.color.blend=function(a,b,_317){
if(typeof a=="string"){
return dojo.graphics.color.blendHex(a,b,_317);
}
if(!_317){
_317=0;
}else{
if(_317>1){
_317=1;
}else{
if(_317<-1){
_317=-1;
}
}
}
var c=new Array(3);
for(var i=0;i<3;i++){
var half=Math.abs(a[i]-b[i])/2;
c[i]=Math.floor(Math.min(a[i],b[i])+half+(half*_317));
}
return c;
};
dojo.graphics.color.blendHex=function(a,b,_319){
return dojo.graphics.color.rgb2hex(dojo.graphics.color.blend(dojo.graphics.color.hex2rgb(a),dojo.graphics.color.hex2rgb(b),_319));
};
dojo.graphics.color.extractRGB=function(_320){
var hex="0123456789abcdef";
_320=_320.toLowerCase();
if(_320.indexOf("rgb")==0){
dojo.debug("e1");
var _322=_320.match(/rgba*\((\d+), *(\d+), *(\d+)/i);
var ret=_322.splice(1,3);
return ret;
}else{
var _323=dojo.graphics.color.hex2rgb(_320);
if(_323){
return _323;
}else{
return dojo.graphics.color.named[_320]||[255,255,255];
}
}
};
dojo.graphics.color.hex2rgb=function(hex){
var _324="0123456789ABCDEF";
var rgb=new Array(3);
if(hex.indexOf("#")==0){
hex=hex.substring(1);
}
hex=hex.toUpperCase();
if(hex.replace(new RegExp("["+_324+"]","g"),"")!=""){
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
rgb[i]=_324.indexOf(rgb[i].charAt(0))*16+_324.indexOf(rgb[i].charAt(1));
}
return rgb;
};
dojo.graphics.color.rgb2hex=function(r,g,b){
if(dojo.lang.isArray(r)){
g=r[1]||"00";
b=r[2]||"00";
r=r[0]||"00";
}
r="00"+r.toString(16);
g="00"+g.toString(16);
b="00"+b.toString(16);
return ["#",r.substr(-2,2),g.substr(-2,2),b.substr(-2,2)].join("");
};
dojo.provide("dojo.style");
dojo.require("dojo.dom");
dojo.require("dojo.uri.Uri");
dojo.require("dojo.graphics.color");
dojo.style.boxSizing={marginBox:"margin-box",borderBox:"border-box",paddingBox:"padding-box",contentBox:"content-box"};
dojo.style.getBoxSizing=function(node){
var cm=document["compatMode"];
if(cm=="BackCompat"||cm=="QuirksMode"){
return dojo.style.boxSizing.borderBox;
}else{
if(dojo.render.html.ie){
return dojo.style.boxSizing.contentBox;
}else{
if(arguments.length==0){
node=document.documentElement;
}
var _326=dojo.style.getStyle(node,"-moz-box-sizing");
if(!_326){
_326=dojo.style.getStyle(node,"box-sizing");
}
return (_326?_326:dojo.style.boxSizing.contentBox);
}
}
};
dojo.style.isBorderBox=function(node){
return (dojo.style.getBoxSizing(node)==dojo.style.boxSizing.borderBox);
};
dojo.style.getNumericStyle=function(_327,_328){
var s=dojo.style.getComputedStyle(_327,_328);
if(s==""){
return 0;
}
if(dojo.lang.isUndefined(s)){
return NaN;
}
var _329=s.match(/([\d.]+)([a-z]*)/);
if(!_329||!_329[1]){
return NaN;
}
var n=Number(_329[1]);
return (n==0||_329[2]=="px"?n:NaN);
};
dojo.style.getMarginWidth=function(node){
var left=dojo.style.getNumericStyle(node,"margin-left");
var _331=dojo.style.getNumericStyle(node,"margin-right");
return left+_331;
};
dojo.style.getBorderWidth=function(node){
var left=(dojo.style.getStyle(node,"border-left-style")=="none"?0:dojo.style.getNumericStyle(node,"border-left-width"));
var _332=(dojo.style.getStyle(node,"border-right-style")=="none"?0:dojo.style.getNumericStyle(node,"border-right-width"));
return left+_332;
};
dojo.style.getPaddingWidth=function(node){
var left=(dojo.style.getStyle(node,"padding-left")=="auto"?0:dojo.style.getNumericStyle(node,"padding-left"));
var _333=(dojo.style.getStyle(node,"padding-right")=="auto"?0:dojo.style.getNumericStyle(node,"padding-right"));
return left+_333;
};
dojo.style.getContentWidth=function(node){
return node.offsetWidth-dojo.style.getPaddingWidth(node)-dojo.style.getBorderWidth(node);
};
dojo.style.getInnerWidth=function(node){
return node.offsetWidth;
};
dojo.style.getOuterWidth=function(node){
return dojo.style.getInnerWidth(node)+dojo.style.getMarginWidth(node);
};
dojo.style.setOuterWidth=function(node,_334){
if(!dojo.style.isBorderBox(node)){
_334-=dojo.style.getPaddingWidth(node)+dojo.style.getBorderWidth(node);
}
_334-=dojo.style.getMarginWidth(node);
if(!isNaN(_334)&&_334>0){
node.style.width=_334+"px";
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
var top=dojo.style.getNumericStyle(node,"margin-top");
var _336=dojo.style.getNumericStyle(node,"margin-bottom");
return top+_336;
};
dojo.style.getBorderHeight=function(node){
var top=(dojo.style.getStyle(node,"border-top-style")=="none"?0:dojo.style.getNumericStyle(node,"border-top-width"));
var _337=(dojo.style.getStyle(node,"border-bottom-style")=="none"?0:dojo.style.getNumericStyle(node,"border-bottom-width"));
return top+_337;
};
dojo.style.getPaddingHeight=function(node){
var top=(dojo.style.getStyle(node,"padding-top")=="auto"?0:dojo.style.getNumericStyle(node,"padding-top"));
var _338=(dojo.style.getStyle(node,"padding-bottom")=="auto"?0:dojo.style.getNumericStyle(node,"padding-bottom"));
return top+_338;
};
dojo.style.getContentHeight=function(node){
return node.offsetHeight-dojo.style.getPaddingHeight(node)-dojo.style.getBorderHeight(node);
};
dojo.style.getInnerHeight=function(node){
return node.offsetHeight;
};
dojo.style.getOuterHeight=function(node){
return dojo.style.getInnerHeight(node)+dojo.style.getMarginHeight(node);
};
dojo.style.setOuterHeight=function(node,_339){
if(!dojo.style.isBorderBox(node)){
_339-=dojo.style.getPaddingHeight(node)+dojo.style.getBorderHeight(node);
}
_339-=dojo.style.getMarginHeight(node);
if(!isNaN(_339)&&_339>0){
node.style.height=_339+"px";
return true;
}else{
return false;
}
};
dojo.style.getContentBoxHeight=dojo.style.getContentHeight;
dojo.style.getBorderBoxHeight=dojo.style.getInnerHeight;
dojo.style.getMarginBoxHeight=dojo.style.getOuterHeight;
dojo.style.setMarginBoxHeight=dojo.style.setOuterHeight;
dojo.style.getTotalOffset=function(node,type,_340){
var _341=(type=="top")?"offsetTop":"offsetLeft";
var _342=(type=="top")?"scrollTop":"scrollLeft";
var alt=(type=="top")?"y":"x";
var ret=0;
if(node["offsetParent"]){
if(_340){
ret-=dojo.style.sumAncestorProperties(node,_342);
}
do{
ret+=node[_341];
node=node.offsetParent;
}while(node!=document.getElementsByTagName("body")[0].parentNode&&node!=null);
}else{
if(node[alt]){
ret+=node[alt];
}
}
return ret;
};
dojo.style.sumAncestorProperties=function(node,prop){
if(!node){
return 0;
}
var _344=0;
while(node){
var val=node[prop];
if(val){
_344+=val-0;
}
node=node.parentNode;
}
return _344;
};
dojo.style.totalOffsetLeft=function(node,_345){
return dojo.style.getTotalOffset(node,"left",_345);
};
dojo.style.getAbsoluteX=dojo.style.totalOffsetLeft;
dojo.style.totalOffsetTop=function(node,_346){
return dojo.style.getTotalOffset(node,"top",_346);
};
dojo.style.getAbsoluteY=dojo.style.totalOffsetTop;
dojo.style.styleSheet=null;
dojo.style.insertCssRule=function(_347,_348,_349){
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
_349=dojo.style.styleSheet.cssRules.length;
}else{
if(dojo.style.styleSheet.rules){
_349=dojo.style.styleSheet.rules.length;
}else{
return null;
}
}
}
if(dojo.style.styleSheet.insertRule){
var rule=_347+" { "+_348+" }";
return dojo.style.styleSheet.insertRule(rule,_349);
}else{
if(dojo.style.styleSheet.addRule){
return dojo.style.styleSheet.addRule(_347,_348,_349);
}else{
return null;
}
}
};
dojo.style.removeCssRule=function(_351){
if(!dojo.style.styleSheet){
dojo.debug("no stylesheet defined for removing rules");
return false;
}
if(dojo.render.html.ie){
if(!_351){
_351=dojo.style.styleSheet.rules.length;
dojo.style.styleSheet.removeRule(_351);
}
}else{
if(document.styleSheets[0]){
if(!_351){
_351=dojo.style.styleSheet.cssRules.length;
}
dojo.style.styleSheet.deleteRule(_351);
}
}
return true;
};
dojo.style.insertCssFile=function(URI,doc,_354){
if(!URI){
return;
}
if(!doc){
doc=document;
}
if(doc.baseURI){
URI=new dojo.uri.Uri(doc.baseURI,URI);
}
if(_354&&doc.styleSheets){
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
dojo.style.getBackgroundColor=function(node){
var _358;
do{
_358=dojo.style.getStyle(node,"background-color");
dojo.debug("node:",node,node.tagName,node.id);
dojo.debug("color:",_358);
if(_358.toLowerCase()=="rgba(0, 0, 0, 0)"){
_358="transparent";
}
if(node==document.getElementsByTagName("body")[0]){
node=null;
break;
}
node=node.parentNode;
}while(node&&dojo.lang.inArray(_358,["transparent",""]));
if(_358=="transparent"){
_358=[255,255,255,0];
}else{
_358=dojo.graphics.color.extractRGB(_358);
}
return _358;
};
dojo.style.getComputedStyle=function(_359,_360,_361){
var _362=_361;
if(_359.style.getPropertyValue){
_362=_359.style.getPropertyValue(_360);
}
if(!_362){
if(document.defaultView){
_362=document.defaultView.getComputedStyle(_359,"").getPropertyValue(_360);
}else{
if(_359.currentStyle){
_362=_359.currentStyle[dojo.style.toCamelCase(_360)];
}
}
}
return _362;
};
dojo.style.getStyle=function(_363,_364){
var _365=dojo.style.toCamelCase(_364);
var _366=_363.style[_365];
return (_366?_366:dojo.style.getComputedStyle(_363,_364,_366));
};
dojo.style.toCamelCase=function(_367){
var arr=_367.split("-"),cc=arr[0];
for(var i=1;i<arr.length;i++){
cc+=arr[i].charAt(0).toUpperCase()+arr[i].substring(1);
}
return cc;
};
dojo.style.toSelectorCase=function(_368){
return _368.replace(/([A-Z])/g,"-$1").toLowerCase();
};
dojo.style.setOpacity=function setOpacity(node,_369,_370){
var h=dojo.render.html;
if(!_370){
if(_369>=1){
if(h.ie){
dojo.style.clearOpacity(node);
return;
}else{
_369=0.999999;
}
}else{
if(_369<0){
_369=0;
}
}
}
if(h.ie){
if(node.nodeName.toLowerCase()=="tr"){
var tds=node.getElementsByTagName("td");
for(var x=0;x<tds.length;x++){
tds[x].style.filter="Alpha(Opacity="+_369*100+")";
}
}
node.style.filter="Alpha(Opacity="+_369*100+")";
}else{
if(h.moz){
node.style.opacity=_369;
node.style.MozOpacity=_369;
}else{
if(h.safari){
node.style.opacity=_369;
node.style.KhtmlOpacity=_369;
}else{
node.style.opacity=_369;
}
}
}
};
dojo.style.getOpacity=function getOpacity(node){
if(dojo.render.html.ie){
var opac=(node.filters&&node.filters.alpha&&typeof node.filters.alpha.opacity=="number"?node.filters.alpha.opacity:100)/100;
}else{
var opac=node.style.opacity||node.style.MozOpacity||node.style.KhtmlOpacity||1;
}
return opac>=0.999999?1:Number(opac);
};
dojo.style.clearOpacity=function clearOpacity(node){
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
if(this["dojo"]){
dojo.provide("turbo.turbo");
dojo.require("dojo.style");
}else{
turbo={};
dojo={provide:function(){
},require:function(){
}};
}
turbo.global=this;
turbo.isString=function(v){
return typeof (v)=="string";
};
turbo.isNumber=function(v){
return (typeof (v)=="number")&&isFinite(v);
};
turbo.isFunction=function(v){
return typeof v=="function";
};
turbo.isObject=function(v){
return (v&&typeof v=="object")||turbo.isFunction(v);
};
turbo.isArray=function(v){
return turbo.isObject(v)&&v.constructor==Array;
};
turbo.toInt=function(_375){
var i=parseInt(_375);
return (isNaN(i)?0:i);
};
turbo.cloneArguments=function(_376,_377){
var s=(_377?_377:0);
var l=_376.length;
var _379=new Array(l-s);
for(var i=s,j=0;i<l;i++,j++){
_379[j]=arguments[i];
}
return _379;
};
turbo.bindArgs=function(_380,_381){
var _382=new Array(arguments.length-2);
for(var i=2;i<arguments.length;i++){
_382[i-2]=arguments[i];
}
return function(){
var args=_382.slice(0);
for(var i=0;i<arguments.length;i++){
args.push(arguments[i]);
}
return _381.apply(_380,args);
};
};
turbo.bind=function(_383,_384){
if(_384){
return function(){
return _384.apply(_383,arguments);
};
}else{
dojo.debug("turbo.bind called with null method");
return function(){
};
}
};
turbo.getWindowSize=function(){
if(window.innerWidth){
return {w:window.innerWidth,h:window.innerHeight};
}else{
return {w:document.documentElement.clientWidth,h:document.documentElement.clientHeight};
}
};
turbo.getComputedStyle=function(_385){
if(!_385.style){
return null;
}else{
if(_385.currentStyle){
return _385.currentStyle;
}else{
if(window.getComputedStyle){
return window.getComputedStyle(_385,null);
}else{
return null;
}
}
}
};
turbo.getMargins=function(_386){
with(turbo.getComputedStyle(_386)){
return {w:turbo.toInt(marginLeft)+turbo.toInt(marginRight),h:turbo.toInt(marginTop)+turbo.toInt(marginBottom)};
}
};
turbo.getInnerMargins=function(_387){
with(turbo.getComputedStyle(_387)){
return {w:turbo.toInt(paddingLeft)+turbo.toInt(paddingRight)+turbo.toInt(borderLeftWidth)+turbo.toInt(borderRightWidth),h:turbo.toInt(paddingTop)+turbo.toInt(paddingBottom)+turbo.toInt(borderTopWidth)+turbo.toInt(borderBottomWidth)};
}
};
turbo.getContentMargins=function(_388){
with(turbo.getComputedStyle(_388)){
return {w:turbo.toInt(paddingLeft)+turbo.toInt(paddingRight)+turbo.toInt(borderLeftWidth)+turbo.toInt(borderRightWidth)+turbo.toInt(marginLeft)+turbo.toInt(marginRight),h:turbo.toInt(paddingTop)+turbo.toInt(paddingBottom)+turbo.toInt(borderTopWidth)+turbo.toInt(borderBottomWidth)+turbo.toInt(marginTop)+turbo.toInt(marginBottom)};
}
};
turbo.getContractedSize=function(_389,_390){
return {w:_389.offsetWidth-_390.w,h:_389.offsetHeight-_390.h};
};
turbo.getExpandedSize=function(_391,_392){
return {w:_391.offsetWidth+_392.w,h:_391.offsetHeight+_392.h};
};
turbo.getContentSize=function(_393){
if(!_393||_393==document.body){
return turbo.getWindowSize();
}else{
return turbo.getContractedSize(_393,turbo.getInnerMargins(_393));
}
};
turbo.getInnerSize=function(_394){
if(_394&&_394!=document.body){
return {w:_394.offsetWidth,h:_394.offsetHeight};
}else{
return turbo.getWindowSize();
}
};
turbo.getOuterSize=function(_395){
return turbo.getExpandedSize(_395,turbo.getMargins(_395));
};
turbo.setContentSize=function(_396,inW,inH){
var siz=turbo.getContentSize(_396);
if(inW>0&&inW!=siz.w){
_396.style.width=inW+"px";
}
if(inH>0&&inH!=siz.h){
_396.style.height=inH+"px";
}
};
turbo.setOuterSize=function(_400,inW,inH){
var siz=turbo.getContentMargins(_400);
turbo.setContentSize(_400,inW-siz.w,inH-siz.h);
};
turbo.setBounds=function(_401,inL,inT,inW,inH){
if(_401){
with(_401.style){
if(inL>=0){
left=inL+"px";
}
if(inT>=0){
top=inT+"px";
}
}
dojo.style.setMarginBoxWidth(_401,inW);
dojo.style.setMarginBoxHeight(_401,inH);
}
};
turbo.getParentContentSize=function(_404){
if(_404.parent&&_404.parent!=document.body){
return turbo.getContentSize(_404.parent);
}else{
return turbo.getWindowSize();
}
};
turbo.setStyleWidthPx=function(_405,_406){
if(_405&&_406>0&&_405.offsetWidth!=_406){
_405.style.width=_406+"px";
}
};
turbo.setStyleHeightPx=function(_407,_408){
if(_407&&_408>0&&_407.offsetHeight!=_408){
_407.style.height=_408+"px";
}
};
turbo.getOuterHeightPx=function(_409){
if(_409){
return _409.offsetHeight+turbo.getStyleHeightMargin(_409);
}
};
turbo.getStyleWidthMargin=function(_410){
with(turbo.getComputedStyle(_410)){
return turbo.toInt(borderLeftWidth)+turbo.toInt(borderRightWidth)+turbo.toInt(marginLeft)+turbo.toInt(marginRight)+turbo.toInt(paddingLeft)+turbo.toInt(paddingRight);
}
};
turbo.getStyleHeightMargin=function(_411){
with(turbo.getComputedStyle(_411)){
return turbo.toInt(borderTopWidth)+turbo.toInt(borderBottomWidth)+turbo.toInt(marginTop)+turbo.toInt(marginBottom)+turbo.toInt(paddingTop)+turbo.toInt(paddingBottom)+(_411.nodeName=="TEXTAREA"?32:0);
}
};
turbo.getOuterWidthPx=function(_412){
if(_412){
return _412.offsetWidth+turbo.getStyleWidthMargin(_412);
}
};
turbo.getOuterHeightPx=function(_413){
if(_413){
return _413.offsetHeight+turbo.getStyleHeightMargin(_413);
}
};
turbo.setOuterStyleWidthPx=function(_414,_415){
if(_414){
turbo.setStyleWidthPx(_414,_415-turbo.getStyleWidthMargin(_414));
}
};
turbo.setOuterStyleHeightPx=function(_416,_417){
if(_416){
turbo.setStyleHeightPx(_416,_417-turbo.getStyleHeightMargin(_416));
}
};
turbo.fitNode=function(_418,_419){
_418.style.overflow="hidden";
var _420=0;
if(_419){
var m="";
for(var sib=_418.previousSibling;sib;sib=sib.previousSibling){
if(sib.nodeName=="DIV"){
m+=sib.nodeName+(sib.id?"id: "+sib.id:"")+": "+sib.offsetHeight+" ... ";
_420+=(sib.offsetHeight?sib.offsetHeight:0);
}
}
}else{
for(var sib=_418.previousSibling;sib;sib=sib.previousSibling){
_420+=(sib.nodeName=="DIV"?sib.offsetHeight:0);
}
}
for(var sib=_418.nextSibling;sib;sib=sib.nextSibling){
_420+=(sib.nodeName=="DIV"?sib.offsetHeight:0);
}
var h=turbo.getParentSize(_418).h;
turbo.setOuterStyleHeightPx(_418,h-_420);
};
turbo.autoFitNodes=function(_422){
if(!_422){
_422=document.body;
}
if(_422.getAttribute&&_422.getAttribute("fit")=="fit"){
turbo.fitNode(_422);
}
var s=turbo.getComputedStyle(_422);
if((!s||s.display!="none")&&(!_422.getAttribute||_422.getAttribute("fit")!="nofit")){
for(var i=0;i<_422.childNodes.length;i++){
turbo.autoFitNodes(_422.childNodes[i]);
}
}
};
turbo.capture=function(_423){
if(_423.setCapture){
_423.setCapture();
}else{
document.addEventListener("mousemove",_423.onmousemove,true);
document.addEventListener("mouseup",_423.onmouseup,true);
}
};
turbo.release=function(_424){
if(_424.releaseCapture){
_424.releaseCapture();
}else{
document.removeEventListener("mousemove",_424.onmousemove,true);
document.removeEventListener("mouseup",_424.onmouseup,true);
}
};
turbo.time=function(){
return new Date().getTime();
};
turbo.profile=function(_425,_426){
var t=turbo.time();
if(_426){
_425.call(_426);
}else{
_425();
}
return turbo.time()-t;
};
turbo.themes=new function(){
this.theme="";
this.themeable=new Array();
this.addThemeable=function(_427){
this.themeable.push(_427);
};
this.setTheme=function(_428){
this.theme=_428;
for(var i in this.themeable){
this.themeable[i].setTheme(_428);
}
};
};
turbo.array_swap=function(_429,inI,inJ){
var _432=_429[inI];
_429[inI]=_429[inJ];
_429[inJ]=_432;
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
turbo.swiss=function(_434,_435){
for(var i in _434){
_435[i]=_434[i];
}
return _435;
};
turbo.debugObject=function(_436,_437){
if(_437==undefined){
_437="";
}
if(_437.length>6*5){
dojo.debug(_437+"too deep");
}
for(var name in _436){
var obj=_436[name];
s=_437+"| "+name;
if(obj!=null&&typeof (obj)=="object"){
dojo.debug(s+" = ("+(obj instanceof Array?"array":"object")+")");
turbo.debugObject(obj,_437+"......");
}else{
dojo.debug(s+" = "+obj);
}
}
};
turbo.debug=function(){
var c=arguments.length;
for(var i=0;i<c;i++){
if(turbo.isObject(arguments[i])){
turbo.debugObject(arguments[i]);
}else{
dojo.debug(arguments[i]);
}
}
};
turbo.preloadImage=function(_438){
new Image().src=_438;
};
dojo.provide("turbo.lib.align");
dojo.require("turbo.turbo");
turbo.aligner=new function(){
this.getAlignment=function(_439){
var _440=_439.getAttribute("turboAlign");
if(!_440){
_440=_439.getAttribute("turboalign");
}
return _440;
};
this.visible=function(_441){
var _442=turbo.getComputedStyle(_441);
return (_442.display!="none"&&_442.top!="-4000px");
};
this.listChildrenByAlignment=function(_443,_444){
var _445=[];
var node=_443.firstChild;
while(node){
if(node.nodeType==1&&this.getAlignment(node)==_444&&this.visible(node)){
_445.push(node);
}
node=node.nextSibling;
}
return _445;
};
this.listAlignedChildren=function(_446){
var _447={none:[],top:[],left:[],client:[],right:[],bottom:[]};
var node=_446.firstChild;
while(node){
if(node.nodeType==1&&this.visible(node)){
var _448=this.getAlignment(node);
if(_448){
if(_447[_448]){
_447[_448].push(node);
}else{
_447[_448]=[node];
}
}
}
node=node.nextSibling;
}
return _447;
};
this.normalizeAlignedElement=function(_449){
var s=turbo.getComputedStyle(_449);
var o=s.overflow;
if(o==""||o=="visible"){
_449.style.overflow="hidden";
}
_449.style.position="absolute";
if(s.marginLeft=="auto"){
_449.style.marginLeft="0";
}
if(s.marginRight=="auto"){
_449.style.marginRight="0";
}
if(s.marginTop=="auto"){
_449.style.marginTop="0";
}
if(s.marginBottom=="auto"){
_449.style.marginBottom="0";
}
};
this.alignElement=function(_450,inL,inT,inW,inH){
this.normalizeAlignedElement(_450);
turbo.setBounds(_450,inL,inT,inW,inH);
this.alignChildren(_450);
};
this.alignChildren=function(_451){
var l,r,t,b,w,h,c,aligns;
var siz=turbo.getInnerSize(_451);
var _452=this.listAlignedChildren(_451);
aligns=_452.top;
t=0;
for(var i in aligns){
this.alignElement(aligns[i],-1,t,siz.w);
t+=aligns[i].offsetHeight;
}
aligns=_452.bottom;
b=siz.h;
c=aligns.length;
for(var i=c-1;i>=0;i--){
b-=aligns[i].offsetHeight;
this.alignElement(aligns[i],-1,b,siz.w);
}
h=b-t;
aligns=_452.left;
l=0;
for(var i in aligns){
this.alignElement(aligns[i],l,t,-1,h);
l+=aligns[i].offsetWidth;
}
aligns=_452.right;
r=siz.w;
c=aligns.length;
for(var i=c-1;i>=0;i--){
r-=aligns[i].offsetWidth;
this.alignElement(aligns[i],r,t,-1,h);
}
w=r-l;
aligns=_452.client;
for(var i in aligns){
this.alignElement(aligns[i],l,t,w,h);
break;
}
aligns=_452.none;
for(var i in aligns){
this.alignChildren(aligns[i]);
}
};
this.align=function(){
if(this.lastAlign-new Date().getTime()>0){
return;
}
this.lastAlign=new Date().getTime()+100;
this.alignChildren(document.body);
};
};
dojo.provide("turbo.lib.app");
turbo.app=function(){
var self=this;
this.byId=function(inId){
return document.getElementById(inId);
};
this.marshall=function(){
var id="";
for(var i=0;i<arguments.length;i++){
id=arguments[i];
if(!dj_global[id]){
dj_global[id]=document.getElementById(id);
}
}
return dj_global[id];
};
this.align=function(){
turbo.aligner.align();
};
this.resize=function(){
this.align();
};
this.display=function(){
document.body.style.display="block";
this.resize();
};
this.init=function(){
};
this.initialize=function(){
document.body.style.display="none";
this.init();
window.setTimeout(turbo.bind(this,this.display),1);
dojo.event.connect(window,"onresize",this,"resize");
};
dojo.addOnLoad(this,"initialize");
};
dojo.provide("dojo.xml.Parse");
dojo.require("dojo.dom");
dojo.xml.Parse=function(){
this.parseFragment=function(_454){
var _455={};
var _456=dojo.dom.getTagName(_454);
_455[_456]=new Array(_454.tagName);
var _457=this.parseAttributes(_454);
for(var attr in _457){
if(!_455[attr]){
_455[attr]=[];
}
_455[attr][_455[attr].length]=_457[attr];
}
var _459=_454.childNodes;
for(var _460 in _459){
switch(_459[_460].nodeType){
case dojo.dom.ELEMENT_NODE:
_455[_456].push(this.parseElement(_459[_460]));
break;
case dojo.dom.TEXT_NODE:
if(_459.length==1){
if(!_455[_454.tagName]){
_455[_456]=[];
}
_455[_456].push({value:_459[0].nodeValue});
}
break;
}
}
return _455;
};
this.parseElement=function(node,_461,_462,_463){
var _464={};
var _465=dojo.dom.getTagName(node);
_464[_465]=[];
if((!_462)||(_465.substr(0,4).toLowerCase()=="dojo")){
var _466=this.parseAttributes(node);
for(var attr in _466){
if((!_464[_465][attr])||(typeof _464[_465][attr]!="array")){
_464[_465][attr]=[];
}
_464[_465][attr].push(_466[attr]);
}
}
_464[_465].nodeRef=node;
_464.tagName=_465;
_464.index=_463||0;
var _467=0;
for(var i=0;i<node.childNodes.length;i++){
var tcn=node.childNodes.item(i);
switch(tcn.nodeType){
case dojo.dom.ELEMENT_NODE:
_467++;
var ctn=dojo.dom.getTagName(tcn);
if(!_464[ctn]){
_464[ctn]=[];
}
_464[ctn].push(this.parseElement(tcn,true,_462,_467));
if((tcn.childNodes.length==1)&&(tcn.childNodes.item(0).nodeType==dojo.dom.TEXT_NODE)){
_464[ctn][_464[ctn].length-1].value=tcn.childNodes.item(0).nodeValue;
}
break;
case dojo.dom.TEXT_NODE:
if(node.childNodes.length==1){
_464[_465].push({value:node.childNodes.item(0).nodeValue});
}
break;
default:
break;
}
}
return _464;
};
this.parseAttributes=function(node){
var _470={};
var atts=node.attributes;
for(var i=0;i<atts.length;i++){
var _472=atts.item(i);
if((dojo.render.html.capable)&&(dojo.render.html.ie)){
if(!_472){
continue;
}
if((typeof _472=="object")&&(typeof _472.nodeValue=="undefined")||(_472.nodeValue==null)||(_472.nodeValue=="")){
continue;
}
}
_470[_472.nodeName]={value:_472.nodeValue};
}
return _470;
};
};
dojo.hostenv.conditionalLoadModule({common:["dojo.lang"]});
dojo.hostenv.moduleLoaded("dojo.lang.*");
dojo.require("dojo.lang");
dojo.provide("dojo.event");
dojo.event=new function(){
var _473=0;
this.anon={};
this.nameAnonFunc=function(_474,_475){
var nso=(_475||this.anon);
if((dj_global["djConfig"])&&(djConfig["slowAnonFuncLookups"]==true)){
for(var x in nso){
if(nso[x]===_474){
dojo.debug(x);
return x;
}
}
}
var ret="_"+_473++;
while(typeof nso[ret]!="undefined"){
ret="_"+_473++;
}
nso[ret]=_474;
return ret;
};
this.createFunctionPair=function(obj,cb){
var ret=[];
if(typeof obj=="function"){
ret[1]=dojo.event.nameAnonFunc(obj,dj_global);
ret[0]=dj_global;
return ret;
}else{
if((typeof obj=="object")&&(typeof cb=="string")){
return [obj,cb];
}else{
if((typeof obj=="object")&&(typeof cb=="function")){
ret[1]=dojo.event.nameAnonFunc(cb,obj);
ret[0]=obj;
return ret;
}
}
}
return null;
};
this.matchSignature=function(args,_477){
var end=Math.min(args.length,_477.length);
for(var x=0;x<end;x++){
if(compareTypes){
if((typeof args[x]).toLowerCase()!=(typeof _477[x])){
return false;
}
}else{
if((typeof args[x]).toLowerCase()!=_477[x].toLowerCase()){
return false;
}
}
}
return true;
};
this.matchSignatureSets=function(args){
for(var x=1;x<arguments.length;x++){
if(this.matchSignature(args,arguments[x])){
return true;
}
}
return false;
};
function interpolateArgs(args){
var ao={srcObj:dj_global,srcFunc:null,adviceObj:dj_global,adviceFunc:null,aroundObj:null,aroundFunc:null,adviceType:(args.length>2)?args[0]:"after",precedence:"last",once:false,delay:null};
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
if((typeof args[0]=="object")&&(typeof args[1]=="string")&&(typeof args[2]=="string")){
ao.adviceType="after";
ao.srcObj=args[0];
ao.srcFunc=args[1];
ao.adviceFunc=args[2];
}else{
if((typeof args[1]=="string")&&(typeof args[2]=="string")){
ao.srcFunc=args[1];
ao.adviceFunc=args[2];
}else{
if((typeof args[0]=="object")&&(typeof args[1]=="string")&&(typeof args[2]=="function")){
ao.adviceType="after";
ao.srcObj=args[0];
ao.srcFunc=args[1];
var _479=dojo.event.nameAnonFunc(args[2],ao.adviceObj);
ao.adviceObj[_479]=args[2];
ao.adviceFunc=_479;
}else{
if((typeof args[0]=="function")&&(typeof args[1]=="object")&&(typeof args[2]=="string")){
ao.adviceType="after";
ao.srcObj=dj_global;
var _479=dojo.event.nameAnonFunc(args[0],ao.srcObj);
ao.srcObj[_479]=args[0];
ao.srcFunc=_479;
ao.adviceObj=args[1];
ao.adviceFunc=args[2];
}
}
}
}
break;
case 4:
if((typeof args[0]=="object")&&(typeof args[2]=="object")){
ao.adviceType="after";
ao.srcObj=args[0];
ao.srcFunc=args[1];
ao.adviceObj=args[2];
ao.adviceFunc=args[3];
}else{
if((typeof args[1]).toLowerCase()=="object"){
ao.srcObj=args[1];
ao.srcFunc=args[2];
ao.adviceObj=dj_global;
ao.adviceFunc=args[3];
}else{
if((typeof args[2]).toLowerCase()=="object"){
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
dj_throw("bad srcObj for srcFunc: "+ao.srcFunc);
}
if(!ao.adviceObj){
dj_throw("bad adviceObj for adviceFunc: "+ao.adviceFunc);
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
this.kwConnectImpl_=function(_482,_483){
var fn=(_483)?"disconnect":"connect";
if(typeof _482["srcFunc"]=="function"){
_482.srcObj=_482["srcObj"]||dj_global;
var _484=dojo.event.nameAnonFunc(_482.srcFunc,_482.srcObj);
_482.srcFunc=_484;
}
if(typeof _482["adviceFunc"]=="function"){
_482.adviceObj=_482["adviceObj"]||dj_global;
var _484=dojo.event.nameAnonFunc(_482.adviceFunc,_482.adviceObj);
_482.adviceFunc=_484;
}
return dojo.event[fn]((_482["type"]||_482["adviceType"]||"after"),_482["srcObj"],_482["srcFunc"],_482["adviceObj"]||_482["targetObj"],_482["adviceFunc"]||_482["targetFunc"],_482["aroundObj"],_482["aroundFunc"],_482["once"],_482["delay"]);
};
this.kwConnect=function(_485){
return this.kwConnectImpl_(_485,false);
};
this.disconnect=function(){
var ao=interpolateArgs(arguments);
if(!ao.adviceFunc){
return;
}
var mjp=dojo.event.MethodJoinPoint.getForMethod(ao.srcObj,ao.srcFunc);
return mjp.removeAdvice(ao.adviceObj,ao.adviceFunc,ao.adviceType,ao.once);
};
this.kwDisconnect=function(_486){
return this.kwConnectImpl_(_486,true);
};
};
dojo.event.MethodInvocation=function(_487,obj,args){
this.jp_=_487;
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
dojo.event.MethodJoinPoint=function(obj,_491){
this.object=obj||dj_global;
this.methodname=_491;
this.methodfunc=this.object[_491];
this.before=[];
this.after=[];
this.around=[];
};
dojo.event.MethodJoinPoint.getForMethod=function(obj,_492){
if(!obj){
obj=dj_global;
}
if(!obj[_492]){
obj[_492]=function(){
};
}else{
if((!dojo.lang.isFunction(obj[_492]))&&(!dojo.lang.isAlien(obj[_492]))){
return null;
}
}
var _493=_492+"$joinpoint";
var _494=_492+"$joinpoint$method";
var _495=obj[_493];
if(!_495){
var _496=false;
if(dojo.event["browser"]){
if((obj["attachEvent"])||(obj["nodeType"])||(obj["addEventListener"])){
_496=true;
dojo.event.browser.addClobberNodeAttrs(obj,[_493,_494,_492]);
}
}
obj[_494]=obj[_492];
_495=obj[_493]=new dojo.event.MethodJoinPoint(obj,_494);
obj[_492]=function(){
var args=[];
if((_496)&&(!arguments.length)&&(window.event)){
args.push(dojo.event.browser.fixEvent(window.event));
}else{
for(var x=0;x<arguments.length;x++){
if((x==0)&&(_496)&&(dojo.event.browser.isEvent(arguments[x]))){
args.push(dojo.event.browser.fixEvent(arguments[x]));
}else{
args.push(arguments[x]);
}
}
}
return _495.run.apply(_495,args);
};
}
return _495;
};
dojo.event.MethodJoinPoint.prototype.unintercept=function(){
this.object[this.methodname]=this.methodfunc;
};
dojo.event.MethodJoinPoint.prototype.run=function(){
var obj=this.object||dj_global;
var args=arguments;
var _497=[];
for(var x=0;x<args.length;x++){
_497[x]=args[x];
}
var _498=function(marr){
if(!marr){
dojo.debug("Null argument to unrollAdvice()");
return;
}
var _500=marr[0]||dj_global;
var _501=marr[1];
if(!_500[_501]){
throw new Error("function \""+_501+"\" does not exist on \""+_500+"\"");
}
var _502=marr[2]||dj_global;
var _503=marr[3];
var _504;
var _505=parseInt(marr[4]);
var _506=((!isNaN(_505))&&(marr[4]!==null)&&(typeof marr[4]!="undefined"));
var to={args:[],jp_:this,object:obj,proceed:function(){
return _500[_501].apply(_500,to.args);
}};
to.args=_497;
if(_503){
_502[_503].call(_502,to);
}else{
if((_506)&&((dojo.render.html)||(dojo.render.svg))){
dj_global["setTimeout"](function(){
_500[_501].apply(_500,args);
},_505);
}else{
_500[_501].apply(_500,args);
}
}
};
if(this.before.length>0){
dojo.lang.forEach(this.before,_498,true);
}
var _508;
if(this.around.length>0){
var mi=new dojo.event.MethodInvocation(this,obj,args);
_508=mi.proceed();
}else{
if(this.methodfunc){
_508=this.object[this.methodname].apply(this.object,args);
}
}
if(this.after.length>0){
dojo.lang.forEach(this.after,_498,true);
}
return (this.methodfunc)?_508:null;
};
dojo.event.MethodJoinPoint.prototype.getArr=function(kind){
var arr=this.after;
if((typeof kind=="string")&&(kind.indexOf("before")!=-1)){
arr=this.before;
}else{
if(kind=="around"){
arr=this.around;
}
}
return arr;
};
dojo.event.MethodJoinPoint.prototype.kwAddAdvice=function(args){
this.addAdvice(args["adviceObj"],args["adviceFunc"],args["aroundObj"],args["aroundFunc"],args["adviceType"],args["precedence"],args["once"],args["delay"]);
};
dojo.event.MethodJoinPoint.prototype.addAdvice=function(_511,_512,_513,_514,_515,_516,once,_518){
var arr=this.getArr(_515);
if(!arr){
dj_throw("bad this: "+this);
}
var ao=[_511,_512,_513,_514,_518];
if(once){
if(this.hasAdvice(_511,_512,_515,arr)>=0){
return;
}
}
if(_516=="first"){
arr.unshift(ao);
}else{
arr.push(ao);
}
};
dojo.event.MethodJoinPoint.prototype.hasAdvice=function(_519,_520,_521,arr){
if(!arr){
arr=this.getArr(_521);
}
var ind=-1;
for(var x=0;x<arr.length;x++){
if((arr[x][0]==_519)&&(arr[x][1]==_520)){
ind=x;
}
}
return ind;
};
dojo.event.MethodJoinPoint.prototype.removeAdvice=function(_523,_524,_525,once){
var arr=this.getArr(_525);
var ind=this.hasAdvice(_523,_524,_525,arr);
if(ind==-1){
return false;
}
while(ind!=-1){
arr.splice(ind,1);
if(once){
break;
}
ind=this.hasAdvice(_523,_524,_525,arr);
}
return true;
};
dojo.provide("dojo.widget.Manager");
dojo.require("dojo.lang.*");
dojo.require("dojo.event");
dojo.widget.manager=new function(){
this.widgets=[];
this.widgetIds=[];
this.root=null;
var _526={};
this.getUniqueId=function(_527){
return _527+"_"+(_526[_527]!=undefined?++_526[_527]:_526[_527]=0);
};
this.add=function(_528){
this.widgets.push(_528);
if(_528.widgetId==""){
if(_528["id"]){
_528.widgetId=_528["id"];
}else{
if(_528.extraArgs["id"]){
_528.widgetId=_528.extraArgs["id"];
}else{
_528.widgetId=this.getUniqueId(_528.widgetType);
}
}
}
if(this.widgetIds[_528.widgetId]){
dojo.debug("widget ID collision on ID: "+_528.widgetId);
}
this.widgetIds[_528.widgetId]=_528;
var _529=this;
dojo.event.connect(_528,"destroy",function(){
_529.removeById(_528.widgetId);
});
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
this.remove=function(_530){
var tw=this.widgets[_530].widgetId;
delete this.widgetIds[tw];
this.widgets.splice(_530,1);
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
this.getWidgetsByFilter=function(_533){
var ret=[];
dojo.lang.forEach(this.widgets,function(x){
if(_533(x)){
ret.push(x);
}
});
return ret;
};
var _534=[];
var _535=["dojo.widget","dojo.webui.widgets"];
for(var i=0;i<_535.length;i++){
_535[_535[i]]=true;
}
this.registerWidgetPackage=function(_536){
if(!_535[_536]){
_535[_536]=true;
_535.push(_536);
}
};
this.getImplementation=function(_537,_538,_539){
var impl=this.getImplementationName(_537);
if(impl){
return new impl(_538);
}
};
this.getImplementationName=function(_541){
var _542=_541.toLowerCase();
var impl=_534[_542];
if(impl){
return impl;
}
var _543=[];
for(var _544 in dojo.render){
if(dojo.render[_544]["capable"]===true){
var _545=dojo.render[_544].prefixes;
for(var i=0;i<_545.length;i++){
_543.push(_545[i].toLowerCase());
}
}
}
for(var i=0;i<_535.length;i++){
var _546=dojo.evalObjPath(_535[i]);
if(!_546){
continue;
}
for(var j=0;j<_543.length;j++){
if(!_546[_543[j]]){
continue;
}
for(var _547 in _546[_543[j]]){
if(_547.toLowerCase()!=_542){
continue;
}
_534[_542]=_546[_543[j]][_547];
return _534[_542];
}
}
for(var j=0;j<_543.length;j++){
for(var _547 in _546){
if(_547.toLowerCase()!=(_543[j]+_542)){
continue;
}
_534[_542]=_546[_547];
return _534[_542];
}
}
}
throw new Error("Could not locate \""+_541+"\" class");
};
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
dojo.provide("dojo.event.topic");
dojo.require("dojo.event");
dojo.event.topic=new function(){
this.topics={};
this.getTopic=function(_548){
if(!this.topics[_548]){
this.topics[_548]=new this.TopicImpl(_548);
}
return this.topics[_548];
};
this.registerPublisher=function(_549,obj,_550){
var _549=this.getTopic(_549);
_549.registerPublisher(obj,_550);
};
this.subscribe=function(_551,obj,_552){
var _551=this.getTopic(_551);
_551.subscribe(obj,_552);
};
this.unsubscribe=function(_553,obj,_554){
var _553=this.getTopic(_553);
_553.unsubscribe(obj,_554);
};
this.publish=function(_555,_556){
var _555=this.getTopic(_555);
var args=[];
if((arguments.length==2)&&(_556.length)&&(typeof _556!="string")){
args=_556;
}else{
var args=[];
for(var x=1;x<arguments.length;x++){
args.push(arguments[x]);
}
}
_555.sendMessage.apply(_555,args);
};
};
dojo.event.topic.TopicImpl=function(_557){
this.topicName=_557;
var self=this;
self.subscribe=function(_558,_559){
dojo.event.connect("before",self,"sendMessage",_558,_559);
};
self.unsubscribe=function(_560,_561){
dojo.event.disconnect("before",self,"sendMessage",_560,_561);
};
self.registerPublisher=function(_562,_563){
dojo.event.connect(_562,_563,self,"sendMessage");
};
self.sendMessage=function(_564){
};
};
dojo.provide("dojo.event.browser");
dojo.require("dojo.event");
dojo_ie_clobber=new function(){
this.clobberArr=["data","onload","onmousedown","onmouseup","onmouseover","onmouseout","onmousemove","onclick","ondblclick","onfocus","onblur","onkeypress","onkeydown","onkeyup","onsubmit","onreset","onselect","onchange","onselectstart","ondragstart","oncontextmenu"];
this.exclusions=[];
this.clobberList={};
this.clobberNodes=[];
this.addClobberAttr=function(type){
if(dojo.render.html.ie){
if(this.clobberList[type]!="set"){
this.clobberArr.push(type);
this.clobberList[type]="set";
}
}
};
this.addExclusionID=function(id){
this.exclusions.push(id);
};
if(dojo.render.html.ie){
for(var x=0;x<this.clobberArr.length;x++){
this.clobberList[this.clobberArr[x]]="set";
}
}
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
}
this.clobber=function(_565){
for(var x=0;x<this.exclusions.length;x++){
try{
var tn=document.getElementById(this.exclusions[x]);
tn.parentNode.removeChild(tn);
}
catch(e){
}
}
var na;
var tna;
if(_565){
tna=_565.getElementsByTagName("*");
na=[_565];
for(var x=0;x<tna.length;x++){
if(!djConfig.ieClobberMinimal){
na.push(tna[x]);
}else{
if(tna[x]["__doClobber__"]){
na.push(tna[x]);
}
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
var _569={};
for(var i=na.length-1;i>=0;i=i-1){
var el=na[i];
if(djConfig.ieClobberMinimal){
if(el["__clobberAttrs__"]){
for(var j=0;j<el.__clobberAttrs__.length;j++){
nukeProp(el,el.__clobberAttrs__[j]);
}
nukeProp(el,"__clobberAttrs__");
nukeProp(el,"__doClobber__");
}
}else{
for(var p=this.clobberArr.length-1;p>=0;p=p-1){
var ta=this.clobberArr[p];
nukeProp(el,ta);
}
}
}
na=null;
};
};
if((dojo.render.html.ie)&&((!dojo.hostenv.ie_prevent_clobber_)||(djConfig.ieClobberMinimal))){
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
var _572=0;
this.clean=function(node){
if(dojo.render.html.ie){
dojo_ie_clobber.clobber(node);
}
};
this.addClobberAttr=function(type){
dojo_ie_clobber.addClobberAttr(type);
};
this.addClobberAttrs=function(){
for(var x=0;x<arguments.length;x++){
this.addClobberAttr(arguments[x]);
}
};
this.addClobberNode=function(node){
if(djConfig.ieClobberMinimal){
if(!node.__doClobber__){
node.__doClobber__=true;
dojo_ie_clobber.clobberNodes.push(node);
node.__clobberAttrs__=[];
}
}
};
this.addClobberNodeAttrs=function(node,_573){
this.addClobberNode(node);
if(djConfig.ieClobberMinimal){
for(var x=0;x<_573.length;x++){
node.__clobberAttrs__.push(_573[x]);
}
}else{
this.addClobberAttrs.apply(this,_573);
}
};
this.removeListener=function(node,_574,fp,_575){
if(!_575){
var _575=false;
}
_574=_574.toLowerCase();
if(_574.substr(0,2)=="on"){
_574=_574.substr(2);
}
if(node.removeEventListener){
node.removeEventListener(_574,fp,_575);
}
};
this.addListener=function(node,_576,fp,_577){
if(!_577){
var _577=false;
}
_576=_576.toLowerCase();
if(_576.substr(0,2)=="on"){
_576=_576.substr(2);
}
if(!node){
return;
}
var _578=function(evt){
if(!evt){
evt=window.event;
}
var ret=fp(dojo.event.browser.fixEvent(evt));
if(_577){
dojo.event.browser.stopEvent(evt);
}
return ret;
};
var _579="on"+_576;
if(node.addEventListener){
node.addEventListener(_576,_578,_577);
return _578;
}else{
if(typeof node[_579]=="function"){
var _580=node[_579];
node[_579]=function(e){
_580(e);
_578(e);
};
}else{
node[_579]=_578;
}
if(dojo.render.html.ie){
this.addClobberNodeAttrs(node,[_579]);
}
return _578;
}
};
this.isEvent=function(obj){
return (typeof Event!="undefined")&&(obj.eventPhase);
};
this.fixEvent=function(evt){
if(evt.type&&evt.type.indexOf("key")==0){
var keys={KEY_BACKSPACE:8,KEY_TAB:9,KEY_ENTER:13,KEY_SHIFT:16,KEY_CTRL:17,KEY_ALT:18,KEY_PAUSE:19,KEY_CAPS_LOCK:20,KEY_ESCAPE:27,KEY_SPACE:32,KEY_PAGE_UP:33,KEY_PAGE_DOWN:34,KEY_END:35,KEY_HOME:36,KEY_LEFT_ARROW:37,KEY_UP_ARROW:38,KEY_RIGHT_ARROW:39,KEY_DOWN_ARROW:40,KEY_INSERT:45,KEY_DELETE:46,KEY_LEFT_WINDOW:91,KEY_RIGHT_WINDOW:92,KEY_SELECT:93,KEY_F1:112,KEY_F2:113,KEY_F3:114,KEY_F4:115,KEY_F5:116,KEY_F6:117,KEY_F7:118,KEY_F8:119,KEY_F9:120,KEY_F10:121,KEY_F11:122,KEY_F12:123,KEY_NUM_LOCK:144,KEY_SCROLL_LOCK:145};
evt.keys=[];
for(var key in keys){
evt[key]=keys[key];
evt.keys[keys[key]]=key;
}
if(dojo.render.html.ie&&evt.type=="keypress"){
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
evt.callListener=function(_581,_582){
if(typeof _581!="function"){
dj_throw("listener not a function: "+_581);
}
evt.currentTarget=_582;
var ret=_581.call(_582,evt);
return ret;
};
evt.stopPropagation=function(){
evt.cancelBubble=true;
};
evt.preventDefault=function(){
evt.returnValue=false;
};
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
dojo.hostenv.conditionalLoadModule({common:["dojo.event","dojo.event.topic"],browser:["dojo.event.browser"]});
dojo.hostenv.moduleLoaded("dojo.event.*");
dojo.provide("dojo.widget.Widget");
dojo.provide("dojo.widget.tags");
dojo.require("dojo.lang.*");
dojo.require("dojo.widget.Manager");
dojo.require("dojo.event.*");
dojo.require("dojo.string");
dojo.widget.Widget=function(){
this.children=[];
this.rightClickItems=[];
this.extraArgs={};
};
dojo.lang.extend(dojo.widget.Widget,{parent:null,isTopLevel:false,isModal:false,isEnabled:true,isHidden:false,isContainer:false,widgetId:"",widgetType:"Widget",enable:function(){
this.isEnabled=true;
},disable:function(){
this.isEnabled=false;
},hide:function(){
this.isHidden=true;
},show:function(){
this.isHidden=false;
},create:function(args,_584,_585){
this.satisfyPropertySets(args,_584,_585);
this.mixInProperties(args,_584,_585);
dojo.widget.manager.add(this);
this.buildRendering(args,_584,_585);
this.initialize(args,_584,_585);
this.postInitialize(args,_584,_585);
this.postCreate(args,_584,_585);
return this;
},destroy:function(_586){
this.uninitialize();
this.destroyRendering(_586);
dojo.widget.manager.removeById(this.widgetId);
},destroyChildren:function(_587){
_587=(!_587)?function(){
return true;
}:_587;
for(var x=0;x<this.children.length;x++){
var tc=this.children[x];
if((tc)&&(_587(tc))){
tc.destroy();
}
}
},destroyChildrenOfType:function(type){
type=type.toLowerCase();
this.destroyChildren(function(item){
if(item.widgetType.toLowerCase()==type){
return true;
}else{
return false;
}
});
},satisfyPropertySets:function(args){
var _589=[];
var _590=[];
for(var x=0;x<_589.length;x++){
}
for(var x=0;x<_590.length;x++){
}
return args;
},mixInProperties:function(args,frag){
if((args["fastMixIn"])||(frag["fastMixIn"])){
for(var x in args){
this[x]=args[x];
}
return;
}
var _591;
var _592;
if(this.constructor.prototype["lcArgs"]){
_592=this.constructor.prototype.lcArgs;
}else{
_592={};
for(var y in this){
_592[((new String(y)).toLowerCase())]=y;
}
this.constructor.prototype.lcArgs=_592;
}
var _594={};
for(var x in args){
if(this[x]){
_594[x]=args[x];
}else{
var y=_592[(new String(x)).toLowerCase()];
_594[(y?y:x)]=args[x];
}
}
args=_594;
for(var x in args){
if((typeof this[x])!=(typeof _591)){
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
var tn=dojo.event.nameAnonFunc(new Function(args[x]),this);
dojo.event.connect(this,x,this,tn);
}else{
if(this[x].constructor==Array){
this[x]=args[x].split(";");
}else{
if(this[x] instanceof Date){
this[x]=new Date(Number(args[x]));
}else{
if(typeof this[x]=="object"){
var _595=args[x].split(";");
for(var y=0;y<_595.length;y++){
var si=_595[y].indexOf(":");
if((si!=-1)&&(_595[y].length>si)){
this[x][dojo.string.trim(_595[y].substr(0,si))]=_595[y].substr(si+1);
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
},initialize:function(args,frag){
return false;
},postInitialize:function(args,frag){
return false;
},postCreate:function(args,frag){
return false;
},uninitialize:function(){
return false;
},buildRendering:function(){
dj_unimplemented("dojo.widget.Widget.buildRendering");
return false;
},destroyRendering:function(){
dj_unimplemented("dojo.widget.Widget.destroyRendering");
return false;
},cleanUp:function(){
dj_unimplemented("dojo.widget.Widget.cleanUp");
return false;
},addedTo:function(_597){
},addChild:function(_598){
dj_unimplemented("dojo.widget.Widget.addChild");
return false;
},addChildAtIndex:function(_599,_600){
dj_unimplemented("dojo.widget.Widget.addChildAtIndex");
return false;
},removeChild:function(_601){
dj_unimplemented("dojo.widget.Widget.removeChild");
return false;
},removeChildAtIndex:function(_602){
dj_unimplemented("dojo.widget.Widget.removeChildAtIndex");
return false;
},resize:function(_603,_604){
this.setWidth(_603);
this.setHeight(_604);
},setWidth:function(_605){
if((typeof _605=="string")&&(_605.substr(-1)=="%")){
this.setPercentageWidth(_605);
}else{
this.setNativeWidth(_605);
}
},setHeight:function(_606){
if((typeof _606=="string")&&(_606.substr(-1)=="%")){
this.setPercentageHeight(_606);
}else{
this.setNativeHeight(_606);
}
},setPercentageHeight:function(_607){
return false;
},setNativeHeight:function(_608){
return false;
},setPercentageWidth:function(_609){
return false;
},setNativeWidth:function(_610){
return false;
}});
dojo.widget.tags={};
dojo.widget.tags.addParseTreeHandler=function(type){
var _611=type.toLowerCase();
this[_611]=function(_612,_613,_614,_615){
return dojo.widget.buildWidgetFromParseTree(_611,_612,_613,_614,_615);
};
};
dojo.widget.tags.addParseTreeHandler("dojo:widget");
dojo.widget.tags["dojo:propertyset"]=function(_616,_617,_618){
var _619=_617.parseProperties(_616["dojo:propertyset"]);
};
dojo.widget.tags["dojo:connect"]=function(_620,_621,_622){
var _623=_621.parseProperties(_620["dojo:connect"]);
};
dojo.widget.buildWidgetFromParseTree=function(type,frag,_624,_625,_626){
var _627=type.split(":");
_627=(_627.length==2)?_627[1]:type;
var _628=_624.getPropertySets(frag);
var _629=_624.parseProperties(frag["dojo:"+_627]);
for(var x=0;x<_628.length;x++){
}
var _630=dojo.widget.manager.getImplementation(_627);
if(!_630){
throw new Error("cannot find \""+_627+"\" widget");
}else{
if(!_630.create){
throw new Error("\""+_627+"\" widget object does not appear to implement *Widget");
}
}
_629["dojoinsertionindex"]=_626;
return _630.create(_629,frag,_625);
};
dojo.provide("dojo.widget.Parse");
dojo.require("dojo.widget.Manager");
dojo.require("dojo.string");
dojo.require("dojo.dom");
dojo.widget.Parse=function(_631){
this.propertySetsList=[];
this.fragment=_631;
this.createComponents=function(_631,_632){
var _633=dojo.widget.tags;
var _634=[];
for(var item in _631){
var _635=false;
try{
if(_631[item]&&(_631[item]["tagName"])&&(_631[item]!=_631["nodeRef"])){
var tn=new String(_631[item]["tagName"]);
var tna=tn.split(";");
for(var x=0;x<tna.length;x++){
var ltn=dojo.string.trim(tna[x]).toLowerCase();
if(_633[ltn]){
_635=true;
_631[item].tagName=ltn;
_634.push(_633[ltn](_631[item],this,_632,_631[item]["index"]));
}else{
if(ltn.substr(0,5)=="dojo:"){
dojo.debug("no tag handler registed for type: ",ltn);
}
}
}
}
}
catch(e){
dojo.debug(e);
}
if((!_635)&&(typeof _631[item]=="object")&&(_631[item]!=_631.nodeRef)&&(_631[item]!=_631["tagName"])){
_634.push(this.createComponents(_631[item],_632));
}
}
return _634;
};
this.parsePropertySets=function(_637){
return [];
var _638=[];
for(var item in _637){
if((_637[item]["tagName"]=="dojo:propertyset")){
_638.push(_637[item]);
}
}
this.propertySetsList.push(_638);
return _638;
};
this.parseProperties=function(_639){
var _640={};
for(var item in _639){
if((_639[item]==_639["tagName"])||(_639[item]==_639.nodeRef)){
}else{
if((_639[item]["tagName"])&&(dojo.widget.tags[_639[item].tagName.toLowerCase()])){
}else{
if((_639[item][0])&&(_639[item][0].value!="")){
try{
if(item.toLowerCase()=="dataprovider"){
var _641=this;
this.getDataProvider(_641,_639[item][0].value);
_640.dataProvider=this.dataProvider;
}
_640[item]=_639[item][0].value;
var _642=this.parseProperties(_639[item]);
for(var _643 in _642){
_640[_643]=_642[_643];
}
}
catch(e){
dj_debug(e);
}
}
}
}
}
return _640;
};
this.getDataProvider=function(_644,_645){
dojo.io.bind({url:_645,load:function(type,_646){
if(type=="load"){
_644.dataProvider=_646;
}
},mimetype:"text/javascript",sync:true});
};
this.getPropertySetById=function(_647){
for(var x=0;x<this.propertySetsList.length;x++){
if(_647==this.propertySetsList[x]["id"][0].value){
return this.propertySetsList[x];
}
}
return "";
};
this.getPropertySetsByType=function(_648){
var _649=[];
for(var x=0;x<this.propertySetsList.length;x++){
var cpl=this.propertySetsList[x];
var cpcc=cpl["componentClass"]||cpl["componentType"]||null;
if((cpcc)&&(propertySetId==cpcc[0].value)){
_649.push(cpl);
}
}
return _649;
};
this.getPropertySets=function(_652){
var ppl="dojo:propertyproviderlist";
var _654=[];
var _655=_652["tagName"];
if(_652[ppl]){
var _656=_652[ppl].value.split(" ");
for(propertySetId in _656){
if((propertySetId.indexOf("..")==-1)&&(propertySetId.indexOf("://")==-1)){
var _657=this.getPropertySetById(propertySetId);
if(_657!=""){
_654.push(_657);
}
}else{
}
}
}
return (this.getPropertySetsByType(_655)).concat(_654);
};
this.createComponentFromScript=function(_658,_659,_660,_661){
var frag={};
var _662="dojo:"+_659.toLowerCase();
frag[_662]={};
var bo={};
for(prop in _660){
if(typeof bo[prop]=="undefined"){
frag[_662][prop.toLowerCase()]=[{value:_660[prop]}];
}
}
frag[_662]["dojotype"]=[{value:_659}];
frag[_662].nodeRef=_658;
frag.tagName=_662;
var _664=[frag];
if(_661){
_664[0].fastMixIn=true;
}
return this.createComponents(_664);
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
dojo.widget.fromScript=function(name,_665,_666,_667){
if((typeof name!="string")&&(typeof _665=="string")){
return dojo.widget._oldFromScript(name,_665,_666);
}
_665=_665||{};
var _668=false;
var tn=null;
var h=dojo.render.html.capable;
if(h){
tn=document.createElement("span");
}
if(!_666){
_668=true;
_666=tn;
if(h){
dojo.html.body().appendChild(_666);
}
}else{
if(_667){
dojo.dom.insertAtPosition(tn,_666,_667);
}else{
tn=_666;
}
}
var _669=dojo.widget._oldFromScript(tn,name,_665);
if(!_669[0]||typeof _669[0].widgetType=="undefined"){
throw new Error("Creation of \""+name+"\" widget fromScript failed.");
}
if(_668){
if(_669[0].domNode.parentNode){
_669[0].domNode.parentNode.removeChild(_669[0].domNode);
}
}
return _669[0];
};
dojo.widget._oldFromScript=function(_670,name,_671){
var ln=name.toLowerCase();
var tn="dojo:"+ln;
_671[tn]={dojotype:[{value:ln}],nodeRef:_670,fastMixIn:true};
var ret=dojo.widget.getParser().createComponentFromScript(_670,name,_671,true);
return ret;
};
dojo.provide("dojo.html");
dojo.require("dojo.dom");
dojo.require("dojo.style");
dojo.require("dojo.string");
dojo.lang.mixin(dojo.html,dojo.dom);
dojo.lang.mixin(dojo.html,dojo.style);
dojo.html.clearSelection=function(){
try{
if(window.getSelection){
window.getSelection().removeAllRanges();
}else{
if(document.selection&&document.selection.clear){
document.selection.clear();
}
}
}
catch(e){
dojo.debug(e);
}
};
dojo.html.disableSelection=function(_673){
if(arguments.length==0){
_673=this.body();
}
if(dojo.render.html.mozilla){
_673.style.MozUserSelect="none";
}else{
if(dojo.render.html.safari){
_673.style.KhtmlUserSelect="none";
}else{
if(dojo.render.html.ie){
_673.unselectable="on";
}
}
}
};
dojo.html.enableSelection=function(_674){
if(arguments.length==0){
_674=this.body();
}
if(dojo.render.html.mozilla){
_674.style.MozUserSelect="";
}else{
if(dojo.render.html.safari){
_674.style.KhtmlUserSelect="";
}else{
if(dojo.render.html.ie){
_674.unselectable="off";
}
}
}
};
dojo.html.selectElement=function(_675){
if(document.selection&&this.body().createTextRange){
var _676=this.body().createTextRange();
_676.moveToElementText(_675);
_676.select();
}else{
if(window.getSelection){
var _677=window.getSelection();
if(_677.selectAllChildren){
_677.selectAllChildren(_675);
}
}
}
};
dojo.html.isSelectionCollapsed=function(){
if(document.selection){
return document.selection.createRange().text=="";
}else{
if(window.getSelection){
var _678=window.getSelection();
if(dojo.lang.isString(_678)){
return _678=="";
}else{
return _678.isCollapsed;
}
}
}
};
dojo.html.getEventTarget=function(evt){
if((window["event"])&&(window.event["srcElement"])){
return window.event.srcElement;
}else{
if((evt)&&(evt.target)){
return evt.target;
}
}
};
dojo.html.getScrollTop=function(){
return document.documentElement.scrollTop||this.body().scrollTop||0;
};
dojo.html.getScrollLeft=function(){
return document.documentElement.scrollLeft||this.body().scrollLeft||0;
};
dojo.html.getParentOfType=function(node,type){
var _679=node;
type=type.toLowerCase();
while(_679.nodeName.toLowerCase()!=type){
if((!_679)||(_679==(document["body"]||document["documentElement"]))){
return null;
}
_679=_679.parentNode;
}
return _679;
};
dojo.html.getAttribute=function(node,attr){
if((!node)||(!node.getAttribute)){
return null;
}
var ta=typeof attr=="string"?attr:new String(attr);
var v=node.getAttribute(ta.toUpperCase());
if((v)&&(typeof v=="string")&&(v!="")){
return v;
}
if(v&&typeof v=="object"&&v.value){
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
var v=this.getAttribute(node,attr);
return v?true:false;
};
dojo.html.getClass=function(node){
if(node.className){
return node.className;
}else{
if(dojo.html.hasAttribute(node,"class")){
return dojo.html.getAttribute(node,"class");
}
}
return "";
};
dojo.html.hasClass=function(node,_680){
var _681=dojo.html.getClass(node).split(/\s+/g);
for(var x=0;x<_681.length;x++){
if(_680==_681[x]){
return true;
}
}
return false;
};
dojo.html.prependClass=function(node,_682){
if(!node){
return null;
}
if(dojo.html.hasAttribute(node,"class")||node.className){
_682+=" "+(node.className||dojo.html.getAttribute(node,"class"));
}
return dojo.html.setClass(node,_682);
};
dojo.html.addClass=function(node,_683){
if(!node){
throw new Error("addClass: node does not exist");
}
if(dojo.html.hasAttribute(node,"class")||node.className){
_683=(node.className||dojo.html.getAttribute(node,"class"))+" "+_683;
}
return dojo.html.setClass(node,_683);
};
dojo.html.setClass=function(node,_684){
if(!node){
return false;
}
var cs=new String(_684);
try{
if(typeof node.className=="string"){
node.className=cs;
}else{
if(node.setAttribute){
node.setAttribute("class",_684);
node.className=cs;
}else{
return false;
}
}
}
catch(e){
dojo.debug("__util__.setClass() failed",e);
}
return true;
};
dojo.html.removeClass=function(node,_686){
if(!node){
return false;
}
var _686=dojo.string.trim(new String(_686));
try{
var cs=String(node.className).split(" ");
var nca=[];
for(var i=0;i<cs.length;i++){
if(cs[i]!=_686){
nca.push(cs[i]);
}
}
node.className=nca.join(" ");
}
catch(e){
dojo.debug("__util__.removeClass() failed",e);
}
return true;
};
dojo.html.classMatchType={ContainsAll:0,ContainsAny:1,IsOnly:2};
dojo.html.getElementsByClass=function(_688,_689,_690,_691){
if(!_689){
_689=document;
}
var _692=_688.split(/\s+/g);
var _693=[];
if(_691!=1&&_691!=2){
_691=0;
}
if(false&&document.evaluate){
var _694="//"+(_690||"*")+"[contains(";
if(_691!=dojo.html.classMatchType.ContainsAny){
_694+="concat(' ',@class,' '), ' "+_692.join(" ') and contains(concat(' ',@class,' '), ' ")+" ')]";
}else{
_694+="concat(' ',@class,' '), ' "+_692.join(" ')) or contains(concat(' ',@class,' '), ' ")+" ')]";
}
var _695=document.evaluate(_694,_689,null,XPathResult.UNORDERED_NODE_SNAPSHOT_TYPE,null);
outer:
for(var node=null,i=0;node=_695.snapshotItem(i);i++){
if(_691!=dojo.html.classMatchType.IsOnly){
_693.push(node);
}else{
if(!dojo.html.getClass(node)){
continue outer;
}
var _696=dojo.html.getClass(node).split(/\s+/g);
var _697=new RegExp("(\\s|^)("+_692.join(")|(")+")(\\s|$)");
for(var j=0;j<_696.length;j++){
if(!_696[j].match(_697)){
continue outer;
}
}
_693.push(node);
}
}
}else{
if(!_690){
_690="*";
}
var _698=_689.getElementsByTagName(_690);
outer:
for(var i=0;i<_698.length;i++){
var node=_698[i];
if(!dojo.html.getClass(node)){
continue outer;
}
var _696=dojo.html.getClass(node).split(/\s+/g);
var _697=new RegExp("(\\s|^)(("+_692.join(")|(")+"))(\\s|$)");
var _699=0;
for(var j=0;j<_696.length;j++){
if(_697.test(_696[j])){
if(_691==dojo.html.classMatchType.ContainsAny){
_693.push(node);
continue outer;
}else{
_699++;
}
}else{
if(_691==dojo.html.classMatchType.IsOnly){
continue outer;
}
}
}
if(_699==_692.length){
if(_691==dojo.html.classMatchType.IsOnly&&_699==_696.length){
_693.push(node);
}else{
if(_691==dojo.html.classMatchType.ContainsAll){
_693.push(node);
}
}
}
}
}
return _693;
};
dojo.html.gravity=function(node,e){
var _700=e.pageX||e.clientX+this.body().scrollLeft;
var _701=e.pageY||e.clientY+this.body().scrollTop;
with(dojo.html){
var _702=getAbsoluteX(node)+(getInnerWidth(node)/2);
var _703=getAbsoluteY(node)+(getInnerHeight(node)/2);
}
with(dojo.html.gravity){
return ((_700<_702?WEST:EAST)|(_701<_703?NORTH:SOUTH));
}
};
dojo.html.gravity.NORTH=1;
dojo.html.gravity.SOUTH=1<<1;
dojo.html.gravity.EAST=1<<2;
dojo.html.gravity.WEST=1<<3;
dojo.html.overElement=function(_704,e){
var _705=e.pageX||e.clientX+this.body().scrollLeft;
var _706=e.pageY||e.clientY+this.body().scrollTop;
with(dojo.html){
var top=getAbsoluteY(_704);
var _707=top+getInnerHeight(_704);
var left=getAbsoluteX(_704);
var _708=left+getInnerWidth(_704);
}
return (_705>=left&&_705<=_708&&_706>=top&&_706<=_707);
};
dojo.html.renderedTextContent=function(node){
var _709="";
if(node==null){
return _709;
}
for(var i=0;i<node.childNodes.length;i++){
switch(node.childNodes[i].nodeType){
case 1:
case 5:
switch(dojo.style.getStyle(node.childNodes[i],"display")){
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
_709+="\n";
_709+=dojo.html.renderedTextContent(node.childNodes[i]);
_709+="\n";
break;
case "none":
break;
default:
_709+=dojo.html.renderedTextContent(node.childNodes[i]);
break;
}
break;
case 3:
case 2:
case 4:
var text=node.childNodes[i].nodeValue;
switch(dojo.style.getStyle(node,"text-transform")){
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
switch(dojo.style.getStyle(node,"text-transform")){
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
if(/\s$/.test(_709)){
text.replace(/^\s/,"");
}
break;
}
_709+=text;
break;
default:
break;
}
}
return _709;
};
dojo.html.setActiveStyleSheet=function(_710){
var i,a,main;
for(i=0;(a=document.getElementsByTagName("link")[i]);i++){
if(a.getAttribute("rel").indexOf("style")!=-1&&a.getAttribute("title")){
a.disabled=true;
if(a.getAttribute("title")==_710){
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
return document.body||document.getElementsByTagName("body")[0];
};
dojo.html.createNodesFromText=function(txt,wrap){
var tn=document.createElement("div");
tn.style.visibility="hidden";
document.body.appendChild(tn);
tn.innerHTML=txt;
tn.normalize();
if(wrap){
var ret=[];
var fc=tn.firstChild;
ret[0]=((fc.nodeValue==" ")||(fc.nodeValue=="\t"))?fc.nextSibling:fc;
document.body.removeChild(tn);
return ret;
}
var _714=[];
for(var x=0;x<tn.childNodes.length;x++){
_714.push(tn.childNodes[x].cloneNode(true));
}
tn.style.display="none";
document.body.removeChild(tn);
return _714;
};
if(!dojo.evalObjPath("dojo.dom.createNodesFromText")){
dojo.dom.createNodesFromText=function(){
dojo.deprecated("dojo.dom.createNodesFromText","use dojo.html.createNodesFromText instead");
dojo.html.createNodesFromText.apply(dojo.html,arguments);
};
}
dojo.provide("dojo.math.curves");
dojo.require("dojo.math");
dojo.math.curves={Line:function(_715,end){
this.start=_715;
this.end=end;
this.dimensions=_715.length;
for(var i=0;i<_715.length;i++){
_715[i]=Number(_715[i]);
}
for(var i=0;i<end.length;i++){
end[i]=Number(end[i]);
}
this.getValue=function(n){
var _716=new Array(this.dimensions);
for(var i=0;i<this.dimensions;i++){
_716[i]=((this.end[i]-this.start[i])*n)+this.start[i];
}
return _716;
};
return this;
},Bezier:function(pnts){
this.getValue=function(step){
if(step>=1){
return this.p[this.p.length-1];
}
if(step<=0){
return this.p[0];
}
var _719=new Array(this.p[0].length);
for(var k=0;j<this.p[0].length;k++){
_719[k]=0;
}
for(var j=0;j<this.p[0].length;j++){
var C=0;
var D=0;
for(var i=0;i<this.p.length;i++){
C+=this.p[i][j]*this.p[this.p.length-1][0]*dojo.math.bernstein(step,this.p.length,i);
}
for(var l=0;l<this.p.length;l++){
D+=this.p[this.p.length-1][0]*dojo.math.bernstein(step,this.p.length,l);
}
_719[j]=C/D;
}
return _719;
};
this.p=pnts;
return this;
},CatmullRom:function(pnts,c){
this.getValue=function(step){
var _722=step*(this.p.length-1);
var node=Math.floor(_722);
var _723=_722-node;
var i0=node-1;
if(i0<0){
i0=0;
}
var i=node;
var i1=node+1;
if(i1>=this.p.length){
i1=this.p.length-1;
}
var i2=node+2;
if(i2>=this.p.length){
i2=this.p.length-1;
}
var u=_723;
var u2=_723*_723;
var u3=_723*_723*_723;
var _730=new Array(this.p[0].length);
for(var k=0;k<this.p[0].length;k++){
var x1=(-this.c*this.p[i0][k])+((2-this.c)*this.p[i][k])+((this.c-2)*this.p[i1][k])+(this.c*this.p[i2][k]);
var x2=(2*this.c*this.p[i0][k])+((this.c-3)*this.p[i][k])+((3-2*this.c)*this.p[i1][k])+(-this.c*this.p[i2][k]);
var x3=(-this.c*this.p[i0][k])+(this.c*this.p[i1][k]);
var x4=this.p[i][k];
_730[k]=x1*u3+x2*u2+x3*u+x4;
}
return _730;
};
if(!c){
this.c=0.7;
}else{
this.c=c;
}
this.p=pnts;
return this;
},Arc:function(_735,end,ccw){
var _737=dojo.math.points.midpoint(_735,end);
var _738=dojo.math.points.translate(dojo.math.points.invert(_737),_735);
var rad=Math.sqrt(Math.pow(_738[0],2)+Math.pow(_738[1],2));
var _740=dojo.math.radToDeg(Math.atan(_738[1]/_738[0]));
if(_738[0]<0){
_740-=90;
}else{
_740+=90;
}
dojo.math.curves.CenteredArc.call(this,_737,rad,_740,_740+(ccw?-180:180));
},CenteredArc:function(_741,_742,_743,end){
this.center=_741;
this.radius=_742;
this.start=_743||0;
this.end=end;
this.getValue=function(n){
var _744=new Array(2);
var _745=dojo.math.degToRad(this.start+((this.end-this.start)*n));
_744[0]=this.center[0]+this.radius*Math.sin(_745);
_744[1]=this.center[1]-this.radius*Math.cos(_745);
return _744;
};
return this;
},Circle:function(_746,_747){
dojo.math.curves.CenteredArc.call(this,_746,_747,0,360);
return this;
},Path:function(){
var _748=[];
var _749=[];
var _750=[];
var _751=0;
this.add=function(_752,_753){
if(_753<0){
dj_throw("dojo.math.curves.Path.add: weight cannot be less than 0");
}
_748.push(_752);
_749.push(_753);
_751+=_753;
computeRanges();
};
this.remove=function(_754){
for(var i=0;i<_748.length;i++){
if(_748[i]==_754){
_748.splice(i,1);
_751-=_749.splice(i,1)[0];
break;
}
}
computeRanges();
};
this.removeAll=function(){
_748=[];
_749=[];
_751=0;
};
this.getValue=function(n){
var _755=false,value=0;
for(var i=0;i<_750.length;i++){
var r=_750[i];
if(n>=r[0]&&n<r[1]){
var subN=(n-r[0])/r[2];
value=_748[i].getValue(subN);
_755=true;
break;
}
}
if(!_755){
value=_748[_748.length-1].getValue(1);
}
for(j=0;j<i;j++){
value=dojo.math.points.translate(value,_748[j].getValue(1));
}
return value;
};
function computeRanges(){
var _757=0;
for(var i=0;i<_749.length;i++){
var end=_757+_749[i]/_751;
var len=end-_757;
_750[i]=[_757,end,len];
_757=end;
}
}
return this;
}};
dojo.provide("dojo.animation");
dojo.provide("dojo.animation.Animation");
dojo.require("dojo.math");
dojo.require("dojo.math.curves");
dojo.animation={};
dojo.animation.Animation=function(_758,_759,_760,_761){
var _762=this;
this.curve=_758;
this.duration=_759;
this.repeatCount=_761||0;
this.animSequence_=null;
if(_760){
if(dojo.lang.isFunction(_760.getValue)){
this.accel=_760;
}else{
var i=0.35*_760+0.5;
this.accel=new dojo.math.curves.CatmullRom([[0],[i],[1]],0.45);
}
}
this.onBegin=null;
this.onAnimate=null;
this.onEnd=null;
this.onPlay=null;
this.onPause=null;
this.onStop=null;
this.handler=null;
var _763=null,endTime=null,lastFrame=null,timer=null,percent=0,active=false,paused=false;
this.play=function(_764){
if(_764){
clearTimeout(timer);
active=false;
paused=false;
percent=0;
}else{
if(active&&!paused){
return;
}
}
_763=new Date().valueOf();
if(paused){
_763-=(_762.duration*percent/100);
}
endTime=_763+_762.duration;
lastFrame=_763;
var e=new dojo.animation.AnimationEvent(_762,null,_762.curve.getValue(percent),_763,_763,endTime,_762.duration,percent,0);
active=true;
paused=false;
if(percent==0){
e.type="begin";
if(typeof _762.handler=="function"){
_762.handler(e);
}
if(typeof _762.onBegin=="function"){
_762.onBegin(e);
}
}
e.type="play";
if(typeof _762.handler=="function"){
_762.handler(e);
}
if(typeof _762.onPlay=="function"){
_762.onPlay(e);
}
if(this.animSequence_){
this.animSequence_.setCurrent(this);
}
cycle();
};
this.pause=function(){
clearTimeout(timer);
if(!active){
return;
}
paused=true;
var e=new dojo.animation.AnimationEvent(_762,"pause",_762.curve.getValue(percent),_763,new Date().valueOf(),endTime,_762.duration,percent,0);
if(typeof _762.handler=="function"){
_762.handler(e);
}
if(typeof _762.onPause=="function"){
_762.onPause(e);
}
};
this.playPause=function(){
if(!active||paused){
_762.play();
}else{
_762.pause();
}
};
this.gotoPercent=function(pct,_766){
clearTimeout(timer);
active=true;
paused=true;
percent=pct;
if(_766){
this.play();
}
};
this.stop=function(_767){
clearTimeout(timer);
var step=percent/100;
if(_767){
step=1;
}
var e=new dojo.animation.AnimationEvent(_762,"stop",_762.curve.getValue(step),_763,new Date().valueOf(),endTime,_762.duration,percent,Math.round(fps));
if(typeof _762.handler=="function"){
_762.handler(e);
}
if(typeof _762.onStop=="function"){
_762.onStop(e);
}
active=false;
paused=false;
};
this.status=function(){
if(active){
return paused?"paused":"playing";
}else{
return "stopped";
}
};
function cycle(){
clearTimeout(timer);
if(active){
var curr=new Date().valueOf();
var step=(curr-_763)/(endTime-_763);
fps=1000/(curr-lastFrame);
lastFrame=curr;
if(step>=1){
step=1;
percent=100;
}else{
percent=step*100;
}
if(_762.accel&&_762.accel.getValue){
step=_762.accel.getValue(step);
}
var e=new dojo.animation.AnimationEvent(_762,"animate",_762.curve.getValue(step),_763,curr,endTime,_762.duration,percent,Math.round(fps));
if(typeof _762.handler=="function"){
_762.handler(e);
}
if(typeof _762.onAnimate=="function"){
_762.onAnimate(e);
}
if(step<1){
timer=setTimeout(cycle,10);
}else{
e.type="end";
active=false;
if(typeof _762.handler=="function"){
_762.handler(e);
}
if(typeof _762.onEnd=="function"){
_762.onEnd(e);
}
if(_762.repeatCount>0){
_762.repeatCount--;
_762.play(true);
}else{
if(_762.repeatCount==-1){
_762.play(true);
}else{
if(_762.animSequence_){
_762.animSequence_.playNext();
}
}
}
}
}
}
};
dojo.animation.AnimationEvent=function(anim,type,_769,_770,_771,_772,dur,pct,fps){
this.type=type;
this.animation=anim;
this.coords=_769;
this.x=_769[0];
this.y=_769[1];
this.z=_769[2];
this.startTime=_770;
this.currentTime=_771;
this.endTime=_772;
this.duration=dur;
this.percent=pct;
this.fps=fps;
this.coordsAsInts=function(){
var _775=new Array(this.coords.length);
for(var i=0;i<this.coords.length;i++){
_775[i]=Math.round(this.coords[i]);
}
return _775;
};
return this;
};
dojo.animation.AnimationSequence=function(_776){
var _777=[];
var _778=-1;
this.repeatCount=_776||0;
this.onBegin=null;
this.onEnd=null;
this.onNext=null;
this.handler=null;
this.add=function(){
for(var i=0;i<arguments.length;i++){
_777.push(arguments[i]);
arguments[i].animSequence_=this;
}
};
this.remove=function(anim){
for(var i=0;i<_777.length;i++){
if(_777[i]==anim){
_777[i].animSequence_=null;
_777.splice(i,1);
break;
}
}
};
this.removeAll=function(){
for(var i=0;i<_777.length;i++){
_777[i].animSequence_=null;
}
_777=[];
_778=-1;
};
this.play=function(_779){
if(_777.length==0){
return;
}
if(_779||!_777[_778]){
_778=0;
}
if(_777[_778]){
if(_778==0){
var e={type:"begin",animation:_777[_778]};
if(typeof this.handler=="function"){
this.handler(e);
}
if(typeof this.onBegin=="function"){
this.onBegin(e);
}
}
_777[_778].play(_779);
}
};
this.pause=function(){
if(_777[_778]){
_777[_778].pause();
}
};
this.playPause=function(){
if(_777.length==0){
return;
}
if(_778==-1){
_778=0;
}
if(_777[_778]){
_777[_778].playPause();
}
};
this.stop=function(){
if(_777[_778]){
_777[_778].stop();
}
};
this.status=function(){
if(_777[_778]){
return _777[_778].status();
}else{
return "stopped";
}
};
this.setCurrent=function(anim){
for(var i=0;i<_777.length;i++){
if(_777[i]==anim){
_778=i;
break;
}
}
};
this.playNext=function(){
if(_778==-1||_777.length==0){
return;
}
_778++;
if(_777[_778]){
var e={type:"next",animation:_777[_778]};
if(typeof this.handler=="function"){
this.handler(e);
}
if(typeof this.onNext=="function"){
this.onNext(e);
}
_777[_778].play(true);
}else{
var e={type:"end",animation:_777[_777.length-1]};
if(typeof this.handler=="function"){
this.handler(e);
}
if(typeof this.onEnd=="function"){
this.onEnd(e);
}
if(this.repeatCount>0){
_778=0;
this.repeatCount--;
_777[_778].play(true);
}else{
if(this.repeatCount==-1){
_778=0;
_777[_778].play(true);
}else{
_778=-1;
}
}
}
};
};
dojo.hostenv.conditionalLoadModule({common:["dojo.uri.Uri",false,false]});
dojo.hostenv.moduleLoaded("dojo.uri.*");
dojo.provide("dojo.widget.DomWidget");
dojo.require("dojo.event.*");
dojo.require("dojo.string");
dojo.require("dojo.widget.Widget");
dojo.require("dojo.dom");
dojo.require("dojo.html");
dojo.require("dojo.xml.Parse");
dojo.require("dojo.math.curves");
dojo.require("dojo.animation.Animation");
dojo.require("dojo.uri.*");
dojo.widget._cssFiles={};
dojo.widget.buildFromTemplate=function(obj,_780,_781,_782){
var _783=_780||obj.templatePath;
var _784=_781||obj.templateCssPath;
if(!_784&&obj.templateCSSPath){
obj.templateCssPath=_784=obj.templateCSSPath;
obj.templateCSSPath=null;
dj_deprecated("templateCSSPath is deprecated, use templateCssPath");
}
if(_783&&!(_783 instanceof dojo.uri.Uri)){
_783=dojo.uri.dojoUri(_783);
dj_deprecated("templatePath should be of type dojo.uri.Uri");
}
if(_784&&!(_784 instanceof dojo.uri.Uri)){
_784=dojo.uri.dojoUri(_784);
dj_deprecated("templateCssPath should be of type dojo.uri.Uri");
}
var _785=dojo.widget.DomWidget.templates;
if(!obj["widgetType"]){
do{
var _786="__dummyTemplate__"+dojo.widget.buildFromTemplate.dummyCount++;
}while(_785[_786]);
obj.widgetType=_786;
}
if((_784)&&(!dojo.widget._cssFiles[_784])){
dojo.html.insertCssFile(_784);
obj.templateCssPath=null;
dojo.widget._cssFiles[_784]=true;
}
var ts=_785[obj.widgetType];
if(!ts){
_785[obj.widgetType]={};
ts=_785[obj.widgetType];
}
if(!obj.templateString){
obj.templateString=_782||ts["string"];
}
if(!obj.templateNode){
obj.templateNode=ts["node"];
}
if((!obj.templateNode)&&(!obj.templateString)&&(_783)){
var _788=dojo.hostenv.getText(_783);
if(_788){
var _789=_788.match(/<body[^>]*>\s*([\s\S]+)\s*<\/body>/im);
if(_789){
_788=_789[1];
}
}else{
_788="";
}
obj.templateString=_788;
ts.string=_788;
}
if(!ts["string"]){
ts.string=obj.templateString;
}
};
dojo.widget.buildFromTemplate.dummyCount=0;
dojo.widget.attachProperties=["dojoAttachPoint","id"];
dojo.widget.eventAttachProperty="dojoAttachEvent";
dojo.widget.subTemplateProperty="dojoSubTemplate";
dojo.widget.onBuildProperty="dojoOnBuild";
dojo.widget.attachTemplateNodes=function(_790,_791,_792,_793){
var _794=dojo.dom.ELEMENT_NODE;
if(!_790){
_790=_791.domNode;
}
if(_790.nodeType!=_794){
return;
}
var _795=_790.getElementsByTagName("*");
var _796=_791;
for(var x=-1;x<_795.length;x++){
var _797=(x==-1)?_790:_795[x];
var _798=null;
for(var y=0;y<this.attachProperties.length;y++){
_798=_797.getAttribute(this.attachProperties[y]);
if(_798){
_791[_798]=_797;
break;
}
}
var _799=_797.getAttribute(this.templateProperty);
if(_799){
_791[_799]=_797;
}
var _799=_797.getAttribute(this.subTemplateProperty);
if(_799){
_792.subTemplates[_799]=_797.parentNode.removeChild(_797);
_792.subTemplates[_799].removeAttribute(this.subTemplateProperty);
}
var _800=_797.getAttribute(this.eventAttachProperty);
if(_800){
var evts=_800.split(";");
for(var y=0;y<evts.length;y++){
if(!evts[y]){
continue;
}
if(!evts[y].length){
continue;
}
var tevt=null;
var _803=null;
tevt=dojo.string.trim(evts[y]);
if(tevt.indexOf(":")>=0){
var _804=tevt.split(":");
tevt=dojo.string.trim(_804[0]);
_803=dojo.string.trim(_804[1]);
}
if(!_803){
_803=tevt;
}
var tf=function(){
var ntf=new String(_803);
return function(evt){
if(_796[ntf]){
_796[ntf](evt);
}
};
}();
dojo.event.browser.addListener(_797,tevt.substr(2),tf);
}
}
for(var y=0;y<_793.length;y++){
var _807=_797.getAttribute(_793[y]);
if((_807)&&(_807.length)){
var _803=null;
var _808=_793[y].substr(4).toLowerCase();
_803=dojo.string.trim(_807);
var tf=function(){
var ntf=new String(_803);
return function(evt){
if(_796[ntf]){
_796[ntf](evt);
}
};
}();
dojo.event.browser.addListener(_797,_808.substr(2),tf);
}
}
var _809=_797.getAttribute(this.onBuildProperty);
if(_809){
eval("var node = baseNode; var widget = targetObj; "+_809);
}
_797.id="";
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
dojo.widget.buildAndAttachTemplate=function(obj,_811,_812,_813,_814){
this.buildFromTemplate(obj,_811,_812,_813);
var node=dojo.dom.createNodesFromText(obj.templateString,true)[0];
this.attachTemplateNodes(node,_814||obj,obj,dojo.widget.getDojoEventsFromStr(_813));
return node;
};
dojo.widget.DomWidget=function(){
dojo.widget.Widget.call(this);
if((arguments.length>0)&&(typeof arguments[0]=="object")){
this.create(arguments[0]);
}
};
dojo.inherits(dojo.widget.DomWidget,dojo.widget.Widget);
dojo.lang.extend(dojo.widget.DomWidget,{templateNode:null,templateString:null,subTemplates:{},domNode:null,containerNode:null,addChild:function(_815,_816,pos,ref,_818){
if(!this.isContainer){
dojo.debug("dojo.widget.DomWidget.addChild() attempted on non-container widget");
return false;
}else{
if((!this.containerNode)&&(!_816)){
this.containerNode=this.domNode;
}
var cn=(_816)?_816:this.containerNode;
if(!pos){
pos="after";
}
if(!ref){
ref=cn.lastChild;
}
if(!_818){
_818=0;
}
_815.domNode.setAttribute("dojoinsertionindex",_818);
if(!ref){
cn.appendChild(_815.domNode);
}else{
dojo.dom.insertAtPosition(_815.domNode,ref,pos);
}
this.children.push(_815);
_815.parent=this;
_815.addedTo(this);
}
return _815;
},removeChild:function(_820){
for(var x=0;x<this.children.length;x++){
if(this.children[x]===_820){
this.children.splice(x,1);
break;
}
}
return _820;
},postInitialize:function(args,frag,_821){
if(_821){
_821.addChild(this,"","insertAtIndex","",args["dojoinsertionindex"]);
}else{
if(!frag){
return;
}
var _822=frag["dojo:"+this.widgetType.toLowerCase()]["nodeRef"];
if(!_822){
return;
}
this.parent=dojo.widget.manager.root;
if((this.domNode)&&(this.domNode!==_822)){
var _823=_822.parentNode.replaceChild(this.domNode,_822);
}
}
if(this.isContainer){
var _824=dojo.dom.ELEMENT_NODE;
var _825=dojo.widget.getParser();
_825.createComponents(frag,this);
}
},startResize:function(_826){
dj_unimplemented("dojo.widget.DomWidget.startResize");
},updateResize:function(_827){
dj_unimplemented("dojo.widget.DomWidget.updateResize");
},endResize:function(_828){
dj_unimplemented("dojo.widget.DomWidget.endResize");
},buildRendering:function(args,frag){
var ts=dojo.widget.DomWidget.templates[this.widgetType];
if((this.templatePath)||(this.templateNode)||((this["templateString"])&&(this.templateString.length))||((typeof ts!="undefined")&&((ts["string"])||(ts["node"])))){
this.buildFromTemplate(args,frag);
}else{
this.domNode=frag["dojo:"+this.widgetType.toLowerCase()]["nodeRef"];
}
this.fillInTemplate(args,frag);
},buildFromTemplate:function(args,frag){
var ts=dojo.widget.DomWidget.templates[this.widgetType];
if(ts){
if(!this.templateString.length){
this.templateString=ts["string"];
}
if(!this.templateNode){
this.templateNode=ts["node"];
}
}
var node=null;
if((!this.templateNode)&&(this.templateString)){
this.templateString=this.templateString.replace(/\$\{baseScriptUri\}/mg,dojo.hostenv.getBaseScriptUri());
this.templateString=this.templateString.replace(/\$\{dojoRoot\}/mg,dojo.hostenv.getBaseScriptUri());
this.templateNode=this.createNodesFromText(this.templateString,true)[0];
ts.node=this.templateNode;
}
if(!this.templateNode){
dojo.debug("weren't able to create template!");
return false;
}
var node=this.templateNode.cloneNode(true);
if(!node){
return false;
}
this.domNode=node;
this.attachTemplateNodes(this.domNode,this);
},attachTemplateNodes:function(_829,_830){
if(!_830){
_830=this;
}
return dojo.widget.attachTemplateNodes(_829,_830,this,dojo.widget.getDojoEventsFromStr(this.templateString));
},fillInTemplate:function(){
},destroyRendering:function(){
try{
var _831=this.domNode.parentNode.removeChild(this.domNode);
delete _831;
}
catch(e){
}
},cleanUp:function(){
},getContainerHeight:function(){
return dojo.html.getInnerHeight(this.domNode.parentNode);
},getContainerWidth:function(){
return dojo.html.getInnerWidth(this.domNode.parentNode);
},createNodesFromText:function(){
dj_unimplemented("dojo.widget.DomWidget.createNodesFromText");
}});
dojo.widget.DomWidget.templates={};
dojo.provide("dojo.widget.HtmlWidget");
dojo.require("dojo.widget.DomWidget");
dojo.require("dojo.html");
dojo.widget.HtmlWidget=function(args){
dojo.widget.DomWidget.call(this);
};
dojo.inherits(dojo.widget.HtmlWidget,dojo.widget.DomWidget);
dojo.lang.extend(dojo.widget.HtmlWidget,{templateCssPath:null,templatePath:null,allowResizeX:true,allowResizeY:true,resizeGhost:null,initialResizeCoords:null,getContainerHeight:function(){
dj_unimplemented("dojo.widget.HtmlWidget.getContainerHeight");
},getContainerWidth:function(){
return this.parent.domNode.offsetWidth;
},setNativeHeight:function(_832){
var ch=this.getContainerHeight();
},startResize:function(_833){
_833.offsetLeft=dojo.html.totalOffsetLeft(this.domNode);
_833.offsetTop=dojo.html.totalOffsetTop(this.domNode);
_833.innerWidth=dojo.html.getInnerWidth(this.domNode);
_833.innerHeight=dojo.html.getInnerHeight(this.domNode);
if(!this.resizeGhost){
this.resizeGhost=document.createElement("div");
var rg=this.resizeGhost;
rg.style.position="absolute";
rg.style.backgroundColor="white";
rg.style.border="1px solid black";
dojo.html.setOpacity(rg,0.3);
dojo.html.body().appendChild(rg);
}
with(this.resizeGhost.style){
left=_833.offsetLeft+"px";
top=_833.offsetTop+"px";
}
this.initialResizeCoords=_833;
this.resizeGhost.style.display="";
this.updateResize(_833,true);
},updateResize:function(_835,_836){
var dx=_835.x-this.initialResizeCoords.x;
var dy=_835.y-this.initialResizeCoords.y;
with(this.resizeGhost.style){
if((this.allowResizeX)||(_836)){
width=this.initialResizeCoords.innerWidth+dx+"px";
}
if((this.allowResizeY)||(_836)){
height=this.initialResizeCoords.innerHeight+dy+"px";
}
}
},endResize:function(_839){
var dx=_839.x-this.initialResizeCoords.x;
var dy=_839.y-this.initialResizeCoords.y;
with(this.domNode.style){
if(this.allowResizeX){
width=this.initialResizeCoords.innerWidth+dx+"px";
}
if(this.allowResizeY){
height=this.initialResizeCoords.innerHeight+dy+"px";
}
}
this.resizeGhost.style.display="none";
},createNodesFromText:function(txt,wrap){
return dojo.html.createNodesFromText(txt,wrap);
},_old_buildFromTemplate:dojo.widget.DomWidget.prototype.buildFromTemplate,buildFromTemplate:function(){
dojo.widget.buildFromTemplate(this);
this._old_buildFromTemplate();
},destroyRendering:function(_840){
try{
var _841=this.domNode.parentNode.removeChild(this.domNode);
if(!_840){
dojo.event.browser.clean(_841);
}
delete _841;
}
catch(e){
}
}});
dojo.hostenv.conditionalLoadModule({common:["dojo.xml.Parse","dojo.widget.Widget","dojo.widget.Parse","dojo.widget.Manager"],browser:["dojo.widget.DomWidget","dojo.widget.HtmlWidget"],svg:["dojo.widget.SvgWidget"]});
dojo.hostenv.moduleLoaded("dojo.widget.*");
dojo.provide("turbo.widgets.TurboWidget");
dojo.require("dojo.widget.*");
dojo.require("turbo.turbo");
turbo.widgetRoot="../turbo/widgets/";
turbo.templateRoot=turbo.widgetRoot+"templates/";
turbo.themeRoot=turbo.widgetRoot+"themes/";
turbo.addHeadNode=function(_842){
document.getElementsByTagName("head").item(0).appendChild(_842);
};
turbo.loadScript=function(_843){
var _844=document.createElement("script");
_844.type="text/javascript";
_844.language="JavaScript";
turbo.addHeadNode(_844);
_844.src=_843;
};
turbo.loadCss=function(_845){
var _846=dojo.hostenv.getBaseScriptUri()+_845;
if((_846)&&(!dojo.widget._cssFiles[_846])){
dojo.html.insertCssFile(_846);
dojo.widget._cssFiles[_846]=true;
}
};
turbo.setWidgetType=function(_847,_848){
if(_847.widgetType=="Widget"){
_847.widgetType=_848;
}
};
dojo.widget.HtmlTurboWidget=function(){
dojo.widget.HtmlWidget.call(this);
this.themeRoot=turbo.themeRoot;
this.templateRoot=turbo.templateRoot;
this.templatePath=dojo.uri.dojoUri(this.templateRoot+this.widgetType+".html");
this.isContainer=false;
this.style="";
this.theme="";
this.themeable=true;
this.initialize=function(){
if(this.widgetId.substr(-2,1)!="_"&&this.widgetId.substr(-3,1)!="_"){
dj_global[this.widgetId]=this;
}
if(this.extraArgs.turboalign){
this.domNode.setAttribute("turboalign",this.extraArgs.turboalign);
}
if(this.extraArgs.turboAlign){
this.domNode.setAttribute("turboalign",this.extraArgs.turboAlign);
}
if(this.extraArgs["class"]){
this.domNode.className=this.extraArgs["class"];
}
this.domNode.id=this.widgetId;
if(this.themeable){
turbo.themes.addThemeable(this);
}
};
this.getWidgetFragment=function(_849){
return _849["dojo:"+this.widgetType.toLowerCase()]["nodeRef"];
};
this.getStylePath=function(_850){
return this.themeRoot+(_850?_850:"default")+"/"+this.widgetType+_850+".css";
};
this.loadStyle=function(_851){
turbo.loadCss(this.getStylePath(_851));
};
this.setStyle=function(_852){
this.loadStyle(_852);
this.style=_852;
this.styleChanged();
};
this.setTheme=function(_853){
this.loadStyle("");
if(!this.themeable){
return;
}
if(_853&&(_853.charAt(0)=="+")){
this.themeable=false;
_853=_853.substring(1);
}
this.setStyle(_853);
};
this.styleChanged=function(){
};
this.setClassName=function(_854,_855){
_854.className=this.classTag+_855+(this.style?" "+this.classTag+this.style+_855:"");
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
dojo.provide("turbo.widgets.TurboTree");
dojo.provide("turbo.widgets.HtmlTurboTree");
dojo.require("turbo.widgets.TurboWidget");
turbo.objectToArray=function(_856){
if(turbo.isArray(_856)){
return _856;
}
var _857=[];
for(var i in _856){
_857.push(new Array(i,turbo.objectToArray(_856[i])));
}
return _857;
};
dojo.widget.HtmlTurboTree=function(){
this.widgetType="TurboTree";
dojo.widget.HtmlTurboWidget.call(this);
this.templatePath=null;
this.templateString="<div dojoattachpoint=\"mainDiv\"><div dojoattachpoint=\"treeDiv\"></div></div>";
this.classTag="turbo_tree";
this.imageRoot=dojo.uri.dojoUri(this.themeRoot+"default/");
this.mainDiv=null;
this.treeDiv=null;
this.nodes=null;
this.selected=null;
this.onCanUnselect=function(_858){
};
this.onCanSelect=function(_859){
};
this.onNodeSelected=function(_860){
};
this.fillInTemplate=function(){
this.setTheme("");
if(this.nodes){
this.buildNodes(this.nodes);
}
};
this.styleChanged=function(){
this.mainDiv.className=this.classTag+(this.style?" "+this.classTag+"_"+this.style:"");
};
this.buildLeaf=function(_861,_862,_863,_864){
var img=document.createElement("img");
var leaf=this.imageRoot+(_861?"tree_root.gif":(_863?"tree_last_leaf.gif":"tree_leaf.gif"));
if(_862){
img.src=this.imageRoot+(_864?"tree_closed":"tree_open")+".gif";
img.style.backgroundImage="url("+leaf+")";
}else{
img.src=leaf;
}
return img;
};
this.buildNode=function(_867,_868,_869,_870){
if(turbo.isObject(_869)){
var _871=(_869.children)&&(_869.children.length>0);
var _872=(_869.content?_869.content:_869.name);
}else{
var _871=false;
var _872=_869;
}
var row=document.createElement("div");
row.setAttribute("turboTreeNode","true");
if(_869.data){
for(var i in _869.data){
row.setAttribute(i,_869.data[i]);
}
}
if(_868){
row.appendChild(_868.cloneNode(true));
}else{
row.appendChild(document.createTextNode(""));
}
row.appendChild(this.buildLeaf((_868==null),_871,_870,_869.closed));
var node=document.createElement("span");
node.innerHTML=_872;
node.className=this.classTag+"-content";
node.style.cursor="default";
row.appendChild(node);
if(_871){
var pre=(_868?_868:document.createElement("span"));
var img=document.createElement("img");
img.src=this.imageRoot+(_870?"tree_blank.gif":"tree_bar.gif");
pre.appendChild(img);
var _875=document.createElement("div");
this.buildChildren(_875,pre,_869.children);
row.appendChild(_875);
pre.removeChild(img);
if(_869.closed){
_875.style.display="none";
}
}
_867.appendChild(row);
};
this.buildChildren=function(_876,_877,_878){
var l=_878.length;
for(var i=0;i<l;i++){
this.buildNode(_876,_877,_878[i],(i==l-1));
}
};
this.buildNodes=function(_879){
dojo.event.browser.clean(this.mainDiv);
this.treeDiv.innerHTML="";
if(turbo.isArray(_879)){
this.buildChildren(this.treeDiv,null,_879);
}else{
this.buildNode(this.treeDiv,null,_879,true);
}
dojo.event.connect(this.mainDiv,"onclick",this,"divClick");
};
this.getToggleElement=function(_880){
return _880.childNodes[1];
};
this.getContentElement=function(_881){
return _881.childNodes[2];
};
this.getChildrenElement=function(_882){
return _882.childNodes[3];
};
this.setSelected=function(_883,_884){
if(_883){
with(this.getContentElement(_883)){
style.backgroundColor=(_884?"blue":"");
style.color=(_884?"white":"");
}
}
};
this.getContent=function(_885){
return this.getContentElement(_885).innerHTML;
};
this.selectNode=function(_886){
if(this.onCanUnselect(this.selected)===false||this.onCanSelect(_886)===false){
return;
}
this.setSelected(this.selected,false);
this.selected=_886;
this.setSelected(this.selected,true);
this.onNodeSelected(this.selected);
};
this.toggleNode=function(_887){
var n=this.getChildrenElement(_887);
if(n){
n.style.display=(n.style.display=="none"?"":"none");
this.getToggleElement(_887).src=this.imageRoot+(n.style.display=="none"?"tree_closed":"tree_open")+".gif";
}
};
this.isTreeNode=function(_888){
return _888&&_888.getAttribute&&_888.getAttribute("turboTreeNode");
};
this.divClick=function(_889){
var t=_889.target;
while(t&&!this.isTreeNode(t)){
t=t.parentNode;
}
if(t){
if(_889.target==this.getToggleElement(t)){
this.toggleNode(t);
}else{
this.selectNode(t);
}
}
};
this._firstTreeNode=function(_890){
if(_890&&!this.isTreeNode(_890)){
_890=_890.nextSibling;
}
return _890;
};
this.nextNode=function(_891){
return (_891?this._firstTreeNode(_891.nextSibling):null);
};
this.childNode=function(_892){
if(!_892||!this.isTreeNode(_892)){
return null;
}
var _893=this.getChildrenElement(_892);
if(!_893){
return null;
}
return this._firstTreeNode(_893.firstChild);
};
this.rootNode=function(){
return this._firstTreeNode(this.treeDiv.firstChild);
};
};
dojo.inherits(dojo.widget.HtmlTurboTree,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbotree");
dojo.provide("turbo.widgets.TurboGrid");
dojo.provide("turbo.widgets.HtmlTurboGrid");
dojo.require("turbo.widgets.TurboWidget");
dojo.widget.HtmlTurboGrid=function(){
this.widgetType="TurboGrid";
dojo.widget.HtmlTurboWidget.call(this);
this.controller={};
this.cols=0;
this.colWidth=96;
this.dataCache=null;
this.editingCell=null;
this.editingRow=-1;
this.fixedColWidth=32;
this.rows=0;
this.scrollLeft=0;
this.selectedRow=-1;
this.selectCount=0;
this.sortInfo={};
this.selected=[];
this.rowMarkerClass=[];
this.readyImage="";
this.busyImage="";
this.Corner=null;
this.ColDiv=null;
this.HdrDiv=null;
this.DtaDiv=null;
this.Status=null;
this.GrdTbl=null;
this.DtaTbl=null;
this.HdrTbl=null;
this.ColTbl=null;
this.onSelectionChange=function(){
};
this.onSelectRow=function(_894){
if(this.controller.onSelectRow){
this.controller.onSelectRow(this,_894);
}
};
this.onUnSelectRow=function(_895){
if(this.controller.onUnSelectRow){
this.controller.onUnSelectRow(this,_895);
}
};
this.onUpdateRow=function(_896){
if(this.controller.onUpdateRow){
this.controller.onUpdateRow(this,_896);
}
};
this.onEditRowStart=function(_897){
if(this.controller.onEditRowStart){
this.controller.onEditRowStart(this,_897);
}
};
this.onEditRowDone=function(){
if(this.controller.onEditRowDone){
this.controller.onEditRowDone(this);
}
};
this.fillInTemplate=function(){
this.setStyle(this.style);
this.GrdTbl.onselectstart=turbo.bind(this,this.doSelectStart);
this.DtaDiv.onscroll=turbo.bind(this,this.doScroll);
dojo.event.connect(this.DtaDiv,"onkeydown",this,"keyDown");
};
this.enableAutoResize=function(){
dojo.event.connect(window,"onresize",this,"doResize");
};
this.doSelectStart=function(){
if(window.event){
return (!window.event.ctrlKey&&!window.event.shiftKey);
}
};
this.styleChanged=function(){
this.classRoot="turbo_grid"+this.style;
this.GrdTbl.className=this.classRoot;
this.Corner.className=this.classRoot+"Corner";
this.ColDiv.className=this.classRoot+"Col";
this.HdrDiv.className=this.classRoot+"Hdr";
this.DtaDiv.className=this.classRoot+"Dta";
this.Status.className=this.classRoot+"Status";
if(this.DtaTbl){
this.updateRowClasses();
}
};
this.setStatus=function(_898,_899){
var h=(_899?"<img src=\"images/"+_899+"\" align=\"absmiddle\"/>":"");
this.Status.innerHTML=h+_898;
};
this.setReadyStatus=function(){
document.body.style.cursor="default";
this.setStatus("Ready.",this.readyImage);
};
this.setBusyStatus=function(){
this.setStatus("Busy.",this.busyImage);
document.body.style.cursor="wait";
};
this.setSize=function(_900,_901){
this.cols=_900;
this.rows=_901;
};
this.clearGrid=function(){
this.scrollLeft=0;
this.selected=[];
this.selectCount=0;
this.selectedRow=-1;
this.editingCell=null;
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
this.domNode.style.display="none";
this.getSortInfo();
this.buildTable();
this.buildFixedColumn();
this.buildHeader();
this.setScrollLeft();
this.setReadyStatus();
this.domNode.style.display="";
window.setTimeout(turbo.bind(this,this.resize),50);
};
this.getCellPos=function(_902){
return {col:_902.cellIndex,row:_902.parentNode.rowIndex};
};
this.sameCell=function(inA,inB){
return inA&&inB&&(inA.col==inB.col)&&(inA.row==inB.row);
};
this.goodCell=function(_905){
return (_905.col>=0&&_905.col<this.cols&&_905.row>=0&&_905.row<this.rows);
};
this.getDomCell=function(_906){
return this.DtaTbl.rows[_906.row].cells[_906.col];
};
this.setSortColumn=function(_907,_908){
if(this.sortInfo.column==_907&&_908===undefined){
_908=!this.sortInfo.desc;
}
this.sortInfo={column:_907,desc:_908};
};
this.getSortInfo=function(){
this.sortInfo=(this.controller.getSortInfo?this.controller.getSortInfo():this.sortInfo);
};
this.getColumnTitle=function(_909){
var h=(this.controller.getColumnTitle?this.controller.getColumnTitle(this,_909):undefined);
if(h==undefined){
h=String.fromCharCode("A".charCodeAt(0)+_909);
}
if(this.sortInfo&&_909==this.sortInfo.column){
h+="&nbsp;&nbsp;<img width=\"9\" height=\"6\" src=\"images/"+(this.sortInfo.desc?"down.gif":"up.gif")+"\"/>";
}
return h;
};
this.getDatum=function(_910,_911){
return (this.controller.getCell?this.controller.getCell(this,_910,_911):_910+", "+_911);
};
this.updateCell=function(_912){
this.getDomCell(_912).innerHTML=this.getDatum(_912.col,_912.row);
};
this.getColumnWidth=function(_913){
var w=(this.controller.getColumnWidth?this.controller.getColumnWidth(this,_913):-1);
return (w>=0?w:this.colWidth);
};
this.calcColsWidth=function(){
var sum=0;
for(var i=0;i<this.cols;i++){
sum+=this.getColumnWidth(i);
}
return sum;
};
this.calcTableWidth=function(){
return this.calcColsWidth()+this.cols*(1+6+2)+1;
};
this.getTable=function(){
return "<table width=\""+this.calcTableWidth()+"\" cellspacing=\"0\">";
};
this.createTable=function(){
var _916=document.createElement("table");
_916.cellPadding=0;
_916.cellSpacing=0;
_916.width=this.calcTableWidth();
return _916;
};
this.buildHeader=function(){
var c="";
for(var w,i=0;i<this.cols;i++){
w=this.getColumnWidth(i);
c+="<td width=\""+w+"\"><div style=\"width:"+w+"px;\">"+this.getColumnTitle(i)+"</div></td>";
}
c+="<td></td>";
var h="<tr>"+c+"</tr>";
c="";
for(var i=0;i<this.cols;i++){
c+="<td height=\"3\" class=\""+this.classRoot+"Bevel\" width=\""+this.getColumnWidth(i)+"\"></td>";
}
c+="<td></td>";
h+="<tr>"+c+"</tr>";
this.HdrDiv.innerHTML=this.getTable()+h+"</table>";
this.HdrTbl=this.HdrDiv.firstChild;
this.HdrHeight=this.HdrDiv.clientHeight;
dojo.event.connect(this.HdrTbl,"onmouseover",this,"headerOver");
dojo.event.connect(this.HdrTbl,"onmouseout",this,"headerOut");
dojo.event.connect(this.HdrTbl,"onclick",this,"headerClick");
};
this.getRowClass=function(_917){
var _918=this.rowMarkerClass[_917];
if((!_918||this.selectCount>1)&&this.selected[_917]){
_918=(this.editingRow==_917?"editing":"selected");
}
return this.classRoot+"Row"+(_917&1)+(_918?" "+this.classRoot+"_"+_918:"");
};
this.getRowHeight=function(_919){
var _920=this.DtaTbl.rows[_919].clientHeight-(dojo.render.html.ie?5:0);
return (_920>1?_920:4);
};
this.getFixedColClass=function(_921){
return this.classRoot+(this.selected[_921]?"FixedSelect":"");
};
this.formatFixedCol=function(_922){
return (this.controller.formatFixedCol?this.controller.formatFixedCol(this,_922):Number(_922)+1);
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
this.updateFixedColumnRow=function(_924){
var row=this.ColTbl.rows[_924];
var cell=row.cells[0];
cell.className=this.getFixedColClass(_924);
cell.innerHTML=this.formatFixedCol(_924);
};
this.buildCells=function(){
var _926=new Array(this.cols);
for(var i=0;i<this.cols;i++){
var w=this.getColumnWidth(i);
_926[i]="<td width=\""+w+"\"><div style=\"width:"+w+"px;\">";
}
return _926;
};
this.buildTable=function(){
var _927=this.buildCells();
var tbl=new Array(this.rows);
for(var j=0;j<this.rows;j++){
var row=new Array(this.cols);
for(var i=0;i<this.cols;i++){
row[i]=_927[i]+this.getDatum(i,j)+"</div></td>";
}
tbl[j]="<tr class=\""+this.classRoot+"Row"+(j&1)+"\">"+row.join("")+"<td>&nbsp;</td></tr>";
}
var h=tbl.join("");
this.DtaDiv.innerHTML=this.getTable()+h+"</table>";
this.DtaTbl=this.DtaDiv.firstChild;
dojo.event.connect(this.DtaTbl,"onmouseover",this,"tableOver");
dojo.event.connect(this.DtaTbl,"onmouseout",this,"tableOut");
dojo.event.connect(this.DtaTbl,"onclick",this,"tableClick");
dojo.event.connect(this.DtaTbl,"ondblclick",this,"tableDblClick");
};
this.buildCols=function(_928){
var cell;
var j=_928.rowIndex;
for(var i=0;i<this.cols;i++){
var w=this.getColumnWidth(i);
cell=_928.insertCell(i);
cell.width=w;
var h="<div style=\"width:"+w+"px;\">"+this.getDatum(i,j)+"</div>";
cell.innerHTML=h;
}
cell=_928.insertCell(this.cols);
cell.innerHTML="&nbsp;";
};
this.buildRow=function(_929){
_929.onmouseover=turbo.bindArgs(this,this.dataOver,_929);
_929.onmouseout=turbo.bindArgs(this,this.dataOut,_929);
_929.className=this.getRowClass(_929.rowIndex);
this.buildCols(_929);
};
this.hasSelection=function(){
return (this.getFirstSelectedRow()>-1);
};
this.getFirstSelectedRow=function(){
for(var i=0;i<this.rows;i++){
if(this.selected[i]){
return Number(i);
}
}
return -1;
};
this.getNextSelectedRow=function(_930){
for(var i=_930+1;i<this.rows;i++){
if(this.selected[i]){
return i;
}
}
return -1;
};
this.getSelectedRows=function(){
var _931=[];
for(var i=0;i<this.rows;i++){
if(this.selected[i]){
_931.push(i);
}
}
return _931;
};
this.clearSelection=function(){
this.finishEditRow();
this.selected=[];
this.selectedRow=-1;
this.selectCount=0;
this.updateRowClasses();
this.buildFixedColumn();
this.onSelectionChange();
};
this.offsetMarkers=function(_932,_933){
var _934=[];
for(var i in this.rowMarkerClass){
if(this.rowMarkerClass[i]){
if(i>=_932){
_934[Number(i)+_933]=this.rowMarkerClass[i];
}else{
_934[i]=this.rowMarkerClass[i];
}
}
}
this.rowMarkerClass=_934;
};
this.setMarker=function(_935,_936){
this.rowMarkerClass[_935]=_936;
this.updateRowClass(_935);
};
this.addRow=function(_937){
if(this.editingRow>-1){
dojo.debug("cannot addRow while editing");
return;
}
this.clearSelection();
this.buildRow(this.DtaTbl.insertRow(_937));
this.offsetMarkers(_937,1);
this.rows++;
this.buildFixedColumn();
this.updateRowSizes();
this.setRowSelected(_937,true);
};
this.removeRow=function(_938){
this.finishEdit();
this.rowMarkerClass[_938]=null;
this.offsetMarkers(_938,-1);
this.rows--;
this.DtaTbl.deleteRow(_938);
this.clearSelection();
};
this.updateRow=function(_939){
this.DtaTbl.deleteRow(_939);
this.buildRow(this.DtaTbl.insertRow(_939));
};
this.swapRows=function(_940,_941){
turbo.array_swap(this.rowMarkerClass,_940,_941);
this.updateRow(_940);
this.updateRow(_941);
this.updateRowSizes();
};
this.replaceRow=function(_942){
if(_942==this.selectedRow){
this.finishEdit();
}
this.updateRow(_942);
this.updateRowSizes();
};
this.updateRowSizes=function(){
for(var j=0;j<this.rows;j++){
this.ColTbl.rows[j].style.height=this.getRowHeight(j)+"px";
}
};
this.updateColSizes=function(){
for(var i=0;i<this.cols;i++){
this.HdrTbl.rows[0].cells[i].width=this.DtaTbl.rows[0].cells[i].clientWidth;
}
};
this.updateRowClass=function(_943){
this.DtaTbl.rows[_943].className=this.getRowClass(_943);
};
this.updateRowClasses=function(){
for(var j=0;j<this.rows;j++){
this.DtaTbl.rows[j].className=this.getRowClass(j);
}
};
this.getBevel=function(_944){
var _945=_944.parentNode.parentNode;
if(!_945.rows){
_945=_945.parentNode;
}
return _945.rows[1].cells[_944.cellIndex];
};
this.setElementClass=function(_946,_947){
_947=this.classRoot+_947;
if(_946.className!=_947){
_946.className=_947;
}
};
this.findEventCell=function(_948,_949){
while(_948.tagName!="TD"&&_948.parentNode&&_948.parentNode!=this.GrdTbl){
_948=_948.parentNode;
}
return (_948&&dojo.dom.isDescendantOf(_948,_949)?_948:null);
};
this.headerOver=function(_950){
var _951=this.findEventCell(_950.target,this.HdrTbl);
if(_951){
this.setElementClass(_951,"Over");
this.setElementClass(this.getBevel(_951),"BevelOver");
}
};
this.headerOut=function(_952){
var _953=this.findEventCell(_952.target,this.HdrTbl);
if(_953){
this.setElementClass(_953,"");
this.setElementClass(this.getBevel(_953),"Bevel");
}
};
this.dataOver=function(_954){
if(!this.selected[_954.rowIndex]){
this.setElementClass(_954,"RowOver");
}
};
this.dataOut=function(_955){
_955.className=this.getRowClass(_955.rowIndex);
};
this.tableOver=function(_956){
var _957=this.findEventCell(_956.target,this.DtaTbl);
if(_957){
this.dataOver(_957.parentNode);
}
};
this.tableOut=function(_958){
var _959=this.findEventCell(_958.target,this.DtaTbl);
if(_959){
this.dataOut(_959.parentNode);
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
this.getParentSize=function(){
return turbo.getContentSize(this.GrdTbl.parentNode);
};
this.doResize=function(){
var siz=this.getParentSize();
siz.w=siz.w-this.fixedColWidth-1;
turbo.setStyleWidthPx(this.HdrDiv,siz.w);
turbo.setStyleWidthPx(this.DtaDiv,siz.w);
this.DataWidth=this.calcTableWidth();
siz.w=(siz.w<this.DataWidth?this.DataWidth:siz.w-16);
var _960=function(_961,_962){
if(_961&&_962>0){
_961.width=_962;
}
};
_960(this.HdrTbl,siz.w+128+64);
_960(this.DtaTbl,siz.w);
hh=this.HdrDiv.clientHeight;
turbo.setStyleHeightPx(this.Corner,hh-1);
hh=siz.h-hh-this.Status.clientHeight-1;
turbo.setStyleHeightPx(this.ColDiv,hh);
turbo.setStyleHeightPx(this.DtaDiv,hh);
this.updateRowSizes();
};
this.resize=this.doResize;
this.setRowSelected=function(_963,_964){
if(_963<0){
return;
}
if(_964===undefined){
_964=true;
}
if(this.selected[_963]!=_964){
this.selected[_963]=_964;
this.selectedRow=(_964?_963:-1);
this.selectCount+=(_964?1:-1);
if(_964){
this.onSelectRow(_963);
}else{
this.onUnSelectRow(_963);
if(_963==this.editingRow){
this.finishEditRow();
}
}
}
this.selectedRow=(_964?_963:-1);
this.updateFixedColumnRow(_963);
this.DtaTbl.rows[_963].className=this.getRowClass(_963);
};
this.selectRow=function(_965){
if(!this.selected[_965]){
this.finishEditCell();
this.setRowSelected(_965,true);
this.updateRowSizes();
}
};
this.deselectRow=function(_966){
if(this.selected[_966]){
this.finishEditCell();
this.setRowSelected(_966,false);
this.updateRowSizes();
}
};
this.toggleSelectRow=function(_967){
if(this.selected[_967]){
this.setRowSelected(_967,false);
}else{
this.setRowSelected(_967,true);
}
};
this.unSelectRows=function(_968){
for(var i in this.selected){
if(i!=_968&&this.selected[i]){
this.setRowSelected(i,false);
}
}
};
this.clickSelect=function(_969,_970,_971){
if((!_970&&!_971)){
this.unSelectRows(_969);
}
if(!_971){
if(_970){
this.toggleSelectRow(_969);
}else{
this.setRowSelected(_969,true);
}
this.updateRowClasses();
}else{
var r=(this.selectedRow<0?0:this.selectedRow);
var s=r;
var e=_969;
if(s>_969){
e=s;
s=_969;
}
for(var i=s;i<=e;i++){
this.setRowSelected(i,true);
}
this.updateRowClass(r);
}
window.setTimeout(turbo.bind(this,this.updateRowSizes),100);
this.onSelectionChange();
};
this.editDone=function(){
if(this.controller.onEditDone){
this.controller.onEditDone(this);
}
this.editingCell=null;
};
this.cancelEditCell=function(){
if(this.editingCell){
this.updateCell(this.editingCell);
this.editDone();
}
};
this.updateEditCell=function(){
if(this.editingCell&&this.editor){
if(this.controller.onUpdateCell){
this.controller.onUpdateCell(this,this.editingCell,this.editor);
}
this.updateCell(this.editingCell);
}
};
this.finishEditCell=function(){
this.updateEditCell();
if(this.editingCell){
this.DtaTbl.rows[this.editingCell.row].className=this.getRowClass(this.editingCell.row);
}
this.editDone();
};
this.editRowStart=function(_972){
if(this.editingRow!=_972){
this.editingRow=_972;
this.onEditRowStart(_972);
}
};
this.editRowDone=function(){
this.editingRow=-1;
this.onEditRowDone();
};
this.cancelEditRow=function(){
if(this.editingRow>=0){
var row=this.editingRow;
this.editingRow=-1;
this.updateRowClass(row);
this.editRowDone();
}
};
this.finishEditRow=function(){
if(this.editingRow>=0){
var row=this.editingRow;
this.onUpdateRow(row);
this.editingRow=-1;
this.updateRowClass(row);
this.editRowDone();
}
};
this.cancelEdit=function(){
this.cancelEditCell();
this.cancelEditRow();
};
this.finishEdit=function(){
this.finishEditCell();
this.finishEditRow();
};
this.editCell=function(_973,_974,_975){
if(!_973){
return;
}
var cell=this.getCellPos(_973);
if(!this.goodCell(cell)){
return;
}
if(this.sameCell(this.editingCell,cell)){
return;
}
this.finishEditCell();
if(_974||_975){
this.finishEditRow();
this.clickSelect(cell.row,_974,_975);
}else{
this.unSelectRows(cell.row);
if(_973.parentNode==null){
return;
}
cell=this.getCellPos(_973);
if(this.controller.onEditCell){
this.editor=this.controller.onEditCell(this,cell,this.getDomCell(cell));
if(this.editor){
this.editingCell=cell;
this.editRowStart(cell.row);
window.setTimeout(turbo.bind(this,this.updateRowSizes),100);
}
}
this.setRowSelected(cell.row,true);
this.onSelectionChange();
}
if(window.getSelection){
window.getSelection().removeAllRanges();
}
};
this.tableClick=function(_976){
var _977=this.findEventCell(_976.target,this.DtaTbl);
if(_977&&(!this.controller.onClick||!this.controller.onClick(this,this.getCellPos(_977)))){
this.editCell(_977,_976.ctrlKey,_976.shiftKey);
}
};
this.tableDblClick=function(_978){
if(this.controller.onDblClick){
var _979=this.findEventCell(_978.target,this.DtaTbl);
if(_979){
return this.controller.onDblClick(this,this.getCellPos(_979));
}
}
};
this.fixedClick=function(_980,_981){
this.finishEditCell();
var cell=this.getCellPos(_981);
if(!this.controller.onFixedClick||!this.controller.onFixedClick(this,cell)){
this.clickSelect(cell.row,_980.ctrlKey,_980.shiftKey);
}
};
this.fixedTableClick=function(_982){
var _983=this.findEventCell(_982.target,this.ColTbl);
return (_983?this.fixedClick(_982,_983):true);
};
this.delayedHeaderClick=function(_984){
var idx=_984.cellIndex;
if(this.controller.onHeaderClick&&this.controller.onHeaderClick(this,idx)){
return;
}
};
this.headerClick=function(_985){
var _986=this.findEventCell(_985.target,this.HdrTbl);
if(_986){
this.setElementClass(_986,"Down");
this.setElementClass(this.getBevel(_986),"BevelOver");
this.getScrollLeft();
window.setTimeout(turbo.bindArgs(this,this.delayedHeaderClick,_986),1);
}
};
this.prevEdit=function(){
if(this.editingCell){
var cell={col:this.editingCell.col,row:this.editingCell.row};
if(--cell.col>=0){
this.editCell(this.getDomCell(cell));
}else{
if(--cell.row>=0){
this.editCell(this.getDomCell({col:this.cols-1,row:cell.row}));
}
}
}
};
this.nextEdit=function(){
if(this.editingCell){
var cell={col:this.editingCell.col,row:this.editingCell.row};
if(++cell.col<this.cols){
this.editCell(this.getDomCell(cell));
}else{
if(++cell.row<this.rows){
this.editCell(this.getDomCell({col:0,row:cell.row}));
}
}
}
};
this.keyDown=function(_987){
switch(_987.keyCode){
case _987.KEY_ESCAPE:
this.cancelEditCell();
break;
case _987.KEY_ENTER:
this.finishEditCell();
break;
case _987.KEY_TAB:
if(this.editingCell){
_987.preventDefault();
_987.stopPropagation();
if(_987.shiftKey){
this.prevEdit();
}else{
this.nextEdit();
}
}
break;
}
};
};
dojo.inherits(dojo.widget.HtmlTurboGrid,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbogrid");
if(!turbo.grid){
turbo.grid={};
}
turbo.grid.lineEditor=new function(){
this.createInput=function(_988,_989){
var i=document.createElement("input");
i.style.width=_988.clientWidth-10+"px";
if(i.clientHeight<_988.clientHeight-4){
i.style.height=_988.clientHeight-4+"px";
}
i.value=String(_989);
i.style.overflow="auto";
i.style.border=0;
_988.innerHTML="";
_988.appendChild(i);
i.select();
return i;
};
this.edit=function(_990,_991){
this.Input=this.createInput(_990,_991);
return this;
};
this.getValue=function(){
return (this.Input.value=="null"?null:this.Input.value);
};
};
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
this.setMinMax=function(_992,_993){
this.minimum=_992;
this.maximum=_993;
};
this.changePosition=function(inDx){
var p=this.getPosition();
var n=p+inDx;
return (this.setPosition(p+inDx)-p)-inDx;
};
this.setValue=function(_995){
this.setPosition(Math.round((_995-this.minimum)*this.getExtentOverRange()));
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
this.fillInTemplate=function(_997,_998){
this.loadStyle("");
this.setTheme(this.theme);
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
this.setPosition=function(_999){
var _1000=this.getWindow();
var _1001=this.getExtent();
var p=(_999>_1001?_1001:(_999<this.margin?this.margin:_999));
this.RightBar.style.marginLeft=(p&&p>0?p+"px":0);
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
this.changing=function(_1002){
};
this.change=function(_1003){
};
this.fillInTemplate=function(_1004,_1005){
this.loadStyle("");
this.setTheme(this.theme);
this.Thumb.onmousedown=function(){
return false;
};
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
this.down=function(event){
this.lastValue=this.getValue();
if(this.LeftBar.focus){
this.LeftBar.focus();
}
this.mouseDown=true;
this.mouseX=event.screenX;
turbo.capture(this.Thumb);
};
this.up=function(event){
if(this.mouseDown){
this.mouseDown=false;
turbo.release(this.Thumb);
if(this.snap){
this.setValue(this.getValue());
}
this.change(this);
}
};
this.move=function(event){
if(this.mouseDown){
var dx=event.screenX-this.mouseX;
this.mouseX=event.screenX+this.changePosition(dx);
if(dojo.render.html.safari&&window.getSelection){
window.getSelection().collapse();
}
this.changing(this);
}
};
this.wheel=function(event){
var v=this.getValue()+Math.round(event.wheelDelta/120);
this.setValue(this.getValue()+Math.round(event.wheelDelta/120));
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
this.classTag="turbo_splitter";
this.mouseDown=false;
this.mouseX=0;
this.turboAlign="";
this.changing=function(){
};
this.change=function(){
};
this.fillInTemplate=function(_1007,_1008){
this.loadStyle("");
this.setTheme(this.theme);
this.domNode.onmousedown=function(){
return false;
};
dojo.event.connect(this.domNode,"onmousedown",this,"down");
dojo.event.connect(this.domNode,"onmouseup",this,"up");
dojo.event.connect(this.domNode,"onmousemove",this,"move");
};
this.styleChanged=function(){
this.domNode.style.cursor=(this.turboAlign=="left"||this.turboAlign=="right"?"e-resize":"n-resize");
this.setClassName(this.domNode,"");
};
this.getPosition=function(){
return {top:dojo.style.getNumericStyle(this.domNode,"top"),left:dojo.style.getNumericStyle(this.domNode,"left")};
};
this.getSizeNode=function(inDx){
switch(this.turboAlign){
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
turbo.setOuterSize(this.sizeNode,this.size.w+(this.turboAlign=="right"?-inDx:inDx),this.size.h+(this.turboAlign=="bottom"?-inDy:inDy));
turbo.aligner.align();
};
this.down=function(event){
this.sizeNode=this.getSizeNode();
if(!this.sizeNode){
return;
}
this.size=turbo.getOuterSize(this.sizeNode);
this.initialPosition=this.getPosition();
this.position=this.getPosition();
this.mouseDown=true;
this.mouseX=event.screenX;
this.mouseY=event.screenY;
this.domNode.style.zIndex=1000;
turbo.capture(this.domNode);
document.body.style.cursor=this.domNode.style.cursor;
};
this.up=function(event){
if(this.mouseDown){
this.mouseDown=false;
this.domNode.style.zIndex=0;
turbo.release(this.domNode);
this.adjustSize(this.position.left-this.initialPosition.left,this.position.top-this.initialPosition.top);
document.body.style.cursor="";
this.change();
}
};
this.move=function(event){
if(this.mouseDown){
switch(this.turboAlign){
case "left":
case "right":
this.moveX(event.screenX-this.mouseX);
break;
case "top":
case "bottom":
this.moveY(event.screenY-this.mouseY);
break;
}
this.mouseX=event.screenX;
this.mouseY=event.screenY;
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
dojo.widget.HtmlTurboModule=function(){
this.widgetType="TurboModule";
dojo.widget.HtmlTurboWidget.call(this);
this.templatePath=null;
this.templateString="<div dojoAttachPoint=\"module\"></div>";
this.module=null;
this.classTag="turbo_module";
this.src="";
this.id="";
this.className="";
this.fit="";
this.sync=true;
this.delayed=false;
this.fillInTemplate=function(){
if(this.fit){
this.module.setAttribute("fit",this.fit);
}
if(!this.delayed){
this.request();
}
};
this.request=function(){
this.delayed=false;
var _1010={url:this.src,sync:this.sync,load:turbo.bind(this,this.receive),error:turbo.bind(this,this.error)};
if(dojo.io.bind(_1010)===false){
this.module.innerHTML="bind failed: "+_1010.url;
}
};
this.error=function(type,error){
this.module.innerHTML=error;
};
this.receive=function(type,data,evt){
this.module.innerHTML=data;
var frag=new dojo.xml.Parse().parseElement(this.module);
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
this.templateString="<div dojoAttachPoint=\"pageContainer\" turboAlign=\"client\"></div>";
this.pageContainer=null;
this.classTag="turbo_notebook";
this.count=0;
this.pages=[];
this.modules=[];
this.selected=-1;
this.className="";
this.fit="";
this.id="";
this.src="";
this.sync=true;
this.delayed=false;
this.addPage=function(_1012,_1013){
this.pages.push(_1012);
this.pageContainer.appendChild(_1012);
var _1014=null;
if(_1013){
var frag=new dojo.xml.Parse().parseElement(_1012);
var comps=dojo.widget.getParser().createComponents(frag);
if(comps&&comps.length>1&&comps[1]&&comps[1].length>0&&comps[1][0].widgetType=="TurboModule"){
_1014=comps[1][0];
}
}
this.modules.push(_1014);
};
this.hidePage=function(_1016){
this.pages[_1016].style.display="none";
};
this.showPage=function(_1017){
this.pages[_1017].style.display="";
};
this.showHidePage=function(_1018,_1019){
if(_1019){
this.showPage(_1018);
}else{
this.hidePage(_1018);
}
};
this.fillInTemplate=function(_1020,_1021){
if(this.fit){
this.pageContainer.setAttribute("fit",this.fit);
}
var nodes=this.getWidgetFragment(_1021);
for(var i in nodes.childNodes){
if(nodes.childNodes[i].tagName=="DIV"){
this.addPage(nodes.childNodes[i].cloneNode(true),true);
}
}
this.count=this.pages.length;
this.selectPage(0);
};
this.selectPage=function(_1023){
for(var i in this.pages){
this.showHidePage(i,(i==_1023));
}
this.selected=_1023;
};
};
dojo.inherits(dojo.widget.HtmlTurboNotebook,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbonotebook");
dojo.provide("turbo.widgets.TurboButton");
dojo.provide("turbo.widgets.HtmlTurboButton");
dojo.require("turbo.widgets.TurboWidget");
turbo.button=new function(){
this.groups=[];
this.states={normal:0,down:1,disabled:2,over:3,selected:1};
this.resetGroup=function(_1024){
var g=turbo.button.groups[_1024];
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
this.btn=null;
this.onClick=function(){
};
this.initButton=function(){
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
this.setGroup(this.group);
this.setTheme(this.theme);
};
this.setGroup=function(_1025){
if(_1025){
this.group=_1025;
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
this.updateButton=function(_1026){
dojo.debug("abstract function TurboButtonBase.updateButton invoked.");
};
this.setState=function(_1027){
if(turbo.isString(_1027)){
_1027=(_1027?this.states[_1027]:this.states.normal);
}
if(this.group&&_1027==this.states.down){
turbo.button.resetGroup(this.group);
}
this.state=_1027;
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
this.onMouseDown=function(){
if(!this.toggle&&this.state!=this.states.disabled){
this.setState(this.states.down);
}
this.blur();
};
this.onMouseUp=function(){
if(!this.toggle&&this.state!=this.states.disabled){
this.setState(this.states.normal);
}
};
this.onMouseClick=function(){
if(this.state!=this.states.down){
this.onMouseDown();
window.setTimeout(turbo.bind(this,this.onMouseUp),100);
}
if(this.toggle&&(!this.group||this.state!=this.states.down)){
this.setState(this.state!=this.states.down?this.states.down:this.states.normal);
}
this.onClick();
};
};
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
var a=["","Down","","Over",];
this.btn.className=this.classTag+a[this.state];
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
var a=["Off","Down","Off","Over",];
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
dojo.widget.HtmlTurboToolbtn=function(){
this.widgetType="TurboToolbtn";
dojo.widget.TurboButtonBase.call(this);
this.templatePath=null;
this.templateString="<button dojoAttachPoint=\"btn\" dojoAttachEvent=\"onMouseOver; onMouseOut; onclick: onMouseClick;\"><div dojoAttachPoint=\"div\"><img dojoAttachPoint=\"img\"></div><span dojoAttachPoint=\"span\">Some Caption</span></button>";
this.classTag="turbo_toolbtn";
this.glyph="";
this.image="";
this.caption="";
this.btn=null;
this.span=null;
this.div=null;
this.img=null;
this.fillInTemplate=function(_1028){
this.initButton();
this.setCaption(this.caption?this.caption:this.value);
this.setGlyph(this.image?this.image:this.glyph);
};
this.styleChanged=function(){
this.btn.className=this.classTag;
this.updateButton();
};
this.updateButton=function(){
this.btn.disabled=(this.state==this.states.disabled?"disabled":"");
this.btn.className=this.classTag+["","_down","","_over"][this.state];
};
this.setCaption=function(_1029){
this.span.innerHTML=_1029;
};
this.setGlyph=function(_1030){
if(_1030){
this.img.src="images/"+_1030;
}else{
this.img.style.display="none";
}
};
this.set=function(_1031,_1032){
this.setCaption(_1031);
this.setGlyph(_1032);
};
};
dojo.inherits(dojo.widget.HtmlTurboToolbtn,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbotoolbtn");
dojo.widget.HtmlTurboButton=function(){
turbo.setWidgetType(this,"TurboButton");
dojo.widget.TurboButtonBase.call(this);
this.templatePath=null;
this.templateString="<div class=\"turbo_btnDiv\" dojoAttachPoint=\"btnDiv\"><button disabled=\"disabled\" dojoAttachPoint=\"btnLeft\">&nbsp;</button><button dojoAttachPoint=\"btn\" dojoAttachEvent=\"onMouseOver; onMouseOut; onMouseDown; onMouseUp; onclick: onMouseClick;\">Caption</button><button  disabled=\"disabled\" dojoAttachPoint=\"btnRight\">&nbsp;</button></div>";
this.classTag="turbo_btn";
this.hideLeft="";
this.hideRight="";
this.btnDiv=null;
this.btn=null;
this.btnLeft=null;
this.btnRight=null;
this.fillInTemplate=function(){
this.initButton();
if(this.value){
this.btn.innerHTML=this.value;
}
if(this.hideLeft){
this.btnLeft.style.display="none";
}
if(this.hideRight){
this.btnRight.style.display="none";
}
};
this.styleChanged=function(){
this.btnDiv.className=this.classTag+this.style+"Div";
this.updateButton();
};
this.getButtonClass=function(){
return ["","Down","","Over"][this.state];
};
this.setButtonClasses=function(){
var cn=this.classTag+this.style+this.getButtonClass();
this.btnLeft.className=cn+"Left";
this.btn.className=cn+"Mid";
this.btnRight.className=cn+"Right";
};
this.updateButton=function(){
this.btn.disabled=(this.state==this.states.disabled?"disabled":"");
this.setButtonClasses();
};
};
dojo.inherits(dojo.widget.HtmlTurboButton,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turboButton");
dojo.widget.HtmlTurboTab=function(){
this.widgetType="TurboTab";
dojo.widget.HtmlTurboButton.call(this);
this.classTag="turbo_tab";
this.getButtonClass=function(){
return ["","Down","","Over"][this.state];
};
};
dojo.inherits(dojo.widget.HtmlTurboTab,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turboTab");
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
this.onSelectTab=function(){
};
this.fillInTemplate=function(){
this.setTheme(this.theme);
};
this.inheritedAddChild=this.addChild;
this.addChild=function(child){
child.setGroup(this.widgetId);
child.setTheme(this.style);
child.tabIndex=this.children.length;
child.onClick=this._tabClick;
return this.inheritedAddChild(child);
};
this.styleChanged=function(){
this.containerNode.className=this.classTag+this.style;
for(var i in this.children){
this.children[i].setTheme(this.style);
}
};
var self=this;
this._tabClick=function(){
self.tabClick(this);
};
this.tabClick=function(inTab){
this.lastIndex=this.tabIndex;
this.tabIndex=inTab.tabIndex;
this.onSelectTab();
};
this.selectTab=function(_1035){
this.tabIndex=_1035;
this.children[this.tabIndex].setState("down");
};
};
dojo.inherits(dojo.widget.HtmlTurboTabbar,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbotabbar");
dojo.widget.HtmlTurboPagebar=function(){
this.widgetType="TurboPagebar";
dojo.widget.HtmlTurboTabbar.call(this);
this.templateString="<div dojoAttachPoint=\"master\" turboAlign=\"client\"><div dojoAttachPoint=\"containerNode\" turboAlign=\"top\"></div><div dojoAttachPoint=\"pages\" turboAlign=\"client\"></div></div>";
this.master=null;
this.pages=null;
this.classTag="turbo_pagebar";
this.contentId="";
this.inheritedFillInTemplate=this.fillInTemplate;
this.fillInTemplate=function(){
this.inheritedFillInTemplate();
this.installPages();
};
this.inheritedStyleChanged=this.styleChanged;
this.styleChanged=function(){
this.inheritedStyleChanged();
this.pages.className=this.classTag+this.style+"Pages";
};
this.installPages=function(){
if(this.contentId){
this.content=document.getElementById(this.contentId);
}
if(this.content){
this.content.parentNode.removeChild(this.content);
this.pages.appendChild(this.content);
}
};
};
dojo.inherits(dojo.widget.HtmlTurboPagebar,dojo.widget.HtmlWidget);
dojo.widget.tags.addParseTreeHandler("dojo:turbopagebar");
dojo.provide("turbo.data.store");
turbo.data={};
turbo.data.fields=function(_1036){
var _1037=_1036;
this.defaultField=new _1037();
this.fields=[];
this.clearFields=function(){
this.fields=[];
};
this.mergeField=function(inSrc,inDst){
for(var i in inSrc){
inDst[i]=inSrc[i];
}
return inDst;
};
this.setDefaultField=function(_1040){
if(typeof (_1040)!="object"){
alert("setDefaultField: bad input field object. Are your field definitions included?");
}
turbo.swiss(_1040,this.defaultField);
};
this.setField=function(_1041,_1042){
var f=this.fields[_1041];
if(!f){
f=turbo.swiss(this.defaultField,new _1037());
}
for(var i=1;i<arguments.length;i++){
f=turbo.swiss(arguments[i],f);
}
this.fields[_1041]=f;
};
this.getField=function(_1043){
var _1044=(_1043>=0?this.fields[_1043]:null);
if(!_1044){
_1044=this.defaultField;
}
_1044.index=_1043;
return _1044;
};
this.setFields=function(_1045){
for(i=0;i<_1045.length;i++){
this.setField(i,_1045[i]);
}
};
};
turbo.data.comparator=function(inCol){
var idx=inCol;
return function(a,b){
return (a[idx]>b[idx]?1:(a[idx]==b[idx]?0:-1));
};
};
turbo.data.field=function(_1047){
this.name=_1047;
this.comparator=turbo.data.comparator;
this.getComparator=function(inCol,_1048){
var _1049=this.comparator(inCol);
if(!_1048){
return _1049;
}else{
return function(a,b){
return -_1049(a,b);
};
}
};
};
turbo.data.store=function(){
turbo.data.fields.call(this,turbo.data.field);
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
this.getFieldNameIndex=function(_1050){
for(var i in this.fields){
if(this.fields[i].name==_1050){
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
this._setSortField=function(_1051,_1052){
if(_1052){
this.sortDesc=_1052;
}else{
if(this.sortField==_1051){
this.sortDesc=!this.sortDesc;
}else{
this.sortDesc=false;
}
}
this.sortField=_1051;
};
this.setSortIndex=function(_1053,_1054){
if(_1053<0){
this.sortField="";
}else{
this._setSortField(this.getField(_1053).name,_1054);
}
this.sortIndex=_1053;
};
this.setSortField=function(_1055,_1056){
if(_1055=""){
this.setSortIndex(-1);
}else{
this._setSortField(_1055,_1056);
this.sortIndex=this.getFieldNameIndex(this.sortField);
}
};
};
turbo.data.arrayStore=function(){
turbo.data.store.call(this);
this.data=[];
this.setData=function(_1057){
this.data=_1057;
};
this.getColCount=function(){
var _1058=this.fields.length;
var _1059=(this.data&&this.data.length?this.data[0].length:0);
return Math.max(_1058,_1059);
};
this.getRowCount=function(){
return (this.data&&this.data.length?this.data.length:0);
};
this.getDatum=function(inCol,inRow){
if(djConfig.isDebug&&(inRow<0||inRow>=this.data.length)){
dojo.debug("turbo.data.arrayStore.getDatum: bad row",inRow);
return null;
}
return this.data[inRow][inCol];
};
this.setDatum=function(inCol,inRow,_1061){
this.data[inRow][inCol]=_1061;
};
this.getRow=function(inRow){
return this.data[inRow];
};
this.copyRow=function(inRow){
return this.data[inRow].slice(0);
};
this.addRow=function(_1062,_1063){
var c=this.getColCount();
if(!_1063){
_1063=[];
}
for(var i=0;i<c;i++){
if(_1063[i]==undefined){
_1063[i]=this.getField(i).defaultValue;
}
}
if(this.data.length>0){
this.data.splice(_1062,0,_1063);
}else{
this.data=[_1063];
}
};
this.compareRow=function(inRow,_1064){
var c=this.getColCount();
if(!_1064||_1064.length!=c){
return false;
}
var row=this.getRow(inRow);
for(var i=0;i<c;i++){
if(_1064[i]!==row[i]){
return false;
}
}
return true;
};
this.removeRow=function(inRow){
this.data.splice(inRow,1);
};
this.replaceRow=function(inRow,_1065){
this.data[inRow]=_1065;
};
this.swapRows=function(_1066,_1067){
turbo.array_swap(this.data,_1066,_1067);
};
this.hasData=function(){
return (this.fields.length>0)&&(this.data.length>0)&&(this.data[0].length>0);
};
this.sortData=function(){
if(this.sortIndex>=0&&this.hasData()){
this.data.sort(this.getField(this.sortIndex).getComparator(this.sortIndex,this.sortDesc));
}
};
};
turbo.data.pagedStore=function(){
turbo.data.arrayStore.call(this);
this.totalRows=0;
this.rowsPerPage=50;
this.pageCount=0;
this.pages=[];
this.page=-1;
this.invalidPage=-1;
this.requestPage=function(_1068){
};
this.setTotalRows=function(_1069){
this.totalRows=_1069;
this.paginate();
};
this.getPageLength=function(_1070){
var page=(_1070==this.page?this.data:this.pages[_1070]);
return (page?page.length:this.rowsPerPage);
};
this.paginate=function(){
this.pageCount=Math.ceil(this.totalRows/this.rowsPerPage);
};
this.getTopRow=function(_1072){
var page=(_1072===undefined?this.page:_1072);
return page*this.rowsPerPage;
};
this.getBottomRow=function(_1073){
var row=this.getTopRow(_1073)+this.getPageLength(_1073)-1;
return Math.min(row,this.totalRows-1);
};
this.fillPage=function(_1074,_1075){
this.pages[_1074]=_1075;
};
this.selectPage=function(_1076){
this.page=_1076;
if(!this.pages[_1076]){
this.requestPage(_1076);
}else{
dojo.debug("pagedStore: using cached page "+_1076);
}
if(this.pages[_1076]){
this.invalidPage=-1;
this.data=this.pages[_1076];
}else{
this.invalidPage=_1076;
this.data=[];
}
};
this.invalidatePage=function(){
turbo.debug("invalidating page "+this.page);
this.pages[this.page]=null;
};
this.pageIsValid=function(_1077){
return (this.pages[_1077]?true:false);
};
this.reloadPage=function(){
this.pages[this.page]=null;
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
};
dojo.require("turbo.data.store");
dojo.provide("turbo.data.sql");
turbo.sql={};
turbo.sql.templates={createDb:"CREATE DATABASE IF NOT EXISTS `{db}`",dropDb:"DROP DATABASE `{db}`",rename:"RENAME TABLE `{oldName}` TO `{newName}`",create:"CREATE TABLE IF NOT EXISTS `{tableN}` ({columns})",alter:"ALTER TABLE `{table}` {columns}",drop:"DROP TABLE `{table}`",insert:"INSERT INTO `{table}` ({fields}) VALUES({values})",deleteFrom:"DELETE FROM `{table}` WHERE {where} LIMIT 1",update:"UPDATE `{table}` SET {set} WHERE {where} LIMIT 1",select:"SELECT * FROM `{table}` {where} {orderby} {limit}",count:"SELECT COUNT(*) FROM `{table}` {where}"};
turbo.sql.buildQuery=function(_1078,_1079){
return turbo.supplant(turbo.sql.templates[_1078],_1079);
};
turbo.sql.bq=function(_1080){
return "`"+_1080+"`";
};
turbo.data.sqlTableStore=function(_1081,_1082){
turbo.data.pagedStore.call(this);
this.service=_1081;
this.adapter=_1082;
this.clear=function(){
this.setTotalRows(0);
this.pages=[];
this.page=-1;
this.setSortIndex(-1);
};
this.selectTable=function(_1083,_1084){
this.clear();
this.db=_1083;
this.table=_1084;
this.fetchSchema();
};
this._fetch_schema=function(_1085){
if(!_1085.error&&_1085.result){
this.schema=_1085.result;
this.adapter.adaptFields(this,this.schema.columns);
this.setTotalRows(this.schema.count);
}else{
turbo.debug(_1085.error);
}
};
this.fetchSchema=function(inDb,_1087){
this._fetch_schema(this.service.get_table_schema(this.table,this.db));
};
this._fill_page=function(_1088){
if(_1088&&!_1088.error&&_1088.result){
this.fillPage(this.page,_1088.result);
}else{
turbo.debug((_1088?_1088.error:"sql._fill_page: no response"));
}
};
this.repaginate=function(){
var _1089=this.pageCount;
var _1090=this.page;
var _1091=this.service.get_row_count(this.table,this.db);
if(!_1091.error){
var rows=_1091.result;
}else{
var rows=0;
}
this.pages=[];
if(this.page>=0){
this.pages[this.page]=this.data;
}
this.setTotalRows(_1091.result);
if(this.page>=this.pageCount){
this.page=this.pageCount-1;
}
return (_1089!=this.pageCount||_1090!=this.page);
};
this.sort=function(){
this.pages=[];
this.selectPage(this.page);
};
this.getOrderBy=function(){
return (!this.sortField?"":" ORDER BY "+turbo.sql.bq(this.sortField)+(this.sortDesc?" DESC":""));
};
this.getSql=function(_1093){
return turbo.sql.buildQuery("select",{table:this.table,where:"",orderby:this.getOrderBy(),limit:"LIMIT "+this.getTopRow(_1093)+", "+this.rowsPerPage});
};
this.requestPage=function(_1094,_1095){
if(!this.table||!this.db){
return;
}
var sql=this.getSql(_1094);
dojo.debug(sql);
if(_1095){
this.service.execute_sql(sql,this.db,turbo.bind(this,this._fill_page));
}else{
this._fill_page(this.service.execute_sql(sql,this.db));
}
};
this.requestPageAsync=function(_1097){
this.requestPage(_1097,true);
};
};
dojo.provide("turbo.grid.basicGrid");
turbo.grid.basicGrid=function(_1098){
this.grid=(turbo.isString(_1098)?dojo.widget.manager.getWidgetById(_1098):_1098);
this.grid.controller=this;
this.setModel=function(_1099){
this.model=_1099;
if(this.model){
this.build();
}
};
this.getColCount=function(){
return this.model.getColCount();
};
this.build=function(){
this.grid.setSize(this.getColCount(),this.model.getRowCount());
this.grid.build();
};
this.getColumnTitle=function(_1100,inCol){
return this.model.getField(inCol).name;
};
this.getCell=function(_1101,inCol,inRow){
return this.model.getDatum(inCol,inRow);
};
this.setReadonly=function(_1102){
if(_1102!=this.readonly){
this.readonly=_1102;
this.build();
}
};
};
dojo.provide("turbo.grid.columns");
turbo.grid.editor=new function(){
this.edit=function(_1103,_1104){
return false;
};
this.getValue=function(){
return null;
};
};
turbo.grid.format=function(_1105,_1106){
var s="width:"+this.width+"px;";
if(_1105==null){
s+=" color: #CCBBB3;";
_1105="~";
}else{
if(typeof (_1105)=="string"&&_1105.length>128){
_1105="(text)";
}
}
return "<div style=\""+s+"\">"+_1105+"</div>";
};
turbo.grid.formatLine=turbo.grid.format;
turbo.grid.column=function(_1107){
this.name=(_1107?_1107:"");
this.width=-1;
this.readonly=false;
this.editor=null;
this.format=turbo.grid.format;
this.getEditor=function(){
if(!this.editor){
return turbo.grid.editor;
}else{
this.editor.column=this;
return this.editor;
}
};
};
turbo.grid.formatInt=function(_1108){
var s="";
s+="width:"+(this.width-4)+"px;";
s+=" text-align: right; padding-right: 4px;";
if(_1108==null){
s+=" color: #CCBBB3;";
_1108="~";
}
return "<div style=\""+s+"\">"+_1108+"</div>";
};
turbo.grid.formatAutoInc=function(_1109){
var s="";
s+="width:"+(this.width-4)+"px;";
s+=" text-align: right;";
if(_1109===undefined){
s+=" color: #CCBBB3;";
_1109="auto";
}
return "<div style=\""+s+"\">"+_1109+"</div>";
};
turbo.grid.editLine=new function(){
this.createInput=function(_1110,_1111){
var i=document.createElement("input");
i.value=(_1111===undefined?"":String(_1111));
i.style.width=_1110.clientWidth-10+"px";
i.style.overflow="auto";
_1110.innerHTML="";
_1110.appendChild(i);
i.style.border=0;
if(i.clientHeight<_1110.clientHeight-4){
i.style.height=_1110.clientHeight-4+"px";
}
i.select();
i.focus();
return i;
};
this.edit=function(_1112,_1113){
this.Input=this.createInput(_1112,_1113);
};
this.getValue=function(){
return (this.Input.value=="null"?null:(this.Input.value=="undefined"?undefined:this.Input.value));
};
};
turbo.grid.edit=turbo.grid.editLine;
turbo.grid.editMultiLine=new function(){
this.createInput=function(_1114,_1115){
var i=document.createElement("textarea");
i.value=String(_1115);
i.rows=2;
i.style.width=_1114.clientWidth-8+"px";
_1114.innerHTML="";
_1114.appendChild(i);
if(i.clientHeight<_1114.clientHeight-4){
i.style.height=_1114.clientHeight-4+"px";
}
i.select();
i.focus();
return i;
};
this.edit=function(_1116,_1117){
this.Input=this.createInput(_1116,_1117);
};
this.getValue=function(){
return (this.Input.value=="null"?null:this.Input.value);
};
};
turbo.grid.formatBool=function(_1118,_1119){
var _1120=(this.readonly||_1119||!this.editor);
_1118=(_1118?parseInt(_1118)!=0:false);
return "<div style=\"width:"+this.width+"px; text-align: center\">"+"<input type=\"checkbox\"\""+(_1120?" disabled=\"disabled\"":"")+(_1118?" checked=\"checked\"":"")+"/>"+"</div>";
};
turbo.grid.editBool=new function(){
this.edit=function(_1121,_1122){
while(_1121.tagName!="INPUT"){
_1121=_1121.childNodes[0];
}
this.Input=_1121;
};
this.getValue=function(){
return (this.Input.checked?1:0);
};
};
turbo.grid.formatEnum=function(_1123,_1124){
if(this.readonly||_1124){
return turbo.grid.format(_1123,this.width);
}
var opts="";
if(this.options){
for(var i in this.options){
opts+="<option"+(this.options[i]==_1123?" selected":"")+">"+this.options[i]+"</option>";
}
}else{
opts="<option>"+_1123+"</option>";
}
return "<div>"+"<select>"+opts+"</select>"+"</div>";
};
turbo.grid.editEnum=new function(){
this.edit=function(_1126,_1127){
while(_1126.tagName!="SELECT"){
_1126=_1126.childNodes[0];
}
this.Input=_1126;
};
this.getValue=function(){
var value;
for(var i in this.Input.childNodes){
if(this.Input.childNodes[i].selected){
value=this.Input.childNodes[i].innerHTML;
break;
}
}
return value;
};
};
turbo.grid.decimalIsOk=function(_1129,inKey){
return (inKey=="."&&!/[\.]/.test(_1129));
};
turbo.grid.isIntChar=function(_1131){
return (_1131.search(/[-+\0\t\n\r\d]/)!=-1);
};
turbo.grid.editInt=new function(){
this.limitToInteger=function(_1132,_1133,_1134,_1135){
if(!turbo.grid.isIntChar(String.fromCharCode(_1133.charCode))){
_1133.preventDefault();
}
};
this.createInput=turbo.grid.editLine.createInput;
this.edit=function(_1136,_1137){
var self=this;
var i=this.createInput(_1136,_1137);
dojo.event.connect(i,"onkeypress",function(_1138){
self.limitToInteger(i,_1138,self.column.maxValue,self.column.minValue);
});
this.Input=i;
};
this.getValue=turbo.grid.editLine.getValue;
};
turbo.grid.editDecimal=new function(){
this.limitToDecimal=function(_1139,_1140,_1141,_1142,_1143){
var s=String.fromCharCode(_1140.charCode);
if(!turbo.grid.isIntChar(s)&&!turbo.grid.decimalIsOk(_1139.value,s)){
_1140.preventDefault();
}
};
this.createInput=turbo.grid.editLine.createInput;
this.edit=function(_1144,_1145){
var self=this;
var i=this.createInput(_1144,_1145);
dojo.event.connect(i,"onkeypress",function(_1146){
self.limitToDecimal(i,_1146,self.column.decimals,self.column.maxValue,self.column.minValue);
});
this.Input=i;
};
this.getValue=turbo.grid.editLine.getValue;
};
turbo.grid.basicColumn={width:128,format:turbo.grid.formatLine,editor:turbo.grid.editLine};
turbo.grid.boolColumn={width:48,format:turbo.grid.formatBool,editor:turbo.grid.editBool};
turbo.grid.enumColumn={width:96,format:turbo.grid.formatEnum,editor:turbo.grid.editEnum};
turbo.grid.autoIncColumn={format:turbo.grid.formatAutoInc,editor:turbo.grid.editLine,readonly:true};
dojo.provide("turbo.grid.dataGrid");
dojo.require("turbo.grid.basicGrid");
dojo.require("turbo.grid.columns");
turbo.grid.transactions=function(){
this.xact=[];
this.clear=function(){
this.xact=[];
};
this.get=function(_1147){
return this.xact[_1147];
};
this.set=function(_1148,_1149){
if(!this.xact[_1148]){
this.xact[_1148]={cache:_1149};
}
};
this.unset=function(_1150){
this.xact[_1150]=null;
};
this.insert=function(_1151){
this.xact.splice(_1151,0,{cache:[],insert:true});
};
this.remove=function(_1152){
this.xact.splice(_1152,1);
};
this.pending=function(){
for(var i in this.xact){
if(this.xact[i]){
return true;
}
}
return false;
};
this.dump=function(){
dojo.debug("edit cache:");
for(var i=0;i<this.xact.length;i++){
if(this.xact[i]){
dojo.debug(i+"=>");
turbo.debug(this.xact[i]);
}
}
};
};
turbo.grid.dataGrid=function(_1153){
turbo.grid.basicGrid.call(this,_1153);
this.columns=new turbo.data.fields(turbo.grid.column);
this.edits=new turbo.grid.transactions();
this.readonly=false;
this.onCanSort=function(inCol){
};
this.clear=function(){
this.edits.clear();
};
this.basicBuild=this.build;
this.build=function(){
this.clear();
this.basicBuild();
};
this.getColCount=function(){
var _1154=this.columns.fields.length;
return (_1154?_1154:this.model.getColCount());
};
this.getColumn=function(inCol){
return this.columns.getField(inCol);
};
this.setColumn=function(_1155,inCol){
this.columns.setField(_1155,inCol);
};
this.setColumns=function(_1156){
this.columns.setFields(_1156);
};
this.getColumnWidth=function(_1157,inCol){
return this.getColumn(inCol).width;
};
this.getDatum=function(inCol,inRow){
return this.model.getDatum(inCol,inRow);
};
this.getCell=function(_1158,inCol,inRow){
return this.getColumn(inCol).format(this.getDatum(inCol,inRow),this.readonly,inRow);
};
this.getColumnTitle=function(_1159,inCol){
var t=this.getColumn(inCol).name;
return (t?t:this.model.getField(inCol).name);
};
this.getSortInfo=function(){
return {column:this.model.sortIndex,desc:this.model.sortDesc};
};
this.onHeaderClick=function(_1160,inCol){
if(!this.onCanSort(inCol)){
return;
}
this.applyEdit();
this.model.setSortIndex(inCol);
this.model.sort();
this.build();
};
this.setRow=function(inRow,_1161){
this.model.replaceRow(inRow,_1161);
this.grid.updateRow(inRow);
};
this.onBeginEdit=function(_1162,_1163){
};
this.onEditCell=function(_1164,_1165,_1166){
if(!_1166){
dojo.debug("onEditCell got a bad node");
return null;
}
var _1167=(this.readonly?null:this.columns.getField(_1165.col).getEditor());
if(_1167){
if(_1167.edit(_1166,this.getDatum(_1165.col,_1165.row))===false){
_1167=null;
}else{
this.onBeginEdit(this,_1165);
}
}
return _1167;
};
this.onUpdateCell=function(_1168,_1169,_1170){
this.model.setDatum(_1169.col,_1169.row,_1170.getValue());
};
this.onEditRowStart=function(_1171,inRow){
if(!this.edits.get(inRow)){
this.edits.set(inRow,this.model.copyRow(inRow));
}
};
this.commitRow=function(inRow){
this.edits.unset(inRow);
};
this._updateRow=function(inRow){
if(!this.edits.get(inRow)){
dojo.debug("dataGrid._updateRow: no edit cache found for "+inRow);
}
if(!this.edits.get(inRow)){
return;
}
var _1172=!this.model.compareRow(inRow,this.edits.get(inRow).cache);
if(_1172){
this.commitRow(inRow);
}else{
this.grid.setMarker(inRow,"");
this.edits.unset(inRow);
}
};
this.onUpdateRow=function(_1173,inRow){
this._updateRow(inRow);
};
this.applyEdit=function(){
if(this.grid.editingRow>=0){
this.grid.finishEdit();
}
};
this.applyEditSync=function(){
if(this.grid.editingRow>=0){
this.edits.get(this.grid.editingRow).sync=true;
this.grid.finishEdit();
}
};
this.cancelEdit=function(){
if(this.grid.editingRow>=0){
var row=this.grid.editingRow;
this.grid.cancelEdit();
var edit=this.edits.get(row);
if(edit){
if(edit.insert){
this.removeRow(row);
}else{
this.model.replaceRow(row,edit.cache);
this.grid.updateRow(row);
this.grid.setMarker(row,"");
}
this.edits.unset(row);
}
}
};
this.addRow=function(inRow,_1175){
this.applyEdit();
this.model.addRow(inRow,_1175);
this.grid.addRow(inRow);
this.edits.insert(inRow);
this.grid.editingRow=inRow;
this.grid.setRowSelected(inRow);
this.grid.onSelectionChange();
};
this.newRow=function(){
var row=this.grid.getFirstSelectedRow()+1;
this.addRow(Math.max(row,0));
};
this.removeRow=function(inRow){
this.edits.remove(inRow);
this.model.removeRow(inRow);
this.grid.removeRow(inRow);
};
this.removeSelectedRows=function(){
var rows=this.grid.getSelectedRows();
for(var i=0;i<rows.length;i++){
this.removeRow(rows[i]-i);
}
};
this.swapRows=function(inI,inJ){
this.grid.finishEdit();
this.model.swapRows(inI,inJ);
this.grid.swapRows(inI,inJ);
};
this.canMoveRow=function(_1176){
var src=this.grid.selectedRow;
var dst=src+_1176;
return (src>=0&&dst>=0&&dst<this.grid.rows);
};
this.moveRow=function(_1178){
if(!this.canMoveRow(_1178)){
return;
}
this.grid.finishEdit();
var src=this.grid.selectedRow;
var dst=src+_1178;
this.swapRows(src,src+_1178);
this.grid.setRowSelected(src,false);
this.grid.setRowSelected(dst,true);
this.grid.onSelectionChange();
};
this.moveRowUp=function(){
this.moveRow(-1);
};
this.moveRowDown=function(){
this.moveRow(1);
};
};
dojo.provide("turbo.grid.pagedGrid");
dojo.require("turbo.grid.dataGrid");
turbo.grid.pagedGrid=function(_1179){
turbo.grid.dataGrid.call(this,_1179);
this.setModel=function(_1180){
this.model=_1180;
if(this.model){
this.model.selectPage(0);
this.build();
}
};
this._selectPage=function(_1181,_1182){
this.model.selectPage(_1181);
this.build();
this.grid.setReadyStatus();
if(_1182){
_1182();
}
};
this.selectPage=function(_1183,_1184){
this.grid.setBusyStatus();
this.grid.teardownRows();
window.setTimeout(turbo.bindArgs(this,this._selectPage,_1183,_1184),20);
};
this.reloadPage=function(){
this.model.reloadPage();
this.build();
};
this.formatFixedCol=function(_1185,inRow){
return Number(inRow)+1+this.model.getTopRow();
};
};
dojo.provide("turbo.grid.adodb");
turbo.adodb={};
turbo.adodb.columnTypes=["tinyint","smallint","mediumint","int","integer","bigint","real","double","float","decimal","numeric","bit","bool","char","varchar","date","time","year","timestamp","datetime","tinyblob","blob","mediumblob","longblob","tinytext","text","mediumtext","longtext","enum","set"];
turbo.adodb.categories={boolTypes:["tinyint","bool","int"],integerTypes:["tinyint","smallint","mediumint","int","integer","bigint"],decimalTypes:["real","double","float","decimal","numeric"],oneLineStringTypes:["char","varchar","tinytext","string"],multiLineStringTypes:["text","mediumtext","longtext"],enumTypes:["enum"],dateTypes:["date","time","year","timestamp","datetime"],blobTypes:["bit","tinyblob","blob","mediumblob","longblob"],setTypes:["set"],inCategory:function(_1186,_1187){
var cat=this[_1187];
for(var i in cat){
if(_1186==cat[i]){
return true;
}
}
return false;
}};
turbo.adodb.adapter=new function(){
this.colMinWidth=30;
this.colDefaultWidth=80;
this.colMaxWidth=250;
this.charWidth=7;
this.dateTimeWidth=120;
this.adaptFields=function(_1189,_1190){
_1189.clearFields();
var idx=0;
for(var i in _1190){
_1189.setField(idx++,{name:_1190[i].name,defaultValue:this.getDefaultValue(_1190[i])});
}
};
this.getDefaultValue=function(_1191){
return (_1191.has_default?_1191.default_value:(_1191.not_null?this.manufactureDefault(_1191):null));
};
this.manufactureDefault=function(_1192){
var value="";
if(_1192.auto_increment){
value=undefined;
}else{
if(turbo.adodb.categories.inCategory(_1192.type,"integerTypes")||turbo.adodb.categories.inCategory(_1192.type,"boolTypes")){
value="0";
}else{
if(turbo.adodb.categories.inCategory(_1192.type,"decimalTypes")){
value="0";
if(_1192.scale>0){
value+=".0";
}
for(var i=1;i<_1192.scale;i++){
value+="0";
}
}
}
}
return value;
};
this.adaptColumns=function(_1193,_1194){
_1193.clearFields();
_1193.setDefaultField(turbo.grid.basicColumn);
var idx=0;
for(var i in _1194){
var ci=_1194[i];
_1193.setField(idx++,this.getBaseColumn(ci),{name:ci.name,width:this.getColumnWidth(ci),maxLength:ci.max_length,decimals:ci.scale});
}
};
this.getBaseColumn=function(_1196){
var c=turbo.adodb.categories;
var t=_1196.type;
if(c.inCategory(t,"boolTypes")&&_1196.max_length==1){
return turbo.grid.boolColumn;
}else{
if(c.inCategory(t,"integerTypes")){
if(_1196.auto_increment){
return turbo.grid.autoIncColumn;
}else{
return {format:turbo.grid.formatInt,editor:turbo.grid.editInt};
}
}else{
if(c.inCategory(t,"decimalTypes")){
return {format:turbo.grid.formatInt,editor:turbo.grid.editDecimal};
}else{
if(c.inCategory(t,"oneLineStringTypes")){
return {editor:turbo.grid.edit};
}else{
if(c.inCategory(t,"multiLineStringTypes")){
return {editor:turbo.grid.editMultiLine};
}else{
if(c.inCategory(t,"enumTypes")){
return {format:turbo.grid.formatEnum,editor:turbo.grid.editEnum,options:_1196.values};
}else{
return {};
}
}
}
}
}
}
};
this.getColumnWidth=function(_1197){
var w=this.colDefaultWidth;
if(_1197.type=="datetime"){
w=this.dateTimeWidth;
}else{
if(turbo.adodb.categories.inCategory(_1197.type,"multiLineStringTypes")){
w=this.colMaxWidth;
}else{
if(_1197.max_length>0){
var _1198=(_1197.max_length<96?6:(_1197.max_length<128?3:1));
w=Math.max(this.colMinWidth,Math.min(this.colMaxWidth,Math.round(_1197.max_length*_1198)));
}
}
}
if(turbo.adodb.categories.inCategory(_1197.type,"enumTypes")){
w+=32;
}
return Math.max(w,_1197.name.length*this.charWidth);
};
};
turbo.adodb.schemaAdapter=new function(){
this.adaptData=function(_1199){
var data=[];
for(var i in _1199){
var info=_1199[i];
data.push([(info.primary_key!==null?info.primary_key:0),info.name,info.type,info.max_length,info.scale,(info.has_default?info.default_value:null),info.not_null,info.auto_increment,info.unsigned,info.zerofill,info.binary,(info.values?info.values:null),info.name]);
}
return data;
};
this.defaultRow=[null,"","mediumint",null,null,null,0,0,0,0,0,null];
this.adaptFields=function(_1201){
_1201.setField(2,{defaultValue:"int"});
};
this.adaptColumns=function(_1202){
_1202.clearFields();
_1202.setDefaultField(turbo.grid.basicColumn);
_1202.setField(0,{name:"Primary Key"},turbo.grid.boolColumn);
_1202.setField(1,{name:"Name",width:148,editor:turbo.grid.editLine});
_1202.setField(2,{name:"Type",defaultValue:"int",options:turbo.adodb.columnTypes},turbo.grid.enumColumn);
_1202.setField(3,{name:"Length",width:60,editor:turbo.grid.editInt});
_1202.setField(4,{name:"Decimals",width:60,editor:turbo.grid.editInt});
_1202.setField(5,{name:"Default",width:80,editor:turbo.grid.editLine});
_1202.setField(6,{name:"Allow Null",defaultValue:false},turbo.grid.boolColumn);
_1202.setField(7,{name:"Auto Increment",defaultValue:false},turbo.grid.boolColumn);
_1202.setField(8,{name:"Unsigned",defaultValue:false},turbo.grid.boolColumn);
_1202.setField(9,{name:"Zerofill",defaultValue:false},turbo.grid.boolColumn);
_1202.setField(10,{name:"Binary",defaultValue:false},turbo.grid.boolColumn);
_1202.setField(11,{name:"Values",width:150});
};
};