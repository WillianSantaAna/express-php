<?php
class connection
{
    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private static function instance()
    {
        try {
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            $pdo_options[PDO::ATTR_EMULATE_PREPARES] = FALSE;
            $pdo_options[PDO::ATTR_STRINGIFY_FETCHES] = FALSE;
            return new PDO('mysql:host=localhost:3306;dbname=dbname', 'user', 'password', $pdo_options);
        } catch (PDOException $e) {
            return $e;
        }
    }

    public static function query($sql, $params = [])
    {
        try {
            $instance = self::instance();
            $request = $instance->prepare($sql);
            if ($request) {
                $request->execute($params);

                if ($lastId = $instance->lastInsertId()) {
                    return $lastId;
                }

                return $request->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            return $e;
        }
    }
}
