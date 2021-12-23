<?php
    require_once(__DIR__ . '/db_conn.php');
    require_once(__DIR__ . '/../../utils.php');
    abstract class DAO extends DB {
        protected $table_name;
        protected $primary_key;
        protected $props;
        protected $refs;

        protected function __construct($table_name, $primary_key, $props, $refs = null) {
            parent::__construct();
            $this->table_name = $this->addBackticks($table_name);
            $this->primary_key = $this->addBackticks($primary_key);
            $this->props = $props;
            $this->refs = $refs;
        }
        
        public function create($data) {
            $diff = array_diff_key($data, $this->props);
            if (count($diff) > 0) {
                Responde::badRequest("Key Error: " . array_keys($diff)[0]);
            }

            $toInsert = [];
            for ($i = 0; $i < count(array_keys($this->props)); $i += 1) {
                $key = array_keys($this->props)[$i];
                if ("`$key`" == $this->primary_key) {
                    continue;
                }

                $toInsert["`$key`"] = isset($data[$key]) ? ($this->props[$key] == 'str' ? $this->sanitize_message($data[$key]) : $data[$key]) : null;
            }

            $query = "INSERT INTO $this->table_name (" . implode(',', array_keys($toInsert)) . ") values (" . implode(',', array_fill(0, count(array_keys($toInsert)), '?')) . ");";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array_values($toInsert));

            return $this->findByPk($this->pdo->lastInsertId());
        }

        public function findAll($where = null, $include = null, $exclude = null, $orderby = null, $desc = false, $limit = null, $offset = null, $skipRefs = false) {
            $fields = $this->generateFields($include, $exclude);
            $olo = $this->generateOlo($orderby, $desc, $limit, $offset);
            $query = "SELECT $fields FROM $this->table_name" . ((!is_null($where)) ? " WHERE $where " : "") . " $olo;";

            $stmt = $this->pdo->query($query);
            $result = $stmt->fetchAll();

            $result = array_map(function ($item) { return $this->castTypes($item); }, $result);
            return $skipRefs ? $result : $this->attachRefs($result);
        }

        public function findByPk($id, $include = null, $exclude = null, $skipRefs = false) {
            $stmt = $this->pdo->query("SELECT " . $this->generateFields($include, $exclude) . " FROM $this->table_name WHERE $this->primary_key=$id;");
            $item = $stmt->fetch();
            
            if (!$item) {
                Responde::notFound();
            }

            $item = $this->castTypes($item);
            return $skipRefs ? $item : $this->attachRefs([$item])[0];
        }

        public function update($id, $data) {
            $diff = array_diff_key($data, $this->props);
            if (count($diff) > 0) {
                foreach ($diff as $key => $value) {
                    if (!in_array($key . "Id", array_keys($this->props))) {
                        Responde::badRequest("Key Error: " . array_keys($diff)[0]);
                    }
                }
            }

            $updates = $this->generateUpdate($data);
            $query = "UPDATE $this->table_name SET $updates WHERE $this->primary_key=?;";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);

            return $stmt->rowCount();
        }

        public function delete($id) {
            $query = "DELETE FROM $this->table_name WHERE $this->primary_key=?;";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);

            if ($stmt->rowCount() == 0) {
                Responde::notFound();
            } else {
                return 1;
            }

        }

        public function getPdo() {
            return $this->pdo;
        }

        // Could be changed to pass by reference, if more efficiency needed
        private function castTypes($item) {
            foreach($this->props as $prop => $type) {
                switch ($type) {
                    case 'int':
                        $item[$prop] = intval($item[$prop], 10);
                        break;
                    case 'float':
                        $item[$prop] = floatval($item[$prop]);
                        break;
                    case 'datetime':
                        $item[$prop] = strtotime($item[$prop]);
                        break;
                    case 'str':
                    default:
                        break;
                }
            }

            return $item;
        }

        // Could be changed to pass by reference, if more efficiency needed
        private function attachRefs($items) {
            if (is_null($this->refs)) {
                return $items;
            }

            foreach($items as &$item) {
                foreach($this->refs as $column => $class) {
                    if (isset($item[$column]) && is_int($item[$column])) {
                        $newColumn = rtrim($column, 'Id');
                        $item[$newColumn] = $class::getInstance()->findByPk($item[$column]);
                    }
                }
            }

            return $items;
        }

        private function generateOlo(string|null $orderby = null, bool|null $desc = false, int|null $limit = null, int|null $offset = null) {
            $result = ""; 
            
            if (!is_null($orderby)) {
                $result .= "ORDER BY " . $this->addBackticks($orderby);

                if (!is_null($desc) && $desc) {
                    $result .= " DESC";
                }
            }

            if (!is_null($limit)) {
                $result .= " LIMIT $limit";
            }

            if (!is_null($offset)) {
                $result .= " OFFSET $offset";
            }

            return $result;
        }

        private function generateFields(array $include = null, array $exclude = null) {
            if (is_null($include) && is_null($exclude)) {
                return "*";
            }

            if (!is_null($include) && !is_null($exclude)) {
                throw new Exception("May only pass exactly one of either include or exclude");
            }

            $diff = array_diff(!is_null($include) ? $include : $exclude, array_keys($this->props));
            if (count($diff) > 0) {
                throw new Exception("Key Error: " . print_r($diff));
            }

            if (!is_null($include)) {
                return implode(',', $this->addBackticks($include));
            } else {
                return implode(',', $this->addBackticks(array_diff(array_keys($this->props), $exclude)));
            }
        }

        private function generateUpdate(array $data) {
            $result = [];
            foreach ($this->props as $key => $type) {
                if (!array_key_exists($key, $this->props) || "`$key`" == $this->primary_key) {
                    continue;
                }

                if (isset($data[$key])) {
                    $value = $data[$key];
                    if ($this->props[$key] == 'str') {
                        $result[] = "`$key`=" . (isset($data[$key]) ? '"'. $this->sanitize_message($value) .'"' : "NULL");
                    } else {
                        $result[] = "`$key`=" . (isset($data[$key]) ? $value : "NULL");
                    }
                }
            }
            return implode(',', $result);
        }

        private function sanitize_message($message) {
            if (is_string($message)) {
                return htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
            }
        }

        private function addBackticks($items) {
            return is_array($items)
                ? array_map(function ($item) { return "`" . $item . "`"; }, $items)
                : "`" . $items . "`";
        }
    }
?>