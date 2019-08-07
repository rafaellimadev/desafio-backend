<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name: flexi auth lang - Portuguese
* 
* Author: 
* Rob Hussey
* flexiauth@haseydesign.com
* haseydesign.com/flexi-auth
*
* Released: 13/09/2012
*
* Description:
* Portuguese language file for flexi auth
*
* Requirements: PHP5 or above and Codeigniter 2.0+
*/

// Account Creation
$lang['account_creation_successful']				= 'Sua conta foi criada com sucesso.';
$lang['account_creation_unsuccessful']				= 'Impossível criar sua conta.';
$lang['account_creation_duplicate_email']			= 'Uma conta com este email já existe.'; 
$lang['account_creation_duplicate_username']		= 'Uma conta com este nome de usuário já existe.'; 
$lang['account_creation_duplicate_identity'] 		= 'Uma conta com esta identificaão já existe';
$lang['account_creation_insufficient_data']			= 'Dados insuficientes para criar a conta. Certifique-se de que uma identidade e senha válidas sejam informadas';

// Password
$lang['password_invalid']							= "O Campo %s é inválido.";
$lang['password_change_successful'] 	 	 		= 'Senha alterada com sucesso.';
$lang['password_change_unsuccessful'] 	  	 		= 'A senha informada não confere com nossos registros.';
$lang['password_token_invalid']  					= 'O token de alteração de senha é inválido ou expirou.'; 
$lang['email_new_password_successful']				= 'Uma nova senha foi enviada por email para você.';
$lang['email_forgot_password_successful']	 		= 'Um email foi enviado para renovar sua senha.';
$lang['email_forgot_password_unsuccessful']  		= 'Impossível renovar a senha. Verifique o seu CPF!'; 

// Activation
$lang['activate_successful']						= 'Conta foi ativada.';
$lang['activate_unsuccessful']						= 'Impossível ativar a conta.';
$lang['deactivate_successful']						= 'Conta foi desativada.';
$lang['deactivate_unsuccessful']					= 'Impossível desativar a conta.';
$lang['activation_email_successful'] 	 			= 'Um email de ativação foi enviado.';
$lang['activation_email_unsuccessful']  	 		= 'Impossível enviar o email de ativação.';
$lang['account_requires_activation'] 				= 'Sua conta precisa ser ativda por email.';
$lang['account_already_activated'] 					= 'Sua conta já foi ativada.';
$lang['email_activation_email_successful']			= 'Um email foi enviado para ativar seu novo endereço.';
$lang['email_activation_email_unsuccessful']		= 'Impossível enviar um email para ativar seu endereço.';

// Login / Logout
$lang['login_successful']							= 'Você acessou o sistema com sucesso.';
$lang['login_unsuccessful']							= 'Dados de login informados estão incorretos.';
$lang['logout_successful']							= 'Você saíu do sistema com sucesso.';
$lang['login_details_invalid'] 						= 'Os detalhes de seu login estão incorretos.';
$lang['captcha_answer_invalid'] 					= 'Resposta à imagem está incorreta.';
$lang['login_attempts_exceeded'] 					= 'Você excedeu o número máximo de tentativas de login,por favor espere alguns momentos para tentar novamente.';
$lang['login_session_expired']						= 'Sua sessão de acesso expirou.';
$lang['account_suspended'] 							= 'Sua conta foi suspensa.';

// Account Changes
$lang['update_successful']							= 'Informações da conta foram atualizadas com sucesso.';
$lang['update_unsuccessful']						= 'Impossível atualizar informações da conta.';
$lang['delete_successful']							= 'Informações da conta foram excluidas com sucesso.';
$lang['delete_unsuccessful']						= 'Impossível excluir informações da conta.';

// Form Validation
$lang['form_validation_duplicate_identity'] 		= "Uma conta com este email ou usuário já existe.";
$lang['form_validation_duplicate_email'] 			= "O campo de email %s não está disponível.";
$lang['form_validation_duplicate_username'] 		= "O nome de usuário do campo %s não está disponível.";
$lang['form_validation_current_password'] 			= "O campo %s é inválido.";