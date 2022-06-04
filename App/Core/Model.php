<?php namespace App\Core;
use PDO;
class Model
{
    protected $pdo;
    protected $tabel_name;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    protected function setTableName($tabel_name)
    {
        $this->tabel_name = $tabel_name;
    }

    private function getValues(array $array)
    {
        $array_keys = array_keys($array);
        $array_map = array_map(fn($item) => "$item = :$item", $array_keys);
        return $values = implode(',', $array_map);
    }

    protected function insert(array $data)
    {
        $values = $this->getValues($data);
        $query = "INSERT INTO {$this->tabel_name} SET {$values}";
        $insert = $this->pdo->prepare($query);
        return $insert->execute($data);
    }

    protected function select($where = null, $data = [])
    {
        if (isset($where) && !empty($where)) {
            $select = $this->pdo->prepare("SELECT * FROM {$this->tabel_name} WHERE {$where}");
            $select->execute($data);
            if ($select->rowCount() > 1)
                return $select->fetchAll(PDO::FETCH_ASSOC);
            elseif ($select->rowCount() == 1)
                return $select->fetch(PDO::FETCH_ASSOC);
            else
                return false;
        } else {
            $select = $this->pdo->prepare("SELECT * FROM {$this->tabel_name}");
            $select->execute();
            if ($select->rowCount() > 1) {
                $row = $select->fetchAll(PDO::FETCH_ASSOC);
                return $row;
            } elseif ($select->rowCount() == 1) {
                return $select->fetch(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        }
    }

    protected function delete($where = null, array $data)
    {
        $delete = $this->pdo->prepare("DELETE FROM {$this->tabel_name} WHERE {$where}");
        return $delete->execute($data);
    }

    protected function update(array $data, $where, array $where_data)
    {
        $values = $this->getValues($data);

        $query = "UPDATE {$this->tabel_name} SET {$values} WHERE {$where}";
        $update = $this->pdo->prepare($query);

        $data = array_merge($data, $where_data);

        return $update->execute($data);
    }

}