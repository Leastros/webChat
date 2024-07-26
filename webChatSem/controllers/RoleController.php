<?php

include "models/RoleModel.php";


/**
 * Controller pro praci s tabulkou rolí
 * Veskerer operace s databazi se vytvari pres prepare statementy a s pouzitim parameteru, tudiz je DB chranena proti SQLInjection
 */
class RoleController
{
    // Metoda prida novou roly
    // V podstate se nevyuziva, protoze role jsou zadefinovany v databazi pevne
    public static function add(RoleModel $model): RoleModel {
        $role = 1;
        $conn = DB::connect();
        $stmt = $conn->prepare(
            "INSERT INTO roles (role) 
            VALUES (?)",
        );
        $stmt->bind_param(
            "s",
            $model->roleName,
        );
        $stmt->execute();

        $model->id = $conn->insert_id;

        return $model;
    }


    // Ziskani konkretni role podle jeji ID
    public static function get(int $id): ?RoleModel {
        $role = 1;
        $conn = DB::connect();
        $stmt = $conn->prepare(
            "SELECT role_id, role FROM roles WHERE role_id = ?",
        );

        $stmt->bind_param(
            "i",
            $id,
        );

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $data = $result->fetch_assoc();
            $model = new RoleModel();

            $model->id = $data['role_id'];
            $model->roleName = $data['role'];

            return $model;
        }


        return null;
    }
}