<?php 
header('Content-Type: text/css;  charset=UTF-8'); 
$colorRGBBG = "22, 142, 247";
$colorRGBFG = "255,255,255";
$colorDARK = 1;
$colorRGB2 = "90, 143, 0";
function rgba($alpha,$type="fg")
{
	switch($type) 
	{
		case "2":
			return "rgba(".$GLOBALS["colorRGB2"].",".abs( ($GLOBALS["colorRGB2"]-$alpha) ).")";
		case "bg":
			return "rgba(".$GLOBALS["colorRGBBG"].",".abs( ($GLOBALS["colorDARK"]-$alpha) ).")";
		case "bd":
		case "in":
			return "rgba(".$GLOBALS["colorRGBFG"].",".abs( ($GLOBALS["colorDARK"]-$alpha) ).")";
		default:
			return "rgba(".$GLOBALS["colorRGBFG"].",".abs( $alpha ).")";
	}
}


?>
   html { height: 100% }
   body { height: 100%; background-color:#CCC }
   #map-container { min-height: 600px }
   .container {background-color:#FFF;}
   @media all and (max-width: 768px) {
	#map-container { min-height: 300px; width: 100%;}
       .breadcrumbXL {display:none!important;}
   }
   @media (min-width:1200px){
		.container{width:90%;}
	}
   @media (min-width:992px) and (max-width:1600px){
	table .hidden-md{
		display:none!important
	}
   }
       

.cb1 {background-color:#00DD00; color: white;}
.cb2 {background-color:#00DD00; color: white;}
.cb3 {background-color:#00DD00; color: white;}
.cb4 {background-color:#00DD00; color: white;}
.cb5 {background-color:#168EF7; color: white;}
.cb6 {background-color:#F88017; color: white;}
.cb7 {background-color:#FF0000; color: white;}
.cb8 {background-color:#222222; color: white;}
.cb9 {background-color:#000000; color: white;}
.cbn {background-color:#000000; color: white;}
	
          .tagtype {display: inline-block; background-color:#BBFFBB; border-radius:3px; padding:3px; margin: 3px;}
	  .btn-list {min-width:50%; text-align: left;}	
          .btn-right {float:right;}
          
	  .progress {margin:3px;}
	  .row1 {background-color:#EEEEEE;}
	  .row1 {background-color:#EEEEEE;}
	  img.rtgitem {border:1px solid black; margin:5px;padding:0;width:25px;}
	  #topo-container-img {width:100%; text-align:center;}
	  #topo-container-img img { max-width:100%; max-height: 500px; margin-right:auto;	margin-left:auto;   }


	  #topo-container > div { }
	  #topo-container-prev, #topo-container-next { height:100%; padding-top: 33%; position: absolute; top:0; width:40%}
	  #topo-container-prev { left:0; padding-left: 5px;} 
	  #topo-container-next { right:0; padding-right: 5px;}
	  #topo-container-prev div { text-align:left; } 
	  #topo-container-next div { text-align:right; }
	  #topo-container-prev a, #topo-container-next a { display:block; font-size:2em;}
	  #topo-container-prev a:hover, #topo-container-next a:hover {text-decoration:none; text-variant:bold;} 
	  b.new {text-decoration:none; text-variant:bold; color:red;} 
	  
	  .modal-rtg {	width: 90%;	}
	  .rtg_topo_img {width: 100%;}
	  img.full {width: 100%;}
	  table.legende {width: 100%;}
	  
	  




.panel.legende .item { border: 1px solid grey; border-radius: 6px; margin:4px; padding:4px; min-width: 30%;}
.panel.legende .item img {margin-right: 7px;}

.modal-body .panel.legende {min-width:250px; width:33%; display:inline-table;}
.panel.legende .item {display:inline-block;}

/*#betacreator .callout-bubble {z-index: 10000;}*/
#betacreator .button-bar-item  {height: 18px !important; width: 20px !important;}
#betacreator .callout {z-index: 10000 !important;
		width: 300px !important;
		height: 300px !important;
		position: absolute !important;
}
#betacreator .callout.hidden{
	display:initial!important;
	visibility:initial!important;
}






.comment { margin-left: 20px;}	
.comment .commentMeta,.comment .commentDatas {
	display: inline-table; padding: 2px; margin: 2px; 
	background-color:<?=rgba(0,"bg")?>;
	color:<?=rgba(0.7)?>;
	}
.comment .commentMeta {text-align: center; border-radius:5px; margin-left:10px; vertical-align: top;}
.comment .commentMeta:before {
    font-family:'Glyphicons Halflings';
    content:"\e008";
}
.comment a {
    color: <?=rgba(1)?>;
    font-weight: bold;
}
.comment .commentDatas {
  position:relative;
  padding:15px;
  margin:1em 0 3em 0;
  border:2px solid <?=rgba(0,"2")?>;
  color:<?=rgba(0.7)?>;
  background:<?=rgba(0,"2")?>;
  /* css3 */
  -webkit-border-radius:10px;
  -moz-border-radius:10px;
  border-radius:10px;
  margin-left:10px;
  max-width: 90%;
}

.comment .commentDatas:before {
  content:"";
  position:absolute;
  border-style:solid;
  border-color:<?=rgba(0,"2")?> transparent;
  display:block;
  width:0;
}

/* creates the smaller  triangle */
.comment .commentDatas:after {
  content:"";
  position:absolute;
  border-style:solid;
  border-color:<?=rgba(0,"2")?> transparent;
  /* reduce the damage in FF3.0 */
  display:block;
  width:0;
}
/* creates the larger triangle */
.comment .commentDatas:before {
  top:14px; /* controls vertical position */
  bottom:auto;
  left:-20px; /* value = - border-left-width - border-right-width */
  border-width:11px 20px 11px 0;
  border-color:transparent <?=rgba(0,"2")?>;
}

/* creates the smaller  triangle */
.comment .commentDatas:after {
  top:16px; /* value = (:before top) + (:before border-top) - (:after border-top) */
  bottom:auto;
  left:-18px; /* value = - border-left-width - border-right-width */
  border-width:9px 21px 9px 0;
  border-color:transparent <?=rgba(0,"2")?>;
}

.comment .commentText {text-align: justify;}

.comment .commentPublic {text-align: right; border-bottom: 1px solid black;}






.ui-datepicker {
background-color: #fff;
border: 1px solid #66AFE9;
border-radius: 4px;
box-shadow: 0 0 8px rgba(102,175,233,.6);
display: none;
margin-top: 4px;
padding: 10px;
width: 240px;
}
.ui-datepicker a,
.ui-datepicker a:hover {
text-decoration: none;
}
.ui-datepicker a:hover,
.ui-datepicker td:hover a {
color: #2A6496;
-webkit-transition: color 0.1s ease-in-out;
-moz-transition: color 0.1s ease-in-out;
-o-transition: color 0.1s ease-in-out;
transition: color 0.1s ease-in-out;
}
.ui-datepicker .ui-datepicker-header {
margin-bottom: 4px;
text-align: center;
}
.ui-datepicker .ui-datepicker-title {
font-weight: 700;
}
.ui-datepicker .ui-datepicker-prev,
.ui-datepicker .ui-datepicker-next {
cursor: default;
font-family: 'Glyphicons Halflings';
-webkit-font-smoothing: antialiased;
font-style: normal;
font-weight: normal;
height: 20px;
line-height: 1;
margin-top: 2px;
width: 30px;
}
.ui-datepicker .ui-datepicker-prev {
float: left;
text-align: left;
}
.ui-datepicker .ui-datepicker-next {
float: right;
text-align: right;
}
.ui-datepicker .ui-datepicker-prev:before {
content: "\e079";
}
.ui-datepicker .ui-datepicker-next:before {
content: "\e080";
}
.ui-datepicker .ui-icon {
display: none;
}
.ui-datepicker .ui-datepicker-calendar {
table-layout: fixed;
width: 100%;
}
.ui-datepicker .ui-datepicker-calendar th,
.ui-datepicker .ui-datepicker-calendar td {
text-align: center;
padding: 4px 0;
}
.ui-datepicker .ui-datepicker-calendar td {
border-radius: 4px;
-webkit-transition: background-color 0.1s ease-in-out, color 0.1s ease-in-out;
-moz-transition: background-color 0.1s ease-in-out, color 0.1s ease-in-out;
-o-transition: background-color 0.1s ease-in-out, color 0.1s ease-in-out;
transition: background-color 0.1s ease-in-out, color 0.1s ease-in-out;
}
.ui-datepicker .ui-datepicker-calendar td:hover {
background-color: #eee;
cursor: pointer;
}
.ui-datepicker .ui-datepicker-calendar td a {
text-decoration: none;
}
.ui-datepicker .ui-datepicker-current-day {
background-color: #4289cc;
}
.ui-datepicker .ui-datepicker-current-day a {
color: #fff
}
.ui-datepicker .ui-datepicker-calendar .ui-datepicker-unselectable:hover {
background-color: #fff;
cursor: default;
} 


.panel-success {
    background-color: #D6E9C6;
	
}
.panel-warning {
    background-color: #FAEBCC;
}

#lists .img-thumbnail {
       max-height: 250px !important;
}
#lists .img-thumbnail-crop {
	height: 255px !important;
	text-align: center;
}



#changeLog{
	position: absolute;
	z-index: 10;
	top: 20px;
	left: 35px;
	}


/*mmenu*/
html, body
{
	padding: 0;
	margin: 0;
}
body
{
	background-color: #fff;
	font-family: Arial, Helvetica, Verdana;
	font-size: 14px;
	line-height: 22px;
	color: #666;
	position: relative;
	-webkit-text-size-adjust: none;
}
body *
{
	text-shadow: none;
}






nav:not(.mm-menu)
{
	display: none;
}


.header,
.footer
{
	font-size: 16px;
	font-weight: bold;
	color: #fff;
	line-height: 40px;

	-moz-box-sizing: border-box;
	box-sizing: border-box;	
	width: 100%;
	height: 40px;
	padding: 0 50px;
}
.header.fixed
{
	position: fixed;
	top: 0;
	left: 0;
}
.footer.fixed
{
	position: fixed;
	bottom: 0;
	left: 0;
}
.header a#bmenu
{
	background: center center no-repeat transparent;
	background-image: url( data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAADhJREFUeNpi/P//PwOtARMDHQBdLGFBYtMq3BiHT3DRPU4YR4NrNAmPJuHRJDyahEeT8Ii3BCDAAF0WBj5Er5idAAAAAElFTkSuQmCC );

	display: block;
	width: 40px;
	height: 40px;
	position: absolute;
	top: 0;
	left: 10px;
        border-radius:5px;
}

#titleNav{
	border-radius:5px; 
	padding:10px 10px 5px 8px;
	
	
}
#rtg_nav_root {
	padding: 10px 8px 8px 35px;
}
#rtg_nav_root a { 
	color: <?=rgba( 0.9)?>; 
	font-variant: small-caps;
	font-family: arial;
	letter-spacing: -2px;
	font-size: 20px;
	text-shadow: 1px 1px 1px <?=rgba( 0.1,"bg")?>, -1px 1px 1px <?=rgba( 0.1,"bg")?>, 1px -1px 1px <?=rgba( 0.1,"bg")?>, -1px -1px 1px <?=rgba( 0.1,"bg")?> !important;
	}
#rtg_nav_root a:hover { color: rgba(255, 0, 0, 1); text-decoration:none; }

#titleNav {
	text-align:center;
}
.content
{
	padding: 10px 10px 10px 10px;
}

.mm-listview > li.divider::after {
    border-color: <?=rgba( 0.8)?> !important;
}

/* fleche pour le menu */
#contentbody { margin: 0; background: top left no-repeat #FFF;
	       background-image: url(/img/menu-arrow.png)}
.panel {
    background-color: transparent !important;
}


/*theme*/


.header,
.footer
{
	background: <?=rgba( 0.05,"bg")?>;
}

#titleNav{
	background: <?=rgba( 0.1,"in")?>;
	color: <?=rgba( 0.6)?>;

}

.mm-menu.mm-theme-rtg {
  background: <?=rgba( 0,"bg")?>;
  color: <?=rgba( 0.9)?>; }
  .mm-menu.mm-theme-rtg .mm-header {
    border-color: <?=rgba( 0.1,"bd")?>; }
    .mm-menu.mm-theme-rtg .mm-header > a {
      color: <?=rgba( 1)?>;
      font-variant: small-caps; }
    .mm-menu.mm-theme-rtg .mm-header .mm-btn:before,
    .mm-menu.mm-theme-rtg .mm-header .mm-btn:after {
      border-color: <?=rgba( 0.3,"bd")?>; }
  .mm-menu.mm-theme-rtg .mm-listview > li:after {
    border-color: <?=rgba( 0.1,"bd")?>;
   }
  .mm-menu.mm-theme-rtg .mm-listview > li > a.mm-prev, .mm-menu.mm-theme-rtg .mm-listview > li > a.mm-next {
    color: <?=rgba( 0.8)?>; }
  .mm-menu.mm-theme-rtg .mm-listview > li > a.mm-prev:before, .mm-menu.mm-theme-rtg .mm-listview > li > a.mm-next:after {
    border-color: <?=rgba( 0.3,"in")?>; }
  .mm-menu.mm-theme-rtg .mm-listview > li > a.mm-prev:after, .mm-menu.mm-theme-rtg .mm-listview > li > a.mm-next:before {
    border-color: <?=rgba( 0.1,"in")?>; }
  .mm-menu.mm-theme-rtg .mm-listview > li.mm-selected > a:not(.mm-next),
  .mm-menu.mm-theme-rtg .mm-listview > li.mm-selected > span {
    background: <?=rgba( 0.05,"bg")?>; }
  .mm-menu.mm-theme-rtg.mm-vertical .mm-listview li.mm-opened > a.mm-next,
  .mm-menu.mm-theme-rtg.mm-vertical .mm-listview li.mm-opened > .mm-panel,
  .mm-menu.mm-theme-rtg .mm-listview li.mm-opened.mm-vertical > a.mm-next,
  .mm-menu.mm-theme-rtg .mm-listview li.mm-opened.mm-vertical > .mm-panel {
    background: <?=rgba( 0.03,"bg")?>; }
  .mm-menu.mm-theme-rtg .mm-divider {
    background: <?=rgba( 0.03,"bg")?>; }

.mm-menu.mm-theme-rtg .mm-buttonbar {
  border-color: <?=rgba( 0.6,"bd")?>;
  background: white; }
  .mm-menu.mm-theme-rtg .mm-buttonbar > * {
    border-color: <?=rgba( 0.6,"bg")?>; }
  .mm-menu.mm-theme-rtg .mm-buttonbar > input:checked + label {
    background: <?=rgba( 0.6,"bg")?>;
    color: white; }

.mm-menu.mm-theme-rtg label.mm-check:before {
  border-color: <?=rgba( 0.6,"bd")?>; }

.mm-menu.mm-theme-rtg em.mm-counter {
  color: <?=rgba( 0.3)?>; }

.mm-menu.mm-theme-rtg .mm-footer {
  border-color: <?=rgba( 0.1,"bd")?>;
  color: <?=rgba( 0.3)?>; }

.mm-menu.mm-theme-rtg .mm-fixeddivider span {
  background: <?=rgba( 0.03,"bg");?> }

.mm-menu.mm-pageshadow.mm-theme-rtg:after {
  box-shadow: 0 0 10px <?=rgba( 0.2)?>; }

.mm-menu.mm-theme-rtg .mm-search input {
  background: <?=rgba( 0.05,"in")?>;
  color: <?=rgba( 0.3,"bg")?>; }
.mm-menu.mm-theme-rtg .mm-noresultsmsg {
  color: <?=rgba( 0.3)?>; }

.mm-menu.mm-theme-rtg .mm-indexer a {
  color: <?=rgba( 0.3)?>; }

.mm-menu.mm-theme-rtg label.mm-toggle {
  background: <?=rgba( 0.1,"bg")?>; }
  .mm-menu.mm-theme-rtg label.mm-toggle:before {
    background: white; }
.mm-menu.mm-theme-rtg input.mm-toggle:checked ~ label.mm-toggle {
  background: #4bd963; }

em.mm-counter { right: 60px !important; }

.mm-menu.mm-theme-rtg .mm-listview > li > a.mm-next {
    background-color: <?=rgba( 0.5,"in")?>;
}
.mm-menu.mm-theme-rtg .mm-listview > li > .mm-next.mm-fullsubopen {
    background-color: transparent !important;
}
a.mm-next.mm-fullsubopen:after {
    border-color: <?=rgba( 1)?> !important;
}

.mm-menu.mm-theme-rtg li span {

}

.btn-primary, .modal-header
{
  background-color: <?=rgba( 0,"bg")?> !important;
  color: <?=rgba(0,"in")?>;
}
.btn-primary:hover
{
  background-color: <?=rgba( 0.2,"bg")?> !important;
  border-color: <?=rgba( 0.2,"bd")?>;
}




/* breadcrumb */
.breadcrumb {
	text-align:center;
	padding:8px;
	margin: 2px 30px;
        background: transparent;
	/*centering*/
	display: block;
	overflow: hidden;
	border-radius: 5px;
}

.comment .breadcrumb, .table .breadcrumb{
	margin: 2px !important;
}
.comment .breadcrumb a,.table .breadcrumb a{
	font-size: 10px;
	line-height: 20px;
	padding: 0 8px 0 25px
}
 .comment .breadcrumb a:after, .table .breadcrumb a:after{
	width: 20px; 
	height: 20px;
	right: -8px;
}
.breadcrumb a {
	text-decoration: none;
	outline: none;
	display: block;
	float: left;
	font-size: 16px;
	line-height: 36px;
	color: white;
	/*need more margin on the left of links to accomodate the numbers*/
	padding: 0 10px 0 40px;
	background: <?=rgba( 0,"bg")?>;
	position: relative;
	font-size: 16px;
	font-weight: bold;
}
/*since the first link does not have a triangle before it we can reduce the left padding to make it look consistent with other links*/
.breadcrumb a:first-child {
	border-radius: 5px 0 0 5px; /*to match with the parent's radius*/
	padding-left: 10px;
}
.breadcrumb a:first-child:before {
	left: 14px;
}
.breadcrumb a:last-child {
	border-radius: 0 5px 5px 0; /*this was to prevent glitches on hover*/
	padding-right: 20px;
}

/*hover/active styles*/
.breadcrumb a.active, .breadcrumb a:hover{
	background: <?=rgba( 0,"in")?>;
        color:<?=rgba( 0,"bg")?>;
}
.breadcrumb a.active:after, .breadcrumb a:hover:after {
	background: <?=rgba( 0,"in")?>;
        color:<?=rgba( 0,"bg")?>;
}

/*adding the arrows for the breadcrumbs using rotated pseudo elements*/
.breadcrumb a:after {
	content: '';
	position: absolute;
	top: 0;
	right: -18px; /*half of square's length*/
	/*same dimension as the line-height of .breadcrumb a */
	width: 36px; 
	height: 36px;
	/*as you see the rotated square takes a larger height. which makes it tough to position it properly. So we are going to scale it down so that the diagonals become equal to the line-height of the link. We scale it to 70.7% because if square's: 
	length = 1; diagonal = (1^2 + 1^2)^0.5 = 1.414 (pythagoras theorem)
	if diagonal required = 1; length = 1/1.414 = 0.707*/
	transform: scale(0.707) rotate(45deg);
	/*we need to prevent the arrows from getting buried under the next link*/
	z-index: 1;
	/*background same as links but the gradient will be rotated to compensate with the transform applied*/
	background: <?=rgba( 0,"bg")?>;
	border-radius: 0 5px 0 50px;

        /*stylish arrow design using box shadow*/
	box-shadow: 
		2px -2px 0 2px <?=rgba( 0.4,"bg")?>, 
		3px -3px 0 2px <?=rgba( 0.1,"in")?>;

}
/*we dont need an arrow after the last link*/
.breadcrumb a:last-child:after {
	content: none;
}


