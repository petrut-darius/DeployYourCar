<?php

namespace App;

enum CarPermissions:string {
    case CREATE = "car:create";
    case UPDATE = "car:update";
    case UPDATE_ANY = "car:update-any";
    case DELETE = "car:delete";
    case DELETE_ANY = "car:delete-any";
}
