CREATE TABLE messages (
  message_id int(11) NOT NULL AUTO_INCREMENT,
  conversation_id int(11) DEFAULT NULL,
  user_id int(11) DEFAULT NULL,
  body text COLLATE utf8_unicode_ci,
  created datetime DEFAULT NULL,
  PRIMARY KEY (message_id)
) 