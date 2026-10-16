<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>MODx CMF Manager Login</title>
    <meta http-equiv="content-type" content="text/html; charset=[+modx_charset+]" />
    <meta name="robots" content="noindex, nofollow" />
    <style type="text/css">

		body {font-family:  Arial, HelveticaNeue, "Helvetica Neue", Helvetica, "Hiragino Kaku Gothic Pro", Meiryo, sans-serif;}
		input {font-family:inherit;}
		#login {
			background: #eeeeee url(media/style/[+theme+]/images/body.jpg);
			margin: 12% 0 0;
		}
		#mx_loginbox {
			width: 309px;
			margin: 0 auto;
		}
		.sectionBody {
			border: 1px solid #E6E6E6;
			background: #FEFEFE;
		    padding: 25px 0 0 20px;
			overflow: hidden;
			-webkit-box-shadow: 0 0 5px rgba(50, 50, 50, 0.2);
			-moz-box-shadow:    0 0 5px rgba(50, 50, 50, 0.2);
			box-shadow:         0 0 5px rgba(50, 50, 50, 0.2);
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;
		}
		.logo {
			margin: 0 0 0 14px;
		}
		.logo img{
			border: 0 none;
			margin: 0 0 17px;
		}
		.sectionBody label {
			color: #666666;
			display: block;
			font: 14px Arial;
			margin: 0 0 11px;
		}
		.sectionBody .text {
			width: 261px;
			height: 33px;
			border: 1px solid #E5E5E5;
			text-indent: 5px;
			margin: 0 0 10px;
			font-size: 20px;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;
			-webkit-box-shadow: 0 0 5px rgba(188, 188, 188, 0.2);
			-moz-box-shadow:    0 0 5px rgba(188, 188, 188, 0.2);
			box-shadow:         0 0 5px rgba(188, 188, 188, 0.2);
		}
		.sectionBody .text:focus {
			border: 1px solid #DECBA5;
			-webkit-box-shadow: 0 0 5px rgba(222, 203, 165, 0.5);
			-moz-box-shadow:    0 0 5px rgba(222, 203, 165, 0.5);
			box-shadow:         0 0 5px rgba(222, 203, 165, 0.5);
		}
		#rememberme {
			float: left;
			margin: 3px 5px 0 1px;
		}
		.sectionBody .remtext {
			color: #999999;
			display: block;
			float: left;
			font-size: 13px;
			margin: 0;
		} 
		#submitButton {
			display: block;
			float: right;
			border: 0;
			width: 91px;
			height: 33px;
			cursor: pointer;
			/*text-indent: -9999px;*/
			color:#fff;
			font-size: 14px;
			font-weight: 100;
		    margin-top: -7px;
		    margin-right:20px;
		    margin-bottom:10px;
			background: url(media/style/[+theme+]/images/misc/enter.png) no-repeat top left;
		}
		#onManagerLoginFormRender {
			clear: both;
		}
		#FMP-email_label {
			color: #666666;
			font: 13px Arial;
			margin: 0 0 7px;
		}
		#FMP-email {
			width: 261px;
			height: 33px;
			border: 1px solid #E5E5E5;
			text-indent: 5px;
			margin: 0 0 10px;
			font-size: 14px;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;
			-webkit-box-shadow: 0 0 5px rgba(188, 188, 188, 0.2);
			-moz-box-shadow:    0 0 5px rgba(188, 188, 188, 0.2);
			box-shadow:         0 0 5px rgba(188, 188, 188, 0.2);
		}
		#FMP-email:focus {
			border: 1px solid #DECBA5;
			-webkit-box-shadow: 0 0 5px rgba(222, 203, 165, 0.5);
			-moz-box-shadow:    0 0 5px rgba(222, 203, 165, 0.5);
			box-shadow:         0 0 5px rgba(222, 203, 165, 0.5);
		}
		#FMP-email_button {
			border: 0 none;
			cursor: pointer;
			display: block;
			float: right;
			height: 33px;
			margin: 5px 26px 22px 0;
			/*text-indent: -9999px;*/
			color:#fff;
			font-size: 14px;
			font-weight: 100;
			width: 111px;
			background: url(media/style/[+theme+]/images/misc/submit.png) no-repeat top left;
		}
		.loginLicense {
			width: 309px;
			margin: 0 auto;
			display: block;
		}
		.loginLicense a {
			color: #999999;
			display: block;
			font: 13px Arial;
			margin: 13px 0 0 21px;
			text-decoration: underline;
		}
		#ForgotManagerPassword-show_form {
			color: #999999;
			display: block;
			font: 13px Arial;
			margin: 0 0 10px;
			text-align: left;
		}
		.error {
			font: 13px Arial;
			color: #f00;
		}
		.gpl {position:absolute; bottom: 0; right:0; color: #B2B2B2;margin: 0.5em auto;font-size: 80%;}
	
         .gpl a, .loginLicense a {color: #B2B2B2;}
    </style>

    <script src="media/script/mootools/mootools.js" type="text/javascript"></script>

    <script type="text/javascript">
    /* <![CDATA[ */
        if (top.frames.length!=0) {
            top.location=self.document.location;
        }
        
        window.addEvent('domready', function() {
            $('submitButton').addEvent('click', function(e) {
                 e = new Event(e).stop();
                 params = 'ajax=1&' + $('loginfrm').toQueryString();
                 url = 'processors/login.processor.php';
                 new Ajax(url,
                    {
                        method: 'post',
                        postBody: params,
                        onComplete:ajaxReturn
                    }
                ).request();
                $$('input').setProperty('readonly', 'readonly');
            });  
			
			// Initial focus
			if ($('username').value != '') {
				$('password').focus();
			} else {
				$('username').focus();
			}
			   
        });

        function ajaxReturn(response) {
            var header = response.substr(0,9);
            if (header.toLowerCase()=='location:') top.location = response.substr(10);
            else {
                var cimg = $('captcha_image');
                if (cimg) {
                	cimg.src = 'includes/veriword.php?rand=' + Math.random();
                }
                $$('input').removeProperty('readonly');
                alert(response);
            }
        }
    /* ]]> */
    </script>
</head>
<body id="login">
<div id="mx_loginbox">
    <form method="post" name="loginfrm" id="loginfrm" action="processors/login.processor.php">
    <!-- anything to output before the login box via a plugin? -->
    [+OnManagerLoginFormPrerender+]
        <div class="sectionHeader">
			<a class="logo" href="../" title="[+site_name+]">
				<svg style="max-width: 274px;max-height: 84px;" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="113.442mm" height="33.0818mm" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 5284 1541" xmlns:xlink="http://www.w3.org/1999/xlink">
							 <defs>
								<style type="text/css">
								 
									.fil3 {fill:#788C9A}
									.fil2 {fill:#04557A}
									.fil0 {fill:#788C9A;fill-rule:nonzero}
									.fil1 {fill:#04557A;fill-rule:nonzero}
								 
								</style>
							 </defs>
							 <g id="svg_empty_id">
								<metadata id="CorelCorpID_0Corel-Layer"></metadata>
								<g id="_1119423072">
								
								 <path class="fil0" d="M1175 647l175 0 15 0c9,0 14,0 17,0l35 2c12,0 21,1 26,2l14 3c3,0 5,1 8,2 3,0 6,1 9,3 19,5 37,13 55,24 28,17 49,41 64,70 15,28 23,60 23,94 0,65 -26,116 -76,154 -23,17 -48,28 -76,35 -14,2 -29,4 -45,6 -17,2 -34,2 -52,2l-126 0 0 269 -66 0 0 -666zm181 337c35,0 61,-2 77,-5 10,-2 20,-4 30,-8 9,-4 19,-8 28,-14 17,-11 31,-26 42,-47 11,-21 16,-42 16,-64 0,-14 -2,-27 -5,-40 -2,-6 -4,-12 -7,-18 -3,-6 -6,-12 -10,-17 -14,-22 -34,-38 -59,-49 -26,-10 -58,-16 -96,-16l-22 0 -23 0 -86 0 0 278 115 0z"></path>
								 <path class="fil0" d="M1120 978c0,62 -15,120 -47,173 -31,53 -75,95 -130,127 -55,31 -113,47 -175,47 -62,0 -119,-15 -173,-45 -54,-30 -97,-72 -130,-125 -33,-53 -49,-110 -49,-172 0,-63 16,-121 47,-173 32,-52 75,-94 129,-126 55,-31 113,-47 176,-47 62,0 121,15 174,45 54,30 97,71 129,124 33,53 49,111 49,172zm-494 -239c-43,24 -78,58 -104,103 -26,44 -39,92 -39,141 0,50 13,96 39,138 27,43 62,77 107,102 45,25 91,38 140,38 19,0 37,-2 55,-5 10,-2 19,-4 28,-7 8,-3 18,-6 27,-10 34,-14 64,-35 91,-60 27,-26 47,-57 62,-91 14,-35 21,-72 21,-109 0,-51 -12,-97 -38,-139 -26,-42 -61,-76 -105,-102 -22,-12 -45,-21 -69,-28l-36 -6c-12,-2 -24,-3 -36,-3l-5 0c-25,0 -49,3 -72,9 -23,7 -45,16 -66,29z"></path>
								 <polygon class="fil1" points="287,564 287,564 56,564 0,504 287,504 341,504 640,504 584,564 354,564 354,1313 287,1313 "></polygon>
								 <path class="fil0" d="M4833 647l158 0 58 1 18 0 32 4c30,4 57,13 82,26 33,20 58,46 76,79 18,33 27,69 27,107 0,34 -8,66 -26,97 -17,30 -41,55 -72,75 -7,4 -14,8 -21,11 -8,4 -16,7 -26,11l-26 5 -26 2 188 248 -83 0 -226 -302 20 0c37,0 66,-1 87,-3 20,-2 40,-7 58,-15 28,-13 49,-31 64,-55 7,-12 13,-25 17,-38 4,-13 6,-27 6,-42 0,-24 -6,-47 -16,-67 -11,-21 -26,-38 -45,-52 -20,-14 -42,-23 -67,-27 -25,-4 -53,-6 -85,-6l-106 0 0 607 -66 0 0 -666z"></path>
								 <path class="fil0" d="M4751 978c0,62 -15,120 -47,173 -31,53 -75,95 -130,127 -55,31 -114,47 -175,47 -62,0 -119,-15 -173,-45 -54,-30 -97,-72 -130,-125 -33,-53 -49,-110 -49,-172 0,-63 16,-121 47,-173 32,-52 75,-94 129,-126 55,-31 113,-47 176,-47 62,0 121,15 174,45 54,30 97,71 129,124 33,53 49,111 49,172zm-494 -239c-43,24 -78,58 -104,103 -26,44 -39,92 -39,141 0,50 13,96 39,138 27,43 62,77 107,102 45,25 91,38 139,38 20,0 38,-2 56,-5 10,-2 19,-4 28,-7 8,-3 17,-6 27,-10 34,-14 64,-35 91,-60 26,-26 47,-57 62,-91 14,-35 21,-72 21,-109 0,-51 -12,-97 -38,-139 -26,-42 -61,-76 -106,-102 -21,-12 -44,-21 -68,-28l-36 -6c-12,-2 -25,-3 -37,-3l-4 0c-25,0 -49,3 -72,9 -23,7 -45,16 -66,29z"></path>
								 <polygon class="fil0" points="3849,707 3695,707 3695,647 4067,647 4067,707 3915,707 3915,1313 3849,1313 "></polygon>
								 <polygon class="fil0" points="3412,647 3479,647 3479,1253 3755,1253 3755,1313 3412,1313 "></polygon>
								 <path class="fil0" d="M2995 647l74 0 285 666 -71 0 -97 -223 -311 0 -96 223 -72 0 288 -666zm165 382l-128 -306 -131 306 259 0z"></path>
								 <polygon class="fil0" points="2283,647 2648,647 2648,707 2350,707 2350,947 2638,947 2638,1007 2350,1007 2350,1253 2648,1253 2648,1313 2283,1313 "></polygon>
								 <polygon class="fil1" points="1748,647 1748,1313 1681,1313 1681,647 "></polygon>
								 <polygon class="fil1" points="826,907 826,907 825,907 "></polygon>
								
										 <polygon class="fil2 let-star an-svg circ" points="2367,292 2408,378 2502,390 2433,455 2451,549 2367,503 2284,549 2301,455 2232,390 2326,378 "></polygon>
									
								
								 <polygon class="fil2 let-star an-svg circ" points="3039,292 3080,378 3174,390 3105,455 3123,549 3039,503 2956,549 2973,455 2904,390 2998,378 "></polygon>
								 <polygon class="fil2 let-star an-svg circ" points="3711,292 3752,378 3846,390 3777,455 3795,549 3711,503 3628,549 3645,455 3576,390 3670,378 "></polygon>
								 <polygon class="fil2 let-star an-svg circ" points="4383,292 4424,378 4518,390 4449,455 4467,549 4383,503 4300,549 4317,455 4248,390 4342,378 "></polygon>
								 <polygon class="fil2 let-star an-svg circ" points="5055,292 5096,378 5190,390 5121,455 5139,549 5055,503 4972,549 4989,455 4920,390 5014,378 "></polygon>
								 <path class="fil1" d="M1465 564l-440 -475 -329 354 -82 0 154 -166 0 -148 66 0 0 76 191 -205 466 504 214 0 40 0 194 0c72,0 137,29 184,77 47,47 76,113 76,185 0,72 -29,137 -76,185 -40,40 -92,67 -150,75l266 287 -82 0 -321 -346 63 0 20 0 20 0c55,0 105,-22 141,-59 36,-36 59,-87 59,-142 0,-56 -23,-106 -59,-143 -36,-36 -86,-59 -141,-59l-194 0 0 0 -268 0 0 0 -12 0z"></path>
								 <polygon class="fil3" points="1210,443 839,443 1025,243 "></polygon>
								</g>
							 </g>
							</svg>
			</a>
		</div>
        <div class="sectionBody">
            <!--<p class="loginMessage">[+login_message+]</p>-->
            <label for="username">[+username+]</label>
            <input type="text" class="text" name="username" id="username" tabindex="1" value="[+uid+]" />
            <label for="password">[+password+]</label>
            <input type="password" class="text" name="password" id="password" tabindex="2" value="" />
            <p class="caption">[+login_captcha_message+]</p>
            <div>[+captcha_image+]</div>
            [+captcha_input+]
            <input type="checkbox" id="rememberme" name="rememberme" tabindex="4" value="1" class="checkbox" [+remember_me+] />
			<label for="rememberme" style="cursor:pointer" class="remtext">[+remember_username+]</label>
            <input type="submit" class="login" id="submitButton" value="[+login_button+]" />
            <!-- anything to output before the login box via a plugin ... like the forgot password link? -->
            [+OnManagerLoginFormRender+]
        </div>
    </form>
</div>
<!-- close #mx_loginbox -->

<!-- convert this to a language include -->
<p class="loginLicense" >
	
</p>
<div class="gpl">&copy; 2005-2013 by the <a href="http://modx.com/" target="_blank">MODX</a>. <strong>MODX</strong>&trade; is licensed under the GPL.</div>
</body>
</html>