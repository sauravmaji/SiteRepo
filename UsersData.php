<?php

/*
 * @todo Fetch District AC and Parts Combo Data on seperate request via ajax
 */

$Data = new MySQLiDB();
$_SESSION['action'] = 0;
$Query = '';
if (WebLib::GetVal($_POST, 'FormToken') !== NULL) {
  if (WebLib::GetVal($_POST, 'FormToken') !== WebLib::GetVal($_SESSION, 'FormToken')) {
    $_SESSION['action'] = 1;
  } else {
    // Authenticated Inputs
    switch (WebLib::GetVal($_POST, 'CmdSubmit')) {
      case 'Create':
        if (strlen(WebLib::GetVal($_POST, 'UserName')) > 2) {
          $Query = 'Insert Into `' . MySQL_Pre . 'Users` (`UserName`,`CtrlMapID`,`Registered`,`Activated`)'
                  . ' Values(\'' . WebLib::GetVal($_POST, 'UserName', TRUE) . '\',' . WebLib::GetVal($_SESSION, 'UserMapID', TRUE) . ',0,0)';
        } else {
          $Query = '';
          $_SESSION['Msg'] = 'UserName must be at least 3 characters or more.';
        }
        break;

      case 'Activate':
        $Query = 'Update `' . MySQL_Pre . 'Users` Set `Activated`=1'
                . ' Where `Activated`=0 AND `CtrlMapID`=' . WebLib::GetVal($_SESSION, 'UserMapID', TRUE)
                . ' AND `UserMapID`=' . WebLib::GetVal($_POST, 'UserMapID');
        $User = explode('|', $Data->do_max_query('Select CONCAT(`UserName`,\'|\',`UserID`) FROM `' . MySQL_Pre . 'Users`'
                        . ' Where UserMapID=\'' . WebLib::GetVal($_POST, 'UserMapID') . '\''));
        $Subject = 'User Account Activated - SRER 2014';
        $Body = '<span>Your UserID: <b>' . $User[1] . '</b> is now Activated</span>';
        $Mail = json_decode(GMailSMTP($User[1], $User[0], $Subject, $Body));
        break;

      case 'De-Activate':
        $Query = 'Update `' . MySQL_Pre . 'Users` Set `Activated`=0'
                . ' Where `Activated`=1 AND `CtrlMapID`=' . WebLib::GetVal($_SESSION, 'UserMapID', TRUE)
                . ' AND `UserMapID`=' . WebLib::GetVal($_POST, 'UserMapID');
        $User = explode('|', $User = $Data->do_max_query('Select CONCAT(`UserName`,\'|\',`UserID`) FROM `' . MySQL_Pre . 'Users`'
                . ' Where UserMapID=\'' . WebLib::GetVal($_POST, 'UserMapID') . '\''));
        $Subject = 'User Account De-Activated - SRER 2014';
        $Body = '<span>Your UserID: <b>' . $User[1] . '</b> is now De-Activated</span>';
        $Mail = json_decode(GMailSMTP($User[1], $User[0], $Subject, $Body));
        break;

      case 'Un-Register':
        $Query = 'Update `' . MySQL_Pre . 'Users` Set `Registered`=0,`Activated`=0'
                . ' Where `Registered`=1 AND `CtrlMapID`=' . WebLib::GetVal($_SESSION, 'UserMapID', TRUE)
                . ' AND `UserMapID`=' . WebLib::GetVal($_POST, 'UserMapID');
        $User = explode('|', $Data->do_max_query('Select CONCAT(`UserName`,\'|\',`UserID`) FROM `' . MySQL_Pre . 'Users`'
                        . ' Where UserMapID=\'' . WebLib::GetVal($_POST, 'UserMapID') . '\''));
        $Subject = 'User Account Un-Registered - SRER 2014';
        $Body = '<span>Your UserID: <b>' . $User[1] . '</b> is now Un-Registered</span><br/>'
                . '<b>Please Register again to change EmailID and Password</b>';
        $Mail = json_decode(GMailSMTP($User[1], $User[0], $Subject, $Body));
        break;

      /**
       * @todo User will be assigned a District only if user is Activated
       */
      case 'Assign Whole District':
        $Query = 'Update `' . MySQL_Pre . 'SRER_Districts` Set `UserMapID`=' . WebLib::GetVal($_POST, 'UserMapID')
                . ' Where `DistCode`=\'' . WebLib::GetVal($_POST, 'DistCode', TRUE) . '\'';
        $User = explode('|', $Data->do_max_query('Select CONCAT(`UserName`,\'|\',`UserID`) FROM `' . MySQL_Pre . 'Users`'
                        . ' Where UserMapID=' . WebLib::GetVal($_POST, 'UserMapID')));
        $Subject = 'User Account Changed - SRER 2014';
        $Body = '<span>A New District in now assigned Your UserID: <b>' . $User[1] . '</b></span><br/>'
                . '<b>Please Login to check it out.</b>';
        $Mail = json_decode(GMailSMTP($User[1], $User[0], $Subject, $Body));
        $_SESSION['Msg'] = 'Whole District Assigned Successfully!';
        break;

      /**
       * @todo User will be assigned an AC only if user is Activated
       */
      case 'Assign Whole AC':
        $Query = 'Update `' . MySQL_Pre . 'SRER_ACs` Set `UserMapID`=' . WebLib::GetVal($_POST, 'UserMapID')
                . ' Where `ACNo`=\'' . WebLib::GetVal($_POST, 'ACNo', TRUE) . '\'';
        $User = explode('|', $Data->do_max_query('Select CONCAT(`UserName`,\'|\',`UserID`) FROM `' . MySQL_Pre . 'Users`'
                        . ' Where UserMapID=' . WebLib::GetVal($_POST, 'UserMapID')));
        $Subject = 'User Account Changed - SRER 2014';
        $Body = '<span>A New Assembly Constituency in now assigned Your UserID: <b>' . $User[1] . '</b></span><br/>'
                . '<b>Please Login to check it out.</b>';
        $Mail = json_decode(GMailSMTP($User[1], $User[0], $Subject, $Body));
        $_SESSION['Msg'] = 'Whole AC Assigned Successfully!';
        break;

      /**
       * @todo User will be assigned a Part only if user is Activated
       */
      case 'Assign Part':
        $Query = 'Update `' . MySQL_Pre . 'SRER_PartMap` Set `UserMapID`=' . WebLib::GetVal($_POST, 'UserMapID')
                . ' Where `PartID`=\'' . WebLib::GetVal($_POST, 'PartID', TRUE) . '\'';
        $User = explode('|', $Data->do_max_query('Select CONCAT(`UserName`,\'|\',`UserID`) FROM `' . MySQL_Pre . 'Users`'
                        . ' Where UserMapID=' . WebLib::GetVal($_POST, 'UserMapID')));
        $Subject = 'User Account Changed - SRER 2014';
        $Body = '<span>A New District in now assigned Your UserID: <b>' . $User[1] . '</b></span><br/>'
                . '<b>Please Login to check it out.</b>';
        $Mail = json_decode(GMailSMTP($User[1], $User[0], $Subject, $Body));
        $_SESSION['Msg'] = 'Part Assigned Successfully!';
        break;
    }
    if ($Query !== '') {
      $Inserted = $Data->do_ins_query($Query);
      if ($Inserted > 0) {
        if (WebLib::GetVal($_POST, 'CmdSubmit') === 'Create') {
          $_SESSION['Msg'] = 'User Created Successfully!';
        } else {
          if (1 || $Mail->Sent === TRUE) {
            if (WebLib::GetVal($_SESSION, 'Msg') === '') {
              $_SESSION['Msg'] = 'User ' . WebLib::GetVal($_POST, 'CmdSubmit') . 'd Successfully!';
            }
          } else {
            $_SESSION['Msg'] = 'Action completed Successfully! But Unable to Send eMail!';
          }
        }
      } else {
        $_SESSION['Msg'] = $Query; //'Unable to ' . WebLib::GetVal($_POST, 'CmdSubmit') . '!';
      }
    }
  }
}
$_SESSION['FormToken'] = md5($_SERVER['REMOTE_ADDR'] . session_id() . microtime());
$Data->do_close();
unset($Data);
?>
