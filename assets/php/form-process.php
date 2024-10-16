<?php
require 'vendor/autoload.php';

// Incluir dependências do Google Cloud usando o Composer
use Google\Cloud\RecaptchaEnterprise\V1\RecaptchaEnterpriseServiceClient;
use Google\Cloud\RecaptchaEnterprise\V1\Event;
use Google\Cloud\RecaptchaEnterprise\V1\Assessment;
use Google\Cloud\RecaptchaEnterprise\V1\TokenProperties\InvalidReason;

/**
  * Crie uma avaliação para analisar o risco de uma ação da interface.
  * @param string $recaptchaKey A chave reCAPTCHA associada ao site/app
  * @param string $token O token gerado obtido do cliente.
  * @param string $project O ID do seu projeto do Google Cloud.
  * @param string $action Nome da ação correspondente ao token.
  */
function create_assessment(
  string $recaptchaKey,
  string $token,
  string $project,
  string $action
): void {
  // Crie o cliente reCAPTCHA.
  // TODO: armazena em cache o código de geração do cliente (recomendado) ou a chamada client.close() antes de sair do método.
  $client = new RecaptchaEnterpriseServiceClient();
  $projectName = $client->projectName($project);

  // Defina as propriedades do evento que será monitorado.
  $event = (new Event())
    ->setSiteKey($recaptchaKey)
    ->setToken($token);

  // Crie a solicitação de avaliação.
  $assessment = (new Assessment())
    ->setEvent($event);

  try {
    $response = $client->createAssessment(
      $projectName,
      $assessment
    );

    // Verifique se o token é válido.
    if ($response->getTokenProperties()->getValid() == false) {
      printf('The CreateAssessment() call failed because the token was invalid for the following reason: ');
      printf(InvalidReason::name($response->getTokenProperties()->getInvalidReason()));
      return;
    }

    // Verifique se a ação esperada foi executada.
    if ($response->getTokenProperties()->getAction() == $action) {
      // Consulte a pontuação de risco e os motivos.
      // Para mais informações sobre como interpretar a avaliação, acesse:
      // https://cloud.google.com/recaptcha-enterprise/docs/interpret-assessment
      printf('The score for the protection action is:');
      printf($response->getRiskAnalysis()->getScore());
    } else {
      printf('The action attribute in your reCAPTCHA tag does not match the action you are expecting to score');
    }
  } catch (exception $e) {
    printf('CreateAssessment() call failed with the following error: ');
    printf($e);
  }
}

// O que fazer: substitua o token e as variáveis de ação reCAPTCHA antes de executar a amostra.
create_assessment(
   '6LfUq1UqAAAAAIpNoLs-hTaE2rVq85f1sCWCZu2C',
   'YOUR_USER_RESPONSE_TOKEN',
   'global-forense-1727886540213',
   'YOUR_RECAPTCHA_ACTION'
);

$name = $_POST["name"];
$email = $_POST["email"];
$subject = $_POST["subject"];
$department = $_POST["department"];
$message = $_POST["message"];
 
$EmailTo = "lucaslucaslucas655@gmail.com";
$Subject = "New Message Received";
 
// prepare email body text
$Body .= "Name: ";
$Body .= $name;
$Body .= "\n";
 
$Body .= "Email: ";
$Body .= $email;
$Body .= "\n";

$Body .= "Subject: ";
$Body .= $subject;
$Body .= "\n";

$Body .= "Department: ";
$Body .= $department;
$Body .= "\n";
 
$Body .= "Message: ";
$Body .= $message;
$Body .= "\n";
 
// send email
$success = mail($EmailTo, $Subject, $Body, "From:".$email);
 
// redirect to success page
if ($success){
   echo "success";
}else{
    echo "invalid";
}

?>