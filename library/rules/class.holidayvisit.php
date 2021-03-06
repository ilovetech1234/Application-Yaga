<?php if(!defined('APPLICATION')) exit();

/**
 * This rule awards badges based on a user's sign in date
 *
 * @author Zachary Doll
 * @since 1.0
 * @package Yaga
 */
class HolidayVisit implements YagaRule {

  public function Award($Sender, $User, $Criteria) {
    // Determine if today is the target day
    $Month = date('n');
    $Day = date('j');

    if($Criteria->Month == $Month
            && $Criteria->Day == $Day) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  public function Form($Form) {
    $Months = array();
    $Days = array();
    for($i = 1; $i <= 12; $i++) {
      $Months[$i] = date('F', mktime(0,0,0,$i));
    }
    for($i = 1; $i <= 31; $i++) {
      $Days[$i] = $i;
    }

    $String = $Form->Label('Holiday date', 'HolidayVisit');
    $String .= $Form->DropDown('Month', $Months) . ' ';
    $String .= $Form->DropDown('Day', $Days);
    return $String;
  }

  public function Validate($Criteria, $Form) {
    $Validation = new Gdn_Validation();
    $Validation->ApplyRules(array(
        array(
          'Name' => 'Month', 'Validation' => array('Required', 'Integer')
        ),
        array(
          'Name' => 'Day', 'Validation' => array('Required', 'Integer')
        )
    ));
    $Validation->Validate($Criteria);
    $Form->SetValidationResults($Validation->Results());
  }

  public function Hooks() {
    return array('Base_AfterSignIn');
  }

  public function Description() {
    $Description = T('Yaga.Rules.HolidayVisit.Desc');
    return Wrap($Description, 'div', array('class' => 'InfoMessage'));
  }

  public function Name() {
    return T('Yaga.Rules.HolidayVisit');
  }
}
