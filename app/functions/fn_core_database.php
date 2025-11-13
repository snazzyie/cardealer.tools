<?php
/**
 * Core Database Functions
 *
 * PDO-based database helper functions for CRUD operations
 * All functions use prepared statements for security
 */

/**
 * Get PDO database connection
 *
 * @return PDO
 */
function fn_core_database_connection() {
    $config = require BASE_PATH . 'config.php';

    $username = $config['database']['username'];
    $password = $config['database']['password'];

    $dsn = 'mysql:' . http_build_query($config['database'], '', ';');

    try {
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }
}

/**
 * Fetch multiple rows from database
 *
 * @param string $query SQL query with placeholders
 * @param array $params Parameters for prepared statement
 * @return array Array of rows
 */
function fn_core_database_rows($query, $params = []) {
    $pdo = fn_core_database_connection();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Fetch single row from database
 *
 * @param string $query SQL query with placeholders
 * @param array $params Parameters for prepared statement
 * @return array|false Single row or false if not found
 */
function fn_core_database_row($query, $params = []) {
    $pdo = fn_core_database_connection();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetch();
}

/**
 * Insert row and redirect
 *
 * @param string $query SQL INSERT query
 * @param array $params Parameters for prepared statement
 * @param string $return Redirect URL after insert
 * @return int Last insert ID
 */
function fn_core_insert_row($query, $params, $return) {
    $pdo = fn_core_database_connection();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $lastId = $pdo->lastInsertId();

    header("Location: $return");
    exit;
}

/**
 * Insert row without redirect
 *
 * @param string $query SQL INSERT query
 * @param array $params Parameters for prepared statement
 * @return int Last insert ID
 */
function fn_core_insert_row_no_redirect($query, $params) {
    $pdo = fn_core_database_connection();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $pdo->lastInsertId();
}

/**
 * Update row and redirect
 *
 * @param string $query SQL UPDATE query
 * @param array $params Parameters for prepared statement
 * @param string $return Redirect URL after update
 */
function fn_core_edit_row($query, $params, $return) {
    $pdo = fn_core_database_connection();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    header("Location: $return");
    exit;
}

/**
 * Update row without redirect
 *
 * @param string $query SQL UPDATE query
 * @param array $params Parameters for prepared statement
 * @return int Number of affected rows
 */
function fn_core_edit_row_no_redirect($query, $params) {
    $pdo = fn_core_database_connection();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->rowCount();
}

/**
 * Delete row and redirect
 *
 * @param string $query SQL DELETE query
 * @param array $params Parameters for prepared statement
 * @param string $return Redirect URL after delete
 */
function fn_core_delete_row($query, $params, $return) {
    $pdo = fn_core_database_connection();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    header("Location: $return");
    exit;
}

/**
 * Delete row without redirect
 *
 * @param string $query SQL DELETE query
 * @param array $params Parameters for prepared statement
 * @return int Number of affected rows
 */
function fn_core_delete_row_no_redirect($query, $params) {
    $pdo = fn_core_database_connection();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->rowCount();
}

/**
 * Count all rows in a table
 *
 * @param string $table Table name
 * @return int Total count
 */
function fn_core_count_rows($table) {
    $query = "SELECT COUNT(*) as total FROM $table";
    $result = fn_core_database_row($query);
    return $result['total'];
}

/**
 * Count rows in a table by company_id
 *
 * @param string $table Table name
 * @param int $company_id Company ID
 * @return int Total count for company
 */
function fn_core_count_rows_company($table, $company_id) {
    $query = "SELECT COUNT(*) as total FROM $table WHERE company_id = :company_id";
    $result = fn_core_database_row($query, ['company_id' => $company_id]);
    return $result['total'];
}

/**
 * Execute raw SQL query (use with caution)
 *
 * @param string $query SQL query
 * @param array $params Parameters for prepared statement
 * @return bool Success status
 */
function fn_core_execute_query($query, $params = []) {
    $pdo = fn_core_database_connection();
    $stmt = $pdo->prepare($query);
    return $stmt->execute($params);
}

/**
 * Execute database query (alias for fn_core_execute_query)
 *
 * @param string $query SQL query
 * @param array $params Parameters for prepared statement
 * @return bool Success status
 */
function fn_core_database_query($query, $params = []) {
    return fn_core_execute_query($query, $params);
}

/**
 * Create row in table (wrapper for fn_core_insert_row_no_redirect)
 *
 * @param string $table Table name
 * @param array $data Associative array of column => value
 * @param string $primaryKey Primary key column name (default: 'id')
 * @return int|false Last insert ID or false
 */
function fn_core_create_row($table, $data, $primaryKey = 'id') {
    // Build column names and placeholders
    $columns = array_keys($data);
    $placeholders = array_fill(0, count($columns), '?');

    $query = "INSERT INTO $table (" . implode(', ', $columns) . ")
              VALUES (" . implode(', ', $placeholders) . ")";

    return fn_core_insert_row_no_redirect($query, array_values($data));
}

/**
 * Update row in table (wrapper for fn_core_edit_row_no_redirect)
 *
 * @param string $table Table name
 * @param int $id Primary key value
 * @param array $data Associative array of column => value
 * @param string $primaryKey Primary key column name (default: 'id')
 * @return int Number of affected rows
 */
function fn_core_update_row($table, $id, $data, $primaryKey = 'id') {
    // Build SET clause
    $setParts = [];
    foreach (array_keys($data) as $column) {
        $setParts[] = "$column = ?";
    }

    $query = "UPDATE $table SET " . implode(', ', $setParts) .
             " WHERE $primaryKey = ?";

    $params = array_values($data);
    $params[] = $id;

    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Begin database transaction
 *
 * @return PDO
 */
function fn_core_begin_transaction() {
    $pdo = fn_core_database_connection();
    $pdo->beginTransaction();
    return $pdo;
}

/**
 * Commit database transaction
 *
 * @param PDO $pdo PDO instance
 */
function fn_core_commit_transaction($pdo) {
    $pdo->commit();
}

/**
 * Rollback database transaction
 *
 * @param PDO $pdo PDO instance
 */
function fn_core_rollback_transaction($pdo) {
    $pdo->rollBack();
}
