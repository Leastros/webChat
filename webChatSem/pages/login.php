<?php

if (isset($_POST['loginUser'])) {
    
    include "controllers/ProfileController.php";


    if (isset($_POST['pwdInput']) && isset($_POST['usernameInput'])) {
        $username = $_POST['usernameInput'] ?? "";
        $password = $_POST['pwdInput'] ?? "";
        
        $id = ProfileController::login($username, $password);
        
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = $id;
        $_SESSION['is_admin'] = false;
        
        if ($id != null) {
            $profile = ProfileController::get($id);
            if ($profile->role->id == 1 && $profile->validate()) {
                $_SESSION['is_admin'] = true;
            }
        }

    }
    else {
        $invalidCred = true;
    }

}

if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
        header('Location: ?page=admin');
    }
    else {
        header('Location: ?page=profile');
    }
}

?>

<div class="flex-grow-1 d-flex justify-content-center align-items-center ">

    <!-- login card -->
    <div class="card" style="width: 25rem;">
        <h2 class="card-header align-self-stretch text-center">Login</h2>
        <div class="card-body d-flex flex-column gap-2 m-3">

            <form method="post" action="" class="d-flex flex-column gap-3">

                <div>
                    <label for="usernameInput" class="form-label">Username</label>
                    <input id="usernameInput" name="usernameInput" type="text" class="form-control">
                </div>
                <div>
                    <label for="pwdInput" class="form-label">Password</label>
                    <input id="pwdInput" type="password" name="pwdInput" type="text" class="form-control">

                </div>

                <?php if (!empty($invalidCred)) : ?>
                    <div class="text-center text-danger" role="alert">
                        Invalid Credentials
                    </div>
                <?php endif; ?>


                <!-- submit btn -->
                <div class="d-flex flex-column">
                    <input type="submit" id="loginUser" name="loginUser" value="Login" class="col btn btn-primary mt-3 mb-1">
                    <span class="d-flex flex-row justify-content-center">
                        Don't have an account?
                        <a href="?page=signup" class="ms-1">Sign Up</a>
                    </span>
                </div>

            </form>

        </div>
    </div>

</div>
