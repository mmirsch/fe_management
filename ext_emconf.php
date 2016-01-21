<?php

########################################################################
# Extension Manager/Repository config file for ext "fe_management".
#
# Auto generated 19-06-2012 15:48
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'fe_management',
	'description' => 'You can create, edit, list severall extensions in the Frontend.
supported extensions are tt_news, cal
It is also possible to define super users which can managed entries.',
	'category' => 'plugin',
	'author' => 'Hochschule Esslingen',
	'author_email' => 't3admin@hs-esslingen.de',
	'shy' => '',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => 'Hochschule Esslingen',
	'version' => '0.0.3',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'typo3' => '3.5.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:473:{s:9:"ChangeLog";s:4:"1f94";s:10:"README.txt";s:4:"baaa";s:16:"ext_autoload.php";s:4:"f0d4";s:12:"ext_icon.gif";s:4:"20ea";s:17:"ext_localconf.php";s:4:"1add";s:14:"ext_tables.php";s:4:"c1bc";s:14:"ext_tables.sql";s:4:"ed8c";s:17:"fe_management.png";s:4:"2aed";s:12:"flexform.xml";s:4:"1f2c";s:16:"locallang_db.xml";s:4:"5c96";s:47:"controller/class.tx_femanagement_controller.php";s:4:"e4b5";s:66:"controller/cal/class.tx_femanagement_controller_calendar_event.php";s:4:"802d";s:69:"controller/cal/class.tx_femanagement_controller_calendar_location.php";s:4:"562f";s:65:"controller/cal/class.tx_femanagement_controller_calendar_main.php";s:4:"c5d4";s:70:"controller/cal/class.tx_femanagement_controller_calendar_organizer.php";s:4:"9233";s:76:"controller/permissions/class.tx_femanagement_controller_permissions_apps.php";s:4:"2890";s:78:"controller/permissions/class.tx_femanagement_controller_permissions_groups.php";s:4:"3807";s:76:"controller/permissions/class.tx_femanagement_controller_permissions_main.php";s:4:"3062";s:77:"controller/permissions/class.tx_femanagement_controller_permissions_roles.php";s:4:"a590";s:64:"controller/qsm/class.tx_femanagement_controller_qsm_antraege.php";s:4:"8058";s:60:"controller/tt_news/class.tx_femanagement_controller_news.php";s:4:"4e95";s:19:"doc/wizard_form.dat";s:4:"6008";s:20:"doc/wizard_form.html";s:4:"88d5";s:33:"eid/class.tx_femanagement_eid.php";s:4:"8492";s:37:"model/class.tx_femanagement_model.php";s:4:"ba94";s:56:"model/cal/class.tx_femanagement_model_cal_categories.php";s:4:"0bf0";s:51:"model/cal/class.tx_femanagement_model_cal_event.php";s:4:"628a";s:54:"model/cal/class.tx_femanagement_model_cal_location.php";s:4:"4235";s:55:"model/cal/class.tx_femanagement_model_cal_organizer.php";s:4:"adb0";s:66:"model/permissions/class.tx_femanagement_model_permissions_apps.php";s:4:"6761";s:68:"model/permissions/class.tx_femanagement_model_permissions_groups.php";s:4:"8120";s:67:"model/permissions/class.tx_femanagement_model_permissions_roles.php";s:4:"d3b1";s:54:"model/qsm/class.tx_femanagement_model_qsm_antraege.php";s:4:"e9d5";s:53:"model/qsm/class.tx_femanagement_model_qsm_budgets.php";s:4:"8963";s:54:"model/qsm/class.tx_femanagement_model_qsm_fe_users.php";s:4:"c2a0";s:52:"model/qsm/class.tx_femanagement_model_qsm_mittel.php";s:4:"64b5";s:50:"model/tt_news/class.tx_femanagement_model_news.php";s:4:"d5b5";s:61:"model/tt_news/class.tx_femanagement_model_news_categories.php";s:4:"58b9";s:33:"pi1/class.tx_femanagement_pi1.php";s:4:"e68e";s:17:"pi1/locallang.xml";s:4:"4505";s:14:"pi1/manual.txt";s:4:"44d1";s:13:"res/error.png";s:4:"63f5";s:20:"res/femanagement.css";s:4:"cc7e";s:19:"res/femanagement.js";s:4:"56fa";s:23:"res/jquery-1.7.2.min.js";s:4:"b8d6";s:34:"res/jquery-ui-1.8.20.custom.min.js";s:4:"fed8";s:26:"res/jquery.multi-select.js";s:4:"42f9";s:25:"res/jquery.quicksearch.js";s:4:"2ed4";s:21:"res/jquery.ui.all.css";s:4:"46c6";s:22:"res/jquery.ui.base.css";s:4:"4561";s:22:"res/jquery.ui.core.css";s:4:"9637";s:28:"res/jquery.ui.datepicker.css";s:4:"0209";s:23:"res/jquery.ui.theme.css";s:4:"da9d";s:26:"res/jquery.validate.min.js";s:4:"c593";s:18:"res/messages_de.js";s:4:"7d4a";s:20:"res/multi-select.css";s:4:"b731";s:36:"res/akzhan-jwysiwyg/CHANGES.markdown";s:4:"d38d";s:35:"res/akzhan-jwysiwyg/GPL-LICENSE.txt";s:4:"2c17";s:35:"res/akzhan-jwysiwyg/MIT-LICENSE.txt";s:4:"16c5";s:30:"res/akzhan-jwysiwyg/README.rst";s:4:"2393";s:35:"res/akzhan-jwysiwyg/ajax-loader.gif";s:4:"7b97";s:30:"res/akzhan-jwysiwyg/index.html";s:4:"fcb6";s:41:"res/akzhan-jwysiwyg/jquery.wysiwyg.bg.png";s:4:"bd11";s:38:"res/akzhan-jwysiwyg/jquery.wysiwyg.css";s:4:"c421";s:38:"res/akzhan-jwysiwyg/jquery.wysiwyg.gif";s:4:"0c33";s:38:"res/akzhan-jwysiwyg/jquery.wysiwyg.jpg";s:4:"260f";s:37:"res/akzhan-jwysiwyg/jquery.wysiwyg.js";s:4:"18a5";s:44:"res/akzhan-jwysiwyg/jquery.wysiwyg.modal.css";s:4:"6096";s:47:"res/akzhan-jwysiwyg/jquery.wysiwyg.no-alpha.gif";s:4:"0c5b";s:49:"res/akzhan-jwysiwyg/jquery.wysiwyg.old-school.css";s:4:"b09f";s:51:"res/akzhan-jwysiwyg/controls/wysiwyg.colorpicker.js";s:4:"8288";s:47:"res/akzhan-jwysiwyg/controls/wysiwyg.cssWrap.js";s:4:"5e49";s:45:"res/akzhan-jwysiwyg/controls/wysiwyg.image.js";s:4:"ae67";s:44:"res/akzhan-jwysiwyg/controls/wysiwyg.link.js";s:4:"35c9";s:45:"res/akzhan-jwysiwyg/controls/wysiwyg.table.js";s:4:"6aea";s:41:"res/akzhan-jwysiwyg/help/bin/compile.bash";s:4:"7eba";s:41:"res/akzhan-jwysiwyg/help/bin/compile.conf";s:4:"f2d4";s:44:"res/akzhan-jwysiwyg/help/docs/code_style.rst";s:4:"3491";s:46:"res/akzhan-jwysiwyg/help/docs/contributing.rst";s:4:"1126";s:41:"res/akzhan-jwysiwyg/help/docs/get_dia.rst";s:4:"8ba3";s:43:"res/akzhan-jwysiwyg/help/docs/internals.rst";s:4:"4216";s:41:"res/akzhan-jwysiwyg/help/docs/plugins.rst";s:4:"8f1a";s:52:"res/akzhan-jwysiwyg/help/docs/scheme-refactoring.dia";s:4:"a083";s:52:"res/akzhan-jwysiwyg/help/docs/scheme-refactoring.png";s:4:"a5dd";s:40:"res/akzhan-jwysiwyg/help/docs/scheme.dia";s:4:"14d0";s:40:"res/akzhan-jwysiwyg/help/docs/scheme.png";s:4:"3ba8";s:47:"res/akzhan-jwysiwyg/help/examples/01-basic.html";s:4:"5fa2";s:46:"res/akzhan-jwysiwyg/help/examples/02-full.html";s:4:"96eb";s:46:"res/akzhan-jwysiwyg/help/examples/03-ajax.html";s:4:"59d9";s:51:"res/akzhan-jwysiwyg/help/examples/04-resizable.html";s:4:"e351";s:52:"res/akzhan-jwysiwyg/help/examples/05-ui-dialogs.html";s:4:"0654";s:62:"res/akzhan-jwysiwyg/help/examples/06-hide-heading-buttons.html";s:4:"acec";s:52:"res/akzhan-jwysiwyg/help/examples/07-enable-rtl.html";s:4:"a47b";s:51:"res/akzhan-jwysiwyg/help/examples/08-auto-grow.html";s:4:"9351";s:57:"res/akzhan-jwysiwyg/help/examples/09-css-autoloading.html";s:4:"e1a7";s:57:"res/akzhan-jwysiwyg/help/examples/10-custom-controls.html";s:4:"484a";s:49:"res/akzhan-jwysiwyg/help/examples/11-plugins.html";s:4:"248d";s:57:"res/akzhan-jwysiwyg/help/examples/12-writing-plugins.html";s:4:"a38a";s:53:"res/akzhan-jwysiwyg/help/examples/13-fileManager.html";s:4:"35f7";s:57:"res/akzhan-jwysiwyg/help/examples/14-PHP-fileManager.html";s:4:"1811";s:44:"res/akzhan-jwysiwyg/help/examples/index.html";s:4:"bf6d";s:46:"res/akzhan-jwysiwyg/help/examples/jw_ajax.html";s:4:"594b";s:43:"res/akzhan-jwysiwyg/help/htmlentity/Gemfile";s:4:"b66e";s:42:"res/akzhan-jwysiwyg/help/htmlentity/db.csv";s:4:"bc1e";s:55:"res/akzhan-jwysiwyg/help/htmlentity/unicode4jwysiwyg.pl";s:4:"497d";s:55:"res/akzhan-jwysiwyg/help/htmlentity/unicode4jwysiwyg.rb";s:4:"84f6";s:38:"res/akzhan-jwysiwyg/help/lib/jquery.js";s:4:"a34f";s:51:"res/akzhan-jwysiwyg/help/lib/jquery.simplemodal.css";s:4:"e12d";s:50:"res/akzhan-jwysiwyg/help/lib/jquery.simplemodal.js";s:4:"6cee";s:34:"res/akzhan-jwysiwyg/help/lib/x.png";s:4:"e7a7";s:45:"res/akzhan-jwysiwyg/help/lib/blueprint/ie.css";s:4:"6091";s:48:"res/akzhan-jwysiwyg/help/lib/blueprint/print.css";s:4:"d1bd";s:49:"res/akzhan-jwysiwyg/help/lib/blueprint/screen.css";s:4:"ad22";s:48:"res/akzhan-jwysiwyg/help/lib/jasmine/MIT.LICENSE";s:4:"cde0";s:52:"res/akzhan-jwysiwyg/help/lib/jasmine/jasmine-html.js";s:4:"0cb3";s:48:"res/akzhan-jwysiwyg/help/lib/jasmine/jasmine.css";s:4:"c497";s:47:"res/akzhan-jwysiwyg/help/lib/jasmine/jasmine.js";s:4:"f054";s:64:"res/akzhan-jwysiwyg/help/lib/plugins/autoload/jquery.autoload.js";s:4:"2463";s:62:"res/akzhan-jwysiwyg/help/lib/plugins/farbtastic/CHANGELOG.html";s:4:"068e";s:59:"res/akzhan-jwysiwyg/help/lib/plugins/farbtastic/LICENSE.txt";s:4:"60ef";s:59:"res/akzhan-jwysiwyg/help/lib/plugins/farbtastic/README.html";s:4:"f667";s:61:"res/akzhan-jwysiwyg/help/lib/plugins/farbtastic/farbtastic.js";s:4:"c784";s:66:"res/akzhan-jwysiwyg/help/lib/plugins/farbtastic/css/farbtastic.css";s:4:"3494";s:63:"res/akzhan-jwysiwyg/help/lib/plugins/farbtastic/demo/demo1.html";s:4:"6dd7";s:63:"res/akzhan-jwysiwyg/help/lib/plugins/farbtastic/demo/demo2.html";s:4:"37f7";s:65:"res/akzhan-jwysiwyg/help/lib/plugins/farbtastic/images/marker.png";s:4:"4f93";s:63:"res/akzhan-jwysiwyg/help/lib/plugins/farbtastic/images/mask.png";s:4:"c6dc";s:64:"res/akzhan-jwysiwyg/help/lib/plugins/farbtastic/images/wheel.png";s:4:"2b6d";s:55:"res/akzhan-jwysiwyg/help/lib/ui/jquery.effects.blind.js";s:4:"70ee";s:56:"res/akzhan-jwysiwyg/help/lib/ui/jquery.effects.bounce.js";s:4:"0571";s:54:"res/akzhan-jwysiwyg/help/lib/ui/jquery.effects.clip.js";s:4:"ee65";s:54:"res/akzhan-jwysiwyg/help/lib/ui/jquery.effects.core.js";s:4:"34db";s:54:"res/akzhan-jwysiwyg/help/lib/ui/jquery.effects.drop.js";s:4:"9a36";s:57:"res/akzhan-jwysiwyg/help/lib/ui/jquery.effects.explode.js";s:4:"ab30";s:54:"res/akzhan-jwysiwyg/help/lib/ui/jquery.effects.fold.js";s:4:"2b6b";s:59:"res/akzhan-jwysiwyg/help/lib/ui/jquery.effects.highlight.js";s:4:"aabe";s:57:"res/akzhan-jwysiwyg/help/lib/ui/jquery.effects.pulsate.js";s:4:"76fd";s:55:"res/akzhan-jwysiwyg/help/lib/ui/jquery.effects.scale.js";s:4:"f301";s:55:"res/akzhan-jwysiwyg/help/lib/ui/jquery.effects.shake.js";s:4:"3f61";s:55:"res/akzhan-jwysiwyg/help/lib/ui/jquery.effects.slide.js";s:4:"8f31";s:58:"res/akzhan-jwysiwyg/help/lib/ui/jquery.effects.transfer.js";s:4:"28e8";s:55:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.accordion.css";s:4:"728d";s:54:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.accordion.js";s:4:"3661";s:49:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.all.css";s:4:"a48a";s:58:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.autocomplete.css";s:4:"c9e4";s:57:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.autocomplete.js";s:4:"68af";s:50:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.base.css";s:4:"0472";s:52:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.button.css";s:4:"a45f";s:51:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.button.js";s:4:"0c87";s:50:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.core.css";s:4:"6ef6";s:49:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.core.js";s:4:"5a75";s:56:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.datepicker.css";s:4:"5d1a";s:55:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.datepicker.js";s:4:"5b53";s:52:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.dialog.css";s:4:"1f60";s:51:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.dialog.js";s:4:"e5fe";s:54:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.draggable.js";s:4:"f054";s:54:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.droppable.js";s:4:"629a";s:50:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.mouse.js";s:4:"f1aa";s:53:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.position.js";s:4:"44ea";s:57:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.progressbar.css";s:4:"5676";s:56:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.progressbar.js";s:4:"fc27";s:55:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.resizable.css";s:4:"c3a0";s:54:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.resizable.js";s:4:"1fa5";s:55:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.selectable.js";s:4:"0e94";s:52:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.slider.css";s:4:"1d2e";s:51:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.slider.js";s:4:"feec";s:53:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.sortable.js";s:4:"b73e";s:50:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.tabs.css";s:4:"0d5a";s:49:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.tabs.js";s:4:"2ec9";s:51:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.theme.css";s:4:"c34a";s:51:"res/akzhan-jwysiwyg/help/lib/ui/jquery.ui.widget.js";s:4:"50a1";s:62:"res/akzhan-jwysiwyg/help/lib/ui/images/ui-anim_basic_16x16.gif";s:4:"03ce";s:69:"res/akzhan-jwysiwyg/help/lib/ui/images/ui-bg_flat_0_aaaaaa_40x100.png";s:4:"2a44";s:70:"res/akzhan-jwysiwyg/help/lib/ui/images/ui-bg_flat_75_ffffff_40x100.png";s:4:"8692";s:70:"res/akzhan-jwysiwyg/help/lib/ui/images/ui-bg_glass_55_fbf9ee_1x400.png";s:4:"f8f4";s:70:"res/akzhan-jwysiwyg/help/lib/ui/images/ui-bg_glass_65_ffffff_1x400.png";s:4:"e5a8";s:70:"res/akzhan-jwysiwyg/help/lib/ui/images/ui-bg_glass_75_dadada_1x400.png";s:4:"c12c";s:70:"res/akzhan-jwysiwyg/help/lib/ui/images/ui-bg_glass_75_e6e6e6_1x400.png";s:4:"f425";s:70:"res/akzhan-jwysiwyg/help/lib/ui/images/ui-bg_glass_95_fef1ec_1x400.png";s:4:"5a3b";s:79:"res/akzhan-jwysiwyg/help/lib/ui/images/ui-bg_highlight-soft_75_cccccc_1x100.png";s:4:"72c5";s:66:"res/akzhan-jwysiwyg/help/lib/ui/images/ui-icons_222222_256x240.png";s:4:"9129";s:66:"res/akzhan-jwysiwyg/help/lib/ui/images/ui-icons_2e83ff_256x240.png";s:4:"2516";s:66:"res/akzhan-jwysiwyg/help/lib/ui/images/ui-icons_454545_256x240.png";s:4:"7710";s:66:"res/akzhan-jwysiwyg/help/lib/ui/images/ui-icons_888888_256x240.png";s:4:"faf6";s:66:"res/akzhan-jwysiwyg/help/lib/ui/images/ui-icons_cd0a0a_256x240.png";s:4:"5d88";s:44:"res/akzhan-jwysiwyg/help/tests/issue005.html";s:4:"1f4c";s:47:"res/akzhan-jwysiwyg/help/tests/issue014-cg.html";s:4:"6a46";s:44:"res/akzhan-jwysiwyg/help/tests/issue016.html";s:4:"2750";s:44:"res/akzhan-jwysiwyg/help/tests/issue026.html";s:4:"c6f5";s:44:"res/akzhan-jwysiwyg/help/tests/issue029.html";s:4:"5478";s:44:"res/akzhan-jwysiwyg/help/tests/issue130.html";s:4:"3d67";s:44:"res/akzhan-jwysiwyg/help/tests/issue145.html";s:4:"4b0e";s:45:"res/akzhan-jwysiwyg/help/tests/issue152.xhtml";s:4:"360a";s:51:"res/akzhan-jwysiwyg/help/tests/issue152_iframe.html";s:4:"bd13";s:44:"res/akzhan-jwysiwyg/help/tests/issue154.html";s:4:"951a";s:47:"res/akzhan-jwysiwyg/help/tests/issue214-cg.html";s:4:"222d";s:53:"res/akzhan-jwysiwyg/help/tests/run-jasmine-tests.html";s:4:"f926";s:50:"res/akzhan-jwysiwyg/help/tests/css/issue014-cg.css";s:4:"3c80";s:47:"res/akzhan-jwysiwyg/help/tests/css/issue145.css";s:4:"f111";s:49:"res/akzhan-jwysiwyg/help/tests/images/quote02.gif";s:4:"a3fc";s:50:"res/akzhan-jwysiwyg/help/tests/jasmine/154.spec.js";s:4:"afe6";s:50:"res/akzhan-jwysiwyg/help/tests/jasmine/159.spec.js";s:4:"3073";s:48:"res/akzhan-jwysiwyg/help/tests/jasmine/helper.js";s:4:"d41d";s:55:"res/akzhan-jwysiwyg/help/tests/jasmine/jwysiwyg.spec.js";s:4:"6e8b";s:54:"res/akzhan-jwysiwyg/help/tests/jasmine/plugins.spec.js";s:4:"458e";s:60:"res/akzhan-jwysiwyg/help/tests/jasmine/controls/html.spec.js";s:4:"3979";s:67:"res/akzhan-jwysiwyg/help/tests/jasmine/functions/insertHtml.spec.js";s:4:"674c";s:59:"res/akzhan-jwysiwyg/help/tests/jasmine/plugins/i18n.spec.js";s:4:"980c";s:63:"res/akzhan-jwysiwyg/help/tests/jasmine/plugins/rmFormat.spec.js";s:4:"5f61";s:35:"res/akzhan-jwysiwyg/i18n/lang.cs.js";s:4:"898f";s:35:"res/akzhan-jwysiwyg/i18n/lang.de.js";s:4:"23b3";s:35:"res/akzhan-jwysiwyg/i18n/lang.en.js";s:4:"fc27";s:35:"res/akzhan-jwysiwyg/i18n/lang.es.js";s:4:"bbe6";s:35:"res/akzhan-jwysiwyg/i18n/lang.fr.js";s:4:"bd24";s:35:"res/akzhan-jwysiwyg/i18n/lang.he.js";s:4:"27af";s:35:"res/akzhan-jwysiwyg/i18n/lang.hr.js";s:4:"d4e6";s:35:"res/akzhan-jwysiwyg/i18n/lang.it.js";s:4:"622f";s:35:"res/akzhan-jwysiwyg/i18n/lang.ja.js";s:4:"45de";s:35:"res/akzhan-jwysiwyg/i18n/lang.nb.js";s:4:"199f";s:35:"res/akzhan-jwysiwyg/i18n/lang.nl.js";s:4:"2ce6";s:35:"res/akzhan-jwysiwyg/i18n/lang.pl.js";s:4:"c5d4";s:38:"res/akzhan-jwysiwyg/i18n/lang.pt_br.js";s:4:"d346";s:35:"res/akzhan-jwysiwyg/i18n/lang.ru.js";s:4:"139d";s:35:"res/akzhan-jwysiwyg/i18n/lang.se.js";s:4:"33be";s:35:"res/akzhan-jwysiwyg/i18n/lang.sl.js";s:4:"720d";s:38:"res/akzhan-jwysiwyg/i18n/lang.zh-cn.js";s:4:"26d4";s:36:"res/akzhan-jwysiwyg/lib/jquery1.5.js";s:4:"3b49";s:47:"res/akzhan-jwysiwyg/plugins/wysiwyg.autoload.js";s:4:"846e";s:50:"res/akzhan-jwysiwyg/plugins/wysiwyg.fileManager.js";s:4:"5cd5";s:49:"res/akzhan-jwysiwyg/plugins/wysiwyg.fullscreen.js";s:4:"c658";s:43:"res/akzhan-jwysiwyg/plugins/wysiwyg.i18n.js";s:4:"40ba";s:47:"res/akzhan-jwysiwyg/plugins/wysiwyg.rmFormat.js";s:4:"9bd3";s:48:"res/akzhan-jwysiwyg/plugins/fileManager/icon.png";s:4:"2aac";s:63:"res/akzhan-jwysiwyg/plugins/fileManager/wysiwyg.fileManager.css";s:4:"87cf";s:63:"res/akzhan-jwysiwyg/plugins/fileManager/handlers/PHP/common.php";s:4:"df2c";s:63:"res/akzhan-jwysiwyg/plugins/fileManager/handlers/PHP/config.php";s:4:"b1d2";s:69:"res/akzhan-jwysiwyg/plugins/fileManager/handlers/PHP/file-manager.php";s:4:"ebef";s:65:"res/akzhan-jwysiwyg/plugins/fileManager/handlers/PHP/handlers.php";s:4:"b32a";s:69:"res/akzhan-jwysiwyg/plugins/fileManager/handlers/Perl/file_manager.pl";s:4:"3422";s:70:"res/akzhan-jwysiwyg/plugins/fileManager/handlers/Perl/mkdir_handler.pl";s:4:"ee68";s:69:"res/akzhan-jwysiwyg/plugins/fileManager/handlers/Perl/move_handler.pl";s:4:"85c2";s:71:"res/akzhan-jwysiwyg/plugins/fileManager/handlers/Perl/remove_handler.pl";s:4:"8261";s:71:"res/akzhan-jwysiwyg/plugins/fileManager/handlers/Perl/rename_handler.pl";s:4:"1f83";s:71:"res/akzhan-jwysiwyg/plugins/fileManager/handlers/Perl/upload_handler.pl";s:4:"0849";s:62:"res/akzhan-jwysiwyg/plugins/fileManager/images/application.png";s:4:"fc51";s:55:"res/akzhan-jwysiwyg/plugins/fileManager/images/code.png";s:4:"c65f";s:54:"res/akzhan-jwysiwyg/plugins/fileManager/images/css.png";s:4:"783f";s:53:"res/akzhan-jwysiwyg/plugins/fileManager/images/db.png";s:4:"03e2";s:60:"res/akzhan-jwysiwyg/plugins/fileManager/images/directory.png";s:4:"fbd3";s:54:"res/akzhan-jwysiwyg/plugins/fileManager/images/doc.png";s:4:"38af";s:55:"res/akzhan-jwysiwyg/plugins/fileManager/images/file.png";s:4:"a311";s:55:"res/akzhan-jwysiwyg/plugins/fileManager/images/film.png";s:4:"5ad1";s:56:"res/akzhan-jwysiwyg/plugins/fileManager/images/flash.png";s:4:"132a";s:62:"res/akzhan-jwysiwyg/plugins/fileManager/images/folder_open.png";s:4:"bf30";s:55:"res/akzhan-jwysiwyg/plugins/fileManager/images/html.png";s:4:"12ac";s:55:"res/akzhan-jwysiwyg/plugins/fileManager/images/java.png";s:4:"ac46";s:56:"res/akzhan-jwysiwyg/plugins/fileManager/images/linux.png";s:4:"73c2";s:56:"res/akzhan-jwysiwyg/plugins/fileManager/images/mkdir.png";s:4:"4fd0";s:56:"res/akzhan-jwysiwyg/plugins/fileManager/images/music.png";s:4:"bd22";s:54:"res/akzhan-jwysiwyg/plugins/fileManager/images/pdf.png";s:4:"5ee1";s:54:"res/akzhan-jwysiwyg/plugins/fileManager/images/php.png";s:4:"48cd";s:58:"res/akzhan-jwysiwyg/plugins/fileManager/images/picture.png";s:4:"d204";s:54:"res/akzhan-jwysiwyg/plugins/fileManager/images/ppt.png";s:4:"8c36";s:65:"res/akzhan-jwysiwyg/plugins/fileManager/images/prev-directory.png";s:4:"8498";s:54:"res/akzhan-jwysiwyg/plugins/fileManager/images/psd.png";s:4:"787a";s:57:"res/akzhan-jwysiwyg/plugins/fileManager/images/remove.png";s:4:"6846";s:57:"res/akzhan-jwysiwyg/plugins/fileManager/images/rename.png";s:4:"4a0b";s:55:"res/akzhan-jwysiwyg/plugins/fileManager/images/ruby.png";s:4:"6615";s:57:"res/akzhan-jwysiwyg/plugins/fileManager/images/script.png";s:4:"13ad";s:54:"res/akzhan-jwysiwyg/plugins/fileManager/images/txt.png";s:4:"0da6";s:57:"res/akzhan-jwysiwyg/plugins/fileManager/images/upload.png";s:4:"82a7";s:54:"res/akzhan-jwysiwyg/plugins/fileManager/images/xls.png";s:4:"7363";s:54:"res/akzhan-jwysiwyg/plugins/fileManager/images/zip.png";s:4:"2eba";s:54:"res/akzhan-jwysiwyg/src/jquery.wysiwyg-names-only.html";s:4:"a9b2";s:52:"res/akzhan-jwysiwyg/src/jquery.wysiwyg-names-only.js";s:4:"21ed";s:41:"res/akzhan-jwysiwyg/src/jquery.wysiwyg.js";s:4:"4bdf";s:43:"res/akzhan-jwysiwyg/src/controls/default.js";s:4:"13cc";s:43:"res/akzhan-jwysiwyg/src/dialogs/default.css";s:4:"664e";s:42:"res/akzhan-jwysiwyg/src/dialogs/default.js";s:4:"d991";s:35:"res/akzhan-jwysiwyg/test/basic.html";s:4:"8053";s:36:"res/akzhan-jwysiwyg/test/events.html";s:4:"2cfc";s:46:"res/akzhan-jwysiwyg/test/external_toolbar.html";s:4:"fb3a";s:38:"res/akzhan-jwysiwyg/test/jwysiwyg.html";s:4:"3d71";s:48:"res/akzhan-jwysiwyg/test/multiple_instances.html";s:4:"e1ad";s:47:"res/akzhan-jwysiwyg/test/unified_dialog_ui.html";s:4:"13bd";s:40:"res/file_upload/jquery.fileupload-ui.css";s:4:"5cff";s:36:"res/file_upload/jquery.fileupload.js";s:4:"9079";s:42:"res/file_upload/jquery.iframe-transport.js";s:4:"f5e9";s:35:"res/file_upload/jquery.ui.widget.js";s:4:"bfd1";s:18:"res/images/add.png";s:4:"97e2";s:20:"res/images/arrow.png";s:4:"5f1c";s:23:"res/images/calendar.gif";s:4:"6a0a";s:20:"res/images/check.gif";s:4:"9620";s:21:"res/images/delete.png";s:4:"8872";s:19:"res/images/edit.gif";s:4:"3248";s:20:"res/images/minus.png";s:4:"5050";s:19:"res/images/plus.png";s:4:"a6af";s:21:"res/images/search.png";s:4:"bb96";s:20:"res/images/trash.gif";s:4:"47f2";s:41:"res/images/ui-bg_flat_0_aaaaaa_40x100.png";s:4:"2a44";s:42:"res/images/ui-bg_flat_75_ffffff_40x100.png";s:4:"8692";s:42:"res/images/ui-bg_glass_55_fbf9ee_1x400.png";s:4:"f8f4";s:42:"res/images/ui-bg_glass_65_ffffff_1x400.png";s:4:"e5a8";s:42:"res/images/ui-bg_glass_75_dadada_1x400.png";s:4:"c12c";s:42:"res/images/ui-bg_glass_75_e6e6e6_1x400.png";s:4:"f425";s:42:"res/images/ui-bg_glass_95_fef1ec_1x400.png";s:4:"5a3b";s:51:"res/images/ui-bg_highlight-soft_75_cccccc_1x100.png";s:4:"72c5";s:38:"res/images/ui-icons_222222_256x240.png";s:4:"9129";s:38:"res/images/ui-icons_2e83ff_256x240.png";s:4:"2516";s:38:"res/images/ui-icons_454545_256x240.png";s:4:"7710";s:38:"res/images/ui-icons_888888_256x240.png";s:4:"faf6";s:38:"res/images/ui-icons_cd0a0a_256x240.png";s:4:"5d88";s:32:"res/jquery_RTE/AutoPostTest.aspx";s:4:"90d7";s:35:"res/jquery_RTE/AutoPostTest.aspx.vb";s:4:"bf7b";s:34:"res/jquery_RTE/ColorPickerMenu.htm";s:4:"a327";s:26:"res/jquery_RTE/Default.htm";s:4:"7972";s:25:"res/jquery_RTE/Readme.txt";s:4:"8085";s:30:"res/jquery_RTE/images/disk.png";s:4:"bb6d";s:47:"res/jquery_RTE/scripts/jHtmlArea-0.7.0-vsdoc.js";s:4:"513b";s:41:"res/jquery_RTE/scripts/jHtmlArea-0.7.0.js";s:4:"76a0";s:51:"res/jquery_RTE/scripts/jHtmlArea-0.7.0.min-vsdoc.js";s:4:"e2af";s:45:"res/jquery_RTE/scripts/jHtmlArea-0.7.0.min.js";s:4:"efb8";s:57:"res/jquery_RTE/scripts/jHtmlArea.ColorPickerMenu-0.7.0.js";s:4:"dfc9";s:61:"res/jquery_RTE/scripts/jHtmlArea.ColorPickerMenu-0.7.0.min.js";s:4:"dba5";s:44:"res/jquery_RTE/scripts/jquery-1.3.2-vsdoc.js";s:4:"7904";s:38:"res/jquery_RTE/scripts/jquery-1.3.2.js";s:4:"2004";s:48:"res/jquery_RTE/scripts/jquery-1.3.2.min-vsdoc.js";s:4:"7904";s:42:"res/jquery_RTE/scripts/jquery-1.3.2.min.js";s:4:"d412";s:52:"res/jquery_RTE/scripts/jquery-ui-1.7.2.custom.min.js";s:4:"6d9a";s:50:"res/jquery_RTE/style/jHtmlArea.ColorPickerMenu.css";s:4:"b4d7";s:41:"res/jquery_RTE/style/jHtmlArea.Editor.css";s:4:"0361";s:34:"res/jquery_RTE/style/jHtmlArea.css";s:4:"fb02";s:34:"res/jquery_RTE/style/jHtmlArea.png";s:4:"6f53";s:51:"res/jquery_RTE/style/jHtmlArea_Toolbar_Group_BG.png";s:4:"2cac";s:63:"res/jquery_RTE/style/jHtmlArea_Toolbar_Group__Btn_Select_BG.png";s:4:"5871";s:69:"res/jquery_RTE/style/jqueryui/ui-lightness/jquery-ui-1.7.2.custom.css";s:4:"922c";s:91:"res/jquery_RTE/style/jqueryui/ui-lightness/images/ui-bg_diagonals-thick_18_b81900_40x40.png";s:4:"95f9";s:91:"res/jquery_RTE/style/jqueryui/ui-lightness/images/ui-bg_diagonals-thick_20_666666_40x40.png";s:4:"f040";s:81:"res/jquery_RTE/style/jqueryui/ui-lightness/images/ui-bg_flat_10_000000_40x100.png";s:4:"c18c";s:82:"res/jquery_RTE/style/jqueryui/ui-lightness/images/ui-bg_glass_100_f6f6f6_1x400.png";s:4:"5f18";s:82:"res/jquery_RTE/style/jqueryui/ui-lightness/images/ui-bg_glass_100_fdf5ce_1x400.png";s:4:"d26e";s:81:"res/jquery_RTE/style/jqueryui/ui-lightness/images/ui-bg_glass_65_ffffff_1x400.png";s:4:"e5a8";s:88:"res/jquery_RTE/style/jqueryui/ui-lightness/images/ui-bg_gloss-wave_35_f6a828_500x100.png";s:4:"58d2";s:91:"res/jquery_RTE/style/jqueryui/ui-lightness/images/ui-bg_highlight-soft_100_eeeeee_1x100.png";s:4:"384c";s:90:"res/jquery_RTE/style/jqueryui/ui-lightness/images/ui-bg_highlight-soft_75_ffe45c_1x100.png";s:4:"b806";s:77:"res/jquery_RTE/style/jqueryui/ui-lightness/images/ui-icons_222222_256x240.png";s:4:"9129";s:77:"res/jquery_RTE/style/jqueryui/ui-lightness/images/ui-icons_228ef1_256x240.png";s:4:"8d4d";s:77:"res/jquery_RTE/style/jqueryui/ui-lightness/images/ui-icons_ef8c08_256x240.png";s:4:"47fc";s:77:"res/jquery_RTE/style/jqueryui/ui-lightness/images/ui-icons_ffd27a_256x240.png";s:4:"f224";s:77:"res/jquery_RTE/style/jqueryui/ui-lightness/images/ui-icons_ffffff_256x240.png";s:4:"2cc8";s:33:"res/js_timepicker/GPL-LICENSE.txt";s:4:"cc1e";s:33:"res/js_timepicker/MIT-LICENSE.txt";s:4:"e622";s:28:"res/js_timepicker/index.html";s:4:"c60c";s:42:"res/js_timepicker/jquery.ui.timepicker.css";s:4:"4e96";s:41:"res/js_timepicker/jquery.ui.timepicker.js";s:4:"1455";s:30:"res/js_timepicker/releases.txt";s:4:"5cdb";s:32:"res/js_timepicker/i18n/i18n.html";s:4:"8a14";s:49:"res/js_timepicker/i18n/jquery.ui.timepicker-de.js";s:4:"278f";s:49:"res/js_timepicker/i18n/jquery.ui.timepicker-fr.js";s:4:"3291";s:49:"res/js_timepicker/i18n/jquery.ui.timepicker-ja.js";s:4:"ff4c";s:45:"res/js_timepicker/include/jquery-1.5.1.min.js";s:4:"b04a";s:53:"res/js_timepicker/include/jquery-ui-1.8.14.custom.css";s:4:"2748";s:47:"res/js_timepicker/include/jquery.ui.core.min.js";s:4:"72e2";s:51:"res/js_timepicker/include/jquery.ui.position.min.js";s:4:"d2f1";s:47:"res/js_timepicker/include/jquery.ui.tabs.min.js";s:4:"888a";s:49:"res/js_timepicker/include/jquery.ui.widget.min.js";s:4:"0563";s:74:"res/js_timepicker/include/images/ui-bg_diagonals-thick_18_b81900_40x40.png";s:4:"95f9";s:74:"res/js_timepicker/include/images/ui-bg_diagonals-thick_20_666666_40x40.png";s:4:"f040";s:64:"res/js_timepicker/include/images/ui-bg_flat_10_000000_40x100.png";s:4:"c18c";s:65:"res/js_timepicker/include/images/ui-bg_glass_100_f6f6f6_1x400.png";s:4:"5f18";s:65:"res/js_timepicker/include/images/ui-bg_glass_100_fdf5ce_1x400.png";s:4:"d26e";s:64:"res/js_timepicker/include/images/ui-bg_glass_65_ffffff_1x400.png";s:4:"e5a8";s:71:"res/js_timepicker/include/images/ui-bg_gloss-wave_35_f6a828_500x100.png";s:4:"58d2";s:74:"res/js_timepicker/include/images/ui-bg_highlight-soft_100_eeeeee_1x100.png";s:4:"384c";s:73:"res/js_timepicker/include/images/ui-bg_highlight-soft_75_ffe45c_1x100.png";s:4:"b806";s:60:"res/js_timepicker/include/images/ui-icons_222222_256x240.png";s:4:"ebe6";s:60:"res/js_timepicker/include/images/ui-icons_228ef1_256x240.png";s:4:"79f4";s:60:"res/js_timepicker/include/images/ui-icons_ef8c08_256x240.png";s:4:"ef9a";s:60:"res/js_timepicker/include/images/ui-icons_ffd27a_256x240.png";s:4:"ab8c";s:60:"res/js_timepicker/include/images/ui-icons_ffffff_256x240.png";s:4:"342b";s:87:"res/js_timepicker/include/ui-lightness/images/ui-bg_diagonals-thick_18_b81900_40x40.png";s:4:"95f9";s:87:"res/js_timepicker/include/ui-lightness/images/ui-bg_diagonals-thick_20_666666_40x40.png";s:4:"f040";s:77:"res/js_timepicker/include/ui-lightness/images/ui-bg_flat_10_000000_40x100.png";s:4:"c18c";s:78:"res/js_timepicker/include/ui-lightness/images/ui-bg_glass_100_f6f6f6_1x400.png";s:4:"5f18";s:78:"res/js_timepicker/include/ui-lightness/images/ui-bg_glass_100_fdf5ce_1x400.png";s:4:"d26e";s:77:"res/js_timepicker/include/ui-lightness/images/ui-bg_glass_65_ffffff_1x400.png";s:4:"e5a8";s:84:"res/js_timepicker/include/ui-lightness/images/ui-bg_gloss-wave_35_f6a828_500x100.png";s:4:"58d2";s:87:"res/js_timepicker/include/ui-lightness/images/ui-bg_highlight-soft_100_eeeeee_1x100.png";s:4:"384c";s:86:"res/js_timepicker/include/ui-lightness/images/ui-bg_highlight-soft_75_ffe45c_1x100.png";s:4:"b806";s:73:"res/js_timepicker/include/ui-lightness/images/ui-icons_222222_256x240.png";s:4:"ebe6";s:73:"res/js_timepicker/include/ui-lightness/images/ui-icons_228ef1_256x240.png";s:4:"79f4";s:73:"res/js_timepicker/include/ui-lightness/images/ui-icons_ef8c08_256x240.png";s:4:"ef9a";s:73:"res/js_timepicker/include/ui-lightness/images/ui-icons_ffd27a_256x240.png";s:4:"ab8c";s:73:"res/js_timepicker/include/ui-lightness/images/ui-icons_ffffff_256x240.png";s:4:"342b";s:40:"res/trentrichardson-Timepicker/README.md";s:4:"310b";s:61:"res/trentrichardson-Timepicker/jquery-ui-timepicker-addon.css";s:4:"c459";s:60:"res/trentrichardson-Timepicker/jquery-ui-timepicker-addon.js";s:4:"c211";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-ca.js";s:4:"eea6";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-cs.js";s:4:"52e6";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-de.js";s:4:"a86f";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-el.js";s:4:"7f85";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-es.js";s:4:"5d52";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-et.js";s:4:"38a5";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-fi.js";s:4:"9062";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-fr.js";s:4:"db4c";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-gl.js";s:4:"9af2";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-he.js";s:4:"67ad";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-hu.js";s:4:"961f";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-id.js";s:4:"084d";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-it.js";s:4:"1617";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-ja.js";s:4:"080c";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-ko.js";s:4:"5006";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-lt.js";s:4:"68aa";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-nl.js";s:4:"6a91";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-no.js";s:4:"9196";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-pl.js";s:4:"58e5";s:73:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-pt-BR.js";s:4:"b4c2";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-pt.js";s:4:"b181";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-ro.js";s:4:"ff2c";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-ru.js";s:4:"3c33";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-sk.js";s:4:"1505";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-sv.js";s:4:"24d8";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-tr.js";s:4:"8fea";s:70:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-vi.js";s:4:"6b5a";s:73:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-zh-CN.js";s:4:"701d";s:73:"res/trentrichardson-Timepicker/localization/jquery-ui-timepicker-zh-TW.js";s:4:"6345";s:20:"static/constants.txt";s:4:"29e5";s:16:"static/setup.txt";s:4:"8217";s:40:"view/class.tx_femanagement_view_form.php";s:4:"27db";s:45:"view/class.tx_femanagement_view_form_list.php";s:4:"d211";s:47:"view/class.tx_femanagement_view_form_single.php";s:4:"d69b";s:14:"view/liste.txt";s:4:"d0b8";s:51:"view/actions/class.tx_femanagement_view_actions.php";s:4:"1712";s:59:"view/actions/class.tx_femanagement_view_actions_onclick.php";s:4:"2362";s:59:"view/cal/class.tx_femanagement_view_form_cal_event_list.php";s:4:"3b65";s:61:"view/cal/class.tx_femanagement_view_form_cal_event_single.php";s:4:"0acd";s:62:"view/cal/class.tx_femanagement_view_form_cal_location_list.php";s:4:"3216";s:64:"view/cal/class.tx_femanagement_view_form_cal_location_single.php";s:4:"31a8";s:63:"view/cal/class.tx_femanagement_view_form_cal_organizer_list.php";s:4:"f564";s:65:"view/cal/class.tx_femanagement_view_form_cal_organizer_single.php";s:4:"1974";s:49:"view/filter/class.tx_femanagement_view_filter.php";s:4:"5dde";s:55:"view/filter/class.tx_femanagement_view_filter_check.php";s:4:"daee";s:56:"view/filter/class.tx_femanagement_view_filter_search.php";s:4:"f1e3";s:56:"view/filter/class.tx_femanagement_view_filter_select.php";s:4:"35e3";s:56:"view/filter/class.tx_femanagement_view_filter_toggle.php";s:4:"c8da";s:57:"view/form_fields/class.tx_femanagement_view_container.php";s:4:"424e";s:53:"view/form_fields/class.tx_femanagement_view_field.php";s:4:"c1fc";s:65:"view/form_fields/class.tx_femanagement_view_field_ajax_select.php";s:4:"904d";s:60:"view/form_fields/class.tx_femanagement_view_field_button.php";s:4:"cceb";s:62:"view/form_fields/class.tx_femanagement_view_field_checkbox.php";s:4:"ecb9";s:58:"view/form_fields/class.tx_femanagement_view_field_date.php";s:4:"4bef";s:63:"view/form_fields/class.tx_femanagement_view_field_dyn_table.php";s:4:"4cff";s:58:"view/form_fields/class.tx_femanagement_view_field_file.php";s:4:"6489";s:60:"view/form_fields/class.tx_femanagement_view_field_hidden.php";s:4:"31f9";s:59:"view/form_fields/class.tx_femanagement_view_field_input.php";s:4:"cf1a";s:65:"view/form_fields/class.tx_femanagement_view_field_multiselect.php";s:4:"e048";s:62:"view/form_fields/class.tx_femanagement_view_field_password.php";s:4:"8882";s:59:"view/form_fields/class.tx_femanagement_view_field_radio.php";s:4:"796b";s:60:"view/form_fields/class.tx_femanagement_view_field_select.php";s:4:"a5fe";s:58:"view/form_fields/class.tx_femanagement_view_field_text.php";s:4:"f4ef";s:62:"view/form_fields/class.tx_femanagement_view_field_textarea.php";s:4:"73d1";s:58:"view/form_fields/class.tx_femanagement_view_field_time.php";s:4:"e236";s:56:"view/form_fields/class.tx_femanagement_view_fieldset.php";s:4:"dd5b";s:74:"view/permissions/class.tx_femanagement_view_form_permissions_apps_list.php";s:4:"1d33";s:76:"view/permissions/class.tx_femanagement_view_form_permissions_apps_single.php";s:4:"d583";s:76:"view/permissions/class.tx_femanagement_view_form_permissions_groups_list.php";s:4:"8fb7";s:78:"view/permissions/class.tx_femanagement_view_form_permissions_groups_single.php";s:4:"ccde";s:75:"view/permissions/class.tx_femanagement_view_form_permissions_roles_list.php";s:4:"91ec";s:77:"view/permissions/class.tx_femanagement_view_form_permissions_roles_single.php";s:4:"277e";s:62:"view/qsm/class.tx_femanagement_view_form_qsm_antraege_list.php";s:4:"fa97";s:64:"view/qsm/class.tx_femanagement_view_form_qsm_antraege_single.php";s:4:"e538";s:58:"view/tt_news/class.tx_femanagement_view_form_news_list.php";s:4:"3ae4";s:60:"view/tt_news/class.tx_femanagement_view_form_news_single.php";s:4:"b8dc";}',
	'suggests' => array(
	),
);

?>