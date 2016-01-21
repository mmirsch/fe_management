Fragen:

- input felder Readonly ? Brauchen wir das oder kann man das flexibel übergeben?
		<input name="" type="text" size="8" value="fester wert" readonly="readonly">
		
- input felder selected zum Beispiel zum editieren vorhandenere Einträge
		selected="selected"  ??
		
- was bedeutet das $mode in der funktion show($mode) in class.tx_femanagement_view_form.php

- Umsetzung von eines Dropdown Feldes select -- flexibel? mit javascript eid

- locallang für deutsch und englisch

- Zuordnung von cal Kategorie oder cal Kalender mit Möglichkeit weitere Unterkategorien anzulegen.
	Momentan nur ein Kalender mit vielen Kategorien??
	
- alles was bei case CAL_CREATEFORM steht in controller verschieben ???

ABLAUF:

- Start ist in der pi1
		- Einbindung aller Klassen
		- Einbindung von Javascripten und CSS
		- Abfrage von ausgewählter Ansicht über Flexform
		- Bei Create Event:
				- Erzeugen einer neuen Instanz von view_form_cal welche die Klasse view_form erweitert
				  daher wird der Contructor (die Funktion public function __construct($title='',$wrapClass='') ) mit den 
				  Parametern title und wrap Class aufgerufen.
				  (--> Die Funktion ist in der Mutterklasse leer aber wird über die individuellen Kinderklassen zB. view_form_cal erweitert 
				  da je nach Bereich unterschiedliche Felder benötigt werden)
				- Die Felder werden erzeugt (Arrays), dann in einem Container-Array gesammelt, welches wiederum in einem Containerlist-Array gesammelt wird.
				  Das Containerlist-Array wird dann an die Funktion addFieldset in der Klasse view_form übergeben welche ein neues Objekt erstellt und 
				  falls es ein Array ist einen neuen container erstellt welches ein neues Objekt für view_field erstellt.
				  
				  --> 
				  