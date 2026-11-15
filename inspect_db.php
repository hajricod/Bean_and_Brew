<?php
$db_path = __DIR__ . '/includes/database.sqlite';
try {
    $pdo = new PDO("sqlite:$db_path");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Tables:\n";
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
    while ($table = $tables->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . $table['name'] . "\n";
        echo "  Schema:\n";
        $columns = $pdo->query("PRAGMA table_info(" . $table['name'] . ")");
        while ($column = $columns->fetch(PDO::FETCH_ASSOC)) {
            echo "    - " . $column['name'] . " (" . $column['type'] . ")" . ($column['notnull'] ? " NOT NULL" : "") . ($column['pk'] ? " [PK]" : "") . "\n";
        }
        echo "\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
