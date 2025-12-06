<?php

namespace core\classes;

use DateTime;
use Exception;

class Store
{

    //Função para carregar as partes da paguina
    public static function layout($estruturas, $dados = null)
    {
        //verifica se estruturas é um arrey
        if (!is_array($estruturas)) {
            throw new Exception("Cloleção de estrutura invalida");
        }

        //variaveis
        if (!empty($dados) && is_array($dados)) {
            extract($dados);
        }
        //Apresentar as views da aplicação
        foreach ($estruturas as $estruturas) {
            include("../core/Views/$estruturas.php");
        }
    }

    //Função para carregar as partes da paguina do admin
    public static function layout_admin($estruturas, $dados = null)
    {
        //verifica se estruturas é um arrey
        if (!is_array($estruturas)) {
            throw new Exception("Cloleção de estrutura invalida");
        }

        //variaveis
        if (!empty($dados) && is_array($dados)) {
            extract($dados);
        }
        //Apresentar as views da aplicação
        foreach ($estruturas as $estruturas) {
            include("../../core/Views/$estruturas.php");
        }
    }

    //redirecionamento
    public static function redirect($rota = '', $admin = false)
    {

        // faz o redirecionamento para a URL desejada (rota)
        if (!$admin) {
            header("Location: " . BASE_URL . "?a=$rota");
        } else {
            header("Location: " . BASE_URL . "/admin?a=$rota");
        }
    }

    //Verificar se tem um estudante Logado
    public static function logado()
    {
        return isset($_SESSION['usuario']);
    }

    //Verificar se tem um estudante Logado
    public static function Admin_logado()
    {
        return isset($_SESSION['admin']);
        return isset($_SESSION['tipo_admin']);
    }

    //criar hashes
    public static function criarHash($num_caracteres = 12)
    {

        $char = '01234567890123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($char), 0, $num_caracteres);
    }

    //criar senhas para usuarios admin
    public static function criarsenha()
    {

        $senha = "";
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZABCDEFGHIJKLMNOPQRSTUVWXYZABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $senha .= substr(str_shuffle($chars), 0, 4);
        $senha .= rand(1000, 9999);
        return $senha;
    }

    //gerar codigo de inscrição
    public static function gerarCodigo()
    {
        $codigo = "";
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZABCDEFGHIJKLMNOPQRSTUVWXYZABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codigo .= substr(str_shuffle($chars), 0, 2);
        $codigo .= rand(100000, 999999);
        return $codigo;
    }

    //Ver dados de um array
    public static function PrintData($data, $die = true)
    {

        if (is_array($data) || is_object($data)) {
            echo '<pre>';
            print_r($data);
            echo '</pre>';
        } else {
            echo 'Coleção de dados Vazio';
        }

        if ($die) {
            die("<br> Terminado");
        }
    }

    //verificar campos vazios
    public static function verificar_campos($campos = [])
    {

        foreach ($campos as $campo) {
            if (empty($campo)) {
                echo 'campo vazio';
            }
        }
    }

    //encriptação
    public static function aesEcriptar($valor)
    {
        return bin2hex(openssl_encrypt($valor, 'aes-256-cbc', AES_KEY, OPENSSL_RAW_DATA, AES_IV));
    }
    //encriptação
    public static function aesDesencriptar($valor)
    {
        return openssl_decrypt(hex2bin($valor), 'aes-256-cbc', AES_KEY, OPENSSL_RAW_DATA, AES_IV);
    }

    //validar numero
    public static function validar_numero($numero)
    {
        // Remover todos os caracteres não numéricos
        $numero = preg_replace('/\D/', '', $numero);

        // Verificar se o número tem 9 dígitos e começa com 9
        if (strlen($numero) == 9 && substr($numero, 0, 1) == '9') {
            return true;
        } else {
            return false;
        }
    }

    // validar numero do bilhete
    public static function validar_bilhete($numero_bilhete)
    {

        # Defina o padrão para o número do bilhete
        $padrao = '/^\d{9}[A-Z]{2}\d{3}$/';

        # Verifique se o número do bilhete corresponde ao padrão
        if (preg_match($padrao, $numero_bilhete)) {
            return true;
        } else {
            return false;
        }
    }

    // validar data de nascimento
    public static function validar_data_nascimento($data_nascimento)
    {

        // Converte a data de nascimento para um objeto DateTime
        $data_nascimento_obj = DateTime::createFromFormat('Y-m-d', $data_nascimento);

        // Verifica se a data de nascimento é válida
        if (!$data_nascimento_obj) {

            return false;
        }

        // Obtém a data atual
        $data_atual = new DateTime();

        // Calcula a diferença entre as datas
        $intervalo = $data_nascimento_obj->diff($data_atual);
        $diferenca_anos = $intervalo->y;

        // Verifica se a diferença de anos é maior ou igual a 12
        if ($diferenca_anos >= 10) {
            return true;
        } else {
            return false;
        }
    }


    // validaar numero do bilhete
    public static function validar_codigo($codigo)
    {

        # Defina o padrão para o número do bilhete
        $padrao = '/^[A-Z]{2}\d{6}$/';

        # Verifique se o número do bilhete corresponde ao padrão
        if (preg_match($padrao, $codigo)) {
            return true;
        } else {
            return false;
        }
    }
}
