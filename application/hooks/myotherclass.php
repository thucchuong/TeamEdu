<?php

class MyOtherClass {

    function MyOtherfunction() {

        $CI =& get_instance();
        $CI->db->from('settings');
        $CI->db->where(['type'=>'email']);
        $query = $CI->db->get();
        $confiemail = $query->result_array();
        
        if(!empty($confiemail[0]['data'])){
            $confiemail = json_decode($confiemail[0]['data']);

            if($confiemail->smtp_encryption == 'off'){
                $smtp_encryption = $confiemail->smtp_host;
            }else{
                $smtp_encryption = $confiemail->smtp_encryption.'://'.$confiemail->smtp_host;
            }
            
            $data = array(
                'mailtype' => $confiemail->mail_content_type,
                'protocol' => 'SMTP',
                'smtp_host' => $smtp_encryption,
                'smtp_port' => $confiemail->smtp_port,
                'smtp_user' => $confiemail->email,
                'smtp_pass' => $confiemail->password,
                'charset' => 'utf-8',
                'newline' => "\r\n",
            ); 

            $CI->config->set_item('email_config', $data);
        }

    }

    function verify_doctor_brown()
    {
        $exclude_uris = array(
            base_url("purchase_code"),
            base_url("purchase_code/validator"),
            base_url("auth/logout"),
            base_url("auth/login"),
            base_url("auth"),
            base_url("home"),
            base_url(),
        );

        $doctor_brown = get_system_settings('doctor_brown');
        

        if ((empty($doctor_brown)) && !in_array(current_url(), $exclude_uris)) {
            /* redirect him to the page where he can enter the purchase code */
            redirect(base_url("purchase_code"));
        } else {
            if ((!empty($doctor_brown) && !in_array(current_url(), $exclude_uris))) {
                /* redirect him to the page where he can enter the purchase code */
                $calculated_time_check = $time_check = '';

                $time_check = (isset($doctor_brown["time_check"])) ? trim($doctor_brown["time_check"]) : "";
                $code_bravo = (isset($doctor_brown["code_bravo"])) ? trim($doctor_brown["code_bravo"]) : "";
                $code_adam = (isset($doctor_brown["code_adam"])) ? trim($doctor_brown["code_adam"]) : "";
                $dr_firestone = (isset($doctor_brown["dr_firestone"])) ? trim($doctor_brown["dr_firestone"]) : "";
                $str = $code_bravo . "|" . $code_adam . "|" . $dr_firestone;
                $calculated_time_check = hash('sha256', $str);
                if (empty($calculated_time_check) || empty($time_check)) {
                    if (!in_array(current_url(), $exclude_uris)) {
                        redirect(base_url("purchase_code"));
                    }
                }
            }
           
        }
    }

}
