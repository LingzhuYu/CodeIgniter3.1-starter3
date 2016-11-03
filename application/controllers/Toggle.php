<?php
/**
 * Created by PhpStorm.
 * User: Baik
 * Date: 11/3/2016
 * Time: 3:42 PM
 */
class Toggle extends Application {
    public function index()	{
        $origin = $_SERVER['HTTP_REFERER'];
        $role = $this->session->userdata('userrole');
        if ($role == 'user') $role = 'admin';
        else $role = 'user';
        $this->session->set_userdata('userrole', $role);
        redirect($origin);
    }
}