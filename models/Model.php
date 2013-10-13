<?php
/*
Not used. Use Model.php instead, which does not require PEAR.
*/
// Base class
if (!defined('__ROOT__')) { define('__ROOT__', dirname(dirname(__FILE__))); }
class Model {
    public static $db;

	// Change database details here
    public static function initialize() {
        try{
					mysqli_connect('locahost','root','ritesh',dbname);			
			}
		   catch( PDOException $e ) {
				$log->error(" Exception while cteating PDO: ".$e->getMessage());
			
				die( "Error connecting to SQL Server" );
			}
			self::$db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
			self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

	// Make a WHERE clause from an associative array
    private static function whereClause($data) {
        $clauses = array();
        foreach ($data as $key => $value) {
            if (is_null($value) or $value==='--**--') {
                $clauses[] = sprintf('%s IS NULL', $key);
            } else if (is_array($value)) {
				// This could probably be more elegant
				$subclauses = array();
				foreach($value as $possibility) {
					if ($possibility === NULL or $possibility==='--**--') {
						$subclauses[] = sprintf('%s IS NULL', $key);
					} else if (is_numeric($possibility)) {
						$subclauses[] = sprintf('%s=%s', $key, $possibility);
					}  else {
						$subclauses[] = sprintf('%s=%s', $key, self::quote($possibility));
					}
				}
				$clauses[] = sprintf('(%s)', implode(' OR ', $subclauses));	
			} else if (is_numeric($value)) {
                $clauses[] = sprintf('%s=%s', $key, $value);
             } /*else if( is_string($value) ){
						 $subclauses[] = sprintf('%s LIKE %s', $key, self::quote($value));
			 } */ else {
                $clauses[] = sprintf('%s=%s', $key, self::quote($value));
            }
        }
        return implode(' AND ', $clauses);
    }

    public static function wherePartial($data) {
        if (count($data) == 0) {
            return ' ';
        }
        $clauses = self::whereClause($data);
        return sprintf(' AND %s', $clauses);
    }

    public static function where($data) {
        if (count($data) == 0) {
            return ' ';
        }
        $clauses = self::whereClause($data);
        return sprintf(' WHERE %s', $clauses);
    }
	
	// Make a SET clause from an associative array.
	public static function setClause($data) {
		$clauses = array();
        foreach ($data as $key => $value) {
            if (is_numeric($value)) {
                $clauses[] = sprintf('%s=%s', $key, $value);
            } else if (is_null($value) or $value === "") {
                $clauses[] = sprintf('%s = NULL', $key);
			} else {
                $clauses[] = sprintf('%s=%s', $key, self::quote($value));
            }
        }
        return implode(', ', $clauses);
	}
	
	  public static function whereFilter($data) {
        $clauses = array();
        foreach ($data as $key => $value) {
			if (is_null($value['value']) or $value['value']==='--**--') {
                $clauses[] = sprintf('%s IS NULL', $key);
            } 
			else if (is_array($value['value'])) {
				// This could probably be more elegant
				$subclauses = array();
				foreach($value['value'] as $possibility) {
					 if (is_null($possibility) or $possibility==='--**--') {
						$subclauses[] = sprintf('%s IS NULL', $key);
					}else  if (preg_match("/ *[0-9]+ *[-] *[0-9]+ */i",$possibility)) {
						$boundary = explode('-',$possibility);
						$subclauses[] .= sprintf(" %s between %d and %d ",$key,$boundary[0],$boundary[1]);
					} 
					else  if ($value['type']=='integer') {
						$subclauses[] = sprintf('%s=%s', $key, $possibility);
					}  else {
						$subclauses[] = sprintf('%s=%s', $key, self::quote($possibility));
					}
				}
				$clauses[] = sprintf('(%s)', implode(' OR ', $subclauses));	
			}
			else  if (preg_match("/ *[0-9]+ *- *[0-9]+ */i",$value['value'])) {
				$boundary = explode('-',$value['value']);
				$clauses[] .= sprintf(" %s between %d and %d ",$key,$boundary[0],$boundary[1]);
			} 
			else if ($value['type']=='integer') {
                $clauses[] = sprintf('%s=%s', $key, $value['value']);
            } /*else if( is_string($value) ){
						 $subclauses[] = sprintf('%s LIKE %s', $key, self::quote($value));
			 } */ 
			 else {
                $clauses[] = sprintf('%s=%s', $key, self::quote($value));
            }
        }
        return implode(' AND ', $clauses);
    }
	
	public static function setPartial($data) {
        if (count($data) == 0) {
            return ' ';
        }
        $clauses = self::setClause($data);
        return sprintf(', %s', $clauses);
    }

    public static function set($data) {
        if (count($data) == 0) {
            return ' ';
        }
        $clauses = self::setClause($data);
        return sprintf(' SET %s', $clauses);
    }

	// Quote strings.
    public static function quote($data) {
		//return "'".str_replace("''''","''",str_replace("'","''",$str))."'";
		//return str_replace("''''","''",str_replace("'","''",$str));
        //return self::$db->quote($str);
		if ( !isset($data) or empty($data) ) return '';
		//if ( is_numeric($data) ) return $data;

		$non_displayables = array(
			'/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
			'/%1[0-9a-f]/',             // url encoded 16-31
			'/[\x00-\x08]/',            // 00-08
			'/\x0b/',                   // 11
			'/\x0c/',                   // 12
			'/[\x0e-\x1f]/'             // 14-31
		);
		foreach ( $non_displayables as $regex )
			$data = preg_replace( $regex, '', $data );
		$data = str_replace("'", "''", $data );
		return "'$data'";
    }
    
	// Basic query
    public static function query($query) {
		//print_r($query);
        $stmt = self::$db->query($query);
     	//self::checkError($stmt);
        return $stmt;
    }
    
    
    public static function fetchOne($query) {
        $res = self::query($query);                
        return $res->fetchColumn();
    }
    
    public static function fetchRow($query) {
        $res =& self::query($query);
        
        if ($res->rowCount() === 0) {
            return NULL;
        }
        
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
	
	public static function doesExist($query) { //does a record with satisfying $query exists in respective table
        $res =& self::query($query);
        
        if ($res->rowCount() === 0) {
            return false;
        }
        return true;
    }
    
    public static function fetchAll($query) {
        $res = self::query($query);                
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function checkError($res) {
        if ($res == FALSE) {
            throw new Exception($res->errorCode() . ', ' . $res->errorInfo());
        } 
    }
	
	public static function executeProcedure($call) {
        
    }
	// CRUD
	// or, uh, CFUD
	
	public static function create($args = array(), $ignoreList = array()) {
		if (!isset(static::$table)) { throw new Exception('Table not set'); }
		
		$fields = array_keys($args);
		$values = array_values($args);
		$ignore = array_flip(array_unique($ignoreList));
		
		foreach($values as $key=>$value) {
			if ($value === NULL ) {
                $values[$key] = 'NULL';
            } else if (is_numeric($value)) {
                $values[$key] = $value;
            } else if (!isset($ignore[$fields[$key]])) {
                $values[$key] = self::quote($value);
            } else {
                $values[$key] = $value;
			}
		}
		
		$sql = sprintf('INSERT INTO %s (%s)', static::$table, implode(',', $fields));
		if (isset(static::$idCol)) $sql .= sprintf(' OUTPUT inserted.%s AS id', static::$idCol);
		$sql .= sprintf(' VALUES (%s)', implode(', ', $values));
		// print_r($sql);
		// exit();
		if (isset(static::$idCol)) {
			$rows = self::fetchAll($sql);
			return $rows[0]['id'];
		} else {
			self::query($sql);
			return null;
		}
	}

    public static function find($args = array()) {
        if (!isset(static::$table)) { throw new Exception('Table not set'); }
		
		$sql = 'SELECT * FROM ' . static::$table . self::where($args);
		if (isset(static::$order)) $sql .= ' ORDER BY ' . static::$order;
		
        return self::fetchAll($sql);  // database rows with column names as keys
    }
	
	// Several tables have natural key=>value arrangements (e.g. AssignmentTypes)
	public static function findHash($key, $value, $args = array()) {
		$rows = self::find($args);
		$out = array();
		foreach($rows as $row) {
			$out[$row[$key]] = $row[$value];
		} unset($row);
		return $out;
	}
	
	// Get one row as an associative array
	public static function findRow($args = array()) {
        $rows = self::find($args);
		if (isset($rows[0])) {
			return $rows[0];
		} else {
			return null;
		}
    }
	
	public static function findLastRow($args = array()) {
        $rows = self::find($args);
		if (isset($rows[0])) {
			$len = count($rows) - 1;
			return $rows[$len];
		} else {
			return null;
		}
    }
	
	// Get one column as an array
	public static function findCol($key, $args = array()) {
		$rows = self::find($args);
		$out = array();
		foreach($rows as $row) {
			$out[] = $row[$key];
		} unset($row);
		return $out;
	}
	
	public static function update($setArgs = array(), $whereArgs = array()) {
        if (!isset(static::$table)) { throw new Exception('Table not set'); }
		if (count($setArgs) == 0) return;
        self::query('UPDATE ' . static::$table . self::set($setArgs) . self::where($whereArgs));
    }
	
	public static function delete($args = array()) {
        if (!isset(static::$table)) { throw new Exception('Table not set'); }
        self::query('DELETE FROM ' . static::$table . self::where($args));
    }
}
Model::initialize();
?>