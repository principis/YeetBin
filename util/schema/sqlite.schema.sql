CREATE TABLE IF NOT EXISTS "file_pastes"
(
    "id"        INTEGER NOT NULL,
    "paste_id"  INTEGER NOT NULL UNIQUE,
    "file_name" TEXT    NOT NULL,
    "file_ext"  TEXT    NULL,
    PRIMARY KEY ("id" AUTOINCREMENT),
    FOREIGN KEY ("paste_id") REFERENCES "pastes" ("id") ON UPDATE RESTRICT ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS "image_pastes"
(
    "id"       INTEGER NOT NULL,
    "paste_id" INTEGER NOT NULL UNIQUE,
    "format"   TEXT    NOT NULL,
    PRIMARY KEY ("id" AUTOINCREMENT),
    FOREIGN KEY ("paste_id") REFERENCES "pastes" ("id") ON UPDATE RESTRICT ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS "pastes"
(
    "id"    INTEGER    NOT NULL,
    "uid"   VARCHAR(8) NOT NULL UNIQUE,
    "type"  TINYINT    NOT NULL,
    "title" TEXT       NULL,
    PRIMARY KEY ("id" AUTOINCREMENT)
);

CREATE UNIQUE INDEX "uid" ON "pastes" ("uid");

CREATE TABLE IF NOT EXISTS "text_pastes"
(
    "id"       INTEGER NOT NULL,
    "paste_id" INTEGER NOT NULL UNIQUE,
    "lang"     TEXT    NOT NULL,
    "content"  TEXT    NOT NULL,
    PRIMARY KEY ("id" AUTOINCREMENT),
    FOREIGN KEY ("paste_id") REFERENCES "pastes" ("id") ON UPDATE RESTRICT ON DELETE CASCADE
);