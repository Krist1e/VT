<?php

require_once('./models/DBConnection.php');

class HomeController
{
    private DBConnection $db;
    private Twig\Environment $twig;

    public function __construct()
    {
        $this->db = new DBConnection("db", "root", "kris123", "data");
        $this->twig = new Twig\Environment(new Twig\Loader\FilesystemLoader('./templates'), ['auto_reload' => true]);
    }

    public function index(): void
    {
        $template = $this->twig->load('index.html.twig');

        $articles = $this->db->query('SELECT * FROM articles WHERE is_side_content = 0');
        $sideContent = $this->db->query('SELECT * FROM articles WHERE is_side_content = 1')[0];

        $template->display([
            'articles' => $articles,
            'side' => $sideContent
        ]);
    }

    public function registration() : void
    {
        $template = $this->twig->load('registration.html.twig');
        $template->display();
    }

    public function validateUsername($username): ?string
    {
        $first_char_pattern = '/^[a-zA-Z]/';
        $amount_pattern = "/^.{8,20}$/";
        $special_char_pattern = "/^(?=.*[!@#$%^&*-])$/";

        if (empty($username)) {
            return "Username is required.";
        }
        if (!preg_match($amount_pattern, $username)) {
            return "Username must have from 8 to 20 characters.";
        }
        if (!preg_match($special_char_pattern, $username)) {
            return "Username must not contain special characters.";
        }
        if (!preg_match($first_char_pattern, $username[0])) {
            return "First character of username must be a letter.";
        }
        return null;
    }

    function validatePassword($password): bool
    {
        if (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
            return false;
        }
        return true;
    }

    function preValidate($input): string
    {
        return trim($input);
    }

    function postValidate($input): string
    {
        return htmlentities($input, ENT_QUOTES, 'UTF-8');
    }

    public function signup()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'];
        $password = $data['password'];
        $usernamePattern = '//';
        $passwordPattern = '//';
        if (preg_match($usernamePattern, $username) && preg_match($passwordPattern, $password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $isAdded = $this->db->execute('INSERT INTO users (username, password) VALUES (:username, :password)', ['username' => $username, 'password' => $hashedPassword]);
            if ($isAdded) {
                echo json_encode(['success' => true, 'message' => 'Username successfully added']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Username already exists']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid username']);
        }
    }

    public function login(): void
    {
        if ($this->check()) {
            $template = $this->twig->load('profile.html.twig');
        } else {
            $template = $this->twig->load('login.html.twig');
        }
        $template->display();
    }

    private function check(): bool
    {
        if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
            $data = $this->db->query("SELECT * FROM users WHERE id = :id LIMIT 1", ['id' => $_COOKIE['id']])[0];
            if (($data['hash'] != $_COOKIE['hash']) or ($data['id'] != $_COOKIE['id'])) {
                setcookie("id", "", time() - 3600 * 24 * 30 * 12, "/");
                setcookie("hash", "", time() - 3600 * 24 * 30 * 12, "/", "", "", true);
                return false;
            }
            return true;
        }
        return false;
    }

    public function logout(): void
    {
        setcookie("id", "", time() - 3600 * 24 * 30 * 12, "/");
        setcookie("hash", "", time() - 3600 * 24 * 30 * 12, "/", "", "", true);
        header("Location: /login");
    }

    public function signin(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'];
        $password = $data['password'];

        $user = $this->db->query('SELECT id, password FROM users WHERE username = :username LIMIT 1', ['username' => $username])[0];
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Invalid login.']);
            return;
        }
        if (password_verify($password, $user['password'])) {
            $hash = md5($this->generateString(10));
            $this->db->execute('UPDATE users SET hash = :hash WHERE username = :username LIMIT 1', ['hash' => $hash, 'username' => $username]);
            setcookie("id", $user['id'], time() + 60 * 60 * 24 * 30, "/");
            setcookie("hash", $hash, time() + 60 * 60 * 24 * 30, "/", "", "", true);
            echo json_encode(['success' => true, 'message' => 'Success.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid password.']);
        }

    }

    private function generateString($length = 6): string
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
        $string = "";
        $clen = strlen($chars) - 1;
        while (strlen($string) < $length) {
            $string .= $chars[mt_rand(0, $clen)];
        }
        return $string;
    }

    public function about(): void
    {
        $template = $this->twig->load('about.html.twig');
        $template->display();
    }

    public function shop(): void
    {
        $template = $this->twig->load('shop.html.twig');
        $products = $this->db->query('SELECT * FROM products');

        $template->display([
            'cards' => $products,
        ]);
    }
}