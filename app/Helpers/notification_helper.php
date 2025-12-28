<?php

if (!function_exists('get_unread_count')) {
    function get_unread_count() {
        $session = session();
        
        // AuthController sets 'isLoggedIn', NOT 'logged_in'
        if (!$session->get('isLoggedIn')) {
            return 0;
        }
        
        try {
            $model = new \App\Models\NotificationModel();
            // Ensure we use the correct user_id from session
            $userId = $session->get('user_id');
            return $model->getUnreadCount($userId);
        } catch (\Exception $e) {
            return 0;
        }
    }
}
