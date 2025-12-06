<?php

namespace core\classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EnviarEmail
{

    // Email de confirmação de conta
    public function enviar_email_confirmacao($email_usuario, $purl)
    {
        // Construa o purl -- link de validação do email
        $link = BASE_URL . '?a=confirmar_email&purl=' . $purl;



        $mail = new PHPMailer(true);

        try {
            //config de servidor 
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = EMAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = EMAIL_FROM;
            $mail->Password   = EMAIL_PASS;
            $mail->SMTPSecure = 'SSL/TLS';
            $mail->Port       = EMAIL_PORT;
            $mail->CharSet = "UTF-8";

            //O endereço do email para onde queremos enviar
            $mail->addAddress($email_usuario);

            //Content Assunto
            $mail->isHTML(true);
            $mail->Subject = APP_NAME . ' - Confirmação de email';

            // mensage
            $html = '<p>Seja bem-vindo ao nosso sistema ' . APP_NAME . '<p>';
            $html .= '<p>Para poder ativar a sua conta necessita de confirmar o seu email.</p>';
            $html .= '<p>Click no lnk abaixo para poder confirmar o seu email</p>';
            $html .= '<p><a href="' . $link . '"> Confirmar Email <a></p>';
            $html .= '<p><i><strong>' . APP_NAME . '</strong></i></p>';

            $mail->Body = $html;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    // Email de confirmação de reserva
    public function enviar_email_confirmacao_reserva($email_usuario, $dados_reserva)
    {

        $link = BASE_URL . '?a=inicio';

        //Enviar email
        $mail = new PHPMailer(true);

        try {
            //config de servidor 
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = EMAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = EMAIL_FROM;
            $mail->Password   = EMAIL_PASS;
            $mail->SMTPSecure = 'SSL/TLS';
            $mail->Port       = EMAIL_PORT;
            $mail->CharSet = "UTF-8";


            //O endereço do email para onde queremos enviar
            $mail->addAddress($email_usuario);

            //Content Assunto
            $mail->isHTML(true);
            $mail->Subject = APP_NAME . ' - Confirmação da Reserva';

            // mensage

            $html = '<p>Este email serve para confirmar a sua Reserva<p>';
            $html .= '<hr>';
            $html .= '<p>Dados da Reserva:</p>';
            $html .= '<ul>';
            $html .= '<li>Data: ' . $dados_reserva['data'] . '</li>';
            $html .= '<li>Horário: ' . date('H:i', strtotime($dados_reserva['hora_inicio'])) . ' - ' . date('H:i', strtotime($dados_reserva['hora_fim'])) . '</li>';
            $html .= '<li>Nº de Pessoa: ' . $dados_reserva['numero_pessoa'] . '</li>';
            $html .= '<li>Tipo de Refeição: ' . $dados_reserva['tipo_refeicao'] . '</li>';
            $html .= '</ul>';
            $html .= '<hr>';
            $html .= '<strong>Nota:</strong> A sua Reserva será processado após ao pagamento!';

            $html .= '<p>Obrigado pela Preferença!<p>';

            $html .= '<p><a href="' . $link . '">' . APP_NAME . '<a></p>';

            $mail->Body = $html;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // enviar_email_confirmacao_pedido
    public function enviar_email_confirmacao_pedido($email_usuario, $dados_pedido)
    {

        $link = BASE_URL . '?a=inicio';

        //Enviar email
        $mail = new PHPMailer(true);

        try {
            //config de servidor
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host = EMAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = EMAIL_FROM;
            $mail->Password = EMAIL_PASS;
            $mail->SMTPSecure = 'SSL/TLS';
            $mail->Port = EMAIL_PORT;
            $mail->CharSet = "UTF-8";


            //O endereço do email para onde queremos enviar
            $mail->addAddress($email_usuario);

            //Content Assunto
            $mail->isHTML(true);
            $mail->Subject = APP_NAME . ' - Confirmação do Pedido';

            // mensage

            // mensagem
            $html = '<p>Este email serve para confirmar o seu pedido.</p>';
            $html .= '<p>Dados do Pedido:</p>';

            // lista dos itens
            $html .= '<ul>';
            foreach ($dados_pedido['lista_produtos'] as $produto) {
                $html .= "<li>$produto</li>";
            }
            $html .= '</ul>';

            // total
            $html .= '<p>Total: <strong>' . $dados_pedido['total'] . '</strong></p>';

            // dados de pagamento
            $html .= '<hr>';
            $html .= '<p>DADOS DE PAGAMENTO:</p>';
            $html .= '<p>Número da conta: <strong>' . CONTA . '</strong></p>';
            $html .= '<p>Número de IBAN: <strong>' . IBAN . '</strong></p>';
            $html .= '<p>Código do Pedido: <strong>' . $dados_pedido['dados_pagamento']['codigo_pedido'] . '</strong></p>';
            $html .= '<p>Valor a pagar: <strong>' . $dados_pedido['dados_pagamento']['total'] . '</strong></p>';
            $html .= '<hr>';

            // nota importante
            $html .= '<p>NOTA: O pedido só será processada após pagamento.</p>';


            $html .= '<p>Obrigado pela Preferença!<p>';

            $html .= '<p><a href="' . $link . '">' . APP_NAME . '<a></p>';

            $mail->Body = $html;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // enviar email com o ficheiro de pdf com detalhes da reserva
    public function enviar_pdf_reserva($email_usuario, $ficheiro)
    {
        $mail = new PHPMailer(true);

        try {
            //config de servidor 
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = EMAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = EMAIL_FROM;
            $mail->Password   = EMAIL_PASS;
            $mail->SMTPSecure = 'SSL/TLS';
            $mail->Port       = 587;
            $mail->CharSet = "UTF-8";


            //O endereço do email para onde queremos enviar
            $mail->addAddress($email_usuario);

            //Content Assunto
            $mail->isHTML(true);
            $mail->Subject = APP_NAME . ' - PDF detalhe da Reserva';

            // mensage
            $html = '<p>Segue em anexo o PDF com os detalhes da sua Reserva<p>';
            $html .= '<hr>';
            $html .= '<p>Obrigado pela Preferença!<p>';
            $html .= '<p><i><strong>' . APP_NAME . '</strong></i></p>';

            //anexo
            $mail->addAttachment(PDF_PATH . $ficheiro);

            $mail->Body = $html;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // enviar email com o ficheiro de pdf com detalhes da reserva
    public function enviar_pdf_pedido($email_usuario, $ficheiro)
    {
        $mail = new PHPMailer(true);

        try {
            //config de servidor 
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = EMAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = EMAIL_FROM;
            $mail->Password   = EMAIL_PASS;
            $mail->SMTPSecure = 'SSL/TLS';
            $mail->Port       = 587;
            $mail->CharSet = "UTF-8";


            //O endereço do email para onde queremos enviar
            $mail->addAddress($email_usuario);

            //Content Assunto
            $mail->isHTML(true);
            $mail->Subject = APP_NAME . ' - PDF detalhe dp Pedido';

            // mensage
            $html = '<p>Segue em anexo o PDF com os detalhes do Pedido<p>';
            $html .= '<hr>';
            $html .= '<p>Obrigado pela Preferença!<p>';
            $html .= '<p><a href="' . BASE_URL . '"><i>' . APP_NAME . '</i></a></p>';

            //anexo
            $mail->addAttachment(PDF_PATH . $ficheiro);

            $mail->Body = $html;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function email_notificar_cliente_mudanca_estado($reserva)
    {

        $link = BASE_URL . '?a=inicio';

        //Enviar email de confirmação de inscrição em um curso
        $mail = new PHPMailer(true);

        try {
            //config de servidor 
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = EMAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = EMAIL_FROM;
            $mail->Password   = EMAIL_PASS;
            $mail->SMTPSecure = 'SSL/TLS';
            $mail->Port       = EMAIL_PORT;
            $mail->CharSet = "UTF-8";


            //O endereço do email para onde queremos enviar
            $mail->addAddress($reserva->email);

            //Content Assunto
            $mail->isHTML(true);
            $mail->Subject = APP_NAME . ' - Alteração de Estado da Reserva';

            // mensage

            $html = "<p> Olá $reserva->cliente! <p>";
            $html = '<p>Este email serve para Informar que o Estado da sua reserva foi Alterado<p>';
            $html .= '<hr>';
            $html .= '<p>Informação:</p>';
            $html .= '<ul>';
            $html .= "<li>Novo Estado: $reserva->status</li>";
            $html .= "<li>Data de Inicio: $reserva->hora_inicio</li>";
            $html .= "<li>Periodo: $reserva->tipo_refeicao</li>";
            $html .= '</ul>';
            $html .= '<hr>';
            $html .= '<p>Obrigado pela Preferença!<p>';

            $html .= '<p><a href="' . $link . '">' . APP_NAME . '<a></p>';

            $mail->Body = $html;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function email_notificar_cliente_mudanca_estado_pedido($pedido)
    {

        $link = BASE_URL . '?a=inicio';

        //Enviar email de confirmação de inscrição em um curso
        $mail = new PHPMailer(true);

        try {
            //config de servidor 
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = EMAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = EMAIL_FROM;
            $mail->Password   = EMAIL_PASS;
            $mail->SMTPSecure = 'SSL/TLS';
            $mail->Port       = EMAIL_PORT;
            $mail->CharSet = "UTF-8";


            //O endereço do email para onde queremos enviar
            $mail->addAddress($pedido['pedido']->email);

            //Content Assunto
            $mail->isHTML(true);
            $mail->Subject = APP_NAME . ' - Alteração do Pedido';

            // mensage

            $html = "<p> Olá " . $pedido['pedido']->cliente . "!<p>";
            $html = '<p>Este email serve para Informar que o Estado do seu pedido foi Alterado<p>';
            $html .= '<hr>';
            $html .= '<p>Informação:</p>';
            $html .= '<ul>';
            $html .= "<li>Código: " . $pedido['pedido']->codigo_pedido ."</li>";
            $html .= "<li>Numero da mesa: ". $pedido['pedido']->numero_mesa ."</li>";
            $html .= "<li>Novo Status: ". $pedido['pedido']->status ."</li>";
            $html .= "<li>Data: ". date('d/m/Y', strtotime($pedido['pedido']->data_pedido)) ."</li>";
            $html .= '</ul>';
            $html .= '<hr>';
            $html .= '<p>Obrigado pela Preferença!<p>';
            $html .= '<p><a href="' . $link . '">' . APP_NAME . '<a></p>';

            $mail->Body = $html;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // // Email de recuperação de conta
    // public function email_link_recuperacao($email, $token)
    // {
    //     // Construa o link de recuperação de senha
    //     $link = BASE_URL . '?a=reset_senha&token=' . $token;

    //     $mail = new PHPMailer(true);

    //     try {
    //         //config de servidor 
    //         $mail->SMTPDebug = SMTP::DEBUG_OFF;
    //         $mail->isSMTP();
    //         $mail->Host       = EMAIL_HOST;
    //         $mail->SMTPAuth   = true;
    //         $mail->Username   = EMAIL_FROM;
    //         $mail->Password   = EMAIL_PASS;
    //         $mail->SMTPSecure = 'SSL/TLS';
    //         $mail->Port       = EMAIL_PORT;
    //         $mail->CharSet = "UTF-8";


    //         //O endereço do email para onde queremos enviar
    //         $mail->addAddress($email);

    //         //Content Assunto
    //         $mail->isHTML(true);
    //         $mail->Subject = APP_NAME . ' - Recuperação de Senha';

    //         // mensage
    //         $html = "<p>Clique no link para resetar sua senha</p>";
    //         $html .= '<p><i><a href="' . $link . '"> Link <a></i></p>';
    //         $html .= '<p><i><strong>' . APP_NAME . '</strong></i></p>';

    //         $mail->Body = $html;

    //         $mail->send();
    //         return true;
    //     } catch (Exception $e) {
    //         return false;
    //     }
    // }

    // // Email de recuperação de conta
    // public function email_link_recuperacao_admin($email, $token)
    // {
    //     // Construa o link de recuperação de senha
    //     $link = BASE_URL . 'admin/?a=reset_senha&token=' . $token;

    //     $mail = new PHPMailer(true);

    //     try {
    //         //config de servidor 
    //         $mail->SMTPDebug = SMTP::DEBUG_OFF;
    //         $mail->isSMTP();
    //         $mail->Host       = EMAIL_HOST;
    //         $mail->SMTPAuth   = true;
    //         $mail->Username   = EMAIL_FROM;
    //         $mail->Password   = EMAIL_PASS;
    //         $mail->SMTPSecure = 'SSL/TLS';
    //         $mail->Port       = EMAIL_PORT;
    //         $mail->CharSet = "UTF-8";


    //         //O endereço do email para onde queremos enviar
    //         $mail->addAddress($email);

    //         //Content Assunto
    //         $mail->isHTML(true);
    //         $mail->Subject = APP_NAME . ' - Recuperação de Senha';

    //         // mensage
    //         $html = "<p>Clique no link para resetar sua senha</p>";
    //         $html .= '<p><i><a href="' . $link . '"> Link <a></i></p>';
    //         $html .= '<p><i><strong>' . APP_NAME . '</strong></i></p>';

    //         $mail->Body = $html;

    //         $mail->send();
    //         return true;
    //     } catch (Exception $e) {
    //         return false;
    //     }
    // }

    // enviar email com a senha de usuario
    public function enviar_senha_email($email_usuario, $senha)
    {

        $mail = new PHPMailer(true);

        try {
            //config de servidor 
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = EMAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = EMAIL_FROM;
            $mail->Password   = EMAIL_PASS;
            $mail->SMTPSecure = 'SSL/TLS';
            $mail->Port       = EMAIL_PORT;
            $mail->CharSet = "UTF-8";


            //O endereço do email para onde queremos enviar
            $mail->addAddress($email_usuario);

            //Content Assunto
            $mail->isHTML(true);
            $mail->Subject = APP_NAME . ' - Confirmação de Usuario';

            // mensage
            $html = '<p>Parabéns agora és um usuario adminstrador' . APP_NAME . '<p>';
            $html .= '<p>A sua conta de usuario foi criada com sucesso.</p>';
            $html .= '<p>Esta é a sua senha de usuario <strong>' . $senha . '</strong></p>';
            $html .= '<p>Usará para fazer o <a href="?a=admin_login">Login</a> na sua conta</p>';
            $html .= '<p>ATT: Não compartilhe com Ninguém</p>';
            $html .= '<p><i><strong>' . APP_NAME . '</strong></i></p>';

            $mail->Body = $html;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
