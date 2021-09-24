<?php
namespace App\Infrastructure\PDO;

use PDO;
use Exception;
use PDOStatement;
use App\Infrastructure\Utility\Utilities;
use App\Infrastructure\PDO\Exception\PDOQueryException;
use App\Infrastructure\PDO\Exception\PDOAccessException;
use App\Infrastructure\PDO\Exception\PDOInsertException;
use App\Infrastructure\PDO\Exception\PDOQueryAsObjectException;

class PdoDataAccess
{
    private PDO $pdo;

    public function __construct(string $dsn, string $user, string $password)
    {
        $this->connect($dsn, $user, $password);
    }

    private function connect(string $dsn, string $user, string $password): void
    {
        $this->pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        $this->pdo->commit();
    }

    public function rollback(): void
    {
        $this->pdo->rollBack();
    }

    public function query(string $sqlToPrepare, array $params = [], bool $singleRow = null): ?array
    {
        try {
            $query = $this->callToExecute($sqlToPrepare, $params);
            if ($singleRow === true) {
                $response = $query->fetch(PDO::FETCH_ASSOC);
            } else {
                $response = $query->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            throw new PDOQueryException();
        }

        return !empty($response) ? $response : null;
    }

    public function queryAsObject(string $sqlToPrepare, array $params = [], bool $singleRow = null)
    {
        try {
            $query = $this->callToExecute($sqlToPrepare, $params);
            if ($singleRow === true) {
                $response = $query->fetch(PDO::FETCH_OBJ);
            } else {
                $response = $query->fetchAll(PDO::FETCH_OBJ);
            }
        } catch (Exception $e) {
            throw new PDOQueryAsObjectException();
        }

        return !empty($response) ? $response : null;
    }

    public function insert(string $sqlToPrepare, array $params = []): int
    {
        try {
            $this->callToExecute($sqlToPrepare, $params);
        } catch (Exception $e) {
            throw new PDOInsertException();
        }

        return $this->pdo->lastInsertId();
    }

    private function callToExecute(string $sqlToPrepare, array $params = []): PDOStatement
    {
        try {
            $params = $this->cleanInsertData($params);
            $query = $this->pdo->prepare($sqlToPrepare);

            foreach ($params as $key => $value) {
                $castedValue = Utilities::varCaster($value);
                $query->bindValue(
                    $key,
                    $castedValue,
                    $this->getParamType($value)
                );
            }

            $query->execute();

            return $query;
        } catch (Exception $e) {
            throw new PDOAccessException();
        }
    }

    public function cleanInsertData(array $arrayData): array
    {
        foreach ($arrayData as $key => $value) {
            if (!empty($value)) {
                $arrayData[$key] = $this->xssCleaner($value);
            }
        }

        return $arrayData;
    }

    private function xssCleaner(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    private function getParamType(?string $param)
    {
        $type = 0;

        if (is_numeric($param) && ctype_digit($param)) {
            $type = PDO::PARAM_INT;
        }

        if (!ctype_digit($param)) {
            $type = PDO::PARAM_STR;
        }

        return $type;
    }
}