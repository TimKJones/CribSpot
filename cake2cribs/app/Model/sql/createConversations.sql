CREATE TABLE conversations (
  conversation_id int(11) NOT NULL AUTO_INCREMENT,
  listing_id int(11) DEFAULT NULL,
  participant1_id int(11) DEFAULT NULL,
  visible1 tinyint(1) NOT NULL DEFAULT '1',
  participant2_id int(11) DEFAULT NULL,
  visible2 tinyint(1) NOT NULL DEFAULT '1',
  title varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  last_message_id int(11) DEFAULT NULL,
  created datetime DEFAULT NULL,
  modified datetime DEFAULT NULL,
  PRIMARY KEY (conversation_id)
)
