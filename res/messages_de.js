/*
 * Translated default messages for the jQuery validation plugin.
 * Locale: DE
 */
jQuery.tools.validator.localize("de", {
	'[required]' : "Dieses Feld ist ein Pflichtfeld.",
	'[max]': "Geben Sie bitte maximal $1 Zeichen ein.",
	'[min]': "Geben Sie bitte mindestens $1 Zeichen ein.",
	':email': "Geben Sie bitte eine gültige E-Mail Adresse ein.",
	':url': "Geben Sie bitte eine gültige URL ein.",
	':date': "Bitte geben Sie ein gültiges Datum ein.",
	':number': "Geben Sie bitte eine Nummer ein.",
	':digits': "Geben Sie bitte nur Ziffern ein.",
});

jQuery(function($){
        $.datepicker.regional['de'] = {clearText: 'löschen', clearStatus: 'aktuelles Datum löschen',
                closeText: 'schließen', closeStatus: 'ohne Änderungen schließen',
                prevText: '<zurück', prevStatus: 'letzten Monat zeigen',
                nextText: 'Vor>', nextStatus: 'nächsten Monat zeigen',
                currentText: 'heute', currentStatus: '',
                monthNames: ['Januar','Februar','März','April','Mai','Juni',
                'Juli','August','September','Oktober','November','Dezember'],
                monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun',
                'Jul','Aug','Sep','Okt','Nov','Dez'],
                monthStatus: 'anderen Monat anzeigen', yearStatus: 'anderes Jahr anzeigen',
                weekHeader: 'Wo', weekStatus: 'Woche des Monats',
                dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
                dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
                dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
                dayStatus: 'Setze DD als ersten Wochentag', dateStatus: 'Wähle D, M d',
                dateFormat: 'dd.mm.yy', firstDay: 1,
                initStatus: 'Wähle ein Datum', isRTL: false};
        $.datepicker.setDefaults($.datepicker.regional['de']);
});