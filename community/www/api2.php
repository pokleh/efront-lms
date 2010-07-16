<?php
/*

This file lets you to make requests to eFront and get some useful information in xml format.

You need to be an administrator to use eFront API. First of all you need a token that must be used

in all the next requests for authentication. To get a token you have to call /api.php?action=token

The returned token must be used in the next calls. Action argument determnines the action that you want to do.

Foreach request you must provide the action argument, a token and possibly a set for other arguments.

Below are the available action arguments an the corresponding arguments needed (where <token> is the returned token).

/api.php?token=<token>&action=login&username=<login>&password=<password> 		logs <login> in eFront API (<login> must be an administrator account)

/api.php?token=<token>&action=efrontlogin&login=<login> 			logs <login> in eFront

/api.php?token=<token>&action=create_lesson&name=<name>&category=<category_id>&course_only=<course_only>&language=<language>&price=<price>	creates a new lesson with corresponding fields	

/api.php?token=<token>&action=create_user&login=<login>&password=<password>&email=<email>&languages=<languages>&name=<name>&surname<surname> 	creates a new user with corresponding fields	

/api.php?token=<token>&action=update_user&login=<login>&password=<password>&email=<email>&name=<name>&surname<surname> 	updates a user profile with corresponding fields	

/api.php?token=<token>&action=deactivate_user&login=<login>			deactivates user <login>

/api.php?token=<token>&action=activate_user&login=<login>			activates user <login>

/api.php?token=<token>&action=remove_user&login=<login>				deletes user <login>

/api.php?token=<token>&action=groups                                                		returns all groups defined in eFront

/api.php?token=<token>&action=group_info&group=<group_id>                           		returns <group_id> information 

/api.php?token=<token>&action=group_to_user&login=<login>&group=<group_id>         			assigns group with <group_id> to user <login>   

/api.php?token=<token>&action=group_from_user&login=<login>&lesson=<group_id>       		undo assignment for group with <group_id> to user <login>

/api.php?token=<token>&action=lesson_to_user&login=<login>&lesson=<lesson_id>&type=<user_type>		assigns lesson with <lesson_id> to user <login> with role <user_type> 

/api.php?token=<token>&action=activate_user_lesson&login=<login>&lesson=<lesson_id> 		activate assignment for lesson with <lesson_id> to user <login>

/api.php?token=<token>&action=deactivate_user_lesson&login=<login>&lesson=<lesson_id> 		deactivate assignment for lesson with <lesson_id> to user <login>

/api.php?token=<token>&action=lesson_from_user&login=<login>&lesson=<lesson_id>				undo assignment for lesson with <lesson_id> to user <login>

/api.php?token=<token>&action=course_to_user&login=<login>&course=<course_id>&type=<user_type>		assigns course with <course_id> to user <login> with role <user_type>   

/api.php?token=<token>&action=course_from_user&login=<login>&courses=<course_id>		undo assignment for course with <course_id> to user <login> 

/api.php?token=<token>&action=user_lessons&login=<login> 								returns the lessons that are assigned to the user <login>

/api.php?token=<token>&action=user_courses&login=<login> 								returns the courses that are assigned to the user <login>

/api.php?token=<token>&action=lesson_info&lesson=<lesson_id>							returns <lesson_id> information 

/api.php?token=<token>&action=user_info&login=<login>									returns <login> information 

/api.php?token=<token>&action=lessons													returns all lessons defined in eFront

/api.php?token=<token>&action=courses													returns all courses defined in eFront

/api.php?token=<token>&action=course_info&course=<course_id>							returns <course_id> information

/api.php?token=<token>&action=course_lessons&course=<course_id>							returns lessons containing in <course_id>

/api.php?token=<token>&action=activate_user_course&login=<login>&course=<course_id> 	activate assignment for all lessons within <course_id> to user <login>

/api.php?token=<token>&action=deactivate_user_course&login=<login>&course=<course_id> 	deactivate assignment for all lessons within <course_id> to user <login>

/api.php?token=<token>&action=course_from_user&course=<course_id>&login=<login>			undo assignment for course with <course_id> to user <login>

/api.php?token=<token>&action=catalog													returns the list with all courses and lessons of the system

/api.php?token=<token>&action=logout													logs out from eFront API



API returns xml corresponding to the action argument. For actions like efrontlogin, activate_user etc it returns a status entity ("ok" or "error").

In case of error it returns also a message entity with description of the error occured.

*/
 $path = "../libraries/";
 require_once $path."configuration.php";
    $data = eF_getTableData("configuration", "value", "name='api'"); //Read current values
    $api = $data[0]['value'];
    if ($api == 1) {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            switch($_GET['action']) {
                case 'token':
                    $token = createToken(30);
                    if (strlen($token) == 30) {
                        $insert['token'] = $token;
                        $insert['status'] = "unlogged";
                        $insert['expired'] = 0;
                        $insert['create_timestamp'] = time();
                        eF_insertTableData("tokens", $insert);
                        echo "<xml>";
      echo "<token>".$token."</token>";
      echo "</xml>";
                    }
                    break;
                 case 'efrontlogin':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        $token = $_GET['token'];
                        $creds = eF_getTableData("tokens t, users u", "u.login, u.password, u.user_type", "t.users_LOGIN = u.LOGIN and t.token='$token'");
                        if (sizeof($creds) == 0) {
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Invalid username</message>";
       echo "</xml>";
                            break;
                        }
                        try {
       $user = EfrontUserFactory :: factory($_GET['login']);
      } catch (Exception $e) {
       echo "<xml>";
       echo "<status>error</status>";
                            echo "<message>This user does not exist</message>";
       echo "</xml>";
       break;
      }
      $password = $user -> user['password'];
      session_start();//Otherwise it won't store session data
      $ok = $user -> login($password, true);
                        if ($ok) {
       echo "<xml>";
                            echo "<status>ok</status>";
       echo "</xml>";
                        }
                        else{
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Unable to update log</message>";
       echo "</xml>";
                        }
                        break;
                    }
                    else{
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
                        break;
                    }
                 }
     case 'efrontlogout':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        $token = $_GET['token'];
      if (isset($_GET['login'])) {
       try {
        $user = EfrontUserFactory :: factory($_GET['login']);
        try {
         if ($user -> isLoggedIn()) {
          $user -> logout();
          echo "<xml>";
          echo "<status>ok</status>";
          echo "</xml>";
          break;
         } else {
          echo "<xml>";
          echo "<status>error</status>";
          echo "<message>User is not logged in</message>";
          echo "</xml>";
          break;
         }
        } catch (Exception $e) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>User can not logout</message>";
         echo "</xml>";
         break;
        }
       } catch (Exception $e) {
        echo "<xml>";
        echo "<status>error</status>";
        echo "<message>This user does not exist</message>";
        echo "</xml>";
        break;
       }
      } else {
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
       echo "</xml>";
       break;
      }
     } else {
      echo "<xml>";
      echo "<status>error</status>";
      echo "<message>Invalid token</message>";
      echo "</xml>";
      break;
     }
    }
                 case 'login':{
     if (isset($_GET['username']) && isset($_GET['password']) && isset($_GET['token'])) {
      try {
       $user = EfrontUserFactory :: factory($_GET['username']);
      } catch (EfrontUserException $e) {
       if ($e -> getCode() == EfrontUserException :: INVALID_PARAMETER) {
                                echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid login format</message>";
                                echo "</xml>";
        exit;
       }
                        }
      if ($user -> user['user_type'] == "administrator") {
       $login = $_GET['username'];
       $password = EfrontUser::createPassword($_GET['password']);
       $token = $_GET['token'];
       $tmp = eF_getTableData("tokens","token","status='unlogged'");
       $result = eF_getTableData("tokens","token","status='logged' and users_LOGIN='".$login."'");
       $tmp2 = eF_getTableData("users", "password","login='$login'");
       $pwd = $tmp2[0]['password'];
       if ($pwd != $password) {
        echo "<xml>";
        echo "<status>error</status>";
        echo "<message>Invalid password</message>";
        echo "</xml>";
        exit;
       }
       if (sizeof($tmp) == 0 && sizeof($result) > 0) {
        echo "<xml>";
        echo "<status>error</status>";
        echo "<message>You have already logged in</message>";
        echo "</xml>";
        exit;
       } elseif (sizeof($tmp2) > 0 ) {
        if (eF_checkParameter($login, 'login')) {
         if ($pwd == $password) {
          $update['status'] = "logged";
          $update['users_LOGIN'] = $login;
          eF_updateTableData("tokens",$update,"token='$token'");
          echo "<xml>";
          echo "<status>ok</status>";
          echo "</xml>";
         }
        } else {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Invalid username</message>";
         echo "</xml>";
        }
       } else {
        echo "<xml>";
        echo "<status>error</status>";
        echo "<message>Invalid token</message>";
        echo "</xml>";
       }
      } else {
       echo "<xml>";
       echo "<status>error</status>";
       echo "<message>You must have an administrator account to login to eFront API</message>";
       echo "</xml>";
      }
     } else {
      echo "<xml>";
      echo "<status>error</status>";
      echo "<message>No username/password/token provided</message>";
      echo "</xml>";
     }
                    break;
                } case 'create_lesson':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        if (isset($_GET['name']) && isset($_GET['category']) && isset($_GET['course_only']) && isset($_GET['language'])){
       if (!eF_checkParameter($_GET['category'], 'uint')) {
        echo "<xml>";
                                echo "<status>Invalid category</status>";
        echo "</xml>";
        exit;
       }
                            $insert['name'] = $_GET['name'];
                            $insert['directions_ID'] = $_GET['category'];
                            $insert['course_only'] = $_GET['course_only'];
                            $insert['languages_NAME'] = $_GET['language'];
       $insert['created'] = time();
       if (isset($_GET['price']) && eF_checkParameter($_GET['price'], 'uint')) {
        $insert['price'] = $_GET['price'];
       }
       $fields = array( 'name' => $insert['name'],
            'directions_ID' => $insert['directions_ID'],
            'languages_NAME' => $insert['languages_NAME'],
            'course_only' => $insert['course_only'],
            'created' => $insert['created'],
            'price' => isset($insert['price']) && !$insert['course_only'] ? $insert['price'] : 0);
       try {
        $newLesson = EfrontLesson :: createLesson($fields); //$newLesson is now a new lesson object
        echo "<xml>";
        echo "<status>ok</status>";
        echo "</xml>";
       } catch (Exception $e) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Some problem occured</message>";
        echo "</xml>";
       }
                        } else {
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
                            echo "</xml>";
                        }
                    } else {
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
                    }
                    break;
                } case 'create_user':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])){
                        if (isset($_GET['login']) && isset($_GET['password']) && isset($_GET['email']) && isset($_GET['languages']) && isset($_GET['name']) && isset($_GET['surname'])){
       $insert['login'] = $_GET['login'];
                            $insert['password'] = EfrontUser :: createPassword($_GET['password']);
                            $insert['email'] = $_GET['email'];
                            $insert['languages_NAME'] = $_GET['languages'];
                            $insert['name'] = $_GET['name'];
                            $insert['surname'] = $_GET['surname'];
       $insert['active'] = 1; // Added makriria
       $languages = EfrontSystem :: getLanguages(true, true);
       if ($_GET['languages'] != "" && in_array($_GET['languages'], array_keys($languages)) === false) {
        echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Invalid language</message>";
         echo "</xml>";
         exit;
       }
       try {
        $user = EfrontUser :: createUser($insert);
        echo "<xml>";
                                echo "<status>ok</status>";
        echo "</xml>";
       } catch (Exception $e) {
        if ($e -> getCode() == EfrontUserException :: INVALID_LOGIN) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Invalid login format</message>";
         echo "</xml>";
         exit;
        } elseif ($e -> getCode() == EfrontUserException :: MAXIMUM_REACHED) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Maximum number of users reached</message>";
         echo "</xml>";
         exit;
        } elseif ($e -> getCode() == EfrontUserException :: USER_EXISTS) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>User already exists</message>";
         echo "</xml>";
         exit;
        } elseif ($e -> getCode() == EfrontUserException :: INVALID_PARAMETER) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Invalid parameter</message>";
         echo "</xml>";
         exit;
        } else {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Some problem occured</message>";
         echo "</xml>";
         exit;
        }
       }
      } else {
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
                            echo "</xml>";
                        }
                    }
                    else{
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
                    }
                    break;
                }
                case 'update_user':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        if (isset($_GET['login']) && isset($_GET['password']) && isset($_GET['email']) && isset($_GET['name']) && isset($_GET['surname'])) {
                            $languages = EfrontSystem :: getLanguages(true, true);
       if ($_GET['language'] != "" && in_array($_GET['language'], array_keys($languages)) === false) {
        echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Invalid language</message>";
         echo "</xml>";
         exit;
       }
       try {
        $user = EfrontUserFactory :: factory($_GET['login']);
 //pr($user);	exit;						
        $user -> user['password'] = EfrontUser::createPassword($_GET['password']);
        $_GET['email'] != "" ? $user -> user['email'] = $_GET['email'] : null;
        $_GET['name'] != "" ? $user -> user['name'] = $_GET['name'] : null;
        $_GET['surname']!= "" ? $user -> user['surname'] = $_GET['surname'] : null;
        $_GET['language'] != "" ? $user -> user['languages_NAME'] = $_GET['language'] : null;
        $user -> persist();
        echo "<xml>";
                                echo "<status>ok</status>";
        echo "</xml>";
       } catch (EfrontUserException $e) {
        if ($e -> getCode() == EfrontUserException :: INVALID_PARAMETER) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Invalid login format</message>";
         echo "</xml>";
         exit;
        } elseif ($e -> getCode() == EfrontUserException :: USER_NOT_EXISTS) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>User does not exist</message>";
         echo "</xml>";
         exit;
        } else {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Some problem occured</message>";
         echo "</xml>";
         exit;
        }
       }
                        } else {
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
                            echo "</xml>";
                        }
                    } else {
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
      exit;
                    }
                    break;
                }
                case 'deactivate_user':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])){
                        if (isset($_GET['login'])) {
       try {
        $user = EfrontUserFactory :: factory($_GET['login']);
        $user -> deactivate();
        echo "<xml>";
                                echo "<status>ok</status>";
        echo "</xml>";
        exit;
       } catch (Exception $e) {
        if ($e -> getCode() == EfrontUserException :: INVALID_PARAMETER) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Invalid login format</message>";
         echo "</xml>";
         exit;
        } elseif ($e -> getCode() == EfrontUserException :: USER_NOT_EXISTS) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>User does not exist</message>";
         echo "</xml>";
         exit;
        } else {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Some problem occured</message>";
         echo "</xml>";
         exit;
        }
       }
                        } else {
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
       echo "</xml>";
                        }
                    } else {
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
                    }
                    break;
                }
                case 'activate_user':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        if (isset($_GET['login'])){
       try {
        $user = EfrontUserFactory :: factory($_GET['login']);
        $user -> activate();
        echo "<xml>";
                                echo "<status>ok</status>";
        echo "</xml>";
        exit;
       } catch (Exception $e) {
        if ($e -> getCode() == EfrontUserException :: INVALID_PARAMETER) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Invalid login format</message>";
         echo "</xml>";
         exit;
        } elseif ($e -> getCode() == EfrontUserException :: USER_NOT_EXISTS) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>User does not exist</message>";
         echo "</xml>";
         exit;
        } else {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Some problem occured</message>";
         echo "</xml>";
         exit;
        }
       }
                        } else {
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
       echo "</xml>";
                        }
                    } else {
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
                    }
                    break;
                }
                case 'remove_user':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        if (isset($_GET['login'])) {
       try {
        $user = EfrontUserFactory :: factory($_GET['login']);
        $user -> delete();
        echo "<xml>";
        echo "<status>ok</status>";
        echo "</xml>";
       } catch (Exception $e) {
        if ($e -> getCode() == EfrontUserException :: INVALID_PARAMETER) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Invalid login format</message>";
         echo "</xml>";
         exit;
        } elseif ($e -> getCode() == EfrontUserException :: USER_NOT_EXISTS) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>User does not exist</message>";
         echo "</xml>";
         exit;
        } else {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Some problem occured</message>";
         echo "</xml>";
         exit;
        }
       }
                        } else {
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
       echo "</xml>";
                        }
                    } else {
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
                    }
                    break;
                }
                case 'groups':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
      $groups = EfrontGroup::getGroups(true, true);
                        echo "<xml>";
                        echo "<groups>";
                        foreach ($groups as $key => $group){
                            echo "<group>";
                            echo "<id>".$group -> group['id']."</id>";
                            echo "<name>".$group -> group['name']."</name>";
       echo "<description>".$group -> group['description']."</description>";
       echo "<active>".$group -> group['active']."</active>";
       echo "<user_types_ID>".$group -> group['user_types_ID']."</user_types_ID>";
       echo "<languages_NAME>".$group -> group['languages_NAME']."</languages_NAME>";
       echo "<users_active>".$group -> group['users_active']."</users_active>";
       echo "<assign_profile_to_new>".$group -> group['assign_profile_to_new']."</assign_profile_to_new>";
       echo "<unique_key>".$group -> group['unique_key']."</unique_key>";
       echo "<is_default>".$group -> group['is_default']."</is_default>";
       echo "<key_max_usage>".$group -> group['key_max_usage']."</key_max_usage>";
       echo "<key_current_usage>".$group -> group['key_current_usage']."</key_current_usage>";
                            echo "</group>";
                        }
                        echo "</groups>";
                        echo "</xml>";
                    } else {
                        echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
                        echo "</xml>";
                    }
                    break;
                }
                case 'group_info':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        if (isset($_GET['group'])) {
       if (eF_checkParameter($_GET['group'], 'id') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid group id</message>";
                                echo "</xml>";
        exit;
       }
                            try {
          $groups = EfrontGroup::getGroups(true, true);
          $group = $groups[$_GET['group']];
          if (!empty($group)) {
         echo "<xml>";
         echo "<general_info>";
         echo "<name>".$group -> group['name']."</name>";
         echo "<description>".$group -> group['description']."</description>";
         echo "<language>".$group -> group['languages_NAME']."</language>";
         echo "<unique_key>".$group -> group['unique_key']."</unique_key>";
         echo "</general_info>";
         echo "</xml>";
        } else {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Group doesn't exist</message>";
         echo "</xml>";
        }
                            } catch (Exception $e) {
                                echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Some problem occured</message>";
                                echo "</xml>";
                            }
                        } else {
                            echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
                            echo "</xml>";
                        }
                    } else {
                        echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
                        echo "</xml>";
                    }
                    break;
                }
                case 'group_to_user':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        if (isset($_GET['login']) && isset($_GET['group'])) {
       if (eF_checkParameter($_GET['group'], 'id') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid group id</message>";
                                echo "</xml>";
        exit;
       }
       if (eF_checkParameter($_GET['login'], 'login') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid login format</message>";
                                echo "</xml>";
        exit;
       }
       try {
        $group = new EfrontGroup($_GET['group']);
        $group -> addUsers(array($_GET['login']));
        echo "<xml>";
                                echo "<status>ok</status>";
                                echo "</xml>";
       } catch (Exception $e) {
        if ($e -> getCode() == EfrontGroupException :: USER_ALREADY_MEMBER) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Assignment already exists</message>";
         echo "</xml>";
         exit;
        } elseif ($e -> getCode() == EfrontUserException :: USER_NOT_EXISTS) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>User does not exist</message>";
         echo "</xml>";
         exit;
        } else {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Some problem occured</message>";
         echo "</xml>";
         exit;
        }
       }
                        } else {
                            echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
                            echo "</xml>";
                        }
                    } else {
                        echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
                        echo "</xml>";
                    }
                    break;
                }
                case 'group_from_user':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        if (isset($_GET['login']) && isset($_GET['group'])) {
       if (eF_checkParameter($_GET['group'], 'id') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid group id</message>";
                                echo "</xml>";
        exit;
       }
       if (eF_checkParameter($_GET['login'], 'login') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid login format</message>";
                                echo "</xml>";
        exit;
       }
       try {
        $group = new EfrontGroup($_GET['group']);
        $user = EfrontUserFactory :: factory($_GET['login']);
        $group_users = $group -> getUsers();
        if (!in_array($_GET['login'], $group_users['student']) && !in_array($_GET['login'], $group_users['professor'])) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>User is not assigned to group</message>";
         echo "</xml>";
         exit;
        } else {
         $group -> removeUsers(array($_GET['login']));
         echo "<xml>";
         echo "<status>ok</status>";
         echo "</xml>";
        }
       } catch (Exception $e) {
        if ($e -> getCode() == EfrontUserException :: USER_NOT_EXISTS) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>User does not exist</message>";
         echo "</xml>";
         exit;
        } else {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Some problem occured</message>";
         echo "</xml>";
         exit;
        }
       }
                        } else{
                            echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
                            echo "</xml>";
                        }
                    } else {
                        echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
                        echo "</xml>";
                    }
                    break;
                }
                case 'lesson_to_user':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        if (isset($_GET['login']) && isset($_GET['lesson'])) {
       if (eF_checkParameter($_GET['lesson'], 'id') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid lesson id</message>";
                                echo "</xml>";
        exit;
       }
       if (eF_checkParameter($_GET['login'], 'login') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid login format</message>";
                                echo "</xml>";
        exit;
       }
       try {
        $lesson = new EfrontLesson($_GET['lesson']);
        $user = EfrontUserFactory :: factory($_GET['login']);
        $_GET['type'] != "professor" && $_GET['type'] != 'student' ? $_GET['type'] = 'student' : null;
        if (($lesson -> isStudentInLesson($_GET['login']) === true && $_GET['type'] == 'student') || ($lesson -> isProfessorInLesson($_GET['login']) === true && $_GET['type'] == 'professor')) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Assignment already exists</message>";
         echo "</xml>";
         exit;
        }
        if (isset($_GET['type'])) {
          $lesson ->addUsers($_GET['login'], $_GET['type']);
         } else {
          $lesson ->addUsers($_GET['login']);
         }
        echo "<xml>";
                                echo "<status>ok</status>";
        echo "</xml>";
       } catch (Exception $e) {
        if ($e -> getCode() == EfrontUserException :: USER_NOT_EXISTS) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>User does not exist</message>";
         echo "</xml>";
         exit;
        } elseif ($e -> getCode() == EfrontCourseException :: MAX_USERS_LIMIT) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Maximum number of users reached</message>";
         echo "</xml>";
         exit;
        } else {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Some problem occured</message>";
         echo "</xml>";
         exit;
        }
       }
                        } else {
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
       echo "</xml>";
                        }
                    } else {
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
                    }
                    break;
                }
                case 'deactivate_user_lesson':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        if (isset($_GET['login']) && isset($_GET['lesson'])) {
       if (eF_checkParameter($_GET['lesson'], 'id') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid lesson id</message>";
                                echo "</xml>";
        exit;
       }
       if (eF_checkParameter($_GET['login'], 'login') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid login format</message>";
                                echo "</xml>";
        exit;
       }
                            $update['from_timestamp'] = 0;
                            if (eF_updateTableData("users_to_lessons",$update, "users_LOGIN='".$_GET['login']."' and lessons_ID=".$_GET['lesson'])){
                                $cacheKey = "user_lesson_status:lesson:".$_GET['lesson']."user:".$_GET['login'];
                                Cache::resetCache($cacheKey);
                                echo "<xml>";
                                echo "<status>ok</status>";
                                echo "</xml>";
                            }
                            else{
                                echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Some problem occured</message>";
                                echo "</xml>";
                            }
                        } else {
                            echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
                            echo "</xml>";
                        }
                    } else{
                        echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
                        echo "</xml>";
                    }
                    break;
                }
                case 'activate_user_lesson':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])){
                        if (isset($_GET['login']) && isset($_GET['lesson'])){
       if (eF_checkParameter($_GET['lesson'], 'id') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid lesson id</message>";
                                echo "</xml>";
        exit;
       }
       if (eF_checkParameter($_GET['login'], 'login') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid login format</message>";
                                echo "</xml>";
        exit;
       }
                            $update['from_timestamp'] = time();
                            if (eF_updateTableData("users_to_lessons",$update, "users_LOGIN='".$_GET['login']."' and lessons_ID=".$_GET['lesson'])){
                                $cacheKey = "user_lesson_status:lesson:".$_GET['lesson']."user:".$_GET['login'];
                                Cache::resetCache($cacheKey);
                                echo "<xml>";
                                echo "<status>ok</status>";
                                echo "</xml>";
                            }
                            else{
                                echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Some problem occured</message>";
                                echo "</xml>";
                            }
                        }
                        else{
                            echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
                            echo "</xml>";
                        }
                    }
                    else{
                        echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
                        echo "</xml>";
                    }
                    break;
                }
                case 'lesson_from_user':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])){
                        if (isset($_GET['login']) && isset($_GET['lesson'])){
                            $res = eF_deleteTableData("users_to_lessons", "users_LOGIN='".$_GET['login']."' and lessons_ID=".$_GET['lesson']);
       echo "<xml>";
                            echo "<status>ok</status>";
       echo "</xml>";
                        }
                        else{
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
       echo "</xml>";
                        }
                    }
                    else{
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
                    }
                    break;
                }
                case 'user_lessons':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        if (isset($_GET['login'])) {
       if (eF_checkParameter($_GET['login'], 'login') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid login format</message>";
                                echo "</xml>";
        exit;
       }
       try {
        $user = EfrontUserFactory :: factory($_GET['login']);
        $lessonsList = $user -> getLessons(true);
        echo "<xml>";
        foreach ($lessonsList as $key => $lesson){
         echo "<lesson>".$lesson -> lesson['name']."</lesson>";
        }
        echo "</xml>";
       } catch (Exception $e) {
        if ($e -> getCode() == EfrontUserException :: USER_NOT_EXISTS) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>User does not exist</message>";
         echo "</xml>";
         exit;
        }
       }
                        } else {
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
       echo "</xml>";
                        }
                    } else {
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
                    }
                    break;
                }
                case 'lesson_info':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        if (isset($_GET['lesson'])) {
       if (eF_checkParameter($_GET['lesson'], 'id') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid lesson id</message>";
                                echo "</xml>";
        exit;
       }
                            try {
                                $lesson = new EfrontLesson($_GET['lesson']);
                                $info = $lesson -> getStatisticInformation();
        $lesson_info = unserialize($lesson -> lesson['info']);
        $metadata = unserialize($lesson -> lesson['metadata']);
                                echo "<xml>";
                                echo "<general_info>";
                                echo "<name>".$lesson -> lesson['name']."</name>";
                                echo "<direction>".$info['direction']."</direction>";
                                echo "<price>";
        echo "<value>".$info['price']."</value>";
        echo "<currency>".$GLOBALS['configuration']['currency']."</currency>";
        echo "</price>";
                                echo "<language>".$info['language']."</language>";
        echo "<info>";
        foreach ($lesson_info as $key => $value) {
         echo "<".$key.">".$value."</".$key.">";
        }
        echo "</info>";
        echo "<metadata>";
        foreach ($metadata as $key => $value) {
         echo "<".$key.">".$value."</".$key.">";
        }
        echo "</metadata>";
                                echo "</general_info>";
                                echo "</xml>";
                            } catch (Exception $e) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Lesson doesn't exist</message>";
        echo "</xml>";
                            }
                        } else {
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
       echo "</xml>";
                        }
                    } else {
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
                    }
                    break;
                }
                case 'user_courses':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        if (isset($_GET['login'])) {
       if (eF_checkParameter($_GET['login'], 'login') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid login format</message>";
                                echo "</xml>";
        exit;
       }
                            try {
        $user = EfrontUserFactory :: factory($_GET['login']);
        $coursesList = $user -> getUserCourses();
        echo "<xml>";
        foreach ($coursesList as $key => $course) {
         echo "<course>".$course -> course['name']."</course>";
        }
        echo "</xml>";
       } catch (Exception $e) {
        if ($e -> getCode() == EfrontUserException :: USER_NOT_EXISTS) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>User does not exist</message>";
         echo "</xml>";
         exit;
        } else {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Some problem occured</message>";
         echo "</xml>";
         exit;
        }
       }
                        } else {
                            echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
                            echo "</xml>";
                        }
                    } else {
                        echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
                        echo "</xml>";
                    }
                    break;
                }
                case 'user_info':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        if (isset($_GET['login'])) {
                            try {
                                $user = EfrontUserFactory :: factory($_GET['login']);
                                echo "<xml>";
                                echo "<general_info>";
                                echo "<name>".$user -> user['name']." ".$user -> user['surname']."</name>";
                                echo "<active>".$user -> user['active']."</active>";
                                echo "<user_type>".$user -> user['user_type']."</user_type>";
                                echo "</general_info>";
                                echo "</xml>";
                            } catch (Exception $e) {
        if ($e -> getCode() == EfrontUserException :: USER_NOT_EXISTS) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>User does not exist</message>";
         echo "</xml>";
         exit;
        } else {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Invalid login format</message>";
         echo "</xml>";
         exit;
        }
                            }
                        } else {
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
       echo "</xml>";
                        }
                    } else {
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
                    }
                    break;
                }
                case 'catalog':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
      $lessons = EFrontLesson :: getLessons();
      $lessons = eF_multiSort($lessons, 'id', 'desc');
      $courses = EfrontCourse :: getAllCourses();
                        echo "<xml>";
                        echo "<catalog>";
                        echo "<courses>";
                        foreach ($courses as $key => $course) {
                            echo "<course>";
                            echo "<id>".$course -> course['id']."</id>";
                            echo "<name>".$course -> course['name']."</name>";
                            echo "</course>";
                        }
                        echo "</courses>";
                        echo "<lessons>";
      foreach ($lessons as $key => $lesson) {
                            echo "<lesson>";
                            echo "<id>".$lesson['id']."</id>";
                            echo "<name>".$lesson['name']."</name>";
                            echo "</lesson>";
                        }
                        echo "</lessons>";
                        echo "</catalog>";
                        echo "</xml>";
                    } else {
                        echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
                        echo "</xml>";
                    }
                    break;
                }
                case 'lessons':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
      $lessons = EFrontLesson :: getLessons();
      $lessons = eF_multiSort($lessons, 'id', 'desc');
                        echo "<xml>";
                        echo "<lessons>";
      foreach ($lessons as $key => $lesson) {
                            echo "<lesson>";
                            echo "<id>".$lesson['id']."</id>";
                            echo "<name>".$lesson['name']."</name>";
                            echo "</lesson>";
                        }
                        echo "</lessons>";
                        echo "</xml>";
                    } else {
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
                    }
                    break;
    }
                case 'courses':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
      $courses = EfrontCourse :: getAllCourses();
                        echo "<xml>";
                        echo "<courses>";
                        foreach ($courses as $key => $course) {
                            echo "<course>";
                            echo "<id>".$course -> course['id']."</id>";
                            echo "<name>".$course -> course['name']."</name>";
                            echo "</course>";
                        }
                        echo "</courses>";
                        echo "</xml>";
                    } else {
                        echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
                        echo "</xml>";
                    }
                    break;
                }
                case 'course_info':{
                 if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        if (isset($_GET['course'])) {
       if (eF_checkParameter($_GET['course'], 'id') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid course id</message>";
                                echo "</xml>";
        exit;
       }
       try {
                                $course = new EfrontCourse($_GET['course']);
        $info = unserialize($course -> course['info']);
        $metadata = unserialize($course -> course['metadata']);
        echo "<xml>";
        echo "<general_info>";
        echo "<id>".$course -> course['id']."</id>";
        echo "<name>".$course -> course['name']."</name>";
        echo "<info>";
        foreach ($info as $key => $value) {
         echo "<".$key.">".$value."</".$key.">";
        }
        echo "</info>";
        echo "<metadata>";
        foreach ($metadata as $key => $value) {
         echo "<".$key.">".$value."</".$key.">";
        }
        echo "</metadata>";
        echo "<price>";
        echo "<value>".$course -> course['price']."</value>";
        echo "<currency>".$GLOBALS['configuration']['currency']."</currency>";
        echo "</price>";
        echo "<active>".$course -> course['active']."</active>";
        echo "<languages_NAME>".$course -> course['languages_NAME']."</languages_NAME>";
        echo "<general_info>";
        echo "</xml>";
                            } catch (Exception $e) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Course doesn't exist</message>";
        echo "</xml>";
                            }
                        } else {
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
       echo "</xml>";
                        }
                    } else {
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
                    }
                    break;
    }
    case 'course_lessons':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])){
      if (isset($_GET['course'])) {
       if (eF_checkParameter($_GET['course'], 'id') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid course id</message>";
                                echo "</xml>";
        exit;
       }
       try {
        $course = new EfrontCourse($_GET['course']);
        $lessons = EfrontCourse::convertLessonObjectsToArrays($course->getCourseLessons());
        echo "<xml>";
         echo "<lessons>";
         foreach ($lessons as $key=>$values ) {
          echo "<lesson>";
           echo "<id>".$lessons[$key]['id']."</id>";
           echo "<name>".$lessons[$key]['name']."</name>";
           echo "<previous_lessons_ID>".$lessons[$key]['previous_lessons_ID']."</previous_lessons_ID>";
          echo "</lesson>";
         }
         echo "<lessons>";
        echo "</xml>";
       } catch (Exception $e) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Course doesn't exist</message>";
         echo "</xml>";
       }
       /*	

							$lessons = eF_getTableData("lessons_to_courses", "courses_ID, lessons_ID, previous_lessons_ID", "courses_ID ='".$_GET['course']."'");

							echo "<xml>";

								echo "\n\t";

								echo "<lessons>";

								echo "\n\t\t";

								for ($i=0; $i < sizeof($lessons);$i++){

									echo "<lesson>";

										echo "\n\t\t\t";

										echo "<courses_ID>".$lessons[$i]['courses_ID']."</courses_ID>";

										echo "\n\t\t\t";

										echo "<lessons_ID>".$lessons[$i]['lessons_ID']."</lessons_ID>"; 

										echo "\n\t\t\t";

										echo "<previous_lessons_ID>".$lessons[$i]['previous_lessons_ID']."</previous_lessons_ID>"; 

										echo "\n\t\t";

									echo "</lesson>";

						} 

								echo "\n\t";

								echo "<lessons>";	

							echo "\n";

							echo "</xml>";

							*/
                        }
                        else{
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
       echo "</xml>";
                        }
                    }
                    else{
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
                    }
                    break;
    }
    case 'course_to_user':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
      if (isset($_GET['login']) && isset($_GET['course'])) {
       if (eF_checkParameter($_GET['course'], 'id') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid course id</message>";
                                echo "</xml>";
        exit;
       }
       if (eF_checkParameter($_GET['login'], 'login') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid login format</message>";
                                echo "</xml>";
        exit;
       }
        try {
         $course = new EfrontCourse($_GET['course']);
         $user = EfrontUserFactory :: factory($_GET['login']);
         $_GET['type'] != "professor" && $_GET['type'] != 'student' ? $_GET['type'] = 'student' : null;
         if (($course -> isStudentInCourse($_GET['login']) === true && $_GET['type'] == 'student') || ($course -> isProfessorInCourse($_GET['login']) === true && $_GET['type'] == 'professor')) {
          echo "<xml>";
          echo "<status>error</status>";
          echo "<message>Assignment already exists</message>";
          echo "</xml>";
          exit;
         }
         if (isset($_GET['type'])) {
          $course ->addUsers($_GET['login'], $_GET['type']);
         } else {
          $course ->addUsers($_GET['login']);
         }
         echo "<xml>";
         echo "<status>ok</status>";
         echo "</xml>";
        } catch (Exception $e) {
         if ($e -> getCode() == EfrontUserException :: USER_NOT_EXISTS) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>User does not exist</message>";
         echo "</xml>";
         exit;
        } elseif ($e -> getCode() == EfrontCourseException :: COURSE_NOT_EXISTS) {
          echo "<xml>";
          echo "<status>error</status>";
          echo "<message>Course doesn't exist</message>";
          echo "</xml>";
         } elseif ($e -> getCode() == EfrontCourseException :: MAX_USERS_LIMIT) {
          echo "<xml>";
          echo "<status>error</status>";
          echo "<message>Maximum number of users reached</message>";
          echo "</xml>";
         }
        }
                        } else {
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
       echo "</xml>";
                        }
                    } else {
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
                    }
                    break;
    }
                case 'activate_user_course':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        if (isset($_GET['login']) && isset($_GET['course'])) {
       if (eF_checkParameter($_GET['course'], 'id') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid course id</message>";
                                echo "</xml>";
        exit;
       }
       if (eF_checkParameter($_GET['login'], 'login') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid login format</message>";
                                echo "</xml>";
        exit;
       }
                            $update['from_timestamp'] = time();
                            $courses = eF_getTableData("lessons_to_courses","lessons_id", "courses_ID=".$_GET['course']);
                            for ($i=0; $i < sizeof($courses);$i++){
                                if (eF_updateTableData("users_to_lessons",$update, "users_LOGIN='".$_GET['login']."' and lessons_ID=".$courses[$i]['lessons_id'])){
                                 $cacheKey = "user_lesson_status:lesson:".$courses[$i]['lessons_id']."user:".$_GET['login'];
                                 Cache::resetCache($cacheKey);
                                }
                            }
                            echo "<xml>";
                            echo "<status>ok</status>";
                            echo "</xml>";
                        } else {
                            echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
                            echo "</xml>";
                        }
                    } else {
                        echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
                        echo "</xml>";
                    }
                    break;
                }
                case 'deactivate_user_course':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])){
                        if (isset($_GET['login']) && isset($_GET['course'])){
       if (eF_checkParameter($_GET['course'], 'id') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid course id</message>";
                                echo "</xml>";
        exit;
       }
       if (eF_checkParameter($_GET['login'], 'login') == false) {
        echo "<xml>";
                                echo "<status>error</status>";
                                echo "<message>Invalid login format</message>";
                                echo "</xml>";
        exit;
       }
                            $update['from_timestamp'] = 0;
                            $courses = eF_getTableData("lessons_to_courses","lessons_id", "courses_ID=".$_GET['course']);
                            for ($i=0; $i < sizeof($courses);$i++){
                                if (eF_updateTableData("users_to_lessons",$update, "users_LOGIN='".$_GET['login']."' and lessons_ID=".$courses[$i]['lessons_id'])){
                                 $cacheKey = "user_lesson_status:lesson:".$courses[$i]['lessons_id']."user:".$_GET['login'];
                                 Cache::resetCache($cacheKey);
                                }
                            }
                            echo "<xml>";
                            echo "<status>ok</status>";
                            echo "</xml>";
                        }
                        else{
                            echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
                            echo "</xml>";
                        }
                    }
                    else{
                        echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
                        echo "</xml>";
                    }
                    break;
                }
    case 'course_from_user':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
       if (isset($_GET['login']) && isset($_GET['course'])) {
        if (eF_checkParameter($_GET['course'], 'id') == false) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Invalid course id</message>";
         echo "</xml>";
         exit;
        }
        if (eF_checkParameter($_GET['login'], 'login') == false) {
         echo "<xml>";
         echo "<status>error</status>";
         echo "<message>Invalid login format</message>";
         echo "</xml>";
         exit;
        }
        try {
         $course = new EfrontCourse($_GET['course']);
         if ($course -> isStudentInCourse($_GET['login']) === false && $course -> isProfessorInCourse($_GET['login']) === false) {
          echo "<xml>";
          echo "<status>error</status>";
          echo "<message>User not enrolled into course</message>";
          echo "</xml>";
          exit;
         }
         $course -> removeUsers($_GET['login']);
         echo "<xml>";
         echo "<status>ok</status>";
         echo "</xml>";
        }
         catch (Exception $e) {
         if ($e -> getCode() == EfrontCourseException :: COURSE_NOT_EXISTS) {
          echo "<xml>";
          echo "<status>error</status>";
          echo "<message>Course doesn't exist</message>";
          echo "</xml>";
         }
        }
                        } else {
       echo "<xml>";
                            echo "<status>error</status>";
                            echo "<message>Incomplete arguments</message>";
       echo "</xml>";
                        }
                    } else {
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
                    }
                    break;
                }
                case 'logout':{
                    if (isset($_GET['token']) && checkToken($_GET['token'])) {
                        $token = $_GET['token'];
                        eF_deleteTableData("tokens","token='$token'");
      echo "<xml>";
                        echo "<status>ok</status>";
      echo "</xml>";
                    } else {
      echo "<xml>";
                        echo "<status>error</status>";
                        echo "<message>Invalid token</message>";
      echo "</xml>";
                    }
                    break;
                }
    default: {
     echo "<xml>";
     echo "<status>error</status>";
                    echo "<message>Invalid action argument</message>";
     echo "</xml>";
     break;
    }
            }
  } else {
   echo "<xml>";
   echo "<status>error</status>";
            echo "<message>There is no action argument</message>";
   echo "</xml>";
  }
    }
    else{
  echo "<xml>";
        echo "<status>error</status>";
        echo "<message>The api module is disabled</message>";
  echo "</xml>";
    }
    function createToken($length){
        $salt = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789"; // salt to select chars from
        srand((double)microtime()*1000000); // start the random generator
        $token = ""; // set the inital variable
        for ($i = 0; $i < $length; $i++) { // loop and create password
   $token = $token . substr ($salt, rand() % strlen($salt), 1);
  }
        return $token;
    }
    function checkToken($token) {
        $tmp = eF_getTableData("tokens","status","token='$token'");
        $token = $tmp[0]['status'];
        if ($token == 'logged'){
            return true;
        } else {
   return false;
  }
    }
?>