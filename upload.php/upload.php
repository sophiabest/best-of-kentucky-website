<?php

$to = 'bestofky@aol.com';
$subject = 'New Estimate Request';

// Collect form data
$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$phone = htmlspecialchars($_POST['phone']);
$details = htmlspecialchars($_POST['details']);

$message = "New Estimate Request:\n\n";
$message .= "Name: $name\n";
$message .= "Email: $email\n";
$message .= "Phone: $phone\n";
$message .= "Details: $details\n";

// Create boundary
$boundary = md5(time());
$headers = "From: $email\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

$body = "--$boundary\r\n";
$body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
$body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$body .= $message . "\r\n";

// Handle uploaded files
if(isset($_FILES['photos'])){
    foreach($_FILES['photos']['tmp_name'] as $key => $tmp_name){
        $file_name = $_FILES['photos']['name'][$key];
        $file_size = $_FILES['photos']['size'][$key];
        $file_type = $_FILES['photos']['type'][$key];

        if($file_size > 0){
            $handle = fopen($tmp_name, 'r');
            $content = fread($handle, $file_size);
            fclose($handle);
            $encoded_content = chunk_split(base64_encode($content));

            $body .= "--$boundary\r\n";
            $body .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
            $body .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= $encoded_content . "\r\n";
        }
    }
}

$body .= "--$boundary--";

if(mail($to, $subject, $body, $headers)){
    echo 'success';
} else {
    echo 'error';
}
?>