$(document).ready(function(){

  $('.menu-btn').click(function(){
      $('.prim-nav').find('li').toggle();
      $(this).show();
  });


	var parser = new(less.Parser);
    $.get("/assets/less/variables-colors.less", function( data ) {
        parser.parse(data, function (e, tree) {
            var currentGroup = 0;
            var rules = tree.rules;
            for (i in rules) {
            	
            	if(rules[i].type == 'Comment'){
            		currentGroup = i;
            		$(".sg-colors").append('<div class="sg-section-wrap"><h4 class="sg-h4">'+rules[i].value.replace('/*', '').replace('*/', '')+'</h4><ul id="variable-colors-'+currentGroup+'"></ul></div>');
            	}
            	if(rules[i].type == 'Rule'){
                	var name = rules[i].name;
                	var value = rules[i].value;
                	$("#variable-colors-"+currentGroup).append('<li class="sg-swatch sg-color--a"><span class="sg-swatch-preview" style="background:'+value.toCSS()+'"></span><ul class="sg-swatch-details"><li>'+value.toCSS()+'</li><li>'+name+'</li></ul></li>');
           		}
            }
        });
    });

    $.get("/assets/less/variables-fonts.less", function( data ) {
        parser.parse(data, function (e, tree) {
            var currentGroup = 0;
            var rules = tree.rules;
            var fonts = false;
            var styles = false;
            var styleArray = [];

            for (i in rules) {

            	if(fonts == true){
           			if(rules[i].type == 'Comment'){
                  var fontname = rules[i].value.replace('/*', '').replace('*/', '');
           				$('.sg-font-stacks .sg-section-wrap').append('<h4 class="sg-h4">'+fontname.split(":")[0]+'</h4>');
                  styleArray = fontname.split(":")[1].split(',')
           			}else{
           				var name = rules[i].name;
                  if(rules[i].value.toCSS() == ''){
                    value = '';
                  }else{
                    value = 'style="font-family:'+rules[i].value.toCSS()+'"';
                  }
           				for ( iii in styleArray){
           					$('.sg-font-stacks .sg-section-wrap').append('<p class="sg-font '+styleArray[iii].replace('.', '')+'" '+value+'>'+name.replace('@', '')+' '+styleArray[iii].replace('.', '')+'<span class="pull-right">Grumpy wizards make toxic brew for the evil Queen and Jack</span></p>');
           				}
           			}
           		}

           		if(styles == true && fonts != true){
           			 for (ii in rules[i].selectors) {
           				styleArray.push(rules[i].selectors[ii].elements[0].value.replace('.', ''));
           			}
           		}

            	if(rules[i].type == 'Comment'){
	           		if(rules[i].value.search("Fonts") > 0 )
					{
						fonts = true;
	           		}

	           		if(rules[i].value.search("Styles") > 0 )
					{
						styles = true;
	           		}

	           	}
            }

            $('.sg-font-stacks .sg-section-wrap').append('<br /><div class="sg-markup-controls"><a class="sg-btn--top" href="#top">Back to Top</a></div>');
        });
    });

});

var parser = new(less.Parser);
  $.get("/assets/less/font.less", function( data ) {
      parser.parse(data, function (e, tree) {
          var currentGroup = 0;
          var rules = tree.rules;
          var iconList = false;

          for (i in rules) {
            
            if(iconList == true){
              if(rules[i].type == 'Comment'){
                  $('#sg-icons').append('<div class="sg-btn-group"><h4 class="sg-h4">'+rules[i].value.replace('/*', '').replace('*/', '')+'</h4><ul class="sg-icons"></ul></div>');
              }else{
                for (ii in rules[i].selectors) {
                  $('#sg-icons .sg-btn-group:last-child .sg-icons').append('<li><i class="'+rules[i].selectors[ii].elements[0].value.replace('.', '')+'"></i></li>');
                }
              }
            }

            if(rules[i].type == 'Comment' && rules[i].value.search("Icon list") > 0 ){
               iconList = true;
            }

          }
      });
  });
