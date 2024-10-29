<?php
// Enable error reporting to help debug issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['email'])) {

    // Define recipient and subject
    $email_to = "santhananivas.dci@gmail.com";
    $email_subject = "You've got a new submission";

    // Function to handle form data errors
    function problem($error)
    {
        echo "Oh, it looks like there is a problem with your form data:<br><br>";
        echo $error . "<br><br>";
        echo "Please fix the issues to proceed.<br><br>";
        die();
    }

    // Validate expected data exists
    if (!isset($_POST['fullName']) || !isset($_POST['email']) || !isset($_POST['message'])) {
        problem('It seems that some required fields are missing.');
    }

    $name = $_POST['fullName']; // required
    $email = $_POST['email'];   // required
    $message = $_POST['message']; // required

    $error_message = "";

    // Validate email format
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
    if (!preg_match($email_exp, $email)) {
        $error_message .= 'The email address does not seem valid.<br>';
    }

    // Validate name format
    $string_exp = "/^[A-Za-z .'-]+$/";
    if (!preg_match($string_exp, $name)) {
        $error_message .= 'The name does not seem valid.<br>';
    }

    // Validate message length
    if (strlen($message) < 2) {
        $error_message .= 'The message should be at least 2 characters long.<br>';
    }

    // Display errors if any
    if (strlen($error_message) > 0) {
        problem($error_message);
    }

    // Prepare email message
    $email_message = "Form details below:\n\n";
    function clean_string($string)
    {
        $bad = array("content-type", "bcc:", "to:", "cc:", "href");
        return str_replace($bad, "", $string);
    }

    $email_message .= "Name: " . clean_string($name) . "\n";
    $email_message .= "Email: " . clean_string($email) . "\n";
    $email_message .= "Message: " . clean_string($message) . "\n";

    // Create email headers
    $headers = 'From: ' . $email . "\r\n" .
        'Reply-To: ' . $email . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Send email and check success
    if (mail($email_to, $email_subject, $email_message, $headers)) {
        echo "Thanks for contacting us. We will get back to you as soon as possible.";
    } else {
        echo "Sorry, there was a problem sending your message. Please try again later.";
    }
}
?>
