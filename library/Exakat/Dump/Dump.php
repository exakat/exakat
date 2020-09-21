<?php declare(strict_types = 1);

namespace Exakat\Dump;

use Sqlite3;
use Exakat\Reports\Helpers\Results;

abstract class Dump {
    const READ  = 1;
    const INIT  = 0;

    protected $project          = null;
    protected $phpexcutable     = null;
    protected $sqlite           = null;
    protected $sqliteFileFinal    = '';
    protected $sqliteFile         = null;
    protected $sqliteFilePrevious = null;

    protected $tablesList = array();

    public function __construct(string $path, int $init = self::READ, string $project = '', string $phpexecutable = '') {
        $this->sqliteFileFinal    = $path;
        $this->sqliteFile         = str_replace('/dump', '/.dump', $this->sqliteFileFinal);
        $this->sqliteFilePrevious = str_replace('/dump', '/dump-1', $this->sqliteFileFinal);

        $this->project        = $project;
        $this->phpexcutable   = $phpexecutable;

        if ($init === self::INIT) {
            if (file_exists($this->sqliteFile)) {
                unlink($this->sqliteFile);
                display('Removing old .dump.sqlite');
            }

            if (file_exists($this->sqliteFileFinal)) {
                $this->reuse();
            } else {
                $this->init();
            }
        } else {
            $this->openForRead();
        }
    }

    private function reuse(): void {
        copy($this->sqliteFileFinal, $this->sqliteFile);
        $this->sqlite = new \Sqlite3($this->sqliteFile, \SQLITE3_OPEN_READWRITE);
        $this->sqlite->busyTimeout(\SQLITE3_BUSY_TIMEOUT);

        $this->initTablesList();
    }

    private function init(): void {
        $this->sqlite = new Sqlite3($this->sqliteFile, \SQLITE3_OPEN_READWRITE | \SQLITE3_OPEN_CREATE);
        $this->sqlite->busyTimeout(\SQLITE3_BUSY_TIMEOUT);

        $this->initDump();
    }

    private function openForRead(): void {
        if (file_exists($this->sqliteFile)) {
            unlink($this->sqliteFile);
            display('Removing old .dump.sqlite');
        }

        $this->sqlite = new \Sqlite3($this->sqliteFileFinal,  \SQLITE3_OPEN_READONLY);
        $this->sqlite->busyTimeout(\SQLITE3_BUSY_TIMEOUT);

        $this->initTablesList();
    }

    protected function initTablesList(): void {
        $res = $this->sqlite->query("SELECT name FROM sqlite_master WHERE type ='table' AND name NOT LIKE 'sqlite_%'");
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $this->tablesList[] = $row['name'];
        }
    }

    public static function factory(string $path, int $init = self::READ): self {
        return new Dump2($path, $init);
    }

    protected function collectDatastore(): void {
        $tables = array(//'analyzed',
                        'compilation52',
                        'compilation53',
                        'compilation54',
                        'compilation55',
                        'compilation56',
                        'compilation70',
                        'compilation71',
                        'compilation72',
                        'compilation73',
                        'compilation74',
                        'compilation80',
                        'composer',
                        'configFiles',
                        'externallibraries',
                        'files',
                        'hash',
                        'ignoredFiles',
                        'shortopentag',
                        'tokenCounts',
                        'linediff',
                        );
        $this->collectTables($tables);
    }

    public function removeResults(array $analyzers): void {
        $classesList = makeList($analyzers);

        $this->sqlite->query("DELETE FROM results WHERE analyzer IN ($classesList)");
        $this->sqlite->query("DELETE FROM resultsCounts WHERE analyzer IN ($classesList)");
    }

    public function addResults(array $toDump): array {
        if (empty($toDump)) {
            return array();
        }

        $chunks = array_chunk($toDump, SQLITE_CHUNK_SIZE);
        foreach($chunks as $chunk) {
            foreach($chunk as &$c) {
                assert(count($c) === 8, 'Wrong column count for results : ' . print_r($c, true));
                $c = array_map(array($this->sqlite, 'escapeString'), $c);
                $c = '(NULL, \'' . implode('\', \'', $c) . '\')';
            }
            $sql = 'REPLACE INTO results ("id", "fullcode", "file", "line", "namespace", "class", "function", "analyzer", "severity") VALUES ' . implode(', ', $chunk);
            $this->sqlite->query($sql);
        }

        $return = array_column($toDump, 6);
        $return = array_count_values($return);

        $query = array();
        foreach($return as $k => $v) {
            $query[] = "(NULL, '$k', $v)";
        }

        $this->sqlite->query('INSERT INTO resultsCounts ("id", "analyzer", "count") VALUES ' . implode(', ', $query));

        // Pretty sneaaaaky, as it doesn't count the stored rows
        return $return;
    }

    public function addEmptyResults(array $toDump): void {
        $chunks = array_chunk($toDump, SQLITE_CHUNK_SIZE);
        foreach($chunks as $chunk) {
            foreach($chunk as &$c) {
                $c = "(NULL, '" . $c . "', 0)";
            }
            $sql = 'REPLACE INTO resultsCounts ("id", "analyzer", "count") VALUES ' . implode(', ', $chunk);
            $this->sqlite->query($sql);
        }
    }

    public function getTableCount(string $table): int {
        return $this->sqlite->querySingle('SELECT count(*) FROM ' . $table);
    }

    public function collectTables(array $tables): void {
        $config = exakat('config');
        $this->sqlite->query("ATTACH '{$config->datastore}' AS datastore");

        $query = "SELECT name, sql FROM datastore.sqlite_master WHERE type='table' AND name in ('" . implode("', '", $tables) . "');";
        $existingTables = $this->sqlite->query($query);

        while($table = $existingTables->fetchArray(\SQLITE3_ASSOC)) {
            $createTable = $table['sql'];
            $createTable = str_replace('CREATE TABLE ', 'CREATE TABLE IF NOT EXISTS ', $createTable);

            $this->sqlite->query($createTable);
            $this->sqlite->query('REPLACE INTO ' . $table['name'] . ' SELECT * FROM datastore.' . $table['name']);
        }

        $this->sqlite->query('DETACH datastore');
    }

    public function close(): void {
        $this->sqlite->query('REPLACE INTO resultsCounts ("id", "analyzer", "count") VALUES (NULL, \'Project/Dump\', 1)');

        rename($this->sqliteFile, $this->sqliteFileFinal);
    }

    public function cleanTable(string $table): void {
        $query = 'DELETE FROM ' . $table;
        $this->sqlite->query($query);
    }

    public function storeInTable(string $table, Iterable $results): int {
        $values = array();
        $total  = 0;
        foreach($results as $change) {
            $first = array_shift($change);
            $values[] = '(' . (empty($first) ? 'null' : $first) . ',' . makeList(array_map(array($this->sqlite, 'escapeString'), $change), "'" ) . ')';
            // str_replace is an ugly hack for id, which should be null.
            ++$total;
        }

        if (!empty($values)) {
            $chunks = array_chunk($values, SQLITE_CHUNK_SIZE);
            foreach($chunks as $chunk) {
                $query = 'REPLACE INTO ' . $table . ' VALUES ' . implode(', ', $chunk);
                $this->sqlite->query($query);
            }
        }

        return count($values);
    }

    public function storeQueries(array $queries): int {
        $this->sqlite->lastErrorCode();
        foreach($queries as $query) {
            $res = $this->sqlite->query($query);
            if ($this->sqlite->lastErrorCode()) {
                print  $query . PHP_EOL . PHP_EOL;
            }
        }

        return count($queries);
    }

    public function fetchHashResults(string $key): Results {
        return new Results();
    }
}

?>