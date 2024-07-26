<?php

include "validation/ValidatorInterface.php";

/**
 * Model reprezentujici role
 */
class RoleModel
{
    public ?int $id = null;
    public ?string $roleName = null;

    public function validate(): bool {
        if (
            $this->roleName === null
            ) {
                return false;
        }

        return true;
    }

}