<?php

/**
 * Model reprezentujici uzivatele
 */
class ProfileModel
{
    public ?int $id = null;
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?string $username = null;
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $imageLink = null;
    public ?RoleModel $role = null;

    public function validate(): bool {
        if (
            $this->firstName === null ||
            $this->lastName === null ||
            $this->username === null ||
            $this->email === null ||
            $this->phone === null ||
            $this->role === null
            ) {
                return false;
        }

        if ($this->imageLink === null) {
            $this->imageLink = "../resources/profile_picture.svg";
        }

        // Email validation
        if(!preg_match("/^[\w.\-]+@([\w-]+\.)+[\w-]{2,4}$/", $this->email))
            return false;

        return true;
    }
}