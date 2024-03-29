<?php 

    namespace Alura\Mvc\Controller;

use Alura\Mvc\Helper\FlashMessageTrait;
use Nyholm\Psr7\Response;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

    class LoginController implements RequestHandlerInterface {

        use FlashMessageTrait;
        private PDO $pdo;

        public function __construct()
        {
            $dbPath = __DIR__ . "/../../banco.sqlite";
            $this->pdo = new PDO("sqlite:$dbPath");
        }

        public function handle(ServerRequestInterface $request): ResponseInterface
        {
            $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
            $password = filter_input(INPUT_POST, "password");

            $sql = "Select * from users Where email = :email;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":email", $email);
            $stmt->execute();

            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            $correctPassword = password_verify($password, $userData['password'] ?? '');

            
            if ($correctPassword) {

                if (password_needs_rehash($userData['password'], PASSWORD_ARGON2ID)) {
                    $stmt = $this->pdo->prepare("Update users Set password = :password Where id = :id");
                    $stmt->bindValue(":password", password_hash($password, PASSWORD_ARGON2ID));
                    $stmt->bindValue(":id", $userData['id']);
                    $stmt->execute();
                }
                
                $_SESSION['logado'] = true;
                //header("Location: /");

                return new Response(302, [
                    'Location' => '/'
                ]);

            } else {
                $this->addErrorMessage('Usuário ou senha inválidos');
                //header("Location: /login");
                return new Response(302, [
                    'Location' => '/login'
                ]);
            }
        }

    }

?>