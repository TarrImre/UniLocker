<?php

function filleveryfield($name, $email, $password, $passwordAgain, $neptunCode, $uniPassCode){
    if (empty($name) || empty($email) || empty($password) || empty($passwordAgain) || empty($neptunCode) || empty($uniPassCode)) {
        return "Minden mezőt ki kell tölteni!";
    } else {
        return "";
    }
}

function validateVName($name){
    if (empty($name)) {
        return "Nem adtál meg vezetéknevet!";
    }else if (strlen($name) > 30) {
        return "A vezetéknév maximum 30 karakter lehet!";
    }else if (!preg_match("/^[a-zA-ZáéíóöőúüűÁÉÍÓÖŐÚÜŰ ]*$/", $name)) {
        return "A vezetéknév csak betűket tartalmazhat!";
    } else {
        return "";
    }
}

function validateKName($name){
    if (empty($name)) {
        return "Nem adtál meg keresztnevet!";
    }else if (strlen($name) > 30) {
        return "A keresztnév maximum 30 karakter lehet!";
    }else if (!preg_match("/^[a-zA-ZáéíóöőúüűÁÉÍÓÖŐÚÜŰ ]*$/", $name)) {
        return "A keresztnév csak betűket tartalmazhat!";
    } else {
        return "";
    }
}

function validateEmail($email){
    if (empty($email)) {
        return "Nem adtál meg email címet!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Rossz email formátum!";
    } else {
        return "";
    }
}

function validatePassword($password){
    if (empty($password)) {
        return "Nem adtál meg jelszót!";
    } else if (strlen($password) < 8) {
        return "A jelszónak legalább 8 karakter hosszúnak kell lennie!";
    } else {
        return "";
    }
}

function validatePasswordAgain($password, $passwordAgain){
    if (empty($passwordAgain)) {
        return "Nem adtál meg megerősítő jelszót!";
    } else if ($password != $passwordAgain) {
        return "A két jelszó nem egyezik meg!";
    } else {
        return "";
    }
}

function validateNeptunCode($neptunCode){
    if (empty($neptunCode)) {
        return "Nem adtál meg Neptun kódot!";
    } else if (strlen($neptunCode) != 6) {
        return "A Neptun kód 6 karakter hosszú!";
    } else if (!preg_match("/^[a-zA-Z0-9]*$/", $neptunCode)) {
        return "A Neptun kód csak betűket és számokat tartalmazhat!";
    } else {
        return "";
    }
}

function validateUniPassCode($uniPassCode){
    if (strlen($uniPassCode) != 14 && $uniPassCode != "ERROR" && $uniPassCode != "DEACTIVATED") {
        return "Nem megfelelő kártya!";
    } else if (!preg_match("/^[a-zA-Z0-9]*$/", $uniPassCode)) {
        return "Az UniPass kód csak betűket és számokat tartalmazhat!";
    } else {
        return "";
    }
}


function validateEmailExist($conn, $email){
    $check = "SELECT * FROM users WHERE Email = '$email'";
    $result = mysqli_query($conn, $check);
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        return "Az email cím már foglalt!";
    } else {
        return "";
    }
}


function validateNeptunCodeExist($conn, $neptunCode){
    $check = "SELECT * FROM users WHERE NeptunCode = '$neptunCode'";
    $result = mysqli_query($conn, $check);
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        return "A Neptun kód már foglalt!";
    } else {
        return "";
    }
}

function validateUniPassCodeExist($conn, $uniPassCode){
    $check = "SELECT * FROM users WHERE UniPassCode = '$uniPassCode' and UniPassCode != 'ERROR'";
    $result = mysqli_query($conn, $check);
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        return "Az UniPass kód már foglalt!";
    } else {
        return "";
    }
}

