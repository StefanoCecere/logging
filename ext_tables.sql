
CREATE TABLE tx_logging_domain_model_session (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,

	fe_user int(11) unsigned DEFAULT '0' NOT NULL,
	site_id int(11) unsigned DEFAULT '0' NOT NULL,
	page_id int(11) unsigned DEFAULT '0' NOT NULL,
    ip_address char(15) DEFAULT '' NOT NULL,

	PRIMARY KEY (uid),
	KEY fe_user (fe_user)
) ENGINE = InnoDB;

CREATE TABLE tx_logging_domain_model_event (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,

	session_id int(11) unsigned DEFAULT '0' NOT NULL,
	be_user int(11) unsigned DEFAULT '0' NOT NULL,
	fe_user int(11) unsigned DEFAULT '0' NOT NULL,
	site_id int(11) unsigned DEFAULT '0' NOT NULL,
    action varchar(16) DEFAULT '' NOT NULL,
    tablename varchar(32) DEFAULT '' NOT NULL,
	record_id int(11) unsigned DEFAULT '0' NOT NULL,
    note varchar(255) DEFAULT '' NOT NULL,
    http_referer varchar(255) DEFAULT '' NOT NULL,
    params mediumtext NOT NULL,
    ip_address char(15) DEFAULT '' NOT NULL,

	PRIMARY KEY (uid),
	KEY sel1 (session_id),
	KEY sel2 (action,fe_user)
) ENGINE = InnoDB;
