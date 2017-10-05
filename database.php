<?php

// The SQL to uninstall this tool
$DATABASE_UNINSTALL = array(
"drop table if exists {$CFG->dbprefix}eo_slidedecks"
);

// The SQL to create the tables if they don't exist
$DATABASE_INSTALL = array(
array( "{$CFG->dbprefix}eo_slidedecks",
"create table {$CFG->dbprefix}eo_slidedecks (
    id     INTEGER NOT NULL,
    user_id     INTEGER NOT NULL,
    description        TEXT NOT NULL,
    slides       TEXT NOT NULL,
    options      TEXT NOT NULL,
    updated_at  DATETIME NOT NULL,
    UNIQUE(id)
) ENGINE = InnoDB DEFAULT CHARSET=utf8")
);

