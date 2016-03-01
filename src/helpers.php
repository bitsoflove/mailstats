<?php

/**
 * Because the message id we get back from Mailgun is not in the same form
 * we have to remove the <> that are sometimes added
 *
 * @param string $message_id
 * @return string
 */
function cleanup_mailgun_message_id($message_id)
{
    $pattern = "^<([\\w@.]+)>$";

    if (preg_match("/$pattern/", $message_id, $matches)) {
        return $matches[1];
    }

    return $message_id;
}
