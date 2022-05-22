<?

define("STOP_STATISTICS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use \Bitrix\Iblock;
use Bitrix\Main\Loader;
Loader::includeModule('catalog');

$OutWebElem = array();
$i = 0;
$arFilter = array('IBLOCK_ID' => 7);

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json,true);

   
    foreach($data["theme"] as $themekey=>$themevalue){
      foreach($data["month"] as $key=>$value){
        
        if($value < 10){
          $arFilter['=>PROPERTY_DATE_TIME'] = '2022-0'.$value.'-01 00:00:00';
          $arFilter['<=PROPERTY_DATE_TIME'] = '2022-0'.$value.'-31 23:59:59';
        }else{
          $arFilter['>PROPERTY_DATE_TIME'] = '2022-'.$value.'-01 00:00:00';
          $arFilter['<PROPERTY_DATE_TIME'] = '2022-'.$value.'-31 23:59:59';
        } 
        $arFilter['PROPERTY_SUBJ.NAME'] = $themevalue;


        $iterator = \CIBlockElement::GetList(
          array(),
          $arFilter,
          
          array("ID","NAME","PROPERTY_DATE_TIME","PROPERTY_SUBJ.NAME"),
          );
          while ($ob = $iterator->GetNextElement()){
            $arFields = $ob->GetFields(); 

            $OutWebElem[$i][] = $arFields["NAME"];
            $OutWebElem[$i][] = $arFields["PROPERTY_DATE_TIME_VALUE"];
            $OutWebElem[$i][] = $arFields["PROPERTY_SUBJ_NAME"];
          }
      }
      $i ++; 
    }
  }
  
  $OutJson = json_encode($OutWebElem, JSON_FORCE_OBJECT);




  echo $OutJson;


?>