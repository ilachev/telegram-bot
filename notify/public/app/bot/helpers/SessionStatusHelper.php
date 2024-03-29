<?php

namespace Pcs\Bot\helpers;

class SessionStatusHelper
{
    const DEFAULT = 0;
    const NOT_ADMIN = 1;

    const START = 2;
    const ADMIN_START = 3;

    const SUBSCRIBE = 4;

    const USER_MANAGEMENT = 5;

    const MANAGE_REDIRECTS = 6;
    const ADMIN_MANAGE_REDIRECTS = 7;

    const ADDING_MAPPING = 8;
    const DELETING_MAPPING = 9;
    const EDITING_MAPPING = 10;
    const VIEW_MAPPING = 11;

    const ADDING_DIRECTIONS = 12;
    const DELETING_DIRECTIONS = 13;

    const VIEW_ALLOWED_DIRECTIONS_REDIRECTS = 14;
    const ADDING_EXTENSION_REDIRECT = 15;

    const REDIRECT_ALREADY_HAVE = 16;
    const ADDING_REDIRECT_ANOTHER_NUMBER = 17;
    const ADDING_REDIRECT_ANOTHER_NUMBER_SUCCESS = 18;

    const ADDING_DIRECTION_FIRST_STEP = 19;
    const ADDING_DIRECTION_SECOND_STEP = 20;

    const DELETING_DIRECTIONS_FIRST_STEP = 21;
    const DELETING_DIRECTIONS_SECOND_STEP = 22;

    const DELETING_MAPPING_FIRST_STEP = 23;
    const DELETING_MAPPING_SECOND_STEP = 24;

    const ADDING_MAPPING_FIRST_STEP = 25;
    const ADDING_MAPPING_SECOND_STEP = 26;
    const ADDING_MAPPING_THIRD_STEP = 27;
    const ADDING_MAPPING_ALREADY_HAVE = 28;

    const EDITING_MAPPING_FIRST_STEP = 29;
    const EDITING_MAPPING_SECOND_STEP = 30;
    const EDITING_MAPPING_THIRD_STEP = 31;
    const EDITING_MAPPING_FOURTH_STEP = 32;
    const EDITING_MAPPING_NOT_HAVE = 33;
}