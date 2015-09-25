<?php
namespace Brave\Helpers;

use Brave\App;
use PDO;

class DB extends PDO
{
    protected static $instances = array();

    protected static $config = [
         'type' => 'mysql',
         'host' => 'localhost',
         'dbname' => 'test',
         'username' => 'root',
         'password' => '',
         'charset' => 'utf8',
    ];

    public static function connect($config = false)
    {
        $config = self::$config = !$config ? App::$config['db'] : $config;
        $id = "{$config['type']}{$config['host']}{$config['dbname']}{$config['username']}{$config['password']}";
        if (isset(self::$instances[$id])) {
            return self::$instances[$id];
        }

        try {
            $instance = new DB("{$config['type']}:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
            $instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instances[$id] = $instance;

            return $instance;
        } catch (PDOException $e) {
            //in the event of an error record the error to ErrorLog.html
            Log::error($e);
        }
    }


    /**
     * @param $table
     * @param $data
     * @return string
     */
    public function insert($table, $data)
    {

        ksort($data);

        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $stmt = $this->prepare("INSERT INTO $table ($fieldNames) VALUES ($fieldValues)");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $this->lastInsertId();
    }

    /**
     * @param $table
     * @param $where
     * @param int $limit
     * @return int
     */
    public function delete($table, $where, $limit = 1)
    {
        ksort($where);

        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :$key";
            } else {
                $whereDetails .= " AND $key = :$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');

        //if limit is a number use a limit on the query
        if (is_numeric($limit)) {
            $uselimit = "LIMIT $limit";
        }

        $stmt = $this->prepare("DELETE FROM $table WHERE $whereDetails $uselimit");

        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }


    /**
     * @param $table
     * @param $data
     * @param $where
     * @return int
     */
    public function update($table, $data, $where)
    {
        ksort($data);

        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = :field_$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :where_$key";
            } else {
                $whereDetails .= " AND $key = :where_$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');

        $stmt = $this->prepare("UPDATE $table SET $fieldDetails WHERE $whereDetails");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":field_$key", $value);
        }

        foreach ($where as $key => $value) {
            $stmt->bindValue(":where_$key", $value);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }


    /**
     * @param $sql
     * @param array $array
     * @param int $fetchMode
     * @param string $class
     * @return mixed
     */
    public function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC, $class = '')
    {
        $stmt = $this->prepare($sql);

        foreach ($array as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue("$key", $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue("$key", $value);
            }
        }

        $stmt->execute();

        if ($fetchMode === PDO::FETCH_CLASS) {
            return $stmt->fetchAll($fetchMode, $class);
        } else {
            return $stmt->fetchAll($fetchMode);
        }
    }

    public function findByPk($tableName, $pkValue)
    {
        $primaryKey = 'id';
        $sql = 'select * from ' . $tableName . '  where ' . $primaryKey . '=:pkValue ';
        $r = $this->select($sql, [
            ':pkValue' => $pkValue,
        ]);
        if (!empty($r)) {
            return $r[0];
        }
        return null;
    }

    /**
     * @param $table
     * @return int
     */
    public function truncate($table)
    {
        return $this->exec("TRUNCATE TABLE $table");
    }

}
