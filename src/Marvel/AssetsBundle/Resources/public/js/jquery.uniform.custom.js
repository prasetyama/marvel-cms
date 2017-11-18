/*

Uniform v1.7.5
Copyright Â© 2009 Josh Pyles / Pixelmatrix Design LLC
http://pixelmatrixdesign.com

Requires jQuery 1.4 or newer

Much thanks to Thomas Reynolds and Buck Wilson for their help and advice on this

Disabling text selection is made possible by Mathias Bynens <http://mathiasbynens.be/>
and his noSelect plugin. <http://github.com/mathiasbynens/noSelect-jQuery-Plugin>

Also, thanks to David Kaneda and Eugene Bond for their contributions to the plugin

License:
MIT License - http://www.opensource.org/licenses/mit-license.php

Enjoy!

*/

(function($) {
  $.uniform = {
    options: {
      selectClass:   'selector',
      radioClass: 'radio',
      checkboxClass: 'checker',
      checkedClass: 'checked',
      focusClass: 'focus',
      disabledClass: 'disabled',
      buttonClass: 'button',
      activeClass: 'active',
      hoverClass: 'hover',
      useID: true,
      idPrefix: 'uniform',
      resetSelector: false,
      autoHide: true
    },
    elements: []
  };

  if($.browser.msie && $.browser.version < 7){
    $.support.selectOpacity = false;
  }else{
    $.support.selectOpacity = true;
  }

  $.fn.uniform = function(options) {

    options = $.extend($.uniform.options, options);

    var el = this;
    //code for specifying a reset button
    if(options.resetSelector != false){
      $(options.resetSelector).mouseup(function(){
        function resetThis(){
          $.uniform.update(el);
        }
        setTimeout(resetThis, 10);
      });
    }

    function doSelect(elem){
      var $el = $(elem);
      
      var divTag = $('<div />'),
          spanTag = $('<span />');
      
      if(!$el.css("display") == "none" && options.autoHide){
        divTag.hide();
      }

      divTag.addClass(options.selectClass);

      if(options.useID && elem.attr("id") != ""){
        divTag.attr("id", options.idPrefix+"-"+elem.attr("id"));
      }
      
      var selected = elem.find(":selected:first");
      if(selected.length == 0){
        selected = elem.find("option:first");
      }
      spanTag.html(selected.html());
      
      elem.css('opacity', 0);
      elem.wrap(divTag);
      elem.before(spanTag);

      //redefine variables
      divTag = elem.parent("div");
      spanTag = elem.siblings("span");

      elem.bind({
        "change.uniform": function() {
          spanTag.text(elem.find(":selected").html());
          divTag.removeClass(options.activeClass);
        },
        "focus.uniform": function() {
          divTag.addClass(options.focusClass);
        },
        "blur.uniform": function() {
          divTag.removeClass(options.focusClass);
          divTag.removeClass(options.activeClass);
        },
        "mousedown.uniform touchbegin.uniform": function() {
          divTag.addClass(options.activeClass);
        },
        "mouseup.uniform touchend.uniform": function() {
          divTag.removeClass(options.activeClass);
        },
        "click.uniform touchend.uniform": function(){
          divTag.removeClass(options.activeClass);
        },
        "mouseenter.uniform": function() {
          divTag.addClass(options.hoverClass);
        },
        "mouseleave.uniform": function() {
          divTag.removeClass(options.hoverClass);
          divTag.removeClass(options.activeClass);
        },
        "keyup.uniform": function(){
          spanTag.text(elem.find(":selected").html());
        }
      });
      
      //handle disabled state
      if($(elem).attr("disabled")){
        //box is checked by default, check our box
        divTag.addClass(options.disabledClass);
      }
      $.uniform.noSelect(spanTag);
      
      storeElement(elem);

    }
    
    $.uniform.restore = function(elem){
      if(elem == undefined){
        elem = $($.uniform.elements);
      }
      
      $(elem).each(function(){
        if($(this).is("select")){
          //remove sibling span
          $(this).siblings("span").remove();
          //unwrap parent div
          $(this).unwrap();
        }
        
        //unbind events
        $(this).unbind(".uniform");
        
        //reset inline style
        $(this).css("opacity", "1");
        
        //remove item from list of uniformed elements
        var index = $.inArray($(elem), $.uniform.elements);
        $.uniform.elements.splice(index, 1);
      });
    };

    function storeElement(elem){
      //store this element in our global array
      elem = $(elem).get();
      if(elem.length > 1){
        $.each(elem, function(i, val){
          $.uniform.elements.push(val);
        });
      }else{
        $.uniform.elements.push(elem);
      }
    }
    
    //noSelect v1.0
    $.uniform.noSelect = function(elem) {
      function f() {
       return false;
      };
      $(elem).each(function() {
       this.onselectstart = this.ondragstart = f; // Webkit & IE
       $(this)
        .mousedown(f) // Webkit & Opera
        .css({ MozUserSelect: 'none' }); // Firefox
      });
     };

    $.uniform.update = function(elem){
      if(elem == undefined){
        elem = $($.uniform.elements);
      }
      //sanitize input
      elem = $(elem);

      elem.each(function(){
        //do to each item in the selector
        //function to reset all classes
        var $e = $(this);

        if($e.is("select")){
          //element is a select
          var spanTag = $e.siblings("span");
          var divTag = $e.parent("div");

          divTag.removeClass(options.hoverClass+" "+options.focusClass+" "+options.activeClass);

          //reset current selected text
          spanTag.html($e.find(":selected").html());

          if($e.is(":disabled")){
            divTag.addClass(options.disabledClass);
          }else{
            divTag.removeClass(options.disabledClass);
          }

        }
        
      });
    };

    return this.each(function() {
      if($.support.selectOpacity){
        var elem = $(this);

        if(elem.is("select")){
          //element is a select
          if(elem.attr("multiple") != true){
            //element is not a multi-select
            if(elem.attr("size") == undefined || elem.attr("size") <= 1){
              doSelect(elem);
            }
          }
        }
          
      }
    });
  };
})(jQuery);