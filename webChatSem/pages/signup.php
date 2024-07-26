<?php

if (isset($_SESSION['user_id'])) {
    header('Location: ?page=profile');
}

if (isset($_POST["createProfileSubmit"])) {
    include "controllers/ProfileController.php";
    include "services/ImageSaver.php";


    $profile = new ProfileModel();

    $profile->firstName = $_POST['firstNameInput'] ?? null;
    $profile->lastName = $_POST['lastNameInput'] ?? null;
    $profile->username = $_POST['usernameInput'] ?? null;
    $profile->email = $_POST['emailInput'] ?? null;
    $profile->phone = $_POST['phoneInput'] ?? null;
    $profile->role = new RoleModel( $id = 2 );
    $pwd = $_POST['pwdInput'] ?? null;

    if ($profile->validate()) {
        if ($_FILES['imageInput']) {
            $newFilename = $profile->username . "_profile_picture";
            $profile->imageLink = ImageSaver::compressAndSave($_FILES['imageInput'], $newFilename, 1024);
        }
        else {
            $profile->imageLink = "https://bvwa2-sem.onrender.com/media/profile_picture.svg";
        }
    
        $profile = ProfileController::add($profile, $pwd);
    
        if ($profile != null) {
            header("Location: ?page=login");
        }
    }


}

?>
<script type="text/javascript" src="./pages/signup.js"></script>

<div class="flex-grow-1 d-flex justify-content-center align-items-center ">
    <!-- signup card -->
    <div class="card">
        <h2 class="card-header align-self-stretch text-center">Signup</h2>
        <div class="card-body" x-data="signUpData()">

            <form @submit.prevent="submitForm() ? $el.submit() : null" class="d-flex flex-row justify-content-start" enctype="multipart/form-data" method="post" action="">

                <div class="d-flex flex-md-row flex-column">

                    <!-- profile picture -->
                    <div x-data="imageViewer('./resources/profile_picture.svg')" class="align-items-center d-flex flex-column mx-5 mt-5 mb-3 gap-2">
                        <label for="imageInput" class="align-self-center">Profile Picture</label>
                        <img :src="imageUrl" class="rounded" width="180" src="./resources/profile_picture.svg" alt="profile pic">
                        <input class="d-none" id="imageInput" name="imageInput" type="file" accept="image/*" @change="fileChosen">
                        <label for="imageInput" class="btn btn-primary">Upload Picture</label>
                    </div>

                    <!-- user text info & submit -->
                    <div class="d-flex flex-column gap-3 m-3">
                        <div class="row">
                            <div class="col">
                                <label for="firstNameInput" class="form-label">First Name</label>
                                <input id="firstNameInput" name="firstNameInput" type="text" x-on:input="validateFirstName" x-model="firstName" class="form-control" :class="{'is-invalid': errors.firstName}" />
                                <div class="invalid-feedback" x-text="errors.firstName"></div>
                            </div>
                            <div class="col">
                                <label for="lastNameInput" class="form-label">Last Name</label>
                                <input id="lastNameInput" name="lastNameInput" type="text" x-on:input="validateLastName" x-model="lastName" class="form-control" :class="{'is-invalid': errors.lastName}" />
                                <div class="invalid-feedback" x-text="errors.lastName"></div>
                            </div>
                        </div>
                        <div>
                            <label for="usernameInput" class="form-label">Username</label>
                            <input id="usernameInput" name="usernameInput" type="text" x-on:input="(validateUsername() && debounceUsername())" x-model="username" class="form-control" :class="{'is-invalid': errors.username, 'is-valid': usernameAvailable }" />
                            <div class="invalid-feedback" x-text="errors.username"></div>
                            <div class="valid-feedback">username is available</div>
                        </div>
                        <div>
                            <label for="emailInput" class="form-label">Email address</label>
                            <input id="emailInput" name="emailInput" type="email" x-on:input="validateEmail" x-model="email" class="form-control" :class="{'is-invalid': errors.email}" aria-describedby="emailHelp">
                            <div class="invalid-feedback" x-text="errors.email"></div>
                        </div>
                        <div>
                            <label for="pwdInput" class="form-label">Password</label>
                            <input id="pwdInput" name="pwdInput" type="password" x-on:input="validatePassword" x-model="password" class="form-control" :class="{'is-invalid': errors.password}" />
                            <div class="invalid-feedback" x-text="errors.password"></div>
                        </div>
                        <div>
                            <label for="phoneInput" class="form-label">Phone</label>
                            <input id="phoneInput" name="phoneInput" type="text" x-on:input="validatePhone" x-model="phone" class="form-control" :class="{'is-invalid': errors.phone}" />
                            <div class="invalid-feedback" x-text="errors.phone"></div>
                        </div>

                        <!-- submit btn -->
                        <div class="d-flex flex-column">
                            <!--<button type="submit" id="createUserSubmit" name="createUserSubmit" class="col btn btn-primary mt-3 mb-1">Signup</button>-->
                            <input name="createProfileSubmit" type="hidden" value="createProfileSubmit" />
                            <input type="submit" value="Signup" class="col btn btn-primary mt-3 mb-1">
                            <span class="d-flex flex-row justify-content-center">
                                Already have an account?
                                <a href="?page=login" class="ms-1">Login</a>
                            </span>

                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
