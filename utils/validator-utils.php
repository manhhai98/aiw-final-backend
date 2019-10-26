<?php

class ValidatorUtils {
    public static function hasSpecialChars($input) {
        return preg_match("#$%^&*()+=[]';,/{}|:<>?~", $input);
    }
}
?>