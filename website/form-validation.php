<?php
require_once('./models/DBConnection.php');

function register($dbConnection): void
{
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'];
    $password = $data['password'];
    $usernamePattern = '//';
    $passwordPattern = '//';
    $dbConnection->execute('INSERT INTO users (username, password) VALUES (:username, :password)', ['username' => $username, 'password' => $password]);
    if (preg_match($usernamePattern, $username)) {
        $isAdded = $dbConnection->execute('INSERT INTO users (username, password) VALUES (:username, :password)', ['username' => $username, 'password' => $password]);
        if ($isAdded) {
            echo json_encode(['success' => true, 'message' => 'Username successfully added']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Username already exists']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid username']);
    }
}

function login(): void
{
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'];
    $password = $data['password'];
    $user = query();
    if ($user) {
        echo json_encode(['success' => true, 'message' => 'Username successfully added']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
    }
}



/*
if (isset($_POST['submit'])) {
    $username = preValidate($_POST['username']);
    if (validateUsername($username)) {
        $username = postValidate($username);
        $password = preValidate($_POST['password']);
        if (validatePassword($password)) {
            $password = postValidate($password);
        }
    }
}
function validateUsername($username): bool
{
    $first_char_pattern = '/^[a-zA-Z]/';
    $amount_pattern = "/^.{8,20}$/";
    $special_char_pattern = "/^(?=.*[!@#$%^&*-])$/";

    if (empty($username)) {
        echo "Username is required.";
        return false;
    }
    else if (!preg_match($amount_pattern, $username)) {
        echo "Username must have from 8 to 20 characters.";
        return false;
    }
    else if (!preg_match($special_char_pattern, $username)) {
        echo "Username must not contain special characters.";
        return false;
    }
    else if (!preg_match($first_char_pattern, $username[0])) {
        echo "First character of username must be a letter.";
        return false;
    }

    return true;
}

function validatePassword($password): bool
{
    if (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
        return false;
    }
    return true;
}

function preValidate($input) : string {
    return trim($input);
}

function postValidate($input) : string {
    return htmlentities($input, ENT_QUOTES, 'UTF-8');
}
*/
