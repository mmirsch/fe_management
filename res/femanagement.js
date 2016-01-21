	function popup(url,title,left,top,width,height) {
		var docHeight = $(window).height();
		var docWidth = $(window).width();
		if (left == null){
		   left = Math.floor(docWidth*0.05);
		}
		if (top == null){
		  top = Math.floor(docHeight*0.05);
		}
		if (width == null){
		  width = Math.floor(docWidth*0.9);
		}
		if (height == null){
		  height = Math.floor(docHeight*0.9);
		}
		if (url.indexOf("www.hs-esslingen.de")<0) {
          var protocol = document.location.protocol;
			url = protocol + "//www.hs-esslingen.de/" + url;
		}
		var fenster = window.open(url, title, "left=" + left + ",top=" + top + ",width=" + width + ",height=" + height + ",scrollbars=yes,resizable=yes");
		fenster.focus();
		return false;
	}


	$( document ).ready(function() {
		$('.tx-femanagement-pi1').delegate('.popup_window','click', function() {	
			var title = $(this).attr('title');
			var linkUrl = $(this).attr('data-linkurl');
			var x = $(this).attr('data-window-x');
			var y = $(this).attr('data-window-y');
			var w = $(this).attr('data-window-w');
			var h = $(this).attr('data-window-h');

			if (x === undefined) { x = 50; }
			if (y === undefined) { y = 50; }
			if (w === undefined) { w = 600; }
			if (h === undefined) { h = 600; }
//			popup('"' + linkUrl + '"' ,'"' + title + '"',50,50,600,600);
			popup(linkUrl,title,x,y,w,h);
			return false;
		}); 
	});

    function loeschabfrage(left, top, uid, eidUrl, text) {
	if (typeof text === 'undefined') {
		text = 'Soll dieses Element wirklich gelöscht werden?';
	}
	msgBox(left, top,
				 text,
				 {'verify': true,'title': "Element löschen?"}, 
				 function(antwort){
						if(antwort) {
							$.ajax({
								url: eidUrl,
								data: {
									uid: uid
								},
								async: false,
								success: function(result, request) {
									window.location.reload();
								}
							});
						}
					}
	);
	return false;
}

function executeAjax(url,reload){
	var result=""
	$.ajax({
		url: url,
		async: false,
		beforeSend : function(){
			processingAnimation("start","bitte warten");
		},
		success: function(data, request) {
			if (reload) {
				window.location.reload();			
			}
			processingAnimation("stop");
			result = data; 
		}
	});
	return result;
}

function processingAnimation(mode,message) {
  var aHeight = $(window).height();
  var aWidth = $(window).width(); 

  if (mode=="start") {
    if ($('#spinOverlay').size()==0) {
			$('body').append('<div id="spinOverlay"></div>');
		  $('#spinOverlay').css('height', aHeight).css('width', aWidth);	
			if (message) {
				$('#spinOverlay').append('<div id="spinOverlayMessage">' + message + '</div>');
				var left = Math.ceil((aWidth - $('#spinOverlayMessage').width()) / 2);
				var top = Math.ceil((aHeight - $('#spinOverlayMessage').height()) / 2)+30;
			  $('#spinOverlayMessage').css('left', left).css('top', top);	
			}
    }
    $('#spinOverlay').show();
  } else if (mode=="stop") {
    if ($('#spinOverlay')) {
    	$('#spinOverlay').remove();
    }
    if ($('#spinOverlayMessage')) {
    	$('#spinOverlayMessage').remove();
    }
  }
}

function msgBox(left, top, string, args, callback){
  var default_args = {
      'title': false,
      'confirm': false,
      'verify': false,
      'input': false,
      'width': false,
      'animate': false,
      'textOk': 'Ok',
      'textCancel': 'Abbrechen',
      'textYes': 'Ja',
      'textNo': 'Nein'
  }

	var argBoxBreite;
	var argBoxHoehe;
  var aHeight = $(window).height();
  var aWidth = $(window).width();
  top = top - $(window).scrollTop();
  left = left - $(window).scrollLeft();
  if (args) {
    for (var index in default_args) {
      if (typeof args[index] == "undefined") {
        args[index] = default_args[index];				
			}
    }
		if (args['width']) {
	  	argBoxBreite = args['width'];
		}
		if (args['height']) {
	  	argBoxHoehe = args['height'];
		}
  }
  $('body').append('<div class="msgBoxOverlay" id="aOverlay"></div>');
  $('.msgBoxOverlay').css('height', aHeight).css('width', aWidth).fadeIn(100);
  $('body').append('<div class="msgBoxOuter"></div>');
  $('.msgBoxOuter').append('<div class="msgboxTitle"></div>');
  $('.msgBoxOuter').append('<div class="msgBoxInner"></div>');
  $('.msgBoxInner').append(string);
  if (args) {
    if (args['title']) {
    	$('.msgboxTitle').append('<h3>' + args['title'] + '</h3>');
    }
    if (args['input']) {
      if (typeof(args['input']) == 'string') {
      	$('.msgBoxInner').append('<div class="aInput"><input type="text" class="aTextbox" t="aTextbox" value="' + args['input'] + '" /></div>');
      } else {
        $('.msgBoxInner').append('<div class="aInput"><input type="text" class="aTextbox" t="aTextbox" /></div>');
      }
      $('.aTextbox').focus();
    }
    if (args['check']) {
      $('.msgBoxInner').append('<div class="aCheckbox"><input type="checkbox" class="aCheck" id="aCheck" /><label for="aCheck">' + args['check'] + '</label></div>');
    }
  }
  $('.msgBoxInner').append('<div class="aButtons"></div>');
  if (args) {
    if (args['confirm'] || args['input']) {
      $('.aButtons').append('<button value="ok">' + args['textOk'] + '</button>');
      $('.aButtons').append('<button value="cancel">' + args['textCancel'] + '</button>');
    } else if (args['verify']) {
      $('.aButtons').append('<button value="ok">' + args['textYes'] + '</button>');
      $('.aButtons').append('<button value="cancel">' + args['textNo'] + '</button>');
    } else {
      $('.aButtons').append('<button value="ok">' + args['textOk'] + '</button>');
    }
  } else {
    $('.aButtons').append('<button value="ok">Ok</button>');
  }
  var randX = 20;
  var randY = 20;
  var boxBreite = $('.msgBoxOuter').width();
  var boxHoehe = $('.msgBoxOuter').height();

	if (argBoxBreite>0) {
		boxBreite = argBoxBreite;
	  $('.msgBoxOuter').css("width", argBoxBreite + "px");
	}
	if (argBoxHoehe>0) {
		boxHoehe = argBoxHoehe;
	  $('.msgBoxOuter').css("height", argBoxHoehe + "px");
	}

  var x = left - boxBreite / 2;
  var y = top - boxHoehe / 2;
  if (x + randX + boxBreite > aWidth) {
   x = aWidth - boxBreite - randX;
  } else if (x < randX) {
    x = randX;
  }
  if (y + randY + boxHoehe > aHeight) {
		y = aHeight - boxHoehe - randY;
  } else  if (y < randY) {
  	y = randY;
	}
  $('.msgBoxOuter').css("left", x + "px");
  $('.msgBoxOuter').css('top', y + 'px').fadeIn(200);

  $(document).keydown(function(e){
    if ($('.msgBoxOverlay').is(':visible')) {
      if (e.keyCode == 13) {
        $('.aButtons > button[value="ok"]').click();
      }
      if (e.keyCode == 27) {
        $('.aButtons > button[value="cancel"]').click();
      }
    }
  });
  var aText = $('.aTextbox').val();
  if (!aText) {
    aText = false;
  }
  $('.aTextbox').keyup(function(){
    aText = $(this).val();
  });
	var test123 = $('#aCheck');
  var aCheck = $('#aCheck').attr('checked')=="checked";
  $('#aCheck').click(function(){
    aCheck = $('#aCheck').attr('checked')=="checked";
  });
  $('.aButtons > button').click(function(){
    $('.msgBoxOverlay').remove();
    $('.msgBoxOuter').remove();
    if (callback) {
      var wButton = $(this).attr("value");
      if (wButton == 'ok') {
        if (args) {
          if (args['input']) {
         		if (args['check']) {
							callback(aText, aCheck);
						} else {
							callback(aText);
						} 
          } else {
            callback(true);
          }
        } else {
          callback(true);
        }
      } else if (wButton == 'cancel') {
      	callback(false);
			}
    }
  });
}


$(document).ready(function(){
	$(".tx-femanagement-pi1 span.help").click(function (e) {
		var text = $(this).attr("data-tooltip");
		msgBox(e.pageX,e.pageY,text);
	});
});
