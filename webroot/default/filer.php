<?php
/*!
 * Anrip Web Explorer
 * 作者: 若海 & 尛岢 (http://www.rehiy.com)
 * 说明: 出于安全考虑,默认仅允许管理当前目录及下级目录文件
 */

@error_reporting(0);
@date_default_timezone_set('Etc/GMT-8');

class anrip_explorer {
    public $rip = '';
    public $act = 'view';
    public $rot = '.';
    public $way = '';
    //构造函数
    public function __construct() {
        $this->rip = basename(__FILE__);
        isset($_GET['act']) && $this->act = $_GET['act'];
        isset($_GET['way']) && $this->way = $_GET['way'];
        list($this->rot, $this->way) = $this->safeWay($this->rot, $this->way);
    }
    //执行内部方法
    public function run() {
        $act = $this->act;
        if(!method_exists($this, $act)) {
            return "Sorry, I Can't Do It.";
        }
        return $this->$act();
    }
    //浏览目录
    private function view() {
        $way1 = $this->way ? $this->way.'/' : '';
        $way2 = $this->rot.$way1;
        if(!is_dir($way2)) {
            return "Sorry, Directory Not Found.";
        }
        $rip = $this->rip;
        //目录导航
        $lead = "<div class='lead'>位置:<a class='s1' href='{$rip}'>//</a>";
        if($way1) {
            foreach(($ways = explode('/', $way1)) as $k => $v1) {
                if(!$v1) { continue; }
                $v2 = iconv("GB2312", "UTF-8//IGNORE", $v1);
                $v3 = urlencode(implode('/', array_slice($ways, 0, $k+1)));
                $lead .= "<a href='{$rip}?act=view&way={$v3}'>{$v2}/</a>";
            }
        }
        $lead .= "</div>";
        //目录列表
        $list = $list1 = $list2 = '';
        $hide = array('.' , '..', $rip , 'archive');
        foreach((array)scandir($way2) as $item) {
            if(in_array($item, $hide)) { continue; }
            //扩展信息
            $path = $way2.$item;
            $stat = stat($path);
            $type = "<td class='d2'>{$this->fileType($path)}</td>";
            $size = "<td class='d3'>{$this->fileSize($stat['size'])}</td>";
            $time = "<td class='d4'>{$this->easyTime($stat['mtime'])}</td>";
            //基础信息
            $name = iconv("GB2312", "UTF-8//IGNORE", $item);
            $way3 = $way1.$item;
            $way4 = urlencode($way3);
            if(is_dir($path)) {
                $name = "<td class='d1'><a href='{$rip}?act=view&way={$way4}'>{$name}</a></td>";
                $list1 .= "<tr>{$name}{$type}{$size}{$time}</tr>";
            } else {
                $name = "<td class='d1'><a href='{$way3}' target='_blank'>{$name}</a></td>";
                $list2 .= "<tr>{$name}{$type}{$size}{$time}</tr>";
            }
        }
        $list = "<table id='tab1'>{$list1}{$list2}</table>";
        return $lead.$list;
    }
    //输出图片
    private function image_bg() {
        header("Content-Type: image/gif");
        header("Cache-Control: max-age=86400,must-revalidate");
        header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
        header("Expires: ".gmdate("D, d M Y H:i:s", time()+86400)." GMT");
        exit(base64_decode('R0lGODlhCgAKAKIAAAAAAP///9ra2tTU1P///wAAAAAAAAAAACH5BAEAAAQALAAAAAAKAAoAAAMTOKrSvcO9JQWsV2YHI98W2GlgAgA7'));
    }
    //格式化时间戳
    private function easyTime($timestamp = 0) {
        return $timestamp > 0 ? date('Y-m-d H:i:s', $timestamp) : date($timeformat);
    }
    //获取文件类型
    private function fileType($way) {
        $type = filetype($way);
        $desc = array('dir'=>'目录','link'=>'连接','file'=>'文件');
        return isset($desc[$type]) ? $desc[$type] : '其他';
    }
    //获取文件访问权限
    private function filePerms($way) {
        return substr(decoct(fileperms($way)),-4);
    }
    //优化显示文件大小
    private function fileSize($size) {
        if($size < 1024) return $size.' B';
        elseif($size < 1048576) return round($size/1024,0).' KB';
        elseif($size < 1073741824) return round($size/1048576,1).' MB';
        else return round($size/1073741824,2).' GB';
    }
    //获取安全路径
    private function safeWay($rot, $way) {
        if($rot = realpath($rot)) {
            $rot = strtr($rot.'/', '\\', '/');
            if($way = realpath($way)) {
                $way = strtr($way, '\\', '/');
                $way = strpos($way, $rot) === 0 ? str_replace($rot, '', $way) : '';
                return array($rot, $way);
            }
            return array($rot, '');
        }
        return array('', '');
    }
}

//输出数据
$exr = new anrip_explorer();
$rip = $exr->rip; $out = $exr->run();
if($_GET['inajax']) {
    header('Content-type: text/html; charset=utf-8');
    exit($out);
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>若海实验室</title>
<script type="text/javascript">
/*
 @name HRise JS Core
 @author anrip[wang@rehiy.com]
 @version 2.1, 2012-09-21 10:00
 @website http://lab.rehiy.com/hrise/
 Dual licensed under the MIT and GPL licenses
*/
(function(e,f){var k=e.document,g=Array.prototype.slice,m=Object.prototype.toString,l=Object.prototype.hasOwnProperty,j=function(){var a=function(b,c,o){if(!0===c||1===c||"string"!==typeof b)o=!0,c=f;return o?new a.fn.run(b,c):p(b,c)};a.fn={run:function(b,c){if(!b)return this;if(b.nodeType)return this.context=this[0]=b,this.length=1,this;if("body"===b&&!c&&k.body)return this.context=k,this[0]=k.body,this.selector=b,this.length=1,this;if("string"===typeof b){var o=d(b,c);this.context=c||k;this.selector=
b;this.length=o.length;a.extend(this,o);return this}b.selector!==f&&(this.selector=b.selector,this.context=b.context);return this},get:function(a){return null==a?this.toArray():0>a?this[this.length+a]:this[a]},toArray:function(){return g.call(this,0)},selector:"",length:0};a.fn.run.prototype=a.fn;var c={};a.cache=function(a,h){c[a]===f&&(c[a]={});if(h===f)return c[a];c[a]=h};a.extend=a.fn.extend=function(){var b=1,c=!1,o,d,g=arguments.length,e=arguments[0]||{};"boolean"===typeof e&&(c=arguments[0]||
{},e=arguments[1]||{},b=2);"object"!==typeof e&&!a.isFunction(e)&&(e={});g===b&&(e=this,--b);for(var k=b;k<g;k++)if(null!=arguments[k])for(o in arguments[k])if(b=arguments[k][o],e!==b&&b!==f)if(c&&(a.isPlainObject(b)||(d=a.isArray(b)))){var j=e[o];d?(d=!1,j=j&&a.isArray(j)?j:[]):j=j&&a.isPlainObject(j)?j:{};e[o]=a.extend(c,j,b)}else e[o]=b;return e};a.extend({error:function(a){throw Error(a);},objType:function(a){if(null==a)return""+a;a=m.call(a).toLowerCase().replace(/(\[object\s)|\]/g,"");return 0<
"[boolean,number,string,function,array,date,regexp,object]".indexOf(a)?a:"object"},isArray:Array.isArray||function(b){return"array"===a.objType(b)},isWindow:function(a){return a&&"object"===typeof a&&"setInterval"in a},isFunction:function(b){return"function"===a.objType(b)},isEmptyObject:function(a){for(var c in a)return!1;return!0},isPlainObject:function(b){if(!b||"object"!==a.objType(b)||b.nodeType||a.isWindow(b))return!1;try{if(b.constructor&&!l.call(b,"constructor")&&!l.call(b.constructor.prototype,
"isPrototypeOf"))return!1}catch(c){return!1}for(var o in b);return o===f||l.call(b,o)},isString:function(a){return"string"===typeof a}});return a}(),p=function(a,c){var b=/^(#)?([\w\-_]+)$/.exec(a)||[];if(b[2]){c=c||k;if(b[1])return c.getElementById(b[2]);(a=c.getElementById(b[2]))||(a=c.getElementsByTagName(b[2]));return a}return d(a,c)},d=function(){var a=/(?:[\w\-\\.#]+)+(?:\[\w+?=([\'"])?(?:\\\1|.)+?\1\])?|\*|>/ig,c=/^(?:[\w\-_]+)?\.([\w\-_]+)/,b=/^(?:[\w\-_]+)?#([\w\-_]+)/,h=/^([\w\*\-_]+)/,
f=/^[\w\-_#]+$/,d=[null,null],e=function(a){try{return g.call(a)}catch(b){for(var c=0,h=[],f=a.length;c<f;++c)h[c]=a[c];return h}},j=function(){var a=1,b=+new Date;return function(c){for(var h=i=0,f,d=[],o=c.length;i<o;++i){f=c[i];var e;e=f;var g=e[b],k=a++;g?e=!1:(e[b]=k,e=!0);e&&(d[h++]=f)}b+=1;return d}}(),l=function(a,f,o){var e=a.pop();if(">"===e)return l(a,f,!0);for(var g=[],k=-1,y=-1,j=(e.match(b)||d)[1],m=!j&&(e.match(c)||d)[1],e=!j&&(e.match(h)||d)[1],p,t,u,e=e&&e.toLowerCase();p=f[++y];){t=
p.parentNode;do if(u=(u=(u=!e||"*"===e||e===t.nodeName.toLowerCase())&&(!j||t.id===j))&&(!m||RegExp("(^|\\s)"+m+"(\\s|$)").test(t.className)),o||u)break;while(t=t.parentNode);u&&(g[++k]=p)}return a[0]&&g[0]?l(a,g):g},m=function(g,q){q=q||k;if(!f.test(g)&&q.querySelectorAll)try{return e(q.querySelectorAll(g))}catch(p){return[]}if(-1<g.indexOf(",")){for(var s=g.split(","),n=0,r=[],v=s.length;n<v;++n)r=r.concat(m(s[n],q));return j(r)}s=g.match(a);n=s.pop();if(r=(n.match(b)||d)[1])return(n=q.getElementById(r))?
[n]:[];r=(n.match(c)||d)[1];n=(n.match(h)||d)[1];if(r&&!n&&q.getElementsByClassName)n=e(q.getElementsByClassName(r));else if(n=e(q.getElementsByTagName(n||"*")),r){for(var r=RegExp("(^|\\s)"+r+"(\\s|$)"),v=-1,w,z=-1,x=[];w=n[++v];)r.test(w.className)&&(x[++z]=w);n=x}return s[0]&&n[0]?l(s,n):n};return m}();e.HRise=j})(window);
(function(e,f,k){var g=e.document,m=Array.prototype.slice,l=/^[\],:{}\s]*$/,j=/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,p=/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,d=/(?:^|:|,)(?:\s*\[)+/g;f.extend({trim:function(a){return a?a.replace(/^\s+|\s+$/g,""):a},strlen:function(a){for(var c=0,b=f.browser.charset,h=0;h<a.length;h++)c+=0>a.charCodeAt(h)||255<a.charCodeAt(h)?"utf-8"==b?3:2:1;return c},cutstr:function(a,c,b){for(var h=0,d="",e=f.browser.charset,b=f.isString(b)?b:"...",c=
c-b.length,g=0;g<a.length;g++){h+=0>a.charCodeAt(g)||255<a.charCodeAt(g)?"utf-8"==e?3:2:1;if(h>c){d+=b;break}d+=a.substr(g,1)}return d},each:function(a,c,b){var h,d=a.length,e=d===k||f.isFunction(a);if(b===k)if(e)for(h in a){if(!1===c.call(a[h],h,a[h]))break}else{h=0;for(e=a[0];h<d&&!1!==c.call(e,h,e);e=a[++h]);}else if(b=m.call(arguments,2),e)for(h in a){if(!1===c.apply(a[h],b))break}else for(h=0;h<d&&!1!==c.apply(a[h],b);h++);return a},merge:function(a,c){var b=a.length,h=c.length;if("number"===
typeof h){for(var d=0;d<h;d++)a[b++]=c[d];a.length=d}else for(d in c)a[d]=c[d];return a},inArray:function(a,c){if(c.indexOf)return c.indexOf(a);for(var b=0,h=c.length;b<h;b++)if(c[b]===a)return b;return-1},addElement:function(a,c){var b,c=c||{};c.id&&(b=f("#"+c.id));b||(b=g.createElement(a));for(var h in c)b[h]=c[h];return b},nowStyle:function(a,c){return a.currentStyle?a.currentStyle[c]:e.getComputedStyle?(c=c.replace(/([A-Z])/g,"-$1").toLowerCase(),g.defaultView.getComputedStyle(a,null)[c]):null},
addEvent:function(a,c,b){a.addEventListener?a.addEventListener(c,b,!1):a.attachEvent&&a.attachEvent("on"+c,b)},delEvent:function(a,c,b){a.removeEventListener?a.removeEventListener(c,b,!1):a.detachEvent&&a.detachEvent("on"+c,b)},stopBubble:function(a){a=a||e.event;a.preventDefault?a.preventDefault():a.returnValue=!1;a.stopPropagation?a.stopPropagation():a.cancelBubble=!0},ready:function(a){var c=f.cache("ready");if(c.isReady)return a();c.readyTimer?c.readyList.push(a):(c.readyList=[a],a=function(){!c.isReady&&
(g&&g.getElementById&&g.getElementsByTagName&&g.body)&&(clearInterval(c.readyTimer),c.readyTimer=null,c.isReady=!0,f.each(c.readyList,function(){this.call(g,f)}),c.readyList=null)},f.addEvent(e,"load",a),c.readyTimer=setInterval(a,100))},drag:function(a,c){var b=f("#"+a);if(!b)return!1;var h=c?f("#"+c):b;isNaN(parseInt(h.style.top))&&(h.style.top="0px");isNaN(parseInt(h.style.left))&&(h.style.left="0px");var d=function(a){a=a||e.event;f.stopBubble(a);var c=a.clientY,a=a.clientX;h.style.top=Math.max(Math.min(parseInt(h.style.top)+
c-b.lastY,g.documentElement.clientHeight-h.offsetHeight),0)+"px";h.style.left=Math.max(Math.min(parseInt(h.style.left)+a-b.lastX,g.documentElement.clientWidth-h.offsetWidth),0)+"px";b.lastX=a;b.lastY=c},j=function(){b.releaseCapture?b.releaseCapture():e.captureEvents&&e.captureEvents(Event.MOUSEMOVE|Event.MOUSEUP);f.delEvent(g,"mousemove",d);f.delEvent(g,"mouseup",j)};f.addEvent(b,"mousedown",function(a){a=a||e.event;f.stopBubble(a);b.lastX=a.clientX;b.lastY=a.clientY;b.setCapture?b.setCapture():
e.captureEvents&&e.captureEvents(Event.MOUSEMOVE|Event.MOUSEUP);f.addEvent(g,"mousemove",d);f.addEvent(g,"mouseup",j)})},browser:function(){var a=navigator.userAgent.toLowerCase(),c=(a.match(/.+(?:rv|it|ra|ie)[\/:\s]([\d.]+)/)||[0,"0"])[1];return{opera:/opera/.test(a)&&c,webkit:/webkit/.test(a)&&c,msie:/msie/.test(a)&&!/opera/.test(a)&&c,gecko:/gecko/.test(a)&&!/khtml/.test(a)&&c,charset:(g.charset||g.characterSet).toLowerCase()}}(),locate:function(a){var c=0,b=0;null==a&&(a=e.event);if(a.pageX||
a.pageY)c=a.pageX,b=a.pageY;else if(a.clientX||a.clientY)g.documentElement.scrollTop?(c=a.clientX+g.documentElement.scrollLeft,b=a.clientY+g.documentElement.scrollTop):(c=a.clientX+g.body.scrollLeft,b=a.clientY+g.body.scrollTop);return[b,c]},cookie:function(a,c,b){if(null===a){for(var b=g.cookie||"",h,a=b.split("; "),b=0;b<a.length;b++)h=a[b].split("="),0<h.length&&f.cookie(h[0],null,c);return!0}if(c===k)return b=g.cookie||"",(h=b.match(RegExp("(^|s)"+a+"=([^;]*)(;|$)")))?decodeURIComponent(h[2]):
null;a!==k&&(b=b||{},null===c?(a+="=",b.expires=-1):a+="="+encodeURIComponent(c),b.expires&&("number"==typeof b.expires?(c=new Date,c.setTime(c.getTime()+864E5*b.expires),a+="; expires="+c.toGMTString()):b.expires.toGMTString()&&(a+="; expires="+b.expires.toGMTString())),b.path&&(a+="; path="+b.path),b.domain&&(a+="; domain="+b.domain),b.secure&&(a+="; secure"),g.cookie=a)},ajaxConfig:{url:"",data:"",type:"GET",async:!0,start:null,error:null,success:null},ajax:function(a){var c,a=a||{};if(f.isString(a.url)){a.type=
a.type||"GET";a.data=a.data||null;a.url+=(a.url.match(/\?/)?"&":"?")+"inajax=1";"GET"==a.type&&a.data&&(a.url+="&"+a.data,a.data=null);a.async===k&&(a.async=!0);c=e.ActiveXObject?new ActiveXObject("Microsoft.XMLHTTP"):new XMLHttpRequest;c.onreadystatechange=function(){c.readyState==1?f.isFunction(a.start)&&a.start():c.readyState==4&&(c.status==200?f.isFunction(a.success)&&a.success(c.responseText):f.isFunction(a.error)&&a.error(c.status))};try{c.open(a.type,a.url,!a.async),a.data&&c.setRequestHeader("Content-Type",
"application/x-www-form-urlencoded"),"GET"==a.type&&c.setRequestHeader("If-Modified-Since","0"),c.setRequestHeader("X-Requested-With","XMLHttpRequest"),c.setRequestHeader("Accept","*/*"),c.send(a.data)}catch(b){f.isFunction(a.error)&&a.error(0)}}},loadCSS:function(a,c){var b,h;if("link"==c)h=f.addElement("link",{rel:"stylesheet",href:a});else try{g.createStyleSheet().cssText=a}catch(d){h=f.addElement("style",{type:"text/css",textContent:a})}h&&(b=f("head")[0]||g.documentElement,b.appendChild(h))},
evalScript:function(a,c,b){if("link"==c){var h=f("head")[0]||g.documentElement,d=f.addElement("script",{type:"text/javascript"}),j=function(){h.removeChild(d);f.isFunction(b)&&b()};f.browser.msie?d.onreadystatechange=function(){var a=d.readyState;("loaded"==a||"complete"==a)&&j()}:d.onload=function(){j()};d.src=a;h.insertBefore(d,h.firstChild)}else{if("ajax"==c)return f.ajax({url:a,success:function(a){f.evalScript(a,"text",b)}});e.eval.call(e,a);f.isFunction(b)&&b()}},submitForm:function(a,c){var a=
f.isString(a)?f("#"+a):a,b,d=f("#tarframe1986"),e=a.action,j=a.target;if(d){if(d.loading)return c(!1),!1}else b=f("body")[0]||g.documentElement,d=f.addElement("iframe",{id:"tarframe1986",name:"tarframe1986"}),d.style.display="none",b.appendChild(d);d.loading=1;f.addEvent(d,"load",function(){d.loading=0;a.action=e;a.target=j;try{c(d.contentWindow.document.body.innerHTML)}catch(b){c(null)}});a.action=e?e.replace(/[\?\&]inajax\=1/g,""):"";a.action=a.action+(a.action.match(/\?/)?"&":"?")+"inajax=1";a.mothod=
a.mothod?a.mothod:"POST";a.target="tarframe1986";a.submit();return!1},parseForm:function(a,c){for(var a=f.isString(a)?f("#"+a):a,b,d=a.elements,e={},g=0;g<d.length;++g)if(b=d[g],b.name)if("select-multiple"==b.type){for(var j=b.options,k=j.length,l,m=0,p=[],q=0;q<k;++q)l=j[q],l.selected&&(p[m++]=l.value);e[b.name]=p.join(",")}else"checkbox"==b.type||"radio"==b.type?b.checked&&(e[b.name]=b.value):e[b.name]=b.value;if(c){b=[];for(m in e)b.push(m+"="+encodeURIComponent(e[m]));e=b.join("&")}return e},
parseJson:function(a){if(!f.isString(a)||!(a=f.trim(a)))return null;if(e.JSON&&e.JSON.parse)return e.JSON.parse(a);if(l.test(a.replace(j,"@").replace(p,"]").replace(d,"")))return(new Function("return "+a))();f.error("Invalid JSON: "+a)},parseXml:function(a,c,b){e.DOMParser?(b=new DOMParser,c=b.parseFromString(a,"text/xml")):(c=new ActiveXObject("Microsoft.XMLDOM"),c.async="false",c.loadXML(a));b=c.documentElement;return!b||!b.nodeName||"parsererror"===b.nodeName?null:c},parseUrl:function(a){var c,
b={},a=a||location.href;c=a.indexOf("?");if(-1!=c)for(var a=a.substr(c+1).split("&"),d=0;d<a.length;d++)c=a[d].split("="),b[c[0]]=unescape(c[1]);return b},rand:function(a,c){return a>=c?a:parseInt(Math.random()*(c-a)+a)},time:function(a,c){if(a){var b=a.match(/((\d{4})\D(\d\d)\D(\d\d))(\s(\d\d)\D(\d\d)\D(\d\d))?/)||[];if(b[8])a=new Date(b[2],b[3]-1,b[4],b[6],b[7],b[8]);else if(b[4])a=new Date(b[2],b[3]-1,b[4]);else return 0}return Math.round((a||new Date).getTime()/1E3)+3600*(c||8)},timeout:function(a,
c,b){var d=b?m.call(arguments,2):[];return setTimeout(function(){a.apply(null,d)},1E3*c)}})})(window,HRise);(function(e,f){f.fn.extend({show:function(){for(var e=0;e<this.length;e++)this[e].style.display="block"},hide:function(){for(var e=0;e<this.length;e++)this[e].style.display="none"}})})(window,HRise);
(function(e,f,k){var g=document.documentElement,m=f.browser.msie,l=[0],j=[0],p=function(){this.init.apply(this,arguments)};p.prototype={dbox:function(d){d=f.merge({draid:"",boxid:"dlBox",layid:"dlLay",color:"#000",alpha:0,index:1986},d||{});j[0]||(j[0]=d.index);if(j[1]===k){j[1]=this.get(d.layid,"div");var a=j[1].style;a.position="fixed";a.top=a.left=0;a.zIndex=d.index;a.display="none";a.width=a.height="100%";a.backgroundColor=d.color;m?a.filter="alpha(opacity:"+d.alpha+")":a.opacity=d.alpha/100;
6==m&&(j[1].resize=function(){a.height=Math.max(g.scrollHeight,g.clientHeight)+"px"},a.position="absolute",j[1].resize(),f.addEvent(e,"resize",j[1].resize),j[1].innerHTML='<iframe class="fmselc"></iframe>')}l[d.boxid]||(l[d.boxid]=this.get(d.boxid,"div"),l[d.boxid].style.position="absolute",l[d.boxid].style.display="none");d.draid&&f.drag(d.draid,d.boxid)},show:function(d,a){j[1].style.display="inline";var c=l[d];c.SN=++l[0];c.onHide=a||null;c.style.zIndex=++j[0];c.style.display="inline";c.resize=
function(){c.style.top=g.scrollTop+Math.max((g.clientHeight-c.clientHeight)/2,0)+"px";c.style.left=g.scrollLeft+Math.max((g.clientWidth-c.clientWidth)/2,0)+"px"};c.resize();f.addEvent(e,"resize",c.resize)},hide:function(d,a){var c=0,b=l[d].SN,a=a===k?!0:a,h;for(h in l){var g=l[h];(a?g.SN>=b:g.SN==b)?(f.isFunction(g.onHide)&&(g.onHide(),g.onHide=null),g.SN=0,g.style.display="none",f.delEvent(e,"resize",g.resize)):0<g.SN&&c++}0==c&&(l[0]=0,j[1].style.display="none",6==m&&f.delEvent(e,"resize",j[1].resize))},
get:function(d,a){var c=document,b=c.getElementById(d);if(!b&&a){var e=c.body||c.documentElement,b=c.createElement(a);b.id=d;e.insertBefore(b,e.firstChild)}return b},init:function(d){f.loadCSS(".dlbox { padding: 5px; background: rgba(0,0,0,0.2); border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px; -khtml-border-radius: 5px; }.dlbox .dlwrap { background: #fff; border: 1px solid #999; }.dlbox .dlwrap a.dlhide { position: absolute; top: 5px; right: 11px; font: 18px Tahoma; color: #369; text-decoration: none; }.dlbox .dlwrap a.dlhide { outline: none; blr: expression(this.onFocus=this.blur()); } .dlbox .dlwrap a.dlhide:hover { color: #000; }.dlbox .dlhead { cursor: move; padding: 6px 25px 4px 6px; height: 13px; font: bold 13px/13px \\5b8b\\4f53; color: #369; }.dlbox .dlbody { margin: 0; padding: 0; }.dlbox .dlfoot { height: 24px; background: #f2f2f2; border-top: 1px solid #ccc; padding: 6px; text-align: right; }.dlbox .dlfoot p { float: left; margin-top: 6px; font: 400 12px/14px \\5b8b\\4f53; }.dlbox .dlfoot b { margin-left: 5px; float: right; display: inline-block; *display: inline; zoom: 1; }.dlbox .dlfoot b { padding: 6px 8px 4px; border: solid 1px; border-color: #c2d5e3 #369 #369 #c2d5e3; }.dlbox .dlfoot b { background: #e5edf2; color: #369; font: 700 12px/1 \\5b8b\\4f53; cursor: pointer; }");
m&&9>m&&f.loadCSS(".dlbox { zoom: 1; background: none; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#34000000,endColorstr=#34000000); }.dlBox .fmselc { position: absolute; top: 0; left: 0; width: 100%; height: 100%; filter: alpha(opacity=0); }");this.lang=d||["\u786e \u5b9a","\u53d6 \u6d88"]},template:function(d,a){var c=('<table><tr><td class="dlbox"><div class="dlwrap">'+(a.title?'<div id="[id]_head" class="dlhead">'+a.title+"</div>":"")+(""!==a.title?'<a id="[id]_hide" class="dlhide" href="javascript:;">\u00d7</a>':
"")+'<div class="dlbody">'+(d||"")+"</div>"+(a.info||a.onBtn1?'<div class="dlfoot">'+(a.info?'<p id="[id]_info">'+a.info+"</p>":"")+(a.onBtn1?'<b id="[id]_btn1">'+this.lang[0]+'</b><b id="[id]_btn2">'+this.lang[1]+"</b>":"")+"<div>":"")+"</div></td></tr></table>").replace(/\[id\]_/g,a.id+"_");this.get(a.id+"_wrap","div").innerHTML=c;this.dbox({draid:a.id+"_head",boxid:a.id+"_wrap",alpha:a.alpha||0});(function(b){""!==a.title&&(b.get(a.id+"_hide").onclick=function(){b.hide(a.id+"_wrap")});a.onBtn1&&
(b.get(a.id+"_btn1").onclick=function(){b.hide(a.id+"_wrap");f.isFunction(a.onBtn1)&&a.onBtn1()},b.get(a.id+"_btn2").onclick=function(){b.hide(a.id+"_wrap")});var c=a.id+"_timer";b[c]&&(clearTimeout(b[c]),b[c]=null);a.timer&&(b[c]=setTimeout(function(){b.hide(a.id+"_wrap",false);b[c]=null},a.timer))})(this)},frame:function(d,a){if("string"==typeof d)try{this.hide(d+"_wrap",a)}catch(c){}else{this.template('<iframe id="[id]_frame" frameborder="0" style="display:block;"></iframe>',d);var b=this.get(d.id+
"_frame");isNaN(d.width)||(b.style.width=d.width+"px");isNaN(d.height)||(b.style.height=d.height+"px");f.isFunction(d.onLoad)&&f.addEvent(b,"load",function(){d.onLoad()});b.src=d.url+(d.url.match(/\?/)?"&":"?")+"iframe=1";this.show(d.id+"_wrap",function(){b.parentNode.removeChild(b)})}},notice:function(d,a){if("string"==typeof d)try{this.hide(d+"_wrap",a)}catch(c){}else{this.template('<div id="[id]_notice"></div>',d);var b=this.get(d.id+"_notice");b.innerHTML=d.content;isNaN(d.width)||(b.style.width=
d.width+"px");isNaN(d.height)||(b.style.height=d.height+"px");this.show(d.id+"_wrap",d.onHide)}}};f.DialogBox=p})(window,HRise);
////////////////////////////////////////
var $ = HRise;
var rip = "<?=$rip;?>";
var dlBox = new $.DialogBox();
var dialog = function(c, b, o, t) {
    if(!c) { return dlBox.notice('dialog'); }
    c = '<div class="dialog">' + c + '</div>';
    t = typeof(t) === 'undefined' ? '信息' : t;
    dlBox.notice({id:'dialog', content:c, title:t, onHide:o, onBtn1:b});
}
$.ready(function() {
    //版权信息
    $('#copy a')[0].onclick = function(msg) {
        dialog(''
            + '<div class="about">'
            + '<p><b>主要成员:</b>'
            + ' <a href="http://www.rehiy.com" target="_blank">若海</a>'
            + ' & <a href="http://www.kerring.net" target="_blank">尛岢</a>'
            + ' & <a href="http://www.phpye.com" target="_blank">小小宇</a>'
            + '</p>'
            + '<p><b>项目博客:</b> <a href="http://www.rehiy.com" target="_blank">http://www.rehiy.com</a></p>'
            + '<p><b>技术论坛:</b> <a href="http://www.phpye.com" target="_blank">http://www.phpye.com</a></p>'
            + '<p><b>源码托管:</b> <a href="http://lab.rehiy.com/svn" target="_blank">http://lab.rehiy.com/svn</a></p>'
            + '<p><b>项目说明:</b> 每个根目录都是一个独立项目，您可以查看或下载所有项目文件。</p>'
            + '</div>', null, null,
            '若海实验室'
        );
        return false;
    }
    //鼠标滑过效果
    for(var trs = $('#tab1 tr'),n=trs.length,i=0; i<n; i++) {
        trs[i].onmouseout = function(){ this.style.background=''; }
        trs[i].onmouseover = function(){ this.style.background='#eee'; }
    }
});
</script>
<style type="text/css">
* { margin:0;padding:0; }
html { color:#333;background:#ccc url(<?=$rip;?>?act=image_bg); }
body { font:12px/24px tahoma,helvetica,arial,sans-serif; }
table { border-collapse:collapse;border-spacing:0;text-align:left; }
a { color:#333;text-decoration:none; }
a:hover { color:#f00;text-decoration:underline; }
.dialog { padding: 0 5px 5px 5px; }
.wrap, .head, .foot { width:640px; padding:5px 10px; }
.lead { padding-bottom:3px;font-size:13px;font-weight:bold; }
.lead .s1 { padding-left:5px; }
#tab1 { width:100%; }
#tab1 .d1{ padding-left:20px; }
#tab1 .d2 { width:50px;text-align:right; }
#tab1 .d3 { width:100px;text-align:right; }
#tab1 .d4 { width:150px;text-align:right; }
#copy { float:right; }
#copy a { font-weight:bold; }
#about h3 { padding-bottom:10px; }
</style>
</head>
<body>
<div class="head">
    <h2><a href="http://www.rehiy.com">若海实验室</a></h2>
</div>
<div class="wrap">
    <?=$out;?>
</div>
<div class="foot">
    <span id="copy">&copy; 2005-2017 <a href="http://www.rehiy.com">Anrip</a> Lab, All Rights Reserved</span>
</div>
</body>
</html>