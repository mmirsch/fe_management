#
# Additional fields for table 'tt_news_cat'
#
CREATE TABLE tt_news_cat (
	tx_femanagement_news_cat_admin tinytext,
	tx_femanagement_news_associated_group tinytext
);

#
# Additional fields for table 'tt_news_cat'
#
CREATE TABLE tt_news (
	fe_cruser_id int(11) DEFAULT '0' NOT NULL,
);

#
# Table structure for table 'tx_cal_category'
#
CREATE TABLE tx_cal_category (
	tx_femanagement_cal_cat_admin tinytext,
	tx_femanagement_cal_associated_group tinytext
);

#
# Table structure for table 'tx_cal_location'
#
CREATE TABLE tx_cal_location (
	tx_femanagement_cal_campus tinytext,
	tx_femanagement_cal_building tinytext,
	tx_femanagement_cal_room tinytext
);

#
# Table structure for table 'tx_cal_event'
#
CREATE TABLE tx_cal_event (
	title varchar(255) DEFAULT '' NOT NULL,
	tx_femanagement_cal_title_infoscreen tinytext
);

#
# Table structure for table 'tx_fe_management_permissions_roles'
#
CREATE TABLE tx_fe_management_permissions_roles (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	title text,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_fe_management_permissions_domains'
#
CREATE TABLE tx_fe_management_permissions_domains (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	title text,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_fe_management_permissions_groups'
#
CREATE TABLE tx_fe_management_permissions_groups (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	title text,
	description text,
	application text,
	role int(11) DEFAULT '0' NOT NULL,
	domain int(11) DEFAULT '0' NOT NULL,
	usergroup tinytext,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_qsm_antraege'
#
CREATE TABLE tx_qsm_antraege (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	fe_cruser_id int(11) DEFAULT '0' NOT NULL,
	
	masnanr tinytext,
	status tinyint(3) DEFAULT '0' NOT NULL,
	title text,
	short_title text,
	ziel text,
	begruendung text,
	anlage text,
	zwbericht text,
	asbericht text,
	antragsteller tinytext,
	antragsteller_name tinytext,
	bereich tinytext,
	einrichtung text,
	fina_bereich1 tinytext,
	fina_bereich2 tinytext,
	bezugssemester tinytext,	
	start int(11) DEFAULT '0' NOT NULL,
	ende int(11) DEFAULT '0' NOT NULL,
	entscheidung int(11) DEFAULT '0' NOT NULL,
	persstellen tinytext,	
	anmerkungen text,
	kommentar text,
	originalId int(11) DEFAULT '0' NOT NULL,
	email_pers tinyint(4) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_qsm_antraege_verantwortliche'
#
CREATE TABLE tx_qsm_antraege_verantwortliche (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	antrag int(11) DEFAULT '0' NOT NULL,
	username tinytext,
	name text,

	PRIMARY KEY (uid),
	KEY parent (pid)
);


#
# Table structure for table 'tx_qsm_gremien'
#
CREATE TABLE tx_qsm_gremien (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	fe_cruser_id int(11) DEFAULT '0' NOT NULL,
	title text,
	kuerzel tinytext,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_qsm_gremien_personen'
#
CREATE TABLE tx_qsm_gremien_personen (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	gremium int(11) DEFAULT '0' NOT NULL,
	username text,
	rolle tinytext,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_qsm_fina_bereiche1'
#
CREATE TABLE tx_qsm_fina_bereiche1 (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	fe_cruser_id int(11) DEFAULT '0' NOT NULL,
	title text,
	schluessel tinytext,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_qsm_fina_bereiche2'
#
CREATE TABLE tx_qsm_fina_bereiche2 (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	fe_cruser_id int(11) DEFAULT '0' NOT NULL,
	title text,
	schluessel tinytext,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_qsm_einrichtungen'
#
CREATE TABLE tx_qsm_einrichtungen (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	fe_cruser_id int(11) DEFAULT '0' NOT NULL,
	title text,
	kuerzel tinytext,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_qsm_zeitraeume'
#
CREATE TABLE tx_qsm_zeitraeume (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	fe_cruser_id int(11) DEFAULT '0' NOT NULL,
	title text,
	start int(11) DEFAULT '0' NOT NULL,
	ende int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_qsm_mittel'
#
CREATE TABLE tx_qsm_mittel (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	fe_cruser_id int(11) DEFAULT '0' NOT NULL,
	antrag int(11) DEFAULT '0' NOT NULL,
	title text,
	betrag text,
	kostenstelle text,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_qsm_anlagen'
#
CREATE TABLE tx_qsm_anlagen (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	fe_cruser_id int(11) DEFAULT '0' NOT NULL,
	antrag int(11) DEFAULT '0' NOT NULL,
	mode tinytext,
	pfad text,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_qsm_budgets'
#
CREATE TABLE tx_qsm_budgets (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	fe_cruser_id int(11) DEFAULT '0' NOT NULL,
	antrag int(11) DEFAULT '0' NOT NULL,
	zeitraum tinytext,
	mode tinytext,
	budget tinytext,
	version tinyint(4) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_femanagement_forschungsprojekte'
#
CREATE TABLE tx_femanagement_forschungsprojekte (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	fe_cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	
	title text,
	leitende_einrichtung text,
	fakultaet text,
	faku_link text,
	projektnummer varchar(50) DEFAULT '' NOT NULL,
  foerderkennzeichen varchar(50) DEFAULT '' NOT NULL,
	projekt_typ tinyint(4) DEFAULT '0' NOT NULL,
	foerderung_typ tinyint(4) DEFAULT '0' NOT NULL,
	foerderung_wer text,
	kooperationspartner text,
	wiss_leitung text,
	wiss_leitung_alt text,
	wiss_mitarbeiter text,
	start_datum int(11) DEFAULT '0' NOT NULL,
	end_datum int(11) DEFAULT '0' NOT NULL,
	start_erscheinung int(11) DEFAULT '0' NOT NULL,
	ende_erscheinung int(11) DEFAULT '0' NOT NULL,
	foerdersumme double(11,2) DEFAULT '0.00' NOT NULL,
	webseite text,	
	beschreibung_kurz text,
	beschreibung_lang text,
	downloads text,
	downloads_beschriftung text,
	medien1 text,
	bildunterschrift1 text,
	medien2 text,
	bildunterschrift2 text,
	veroeff_title text,
	veroeff_link text,
	diss text,
	anzahl_stud text,
  nachhaltigkeitsbezug_oekologisch tinyint(4) DEFAULT '0' NOT NULL,
  nachhaltigkeitsbezug_oekonomisch tinyint(4) DEFAULT '0' NOT NULL,
  nachhaltigkeitsbezug_sozial tinyint(4) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_femanagement_forschung_einrichtungen'
#
CREATE TABLE tx_femanagement_forschung_einrichtungen (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	fe_cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	
	title text,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_femanagement_forschung_einrichtungen_admins'
#
CREATE TABLE tx_femanagement_forschung_einrichtungen_admins (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	fe_cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	
	einrichtung int(11) DEFAULT '0' NOT NULL,
	username text,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_femanagement_promotionen'
#
CREATE TABLE tx_femanagement_promotionen (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	fe_cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,

	title text,
	promovend_vorname text,
	promovend_nachname text,
	promovend_email text,
	fakultaet text,
	faku_link text,
	kooperations_uni text,
	erst_betreuer text,
	zweit_betreuer text,
	start_datum int(11) DEFAULT '0' NOT NULL,
	end_datum int(11) DEFAULT '0' NOT NULL,
	beschreibung_kurz text,
	beschreibung_lang text,
	grafik text,
	bildunterschrift text,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_femanagement_personen'
#
CREATE TABLE tx_femanagement_personen (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(1) DEFAULT '0' NOT NULL,
	deleted tinyint(1) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	
	title varchar(40) DEFAULT '' NOT NULL,
	first_name varchar(50) DEFAULT '' NOT NULL,
	last_name varchar(50) DEFAULT '' NOT NULL,
	email varchar(80) DEFAULT '' NOT NULL,
	username varchar(50) DEFAULT '' NOT NULL,
	leitung tinyint(1) DEFAULT '0' NOT NULL,
	genehmigung_veroeff tinyint(1) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_femanagement_events'
#
CREATE TABLE tx_femanagement_events (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(1) DEFAULT '0' NOT NULL,
	deleted tinyint(1) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	
	title varchar(80) DEFAULT '' NOT NULL,
	subtitle varchar(80) DEFAULT '' NOT NULL,
	description longtext,
	contact text,
	street varchar(50) DEFAULT '' NOT NULL,
	city varchar(50) DEFAULT '' NOT NULL,
	zip varchar(8) DEFAULT '' NOT NULL,
	building varchar(50) DEFAULT '' NOT NULL,
	room varchar(50) DEFAULT '' NOT NULL,
	pic text,
	email_text longtext,
		
	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_femanagement_events_dates'
#
CREATE TABLE tx_femanagement_events_dates (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(1) DEFAULT '0' NOT NULL,
	deleted tinyint(1) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	
	event int(11) DEFAULT '0' NOT NULL,
	event_date int(11) DEFAULT '0' NOT NULL,
	start int(11) DEFAULT '0' NOT NULL,
	end int(11) DEFAULT '0' NOT NULL,
		
	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_femanagement_event_anmeldungen'
#
CREATE TABLE tx_femanagement_event_anmeldungen (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(1) DEFAULT '0' NOT NULL,
	deleted tinyint(1) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,

  event int(11) DEFAULT '0' NOT NULL,
  event_date int(11) DEFAULT '0' NOT NULL,
  organization varchar(80) DEFAULT '' NOT NULL,
	first_name varchar(50) DEFAULT '' NOT NULL,
	last_name varchar(50) DEFAULT '' NOT NULL,
	street varchar(50) DEFAULT '' NOT NULL,
	city varchar(50) DEFAULT '' NOT NULL,
	zip varchar(8) DEFAULT '' NOT NULL,
	link varchar(80) DEFAULT '' NOT NULL,
	email varchar(80) DEFAULT '' NOT NULL,
	phone varchar(20) DEFAULT '' NOT NULL,
  count_pt int(11) DEFAULT '0' NOT NULL,
	remarks text,
		
	PRIMARY KEY (uid),
	KEY parent (pid)
);
