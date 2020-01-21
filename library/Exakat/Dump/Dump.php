<?php

namespace Exakat\Dump;

use Exakat\Analyzer\Analyzer;
use Exakat\Query\Query;
use Sqlite3;
use Exakat\Graph\Helpers\GraphResults as Results;

class Dump {
    const READ  = 1;
    const INIT  = 0;
    
    protected $project        = null;
    protected $phpexcutable   = null;
    protected $sqlite         = null;
    private $sqliteFileFinal  = '';
    
    protected $tablesList = array();

    function __construct(string $path, int $init = self::READ, string $project = '', string $phpexecutable = '') {
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

    private function reuse() {
        copy($this->sqliteFileFinal, $this->sqliteFile);
        $this->sqlite = new \Sqlite3($this->sqliteFile, \SQLITE3_OPEN_READWRITE);
        $this->sqlite->busyTimeout(\SQLITE3_BUSY_TIMEOUT);
        
        $this->initTablesList();
    }

    private function init() {
        $this->sqlite = new Sqlite3($this->sqliteFile, \SQLITE3_OPEN_READWRITE | \SQLITE3_OPEN_CREATE);
        $this->sqlite->busyTimeout(\SQLITE3_BUSY_TIMEOUT);

        $this->initDump();
    }

    private function openForRead() {
        if (file_exists($this->sqliteFile)) {
            unlink($this->sqliteFile);
            display('Removing old .dump.sqlite');
        }

        $this->sqlite = new \Sqlite3($this->sqliteFileFinal,  \SQLITE3_OPEN_READONLY);
        $this->sqlite->busyTimeout(\SQLITE3_BUSY_TIMEOUT);
        
        $this->initTablesList();
    }

    private function initTablesList() : void {
        $res = $this->sqlite->query("SELECT name FROM sqlite_master WHERE type ='table' AND name NOT LIKE 'sqlite_%'");
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $this->tablesList[] = $row['name'];
        }
    }

    static function factory(string $path, int $init = self::READ) : self {
        return new Dump1($path, $init);
    }

    private function initDump() {
        $query = <<<'SQL'
CREATE TABLE themas (  id    INTEGER PRIMARY KEY AUTOINCREMENT,
                       thema STRING,
                       CONSTRAINT "themas" UNIQUE (thema) ON CONFLICT IGNORE
                    )
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE results (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                        fullcode STRING,
                        file STRING,
                        line INTEGER,
                        namespace STRING,
                        class STRING,
                        function STRING,
                        analyzer STRING,
                        severity STRING
                     )
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE resultsCounts ( id INTEGER PRIMARY KEY AUTOINCREMENT,
                             analyzer STRING,
                             count INTEGER DEFAULT -6,
                             CONSTRAINT "analyzers" UNIQUE (analyzer) ON CONFLICT REPLACE
                           )
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE hashAnalyzer ( id INTEGER PRIMARY KEY,
                            analyzer STRING,
                            key STRING UNIQUE,
                            value STRING
                          );
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE hashResults ( id INTEGER PRIMARY KEY AUTOINCREMENT,
                            name STRING,
                            key STRING,
                            value STRING
                          );
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE classChanges (  
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    changeType   STRING,
    name         STRING,
    parentClass  STRING,
    parentValue  STRING,
    childClass   STRING,
    childValue   STRING
                    )
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE filesDependencies ( id INTEGER PRIMARY KEY AUTOINCREMENT,
                                 including STRING,
                                 included STRING,
                                 type STRING
                                )
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE classesDependencies ( id INTEGER PRIMARY KEY AUTOINCREMENT,
                                   including STRING,
                                   including_name STRING,
                                   including_type STRING,
                                   included STRING,
                                   included_name STRING,
                                   included_type STRING,
                                   type STRING
                                  )
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE atomsCounts (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                            atom STRING,
                            count INTEGER
                         )
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE phpStructures (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                              name STRING,
                              type STRING,
                              count INTEGER
)
SQL;
        $this->sqlite->query($query);

        // Name spaces
        $query = <<<'SQL'
CREATE TABLE namespaces (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                           namespace STRING
                        )
SQL;
        $this->sqlite->query($query);
        $this->sqlite->query("INSERT INTO namespaces VALUES (1, '\\')");

        $query = <<<'SQL'
CREATE TABLE cit (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name STRING,
                    namespaceId INTEGER DEFAULT 1,
                    type STRING,
                    abstract INTEGER,
                    final INTEGER,
                    phpdoc STRING,
                    begin INTEGER,
                    end INTEGER,
                    file INTEGER,
                    line INTEGER,
                    extends STRING DEFAULT ""
                  )
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE cit_implements (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                               implementing INTEGER,
                               implements STRING,
                               type    STRING
                            )
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE methods (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                        method INTEGER,
                        citId INTEGER,
                        static INTEGER,
                        final INTEGER,
                        abstract INTEGER,
                        visibility STRING,
                        returntype STRING,
                        phpdoc STRING,
                        begin INTEGER,
                        end INTEGER
                     )
SQL;
        $this->sqlite->query($query);
        
        $query = <<<'SQL'
CREATE TABLE arguments (id INTEGER PRIMARY KEY AUTOINCREMENT,
                        name STRING,
                        citId INTEGER,
                        methodId INTEGER,
                        rank INTEGER,
                        reference INTEGER,
                        variadic INTEGER,
                        init STRING,
                        line INTEGER,
                        typehint STRING
                     )
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE properties (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                           property INTEGER,
                           citId INTEGER,
                           visibility STRING,
                           static INTEGER,
                           phpdoc STRING,
                           value STRING
                           )
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE classconstants ( id INTEGER PRIMARY KEY AUTOINCREMENT,
                              constant INTEGER,
                              citId INTEGER,
                              visibility STRING,
                              phpdoc STRING,
                              value STRING
                            )
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE constants (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                          constant INTEGER,
                          namespaceId INTEGER,
                          file STRING,
                          value STRING,
                          phpdoc STRING,
                          type STRING
                       )
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE functions (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                          function STRING,
                          type STRING,
                          namespaceId INTEGER,
                          returntype STRING,
                          reference INTEGER,
                          file STRING,
                          phpdoc STRING,
                          begin INTEGER,
                          end INTEGER,
                          line INTEGER,
                          CONSTRAINT "unique" UNIQUE (function, line)
)
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE readability ( id      INTEGER PRIMARY KEY AUTOINCREMENT,
                           name    STRING,
                           type    STRING,
                           tokens  INTEGER,
                           expressions INTEGER,
                           file        STRING
                         )
SQL;
        $this->sqlite->query($query);


        $query = <<<'SQL'
CREATE TABLE variables (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                          variable STRING,
                          type STRING
                       )
SQL;
        $this->sqlite->query($query);

        $query = <<<'SQL'
CREATE TABLE globalVariables ( id INTEGER PRIMARY KEY AUTOINCREMENT,
                               variable STRING,
                               file STRING,
                               line INTEGER,
                               isRead INTEGER,
                               isModified INTEGER,
                               type STRING
                             )
SQL;
        $this->sqlite->query($query);

        $this->collectDatastore();
        $this->initTablesList();

        $time   = time();
        try {
            $id     = random_int(0, PHP_INT_MAX);
        } catch (\Throwable $e) {
            die("Couldn't generate an id for the current dump file. Aborting");
        }

        if (file_exists($this->sqliteFilePrevious)) {
            $sqliteOld = new \Sqlite3($this->sqliteFilePrevious);
            $sqliteOld->busyTimeout(\SQLITE3_BUSY_TIMEOUT);

            $presence = $sqliteOld->querySingle('SELECT count(*) FROM sqlite_master WHERE type="table" AND name="hash"');
            if ($presence == 1) {
                $serial = $sqliteOld->querySingle('SELECT value FROM hash WHERE key="dump_serial"') + 1;
            } else {
                $serial = 0;
            }
        } else {
            $serial = 1;
        }
        
        $toDump = array(array('', 'dump_time',   $time),
                        array('', 'dump_id',     $id),
                        array('', 'dump_serial', $serial),
                        );

        $this->storeInTable('hash', $toDump);
        display('Inited tables');
    }

    private function collectDatastore() {
        $tables = array('analyzed',
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

    public function removeResults(array $analyzers) : void {
        $classesList = makeList($analyzers);

        $this->sqlite->query("DELETE FROM results WHERE analyzer IN ($classesList)");
        $this->sqlite->query("DELETE FROM resultsCounts WHERE analyzer IN ($classesList)");
    }
    
    public function addResults(array $toDump) : array {
        if (empty($toDump)) {
            return array();
        }

        $chunks = array_chunk($toDump, 12);
        foreach($chunks as $chunk) {
            foreach($chunk as &$c) {
                $c = array_map(array($this->sqlite, 'escapeString'), $c);
                $c = '(NULL, \''.implode('\', \'', $c).'\')';
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

        $this->sqlite->query("INSERT INTO resultsCounts (\"id\", \"analyzer\", \"count\") VALUES ".implode(', ', $query));
        
        // Pretty sneaaaaky, as it doesn't count the stored rows
        return $return;
    }

    public function getTableCount(string $table) : int {
        return $this->sqlite->querySingle('SELECT count(*) FROM '.$table);
    }

    public function collectTables($tables) {
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

    public function close() {
        $this->sqlite->query('REPLACE INTO resultsCounts ("id", "analyzer", "count") VALUES (NULL, \'Project/Dump\', 1)');
        
        rename($this->sqliteFile, $this->sqliteFileFinal);
    }

    function storeInTable($table, Iterable $results) : int {
        $values = array();
        $total  = 0;
        foreach($results as $change) {
            $values[] = str_replace("(''", '(null', '('.makeList(array_map(array($this->sqlite, 'escapeString'), $change), "'").')');
            // str_replace is an ugly hack for id, which should be null.
            ++$total;
        }
        

        if (!empty($values)) {
            $chunks = array_chunk($values, 490);
            foreach($chunks as $chunk) {
                $query = 'INSERT INTO '.$table.' VALUES ' . implode(', ', $chunk);
                $this->sqlite->query($query);
            }
        }

        return count($values);
    }

    function storeQueries(array $queries) : int {
        foreach($queries as $query) {
            $this->sqlite->query($query);
        }

        return count($queries);
    }
}

?>