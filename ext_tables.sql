CREATE TABLE tx_ckfaq_domain_model_records (
	title varchar(255) NOT NULL DEFAULT '',
	description text NOT NULL,
	count_helpful int(11) unsigned NOT NULL DEFAULT '0',
	count_not_helpful int(11) unsigned NOT NULL DEFAULT '0',
	category int(11) unsigned DEFAULT '0',
);

CREATE TABLE sys_category (
	slug varchar(255) DEFAULT '/' NOT NULL,

	KEY slug (slug),
);
