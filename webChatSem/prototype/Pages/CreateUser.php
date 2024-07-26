<?php
require 'Services/PasswordHasher.php';
?>

<div>
    <form action="" method="POST">
        <label for="avatar">Uzivatelske jmeno: </label>
        <input type="text" id="username" name="username" required/>
        <label for="avatar">Heslo: </label>
        <input type="text" id="password" name="password" required/>
        <input type="submit" value="Potvrdit" name="generate" id="generate">
    </form>
    <div>
        <?php
        if (isset($_POST['generate'])) {


            $salt = PasswordHasher::generateSalt();
            $hash = PasswordHasher::hashPassword($_POST['username'], $_POST['password'], $salt);

            echo "Jmeno: ".$_POST['username']."<br>";
            echo "Salt: ".$salt."<br>";
            echo "Hash: ".$hash."<hr>";
            echo "Heslo (neukladat, zapamatovat): ".$_POST['password']."<hr>";
        }
        ?>
    </div>
</div>

<style lang="scss" scoped>
    form {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;

        input{
            margin-bottom: 16px;
        }
    
        label{
            margin-bottom: 4px;
        }
    }

</style>