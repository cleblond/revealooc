<?php

// The SQL to uninstall this tool
$DATABASE_UNINSTALL = array(
"drop table if exists {$CFG->dbprefix}slidedecks"
);

// The SQL to create the tables if they don't exist
$DATABASE_INSTALL = array(
array( "{$CFG->dbprefix}slidedecks",
"create table {$CFG->dbprefix}slidedecks (
    id     INTEGER NOT NULL,
    user_id     INTEGER NOT NULL,
    slides       INTEGER NOT NULL,
    updated_at  DATETIME NOT NULL,
    UNIQUE(id)
) ENGINE = InnoDB DEFAULT CHARSET=utf8")
);

