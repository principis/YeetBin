CREATE TABLE IF NOT EXISTS `pastes`
(
    `id`    int        NOT NULL AUTO_INCREMENT,
    `uid`   varchar(8) NOT NULL,
    `type`  tinyint(4) NOT NULL,
    `title` text DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`uid`)
);

CREATE TABLE IF NOT EXISTS `file_pastes`
(
    `id`        int  NOT NULL AUTO_INCREMENT,
    `paste_id`  int  NOT NULL,
    `file_name` text NOT NULL,
    `file_ext`  text DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY (`paste_id`),
    FOREIGN KEY (`paste_id`) REFERENCES `pastes` (`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `image_pastes`
(
    `id`       int  NOT NULL AUTO_INCREMENT,
    `paste_id` int  NOT NULL,
    `format`   text NOT NULL,
    PRIMARY KEY (`id`),
    KEY (`paste_id`),
    FOREIGN KEY (`paste_id`) REFERENCES `pastes` (`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `text_pastes`
(
    `id`       int(11) NOT NULL AUTO_INCREMENT,
    `paste_id` int(11) NOT NULL,
    `lang`     text    NOT NULL,
    `content`  text    NOT NULL,
    PRIMARY KEY (`id`),
    KEY (`paste_id`),
    FOREIGN KEY (`paste_id`) REFERENCES `pastes` (`id`) ON DELETE CASCADE
);