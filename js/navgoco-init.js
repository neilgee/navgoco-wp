
jQuery(document).ready(function($) {
        $(navgocoVars.ng_navgo.ng_menu_selection).navgoco({//swap in your Menu CSS ID
        	//caretHtml: '<span class="vert-menu"></span>',
        	accordion: navgocoVars.ng_navgo.ng_menu_accordion,
        	openClass: 'vert-open',
        	caretHtml: navgocoVars.ng_navgo.ng_menu_html_carat,
        }); 
    });