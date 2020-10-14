CREATE TABLE tx_jobrouterbase_log (
	uid int(11) unsigned NOT NULL AUTO_INCREMENT,
	request_id varchar(13) DEFAULT '' NOT NULL,
	time_micro double(16,4) NOT NULL default '0.0000',
	component varchar(255) DEFAULT '' NOT NULL,
	level tinyint(1) unsigned DEFAULT '0' NOT NULL,
	message text,
	data text,

	PRIMARY KEY (uid),
	KEY request (request_id)
);
