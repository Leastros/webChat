<?php

include "controllers/ProfileController.php";

if (!isset($_SESSION['user_id']) && !isset($_SESSION['is_admin']) && $_SESSION['is_admin'] !== true) {
    header('Location: ?page=login');
}


if (isset($_POST['submitEdit'])) {
    $profile = new ProfileModel();
    $profile->firstName = $_POST['editFirstName'];
    $profile->lastName = $_POST['editLastName'];
    $profile->email = $_POST['editEmail'];
    $profile->phone = $_POST['editPhone'];
    $profile->id = $_POST['editId'];
    
    ProfileController::update($profile);
}


$users = ProfileController::getAll();
$selectedUser = null;
if (isset($_GET['editUser'])) {
    $userId = $_GET['editUser'];
    foreach($users as $user) {
        if ($userId == $user->id)
            $selectedUser = $user;
    }
}



?>

<div x-on:resize.window="onWindowResize" x-data="messageApp()" class="rounded bg-body gap-2 m-2 d-flex flex-grow-1 border-0">
    <div class="col overflow-auto flex-column">
        <div class="list-group overflow-auto">
            <?php foreach($users as $user) { ?>
                <a href="?page=admin&editUser=<?php echo $user->id ?>" class="p-3  border-end-0 border-start-0 rounded-0 d-flex  list-group-item list-group-item-action">
                    <?php $imageLink = empty($user->imageLink) ? "resources/profile_picture.svg" : $user->imageLink  ?>
                    <img class="me-3 rounded-circle" width="50" height="50" src="<?php echo $imageLink ?>" style="min-width: 50px; width: 50px" />

                    <div class="overflow-auto">
                        <div class="d-flex justify-content-between">

                            <h5 class="">
                                <?php echo $user->firstName . " " . $user->lastName;  ?>
                            </h5>
                            <h6 class="">
                                <?php echo "(" . $user->username . ")";  ?>
                            </h6>


                        </div>


                    </div>
            </a>
            <?php } ?>
        </div>
    </div>

    <?php if($selectedUser != null) { ?>
    <form method="post" action="" class="card col m-2 p-3">
        <div class="mb-3">
            <label for="editFirstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="editFirstName" name="editFirstName" required minlength="2" maxlength="20" value="<?php echo $selectedUser->firstName ?>">
        </div>
        <div class="mb-3">
            <label for="editLastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="editLastName" name="editLastName" required minlength="2" maxlength="40" value="<?php echo $selectedUser->lastName ?>">
        </div>
        <div class="mb-3">
            <label for="editEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="editEmail" name="editEmail" required value="<?php echo $selectedUser->email ?>">
        </div>
        <div class="mb-3">
            <label for="editPhone" class="form-label">Phone</label>
            <input type="tel" class="form-control" id="editPhone" name="editPhone" value="<?php echo $selectedUser->phone ?>">
        </div>
        <input type="hidden" id="editId" name="editId" value="<?php echo $selectedUser->id ?>">
        <input type="submit" value="Save Changes" id="submitEdit" name="submitEdit" class="btn btn-primary"  />
    </form>
    <?php } ?>
</div>

