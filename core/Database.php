<?php

namespace app\core;

use PDO;


class Database
{

    public PDO $pdo;


    private string $tbName = "";
    private array $fields = [];
    private array $types = [];

    function __construct($config)
    {
        $user =  $config['user'];
        $dsn = $config['dsn'];
        $pass = $config['pass'];

        $this->pdo = new PDO($dsn, $user, $pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    function applyMigrations()
    {
        $newMigration = [];
        $this->createMigrationTable();
        $migrations = $this->getMigrations();

        $files = scandir(Application::$ROOT_DIR . "/migrations");
        $files = array_diff($files, $migrations);

        foreach ($files as  $file) {
            # code...
            if ($file === '.' || $file === "..") continue;

            $className = pathinfo($file, PATHINFO_FILENAME);

            require_once Application::$ROOT_DIR . "/migrations/" . $file;
            $instance  = new $className();
            $this->setLog("applying migrations");
            $instance->up();
            $this->setLog("migrations applied successfully");

            $newMigration[] = $file;
        }

        if (!empty($newMigration)) {
            $this->saveMigration($newMigration);
        } {
            $this->setLog("migrations already applied");
        }
    }

    private  function createMigrationTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS migrations(
                id int AUTO_INCREMENT,
                migration varchar(255),
                time_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT pk_key PRIMARY KEY(ID)
            );
        ";
        $this->pdo->exec($sql);
    }

    private function getMigrations()
    {
        $stmt = $this->pdo->prepare(
            "SELECT migration from migrations"
        );
        $stmt->execute();
        return $stmt->fetchAll(Pdo::FETCH_COLUMN);
    }

    private function saveMigration($migration)
    {
        $values = "('" . implode("'),('", $migration) . "')";
        $sql = "INSERT INTO migrations (migration) VALUES  $values;";

        $stmt =  $this->pdo->prepare($sql);
        $stmt->execute();
        $this->setLog("migration added successfully");
    }

    private function setLog($message)
    {
        echo $message . PHP_EOL;
    }

    public function create(string $tbName)
    {
        $this->tbName = $tbName;
        return $this;
    }

    public function setField($field)
    {
        $this->fields[] = $field;
        return $this;
    }

    public function setType($type)
    {
        $this->types[] = $type;
        return $this;
    }


    public function exec()
    {
        $column = "";
        $fields = array_combine($this->fields, $this->types);
        foreach ($fields as $key => $value) {
            $column .= "$key $value,";
        }

        $sql = "CREATE TABLE IF NOT EXISTS $this->tbName( 
                {$this->tbName}_id int auto_increment,
                $column
                time_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT pk PRIMARY KEY( {$this->tbName}_id)
                );
            ";

        $this->pdo->exec($sql);

        $this->fields = [];
        $this->types = [];
    }
}
