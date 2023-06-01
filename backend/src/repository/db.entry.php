<?php
namespace D002834\Backend\repository;

use D002834\Backend\repository\UserRepository\User;

function populateDatabase() {
    User::createTable();
}
