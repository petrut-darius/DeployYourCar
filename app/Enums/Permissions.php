<?php
namespace App\Enums;

enum Permissions: string {
    //cars
    case ManageCars = "manage:cars";
    case CreateCars = "create:cars";
    case UpdateCars = "update:cars";
    case DeleteCars = "delete:cars";

    //groups
    case ManageGroups = "manage:groups";
    case CreateGroups = "create:groups";
    case UpdateGroups = "update:groups";
    case DeleteGroups = "delete:groups";

    //likes
    case ManageLikes = "manage:likes";
    case CreateLikes = "create:likes";
    case UpdateLikes = "update:likes";
    case DeleteLikes = "delete:likes";

    //modifications
    case ManageModifications = "manage:modifications";
    case CreateModifications = "create:modifications";
    case UpdateModifications = "update:modifications";
    case DeleteModifications = "delete:modifications";

    //permissions
    case ManagePermissions = "manage:permissions";
    case CreatePermissions = "create:permissions";
    case UpdatePermissions = "update:permissions";
    case DeletePermissions = "delete:permissions";

    //replies
    case ManageReplies = "manage:replies";
    case CreateReplies = "create:replies";
    case UpdateReplies = "update:replies";
    case DeleteReplies = "delete:replies";

    //stories
    case ManageStories = "manage:stories";
    case CreateStories = "create:stories";
    case UpdateStories = "update:stories";
    case DeleteStories = "delete:stories";

    //tags
    case ManageTags = "manage:tags";
    case CreateTags = "create:tags";
    case UpdateTags = "update:tags";
    case DeleteTags = "delete:tags";

    //types
    case ManageTypes = "manage:types";
    case CreateTypes = "create:types";
    case UpdateTypes = "update:types";
    case DeleteTypes = "delete:types";

    //users
    case ManageUsers = "manage:users";
    case CreateUsers = "create:users";
    case UpdateUsers = "update:users";
    case DeleteUsers = "delete:users";
}