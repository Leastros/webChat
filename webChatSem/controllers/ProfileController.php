<?php

include "models/ProfileModel.php";
include "controllers/RoleController.php";
include "db/DB.php";


/**
 * Controller pro pracui s tabulkou uzivatelu
 * Veskerer operace s databazi se vytvari pres prepare statementy a s pouzitim parameteru, tudiz je DB chranena proti SQLInjection
 */

class ProfileController
{
    // Metoda prida/registruje noveho uzivatele
    // Prijme vytvoreny ProfileModel a heslo prijme zvlast
    // V metode dojde k hashovani heslo a vlozeni uzivatele do DB
    public static function add(ProfileModel $model, string $pwd): ?ProfileModel {
        if(!$model->validate()) {
            return null;
        }

        $role = 2; // user
        $conn = DB::connect();
        $stmt = $conn->prepare(
            "INSERT INTO users (first_name, last_name, username, hash, email, phone, image_link, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
        );

        $hash = password_hash($pwd, PASSWORD_BCRYPT);
        

        $stmt->bind_param(
            "sssssssi",
            $model->firstName,
            $model->lastName,
            $model->username,
            $hash,
            $model->email,
            $model->phone,
            $model->imageLink,
            $role
        );

        try {
            $stmt->execute();
        }
        catch (PDOException $e) {
            return null;
        }


        return $model;

    }

    // Metoda aktualizuje zakladni udaje uzivatele
    public static function update(ProfileModel $model): ?ProfileModel {
        $model->role = new RoleModel($id = 2); // user
        $model->username = "";

        
        if(!$model->validate()) {
            return null;
        }
        

        $conn = DB::connect();
        $stmt = $conn->prepare(
            "UPDATE users SET
                first_name = ?, 
                last_name = ?, 
                email = ?, 
                phone = ?
            WHERE user_id = ?",
        );


        $stmt->bind_param(
            "ssssi",
            $model->firstName,
            $model->lastName,
            $model->email,
            $model->phone,
            $model->id
        );

        try {
            $stmt->execute();
        }
        catch (PDOException $e) {
            return null;
        }


        return $model;

    }


    // Metoda ziska jednoho uzivatele z database podle ID
    public static function get(int $id): ?ProfileModel {

        $conn = DB::connect();
        $stmt = $conn->prepare(
            "SELECT first_name, last_name, username, email, phone, image_link, role
            FROM users 
            WHERE user_id = ?",
        );

        $stmt->bind_param(
            "i",
            $id
        );

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $data = $result->fetch_assoc();
            $model = new ProfileModel();

            $model->id = $id;
            $model->firstName = $data['first_name'];
            $model->lastName = $data['last_name'];
            $model->username = $data['username'];
            $model->email = $data['email'];
            $model->phone = $data['phone'];
            $model->imageLink = $data['image_link'];
            $model->role = RoleController::get($data['role']);

            return $model;
        }


        // Create profile model from retrieved data from database

        return null;
    }



    // Metoda ziska vsechny uzivatele z databaze
    public static function getAll(): array {

        $conn = DB::connect();
        $stmt = $conn->prepare(
            "SELECT user_id, first_name, last_name, username, email, phone, image_link, role
            FROM users"
        );

        $stmt->execute();

        $stmt->bind_result($userId, $firstName, $lastName, $username, $email, $phone, $imageLink, $roleId);

        $result = [];

        while ($stmt->fetch()) {

            $currentProfile = new ProfileModel();   

            $currentProfile->id = $userId;
            $currentProfile->firstName = $firstName;
            $currentProfile->lastName = $lastName;
            $currentProfile->username = $username;
            $currentProfile->email = $email;
            $currentProfile->phone = $phone;
            $currentProfile->imageLink = $imageLink;
            $currentProfile->role = RoleController::get($roleId);
            
            $result[] = $currentProfile;
        }


        return $result;
    }

    // Metoda najde uzivatele podle jeho uzivatelskeho jmena (username)
    // Tado metoda se vyuziva hlavne pri odesilani zprav
    public static function find(string $username): ?ProfileModel {
        
        $conn = DB::connect();
        $stmt = $conn->prepare(
            "SELECT user_id, first_name, last_name, username, email, phone, image_link, role
            FROM users 
            WHERE username = ?",
        );

        $stmt->bind_param(
            "s",
            $username
        );

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $data = $result->fetch_assoc();
            $model = new ProfileModel();

            $model->id = $data['user_id'];
            $model->firstName = $data['first_name'];
            $model->lastName = $data['last_name'];
            $model->username = $data['username'];
            $model->email = $data['email'];
            $model->phone = $data['phone'];
            $model->imageLink = $data['image_link'];

            return $model;
        }


        // Create profile model from retrieved data from database

        return null;
    }


    // Metoda pro prihlaseni uzivatele
    // Podle parametru username a pwd (heslo) se najde prislusny uzivatel v databazi
    // Samozrejme se hledaji v databazi hashe, takze i zde probiha ziskani hashe pro porovnavani
    public static function login(string $username, string $pwd){
        
        $conn = DB::connect();
        $stmt = $conn->prepare(
            "SELECT user_id, hash 
            FROM users 
            WHERE username = ?",
        );

        $stmt->bind_param(
            "s",
            $username
        );

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $data = $result->fetch_assoc();

            $id = $data['user_id'];
            $hash = $data['hash'];

            $pwdValid = password_verify($pwd, $hash);
            if ($pwdValid) {
                return $id;
            }

        }


        // Create profile model from retrieved data from database

        return null;
    }

    public static function changePwd(string $username, string $oldPwd, string $newPwd){
        

        $id = ProfileController::login($username, $oldPwd);    

        if ($id === null) {
            return false;
        }

        $hash = password_hash($newPwd, PASSWORD_BCRYPT);

        $conn = DB::connect();
        $stmt = $conn->prepare(
            "UPDATE users SET
                hash = ?
            WHERE user_id = ?",
        );


        $stmt->bind_param(
            "si",
            $hash,
            $id
        );

        if ($stmt->execute()) {
            return true;
        }
        


        // Create profile model from retrieved data from database

        return false;
    }
}
