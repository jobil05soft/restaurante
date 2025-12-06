<?php
//Responsavel para a gestão da base de dados

namespace core\classes;

use Exception;
use PDO;
use PDOException;

class Database
{

    private $ligacao;

    //============================================================
    private function ligar()
    {
        //ligar a base de dados
        $this->ligacao = new PDO(
            'mysql:' .
                'host=' . MYSQL_SERVER . ';' .
                'dbname=' . MYSQL_DATABASE . ';' .
                'charset=' . MYSQL_CHARSET . ';',
            MYSQL_USER,
            MYSQL_PASS,
            array(PDO::ATTR_PERSISTENT => true)
        );

        //debug
        $this->ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }
    //============================================================
    private function desligar()
    {
        //desligar a conexaoo com base de dados
        $this->ligacao = null;
    }

    //============================================================
    //CRUD
    //============================================================
    public function select($query, $parametros = null)
    {

        $query = trim($query);

        //verificar se é uma intrução SELECT
        if (!preg_match("/^SELECT/i", $query)) {
            throw new Exception('Base de dados - Não é uma intrução SELECT');
        }

        //ligar a bd
        $this->ligar();

        $resultados = null;
        try {
            //comunicar com a bd
            if (!empty($parametros)) {
                $executar = $this->ligacao->prepare($query);
                $executar->execute($parametros);
                $resultados = $executar->fetcHAll(PDO::FETCH_CLASS);
            } else {
                $executar = $this->ligacao->prepare($query);
                $executar->execute();
                $resultados = $executar->fetcHAll(PDO::FETCH_CLASS);
            }
        } catch (PDOException $e) {
            //caso exista erro
            return false;
        }

        //desligar da bd
        $this->desligar();

        //devolver os resultados obtidos
        return $resultados;
    }

    public function select_outros($query, $parametros = null)
    {

        $query = trim($query);

        //ligar a bd
        $this->ligar();

        $resultados = null;
        try {
            //comunicar         com a bd
            if (!empty($parametros)) {
                $executar = $this->ligacao->prepare($query);
                $executar->execute($parametros);
                $resultados = $executar->fetcHAll(PDO::FETCH_CLASS);
            } else {
                $executar = $this->ligacao->prepare($query);
                $executar->execute();
                $resultados = $executar->fetcHAll(PDO::FETCH_CLASS);
            }
        } catch (PDOException $e) {
            //caso exista erro
            return false;
        }

        //desligar da bd
        $this->desligar();

        //devolver os resultados obtidos
        return $resultados;
    }
    //============================================================
    public function insert($query, $parametros = null)
    {

        $query = trim($query);

        //verificar se é uma intrução INSERT
        if (!preg_match("/^INSERT/i", $query)) {
            throw new Exception('Base de dados - Não é uma intrução INSERT');
        }
        //ligar a bd
        $this->ligar();
        //comunicar com a bd
        try {
            if (!empty($parametros)) {
                $executar = $this->ligacao->prepare($query);
                $executar->execute($parametros);
            } else {
                $executar = $this->ligacao->prepare($query);
                $executar->execute();
            }
        } catch (PDOException $e) {
            //caso exista erro
            return false;
        }

        //desligar da bd
        $this->desligar();
    }

    //============================================================
    public function update($query, $parametros = null)
    {

        $query = trim($query);

        //verificar se é uma intrução UPDATE
        if (!preg_match("/^UPDATE/i", $query)) {
            throw new Exception('Base de dados - Não é uma intrução UPDATE');
        }
        //ligar a bd
        $this->ligar();
        //comunicar com a bd
        try {
            if (!empty($parametros)) {
                $executar = $this->ligacao->prepare($query);
                $executar->execute($parametros);
            } else {
                $executar = $this->ligacao->prepare($query);
                $executar->execute();
            }
        } catch (PDOException $e) {
            //caso exista erro
            return false;
        }

        //desligar da bd
        $this->desligar();
    }

    //============================================================
    public function delete($query, $parametros = null)
    {

        $query = trim($query);

        //verificar se é uma intrução DELETE
        if (!preg_match("/^DELETE/i", $query)) {
            throw new Exception('Base de dados - Não é uma intrução DELETE');
        }
        //ligar a bd
        $this->ligar();
        //comunicar com a bd
        try {
            if (!empty($parametros)) {
                $executar = $this->ligacao->prepare($query);
                $executar->execute($parametros);
            } else {
                $executar = $this->ligacao->prepare($query);
                $executar->execute();
            }
        } catch (PDOException $e) {
            //caso exista erro
            return false;
        }

        //desligar da bd
        $this->desligar();
    }

    //Outras querys...
    //============================================================
    public function stantement($query, $parametros = null)
    {

        $query = trim($query);

        //verificar se é uma intrução valida
        if (preg_match("/^(INSERT|SELECT|UPDATE|DELETE)/i", $query)) {
            throw new Exception('Base de dados - Não é uma intrução válida');
        }
        //ligar a bd
        $this->ligar();
        //comunicar com a bd
        try {
            if (!empty($parametros)) {
                $executar = $this->ligacao->prepare($query);
                $executar->execute($parametros);
            } else {
                $executar = $this->ligacao->prepare($query);
                $executar->execute();
            }
        } catch (PDOException $e) {
            //caso exista erro
            return false;
        }

        //desligar da bd
        $this->desligar();
    }

    //============================================================
    // MECANISMO DE SEGURANÇA  // BACKUPS
    //============================================================

    //Backup do banco de dados
    public function backupDatabase()
    {
        $host = MYSQL_SERVER;
        $username = MYSQL_USER;
        $password = MYSQL_PASS;
        $database = MYSQL_DATABASE;
        $backupDir = '../../backups/';

        //caminho do mysql dump
        $mysqldumpPath = '"C:\\Program Files\\MySQL\MySQL Server 8.0\\bin\\mysqldump.exe"';

        // Nome do arquivo de backup com base na data e hora atuais
        $backupFile = $backupDir . '/' . $database . '_' . date('Y-m-d_H-i-s') . '.sql';

        // Comando mysqldump para gerar o backup
        $command = "{$mysqldumpPath} --host={$host} --user={$username} --password={$password} {$database} > {$backupFile}";

        // Executa o comando e armazena a saída padrão e de erro
        $output = [];
        $returnVar = null;
        exec("{$command} > {$backupFile} 2>&1", $output, $returnVar);

        // Verifica se o backup foi criado com sucesso
        if ($returnVar === 0) {
            echo "Backup criado com sucesso: {$backupFile}\\n";
        } else {
            echo "Erro ao criar o backup. Detalhes do erro:\\n";
            echo implode("\\n", $output);
        }
    }
    // backuptable
    public function backuptable($tabela)
    {
        $host = MYSQL_SERVER;
        $username = MYSQL_USER;
        $password = MYSQL_PASS;
        $database = MYSQL_DATABASE;
        $backupDir = '../../backups/';

        //caminho do mysql dump
        $mysqldumpPath = '"C:\\Program Files\\MySQL\MySQL Server 8.0\\bin\\mysqldump.exe"';

        // Nome do arquivo de backup com base na data e hora atuais
        $backupFile = $backupDir . '/' . $database . '_' . date('Y-m-d_H-i-s') . '.sql';

        // Comando mysqldump para gerar o backup
        $command = "{$mysqldumpPath} --host={$host} --user={$username} --password={$password} {$database} $tabela > {$backupFile}";

        // Executa o comando e armazena a saída padrão e de erro
        $output = [];
        $returnVar = null;
        exec("{$command} > {$backupFile} 2>&1", $output, $returnVar);

        // Verifica se o backup foi criado com sucesso
        if ($returnVar === 0) {
            echo "Backup criado com sucesso: {$backupFile}\\n";
        } else {
            echo "Erro ao criar o backup. Detalhes do erro:\\n";
            echo implode("\\n", $output);
        }
    }

}
