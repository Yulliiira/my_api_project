<?php
header('Content-Type: application/json');
echo json_encode([
    'message' => 'Welcome to my API',
    'endpoints' => [
        '/facts' => 'Get or post facts about numbers'
    ]
]);
