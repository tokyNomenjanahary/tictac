<!doctype html>
<html class="no-js">
  <head>
    <meta charset="utf-8">
    <title>{{ __('maintenance.title') }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Site Maintenance">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="assets/css/style.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Varela&display=swap" rel="stylesheet">
  </head>
  <body>
    <header class="header" role="banner">
      <div class="message">
         
		  <div class="lo7go" style="text-align: center;"><img src="/img/blue-logo.png"  alt="logo"></div>
      
          <p>{{ __('maintenance.description') }}</p>
          <div>
          <a href="mailto: contact@bailti.fr">{{ __('maintenance.contact') }}</a>, {{ __('maintenance.retour') }}
          </div>
        <p>&mdash; {{ __('maintenance.equipe') }}</p>
          <p> </p>
      </div>
    </header>
  </body>


  <style>
  .clear:before, .clear:after {
  content: ' ';
  display: table; }

.clear {
  *zoom: 1; }
  .clear:after {
    clear: both; }

* {
  box-sizing: border-box;
  font-smoothing: antialiased;
  text-rendering: optimizeLegibility; 
  text-align: center;}

html {
  font-size: 62.5%; }

body {
  font: 300 13px/1.6 'Helvetica Neue', Helvetica, Arial;
  color: #444;
  transition: all .4s .3s ease-in; }

body, html {
  min-height: 100vh;
  overflow: hidden; }


html {
  font-family: sans-serif;
  /* 1 */
  -ms-text-size-adjust: 100%;
  /* 2 */
  -webkit-text-size-adjust: 100%;
  /* 2 */ }

body {
  margin: 0; }





audio,
canvas,
progress,
video {
  display: inline-block;
  /* 1 */
  vertical-align: baseline;
  /* 2 */ }


audio:not([controls]) {
  display: none;
  height: 0; }


[hidden],
template {
  display: none; }


a {
  background-color: transparent; }


abbr[title] {
  border-bottom: 1px dotted; }



dfn {
  font-style: italic; }

h1, p{
	font-size: 15px;
    /* margin: 0.67em 0; */
    font-family: 'Varela';}




sub,
sup {
  font-size: 75%;
  line-height: 0;
  position: relative;
  vertical-align: baseline; }

sup {
  top: -0.5em; }

sub {
  bottom: -0.25em; }


img {
  border: 0; }


svg:not(:root) {
  overflow: hidden; }


figure {
  margin: 1em 40px; }

hr {
  box-sizing: content-box;
  height: 0; }



button[disabled],
html input[disabled] {
  cursor: default; }


button::-moz-focus-inner,
input::-moz-focus-inner {
  border: 0;
  padding: 0; }



fieldset {
  border: 1px solid #c0c0c0;
  margin: 0 2px;
  padding: 0.35em 0.625em 0.75em; }


legend {
  border: 0;
  /* 1 */
  padding: 0;
  /* 2 */ }

textarea {
  overflow: auto; }


optgroup {
  font-weight: bold; }


table {
  border-collapse: collapse;
  border-spacing: 0; }

td,
th {
  padding: 0; }

html {
  background: white; }

body {
  background-color: #e7f1f5;
  background-image: repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(255, 255, 255, 0.01) 20px, rgba(255, 255, 255, 0.01) 40px);
  color: black; }

body a, html a {
  color: #2b597f;
  font-weight: bold;
  text-decoration: none; }

h1 {
  margin-bottom: 0;
  line-height: 1; }

p {
  margin-top: 0; }


.status {
  position: absolute;
  bottom: 100%;
  background: rgba(0, 0, 0, 0.1);
  color: white;
  left: 0;
  width: 100%;
  text-align: center;
  text-transform: uppercase;
  padding: 1em; }

@media only screen and (max-width: 480px) {
  .logo {
    padding: 1em 2em;
    margin-top: 3.5em;
    position: relative;
    -ms-transform: none;
        transform: none;
    top: auto;
    left: auto; } }

.nav ul {
  list-style: none; }

@font-face {
  font-family: 'Font-Name';
  src: url("../fonts/font-name.eot");
  src: url("../fonts/font-name.eot?#iefix") format("embedded-opentype"), url("../fonts/font-name.woff") format("woff"), url("../fonts/font-name.ttf") format("truetype"), url("../fonts/font-name.svg#font-name") format("svg");
  font-weight: normal;
  font-style: normal; }

::-moz-selection {
  background: #333;
  color: #fff;
  text-shadow: none; }

::selection {
  background: #333;
  color: #fff;
  text-shadow: none; }

::-moz-selection {
  background: #333;
  color: #fff;
  text-shadow: none; }

::-webkit-selection {
  background: #333;
  color: #fff;
  text-shadow: none; }

html {
  background: white; }

body {
  background-color:#e7f1f5;
  background-image: repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(255, 255, 255, 0.01) 20px, rgba(255, 255, 255, 0.01) 40px);
  color: black; }

body a, html a {
  color: #2b597f;
  font-weight: bold;
  text-decoration: none; }

h1 {
  margin-bottom: 0;
  line-height: 1; }

p {
  margin-top: 0;
  color: #525265;
    font-size: 18px;
    margin-top: 15px;
  
   }

.message {
	background: white;
    padding: 6px;
    border-radius: 2px;
    box-shadow: 0 0 3em rgba(0, 0, 0, 0.5);
    position: absolute;
    top: 50%;
    left: 50%;
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    text-align: left;
    max-width: 529px;
    min-width: 302px;
    padding: 0px 34px;
  }
  .message img {
    display: inline-block;
    margin-top: 1.5em;
    max-width: 100%; }

.status {
  position: absolute;
  bottom: 100%;
  background: rgba(0, 0, 0, 0.1);
  color: white;
  left: 0;
  width: 100%;
  text-align: center;
  text-transform: uppercase;
  padding: 1em; }

@media only screen and (max-width: 480px) {
  .message {
    padding: 1em 2em;
    margin-top: 3.5em;
    position: relative;
    -ms-transform: none;
        transform: none;
    top: auto;
    left: auto; } }

@media print {
  * {
    background: transparent !important;
    color: #000 !important;
    box-shadow: none !important;
    text-shadow: none !important; }
  a,
  a:visited {
    text-decoration: underline; }
  a[href]:after {
    content: " (" attr(href) ")"; }
  abbr[title]:after {
    content: " (" attr(title) ")"; }
  .ir a:after,
  a[href^="javascript:"]:after,
  a[href^="#"]:after {
    content: ""; }
  pre,
  blockquote {
    border: 1px solid #999;
    page-break-inside: avoid; }
  thead {
    display: table-header-group; }
  tr,
  img {
    page-break-inside: avoid; }
  img {
    max-width: 100% !important; }
  @page {
    margin: 0.5cm; }
  p,
  h2,
  h3 {
    orphans: 3;
    widows: 3; }
  h2,
  h3 {
    page-break-after: avoid; } }</style>
</html>