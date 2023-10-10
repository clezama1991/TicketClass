<html lang="en"><head>
  <meta charset="UTF-8">
  
 
    <meta name="apple-mobile-web-app-title" content="CodePen">
   
  <title>Ticket(s)</title>
  
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&amp;display=swap">
<style>
.ticket {
  display: flex;
  font-family: Roboto;
  margin: 16px;
  border: 1px solid #E0E0E0;
  position: relative;
      border-radius: 10px;
  border: none!important;
}
.ticket:before {
  content: "";
  width: 32px;
  height: 32px;
  background-color: #fff;
  border: 1px solid #E0E0E0;
  border-top-color: transparent;
  border-left-color: transparent;
  position: absolute;
  transform: rotate(-45deg);
  left: -18px;
  top: 50%;
  margin-top: -16px;
  border-radius: 50%;
}
.ticket:after {
  content: "";
  width: 32px;
  height: 32px;
  background-color: #fff;
  border: 1px solid #E0E0E0;
  border-top-color: transparent;
  border-left-color: transparent;
  position: absolute;
  transform: rotate(135deg);
  right: -18px;
  top: 50%;
  margin-top: -16px;
  border-radius: 50%;
}
.ticket--start {
  position: relative;
  border-right: 1px dashed #E0E0E0;
}
.ticket--start:before {
  content: "";
  width: 32px;
  height: 32px;
  background-color: #fff;
  border: 1px solid #E0E0E0;
  border-top-color: transparent;
  border-left-color: transparent;
  border-right-color: transparent;
  position: absolute;
  transform: rotate(-45deg);
  left: -18px;
  top: -2px;
  margin-top: -16px;
  border-radius: 50%;
}
.ticket--start:after {
  content: "";
  width: 32px;
  height: 32px;
  background-color: #fff;
  border: 1px solid #E0E0E0;
  border-top-color: transparent;
  border-left-color: transparent;
  border-bottom-color: transparent;
  position: absolute;
  transform: rotate(-45deg);
  left: -18px;
  top: 100%;
  margin-top: -16px;
  border-radius: 50%;
}
.ticket--start > img {
  display: block;
  padding: 24px;
  height: 270px;
}
.ticket--center {
  padding: 24px;
  flex: 1;
}
.ticket--center--row {
  display: flex;
}
.ticket--center--row:not(:last-child) {
  padding-bottom: 8px;
}
.ticket--center--row:first-child span {
  color: #4872b0;
  text-transform: uppercase;
  line-height: 24px;
  font-size: 13px;
  font-weight: 500;
}
.ticket--center--row:first-child strong {
  font-size: 20px;
  font-weight: 400;
  text-transform: uppercase;
}
.ticket--center--col {
  display: flex;
  flex: 1;
  width: 50%;
  box-sizing: border-box;
  flex-direction: column;
}
.ticket--center--col:not(:last-child) {
  padding-right: 16px;
}
.ticket--end {
  padding: 24px;
  background-color: #000;
  display: flex;
  flex-direction: column;
  /*position: relative;*/
}
.ticket--endx:before {
  content: "";
  width: 32px;
  height: 32px;
  background-color: #fff;
  border: 1px solid #E0E0E0;
  border-top-color: transparent;
  border-right-color: transparent;
  border-bottom-color: transparent;
  position: absolute;
  transform: rotate(-45deg);
  right: -18px;
  top: -2px;
  margin-top: -16px;
  border-radius: 50%;
}
.ticket--endx:after {
  content: "";
  width: 32px;
  height: 32px;
  background-color: #fff;
  border: 1px solid #E0E0E0;
  border-right-color: transparent;
  border-left-color: transparent;
  border-bottom-color: transparent;
  position: absolute;
  transform: rotate(-45deg);
  right: -18px;
  top: 100%;
  margin-top: -16px;
  border-radius: 50%;
}
.ticket--end > div:first-child {
  flex: 1;
}
.ticket--end > div:first-child > img {
  width: 128px;
  padding: 4px;
  background-color: #fff;
}
.ticket--end > div:last-child > img {
  display: block;
  margin: 0 auto;
  filter: brightness(0) invert(1);
  opacity: 0.64;
}
.ticket--info--title {
  text-transform: uppercase;
  color: #000;
  font-size: 16px;
  line-height: 24px;
  font-weight: 600;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.ticket--info--subtitle {
  font-size: 15px;
  line-height: 24px;

  font-weight: 600;
  color: #000;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.ticket--info--content {
  font-size: 13px;
  line-height: 24px;
  font-weight: 500;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
td{
  padding: 0px!important;
  margin: 0px !important;
}
tr{
  padding: 0px!important;
  margin: 0px !important;
}
table{
  border: none!important;
}
</style>

<style>
{{$css}}
</style>
       <style>
           html{font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{margin:0}article,aside,details,figcaption,figure,footer,header,hgroup,main,menu,nav,section,summary{display:block}audio,canvas,progress,video{display:inline-block;vertical-align:baseline}audio:not([controls]){display:none;height:0}[hidden],template{display:none}a{background-color:transparent}a:active,a:hover{outline:0}abbr[title]{border-bottom:1px dotted}b,strong{font-weight:bold}dfn{font-style:italic}h1{font-size:2em;margin:0.67em 0}mark{background:#ff0;color:#000}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sup{top:-0.5em}sub{bottom:-0.25em}img{border:0}svg:not(:root){overflow:hidden}figure{margin:1em 40px}hr{-moz-box-sizing:content-box;-webkit-box-sizing:content-box;box-sizing:content-box;height:0}pre{overflow:auto}code,kbd,pre,samp{font-family:monospace, monospace;font-size:1em}button,input,optgroup,select,textarea{color:inherit;font:inherit;margin:0}button{overflow:visible}button,select{text-transform:none}button,html input[type="button"],input[type="reset"],input[type="submit"]{-webkit-appearance:button;cursor:pointer}button[disabled],html input[disabled]{cursor:default}button::-moz-focus-inner,input::-moz-focus-inner{border:0;padding:0}input{line-height:normal}input[type="checkbox"],input[type="radio"]{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;padding:0}input[type="number"]::-webkit-inner-spin-button,input[type="number"]::-webkit-outer-spin-button{height:auto}input[type="search"]{-webkit-appearance:textfield;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;box-sizing:content-box}input[type="search"]::-webkit-search-cancel-button,input[type="search"]::-webkit-search-decoration{-webkit-appearance:none}fieldset{border:1px solid #c0c0c0;margin:0 2px;padding:0.35em 0.625em 0.75em}legend{border:0;padding:0}textarea{overflow:auto}optgroup{font-weight:bold}table{border-collapse:collapse;border-spacing:0}td,th{padding:0}*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}*:before,*:after{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}html{font-size:10px;-webkit-tap-highlight-color:rgba(0,0,0,0)}body{font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-size:14px;line-height:1.42857143;color:#333;background-color:#fff}input,button,select,textarea{font-family:inherit;font-size:inherit;line-height:inherit}a{color:#337ab7;text-decoration:none}a:hover,a:focus{color:#23527c;text-decoration:underline}a:focus{outline:thin dotted;outline:5px auto -webkit-focus-ring-color;outline-offset:-2px}figure{margin:0}img{vertical-align:middle}.img-responsive{display:block;max-width:100%;height:auto}.img-rounded{border-radius:6px}.img-thumbnail{padding:4px;line-height:1.42857143;background-color:#fff;border:1px solid #ddd;border-radius:4px;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;transition:all .2s ease-in-out;display:inline-block;max-width:100%;height:auto}.img-circle{border-radius:50%}hr{margin-top:20px;margin-bottom:20px;border:0;border-top:1px solid #eee}.sr-only{position:absolute;width:1px;height:1px;margin:-1px;padding:0;overflow:hidden;clip:rect(0, 0, 0, 0);border:0}.sr-only-focusable:active,.sr-only-focusable:focus{position:static;width:auto;height:auto;margin:0;overflow:visible;clip:auto}h1,h2,h3,h4,h5,h6,.h1,.h2,.h3,.h4,.h5,.h6{font-family:inherit;font-weight:500;line-height:1.1;color:inherit}h1 small,h2 small,h3 small,h4 small,h5 small,h6 small,.h1 small,.h2 small,.h3 small,.h4 small,.h5 small,.h6 small,h1 .small,h2 .small,h3 .small,h4 .small,h5 .small,h6 .small,.h1 .small,.h2 .small,.h3 .small,.h4 .small,.h5 .small,.h6 .small{font-weight:normal;line-height:1;color:#777}h1,.h1,h2,.h2,h3,.h3{margin-top:20px;margin-bottom:10px}h1 small,.h1 small,h2 small,.h2 small,h3 small,.h3 small,h1 .small,.h1 .small,h2 .small,.h2 .small,h3 .small,.h3 .small{font-size:65%}h4,.h4,h5,.h5,h6,.h6{margin-top:10px;margin-bottom:10px}h4 small,.h4 small,h5 small,.h5 small,h6 small,.h6 small,h4 .small,.h4 .small,h5 .small,.h5 .small,h6 .small,.h6 .small{font-size:75%}h1,.h1{font-size:36px}h2,.h2{font-size:30px}h3,.h3{font-size:24px}h4,.h4{font-size:18px}h5,.h5{font-size:14px}h6,.h6{font-size:12px}p{margin:0 0 10px}.lead{margin-bottom:20px;font-size:16px;font-weight:300;line-height:1.4}@media (min-width:768px){.lead{font-size:21px}}small,.small{font-size:85%}mark,.mark{background-color:#fcf8e3;padding:.2em}.text-left{text-align:left}.text-right{text-align:right}.text-center{text-align:center}.text-justify{text-align:justify}.text-nowrap{white-space:nowrap}.text-lowercase{text-transform:lowercase}.text-uppercase{text-transform:uppercase}.text-capitalize{text-transform:capitalize}.text-muted{color:#777}.text-primary{color:#337ab7}a.text-primary:hover{color:#286090}.text-success{color:#3c763d}a.text-success:hover{color:#2b542c}.text-info{color:#31708f}a.text-info:hover{color:#245269}.text-warning{color:#8a6d3b}a.text-warning:hover{color:#66512c}.text-danger{color:#a94442}a.text-danger:hover{color:#843534}.bg-primary{color:#fff;background-color:#337ab7}a.bg-primary:hover{background-color:#286090}.bg-success{background-color:#dff0d8}a.bg-success:hover{background-color:#c1e2b3}.bg-info{background-color:#d9edf7}a.bg-info:hover{background-color:#afd9ee}.bg-warning{background-color:#fcf8e3}a.bg-warning:hover{background-color:#f7ecb5}.bg-danger{background-color:#f2dede}a.bg-danger:hover{background-color:#e4b9b9}.page-header{padding-bottom:9px;margin:40px 0 20px;border-bottom:1px solid #eee}ul,ol{margin-top:0;margin-bottom:10px}ul ul,ol ul,ul ol,ol ol{margin-bottom:0}.list-unstyled{padding-left:0;list-style:none}.list-inline{padding-left:0;list-style:none;margin-left:-5px}.list-inline>li{display:inline-block;padding-left:5px;padding-right:5px}dl{margin-top:0;margin-bottom:20px}dt,dd{line-height:1.42857143}dt{font-weight:bold}dd{margin-left:0}@media (min-width:768px){.dl-horizontal dt{float:left;width:160px;clear:left;text-align:right;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.dl-horizontal dd{margin-left:180px}}abbr[title],abbr[data-original-title]{cursor:help;border-bottom:1px dotted #777}.initialism{font-size:90%;text-transform:uppercase}blockquote{padding:10px 20px;margin:0 0 20px;font-size:17.5px;border-left:5px solid #eee}blockquote p:last-child,blockquote ul:last-child,blockquote ol:last-child{margin-bottom:0}blockquote footer,blockquote small,blockquote .small{display:block;font-size:80%;line-height:1.42857143;color:#777}blockquote footer:before,blockquote small:before,blockquote .small:before{content:'\2014 \00A0'}.blockquote-reverse,blockquote.pull-right{padding-right:15px;padding-left:0;border-right:5px solid #eee;border-left:0;text-align:right}.blockquote-reverse footer:before,blockquote.pull-right footer:before,.blockquote-reverse small:before,blockquote.pull-right small:before,.blockquote-reverse .small:before,blockquote.pull-right .small:before{content:''}.blockquote-reverse footer:after,blockquote.pull-right footer:after,.blockquote-reverse small:after,blockquote.pull-right small:after,.blockquote-reverse .small:after,blockquote.pull-right .small:after{content:'\00A0 \2014'}address{margin-bottom:20px;font-style:normal;line-height:1.42857143}.container{margin-right:auto;margin-left:auto;padding-left:15px;padding-right:15px}@media (min-width:768px){.container{width:750px}}@media (min-width:992px){.container{width:970px}}@media (min-width:1200px){.container{width:1170px}}.container-fluid{margin-right:auto;margin-left:auto;padding-left:15px;padding-right:15px}.row{margin-left:-15px;margin-right:-15px}.col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12{position:relative;min-height:1px;padding-left:15px;padding-right:15px}.col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12{float:left}.col-xs-12{width:100%}.col-xs-11{width:91.66666667%}.col-xs-10{width:83.33333333%}.col-xs-9{width:75%}.col-xs-8{width:66.66666667%}.col-xs-7{width:58.33333333%}.col-xs-6{width:50%}.col-xs-5{width:41.66666667%}.col-xs-4{width:33.33333333%}.col-xs-3{width:25%}.col-xs-2{width:16.66666667%}.col-xs-1{width:8.33333333%}.col-xs-pull-12{right:100%}.col-xs-pull-11{right:91.66666667%}.col-xs-pull-10{right:83.33333333%}.col-xs-pull-9{right:75%}.col-xs-pull-8{right:66.66666667%}.col-xs-pull-7{right:58.33333333%}.col-xs-pull-6{right:50%}.col-xs-pull-5{right:41.66666667%}.col-xs-pull-4{right:33.33333333%}.col-xs-pull-3{right:25%}.col-xs-pull-2{right:16.66666667%}.col-xs-pull-1{right:8.33333333%}.col-xs-pull-0{right:auto}.col-xs-push-12{left:100%}.col-xs-push-11{left:91.66666667%}.col-xs-push-10{left:83.33333333%}.col-xs-push-9{left:75%}.col-xs-push-8{left:66.66666667%}.col-xs-push-7{left:58.33333333%}.col-xs-push-6{left:50%}.col-xs-push-5{left:41.66666667%}.col-xs-push-4{left:33.33333333%}.col-xs-push-3{left:25%}.col-xs-push-2{left:16.66666667%}.col-xs-push-1{left:8.33333333%}.col-xs-push-0{left:auto}.col-xs-offset-12{margin-left:100%}.col-xs-offset-11{margin-left:91.66666667%}.col-xs-offset-10{margin-left:83.33333333%}.col-xs-offset-9{margin-left:75%}.col-xs-offset-8{margin-left:66.66666667%}.col-xs-offset-7{margin-left:58.33333333%}.col-xs-offset-6{margin-left:50%}.col-xs-offset-5{margin-left:41.66666667%}.col-xs-offset-4{margin-left:33.33333333%}.col-xs-offset-3{margin-left:25%}.col-xs-offset-2{margin-left:16.66666667%}.col-xs-offset-1{margin-left:8.33333333%}.col-xs-offset-0{margin-left:0}@media (min-width:768px){.col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12{float:left}.col-sm-12{width:100%}.col-sm-11{width:91.66666667%}.col-sm-10{width:83.33333333%}.col-sm-9{width:75%}.col-sm-8{width:66.66666667%}.col-sm-7{width:58.33333333%}.col-sm-6{width:50%}.col-sm-5{width:41.66666667%}.col-sm-4{width:33.33333333%}.col-sm-3{width:25%}.col-sm-2{width:16.66666667%}.col-sm-1{width:8.33333333%}.col-sm-pull-12{right:100%}.col-sm-pull-11{right:91.66666667%}.col-sm-pull-10{right:83.33333333%}.col-sm-pull-9{right:75%}.col-sm-pull-8{right:66.66666667%}.col-sm-pull-7{right:58.33333333%}.col-sm-pull-6{right:50%}.col-sm-pull-5{right:41.66666667%}.col-sm-pull-4{right:33.33333333%}.col-sm-pull-3{right:25%}.col-sm-pull-2{right:16.66666667%}.col-sm-pull-1{right:8.33333333%}.col-sm-pull-0{right:auto}.col-sm-push-12{left:100%}.col-sm-push-11{left:91.66666667%}.col-sm-push-10{left:83.33333333%}.col-sm-push-9{left:75%}.col-sm-push-8{left:66.66666667%}.col-sm-push-7{left:58.33333333%}.col-sm-push-6{left:50%}.col-sm-push-5{left:41.66666667%}.col-sm-push-4{left:33.33333333%}.col-sm-push-3{left:25%}.col-sm-push-2{left:16.66666667%}.col-sm-push-1{left:8.33333333%}.col-sm-push-0{left:auto}.col-sm-offset-12{margin-left:100%}.col-sm-offset-11{margin-left:91.66666667%}.col-sm-offset-10{margin-left:83.33333333%}.col-sm-offset-9{margin-left:75%}.col-sm-offset-8{margin-left:66.66666667%}.col-sm-offset-7{margin-left:58.33333333%}.col-sm-offset-6{margin-left:50%}.col-sm-offset-5{margin-left:41.66666667%}.col-sm-offset-4{margin-left:33.33333333%}.col-sm-offset-3{margin-left:25%}.col-sm-offset-2{margin-left:16.66666667%}.col-sm-offset-1{margin-left:8.33333333%}.col-sm-offset-0{margin-left:0}}@media (min-width:992px){.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12{float:left}.col-md-12{width:100%}.col-md-11{width:91.66666667%}.col-md-10{width:83.33333333%}.col-md-9{width:75%}.col-md-8{width:66.66666667%}.col-md-7{width:58.33333333%}.col-md-6{width:50%}.col-md-5{width:41.66666667%}.col-md-4{width:33.33333333%}.col-md-3{width:25%}.col-md-2{width:16.66666667%}.col-md-1{width:8.33333333%}.col-md-pull-12{right:100%}.col-md-pull-11{right:91.66666667%}.col-md-pull-10{right:83.33333333%}.col-md-pull-9{right:75%}.col-md-pull-8{right:66.66666667%}.col-md-pull-7{right:58.33333333%}.col-md-pull-6{right:50%}.col-md-pull-5{right:41.66666667%}.col-md-pull-4{right:33.33333333%}.col-md-pull-3{right:25%}.col-md-pull-2{right:16.66666667%}.col-md-pull-1{right:8.33333333%}.col-md-pull-0{right:auto}.col-md-push-12{left:100%}.col-md-push-11{left:91.66666667%}.col-md-push-10{left:83.33333333%}.col-md-push-9{left:75%}.col-md-push-8{left:66.66666667%}.col-md-push-7{left:58.33333333%}.col-md-push-6{left:50%}.col-md-push-5{left:41.66666667%}.col-md-push-4{left:33.33333333%}.col-md-push-3{left:25%}.col-md-push-2{left:16.66666667%}.col-md-push-1{left:8.33333333%}.col-md-push-0{left:auto}.col-md-offset-12{margin-left:100%}.col-md-offset-11{margin-left:91.66666667%}.col-md-offset-10{margin-left:83.33333333%}.col-md-offset-9{margin-left:75%}.col-md-offset-8{margin-left:66.66666667%}.col-md-offset-7{margin-left:58.33333333%}.col-md-offset-6{margin-left:50%}.col-md-offset-5{margin-left:41.66666667%}.col-md-offset-4{margin-left:33.33333333%}.col-md-offset-3{margin-left:25%}.col-md-offset-2{margin-left:16.66666667%}.col-md-offset-1{margin-left:8.33333333%}.col-md-offset-0{margin-left:0}}@media (min-width:1200px){.col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12{float:left}.col-lg-12{width:100%}.col-lg-11{width:91.66666667%}.col-lg-10{width:83.33333333%}.col-lg-9{width:75%}.col-lg-8{width:66.66666667%}.col-lg-7{width:58.33333333%}.col-lg-6{width:50%}.col-lg-5{width:41.66666667%}.col-lg-4{width:33.33333333%}.col-lg-3{width:25%}.col-lg-2{width:16.66666667%}.col-lg-1{width:8.33333333%}.col-lg-pull-12{right:100%}.col-lg-pull-11{right:91.66666667%}.col-lg-pull-10{right:83.33333333%}.col-lg-pull-9{right:75%}.col-lg-pull-8{right:66.66666667%}.col-lg-pull-7{right:58.33333333%}.col-lg-pull-6{right:50%}.col-lg-pull-5{right:41.66666667%}.col-lg-pull-4{right:33.33333333%}.col-lg-pull-3{right:25%}.col-lg-pull-2{right:16.66666667%}.col-lg-pull-1{right:8.33333333%}.col-lg-pull-0{right:auto}.col-lg-push-12{left:100%}.col-lg-push-11{left:91.66666667%}.col-lg-push-10{left:83.33333333%}.col-lg-push-9{left:75%}.col-lg-push-8{left:66.66666667%}.col-lg-push-7{left:58.33333333%}.col-lg-push-6{left:50%}.col-lg-push-5{left:41.66666667%}.col-lg-push-4{left:33.33333333%}.col-lg-push-3{left:25%}.col-lg-push-2{left:16.66666667%}.col-lg-push-1{left:8.33333333%}.col-lg-push-0{left:auto}.col-lg-offset-12{margin-left:100%}.col-lg-offset-11{margin-left:91.66666667%}.col-lg-offset-10{margin-left:83.33333333%}.col-lg-offset-9{margin-left:75%}.col-lg-offset-8{margin-left:66.66666667%}.col-lg-offset-7{margin-left:58.33333333%}.col-lg-offset-6{margin-left:50%}.col-lg-offset-5{margin-left:41.66666667%}.col-lg-offset-4{margin-left:33.33333333%}.col-lg-offset-3{margin-left:25%}.col-lg-offset-2{margin-left:16.66666667%}.col-lg-offset-1{margin-left:8.33333333%}.col-lg-offset-0{margin-left:0}}table{background-color:transparent}caption{padding-top:8px;padding-bottom:8px;color:#777;text-align:left}th{text-align:left}.table{width:100%;max-width:100%;margin-bottom:20px}.table>thead>tr>th,.table>tbody>tr>th,.table>tfoot>tr>th,.table>thead>tr>td,.table>tbody>tr>td,.table>tfoot>tr>td{padding:8px;line-height:1.42857143;vertical-align:top;border-top:1px solid #ddd}.table>thead>tr>th{vertical-align:bottom;border-bottom:2px solid #ddd}.table>caption+thead>tr:first-child>th,.table>colgroup+thead>tr:first-child>th,.table>thead:first-child>tr:first-child>th,.table>caption+thead>tr:first-child>td,.table>colgroup+thead>tr:first-child>td,.table>thead:first-child>tr:first-child>td{border-top:0}.table>tbody+tbody{border-top:2px solid #ddd}.table .table{background-color:#fff}.table-condensed>thead>tr>th,.table-condensed>tbody>tr>th,.table-condensed>tfoot>tr>th,.table-condensed>thead>tr>td,.table-condensed>tbody>tr>td,.table-condensed>tfoot>tr>td{padding:5px}.table-bordered{border:1px solid #ddd}.table-bordered>thead>tr>th,.table-bordered>tbody>tr>th,.table-bordered>tfoot>tr>th,.table-bordered>thead>tr>td,.table-bordered>tbody>tr>td,.table-bordered>tfoot>tr>td{border:1px solid #ddd}.table-bordered>thead>tr>th,.table-bordered>thead>tr>td{border-bottom-width:2px}.table-striped>tbody>tr:nth-child(odd){background-color:#f9f9f9}.table-hover>tbody>tr:hover{background-color:#f5f5f5}table col[class*="col-"]{position:static;float:none;display:table-column}table td[class*="col-"],table th[class*="col-"]{position:static;float:none;display:table-cell}.table>thead>tr>td.active,.table>tbody>tr>td.active,.table>tfoot>tr>td.active,.table>thead>tr>th.active,.table>tbody>tr>th.active,.table>tfoot>tr>th.active,.table>thead>tr.active>td,.table>tbody>tr.active>td,.table>tfoot>tr.active>td,.table>thead>tr.active>th,.table>tbody>tr.active>th,.table>tfoot>tr.active>th{background-color:#f5f5f5}.table-hover>tbody>tr>td.active:hover,.table-hover>tbody>tr>th.active:hover,.table-hover>tbody>tr.active:hover>td,.table-hover>tbody>tr:hover>.active,.table-hover>tbody>tr.active:hover>th{background-color:#e8e8e8}.table>thead>tr>td.success,.table>tbody>tr>td.success,.table>tfoot>tr>td.success,.table>thead>tr>th.success,.table>tbody>tr>th.success,.table>tfoot>tr>th.success,.table>thead>tr.success>td,.table>tbody>tr.success>td,.table>tfoot>tr.success>td,.table>thead>tr.success>th,.table>tbody>tr.success>th,.table>tfoot>tr.success>th{background-color:#dff0d8}.table-hover>tbody>tr>td.success:hover,.table-hover>tbody>tr>th.success:hover,.table-hover>tbody>tr.success:hover>td,.table-hover>tbody>tr:hover>.success,.table-hover>tbody>tr.success:hover>th{background-color:#d0e9c6}.table>thead>tr>td.info,.table>tbody>tr>td.info,.table>tfoot>tr>td.info,.table>thead>tr>th.info,.table>tbody>tr>th.info,.table>tfoot>tr>th.info,.table>thead>tr.info>td,.table>tbody>tr.info>td,.table>tfoot>tr.info>td,.table>thead>tr.info>th,.table>tbody>tr.info>th,.table>tfoot>tr.info>th{background-color:#d9edf7}.table-hover>tbody>tr>td.info:hover,.table-hover>tbody>tr>th.info:hover,.table-hover>tbody>tr.info:hover>td,.table-hover>tbody>tr:hover>.info,.table-hover>tbody>tr.info:hover>th{background-color:#c4e3f3}.table>thead>tr>td.warning,.table>tbody>tr>td.warning,.table>tfoot>tr>td.warning,.table>thead>tr>th.warning,.table>tbody>tr>th.warning,.table>tfoot>tr>th.warning,.table>thead>tr.warning>td,.table>tbody>tr.warning>td,.table>tfoot>tr.warning>td,.table>thead>tr.warning>th,.table>tbody>tr.warning>th,.table>tfoot>tr.warning>th{background-color:#fcf8e3}.table-hover>tbody>tr>td.warning:hover,.table-hover>tbody>tr>th.warning:hover,.table-hover>tbody>tr.warning:hover>td,.table-hover>tbody>tr:hover>.warning,.table-hover>tbody>tr.warning:hover>th{background-color:#faf2cc}.table>thead>tr>td.danger,.table>tbody>tr>td.danger,.table>tfoot>tr>td.danger,.table>thead>tr>th.danger,.table>tbody>tr>th.danger,.table>tfoot>tr>th.danger,.table>thead>tr.danger>td,.table>tbody>tr.danger>td,.table>tfoot>tr.danger>td,.table>thead>tr.danger>th,.table>tbody>tr.danger>th,.table>tfoot>tr.danger>th{background-color:#f2dede}.table-hover>tbody>tr>td.danger:hover,.table-hover>tbody>tr>th.danger:hover,.table-hover>tbody>tr.danger:hover>td,.table-hover>tbody>tr:hover>.danger,.table-hover>tbody>tr.danger:hover>th{background-color:#ebcccc}.table-responsive{overflow-x:auto;min-height:0.01%}@media screen and (max-width:767px){.table-responsive{width:100%;margin-bottom:15px;overflow-y:hidden;-ms-overflow-style:-ms-autohiding-scrollbar;border:1px solid #ddd}.table-responsive>.table{margin-bottom:0}.table-responsive>.table>thead>tr>th,.table-responsive>.table>tbody>tr>th,.table-responsive>.table>tfoot>tr>th,.table-responsive>.table>thead>tr>td,.table-responsive>.table>tbody>tr>td,.table-responsive>.table>tfoot>tr>td{white-space:nowrap}.table-responsive>.table-bordered{border:0}.table-responsive>.table-bordered>thead>tr>th:first-child,.table-responsive>.table-bordered>tbody>tr>th:first-child,.table-responsive>.table-bordered>tfoot>tr>th:first-child,.table-responsive>.table-bordered>thead>tr>td:first-child,.table-responsive>.table-bordered>tbody>tr>td:first-child,.table-responsive>.table-bordered>tfoot>tr>td:first-child{border-left:0}.table-responsive>.table-bordered>thead>tr>th:last-child,.table-responsive>.table-bordered>tbody>tr>th:last-child,.table-responsive>.table-bordered>tfoot>tr>th:last-child,.table-responsive>.table-bordered>thead>tr>td:last-child,.table-responsive>.table-bordered>tbody>tr>td:last-child,.table-responsive>.table-bordered>tfoot>tr>td:last-child{border-right:0}.table-responsive>.table-bordered>tbody>tr:last-child>th,.table-responsive>.table-bordered>tfoot>tr:last-child>th,.table-responsive>.table-bordered>tbody>tr:last-child>td,.table-responsive>.table-bordered>tfoot>tr:last-child>td{border-bottom:0}}.list-group{margin-bottom:20px;padding-left:0}.list-group-item{position:relative;display:block;padding:10px 15px;margin-bottom:-1px;background-color:#fff;border:1px solid #ddd}.list-group-item:first-child{border-top-right-radius:4px;border-top-left-radius:4px}.list-group-item:last-child{margin-bottom:0;border-bottom-right-radius:4px;border-bottom-left-radius:4px}a.list-group-item{color:#555}a.list-group-item .list-group-item-heading{color:#333}a.list-group-item:hover,a.list-group-item:focus{text-decoration:none;color:#555;background-color:#f5f5f5}.list-group-item.disabled,.list-group-item.disabled:hover,.list-group-item.disabled:focus{background-color:#eee;color:#777;cursor:not-allowed}.list-group-item.disabled .list-group-item-heading,.list-group-item.disabled:hover .list-group-item-heading,.list-group-item.disabled:focus .list-group-item-heading{color:inherit}.list-group-item.disabled .list-group-item-text,.list-group-item.disabled:hover .list-group-item-text,.list-group-item.disabled:focus .list-group-item-text{color:#777}.list-group-item.active,.list-group-item.active:hover,.list-group-item.active:focus{z-index:2;color:#fff;background-color:#337ab7;border-color:#337ab7}.list-group-item.active .list-group-item-heading,.list-group-item.active:hover .list-group-item-heading,.list-group-item.active:focus .list-group-item-heading,.list-group-item.active .list-group-item-heading>small,.list-group-item.active:hover .list-group-item-heading>small,.list-group-item.active:focus .list-group-item-heading>small,.list-group-item.active .list-group-item-heading>.small,.list-group-item.active:hover .list-group-item-heading>.small,.list-group-item.active:focus .list-group-item-heading>.small{color:inherit}.list-group-item.active .list-group-item-text,.list-group-item.active:hover .list-group-item-text,.list-group-item.active:focus .list-group-item-text{color:#c7ddef}.list-group-item-success{color:#3c763d;background-color:#dff0d8}a.list-group-item-success{color:#3c763d}a.list-group-item-success .list-group-item-heading{color:inherit}a.list-group-item-success:hover,a.list-group-item-success:focus{color:#3c763d;background-color:#d0e9c6}a.list-group-item-success.active,a.list-group-item-success.active:hover,a.list-group-item-success.active:focus{color:#fff;background-color:#3c763d;border-color:#3c763d}.list-group-item-info{color:#31708f;background-color:#d9edf7}a.list-group-item-info{color:#31708f}a.list-group-item-info .list-group-item-heading{color:inherit}a.list-group-item-info:hover,a.list-group-item-info:focus{color:#31708f;background-color:#c4e3f3}a.list-group-item-info.active,a.list-group-item-info.active:hover,a.list-group-item-info.active:focus{color:#fff;background-color:#31708f;border-color:#31708f}.list-group-item-warning{color:#8a6d3b;background-color:#fcf8e3}a.list-group-item-warning{color:#8a6d3b}a.list-group-item-warning .list-group-item-heading{color:inherit}a.list-group-item-warning:hover,a.list-group-item-warning:focus{color:#8a6d3b;background-color:#faf2cc}a.list-group-item-warning.active,a.list-group-item-warning.active:hover,a.list-group-item-warning.active:focus{color:#fff;background-color:#8a6d3b;border-color:#8a6d3b}.list-group-item-danger{color:#a94442;background-color:#f2dede}a.list-group-item-danger{color:#a94442}a.list-group-item-danger .list-group-item-heading{color:inherit}a.list-group-item-danger:hover,a.list-group-item-danger:focus{color:#a94442;background-color:#ebcccc}a.list-group-item-danger.active,a.list-group-item-danger.active:hover,a.list-group-item-danger.active:focus{color:#fff;background-color:#a94442;border-color:#a94442}.list-group-item-heading{margin-top:0;margin-bottom:5px}.list-group-item-text{margin-bottom:0;line-height:1.3}.well{min-height:20px;padding:19px;margin-bottom:20px;background-color:#f5f5f5;border:1px solid #e3e3e3;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,0.05);box-shadow:inset 0 1px 1px rgba(0,0,0,0.05)}.well blockquote{border-color:#ddd;border-color:rgba(0,0,0,0.15)}.well-lg{padding:24px;border-radius:6px}.well-sm{padding:9px;border-radius:3px}.clearfix:before,.clearfix:after,.dl-horizontal dd:before,.dl-horizontal dd:after,.container:before,.container:after,.container-fluid:before,.container-fluid:after,.row:before,.row:after{content:" ";display:table}.clearfix:after,.dl-horizontal dd:after,.container:after,.container-fluid:after,.row:after{clear:both}.center-block{display:block;margin-left:auto;margin-right:auto}.pull-right{float:right !important}.pull-left{float:left !important}.hide{display:none !important}.show{display:block !important}.invisible{visibility:hidden}.text-hide{font:0/0 a;color:transparent;text-shadow:none;background-color:transparent;border:0}.hidden{display:none !important;visibility:hidden !important}.affix{position:fixed}
       </style>
  <script>
  window.console = window.console || function(t) {};
</script>

  <style>
    table{
      border: 0px;
      padding: 0px!important;
      margin: 0px !important;
    }
    td{
      border: 0px !important;
    }
    .vertical-text {
   -webkit-transform: rotate(90deg);
    top:270px !important;
    left:-100px !important;
    position: absolute;
}
.info1{
    color : #000 !important;
    left:-30px !important;
}
.info2{
    color : #000 !important;
}
.ticket h4{
    color : #000 !important;
}
.codigo{
    
    top:-30px !important;
}
  </style>
  
</head>

<body translate="no">
  <div class="" style="display: none">

    @foreach($attendees as $key_order_item => $attendee)
        @if(!$attendee->is_cancelled)

            <div class="ticket">

                    <div class="codigo ">
                        {!! DNS2D::getBarcodeSVG($attendee->private_reference_number, "QRCODE", 4, 4) !!}
                        <h4>@lang("Ticket.attendee_ref")</h4>
                        <h4>  {{$attendee->reference}} </h4> 
                    </div>

                    @if($event->is_1d_barcode_enabled)
                        <div class="barcode_vertical">
                            {!! DNS1D::getBarcodeSVG($attendee->private_reference_number, "C39+", 1, 50) !!}
                        </div>
                    @endif

                    {{-- <div class="etiqueta "></div> --}}

                    <div class="imagen ">
                        {{--<img alt="{{$event->organiser->full_logo_path}}" src="data:image/png;base64, {{$image}}" />--}}
                        @if(isset($images) && count($images) > 0)
                            @foreach($images as $img)
                                <BR><img src="data:image/png;base64, {{$img}}" />
                            @endforeach
                        @endif
                    </div>
                    <div class="info1 ">
                        <h4>@lang("Ticket.event")</h4>
                            {{$event->title}}
                        <h4>@lang("Ticket.start_date_time")</h4>
                            {{$event->startDateFormatted()}}
                            {{$event->venue_name}}
                          </div>

                          <div class="info2 ">
                          <h4>@lang("Ticket.ticket_type")</h4>
                              {{$attendee->ticket->title}} 
                              @if($attendee->seats)
                                    <h4>
                                        {{{$attendee->seats->seat()}}}
                                    </h4>
                                @endif 
                              <h4>@lang("Ticket.price")</h4>
                              @php
                                  // Calculating grand total including tax
                                  $grand_total = $attendee->ticket->total_price;
                                  $tax_amt = ($grand_total * $event->organiser->tax_value) / 100;
                                  $grand_total = $attendee->ticket->price_neto;
                              @endphp
                              {{money($grand_total, $order->event->currency)}} @if ($attendee->ticket->price_service) ( Mas {{money($attendee->ticket->price_service, $order->event->currency)}} @lang("Public_ViewEvent.inc_fees")) @endif @if ($event->organiser->tax_name) (inc. {{money($tax_amt, $order->event->currency)}} {{$event->organiser->tax_name}})
                              <br><br>{{$event->organiser->tax_name}} ID: {{ $event->organiser->tax_id }}
                              @endif
                                </div>

                          {{--<div class="info2 " >

                      <h4>@lang("Ticket.ticket_type")</h4>
                          {{$attendee->ticket->title}}
                      <h4>@lang("Ticket.price")</h4>
                          @php
                              // Calculating grand total including tax
                              $grand_total = $attendee->ticket->total_price;
                              $tax_amt = ($grand_total * $event->organiser->tax_value) / 100;
                              $grand_total = $tax_amt + $grand_total;
                          @endphp
                          {{money($grand_total, $order->event->currency)}} @if ($attendee->ticket->total_booking_fee) (inc. {{money($attendee->ticket->total_booking_fee, $order->event->currency)}} @lang("Public_ViewEvent.inc_fees")) @endif @if ($event->organiser->tax_name) (inc. {{money($tax_amt, $order->event->currency)}} {{$event->organiser->tax_name}})
                          <br><br>{{$event->organiser->tax_name}} ID: {{ $event->organiser->tax_id }}
                          @endif--}}

                          {{--<h4>@lang("Ticket.order_ref")</h4>
                          {{$order->order_reference}}
                      <h4>@lang("Ticket.attendee_ref")</h4>
                          {{$attendee->reference}}
                      <h4>@lang("Ticket.name")</h4>
                          {{$attendee->first_name.' '.$attendee->last_name}}--}}
                          {{--</div>--}}

          </div>
      @endif
    @endforeach

</div>
          @foreach($attendees as $key_order_item => $attendee)
                @if(!$attendee->is_cancelled)
  <div class="ticket" style="padding:10px; padding-bottom:20px">
      
      
      
    <div class="codigo ">
      {!! DNS2D::getBarcodeSVG($attendee->private_reference_number, "QRCODE", 4, 4) !!}
      <h4>@lang("Ticket.attendee_ref")</h4>
      <h4>  {{$attendee->reference}} </h4> 
  </div>  
      
        <table class="table" width="100%"> 
            <tbody>
                <tr> 
                    <td width="100%"> 
                         
                        <table class="table  vertical-text" width="100%"> 
                            <tbody>
                                <tr class="ticket--center--row"> 
                                    <td colspan="2"> 
                                        
                                        <table class="table"> 
                                            <tbody>
                                                <tr> 
                                                    <td class="ticket--info--title">@lang("Ticket.event")</td>
                                                </tr> 
                                                <tr> 
                                                    <td class="ticket--info--subtitle"> {{$event->title}}</td>
                                                </tr> 
                                            </tbody>
                                        </table>
                        
                                    </td>
                                </tr>  
                                <tr class="ticket--center--row"> 
                                  
                                    <td  colspan="2"> 
                                        
                                        <table class="table"> 
                                            <tbody>
                                                <tr> 
                                                    <td class="ticket--info--title">@lang("Ticket.attendee_ref")</td>
                                                </tr> 
                                                <tr> 
                                                    <td class="ticket--info--subtitle"> {{$attendee->reference}}</td>
                                                </tr> 
                                            </tbody>
                                        </table>
                        
                                    </td>
                                </tr>  
                                <tr class="ticket--center--row"> 
                                    <td  width="60%"> 
                                        
                                        <table class="table"> 
                                            <tbody>
                                                <tr> 
                                                    <td><span class="ticket--info--title">@lang("Ticket.start_date_time")</span></td>
                                                </tr> 
                                                <tr> 
                                                    <td>        <span class="ticket--info--subtitle">{{$event->startDateFormatted()}}</span> </td>
                                                </tr> 
                                            </tbody>
                                        </table>
                        
                                    </td>
                                    <td  width="40%">  
                                        <table class="table"> 
                                            <tbody>
                                                <tr> 
                                                    <td>
                                                      <span class="ticket--info--title">@lang("Ticket.venue")</span>
                                                     </td>
                                                </tr>  
                                                <tr> 
                                                    <td>
                                                       <span class="ticket--info--subtitle">{{$event->venue_name}} </span>
                                                    </td>
                                                </tr>  
                                            </tbody>
                                        </table>
                        
                                    </td>
                                </tr>  
                                <tr class="ticket--center--row"> 
                                    <td  width="50%">  
                                        <table class="table"> 
                                            <tbody>
                                                <tr> 
                                                    <td><span class="ticket--info--title">@lang("Ticket.ticket_type")</span></td>
                                                </tr> 
                                                <tr> 
                                                  <td>        
                                                    <span class="ticket--info--subtitle"> 
                                                      {{$attendee->ticket->title}} 
                                                      
                                                      @if($attendee->seats)
                                                            <h4>
                                                                {{{$attendee->seats->seat()}}}
                                                            </h4>
                                                        @endif  
                                                    </span> 
                                                  </td>
                                                </tr> 
                                            </tbody>
                                        </table>  
                                    </td>
                                    <td  width="50%">  
                                        <table class="table"> 
                                            <tbody>
                                                <tr> 
                                                    <td>
                                                      <span class="ticket--info--title">@lang("Ticket.price")</span> 
                                                     </td>
                                                </tr>  
                                                <tr> 
                                                    <td>
                                                      <span class="ticket--info--subtitle"> 
                                                        @php
                                                          // Calculating grand total including tax
                                                          $grand_total = $attendee->ticket->total_price;
                                                          $tax_amt = ($grand_total * $event->organiser->tax_value) / 100;
                                                          $grand_total = $attendee->ticket->price_neto;
                                                        @endphp
                                                        
                                                        {{money($grand_total, $order->event->currency)}} <br> 
                                                        
                                                        @if ($attendee->ticket->price_service && $attendee->ticket->price_service>0) 
                                                          ( Mas {{money($attendee->ticket->price_service, $order->event->currency)}} @lang("Public_ViewEvent.inc_fees"))
                                                        @endif 

                                                        @if ($event->organiser->tax_name && $tax_amt>0) 
                                                          (inc. {{money($tax_amt, $order->event->currency)}} {{$event->organiser->tax_name}})
                                                          <br><br>{{$event->organiser->tax_name}} ID: {{ $event->organiser->tax_id }}
                                                        @endif 

                                                      </span>
                                                    </td>
                                                </tr>  
                                            </tbody>
                                        </table>
                        
                                    </td>
                                </tr>   
                                <tr class="ticket--center--row"> 
                                
                                    <td colspan="2"> 
                                        
                                    </td>
                                </tr>  
                            </tbody>
                        </table> 
                    </td> 
                </tr> 
            </tbody>
        </table> 
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
                      <div class="info1 ">
                        <h4>@lang("Ticket.event")</h4>
                            {{$event->title}}
                        <h4>@lang("Ticket.start_date_time")</h4>
                            {{$event->startDateFormatted()}}
                            {{$event->venue_name}}
                          </div>

                          <div class="info2 ">
                          <h4>@lang("Ticket.ticket_type")</h4>
                              {{$attendee->ticket->title}} 
                              @if($attendee->seats)
                                    <h4>
                                        {{{$attendee->seats->seat()}}}
                                    </h4>
                                @endif 
                              <h4>@lang("Ticket.price")</h4>
                              @php
                                  // Calculating grand total including tax
                                  $grand_total = $attendee->ticket->total_price;
                                  $tax_amt = ($grand_total * $event->organiser->tax_value) / 100;
                                  $grand_total = $attendee->ticket->price_neto;
                              @endphp
                              {{money($grand_total, $order->event->currency)}} @if ($attendee->ticket->price_service) ( Mas {{money($attendee->ticket->price_service, $order->event->currency)}} @lang("Public_ViewEvent.inc_fees")) @endif @if ($event->organiser->tax_name) (inc. {{money($tax_amt, $order->event->currency)}} {{$event->organiser->tax_name}})
                              <br><br>{{$event->organiser->tax_name}} ID: {{ $event->organiser->tax_id }}
                              @endif
                                </div>

  </div>
  
              @endif
            @endforeach
       

</body></html>