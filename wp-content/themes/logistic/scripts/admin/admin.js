jQuery(document).ready(function($) {	
	var icons = [], myIconPicker = jQuery('input.select_an_icon_field,input[name="ozy_logistic_meta_post[ozy_logistic_meta_post_thumbnail_color_group][0][ozy_logistic_meta_post_thumbnail_icon]"]').fontIconPicker({iconsPerPage:2000});
	
	$('div[id*="ozy_logistic_meta_post"]').css('overflow', 'inherit');
	
	jQuery.ajax({
		url:         ozyAdminParams.ozy_theme_path + 'font/ozy/config.json',
		dataType:    'JSON'
	}).done(function (r) {
		jQuery.each(r.glyphs, function (i,v) {
			icons.push(r.css_prefix_text + v.css);
		});
		myIconPicker.setIcons(icons);
	});	
	

	var ozy_current_target_icon_box = null;
	$(document).on('click', '.edit-menu-item-classes', function() {		
		ozy_current_target_icon_box = $(this);
		tb_show('Menu Options', '#TB_inline?height=815&max-height=815&width=750&inlineId=ozyIconSelectorWindow', false);
		$('#TB_ajaxContent').css('height', '90%');
	});

	$(document).on('click', '#ozy-form-iconselect-icons i', function() {
		if(ozy_current_target_icon_box != null) {
			ozy_current_target_icon_box.val($(this).attr('class').replace('icon ', ''));
			ozy_current_target_icon_box = null;
			tb_remove();
		}
	});


	/****************************/

	function fixHelpIFrame() {
		if(jQuery("#ozy-help-iframe").length > 0) {
			var helpFrame = jQuery("#ozy-help-iframe");
			var innerDoc = (helpFrame.get(0).contentDocument) 
			? helpFrame.get(0).contentDocument 
			: helpFrame.get(0).contentWindow.document;
			helpFrame.height(innerDoc.body.scrollHeight + 35);
		}
	}

	jQuery(function(){
		fixHelpIFrame();
	});
	
	jQuery(window).resize(function(){
		fixHelpIFrame();
	});
	
	/**
	* Custom Menu Styling
	*/
	var ozy_current_target_menu_style_box = null;
	$(document).on('click', '.edit-menu-item-edit-style', function() {		
		ozy_current_target_menu_style_box = $(this);
		
		//load settings
		var get_params = jQuery.parseJSON( ozy_current_target_menu_style_box.siblings('textarea').val() );
		
		//set loaded values
		if (get_params !== undefined && get_params !== null) {
			if(get_params.is_form !== undefined) { $('#custom-menu-request-a-rate').prop('checked', (get_params.is_form === '1' ? true : false)); }
			if(get_params.html_content !== undefined) { $('#custom-menu-html-content').val(Base64.decode(get_params.html_content)); }
			if(get_params.bg_color !== undefined) { $('#custom-menu-bg-color').val(get_params.bg_color).minicolors('destroy').minicolors({defaultValue:get_params.bg_color}); }
			if(get_params.fn_color !== undefined) { $('#custom-menu-fn-color').val(get_params.fn_color).minicolors('destroy').minicolors({defaultValue:get_params.fn_color}); }
			if(get_params.border_color !== undefined) { $('#custom-menu-border-color').val(get_params.border_color).minicolors('destroy').minicolors({defaultValue:get_params.border_color}); }
			if(get_params.border_width !== undefined) { $('#custom-menu-border-width').val(get_params.border_width); }
			if(get_params.character_issues !== undefined) { $('#custom-menu-character-issues').prop('checked', (get_params.character_issues === '1' ? true : false)); }
		}else{
			$('#custom-menu-request-a-rate').prop('checked', false);
			$('#custom-menu-html-content').val('');
			$('#custom-menu-bg-color,#custom-menu-fn-color,#custom-menu-border-color').val('').minicolors('destroy').minicolors();			
			$('#custom-menu-border-width').val('0');
			$('#custom-menu-character-issues').prop('checked', false);
			
		}
		
		tb_show('Custom Menu Style', '#TB_inline?height=315&max-height=315&width=750&inlineId=ozyMegaMenuStyleWindow', false);
		$('#TB_ajaxContent').css('height', '90%');
	});
	
	/*media window*/
    var custom_uploader;
 
    $(document).on('click', '.upload-image-button', function(e) {
		$this = $(this);
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
			$this.parent().find('input[type="text"]').val( attachment.url ).change();
			$this.parent().find('a>img').attr('src', attachment.url );
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });	
	/*end media window*/
	
	$(document).on('click', '#custom-menu-bg-apply', function(){
		if(ozy_current_target_menu_style_box != null) {
			var pass_params =  '{';
			pass_params += '"is_form":"'+ ($('#custom-menu-request-a-rate').is(':checked') ? '1' : '0') +'",';
			pass_params += '"html_content":"'+ Base64.encode($('#custom-menu-html-content').val()) +'",';
			pass_params += '"bg_color":"'+ $('#custom-menu-bg-color').val() +'",';
			pass_params += '"fn_color":"'+ $('#custom-menu-fn-color').val() +'",';
			pass_params += '"border_color":"'+ $('#custom-menu-border-color').val() +'",';
			pass_params += '"character_issues":"'+ ($('#custom-menu-character-issues').is(':checked') ? '1' : '0') +'",';
			pass_params += '"border_width":"'+ $('#custom-menu-border-width').val() +'"';
			
			pass_params += '}';console.log(pass_params);
			ozy_current_target_menu_style_box.siblings('textarea').val( pass_params );
			ozy_current_target_menu_style_box = null;
			tb_remove();
		}
	});
});

/* https://github.com/carlo/jquery-base64 */

"use strict";var Base64 = {
// private property
_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

// public method for encoding
encode : function (input) {
    var output = "";
    var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
    var i = 0;

    input = Base64._utf8_encode(input);

    while (i < input.length) {

        chr1 = input.charCodeAt(i++);
        chr2 = input.charCodeAt(i++);
        chr3 = input.charCodeAt(i++);

        enc1 = chr1 >> 2;
        enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
        enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
        enc4 = chr3 & 63;

        if (isNaN(chr2)) {
            enc3 = enc4 = 64;
        } else if (isNaN(chr3)) {
            enc4 = 64;
        }

        output = output +
        Base64._keyStr.charAt(enc1) + Base64._keyStr.charAt(enc2) +
        Base64._keyStr.charAt(enc3) + Base64._keyStr.charAt(enc4);

    }

    return output;
},

// public method for decoding
decode : function (input) {
    var output = "";
    var chr1, chr2, chr3;
    var enc1, enc2, enc3, enc4;
    var i = 0;

    input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

    while (i < input.length) {

        enc1 = Base64._keyStr.indexOf(input.charAt(i++));
        enc2 = Base64._keyStr.indexOf(input.charAt(i++));
        enc3 = Base64._keyStr.indexOf(input.charAt(i++));
        enc4 = Base64._keyStr.indexOf(input.charAt(i++));

        chr1 = (enc1 << 2) | (enc2 >> 4);
        chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
        chr3 = ((enc3 & 3) << 6) | enc4;

        output = output + String.fromCharCode(chr1);

        if (enc3 != 64) {
            output = output + String.fromCharCode(chr2);
        }
        if (enc4 != 64) {
            output = output + String.fromCharCode(chr3);
        }

    }

    output = Base64._utf8_decode(output);

    return output;

},

// private method for UTF-8 encoding
_utf8_encode : function (string) {
    string = string.replace(/\r\n/g,"\n");
    var utftext = "";

    for (var n = 0; n < string.length; n++) {

        var c = string.charCodeAt(n);

        if (c < 128) {
            utftext += String.fromCharCode(c);
        }
        else if((c > 127) && (c < 2048)) {
            utftext += String.fromCharCode((c >> 6) | 192);
            utftext += String.fromCharCode((c & 63) | 128);
        }
        else {
            utftext += String.fromCharCode((c >> 12) | 224);
            utftext += String.fromCharCode(((c >> 6) & 63) | 128);
            utftext += String.fromCharCode((c & 63) | 128);
        }

    }

    return utftext;
},

// private method for UTF-8 decoding
_utf8_decode : function (utftext) {
    var string = "";
    var i = 0;
    var c = c1 = c2 = 0;

    while ( i < utftext.length ) {

        c = utftext.charCodeAt(i);

        if (c < 128) {
            string += String.fromCharCode(c);
            i++;
        }
        else if((c > 191) && (c < 224)) {
            c2 = utftext.charCodeAt(i+1);
            string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
            i += 2;
        }
        else {
            c2 = utftext.charCodeAt(i+1);
            c3 = utftext.charCodeAt(i+2);
            string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
            i += 3;
        }

    }
    return string;
}
}

/* jQuery fontIconPicker - v1.0.0 - Made by Alessandro Benoit  - http://codeb.it/fontIconPicker - Under MIT License */
;(function(a){function d(b,c){this.element=a(b);this.settings=a.extend({},e,c);this.settings.emptyIcon&&this.settings.iconsPerPage--;this.iconPicker=a("<div/>",{"class":"icons-selector",style:"position: relative",html:'<div class="selector"><span class="selected-icon"><i class="fip-icon-block"></i></span><span class="selector-button"><i class="fip-icon-down-dir"></i></span></div><div class="selector-popup" style="display: none;">'+(this.settings.hasSearch?'<div class="selector-search"><input type="text" name="" value="" placeholder="Search icon" class="icons-search-input"/><i class="fip-icon-search"></i></div>': "")+'<div class="fip-icons-container"></div><div class="selector-footer" style="display:none;"><span class="selector-pages">1/2</span><span class="selector-arrows"><span class="selector-arrow-left" style="display:none;"><i class="fip-icon-left-dir"></i></span><span class="selector-arrow-right"><i class="fip-icon-right-dir"></i></span></span></div></div>'});this.iconContainer=this.iconPicker.find(".fip-icons-container");this.searchIcon=this.iconPicker.find(".selector-search i");this.iconsSearched= [];this.isSearch=!1;this.currentPage=this.totalPage=1;this.currentIcon=!1;this.iconsCount=0;this.open=!1;this.init()}var e={source:!1,emptyIcon:!0,iconsPerPage:20,hasSearch:!0};d.prototype={init:function(){this.element.hide();this.element.before(this.iconPicker);!this.settings.source&&this.element.is("select")&&(this.settings.source=[],this.element.find("option").each(a.proxy(function(b,c){a(c).val()&&this.settings.source.push(a(c).val())},this)));this.loadIcons();this.iconPicker.find(".selector-button").click(a.proxy(function(){this.toggleIconSelector()}, this));this.iconPicker.find(".selector-arrow-right").click(a.proxy(function(b){this.currentPage<this.totalPage&&(this.iconPicker.find(".selector-arrow-left").show(),this.currentPage+=1,this.renderIconContainer());this.currentPage===this.totalPage&&a(b.currentTarget).hide()},this));this.iconPicker.find(".selector-arrow-left").click(a.proxy(function(b){1<this.currentPage&&(this.iconPicker.find(".selector-arrow-right").show(),this.currentPage-=1,this.renderIconContainer());1===this.currentPage&&a(b.currentTarget).hide()}, this));this.iconPicker.find(".icons-search-input").keyup(a.proxy(function(b){var c=a(b.currentTarget).val();""===c?this.resetSearch():(this.searchIcon.removeClass("fip-icon-search"),this.searchIcon.addClass("fip-icon-cancel"),this.isSearch=!0,this.currentPage=1,this.iconsSearched=a.grep(this.settings.source,function(a){if(0<=a.search(c.toLowerCase()))return a}),this.renderIconContainer())},this));this.iconPicker.find(".selector-search").on("click",".fip-icon-cancel",a.proxy(function(){this.iconPicker.find(".icons-search-input").focus(); this.resetSearch()},this));this.iconContainer.on("click",".fip-box",a.proxy(function(b){this.setSelectedIcon(a(b.currentTarget).find("i").attr("class"));this.toggleIconSelector()},this));this.iconPicker.click(function(a){a.stopPropagation();return!1});a("html").click(a.proxy(function(){this.open&&this.toggleIconSelector()},this))},loadIcons:function(){this.iconContainer.html('<i class="fip-icon-spin3 animate-spin loading"></i>');this.settings.source instanceof Array&&this.renderIconContainer()},renderIconContainer:function(){var b, c=[],c=this.isSearch?this.iconsSearched:this.settings.source;this.iconsCount=c.length;this.totalPage=Math.ceil(this.iconsCount/this.settings.iconsPerPage);1<this.totalPage?this.iconPicker.find(".selector-footer").show():this.iconPicker.find(".selector-footer").hide();this.iconPicker.find(".selector-pages").text(this.currentPage+"/"+this.totalPage);b=(this.currentPage-1)*this.settings.iconsPerPage;if(this.settings.emptyIcon)this.iconContainer.html('<span class="fip-box"><i class="fip-icon-block"></i></span>'); else{if(1>c.length){this.iconContainer.html('<span class="icons-picker-error"><i class="fip-icon-block"></i></span>');return}this.iconContainer.html("")}c=c.slice(b,b+this.settings.iconsPerPage);b=0;for(var d;d=c[b++];)a("<span/>",{html:'<i class="'+d+'"></i>',"class":"fip-box"}).appendTo(this.iconContainer);this.settings.emptyIcon||this.element.val()&&-1!==a.inArray(this.element.val(),this.settings.source)?-1===a.inArray(this.element.val(),this.settings.source)?this.setSelectedIcon():this.setSelectedIcon(this.element.val()): this.setSelectedIcon(c[0])},setHighlightedIcon:function(){this.iconContainer.find(".current-icon").removeClass("current-icon");this.currentIcon&&this.iconContainer.find("."+this.currentIcon).parent("span").addClass("current-icon")},setSelectedIcon:function(a){"fip-icon-block"===a&&(a="");this.iconPicker.find(".selected-icon").html('<i class="'+(a||"fip-icon-block")+'"></i>');this.element.val(a).triggerHandler("change");this.currentIcon=a;this.setHighlightedIcon()},toggleIconSelector:function(){this.open= this.open?0:1;this.iconPicker.find(".selector-popup").slideToggle(300);this.iconPicker.find(".selector-button i").toggleClass("fip-icon-down-dir");this.iconPicker.find(".selector-button i").toggleClass("fip-icon-up-dir");this.open&&this.iconPicker.find(".icons-search-input").focus().select()},resetSearch:function(){this.iconPicker.find(".icons-search-input").val("");this.searchIcon.removeClass("fip-icon-cancel");this.searchIcon.addClass("fip-icon-search");this.iconPicker.find(".selector-arrow-left").hide(); this.currentPage=1;this.isSearch=!1;this.renderIconContainer();1<this.totalPage&&this.iconPicker.find(".selector-arrow-right").show()}};a.fn.fontIconPicker=function(b){this.each(function(){a.data(this,"fontIconPicker")||a.data(this,"fontIconPicker",new d(this,b))});this.setIcons=a.proxy(function(b){this.each(function(){a.data(this,"fontIconPicker").settings.source=b;a.data(this,"fontIconPicker").resetSearch();a.data(this,"fontIconPicker").loadIcons()})},this);return this}})(jQuery);