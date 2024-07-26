<?php
include "controllers/ProfileController.php";


if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
}


$id = $_SESSION['user_id'];

$profile = ProfileController::get($id);

if ($profile == null) {
    header('Location: ?page=login');
} 

if (empty($profile->imageLink)) {
    $profile->imageLink = "../resources/profile_picture.svg";
}


if (isset($_POST["editPwd"])) {
    if (isset($_POST["editOldPwd"]) && isset($_POST["editNewPwd"])) {
        $success = ProfileController::changePwd($profile->username, $_POST["editOldPwd"], $_POST["editNewPwd"]);
    }
}

if (isset($_POST['editProfile'])) {

    $editedProfile = $profile;
    $editedProfile->id = $id;
    $editedProfile->firstName = $_POST['editFirstName'] ?? $profile->firstName;
    $editedProfile->lastName = $_POST['editLastName'] ?? $profile->lastName;
    $editedProfile->email = $_POST['editEmail'] ?? $profile->email;
    $editedProfile->phone = $_POST['editPhone'] ?? $profile->phone;
    ProfileController::update($editedProfile);
}

?>
<div class="flex-grow-1 d-flex justify-content-center align-items-center ">

    <!-- user card  -->
    <div class="card" style="width: 30rem;">
        <div class="card-body">

            <!-- username and profile pic  -->
            <div class="row justify-content-end align-items-center">
                <h4 class="col-auto card-title"><?php echo $profile->username ?></h4>
                <div class="col-auto">
                    <img src="<?php echo $profile->imageLink ?>" class="img-fluid rounded-circle" alt="user photo" style="width: 3rem">
                </div>
            </div>

            <!-- personal data -->
            <div class="col p-4" style="font-size: 1.2rem;">
                <div class="row mb-2">
                    <label class="col">First Name</label>
                    <div class="col text-end" id="firstName"><?php echo $profile->firstName ?></div>
                </div>
                <div class="row mb-2">
                    <label class="col">Last Name</label>
                    <div class="col text-end" id="lastName"><?php echo $profile->lastName ?></div>
                </div>
                <div class="row mb-2">
                    <label class="col">Email</label>
                    <div class="col text-end" id="email"><?php echo $profile->email ?></div>
                </div>
                <div class="row mb-2">
                    <label class="col">Phone</label>
                    <div class="col text-end" id="phone"><?php echo $profile->phone ?></div>
                </div>
            </div>

            <!-- profile buttons -->
            <!-- small devices -->
            <div class="row d-flex d-sm-none justify-content-end gap-3 mx-1">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Info</a>
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePwdModal">Change Password</a>
            </div>
            <!-- above small devices -->
            <div class="d-none d-sm-flex justify-content-end gap-3">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Info</a>
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePwdModal">Change Password</a>
            </div>
        </div>
    </div>

</div>


<!-- change pwd modal window -->
<div class="modal fade" id="changePwdModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="editOldPwd" class="form-label">Old Password</label>
                        <input type="password" class="form-control" id="editOldPwd" name="editOldPwd" required minlength="2" maxlength="20">
                    </div>
                    <div class="mb-3">
                        <label for="editNewPwd" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="editNewPwd" name="editNewPwd" required minlength="2" maxlength="40">
                    </div>
                    <input type="submit" value="Save Changes" id="editPwd" name="editPwd" class="btn btn-primary" />
                </form>
            </div>
        </div>
    </div>
</div>

<!-- edit profile modal window -->
<div class="modal fade" id="editProfileModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="editFirstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="editFirstName" name="editFirstName" required minlength="2" maxlength="20">
                    </div>
                    <div class="mb-3">
                        <label for="editLastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="editLastName" name="editLastName" required minlength="2" maxlength="40">
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="editEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPhone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="editPhone" name="editPhone">
                    </div>
                    <input type="submit" value="Save Changes" id="editProfile" name="editProfile" class="btn btn-primary" />
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="pages/common.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.querySelector('#editProfileModal');

        // Get the form and its fields
        const editProfileForm = document.querySelector('#editProfileModal form');
        const editFirstNameInput = document.getElementById('editFirstName');
        const editLastNameInput = document.getElementById('editLastName');
        const editEmailInput = document.getElementById('editEmail');
        const editPhoneInput = document.getElementById('editPhone');

        const firstNameDisplay = document.getElementById('firstName');
        const lastNameDisplay = document.getElementById('lastName');
        const emailDisplay = document.getElementById('email');
        const phoneDisplay = document.getElementById('phone');

        // Set the field values
        editFirstNameInput.value = firstNameDisplay.innerText;
        editLastNameInput.value = lastNameDisplay.innerText;
        editEmailInput.value = emailDisplay.innerText;
        editPhoneInput.value = phoneDisplay.innerText;

        const validateForm = () => {
            let isValid = true;

            // Reset previous validation styles
            editFirstNameInput.classList.remove('is-invalid');
            editLastNameInput.classList.remove('is-invalid');
            editEmailInput.classList.remove('is-invalid');
            editPhoneInput.classList.remove('is-invalid');

            // Validate the fields
            if (editFirstNameInput.value.trim() === '') {
                isValid = false;
                editFirstNameInput.classList.add('is-invalid');
            }

            if (editLastNameInput.value.trim() === '') {
                isValid = false;
                editLastNameInput.classList.add('is-invalid');
            }

            if (!isValidEmail(editEmailInput.value)) {
                isValid = false;
                editEmailInput.classList.add('is-invalid');
            }

            if (!isValidPhone(editPhoneInput.value)) {
                isValid = false;
                editPhoneInput.classList.add('is-invalid');
            }

            return isValid;
        };

        // On form submit
        editProfileForm.addEventListener('submit', function(event) {
            //event.preventDefault();

            if (validateForm()) {

                // TODO: Save changes to db

            }
        });
    });
</script>
