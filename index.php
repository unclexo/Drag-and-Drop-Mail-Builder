<!DOCTYPE html>
<html lang="en">
  <head>
	  <script type="text/javascript">
	  /*<![CDATA[*/
      /*
        * SETUP BASE URL
        * @author         Ivijan-Stefan Stipic <creativform@gmail.com>
        *
        * AVAILABLE OBJECTS
        * window.base              //- Define base URL of this application
        * window.session_id        //- Define unique session ID for current user [timestamp-random]
        * window.start_time        //- Define current start loading timestamp in microseconds
      */
      (function(base, search, replace){
        
        window.start_time = Math.round(new Date().getTime()/1000);
          
        var extend = function(a,b){
            for(var key in b)
                if(b.hasOwnProperty(key))
                    a[key] = b[key];
            return a;
        }, refactor = function(){
            
            if(!replace)
                replace = true;
            
            var elements = extend({
                    script : 'src',
                    img    : 'src',
                    link   : 'href',
                    a      : 'href',
                }, search),
                generateID = function (min, max) {
                    min = min || 0;
                    max = max || 0;

                    if(
						min===0
						|| max===0
						|| !(typeof(min) === "number"
						|| min instanceof Number)
						|| !(typeof(max) === "number"
						|| max instanceof Number)
					) 
                        return Math.floor(Math.random() * 999999) + 1;
                    else
                        return Math.floor(Math.random() * (max - min + 1)) + min;
                };
			
			var baseURL = window.location.protocol + '//' + window.location.hostname + base;

			if (localStorage.getItem("session_id"))
			{
				window.session_id = localStorage.getItem("session_id");
			}
			else
			{
				var generate = new Date().getTime() + '-' + generateID(10000,99999) + '' + generateID(100000,999999) + '' + generateID(1000000,9999999) + '' + generateID(10000000,99999999);
				window.session_id = generate;
				localStorage.setItem("session_id",generate);
			}
            
            localStorage.setItem("baseURL",baseURL);
            window.base = baseURL;
            
			for(tag in elements)
			{
				var list = document.getElementsByTagName(tag)
					listMax = list.length;
				if(listMax>0)
				{
					for(i=0; i<listMax; i++)
					{
						var src = list[i].getAttribute(elements[tag]);
						if(
							!(/^(((a|o|s|t)?f|ht)tps?|s(cp|sh)|as2|chrome|about|javascript)\:(\/\/)?([a-z0-9]+)?/gi.test(src))
							&& !(/^#\S+$/gi.test(src))
							&& '' != src
							&& null != src
							&& replace
						)
						{
							src = baseURL + '/' + src;
							list[i].setAttribute('src',src);
						}
					}
				}
			}
			
		}
		document.addEventListener("DOMContentLoaded", function() {
			refactor();
		});
    }('/email-template-builder'));

    if (localStorage.getItem("baseURL")){
        window.base = localStorage.getItem("baseURL");
	}
	if (localStorage.getItem("session_id")){
        window.session_id = localStorage.getItem("session_id");
	}
	/* ]]> */
    </script>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>MAIL EDITOR</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/jquery-ui.css">
    <link rel="stylesheet" href="assets/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-slider.min.css">
    <link rel="stylesheet" href="assets/plugins/medium-editor/medium-editor.min.css">
    <link rel="stylesheet" href="assets/plugins/medium-editor/template.min.css">
    <link rel="stylesheet" href="assets/css/spectrum.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
  </head>
  <body>
    <div class="container-fullscreen">

    	<div class="container text-center">
        	<div id="choose-template" class="text-center d-none">
                <button class="choose" type="button" data-id="no-sidebar"><img src="assets/img/no-sidebar.jpg" class="img-fluid" alt=""><p>No Sidebar (wide)</p></button>
                <button class="choose" type="button" data-id="left-sidebar"><img src="assets/img/left-sidebar.jpg" class="img-fluid" alt=""><p>Left Sidebar</p></button>
                <button class="choose" type="button" data-id="right-sidebar"><img src="assets/img/right-sidebar.jpg" class="img-fluid" alt=""><p>Right Sidebar</p></button>
                <button class="choose" type="button" data-id="both-sidebar"><img src="assets/img/both-sidebar.jpg" class="img-fluid" alt=""><p>Both Sidebars</p></button>
            </div>
        </div>

        <div class="container-content d-none" id="mail-template">
            Content
        </div>

        <div class="container-sidebar d-none" id="option-tabs">

            <div id="get-options" class="text-center">

                <p class="lead">Drag & Drop elements</p>

                <div class="get-options choose" data-id="content" id="content">
                    <i class="fa fa-file-text-o"></i>
                    <div>Text</div>
                </div>
                <div class="get-options choose" data-id="image" id="image">
                    <i class="fa fa-picture-o"></i>
                    <div>Image</div>
                </div>
                <div class="get-options choose" data-id="link" id="link">
                    <i class="fa fa-link"></i>
                    <div>Link</div>
                </div>
                <div class="get-options choose" data-id="divider" id="divider">
                    <i class="fa fa-minus"></i>
                    <div>Divider</div>
                </div>

                <div id="editor"></div>

                <ul id="attach-data" class="list-group"></ul>
            </div>
            
        </div>
    </div>

	<div id="modal" class="reset-this"></div>

    <button class="btn btn-lg btn-success btn-left-bottom" type="button" id="preview" title="Preview" data-toggle="tooltip" data-placement="top" data-trigger="hover"><i class="fa fa-search-plus"></i></button>
      
    <button class="btn btn-lg btn-secondary btn-left-bottom-3" type="button" id="setting" title="Layout Options" data-toggle="tooltip" data-placement="top" data-trigger="hover"><span class="fa fa-cog fa-spin"></span></button>      
      
    <div id="alerts"></div>
      
    <div class="tools tools-left" id="settings">
        <div class="tools-header">
            <button type="button" class="close" data-dismiss="tools" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4><span class="fa fa-cog fa-spin"></span> Settings</h4>
        </div>
        <div class="tools-body">

            <h6 class="text-left option-title mt-3">Layout</h6>
            
            <div class="form-group">
                <label for="body-layout-bkg-color-form" class="col-form-label">Background Color:</label>
                <div>
                    <div id="body-layout-bkg-color" class="input-group colorpicker-component">
                        <input type="text" value="" class="form-control input-sm" id="body-layout-bkg-color-form">
                        <span class="input-group-addon"><i></i></span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="body-layout-bkg-color-form" class="col-form-label">Body Color:</label>
                <div>
                    <div id="body-layout-bkg-color-body" class="input-group colorpicker-component">
                        <input type="text" value="" class="form-control input-sm" id="body-layout-bkg-color-body-form">
                        <span class="input-group-addon"><i></i></span>
                    </div>
                </div>
            </div>

            <h6 class="text-left option-title mt-5">Header Section</h6>

            <div class="form-group">
                <label for="head-bkg-color-form" class="col-form-label">Background Color:</label>
                <div>
                    <div id="head-bkg-color" class="input-group colorpicker-component">
                        <input type="text" value="" class="form-control input-sm" id="head-bkg-color-form">
                        <span class="input-group-addon"><i></i></span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="head-height" class="col-form-label">Height:</label>
                <div class="bs-slider-container">
                    <input type="text" class="form-control input-sm" id="head-height" data-slider-id="head-height" data-slider-min="0" data-slider-max="1000" data-slider-step="10" data-slider-value="0">
                    <p class="text-left small">Height: <span id="head-height-val">auto</span></p>
                </div>
            </div>


            <div id="dd-body-exists">

                <h6 class="text-left option-title mt-5">Content Section</h6>

                <div class="form-group">
                    <label for="content-bkg-color-form" class="col-form-label">Background Color:</label>
                    <div>
                        <div id="content-bkg-color" class="input-group colorpicker-component">
                            <input type="text" value="" class="form-control input-sm" id="content-bkg-color-form">
                            <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="content-height" class="col-form-label">Height:</label>
                    <div class="bs-slider-container">
                        <input type="text" class="form-control input-sm" id="content-height" data-slider-id="content-height" data-slider-min="0" data-slider-max="1000" data-slider-step="10" data-slider-value="0">
                        <p class="text-left small">Height: <span id="content-height-val">auto</span></p>
                    </div>
                </div>

            </div>

            <div id="dd-sidebar-left-exists">
                <h6 class="text-left option-title mt-5">Left Sidebar Section</h6>

                <div class="form-group">
                    <label for="left-bkg-color-form" class="col-form-label">Background Color:</label>
                    <div>
                        <div id="left-bkg-color" class="input-group colorpicker-component">
                            <input type="text" value="" class="form-control input-sm" id="left-bkg-color-form">
                            <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="left-height" class="col-form-label">Height:</label>
                    <div class="bs-slider-container">
                        <input type="text" class="form-control input-sm" id="left-height" data-slider-id="left-height" data-slider-min="0" data-slider-max="1000" data-slider-step="10" data-slider-value="0">
                        <p class="text-left small">Height: <span id="left-height-val">auto</span></p>
                    </div>
                </div>

            </div>

            <div id="dd-sidebar-right-exists">
                <h6 class="text-left option-title">Right Sidebar Section</h6>

                <div class="form-group">
                    <label for="right-bkg-color-form" class="col-form-label">Background Color:</label>
                    <div>
                        <div id="right-bkg-color" class="input-group colorpicker-component">
                            <input type="text" value="" class="form-control input-sm" id="right-bkg-color-form">
                            <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="right-height" class="col-form-label">Height:</label>
                    <div class="bs-slider-container">
                        <input type="text" class="form-control input-sm" id="right-height" data-slider-id="right-height" data-slider-min="0" data-slider-max="1000" data-slider-step="10" data-slider-value="0">
                        <p class="text-left small">Height: <span id="right-height-val">auto</span></p>
                    </div>
                </div>

            </div>

            <h6 class="text-left option-title">Footer Section</h6>

            <div class="form-group">
                <label for="footer-bkg-color-form" class="col-form-label">Background Color:</label>
                <div>
                    <div id="footer-bkg-color" class="input-group colorpicker-component">
                        <input type="text" value="" class="form-control input-sm" id="footer-bkg-color-form">
                        <span class="input-group-addon"><i></i></span>
                    </div>
                </div>
            </div> 

            <div class="form-group">
                <label for="footer-height" class="col-form-label">Height:</label>
                <div class="bs-slider-container">
                    <input type="text" class="form-control input-sm" id="footer-height" data-slider-id="footer-height" data-slider-min="0" data-slider-max="1000" data-slider-step="10" data-slider-value="0">
                    <p class="text-left small">Height: <span id="footer-height-val">auto</span></p>
                </div>
            </div>

        </div>
        <div class="tools-footer">
            <div class="button-group text-center">
                <button class="btn btn-success btn-sm" data-dismiss="tools" type="button" id="send-message"><span class="glyphicon glyphicon-ok"></span> I'm Done</button>
                <button class="btn btn-warning btn-sm" type="button" id="test"><span class="glyphicon glyphicon-envelope"></span> Send Test</button>
                <button class="btn btn-danger btn-sm" type="button" id="delete"><span class="glyphicon glyphicon-remove-sign"></span> Delete Project</button>
            </div>
        </div>
    </div>

    <script src="https://use.fontawesome.com/86c8941095.js"></script>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery-ui.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/sweetalert.min.js"></script>
    <script src="assets/js/debounce.js"></script>
    <script src="assets/js/bootstrap-colorpicker.min.js"></script>
    <script src="assets/js/bootstrap-slider.min.js"></script>
    <script src="assets/js/spectrum.min.js"></script>
    <script src="assets/plugins/medium-editor/medium-editor.min.js"></script>
    <script src="assets/js/creative.tools.js"></script>
    <script src="assets/js/html2canvas.js"></script>
    <script src="assets/js/creative.tools.js"></script>
    <script src="assets/js/editor.js"></script>

  </body>
</html>
