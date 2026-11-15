<?php
$db = new PDO('sqlite:includes/database.sqlite');
$tables = $db->query('SELECT name FROM sqlite_master WHERE type="table"');

while ($table = $tables->fetch(PDO::FETCH_ASSOC)) {
    echo "Table: " . $table['name'] . "\n";
    $columns = $db->query("PRAGMA table_info(" . $table['name'] . ")");
    while ($column = $columns->fetch(PDO::FETCH_ASSOC)) {
        echo "  " . $column['name'] . " (" . $column['type'] . ")\n";
    }
    echo "\n";
}
?>