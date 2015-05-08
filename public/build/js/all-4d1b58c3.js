
/**
 * 0.1.0
 * Deferred load js/css file, used for ui-jq.js and Lazy Loading.
 * 
 * @ flatfull.com All Rights Reserved.
 * Author url: http://themeforest.net/user/flatfull
 */
var uiLoad = uiLoad || {};

(function($, $document, uiLoad) {
	"use strict";

		var loaded = [],
		promise = false,
		deferred = $.Deferred();

		/**
		 * Chain loads the given sources
		 * @param srcs array, script or css
		 * @returns {*} Promise that will be resolved once the sources has been loaded.
		 */
		uiLoad.load = function (srcs) {
			srcs = $.isArray(srcs) ? srcs : srcs.split(/\s+/);
			if(!promise){
				promise = deferred.promise();
			}

      $.each(srcs, function(index, src) {
      	promise = promise.then( function(){
      		return src.indexOf('.css') >=0 ? loadCSS(src) : loadScript(src);
      	} );
      });
      deferred.resolve();
      return promise;
		};

		/**
		 * Dynamically loads the given script
		 * @param src The url of the script to load dynamically
		 * @returns {*} Promise that will be resolved once the script has been loaded.
		 */
		var loadScript = function (src) {
			if(loaded[src]) return loaded[src].promise();

			var deferred = $.Deferred();
			var script = $document.createElement('script');
			script.src = src;
			script.onload = function (e) {
				deferred.resolve(e);
			};
			script.onerror = function (e) {
				deferred.reject(e);
			};
			$document.body.appendChild(script);
			loaded[src] = deferred;

			return deferred.promise();
		};

		/**
		 * Dynamically loads the given CSS file
		 * @param href The url of the CSS to load dynamically
		 * @returns {*} Promise that will be resolved once the CSS file has been loaded.
		 */
		var loadCSS = function (href) {
			if(loaded[href]) return loaded[href].promise();

			var deferred = $.Deferred();
			var style = $document.createElement('link');
			style.rel = 'stylesheet';
			style.type = 'text/css';
			style.href = href;
			style.onload = function (e) {
				deferred.resolve(e);
			};
			style.onerror = function (e) {
				deferred.reject(e);
			};
			$document.head.appendChild(style);
			loaded[href] = deferred;

			return deferred.promise();
		}

})(jQuery, document, uiLoad);
// lazyload config

var jp_config = {
  easyPieChart:   [ 'build/js/charts/easypiechart/jquery.easy-pie-chart.js' ],
  sparkline:      [ 'build/js/charts/sparkline/jquery.sparkline.min.js' ],
  plot:           [ 'build/js/charts/flot/jquery.flot.min.js', 
                    'build/js/charts/flot/jquery.flot.resize.js',
                    'build/js/charts/flot/jquery.flot.tooltip.min.js',
                    'build/js/charts/flot/jquery.flot.spline.js',
                    'build/js/charts/flot/jquery.flot.orderBars.js',
                    'build/js/charts/flot/jquery.flot.pie.min.js' ],
  slimScroll:     [ 'build/js/slimscroll/jquery.slimscroll.min.js' ],
  vectorMap:      [ 'build/js/jvectormap/jquery-jvectormap.min.js', 
                    'build/js/jvectormap/jquery-jvectormap-world-mill-en.js',
                    'build/js/jvectormap/jquery-jvectormap-us-aea-en.js',
                    'build/js/jvectormap/jquery-jvectormap.css' ],
  sortable:       [ 'build/js/sortable/jquery.sortable.js' ],
  nestable:       [ 'build/js/nestable/jquery.nestable.js',
                      'build/js/nestable/nestable.css' ],
  filestyle:      [ 'build/js/file/bootstrap-filestyle.min.js' ],
  slider:         [ 'build/js/slider/bootstrap-slider.js',
                      'build/js/slider/slider.css' ],
  chosen:         [ 'build/js/chosen/chosen.jquery.min.js',
                      'build/js/chosen/chosen.css' ],
  TouchSpin:      [ 'build/js/spinner/jquery.bootstrap-touchspin.min.js',
                      'build/js/spinner/jquery.bootstrap-touchspin.css' ],
  wysiwyg:        [ 'build/js/wysiwyg/bootstrap-wysiwyg.js',
                      'build/js/wysiwyg/jquery.hotkeys.js' ],
  dataTable:      [ 'build/js/datatables/jquery.dataTables.min.js',
                      'build/js/datatables/dataTables.bootstrap.js',
                      'build/js/datatables/dataTables.bootstrap.css' ],
  footable:       [ 'build/js/footable/footable.all.min.js',
                      'build/js/footable/footable.core.css' ]
};
+function ($) {

  $(function(){

      $("[ui-jq]").each(function(){
        var self = $(this);
        var options = eval('[' + self.attr('ui-options') + ']');

        if ($.isPlainObject(options[0])) {
          options[0] = $.extend({}, options[0]);
        }

        uiLoad.load(jp_config[self.attr('ui-jq')]).then( function(){          
          self[self.attr('ui-jq')].apply(self, options);
        });
      });

  });
}(jQuery);
+function ($) {

  $(function(){

      // nav
      $(document).on('click', '[ui-nav] a', function (e) {
        var $this = $(e.target), $active;
        $this.is('a') || ($this = $this.closest('a'));
        
        $active = $this.parent().siblings( ".active" );
        $active && $active.toggleClass('active').find('> ul:visible').slideUp(200);
        
        ($this.parent().hasClass('active') && $this.next().slideUp(200)) || $this.next().slideDown(200);
        $this.parent().toggleClass('active');
        
        $this.next().is('ul') && e.preventDefault();
      });

  });
}(jQuery);
+function ($) {

    $(function(){

        $(document).on('click', '[ui-toggle]', function (e) {
            e.preventDefault();
            var $this = $(e.target);
            $this.attr('ui-toggle') || ($this = $this.closest('[ui-toggle]'));
            var $target = $($this.attr('target')) || $this;
            $target.toggleClass($this.attr('ui-toggle'));

            var $folderIcon = $this.children('i');
            if ($folderIcon.hasClass('fa-dedent')) {
                $folderIcon.removeClass('fa-dedent').addClass('fa-indent');
            } else {
                $folderIcon.removeClass('fa-indent').addClass('fa-dedent');
            }
        });

    });
}(jQuery);
//# sourceMappingURL=all.js.map