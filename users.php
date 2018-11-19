<?php


class user
{

    // constructor to establish database connection if there isn't one already
    public function __construct($db=NULL)
    {
        if(is_object($db))
        {
            $this->_db = $db;
        }
        else
        {
            $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
            $this->_db = new PDO($dsn, DB_USER, DB_PASS);
        }
    }

    // method to  create account
    public function createAccount($employee_id, $password, $first_name, $last_name)
    {

            //  string to store an sql statement that returns the number of employee_ids in database that are the same as the employee_id entered in the create account form in a variable called the count
        $sql = "SELECT COUNT(employee_id) AS theCount
        FROM users
        WHERE employee_id=:employee_id";

            //  If sql statement is prepared by database without errors
        if($stmt = $this->_db->prepare($sql)) {
                //  Bind $u object to :employee_id parameter in sql statement
            $stmt->bindParam(":employee_id", $employee_id, PDO::PARAM_STR);
                //  Execute sql statement
            $stmt->execute();
                //  Fetches row from executed sql statement and stores it in array
            $row = $stmt->fetch();
                //  If there already an account with that employee_id show return false
            $stmt->closeCursor();
            if($row['theCount']!=0) {
                return false;
            }
            else{
                    //  string that stores a sql statement that inserts employee_id and verification code into users table
                $sql = "INSERT INTO users(employee_id, password, first_name, last_name)
                VALUES(:employee_id, :password, :first_name, :last_name )";

                    //  If sql statement is prepared by database without errors 
                if($stmt = $this->_db->prepare($sql)) {
                    //  Bind $u object to :employee_id parameter in sql statement
                    $stmt->bindParam(":employee_id", $employee_id, PDO::PARAM_STR);
                    //  Bind $p object to :password parameter in sql statement
                    $stmt->bindParam(":password", $password, PDO::PARAM_STR);
                    //  Bind $xml object to :password xmlstring in sql statement
                    $stmt->bindParam(":first_name", $last_name, PDO::PARAM_STR);
                    //  Bind $xml object to :password xmlstring in sql statement
                    $stmt->bindParam(":first_name", $last_name, PDO::PARAM_STR);
                    //  Execute sql statement
                    $stmt->execute();
                    //   Frees up the connection to the server so that other SQL statements may be issued
                    $stmt->closeCursor(); 

                    return true;
                
                }
            }

        }

        else{
            return false;
        }

      
    }

    // method to log in
    public function logIn($employee_id,$password)
    {

        // sql statement that returns rows with matching employee_id and password from user table
        $sql = "SELECT employee_id
        FROM users
        WHERE employee_id=:employee_id
        AND password=:password
        LIMIT 1";

        //  If sql statement is prepared by database without errors
        if($stmt = $this->_db->prepare($sql)) {
            //  Bind $u object to :employee_id parameter in sql statement
            $stmt->bindParam(":employee_id", $employee_id, PDO::PARAM_STR);
            //  Bind $p object to :password parameter in sql statement
            $stmt->bindParam(":password", $password, PDO::PARAM_STR);
            //  Execute sql statement
            $stmt->execute();
            //if there is a user with the same employee_id and password in the user table retur true
            if($stmt->rowCount()==1)
            {
                $_SESSION['employee_id'] = $employee_id;
                $_SESSION['loggedin'] = 1 ;
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }
        
    }


//     // method to delete user account
//     public function deleteAccount($username){

//             //  delete users tasks from database
//         $sql = "DELETE FROM tasks
//         WHERE list_id IN (SELECT id 
//         FROM lists
//         WHERE  user_id = (SELECT id
//         FROM users
//         WHERE username = :username))";

//         //  If sql statement is prepared by database without errors
//         if($stmt = $this->_db->prepare($sql)) {
//             //  Bind $u object to :username parameter in sql statement
//             $stmt->bindParam(":username", $username, PDO::PARAM_STR);
//             //  Execute sql statement
//             $stmt->execute();
//             //   Frees up the connection to the server so that other SQL statements may be issued
//             $stmt->closeCursor();
//             // if a row is affected return true
//                 //delete users lists from database
//             $sql = "DELETE FROM lists
//             WHERE user_id IN (SELECT id
//             FROM users
//             WHERE username = :username)";

//                 //  If sql statement is prepared by database without errors
//             if($stmt = $this->_db->prepare($sql)) {
//                     //  Bind $u object to :username parameter in sql statement
//                 $stmt->bindParam(":username", $username, PDO::PARAM_STR);
//                     //  Execute sql statement
//                 $stmt->execute();
//                     //   Frees up the connection to the server so that other SQL statements may be issued
//                 $stmt->closeCursor();
//                     // if a row is affected return true

//                                 //delete user from database
//                 $sql = "DELETE FROM users
//                 WHERE username = :username";

//                         //  If sql statement is prepared by database without errors
//                 if($stmt = $this->_db->prepare($sql)) {
//                             //  Bind $u object to :username parameter in sql statement
//                     $stmt->bindParam(":username", $username, PDO::PARAM_STR);
//                             //  Execute sql statement
//                     $stmt->execute();
//                             // save the number of rows affected by update in $N
//                     $n = $stmt->rowCount();
//                             //   Frees up the connection to the server so that other SQL statements may be issued
//                     $stmt->closeCursor();
//                             // if a row is affected return true
//                     if($n > 0){

//                         return true;
//                     }
//                     else{
//                         return false;
//                     }
//                 }

//             }       
//         }
        
//     } 

//     public function changePassword($username, $old_password, $new_password){


//         // updates users password
//         $sql = "UPDATE users
//         SET password = :new_password
//         WHERE username = :username
//         AND password = :old_password";

//         //  If sql statement is prepared by database without errors
//         if($stmt = $this->_db->prepare($sql)) {
//             //  Bind $u object to :username parameter in sql statement
//             $stmt->bindParam(':username', $username, PDO::PARAM_STR);
//             //  Bind $new_password object to :new_password parameter in sql statement
//             $stmt->bindParam(':new_password', $new_password, PDO::PARAM_STR);
//             //  Bind $old_password object to :old_password parameter in sql statement
//             $stmt->bindParam(':old_password', $old_password, PDO::PARAM_STR);
//             //  Execute sql statement
//             $stmt->execute();
//             // save the number of rows affected by update in $N
//             $n = $stmt->rowCount();
//             //   Frees up the connection to the server so that other SQL statements may be issued
//             $stmt->closeCursor();
//             // if a row is affected return true
//             if($n > 0){
//                 // return $n
//                 return TRUE;            
//             }
//             else{
//                 return FALSE;
//             }
//         }
//     }

//     public function checkUsername($username){
//         // count the number of usernames the user has entered
//         $sql = "SELECT COUNT(username) AS theCount
//         FROM users
//         WHERE username=:username";

//         //  If sql statement is prepared by database without errors
//         if($stmt = $this->_db->prepare($sql)) {
//             //  Bind $u object to :username parameter in sql statement
//             $stmt->bindParam(":username", $username, PDO::PARAM_STR);
//             //  Execute sql statement
//             $stmt->execute();
//             //  Fetches row from executed sql statement and stores it in array
//             $row = $stmt->fetch();
//             //  If there already an account with that username show error message
//             $stmt->closeCursor();
//             // send the number of usernames in table to signup.js
//             return $row['theCount'];
//         }
//     }

//     public function changeColour($colour, $username){

//         // updates users password
//         $sql = "UPDATE users
//         SET colour = :colour
//         WHERE username = :username";

//             //  If sql statement is prepared by database without errors
//         if($stmt = $this->_db->prepare($sql)) {
//             //  Bind $colour object to :colour parameter in sql statement
//             $stmt->bindParam(":colour", $colour, PDO::PARAM_STR);
//             //  Bind $u object to :username parameter in sql statement
//             $stmt->bindParam(":username", $username, PDO::PARAM_STR);
//             //  Execute sql statement
//             $stmt->execute();
//             // save the number of rows affected by update in $N
//             $n = $stmt->rowCount();
//             //  If there already an account with that username show error message
//             $stmt->closeCursor();
//             if($n > 0){
//                     // return $n
//                     return TRUE;            
//             }
//             else{
//                 return FALSE;
//             }
//         }

//     }

//     public function getColour($username){

//         $sql = "SELECT colour
//         FROM users
//         WHERE username = :username";

//         //  If sql statement is prepared by database without errors
//         if($stmt = $this->_db->prepare($sql)) {
//                 //  Bind $u object to :username parameter in sql statement
//             $stmt->bindParam(":username", $username, PDO::PARAM_STR);
//                 //  Execute sql statement
//             $stmt->execute();
//                 //  Fetches row from executed sql statement and stores it in array
//             $row = $stmt->fetch();
//                 //  If there already an account with that username show return false
//             $stmt->closeCursor();

//             return $row['colour'];

//         }
//     }
}

?>