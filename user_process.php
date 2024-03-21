<?php

require_once("global.php");
require_once("db.php");
require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");

$message = new Message($BASE_URL);

$userDao = new UserDAO($conn, $BASE_URL);

$type = filter_input(INPUT_POST, "type");

if ($type === "update") {

    $userData = $userDao->verifyToken();

    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $bio = filter_input(INPUT_POST, "bio");
    $email = filter_input(INPUT_POST, "email");

    $user = new User();

    $userData->name = $name;
    $userData->lastname = $lastname;
    $userData->bio = $bio;
    $userData->email = $email;

    if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
        $image = $_FILES['image'];

        if (in_array($image['type'], ['image/jpeg', 'image/jpg', 'image/png'])) {

            $image = $_FILES['image'];
            $imageTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            $jpgArray = ['image/jpeg', 'image/jpg'];

            // checagem do tipo de imagem

            if(in_array($image['type'], $imageTypes)) {

                if(in_array($image['type'], $jpgArray)) {
                    $imageFile = imagecreatefromjpeg($image['tmp_name']);
                } else {
                    $imageFile = imagecreatefrompng($image['tmp_name']);
                }

                $imageName = $user->imageGenerateName();

                imagejpeg($imageFile, "img/users/" . $imageName, 100);

                $userData->image = $imageName;

            } else {
                $message->setMessage("Tipo inválido de imagem, insira png ou jpg", "error", "back");
            }

        } else {
            $message->setMessage("Tipo de arquivo inválido", "error", "back");
        }
    }

    $userDao->update($userData);

} else if ($type === "changepassword") {
    // Lógica para alterar a senha

    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");
    $id = filter_input(INPUT_POST, "id");

    if($password == $confirmpassword) {
        $user = new User();

        $finalPassword = $user->generatePassword($password);

        $user->password = $finalPassword;
        $user->id = $id;

        $userDao->changePassword($user);
    } else {
        $message->setMessage("As senhas não são iguais", "error", "back");
    }
} else {
    $message->setMessage("Ação inválida", "error", "index.php");
}
