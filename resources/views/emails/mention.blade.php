<!DOCTYPE html>
<html>
<head>
    <title>You were mentioned</title>
</head>
<body>
    <p>Hi {{ $mentionedUser->name }},</p>

    <p>You were mentioned in a chat message:</p>

    <blockquote>
        {{ $messageModel->content }}  <!-- <- must match the Mailable public property -->
    </blockquote>

    <p>From: {{ $messageModel->user->name }}</p>
</body>
</html>
