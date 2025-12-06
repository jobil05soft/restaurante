<?php

namespace core\Models;

use core\classes\Database;
use core\classes\Store;

class Usuario
{

    public function verificarEmail($email)
    {

        $base_dados = new Database();
        $parametro = [':e' => strtolower(trim($email))];

        $resultado = $base_dados->select("SELECT * FROM tb_pessoa WHERE email = :e", $parametro);
        if (count($resultado) != 0) {
            return true;
        } else {
            return false;
        }
    }

    public function salvarUsuario()
    {

        //registar novo usuario na base de dados
        $db = new Database();

        //cria uma hash para o registro do user
        $purl = Store::criarHash();

        //parametros
        $parametros = [
            ':nome_completo' => trim($_POST['nome']),
            ':email' => strtolower(trim($_POST['email'])),
            ':telefone' => trim($_POST['telefone']),
            ':morada' => trim($_POST['morada']),
        ];

        $db->insert("
        INSERT INTO tb_pessoa VALUES(
            0,
            :nome_completo,
            Null,
            :email,
            :telefone,
            :morada
        )
        ", $parametros);

        // buscar om id da pessoa
        $id_pessoa = $db->select("SELECT MAX(id_pessoa) id_pessoa FROM tb_pessoa")[0]->id_pessoa;

        $parametros = [
            ':nome' => trim($_POST['nome']),
            ':senha' => password_hash($_POST['senha_1'], PASSWORD_DEFAULT),
            ':purl' => $purl,
            ':ativo' => 0,
            ':id_pessoa' => $id_pessoa,
            ':tipo_user' => 2,
        ];


        $db->insert("
        INSERT INTO tb_usuario VALUES(
            0,
            :nome,
            :senha,
            :purl,
            :ativo,
            :id_pessoa,
            :tipo_user,
            Now(),
            Now(),
            Null
        )
        ", $parametros);

        return $purl;
    }

    //================================================================
    public function validar_email($purl)
    {

        //validar o email do novo usuario
        $db = new Database();
        $parametros = [
            ':purl' => $purl
        ];
        $resultado = $db->select("SELECT * FROM tb_usuario WHERE purl = :purl", $parametros);

        //verifica se foi encontrado o usuario
        if (count($resultado) != 1) {
            return false;
        }

        // usuario encontrado
        $id_usuario = $resultado[0]->id_usuario;

        // actualizar os dados do usuario
        $parametros = [
            ':id_usuario' => $id_usuario
        ];
        $db->update("
            UPDATE tb_usuario SET 
            purl = NULL,
            ativo = 1,
            updated_at = NOW()
            WHERE id_usuario = :id_usuario
        ", $parametros);

        return true;
    }

    public function validar_login($sql_condition, $senha)
    {

        $db = new Database();

        $resultado = $db->select("
            SELECT * from tb_usuario u JOIN tb_pessoa p ON u.tb_pessoa_id_pessoa = p.id_pessoa
            WHERE $sql_condition
            AND u.ativo = 1
            AND u.deleted_at IS NULL
        ");

        if (count($resultado) != 1) {

            //não existe usuário
            return false;
        } else {

            //temos usuario. ver a sua senha
            $usuario = $resultado[0];

            //verificar a password
            if (!password_verify($senha, $usuario->senha)) {

                //password invalida
                return false;
            } else {

                //Login valido

                $parametros = [
                    ':id' => $usuario->id_usuario
                ];

                $tipo_user = $db->select("
                    SELECT t.nivel_acesso nivel_acesso FROM tb_usuario u JOIN tb_tipo_usuario t 
                    ON u.tb_tipo_usuario_id_tipouser = t.id_tipouser
                    WHERE u.id_usuario = :id
                ", $parametros)[0]->nivel_acesso;

                if ($tipo_user != 'cliente') {
                    return false;
                }
                
                return $usuario;
            }
        }
    }

    public function buscar_dados($id_usuario)
    {

        $parametro = [':id' => $id_usuario];
        $db = new Database();
        return $db->select(
            '
            SELECT * from tb_pessoa 
            join tb_usuario on tb_usuario.tb_pessoa_id_pessoa = tb_pessoa.id_pessoa
            join tb_tipo_usuario on tb_usuario.tb_tipo_usuario_id_tipouser = tb_tipo_usuario.id_tipouser
            WHERE tb_usuario.id_usuario = :id',
            $parametro
        )[0];
    }

    //================================================================
    public function verificar_email_existe_noutra_conta($id_usuario, $email)
    {

        $db = new Database();
        //verificar se existe o email noutra conta de usuario
        $parametros = [
            ':id_usuario' => $id_usuario,
            ':email' => $email
        ];

        $resultado = $db->select("
            SELECT * FROM tb_pessoa p
            JOIN tb_usuario u on u.tb_pessoa_id_pessoa = p.id_pessoa
            WHERE u.id_usuario <> :id_usuario AND p.email = :email
        ", $parametros);


        if (count($resultado) != 0) {
            return true;
        } else {
            return false;
        }
    }

    //================================================================
    public function actualizar_dados_usuario($nome, $email, $telefone, $morada, $nome_usuario)
    {

        $db = new Database();

        $parametros = [
            ':id_pessoa' => $this->buscar_id_pessoa($_SESSION['user']),
            ':nome' => $nome,
            ':email' => $email,
            ':endereco' => $morada,
            ':telefone' => $telefone,
        ];

        $db->update("
            UPDATE tb_pessoa set
            nome_completo = :nome,
            email = :email,
            telefone = :telefone, 
            endereco = :endereco
            WHERE id_pessoa = :id_pessoa
        ", $parametros);

        $parametros = [
            ':id_usuario' => $_SESSION['user'],
            ':nome' => $nome_usuario,
        ];

        $db->update("
            UPDATE tb_usuario set
            nome_usuario = :nome,
            updated_at = NOW()
            WHERE id_usuario = :id_usuario
        ", $parametros);
    }

    // ===========================================================
    public function buscar_id_usuario($id_pessoa)
    {

        $parametros = [
            ':id_pessoa' => $id_pessoa
        ];

        $bd = new Database();
        $resultados = $bd->select("
            SELECT 
                u.id_usuario
            FROM tb_usuario u left JOIN tb_pessoa p
            on u.tb_pessoa_id_pessoa = p.id_pessoa
            WHERE p.id_pessoa = :id_pessoa
        ", $parametros);

        return $resultados;
    }

    // ===========================================================
    public function buscar_id_pessoa($id_usuario)
    {

        $parametros = [
            ':id_usuario' => $id_usuario
        ];

        $bd = new Database();
        $resultados = $bd->select("
              SELECT 
                  p.id_pessoa
              FROM tb_usuario u JOIN tb_pessoa p
              on u.tb_pessoa_id_pessoa = p.id_pessoa
              WHERE u.id_usuario = :id_usuario
          ", $parametros);

        return $resultados[0]->id_pessoa;
    }
    //================================================================
    public function verificar_senha($id_usuario, $senha_antiga)
    {
        $parametros = [
            ':id_usuario' => $id_usuario,
        ];

        $db = new Database();
        $senha__bd = $db->select("SELECT senha FROM tb_usuario WHERE id_usuario = :id_usuario", $parametros)[0]->senha;

        return password_verify($senha_antiga, $senha__bd);
    }

    //================================================================
    public function actualizar_nova_senha($id_usuario, $nova_senha)
    {

        //Actualizar senha do usuario
        $parametros = [
            ':id_usuario' => $id_usuario,
            ':nova_senha' => password_hash($nova_senha, PASSWORD_DEFAULT)
        ];

        $db = new Database();
        $db->update("
            UPDATE tb_usuario 
            set 
                senha = :nova_senha, 
                updated_at = NOW()
            WHERE id_usuario = :id_usuario
        ", $parametros);
    }

    public function guardar_user()
    {
        //registar novo usuario na base de dados
        $db = new Database();

        //cria uma senha para o registro do usuario
        $senha = Store::criarsenha();

        //parametros
        $parametros = [
            ':nome_completo' => trim($_POST['nome_completo']),
            ':email' => strtolower(trim($_POST['email'])),
            ':telefone' => trim($_POST['telefone']),
            ':morada' => trim($_POST['endereco']),
        ];

        $db->insert("
        INSERT INTO tb_pessoa VALUES(
            0,
            :nome_completo,
            Null,
            :email,
            :telefone,
            :morada
        )
        ", $parametros);

        // buscar om id da pessoa
        $id_pessoa = $db->select("SELECT MAX(id_pessoa) id_pessoa FROM tb_pessoa")[0]->id_pessoa;

        $parametros = [
            ':nome' => trim($_POST['nome_usuario']),
            ':senha' => password_hash($senha, PASSWORD_DEFAULT),
            ':purl' => Null,
            ':ativo' => 1,
            ':id_pessoa' => $id_pessoa,
            ':tipo_user' => trim($_POST['tipo_user']),
        ];


        $db->insert("
        INSERT INTO tb_usuario VALUES(
            0,
            :nome,
            :senha,
            :purl,
            :ativo,
            :id_pessoa,
            :tipo_user,
            Now(),
            Now(),
            Null
        )
        ", $parametros);

        return $senha;
    }

    public function actualizar_dados_admin()
    {
        $db = new Database();

        $parametros = [
            ':id_pessoa' => $this->buscar_id_pessoa($_SESSION['admin']),
            ':nome' => $_POST['nome_completo'],
            ':email' => $_POST['email'],
            ':endereco' => $_POST['endereco'],
            ':telefone' => $_POST['telefone'],
        ];

        $db->update("
            UPDATE tb_pessoa set
            nome_completo = :nome,
            email = :email,
            telefone = :telefone, 
            endereco = :endereco
            WHERE id_pessoa = :id_pessoa
        ", $parametros);

        $parametros = [
            ':id_usuario' => $_SESSION['admin'],
            ':nome' => $_POST['nome_usuario'],
        ];

        $db->update("
            UPDATE tb_usuario set
            nome_usuario = :nome,
            updated_at = NOW()
            WHERE id_usuario = :id_usuario
        ", $parametros);
    }
}
