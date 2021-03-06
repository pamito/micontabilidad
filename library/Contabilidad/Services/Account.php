<?php
class Contabilidad_Services_Account extends Contabilidad_Services_Abstract {
    const NOT_ALL_PARAMS = "not all params";
    const NOT_AUTHENTICATED = "not authenticated";
    const UNSELECTED_ACCOUNT = "unselected account";

    public function createAccount($params){
        $resp = array("result" => "failure", "reason" => self::NOT_ALL_PARAMS);
        if($this->reviewParam('date_end', $params) 
           && $this->reviewParam('date_ini', $params)
           && $this->reviewParam('name', $params)
           && $this->reviewParam('id_currency', $params)){
           if (Contabilidad_Auth::getInstance()->getUser()){
               $user = Contabilidad_Auth::getInstance()->getUser();
               $account = Proxy_Account::getInstance()->createNew($user, $params);
               $serialized = Proxy_Account::getInstance()->serializer($account);
               $resp["account"] = $serialized;
               $resp["result"] = "success";
               $resp["reason"] = "OK";
           }  else {
               $resp["reason"] = self::NOT_AUTHENTICATED;
           }
        }
        return $resp;
    }
    
    public function deleteAccount ($id){
        
        $resp = array("result" => "failure", "reason" => self::UNSELECTED_ACCOUNT);
        if ($id){
        $account = Proxy_Account::getInstance()->findById($id);
            if ($account->id_user == Contabilidad_Auth::getInstance()->getUser()->id){
                $account->delete();
                $resp["result"] = "success";
                $resp["reason"] = "OK";
            } else {
                $resp["reason"] = "not autthorized usser";
            }
        }
        return $resp;
    }
}

