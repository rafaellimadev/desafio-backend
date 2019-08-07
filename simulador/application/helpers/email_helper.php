<?php

//Load Composer's autoloader
require '../vendor/autoload.php';
function enviar_emails($destino, $titulo, $template, $data=array(), $anexo=false, $html=false,$tipo="solicitacao") {
    
    //INSTÂNCIA CODEIGNITER
    $CI = & get_instance();

    //USA TEMPLATE HTML RECEBIDO POR PARÂMETRO, SE NÃO, USA TEMPLATE ATRAVÉS DO CAMINHO RECEBIDO
	if($html) {
        $msg = $template;
    }else{
        $msg = $CI->load->view("/$template", $data, true);
    }      
    
    //TEXTO QUE SERÁ EXIBIDO NO CORPO DO EMAIL
    $text = "";

    /**** SERÁ USADO HTML OU TEXTO PARA O CORPO DA MENSAGEM, NUNCA OS DOIS ****/

    //VERIFICA O(S) DESTINO(S) DO EMAIL - UMA PESSOA OU VÁRIAS PESSOAS
    if(is_array($destino))                $toList = $destino;
    elseif(strpos ($destino,';')!==false) $toList = explode(';', $destino);
    else                                  $toList = array($destino);
    
    // ARRAY COM OPÇÕES ADICIONAIS A SEREM USADAS NO SENDGRID
    $options = array();

    //INICIA OBJETO SENDGRID EMAIL
    $email = new SendGrid\Email();
    
    //SETA O DESTINATÁRIO
    $email->setTos($toList);

    //SETA A CATEGORIA DO EMAIL - SOLICITAÇÃO DE SOCIEDADE/COMPARECIMENTO AOS JOGOS
    $email->addCategory("initial");

    // SETA O ASSUNTO DO EMAIL
    $email->setSubject($titulo);

    //SETA O HTML DO EMAIL
    $email->setHtml($msg);

    //SE POSSUIR ANEXO, SETA O ANEXO DO EMAIL
    if(isset($anexo) && $anexo != null && $anexo) $email->setAttachment($anexo);
    
    //SETA A CONTA SENDGRID E O EMAIL QUE ENVIA
    $username = 'rafaelspinfast@gmail.com';
    $password = 'senha@axs1';
    $email->setFrom("rafaelspinfast@gmail.com");
    
    //INICIA OBJETO SENDGRID - PASSANDO DADOS PARA CONEXÃO
    $sendgrid = new SendGrid($username, $password, $options);
    
    //ENVIA EMAIL - RETORNA UM OBJETO COM 'message', CONTENDO A RESPOSTA DO ENVIO, E 'errors' SE O ENVIO FALHAR
    $result = $sendgrid->send($email);
    
    //VERIFICA SE HOUVE ERRO NO ENVIO
    $result->errors = isset($result->errors) ? $result->errors : array();
    
    //RETORNO 
    $retorno = array();
    $retorno['success'] = false;
    
    //SE TIVER ERROS - RETORNA O ERRO.SE NÃO, RETORNA TRUE
    if (count($result->errors) > 0) $retorno['message'] = $result->errors[0];
    else                            $retorno['success'] = true;
    
    return $retorno;
}

/********** OUTRAS OPÇÕES **********/
//$email->addSubstitution('-name-', $nameList);
    //$email->addSubstitution('-time-', $timeList);
    // Specify that this is an initial contact message
    
    // You can optionally setup individual filters here, in this example, we have 
    // enabled the footer filter
    // $email->addFilter('footer', 'enable', 1);
    // $email->addFilter('footer', "text/plain", "Thank you for your business");
    // $email->addFilter('footer', "text/html", "Thank you for your business");

    // The subject of your email

    // If you do not specify a sender list above, you can specifiy the user here. If 
    // a sender list IS specified above, this email address becomes irrelevant.

    # Crie o corpo da mensagem (uma com texto simples e uma com versão em HTML). 
    # texto é o seu email de texto sem formatação 
    # html é a versão html do email
    # se o receptor for capaz de visualizar html, então somente o e-mail html
    # será exibido
    /*
     * Note the variable substitution here =)
     */
    
    
    // attach the body of the email
    
    
    //$email->setText($text);
    //$email->setFrom("rafa.lima.23@hotmail.com");
    
	
    // Your SendGrid account credentials
    //$username = 'azure_0faed1f6033c1ff80c456efec1402dbc@azure.com';
    //$password = 'send1234!';

    //$username = 'azure_ad6ed8acd63769ca412c37165bf2ce27@azure.com';
    //$password = 'Hm6JQale27b02hp';

    //$username = 'azure_a5c25ade66af2a80d28fea708f022db2@azure.com';
    //$password = 'jec1234!';

    //$username = 'azure_84104ec6d6279ce904d656a737a07c5b@azure.com';
    //$password = 'vnj1234!';


    //PARANÁ
    /*elseif ((strpos($_SERVER['HTTP_HOST'], 'prc.vounojogo') !== false)) {
        if($tipo == 'solicitacao'){
            $username = 'suporte@vounojogo.com.br';
            $password = 'vnj1357246800!';
        }elseif($tipo == 'envioboleto'){
            $username = 'suporte@vounojogo.com.br';
            $password = 'vnj1357246800!';
        }elseif($tipo == 'crm'){
            $username = 'suporte@vounojogo.com.br';
            $password = 'vnj1357246800!';
        }elseif($tipo == 'agradecimentojogo'){
            $username = 'suporte@vounojogo.com.br';
            $password = 'vnj1357246800!';
        }elseif($tipo == 'sentimossuafalta'){
            $username = 'suporte@vounojogo.com.br';
            $password = 'vnj1357246800!';
        }elseif($tipo == 'mensalidadeatraso'){
            $username = 'suporte@vounojogo.com.br';
            $password = 'vnj1357246800!';
        }elseif($tipo == 'preencherdados'){
            $username = 'suporte@vounojogo.com.br';
            $password = 'vnj1357246800!';
        }
    }*/
    
    /*apenas para habiente de desenvolvimento windows*/
    //if( strtoupper (substr(PHP_OS, 0,3)) == 'WIN' ) {
        //$options["turn_off_ssl_verification"] = 1;
    //}