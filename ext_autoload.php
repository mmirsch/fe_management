<?php
$extensionPath = t3lib_extMgm::extPath('fe_management');

return array(

/*
 * ####################### STATIC ####################### 
 */
		
// lib
'tx_femanagement_lib_util' => $extensionPath . 'lib/' . 'class.tx_femanagement_lib_util.php',
'tx_femanagement_lib_htmlparser' => $extensionPath . 'lib/' . 'class.tx_femanagement_lib_htmlparser.php',
'tx_femanagement_lib_shop' => $extensionPath . 'lib/shop/' . 'class.tx_femanagement_lib_shop.php',
		
/*
 * ####################### Controller ####################### 
 */
		
// global
'tx_femanagement_controller_general' => $extensionPath . 'controller/' . 'class.tx_femanagement_controller_general.php',
'tx_femanagement_controller' => $extensionPath . 'controller/' . 'class.tx_femanagement_controller.php',
		
// shop
'tx_femanagement_controller_shop_main' => $extensionPath . 'controller/shop/' . 'class.tx_femanagement_controller_shop_main.php',
'tx_femanagement_controller_shop_article' => $extensionPath . 'controller/shop/' . 'class.tx_femanagement_controller_shop_article.php',
'tx_femanagement_controller_shop_hersteller' => $extensionPath . 'controller/shop/' . 'class.tx_femanagement_controller_shop_hersteller.php',
'tx_femanagement_controller_shop_lieferanten' => $extensionPath . 'controller/shop/' . 'class.tx_femanagement_controller_shop_lieferanten.php',
		
		
// cal
'tx_femanagement_controller_calendar_main' => $extensionPath . 'controller/cal/' . 'class.tx_femanagement_controller_calendar_main.php',
'tx_femanagement_controller_calendar_event' => $extensionPath . 'controller/cal/' . 'class.tx_femanagement_controller_calendar_event.php',
'tx_femanagement_controller_calendar_location' => $extensionPath . 'controller/cal/' . 'class.tx_femanagement_controller_calendar_location.php',
'tx_femanagement_controller_calendar_organizer' => $extensionPath . 'controller/cal/' . 'class.tx_femanagement_controller_calendar_organizer.php',

// permissions
'tx_femanagement_controller_permissions_main' => $extensionPath . 'controller/permissions/' . 'class.tx_femanagement_controller_permissions_main.php',
'tx_femanagement_controller_permissions_apps' => $extensionPath . 'controller/permissions/' . 'class.tx_femanagement_controller_permissions_apps.php',
'tx_femanagement_controller_permissions_groups' => $extensionPath . 'controller/permissions/' . 'class.tx_femanagement_controller_permissions_groups.php',
'tx_femanagement_controller_permissions_roles' => $extensionPath . 'controller/permissions/' . 'class.tx_femanagement_controller_permissions_roles.php',
'tx_femanagement_controller_permissions_domains' => $extensionPath . 'controller/permissions/' . 'class.tx_femanagement_controller_permissions_domains.php',

// qsm
'tx_femanagement_controller_qsm_general' => $extensionPath . 'controller/qsm/' . 'class.tx_femanagement_controller_qsm_general.php',
'tx_femanagement_controller_qsm_main' => $extensionPath . 'controller/qsm/' . 'class.tx_femanagement_controller_qsm_main.php',
'tx_femanagement_controller_qsm_antraege' => $extensionPath . 'controller/qsm/' . 'class.tx_femanagement_controller_qsm_antraege.php',

'tx_femanagement_controller_qsm_antraege_alle' => $extensionPath . 'controller/qsm/antraege/' . 'class.tx_femanagement_controller_qsm_antraege_alle.php',
'tx_femanagement_controller_qsm_antraege_fina' => $extensionPath . 'controller/qsm/antraege/' . 'class.tx_femanagement_controller_qsm_antraege_fina.php',
'tx_femanagement_controller_qsm_antraege_gremien' => $extensionPath . 'controller/qsm/antraege/' . 'class.tx_femanagement_controller_qsm_antraege_gremien.php',
'tx_femanagement_controller_qsm_antraege_meine' => $extensionPath . 'controller/qsm/antraege/' . 'class.tx_femanagement_controller_qsm_antraege_meine.php',
'tx_femanagement_controller_qsm_antraege_verwendung' => $extensionPath . 'controller/qsm/antraege/' . 'class.tx_femanagement_controller_qsm_antraege_verwendung.php',

'tx_femanagement_controller_qsm_gremien' => $extensionPath . 'controller/qsm/' . 'class.tx_femanagement_controller_qsm_gremien.php',
'tx_femanagement_controller_qsm_einrichtungen' => $extensionPath . 'controller/qsm/' . 'class.tx_femanagement_controller_qsm_einrichtungen.php',
'tx_femanagement_controller_qsm_zeitraeume' => $extensionPath . 'controller/qsm/' . 'class.tx_femanagement_controller_qsm_zeitraeume.php',

// tt_news
'tx_femanagement_controller_news' => $extensionPath . 'controller/tt_news/' . 'class.tx_femanagement_controller_news.php',

// lib
'tx_femanagement_controller_lib_email' => $extensionPath . 'controller/lib/' . 'class.tx_femanagement_controller_lib_email.php',
'tx_femanagement_controller_lib_upload' => $extensionPath . 'controller/lib/' . 'class.tx_femanagement_controller_lib_upload.php',
		
// forschung
'tx_femanagement_controller_forschung' => $extensionPath . 'controller/forschung/' . 'class.tx_femanagement_controller_forschung.php',
'tx_femanagement_controller_forschung_personen' => $extensionPath . 'controller/forschung/' . 'class.tx_femanagement_controller_forschung_personen.php',

// promotionen
'tx_femanagement_controller_promotionen' => $extensionPath . 'controller/promotionen/' . 'class.tx_femanagement_controller_promotionen.php',

// modules_en
'tx_femanagement_controller_modules_en' => $extensionPath . 'controller/modules_en/' . 'class.tx_femanagement_controller_modules_en.php',
		
// events
  'tx_femanagement_controller_events' => $extensionPath . 'controller/events/' . 'class.tx_femanagement_controller_events.php',
  'tx_femanagement_controller_events_anmeldungen' => $extensionPath . 'controller/events/' . 'class.tx_femanagement_controller_events_anmeldungen.php',

/*
 * ####################### Model ####################### 
 */

// Global
'tx_femanagement_model' => $extensionPath . 'model/' . 'class.tx_femanagement_model.php',
'tx_femanagement_model_general_userdata' => $extensionPath . 'model/general/' . 'class.tx_femanagement_model_general_userdata.php',
'tx_femanagement_model_general_personen' => $extensionPath . 'model/general/' . 'class.tx_femanagement_model_general_personen.php',

// cal
'tx_femanagement_model_cal_categories' => $extensionPath . 'model/cal/' . 'class.tx_femanagement_model_cal_categories.php',
'tx_femanagement_model_cal_event' => $extensionPath . 'model/cal/' . 'class.tx_femanagement_model_cal_event.php',
'tx_femanagement_model_cal_location' => $extensionPath . 'model/cal/' . 'class.tx_femanagement_model_cal_location.php',
'tx_femanagement_model_cal_organizer' => $extensionPath . 'model/cal/' . 'class.tx_femanagement_model_cal_organizer.php',
'tx_femanagement_model_cal_user' => $extensionPath . 'model/cal/' . 'class.tx_femanagement_model_cal_user.php',

// permissions
'tx_femanagement_model_permissions_apps' => $extensionPath . 'model/permissions/' . 'class.tx_femanagement_model_permissions_apps.php',
'tx_femanagement_model_permissions_groups' => $extensionPath . 'model/permissions/' . 'class.tx_femanagement_model_permissions_groups.php',
'tx_femanagement_model_permissions_roles' => $extensionPath . 'model/permissions/' . 'class.tx_femanagement_model_permissions_roles.php',
'tx_femanagement_model_permissions_domains' => $extensionPath . 'model/permissions/' . 'class.tx_femanagement_model_permissions_domains.php',

// qsm
'tx_femanagement_model_qsm_antraege' => $extensionPath . 'model/qsm/' . 'class.tx_femanagement_model_qsm_antraege.php',
'tx_femanagement_model_qsm_mittel' => $extensionPath . 'model/qsm/' . 'class.tx_femanagement_model_qsm_mittel.php',
'tx_femanagement_model_qsm_fe_users' => $extensionPath . 'model/qsm/' . 'class.tx_femanagement_model_qsm_fe_users.php',
'tx_femanagement_model_qsm_budgets' => $extensionPath . 'model/qsm/' . 'class.tx_femanagement_model_qsm_budgets.php',
'tx_femanagement_model_qsm_gremien' => $extensionPath . 'model/qsm/' . 'class.tx_femanagement_model_qsm_gremien.php',
'tx_femanagement_model_qsm_einrichtungen' => $extensionPath . 'model/qsm/' . 'class.tx_femanagement_model_qsm_einrichtungen.php',
'tx_femanagement_model_qsm_zeitraeume' => $extensionPath . 'model/qsm/' . 'class.tx_femanagement_model_qsm_zeitraeume.php',
		
// tt_news
'tx_femanagement_model_news' => $extensionPath . 'model/tt_news/' . 'class.tx_femanagement_model_news.php',
'tx_femanagement_model_news_categories' => $extensionPath . 'model/tt_news/' . 'class.tx_femanagement_model_news_categories.php',
'tx_femanagement_model_news_sysfolders' => $extensionPath . 'model/tt_news/' . 'class.tx_femanagement_model_news_sysfolders.php',
		
// cal
'tx_femanagement_model_shop_article' => $extensionPath . 'model/shop/' . 'class.tx_femanagement_model_shop_article.php',
'tx_femanagement_model_shop_hersteller' => $extensionPath . 'model/shop/' . 'class.tx_femanagement_model_shop_hersteller.php',
'tx_femanagement_model_shop_lieferanten' => $extensionPath . 'model/shop/' . 'class.tx_femanagement_model_shop_lieferanten.php',
'tx_femanagement_model_shop_hauptkategorie' => $extensionPath . 'model/shop/' . 'class.tx_femanagement_model_shop_hauptkategorie.php',
'tx_femanagement_model_shop_unterkategorie' => $extensionPath . 'model/shop/' . 'class.tx_femanagement_model_shop_unterkategorie.php',
'tx_femanagement_model_shop_eigenschaft1' => $extensionPath . 'model/shop/' . 'class.tx_femanagement_model_shop_eigenschaft1.php',
'tx_femanagement_model_shop_eigenschaft2' => $extensionPath . 'model/shop/' . 'class.tx_femanagement_model_shop_eigenschaft2.php',
'tx_femanagement_model_shop_keyword1' => $extensionPath . 'model/shop/' . 'class.tx_femanagement_model_shop_keyword1.php',
'tx_femanagement_model_shop_keyword2' => $extensionPath . 'model/shop/' . 'class.tx_femanagement_model_shop_keyword2.php',
		
// forschung
'tx_femanagement_model_forschung' => $extensionPath . 'model/forschung/' . 'class.tx_femanagement_model_forschung.php',
'tx_femanagement_model_forschung_einrichtungen' => $extensionPath . 'model/forschung/' . 'class.tx_femanagement_model_forschung_einrichtungen.php',

// promotionen
'tx_femanagement_model_promotionen' => $extensionPath . 'model/promotionen/' . 'class.tx_femanagement_model_promotionen.php',

// modules_en
'tx_femanagement_model_modules_en' => $extensionPath . 'model/modules_en/' . 'class.tx_femanagement_model_modules_en.php',
		
// events
'tx_femanagement_model_events' => $extensionPath . 'model/events/' . 'class.tx_femanagement_model_events.php',
'tx_femanagement_model_events_dates' => $extensionPath . 'model/events/' . 'class.tx_femanagement_model_events_dates.php',
'tx_femanagement_model_events_anmeldungen' => $extensionPath . 'model/events/' . 'class.tx_femanagement_model_events_anmeldungen.php',
		
/*
 * ####################### View ####################### 
 */

// Global
'tx_femanagement_view_form' => $extensionPath . 'view/' . 'class.tx_femanagement_view_form.php',
'tx_femanagement_view_form_list' => $extensionPath . 'view/' . 'class.tx_femanagement_view_form_list.php',
'tx_femanagement_view_form_single' => $extensionPath . 'view/' . 'class.tx_femanagement_view_form_single.php',

// actions
'tx_femanagement_view_actions' => $extensionPath . 'view/actions/' . 'class.tx_femanagement_view_actions.php',
'tx_femanagement_view_actions_onclick' => $extensionPath . 'view/actions/' . 'class.tx_femanagement_view_actions_onclick.php',
'tx_femanagement_view_actions_popup' => $extensionPath . 'view/actions/' . 'class.tx_femanagement_view_actions_popup.php',

// cal
'tx_femanagement_view_form_cal_event_list' => $extensionPath . 'view/cal/' . 'class.tx_femanagement_view_form_cal_event_list.php',
'tx_femanagement_view_form_cal_event_single' => $extensionPath . 'view/cal/' . 'class.tx_femanagement_view_form_cal_event_single.php',
'tx_femanagement_view_form_cal_location_list' => $extensionPath . 'view/cal/' . 'class.tx_femanagement_view_form_cal_location_list.php',
'tx_femanagement_view_form_cal_location_single' => $extensionPath . 'view/cal/' . 'class.tx_femanagement_view_form_cal_location_single.php',
'tx_femanagement_view_form_cal_organizer_list' => $extensionPath . 'view/cal/' . 'class.tx_femanagement_view_form_cal_organizer_list.php',
'tx_femanagement_view_form_cal_organizer_single' => $extensionPath . 'view/cal/' . 'class.tx_femanagement_view_form_cal_organizer_single.php',

// filter
'tx_femanagement_view_filter' => $extensionPath . 'view/filter/' . 'class.tx_femanagement_view_filter.php',
'tx_femanagement_view_filter_check' => $extensionPath . 'view/filter/' . 'class.tx_femanagement_view_filter_check.php',
'tx_femanagement_view_filter_date' => $extensionPath . 'view/filter/' . 'class.tx_femanagement_view_filter_date.php',
'tx_femanagement_view_filter_export' => $extensionPath . 'view/filter/' . 'class.tx_femanagement_view_filter_export.php',
'tx_femanagement_view_filter_hidden' => $extensionPath . 'view/filter/' . 'class.tx_femanagement_view_filter_hidden.php',
'tx_femanagement_view_filter_search' => $extensionPath . 'view/filter/' . 'class.tx_femanagement_view_filter_search.php',
'tx_femanagement_view_filter_select' => $extensionPath . 'view/filter/' . 'class.tx_femanagement_view_filter_select.php',
'tx_femanagement_view_filter_text' => $extensionPath . 'view/filter/' . 'class.tx_femanagement_view_filter_text.php',
'tx_femanagement_view_filter_toggle' => $extensionPath . 'view/filter/' . 'class.tx_femanagement_view_filter_toggle.php',
'tx_femanagement_view_filter_reset' => $extensionPath . 'view/filter/' . 'class.tx_femanagement_view_filter_reset.php',
		
// export 
'tx_femanagement_view_export' => $extensionPath . 'view/export/' . 'class.tx_femanagement_view_export.php',
'tx_femanagement_view_export_csv' => $extensionPath . 'view/export/' . 'class.tx_femanagement_view_export_csv.php',
'tx_femanagement_view_export_excel' => $extensionPath . 'view/export/' . 'class.tx_femanagement_view_export_excel.php',
'tx_femanagement_view_export_excel_xls' => $extensionPath . 'view/export/' . 'class.tx_femanagement_view_export_excel_xls.php',
'tx_femanagement_view_export_word' => $extensionPath . 'view/export/' . 'class.tx_femanagement_view_export_word.php',

// form_fields
'tx_femanagement_view_container' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_container.php',
'tx_femanagement_view_field' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field.php',
'tx_femanagement_view_field_ajax_select' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_ajax_select.php',
'tx_femanagement_view_field_button' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_button.php',
'tx_femanagement_view_field_checkbox' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_checkbox.php',
'tx_femanagement_view_field_date' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_date.php',
'tx_femanagement_view_field_dyn_table' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_dyn_table.php',
'tx_femanagement_view_field_file' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_file.php',
'tx_femanagement_view_field_hidden' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_hidden.php',
'tx_femanagement_view_field_input' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_input.php',
'tx_femanagement_view_field_info' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_info.php',
'tx_femanagement_view_field_multiselect' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_multiselect.php',
'tx_femanagement_view_field_password' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_password.php',
'tx_femanagement_view_field_radio' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_radio.php',
'tx_femanagement_view_field_readonly' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_readonly.php',
'tx_femanagement_view_field_select' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_select.php',
'tx_femanagement_view_field_ajax_feuser_select' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_ajax_feuser_select.php',
'tx_femanagement_view_field_text' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_text.php',
'tx_femanagement_view_field_textarea' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_textarea.php',
'tx_femanagement_view_field_time' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_field_time.php',
'tx_femanagement_view_fieldset' => $extensionPath . 'view/form_fields/' . 'class.tx_femanagement_view_fieldset.php',

// permissions
'tx_femanagement_view_form_permissions_apps_list' => $extensionPath . 'view/permissions/' . 'class.tx_femanagement_view_form_permissions_apps_list.php',
'tx_femanagement_view_form_permissions_apps_single' => $extensionPath . 'view/permissions/' . 'class.tx_femanagement_view_form_permissions_apps_single.php',
'tx_femanagement_view_form_permissions_groups_list' => $extensionPath . 'view/permissions/' . 'class.tx_femanagement_view_form_permissions_groups_list.php',
'tx_femanagement_view_form_permissions_groups_single' => $extensionPath . 'view/permissions/' . 'class.tx_femanagement_view_form_permissions_groups_single.php',
'tx_femanagement_view_form_permissions_roles_list' => $extensionPath . 'view/permissions/' . 'class.tx_femanagement_view_form_permissions_roles_list.php',
'tx_femanagement_view_form_permissions_roles_single' => $extensionPath . 'view/permissions/' . 'class.tx_femanagement_view_form_permissions_roles_single.php',
'tx_femanagement_view_form_permissions_domains_list' => $extensionPath . 'view/permissions/' . 'class.tx_femanagement_view_form_permissions_domains_list.php',
'tx_femanagement_view_form_permissions_domains_single' => $extensionPath . 'view/permissions/' . 'class.tx_femanagement_view_form_permissions_domains_single.php',

// qsm
'tx_femanagement_view_qsm_antraege_list' => $extensionPath . 'view/qsm/' . 'class.tx_femanagement_view_qsm_antraege_list.php',
'tx_femanagement_view_qsm_antraege_single' => $extensionPath . 'view/qsm/' . 'class.tx_femanagement_view_qsm_antraege_single.php',
'tx_femanagement_view_qsm_gremien_list' => $extensionPath . 'view/qsm/' . 'class.tx_femanagement_view_qsm_gremien_list.php',
'tx_femanagement_view_qsm_gremien_single' => $extensionPath . 'view/qsm/' . 'class.tx_femanagement_view_qsm_gremien_single.php',
'tx_femanagement_view_qsm_einrichtungen_list' => $extensionPath . 'view/qsm/' . 'class.tx_femanagement_view_qsm_einrichtungen_list.php',
'tx_femanagement_view_qsm_einrichtungen_single' => $extensionPath . 'view/qsm/' . 'class.tx_femanagement_view_qsm_einrichtungen_single.php',
'tx_femanagement_view_qsm_zeitraeume_list' => $extensionPath . 'view/qsm/' . 'class.tx_femanagement_view_qsm_zeitraeume_list.php',
'tx_femanagement_view_qsm_zeitraeume_single' => $extensionPath . 'view/qsm/' . 'class.tx_femanagement_view_qsm_zeitraeume_single.php',
		
// qsm Anträge Verwendung
'tx_femanagement_view_qsm_antraege_verwendung_list' => $extensionPath . 'view/qsm/antraege/' . 'class.tx_femanagement_view_qsm_antraege_verwendung_list.php',
'tx_femanagement_view_qsm_antraege_verwendung_single' => $extensionPath . 'view/qsm/antraege/' . 'class.tx_femanagement_view_qsm_antraege_verwendung_single.php',
		
// tt_news
'tx_femanagement_view_form_news_list' => $extensionPath . 'view/tt_news/' . 'class.tx_femanagement_view_form_news_list.php',
'tx_femanagement_view_form_news_single' => $extensionPath . 'view/tt_news/' . 'class.tx_femanagement_view_form_news_single.php',

// shop
'tx_femanagement_view_form_shop_article_list' => $extensionPath . 'view/shop/' . 'class.tx_femanagement_view_form_shop_article_list.php',
'tx_femanagement_view_form_shop_article_single' => $extensionPath . 'view/shop/' . 'class.tx_femanagement_view_form_shop_article_single.php',
'tx_femanagement_view_form_shop_article_cart' => $extensionPath . 'view/shop/' . 'class.tx_femanagement_view_form_shop_article_cart.php',
'tx_femanagement_view_form_shop_lieferanten_list' => $extensionPath . 'view/shop/' . 'class.tx_femanagement_view_form_shop_lieferanten_list.php',
'tx_femanagement_view_form_shop_lieferanten_single' => $extensionPath . 'view/shop/' . 'class.tx_femanagement_view_form_shop_lieferanten_single.php',
'tx_femanagement_view_form_shop_hersteller_list' => $extensionPath . 'view/shop/' . 'class.tx_femanagement_view_form_shop_hersteller_list.php',
'tx_femanagement_view_form_shop_hersteller_single' => $extensionPath . 'view/shop/' . 'class.tx_femanagement_view_form_shop_hersteller_single.php',
		

// forschung
'tx_femanagement_view_forschung_list' => $extensionPath . 'view/forschung/' . 'class.tx_femanagement_view_forschung_list.php',
'tx_femanagement_view_forschung_single' => $extensionPath . 'view/forschung/' . 'class.tx_femanagement_view_forschung_single.php',
'tx_femanagement_view_forschung_personen_list' => $extensionPath . 'view/forschung/' . 'class.tx_femanagement_view_forschung_personen_list.php',
'tx_femanagement_view_forschung_personen_single' => $extensionPath . 'view/forschung/' . 'class.tx_femanagement_view_forschung_personen_single.php',
		
// promotionen
'tx_femanagement_view_promotionen_list' => $extensionPath . 'view/promotionen/' . 'class.tx_femanagement_view_promotionen_list.php',
'tx_femanagement_view_promotionen_single' => $extensionPath . 'view/promotionen/' . 'class.tx_femanagement_view_promotionen_single.php',

// modules_en		
'tx_femanagement_view_modules_en_list' => $extensionPath . 'view/modules_en/' . 'class.tx_femanagement_view_modules_en_list.php',
'tx_femanagement_view_modules_en_single' => $extensionPath . 'view/modules_en/' . 'class.tx_femanagement_view_modules_en_single.php',
		
// events		
  'tx_femanagement_view_events_list' => $extensionPath . 'view/events/' . 'class.tx_femanagement_view_events_list.php',
  'tx_femanagement_view_events_single' => $extensionPath . 'view/events/' . 'class.tx_femanagement_view_events_single.php',
  'tx_femanagement_view_events_anmeldungen_list' => $extensionPath . 'view/events/' . 'class.tx_femanagement_view_events_anmeldungen_list.php',
  'tx_femanagement_view_events_anmeldungen_single' => $extensionPath . 'view/events/' . 'class.tx_femanagement_view_events_anmeldungen_single.php',

);
?>