<?php
namespace App\[[DOM]]\Points;

class Points_Member extends Points_Base
{

    function Login_Before()
    {
        if ($this->Session(USER_ID) != "") {
         //   $this->ActionFor("Member", "Center");
          //  return false;
        }
        
        return true;
    }

    function Register_Before()
    {
        if ($this->Session(USER_ID) != "") {
            $this->ActionFor("Member", "Center");
            return false;
        }
        
        return true;
    }
}
?>