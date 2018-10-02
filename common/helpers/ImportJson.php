<?php

namespace common\helpers;

use common\dictionaries\ContactTypes;
use Yii;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;

use backend\models\Mandants;
use backend\models\Contacts;
use backend\models\Company;
use backend\models\Absences;
use backend\models\base\Workunits;
use backend\models\Empcontracts;

use common\todo\ToDoWorkers;
use common\todo\ToDoEmpcontracts;
use common\todo\ToDoMedvisits;
use common\mailer\ImportMailer;

/**
 * Class ImportJson
 *
 * @package common\helpers
 */
class ImportJson
{
    /**
     * @param $path
     * @param $faker
     *
     * @return array
     */
    public static function File($path, $faker)
    {
        $ilog = [];
        $mandantIDcache = [];

        $import = new ImportMailer();

        $filename = basename($path);
        if (StringHelper::startsWith($filename, 'eSST export', false) && 
                StringHelper::endsWith($filename, 'json', false)) {

            // read the json file contents
            $jsondata = file_get_contents($path);
            // convert json object to php associative array
            $rows = json_decode($jsondata, true);

            // nearly identical loops for both:
            // - api\modules\v1\controllers\ApsalController
            // - common\helpers\ImportJson\File
            // always change both !!
            foreach ($rows as $row) {
                if (empty($row['MM'])) {
                    array_push($ilog, 'Skipping row, no Mandant Matricule MM given');
                    continue;
                }
                if (empty($row['Code'])) {
                    array_push($ilog, 'Skipping row, no Code given');
                    continue;
                }
                if (empty($mandantIDcache[$row['MM']])) {
                    // get the mandant and populate chache
                    $mandant_id = Mandants::find()
                            ->select('ID_Mandant')
                            ->where(['Matricule' => $row['MM']])
                            ->andWhere(['ID_Mandant' => Yii::$app->session->get('mandant_id')])
                            ->scalar();
                    if (empty($mandant_id)) {
                        array_push($ilog, 'Skipping row, Mandant Matricule MM not found or invalid auth_key');
                        continue;
                    }
                    $mandantIDcache[$row['MM']] = $mandant_id;
                }

                switch ($row['Code']) {
                    case "APSALENT":
                        $ilog = array_merge($ilog, self::APSALENT($row, $faker, $mandantIDcache[$row['MM']]));
                        break;
                    case "APSALTRAV":
                        $ilog = array_merge($ilog, self::TRAV($row, $faker, $mandantIDcache[$row['MM']], 'Y-m-d'));
                        break;
                    case "APSALABS":
                        $ilog = array_merge($ilog, self::APSALABS($row, $faker, $mandantIDcache[$row['MM']]));
                        break;
                    default:
                        array_push($ilog, 'Skipping row, invalid Code ' . $row['Code']);
                }
            }
            foreach ($mandantIDcache as $key => $value) {
                $ilog = array_merge($ilog, ToDoWorkers::CheckAll($value));
                $ilog = array_merge($ilog, ToDoEmpcontracts::CheckAll($value));
                $ilog = array_merge($ilog, ToDoMedvisits::CheckAll($value));
            }
            // make shure we send to the currently selected mandant
            $mandantIDcache['xxx'] = Yii::$app->session->get('mandant_id');
            // send report by email to admins and TD
            if (!YII_ENV_DEV) {
                $ilog = array_merge($ilog, $import->import(array_values($mandantIDcache), $ilog));
            }


        } elseif (StringHelper::startsWith($filename, 'eSST import travailleurs', false) && 
                StringHelper::endsWith($filename, 'xlsx', false)) {

            // *****************************
            // eSST import travailleurs.xlsx
            // *****************************

            $mandant_id = Yii::$app->session->get('mandant_id');
            $xlformat = \PHPExcel_IOFactory::identify($path);
            $objectreader = \PHPExcel_IOFactory::createReader($xlformat);
            $objectPhpExcel = $objectreader->load($path);
            $objectPhpExcel->setActiveSheetIndexByName('Sheet1');
            $data = $objectPhpExcel->getActiveSheet()->toArray(null, true, true, true);
            // save and remove first row as column header
            $keys = ArrayHelper::remove($data, '1');
            foreach ($data as $row) {
                // here we combine every row with the saves header key names
                $ilog = array_merge($ilog, self::TRAV(array_combine($keys, $row), $faker, $mandant_id, 'd-M-Y'));
            }
            $ilog = array_merge($ilog, ToDoWorkers::CheckAll($mandant_id));
            $ilog = array_merge($ilog, ToDoMedvisits::CheckAll($mandant_id));
            // send report by email to admins and TD
            $ilog = array_merge($ilog, $import->import([$mandant_id], $ilog));

        } elseif (StringHelper::startsWith($filename, 'DECMAL', false) && 
                StringHelper::endsWith($filename, 'DTA', false)) {

            // *****************************
            // DECMAL.DAT
            // *****************************
            
            $mandant_id = Yii::$app->session->get('mandant_id');
            if (($handle = fopen($path, "r")) !== FALSE) {
                while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) {
                    $ilog = array_merge($ilog, self::DECMAL($row, $faker, $mandant_id));
                }
                fclose($handle);
            }
            // send report by email to admins and TD
            $ilog = array_merge($ilog, $import->import([$mandant_id], $ilog));
        }

        return $ilog;
    }
    
    static function APSALENT($row, $faker, $mandant_id) {
        $ilog = [];
        $mandant = Mandants::find()
                ->where(['ImportID' => $row['ImportID']])
                ->one();
        if (empty($mandant)) {
            // mandant does not exist create new record
            $mandant = new Mandants();
        }
        $mandant->ImportID = $row['ImportID'];
        ImportJson::setstring($mandant, 'Name', $row, 'Name', $faker);
        ImportJson::setstring($mandant, 'Address', $row, 'Address', $faker);
        ImportJson::setstring($mandant, 'Zip', $row, 'Zip', $faker);
        ImportJson::setstring($mandant, 'City', $row, 'City', $faker);
        $mandant->Co_Code = $row['Country'];
        $mandant->StartDate = $row['StartDate'];
        $mandant->EndDate = $row['EndDate'];
        ImportJson::setstring($mandant, 'Activity', $row, 'Activity', $faker);
        ImportJson::setstring($mandant, 'Tel', $row, 'Tel', $faker);
        ImportJson::setstring($mandant, 'Fax', $row, 'Fax', $faker);
        ImportJson::setstring($mandant, 'Matricule', $row, 'Matricule', $faker);
        // show differences
        self::log_diff($mandant, 'ENT' . $row['ImportID'], $row['Name'], $ilog);
        $mandant->save(false);
        
        return $ilog;
    }

    static function APSALABS($row, $faker, &$mandant_id) {
        
        $ilog = [];
        if (empty($row['TravID'])) {
            array_push($ilog, '<b>Field TravID missing, skipping line</b> ' . json_encode($row));
            return $ilog;
        }
        if (empty($row['ImportID'])) {
            array_push($ilog, '<b>Field ImportID missing, skipping line</b> ' . json_encode($row));
            return $ilog;
        }
        $contact_import_id = $row['TravID'];
        $abs_id = $row['ImportID'];
        // check if record exists in absences model
        $contact = Contacts::find()
                ->where(['ImportID' => $contact_import_id])
                ->andWhere(['ID_Mandant' => $mandant_id])
                ->one();
        if (empty($contact)) {
            array_push($ilog, '<b>Cannot find TravID, skipping line</b> ' . json_encode($row));
            return $ilog;
        }
        // check if record exists in absences model
        $absence = Absences::find()
                ->where(['ImportID' => $abs_id])
                ->one();
        if (!isset($absence)) {
            $absence = new Absences();
        }
        $absence->ID_Mandant = $mandant_id;
        $absence->ID_Contact = $contact->ID_Contact;
        $absence->ImportID = $abs_id;
        $absence->Start = $row['Startdate'];
        $absence->Stop = $row['Enddate'];
        $absence->Absencetype = $row['Reason'];
        $absence->Hours = $row['Hours'];
        // show differences
        self::log_diff($absence, 'TRAVABS' . $abs_id, $contact->getFullName(), $ilog);
        $absence->save(false);
       
        return $ilog;
    }
    
    static function TRAV($row, $faker, $mandant_id, $srcformat) {
        
        $ilog = [];
        if (empty($row['ImportID'])) {
            array_push($ilog, '<big><b>Field ImportID missing, skipping line</b></big> ' . json_encode($row));
            return $ilog;
        }
        $contact_id = $row['ImportID'];
        $contact = Contacts::find()
                ->where(['ImportID' => $contact_id])
                ->andWhere(['ID_Mandant' => $mandant_id])
                ->one();
        if (empty($contact)) {
            // contact does not exist try to find it by matricule
            $contact = Contacts::find()
                    ->where(['Matricule' => $row['Matricule']])
                    ->andWhere(['ID_Mandant' => $mandant_id])
                    ->one();
        }
        if (empty($contact)) {
            // contact does not exist create new record
            $contact = new Contacts();
            $contact->ID_Mandant = $mandant_id;
            $contact->ImportID = $contact_id;
        }
        if ($faker) {
            // get a fake contact
            $row['Firstname'] = self::getFirstname();
            $row['Lastname'] = self::getLastname();
            $add= [];
            switch (substr($row['Country'],0,1)) {
                case 'B':
                    $add = self::getAddressB();
                    break;
                case 'F':
                    $add = self::getAddressF();
                    break;
                case 'D':
                    $add = self::getAddressD();
                    break;
                default:
                    $add = self::getAddressL();
                    break;
            }
            $row['Address'] = $add[0];
            $row['Zip'] = $add[1];
            $row['City'] = $add[2];
            $row['Phone'] = $add[3];
        }

        if (empty($row['Gender'])) {
            $row['Gender'] = 'Male';
            $row['Title'] = 'Mr';            
        } else {
            if ($row['Gender'] == 'M') {
                $row['Title'] = 'Mr';
                $row['Gender'] = 'Male';
            } else {
                $row['Title'] = 'Ms';
                $row['Gender'] = 'Female';
            }
        }
        ImportJson::setstring($contact, 'Title', $row, 'Title', false);
        ImportJson::setstring($contact, 'Gender', $row, 'Gender', false);
        ImportJson::setstring($contact, 'Firstname', $row, 'Firstname', $faker);
        ImportJson::setstring($contact, 'Lastname', $row, 'Lastname', $faker);
        ImportJson::setstring($contact, 'Position', $row, 'Position', $faker);
        ImportJson::setstring($contact, 'Address', $row, 'Address', $faker);
        ImportJson::setstring($contact, 'Zip', $row, 'Zip', $faker);
        ImportJson::setstring($contact, 'City', $row, 'City', $faker);
        ImportJson::setstring($contact, 'Co_Code', $row, 'Country', false);
        ImportJson::setstring($contact, 'Phone', $row, 'Phone pro', $faker);
        ImportJson::setstring($contact, 'Email', $row, 'Email', $faker);
        ImportJson::setstring($contact, 'Email', $row, 'Email pro', $faker);

        $contact->generateEmail();
        $contact->createUser();
        
        ImportJson::setdate($contact, 'Birthday', $row, 'Birthday', $srcformat);
        ImportJson::setstring($contact, 'Nationality', $row, 'Nationality', $faker);
        ImportJson::setstring($contact, 'InternalNo', $row, 'InternalNo', $faker);
        ImportJson::setstring($contact, 'Matricule', $row, 'Matricule', $faker);
        ImportJson::setstring($contact, 'Iban', $row, 'Iban', $faker);
        ImportJson::setstring($contact, 'Bic', $row, 'Bic', $faker);
        ImportJson::setdate($contact, 'StartDate', $row, 'StartDate', $srcformat);
        ImportJson::setdate($contact, 'EndDate', $row, 'EndDate', $srcformat);
        ImportJson::setdate($contact, 'Seniority', $row, 'Seniority', $srcformat);
        ImportJson::setstring($contact, 'Studies', $row, 'Studies', false);
        ImportJson::setnumber($contact, 'Worktime', $row, 'Worktime', false);

        $contact->CW_Type = ContactTypes::Worker;
        // show differences
        self::log_diff($contact, 'TRAV' . $row['ImportID'], $row['Lastname'], $ilog);
        $contact->save(false);

        // check if row contains a workunit
        if (!empty($row['Workunit'])) {
            // search for the workunit name in all our languages _FR, _DE and _EN
            $wu = Workunits::find()
                    ->where(['ID_Mandant' => $mandant_id])
                    ->andWhere(['or', Workunits::ContLangAllFieldNames('Name', $row['Workunit'])])
                    ->one();
        }
        // check if row contains a employer
        if (!empty($row['Employer'])) {
            $co = Company::find()
                    ->where(['Name' => $row['Employer']])
                    ->andWhere(['CW_Type' => 'E'])
                    ->andWhere(['ID_Mandant' => $mandant_id])
                    ->one();
            if (!empty($co)) {
                $emp = Contacts::find()
                        ->where(['ID_Company' => $co->ID_Company])
                        ->one();
            }
        }
        // check if a Empcontracts Record exist
        $empcontract = Empcontracts::find()
                ->where(['ID_Contact' => $contact->ID_Contact])
                ->andWhere(['ID_Mandant' => $mandant_id]);
        if (!empty($wu)) {
            $empcontract->andWhere(['ID_Workunit' => $wu->ID_Workunit]);
        }
        if (!empty($emp)) {
            $empcontract->andWhere(['ID_Employer' => $emp->ID_Contact]);
        }
        $empcontract = $empcontract->one();
        if (empty($empcontract)) {
            // no create one empcontract record
            $empcontract = new Empcontracts();
            $empcontract->ID_Mandant = $mandant_id;
            $empcontract->ID_Contact = $contact->ID_Contact;
            $empcontract->Start = $contact->StartDate;
            $empcontract->Stop = $contact->EndDate;
            $empcontract->Position = $contact->Position;
            $empcontract->Worktime = $contact->Worktime;
            if (!empty($wu)) {
                $empcontract->ID_Workunit = $wu->ID_Workunit;
            }
            if (!empty($emp)) {
                $empcontract->ID_Employer = $emp->ID_Contact;
            }
            $empcontract->save(false);
        }
            
        return $ilog;
    }
 
    static function DECMAL($row, $faker, $mandant_id) {

        $ilog = [];

        if (count($row) == 0 ) {
            array_push($ilog, '<big><b>DECMAL invalid line</b></big> ' . json_encode($row));
            return $ilog;
        }
        if ($row[0] == '0') {
            return $ilog;
        }
        $contact_import_id = $row[8];
        $contact_matr = $row[2];
        if (!empty($contact_import_id)) {
            $contact = Contacts::find()
                    ->where(['ImportID' => $contact_import_id])
                    ->andWhere(['ID_Mandant' => $mandant_id])
                    ->one();
        }
        if (empty($contact)) {
            $contact = Contacts::find()
                    ->where(['Matricule' => $contact_matr])
                    ->andWhere(['ID_Mandant' => $mandant_id])
                    ->one();
        }
        if (empty($contact)) {
            array_push($ilog, '<big><b>DECMAL invalid worker</b></big> ' . $contact_matr . '/'.$contact_import_id);
            return $ilog;
        }
        if ($row[0] == '2') {
            // 2 = suppression
            array_push($ilog, 'DECMAL ' . $contact->getFullName() . ' delete ' . $row[3]);
            $absence = Absences::find()
                    ->where(['ID_Contact' => $contact->ID_Contact])
                    ->andWhere(['like', 'Start', substr($row[3],0,4) . '-'. substr($row[3],4,2)])
                    ->all();
            foreach ($absence as $model) {
               $model->delete();
            }
        }
        if ($row[0] == '1') {
            // 2 = création
            $start = date_create_from_format('Ymd', $row[5])->format('Y-m-d');
            $absence = Absences::find()
                    ->where(['ID_Contact' => $contact->ID_Contact])
                    ->andWhere(['Start' => $start])
                    ->one();
            if (!isset($absence)) {
                $absence = new Absences();
                $absence->ID_Contact = $contact->ID_Contact;
            }
            self::setdate($absence, 'Start', $row, 5, 'Ymd');
            self::setdate($absence, 'Stop', $row, 6, 'Ymd');
            switch ($row[4]) {
                case '1':
                    $absence->Absencetype = 'MAAC';
                    break;
                case '2':
                    $absence->Absencetype = 'FAM';
                    break;
                case '3':
                    $absence->Absencetype = 'MAT';
                    break;
                case '4':
                    $absence->Absencetype = 'COAD';
                    break;
                case '5':
                    $absence->Absencetype = 'COSP';
                    break;
            }
            $absence->Hours = $row[7];
            // show differences
            self::log_diff($absence, 'DECMAL ', $contact->getFullName(), $ilog);
            $absence->save(false);
        }

        return $ilog;
    }

    static function setstring(&$model, $attr, $row, $name, $faker) {
        if (array_key_exists($name, $row)) {
            $newval = '';
            if ($faker && mt_rand(1, 20) < 2) {
                $words = explode(" ", $row[$name]);
                foreach ($words as &$word) {
                    if (strlen($word) > 2)
                        $word = $word{0} . 
                                ImportJson::str_shuffle_unicode(substr($word, 1, -1)) . 
                                $word{strlen($word) - 1};
                }
                $newval = join(" ", $words);
            } else {
                $newval = $row[$name];
            }         
            $model->setAttribute($attr, $newval);
        }
    }

    static function setdate(&$model, $attr, $row, $name, $srcformat) {
        if (array_key_exists($name, $row)) {
            if ($row[$name] === null) {
                $newval = null;
            } else {
                $newval = date_create_from_format($srcformat, $row[$name])->format('Y-m-d');
            }
            $model->setAttribute($attr, $newval);
        }
    }
    
    static function setnumber(&$model, $attr, $row, $name, $srcformat) {
        if (array_key_exists($name, $row)) {
            $newval = $row[$name];
            $model->setAttribute($attr, $newval);
        }
    }

    static function str_shuffle_unicode($str) {
        $tmp = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
        shuffle($tmp);
        return join("", $tmp);
    }

    static function getFirstname() {
        
        static $a;
        if (!isset($a)) {
            $a = [ 
                'Admira','Adriano','Adrien','Agnès','Aicha','Alain','Alessandra','Alessandro','Alex','Alexandra',
                'Alicia','Aline','Alix','Alma','Amandine','Amélie','Ana','Anais','André','Andrea',
                'Anne','Anne-Claire','Anne-Françoise','Anne-Marie','Annemie','Anne-Sophie','Annick','Annie','Annik','Anouk',
                'Antonio','Armand','Arthur','Audrey','Aurea','Aurélie','Aurore','Barbara','Bénédicte','Bernadette',
                'Carmen','Carole','Caroline','Carolyn','Catarina','Catherine','Catia','Cécile','Céline','Chantal',
                'Charlotte','Christelle','Christian','Christiane','Christine','Christophe','Cindy','Claire','Claude','Claudia',
                'Danielle','David','Déborah','Delphine','Diana','Diane','Didace','Dietmar','Diogo','Dominique',
                'Dorothé-Maria','Dunja','Edgar','Edith','Edouard','Edvard','Edy','Egide','Elena','Eliane',
                'Erik','Erika','Ernestine','Esmeralda','Eva','EVITA','Fabiana','Fabien','Fabienne','Fabrice',
                'Fanny','Fany','Farida','Fatima','Favero-Krieger','Felix','Félix','Fernand','Fernanda','Francesco',
                'Gérardina','Germana','Gilbert','Gilles','Ginette','Gnad','Guillaume','Guy','Hadji','Harpa',
                'Helen','Helena','Hélène','Heloise','Henri','Ilham','Ina','Ingrid','Ioannis','Iris',
                'Jean-Marie','Jean-Michel','Jean-Paul','Jeff','Jennifer','Jessica','Jessy','Jo','Joana','Joao',
                'Joël','Joelle','Joëlle','Johanna','John','Josiane','Julie','Julien','Karin','Karine',
                'Laurence','Laurent','Léa','Lénaïck','Léonie','Lex','Liliane','Lily','Linda','Lisa',
                'Liss','Liz','Loredana','Luc','Luciana','Lucie','Lucien','Ludivine','Luisa','Lydia',
                'Margret','Maria','Marianne','Marie','Marie-Paule','Marie-Rose','Marina','Marine','Marion','Mark',
                'Martijn','Martina','Martine','Martinez','Maryse','Maurice','Maxime','Mélanie','Mélissa','Merryl',
                'Moris','Muriel','Myriam','Nadia','Nadine','Nancy','Natalia','Nathalie','Nico','Nicolas',
                'Nicole','Nils','Njabu','Nora','Odette','Onyszczuk','Otmar','Paolo','Pascal','Pascale',
                'Pierre','Pouss','Rachel','Raffael','Raffaele','Raphael','Raymond','Régis','René','Renée',
                'Rita','Robert','Roberta','Roberto','Robi','Roby','Roger','Romain','Rosa','Roy',
                'Sabine','Sabrina','Sabrine','Sam','Samuel','Sandra','Sandrine','Sandro','Sandy','Sarah',
                'Simone','Sofia','Sonia','Sonja','Sophie','Spina','Stéfania','Steffi','Stella','Steph',
                'Stephanie','Stéphanie','Steve','Suzi','Sylvain','Sylvia','Sylvie','Tamara','Tania','Tanja',
                'Valérie','Vanessa','Vasilica','Vera','Véra','Véronique','Vic','Virginie','Viviane','Werner',
                'Willem','Willy','Xavier','Yasmine','Yolande','Yves'                
            ];
        }
        return $a[mt_rand(0, count($a)-1)];
    }
    
    static function getLastname() {
        
        static $a;
        if (!isset($a)) {
            $a = [ 
                'Acque','Adjadia','Aflalo','Agostino','Alati','Almeida','Alves','Anacleto','Andrade',
                'Anen','Angeli','Antinori','Antoine','Antony','Arendt','Arnoldy','Arrensdorff','Azzouzi',
                'Bialkowski','Bichel','Biever','Bigelbach-Friederich','Bintener','Bisceglie','Bissen','Bissener','Biwer','Blasetti',
                'Blau','Boheme','Boon','Bouchard','Boulogne','Bourson','Bous','Branquinho Lourenço','Braun','Breda',
                'Castro','Castrovinci','Ceusters','Chiche','Chrétien','Claisse','Clarke','Clausse','Clemenz','Cloos',
                'Coffigniez','Collé','Conter','Cordel','Craps','Cruz','Dahm','Dalquier','Dawant','De Almeida',
                'Dentzer','Dias','Dias Ramos','Didden','Dieudonné','Djokic','Doerner','Dolizy','Domingues Carrasqueira ',
                'Dominique','Donven','Dos Santos','Drauden','Duarte Mendes','Duhautpas','Duriez','Edgar','Ehrhardt','Eischen',
                'Ferreira Pereira ','Feyereisen','Fey-Sunnen','Filippini','Fiorelli','Fiorucci','Flammang','Floener','Forthomme','Frisch',
                'Gaasch','Galasso','Ganko','Gansen','Garnier','Gasperi','Gehin','Geiben','Geimer','Gerard',
                'Glodt','Goedert','Goeres','Goetz','Goldschmit','Gonzalez Lopes','Graindorge','Greis','Greisch','Grethen',
                'Groff','Grün','Gunnar','Haas','Hallé','Halsdorf','Hames','Hansen','Hastert','Hauchard',
                'Hebert','Heine','Helfer','Hemmer','Hengen','Henryon','Hensen','Heusbourg','Heylmann','Heynen',
                'Jouant','Joyeux','Jungblut','Kahlki','Kalisa','Kauffmann','Kayser-Wengler','Kelsen','Kesseler','Klein',
                'Klocker','Kmiotek','Kolbach','Koster','Kremer','Kremmer','Kridel','Krier','Kuruhasanoglu','Lahyr',
                'Lecuit','Ledur','Lehoucq','Lemmer','Lentini','Lentz','Leroy','Lima','Lima Sequeira','Loos',
                'Loureiro','Louro','Lovecchio','Ludwig','Lukic-Schoetter','Macedo','Mackel','Maia','Maller','Malyasova',
                'Mathieu-Burton','Mauer','Mercuri','Meslin','Metz','Milne','Minocchi','Miny','Mohr','Molitor',
                'Monteiro','Moschel','Mosse','Moutrier','Mouyaux','Moyano','Mucevic','Muller','Müller',
                'Olivero','Omerovic','Orstreicher','Paglvögli','Pagnon','Parage','Parravano','Patschke','Peller','Persico',
                'Poos','Praus-Leuckefeld','Prisco','Puntel','Pütz-Hinkel','Quaring','Raach','Radziszewska','Radzizszewska','Ralinger',
                'Ranty','Ravanelli','Rech','Reding','Reeff','Reger','Remakel','Resl','Reuter','Ribeiro',
                'Schannel','Schares','Schaul','Schilling-Bebing','Schlesser','Schlim','Schlinker','Schmit','Schmitt','Schockert',
                'Schockweiler','Schoels','Scholtes','Scholzen','Schubert','Schummer','Schummer-Régin','Schwachtgen','Seckler','Serangeli',
                'Streitz','Thies','Thunus','Toussaint','Tranter','Turk','Valdagno','Valoy','van de Walle','Van Gemert',
                'Vandenbosch','Vandivinit','Venturini','Watgen','Weber','Wegner','Weiler','Welter','Wendling','Wernimont'
            ];
        }
        return $a[mt_rand(0, count($a)-1)];
    }
        
    static function getAddressL() {
        
        static $a;
        if (!isset($a)) {
            $a = [ 
                '1 Allée des Tilleuls','L-1025','Altwies','22 51 51-1','1 B Rue de l\'Eglise','L-1111','Alzingen','29 94 94 1',
                '1 B Rue de Medingen','L-1123','Arsdorf','42 22 33-8319','1 Haerebiergstrooss','L-1126','Aspelt','30 73 50',
                '1 rue Des Sept-Arpents','L-1145','Bastendorf','30 73 50','1 Rue d\'Olingen','L-1148','Belvaux','29 94 94-1',
                '1 Rue du Nord','L-1150','Berbourg','26 43 66 1','1 Rue du X Septembre','L-1211','Bereldange','22 51 51-1',
                '1 Rue Principale','L-1220','Bertrange','43 00-23252','1 Schouesbierg','L-1221','Bettembourg','45 71 39',
                '10 Avenue de la Libération','L-1225','Betzdorf','22 11 90','10 Avenue Guillaume','L-1234','Beyren','95 79 49',
                '10 Rue Abbé Neuens','L-1250','Born','57 47 89','10 Rue Auguste Letellier','L-1253','Bourglinster (Buerglënster','56 50 55',
                '10 Rue Belle-Vue','L-1262','Bous','22 07 55-1','10 Rue de la Gare','L-1278','Bridel','22 07 55 1',
                '10 Rue des Celtes','L-1316','Canach','44 88 14-1','10 Rue du X Septembre','L-1318','Capellen','80 41 02',
                '10 Rue Ermesinde','L-1319','Cents','26 81 90 ','101 Avenue De Luxembourg','L-1320','Clervaux','46 30 56',
                '101 Rue Cents','L-1321','Clervaux ','26 15 01 1','101 Rue Nicolas Biver','L-1328','Colmar-Berg','22 40 82',
                '104 Rue Pierre Gansen','L-1345','Crauthem','25 00 09 1 ','105 Avenue Pasteur','L-1354','Dahl','26 81 57-1',
                '106 Route de Bettembourg','L-1358','Dahlem','43 66 76-1','106 Rue de Grunewald','L-1371','Dalheim','72 00 81',
                '107 Boulevard Charles Simonis','L-1415','Diekirch','88 80 08','107-109 Val des Bons Malades','L-1420','Differdange','56 22 56',
                '10a Rue du Millénaire','L-1466','Echternach','40 78 78-1','11 Avenue Nicolas Kreins','L-1469','Eisenborn','99 76 73',
                '11 Grand-Rue','L-1470','Ell','99 77 14 1','11 Rue de la Piscine','L-1471','Erpeldange (Wiltz)','32 03 69',
                '11 Rue des Girondins','L-1511','Esch-sur-Alzette','54 89 13','11 Rue des Peupliers','L-1534','Ettelbruck','99 05 70',
                '11 Rue du Pont','L-1549','Fentange','32 54 13','11 Rue Klengliller','L-1613','Filsdorf','26 30 58 45',
                '11 Rue Nicolas Biever (r. du Commerce)','L-1617','Findel','44 94 99','110 Rue de Belvaux','L-1623','Foetz','43 66 07 1',
                '115 Rue de Cessange','L-1635','Grass','30 81 30','115 rue Des Muguets','L-1638','Grevenmacher','26 48 10 40',
                '116 Rue de Luxembourg','L-1650','Hagen','48 69 18','117 Rue de Bridel','L-1711','Harlange','26 30 58 45',
                '118 Route d\'Arlon','L-1724','Hautcharage','95 99 15','118 Rue de Belvaux','L-1748','Hellange','26 20 60-1 ',
                '118 Rue de Muehlenbach','L-1750','Helmdange','89 91 80','119 Rue de Luxembourg','L-1840','Helmsange','37 10 10',
                '12 Am Haff','L-1868','Hollenfels','42 83 28 1 ','12 Bisserwee','L-1870','Holzem','25 31 53',
                '12 Op der Barriaer','L-1912','Hosingen','36 00 38 1','12 Plâteau Altmünster','L-1918','Hostert (Rambrouch)','50 59 69',
                '12 route De Filsdorf','L-1922','Howald','43 39 44','12 Rue de Hassel','L-1932','Huncherange','83 60 17',
                '12 Rue d\'Olingen','L-1947','Junglinster','47 06 12','12 rue Jean Engling','L-1954','Kahler','39 00 21 1 ',
                '12 Rue Joseph Kutter','L-2112','Kayl','54 04 59','12 Rue Léon Kinsch','L-2121','Kehlen','79 01 88',
                '12 Rue Massen','L-2124','Keispelt','79 01 98','12 Rue Winston Churchill','L-2132','Kleinbettingen','80 43 23',
                '120 Rue des Pommiers','L-2153','Lamadelaine','58 56 51','121 Rue de Mamer','L-2162','Larochette (Fiels','58 62 61',
                '121-123 Rue Pasteur','L-2163','Leudelange','42 54 85 ','125 Rue Laurent Ménager','L-2167','Liefrange','78 96 96-1',
                '126 Boulevard J.-F. Kennedy','L-2168','Linger','83 70 18','126 Place Prince Jean','L-2213','Lintgen','73 01 44',
                '127 Route de Luxembourg','L-2220','Lipperscheid','81 09 53 1','128 Place Prince Jean','L-2221','Livange','58 26 98-1',
                '13 Avenue François Clément','L-2265','Luxembourg-Merl','78 98 30','13 Kraeizgaass','L-2270','Mamer','33 51 67',
                '13 Robert Schuman-Strooss','L-2311','Mersch','72 92 64','13 Route de Luxembourg','L-2316','Mertert','691656728',
                '13 Route de Trèves','L-2320','Mertzig','31 16 07-1','13 Rue Albert Philippe','L-2328','Mondercange','43 03-1',
                '13 Rue de l\'Industrie','L-2330','Mondorf-les-Bains','95 75 85','13 Rue de Meispelt','L-2331','Moutfort','42 42 2000',
                '13 Rue Jean Schaack','L-2356','Niederkorn','58 26 66','131 Rue Notre-Dame','L-2426','Noerdange','49 48 48-1',
                '133 Rue de Muehlenbach','L-2440','Noertzange','30 03 86','138 Boulevard de la Pétrusse','L-2441','Nommern','49 48 48-1',
                '14 Route de Longwy','L-2443','Oberfeulen','22 51 51-1','14 Rue Caspar-Mathias Spoo','L-2451','Oberkorn','80 42 41',
                '14 rue Neuve','L-2539','Olm','95 99 85-1','141 Avenue de la Liberté','L-2550','Pétange','42 22 33-8262',
                '145 Rue des Minières','L-2560','Pintsch','48 64 09','147 Rue Cents','L-2562','Platen','40 12 99 1',
                '147 Rue Principale','L-2563','Pontpierre','26 44 03 65','15 Leemerwee','L-2611','Rambrouch','42 22 33-8344',
                '15 Op Fankenacker','L-2628','Reckange-sur-Mess','51 60 54','15 Route de Remich','L-2630','Redange-sur-Attert','42 44 77',
                '15 Rue des Roses','L-2714','Remerschen','58 49 88','15 Rue des Scillas','L-2716','Remich','40 27 40 1',
                '15 Rue Mgr. Fallize','L-2728','Rodange','40 72 27','150 rue De Beggen','L-2730','Roedgen','52 38 05',
                '150 Rue Victor Hugo','L-2732','Rollingen','57 25 52','15-17 Rue de la Mairie','L-2734','Rollingen (Mersch)','44 80 93',
                '152 Avenue du X Septembre','L-2951','Rosport','49 20 49','152 Rue de Bastogne','L-2988','Rumelange','32 55 80',
                '155 Route de Luxembourg','L-3222','Sandweiler','40 76 33','156 Avenue Gaston Diderich','L-3236','Sanem','37 04 06',
                '16 Beetebuergerstrooss','L-3261','Schoos','4 78-2290','16 Route de Mondorf - Résidence Europe','L-3265','Schouweiler','46 65 80',
                '16 Rue Christophe Plantin','L-3275','Schrassig','26 12 99-704','16 Rue de Bonnevoie','L-3281','Schuttrange','247-86660',
                '16 Rue de Luxembourg','L-3313','Senningerberg','45 17 71 1','16 Rue des Vieilles Parts','L-3315','Soleuvre','43 60 60 1',
                '16 Rue Nicolas Goedert','L-3333','Steinsel','4 78-5506','16 Rue Woiwer','L-3340','Strassen','47 30 71',
                '161 Route d\'Esch','L-3352','Tétange','43 58 51','163 Rue Cents','L-3353','Troisvierges','47 59 81-1 ',
                '165a Route de Longwy','L-3360','Tuntange','47 58 21-1 ','169 A Rue de Rollingergrund','L-3367','Uebersyren','47 59 81-1 ',
                '17 Chemin de Brouck','L-3378','Useldange','80 32 14-1 ','17 rue De l\'Eglise','L-3380','Vianden','47 44 56 1',
                '17 Rue des Bains','L-3401','Warken','24785610','17 Rue des Prés','L-3402','Wasserbillig','54 58 28-1',
                '17 rue Jos Kayser','L-3424','Wasserbillig ','44 54 64 1','179 rue De Luxembourg','L-3441','Weicherdange','247-85587 ',
                '179 Rue du Parc','L-3465','Weiler-la-Tour (Weiler','23 67 1-1','18 B Rue de la Chapelle','L-3468','Welfrange','48 84 03',
                '18 Ennert den Thermen','L-3480','Wiltz','22 56 17','18 Klatzewee','L-3515','Wilwerwiltz','45 02 28',
                '189 Rue Pierre Gansen','L-3542','Wormeldange','42 68 82-1','4 Rue Pierre de Coubertin - Domaine de B','L-6793','Altwies','621161416',
                '42 Rue Pierre Frieden','L-7417','Alzingen','621167626','3 Rue Jos. Moscardo','L-5335','Arsdorf','621295130',
                '5 Avenue Marie-Thérèse','L-8030','Bascharage','0049 65 18 33 22','61 Rue de Pulvermuehl','L-9164','Bastendorf','021 26 36 76',
                '42 Rue du Cimetière','L-7373','Belvaux','03 87 29 84 20','6 rue Des Alliés','L-8821','Berbourg','03 87 51 62 15',
                '6 Rue des Artisans','L-9011','Bereldange','03 87 73 74 26','37 Rue des Alliés','L-6139','Bergem','03 87 76 67 32',
                '6 Avenue Marie-Adélaïde','L-8558','Beringen','063 38 83 08','59 Rue Gioacchino Rossini','L-8531','Bertrange','22 00 32',
                '21 Sentier de Bricherhof','L-4316','Beyren','22 13 21','7 Rue du Parc','L-9906','Bilsdorf','22 19 75',
                '249 Rue de Cessange','L-4607','Bissen','22 26 55','242 route D\'Esch','L-4602','Born','22 49 60',
                '31a Route de Luxembourg','L-5480','Bourglinster (Buerglënster','22 50 27','33 Rue de Luxembourg','L-5635','Bous','22 50 77 200',
                '7 Rue Emile Laux','L-9907','Burmerange','22 81 67','2 Rue Robert Schuman','L-4003','Canach','22 85 28',
                '34a Rue de Luxembourg','L-5710','Capellen','22 87 77-1','5 Cité Saint Blaise','L-8041','Cents','23 60 92 13',
                '5 A Allée du Carmel','L-8025','Clervaux','23 62 03 04','4 Rue du Stade','L-6715','Clervaux ','23 62 16 44',
                '4 Rue d\'Everlange','L-6693','Contern','23 63 92 35','359 A Rue de Neudorf','L-5854','Crauthem','23 63 93 52',
                '39 Rue du Baerendall','L-6211','Dahl','23 64 06 74','42 Rue de Luxembourg','L-7333','Dahlem','23 65 08 08',
                '48 Route d\'Arlon','L-7784','Dalheim','23 66 16 41','6 Rue Metzkimmert','L-9090','Diekirch','23 66 36-1',
                '2 A Beim Dreieck','L-3761','Dudelange','23 66 83 91','65 Rue Zénon Bernard','L-9390','Echternach','23 66 90 32',
                '65 Route de Dudelange','L-9280','Eisenborn','23 66 90 45','1-9 Moulin de Born','L-3598','Ell','23 67 67 37',
                '39-41 Rue de Gasperich','L-6418','Erpeldange (Wiltz)','23 67 73 66','57 Cité Millewee','L-8381','Erpeldange-sur-Sûre','23 69 74 99 ',
                '38A Rue des Celtes','L-6162','Ersange','23 69 83 02','22 Rue de Strasbourg','L-4412','Esch-sur-Alzette','23 69 88 52 ',
                '3 Rue des Capucins','L-5314','Filsdorf','25 23 86','1A Route de Rumelange','L-3713','Findel','25 28 28',
                '24 Rue du Village','L-4599','Foetz','25 42 59 1','5A Rue des Maximins','L-8550','Frisange','25 47 37 1',
                '41-43 Route de Remich','L-7315','Gonderange','26 11 12 1','6-8 rue De Strasbourg','L-9644','Grass','26 19 06 60',
                '25-27 Rue Baudouin','L-4628','Grevenmacher','26 25 44-1','40 Rue du Verger','L-7224','Hagen','26 30 53 04',
                '228 rue De Beggen','L-4422','Hellange','26 43 66 1','4 Rue Joseph Felten','L-6723','Helmdange','26 45 90 71',
                '19 Route d\'Esch','L-3621','Helmsange','26 48 00 26','47 Rue de Gasperich','L-7740','Hesperange','26 50-800',
                '4 Um Paerchen','L-6832','Hëttermillen','26 56 84 33','3 Kohlenberg','L-4972','Hollenfels','26 57 44 21',
                '23 Rue de l\'Ecole','L-4437','Holzem','26 61 52 63','23 Rue Hoovelecker Buurchmauer','L-4487','Hosingen','26 67 55 01',
                '6 Rue Genistre','L-9068','Hostert (Rambrouch)','26 71 44 47','406 Route de Thionville','L-7231','Howald','26 78 02 20',
                '2 Georges Reuter Plaz','L-3770','Itzig','26 95 02 96','26 Rue Raoul Follereau','L-4712','Junglinster','29 00 90 ',
                '24 Duerfstrooss','L-4560','Kahler','29 11 22','7 Rue du Cimetière','L-9841','Kayl','29 21 22 11',
                '57 rue De Beggen','L-8383','Kehlen','29 22 80','44 Rue d\'Eich','L-7508','Keispelt','29 59 95-1',
                '40 Op Fankenacker','L-7217','Kleinbettingen','30 56 10','7 Rue de la Gare','L-9836','Koerich','30 56 56',
                '36 Rue Pierre Martin','L-5955','Koetschette','30 70 76','45 Rue de l\'Ecole','L-7544','Lamadelaine','30 76 76',
                '3 Rue Albert Borschette','L-4992','Liefrange','31 17 37','33 Rue de Niederkorn','L-5638','Linger','31 30 66',
                '20 Avenue Jf Kennedy','L-4005','Lintgen','31 39 41','36 Am Floss','L-5884','Lipperscheid','31 40 27-0 ',
                '3 rue De la Colline','L-4994','Livange','31 71 70','24 Rue de la Libération','L-4592','Lorentzweiler','31 73 02',
                '60 Rue de Steinsel','L-9161','Luxembourg','31 80 24 1','21 B Rue Principale','L-4170','Luxembourg-Merl','31 80 29',
                '2 Rue de Wecker','L-3940','Mertert','32 53 36','50 Avenue J.-F. Kennedy','L-8247','Mertzig','32 53 78',
                '6 Rue de l\'Ecole','L-8811','Mondercange','32 55 12','3-5 Cité Joseph Brebsom','L-5716','Mondorf-les-Bains','32 55 18',
                '33 Route de Trèves','L-5552','Moutfort','32 55 19','6 Rue des Eglantiers','L-9012','Munsbach','32 55 44',
                '6 Rue de Bettembourg','L-8806','Neuhaeusgen','32 55 75','6 Rue Boland','L-8707','Niederkorn','32 56 27',
                '34 Rue Eich','L-5680','Nommern','32 77 60','2 Nei Wiss','L-3811','Oberfeulen','32 83 28-1',
                '4 Rue Marguerite-Séraphine Beving','L-6750','Oberkorn','33 03 40','21 Porte des Ardennes','L-4171','Oberpallen','33 06 10',
                '7 Kettengaass','L-9714','Oetrange','33 14 75','250 Avenue Gaston Diderich','L-4622','Olm','33 20 71',
                '2 Rue de Contern','L-3877','Platen','33 36 67','53 Rue Sidney Thomas','L-8308','Pontpierre','33 37 62',
                '54 Rue de Luxembourg','L-8317','Rambrouch','33 51 38','2 Rue des Romains','L-3943','Reckange-sur-Mess','33 93 13',
                '23 Rue Charles IV','L-4435','Redange-sur-Attert','33 97 05','23 Schoulstrooss','L-4536','Reichlange','34 74 79',
                '7 Lëtzebuergerstrooss','L-9767','Reisdorf','34 85 86','33 Rue Wilson','L-5671','Remerschen','34 89 75',
                '30 rue Langheck','L-5408','Roedgen','36 77 66','21 Rue d\'Amsterdam','L-4204','Rollingen','36 77 97',
                '52 rue Jean Wolter','L-8286','Rollingen (Mersch)','37 03 64','3 Rue des Moulins','L-5316','Rosport','37 11 20',
                '49-51 Rue Principale','L-8017','Rumelange','37 13 71','37 Route de Trèves','L-6130','Sandweiler','37 99 88-1',
                '3 Route d\'Olm','L-4990','Sanem','38 06 70','36 Rue Jean-Baptiste Esch','L-5950','Schieren','39 02 73',
                '1a Rue Gabriel Lippmann Parc d\'Activité ','L-3723','Schouweiler','39 75 01','43 Rue du Sanatorium','L-7475','Schrassig','39 76 01',
                '3B Um Salzwaasser','L-6470','Schuttrange','39 95 98','6 Rue du Couvent','L-9016','Senningerberg','39 98 03',
                '2 Place De Strasbourg','L-3841','Soleuvre','4 01 11 1','31-33 Rue Principale','L-5451','Stadtbredimus','4 78-2956',
                '195 Rue de Differdange','L-3673','Steinfort','4 99 24-1','1A Rue de la Paix','L-3717','Steinsel','4 99 24-1',
                '61A Rue de Trèves','L-9220','Troisvierges','40 06 16-1','2 Rue Bechel','L-3863','Tuntange','40 11 1 1',
                '65 rue De Noertzange','L-9350','Uebersyren','40 12 38','32 Rue de Stavelot','L-5515','Useldange','40 30 40-215',
                '62 Rue de Belvaux','L-9232','Vianden','40 49 49 500','62 rue Principale','L-9233','Wahlhausen','40 49 49 900',
                '23 Rue Michel Thilges','L-4526','Walferdange','40 50 03','48 Rue de l\'Etang','L-8001','Warken','40 83 80',
                '290 Avenue Gaston Diderich','L-4910','Wasserbillig','40 85 90','264 route D\'Esch','L-4714','Wasserbillig ','40 86 27',
                '26A Avenue Grand-Duc Jean','L-4732','Welfrange','42 45 11 1','27 Route d\'Arlon','L-4755','Wiltz','42 45 11 1',
                '279 Route de Longwy','L-4761','Wilwerwiltz','42 45 11 1','28 Rue Emmanuel Servais','L-4807','Windhof','42 45 11 1',
                '28 Rue Servais','L-4831','Wintrange','42 45 11 1','29 Rue André Duchscher','L-4844','Wormeldange','42 45 11 1',
                '2a Rue de la Fontaine','L-4940','Altwies','42 45 11 1','27 Chemin vert','L-4751','Alzingen','42 45 11 1 ',
                '272 Route de Thionville','L-4757','Arsdorf','42 45 11 1 ','28 Rue de l\'Industrie','L-4797','Aspelt','42 45 11-1',
                '29 A Rue Michel Welter','L-4840','Assel','42 45 11-1','23 Rue des Prés','L-4451','Bascharage','42 55 29',
                '23 Avenue Monterey','L-4434','Berbourg','42 76 42 1','52 A Rue de l\'Eglise','L-8278','Bereldange','42 84 77 ',
                '5 Rue de Nassau','L-8095','Bergem','42 86 50','444 Route de Longwy','L-7535','Beringen','42 86 61',
                '31 Rue de la Montagne','L-5423','Bertrange','43 02-1','54 Rue Cyprien Merjai','L-8311','Bettembourg','43 10 43',
                '55 Chemin J.-A. Zinnen','L-8325','Betzdorf','43 74 40','54 Rue Cents','L-8310','Beyren','43 76 14',
                '49 Dikrecherstrooss','L-8013','Born','43 94 44-1','5 Avenue des Alliés','L-8027','Bourglinster (Buerglënster','43 94 44-1',
                '50 Rue des Prés','L-8250','Bous','43 94 44-1','20 Rue Dr Flesch','L-4046','Bridel','43 95 58 1',
                '615 Rue de Neudorf','L-9168','Brouch (Mersch) (Bruch','44 09 51 1','62 Val des Aulnes','L-9244','Burmerange','44 23 24',
                '30 Rue du Parc','L-5402','Canach','44 32 52','43 Route de Remich','L-7465','Capellen','44 40 91-801 ',
                '56 Rue Cents','L-8362','Clervaux ','44 74 40','43 Am Duerf','L-7435','Colmar-Berg','44 75 90',
                '2 Rue de la Gare','L-3895','Consdorf','44 82 85','52 Rue de Tétange','L-8285','Contern','44 88 04 1',
                '3-5 rue Des Frênes','L-5751','Crauthem','44 93 99 1','35 Route d\'Arlon','L-5741','Dahl','44 99 78 50',
                '37 Rue Prenzebierg','L-6140','Dahlem','45 08 85 1','34 Rue Charlemagne','L-5675','Dalheim','45 17 13',
                '26 Rue de Gostingen','L-4650','Dippach','45 85 87-1','2 Op der Tonn','L-3825','Dudelange','46 00 11-1',
                '20 rue De l\'Eglise','L-4031','Echternach','46 06 93 ','36 Rue de la Fontaine (Domaine Schlassga','L-5892','Eisenborn','46 22 75',
                '50 Rue des Romains','L-8254','Ell','46 27 53','30 Rue James-Hillard Polk','L-5407','Erpeldange (Wiltz)','46 44 22',
                '5 Rue du Cimetière','L-8140','Erpeldange-sur-Sûre','46 52 88 ','4 Rue Prommenschenkel','L-6831','Ersange','46 69 66 1',
                '26 Rue de la Déportation','L-4662','Esch-sur-Alzette','46 77 66-1','26 A Rue de Wiltz','L-4645','Ettelbruck','46 95 01',
                '2 Rue Brameschhof','L-3872','Findel','48 46 44 ','20 A Rue de Pulvermuehl','L-4004','Foetz','48 51 41-1',
                '5 Rue Jean Marx','L-8239','Frisange','48 66 40','6 Route de Mersch','L-8611','Gonderange','48 91 61 1',
                '6 Avenue Dr Ernest Feltgen','L-8552','Grass','488 288 1','5 Rue de l\'Eglise','L-8077','Grevenmacher','49 05 59',
                '46 Route de Diekirch','L-7561','Hagen','49 24 74','59 Rue Michel Thilges','L-8537','Harlange','49 25 56-611',
                '3 Rue des Tilleuls','L-5330','Hautcharage','49 48 48-1','36 Rue de Dudelange','L-5889','Hellange','49 55 15',
                '45 Rue Charlemagne','L-7540','Hesperange','49 58 87','5 Rue Jean l\'Aveugle','L-8212','Hëttermillen','49 86 09',
                '26 Cité op Hudelen','L-4649','Hollenfels','49 94 66 1','52 Rue Prince Henri','L-8301','Holzem','50 04 28',
                '6B Rue Stohlbour','L-9665','Hosingen','50 11 71','3B Wäistrooss','L-6475','Hostert (Rambrouch)','50 15 46',
                '4 Cité Raoul Follereau','L-6581','Howald','50 25 81','49 Rue Zénon Bernard','L-8014','Huncherange','50 37 37 1',
                '57 Rue de Kirchberg','L-8399','Junglinster','50 53 35','5 rue De l\'Ecole','L-8065','Kahler','50 53 57',
                '3 An der Gruecht','L-4947','Kayl','50 58 60','3 Allée des Tilleuls','L-4945','Kehlen','50 58 60 ',
                '46 Rue de la Toison d\'Or','L-7572','Keispelt','50 70 16','5 Rue de l\'Hôpital','L-8080','Kleinbettingen','50 72 24',
                '5 Route de la Sûre','L-8048','Koerich','50 77 47','5 Rue de l\'Ecole Agricole','L-8069','Koetschette','50 88 08',
                '35 Val Saint André','L-5811','Lamadelaine','51 19 61','2 Place de l\'Eglise','L-3832','Larochette (Fiels','51 26 52',
                '33 Rue Antoine Meyer','L-5612','Linger','52 05 99 ','43 Rue Principale','L-7480','Lintgen','52 17 17',
                '3 Rue de la Ferme','L-4995','Lipperscheid','52 41 20','30 Rue de la Croix','L-5376','Livange','52 48 59',
                '22 Rue de Bascharage','L-4411','Lorentzweiler','52 49 50','47 Rue d\'Oetrange','L-7780','Luxembourg','52 53 21',
                '19 Rue Jean-Pierre Bausch','L-3631','Luxembourg-Merl','52 61 21-292','46 Rue de Cessange','L-7565','Mamer','53 00 23',
                '202 Rue du Rollingergrund','L-4122','Mersch','53 11 90-1','21-23 Rue Norbert Metz','L-4332','Mertert','53 12 85',
                '59 A Avenue Victor Hugo','L-8510','Mondorf-les-Bains','53 20 57','33 Rue Marie-Adélaïde','L-5650','Moutfort','54 11 33',
                '2 Rue de Pulvermuehl','L-3932','Munsbach','54 27 13','31 Rue du Kiem','L-5429','Neuhaeusgen','54 34 77',
                '18B Rue de la chapelle','L-3543','Niederkorn','54 34 90','31 Wäistrooss','L-5440','Noerdange','54 49 12',
                '61-63 Avenue Grande-Duchesse Charlotte','L-9179','Noertzange','54 55 45','21 Rue de Schwiedelbrouch','L-4240','Nommern','54 78 06',
                '5 Route de Zoufftgen','L-8058','Oberpallen','55 10 95','68 Route d\'Arlon','L-9573','Oetrange','55 20 02 1',
                '5 Rue Zénon Bernard','L-8242','Olm','55 30 84','309 Route d\'Arlon','L-5410','Pétange','558 758 1 ',
                '42 rue Jean-François Gangler Hall Omnisp','L-7374','Pintsch','56 02 62','39 Boulevard Joseph II','L-6196','Platen','56 31 70 ',
                '34 Rue Michel Welter','L-5685','Pontpierre','56 66 55','19 Rue Théodore Gillen','L-3636','Rambrouch','57 12 17',
                '298 Rue de Rollingergrund','L-4930','Reichlange','57 48 80-1','46 Rue Marie-Thérèse','L-7701','Reisdorf','57 50 59',
                '60 Rue de Luxembourg','L-9147','Remerschen','57 56 57','5 Rue de l\'Industrie','L-8081','Remich','58 24 18',
                '56 Rue Clairefontaine','L-8368','Rodange','58 45 451','34 rue Thomas Byrne','L-5698','Roedgen','58 80 93',
                '35 Rue Notre-Dame','L-5772','Rollingen','58 82 89','5 Rue du Kiem','L-8147','Rollingen (Mersch)','59 03 77',
                '32 Rue de l\'Industrie','L-5507','Sandweiler','59 49 45','395 Route de Thionville','L-6434','Sanem','72 70 71',
                '55 Rue de Kockelscheuer','L-8331','Schieren','74 01 42','4 Rue Béatrix de Bourbon','L-6635','Schifflange','74 03 48',
                '22 Roude Wee','L-4390','Schoos','75 01 49','60 Rue de la Déportation','L-9145','Schouweiler','75 05 66 1',
                '19 Boulevard de la Pétrusse','L-3550','Schrassig','75 85 38','4 Rue de la Forêt','L-6684','Schuttrange','75 87 25',
                '56 Boulevard Du Général Patton','L-8352','Stadtbredimus','75 95 20','20 Rue de Contern','L-4030','Steinfort','76 84 85 ',
                '7 Quartier de l\'Eglise','L-9807','Steinsel','76 92 98','40 Rue de Syren','L-7220','Strassen','77 91 39',
                '192 Rue des Romains','L-3672','Tétange','78 71 82 1','2 rue De Beyren','L-3873','Troisvierges','78 72 86',
                '22 Rue Docteur Conzemius','L-4418','Tuntange','78 81 94-1','54 Rue JF Kennedy','L-8323','Uebersyren','78 88 75',
                '481 Route de Longwy','L-8009','Wahlhausen','81 03 60','2 Rue Louis Braille','L-4001','Walferdange','81 87 51',
                '2 Place de l\'Église','L-3833','Warken','81 88 80','19 Haaptstrooss','L-3566','Wasserbillig','81 90 78 ',
                '21 Rue du 9 Mai 1944','L-4309','Wasserbillig ','81 92 48','21-23 Rue Hoovelecker Buurchmauer','L-4326','Weicherdange','83 41 64',
                '21 Rue Bernard Haal','L-4172','Weiler-la-Tour (Weiler','83 48 20','3-5 Rue Jean Wolter','L-5752','Welfrange','83 50 80',
                '19 Rue de la Sûre','L-3630','Windhof','83 97 42','202 Val des Bons-Malades','L-4141','Wintrange','84 93 06',
                '21 A Millefeld','L-4149','Wormeldange','86 91 96','7 Avenue de la Gare','L-9711','Altwies','87 85 44',
                '57 Rue Philippe Manternach','L-8447','Alzingen','88 80 64','32 A Rue Zénon Bernard','L-5495','Arsdorf','92 96 13',
                '52 Rue de Schouweiler','L-8279','Aspelt','94 92 11 1','2 Rue de l\'Eglise','L-3917','Assel','95 97 11'
            ];
        }
        $r = mt_rand(0, floor(count($a)/4)-1)*4;
        return array($a[$r], $a[$r+1], $a[$r+2], $a[$r+3]);
    }
        
    static function getAddressD() {
        
        static $a;
        if (!isset($a)) {
            $a = [ 
                '6 Aemilianusstrasse','D-54317','Osburg','+0049 (0) 68 21 95 1','104 Industriestr','D-54338','Schweich','+49 (0)177 31 83 73 ',
                'Flugplatz Düren','D-54295','Trier','+49 (0)6 84 11 87 39','Gewerbegebiet Langwies','D-54317','Kasel','+49 (0)6332 1 54 82',
                '18 Schlossstr','D-54317','Herl','+49 (0)6332 1 70 21','37 Werner-von-Siemens-Str','D-54317','Osburg','+49 (0)6332 21 84',
                '6 A Philipp-Reis-Strasse','D-54317','Korlingen','+49 (0)6332 30 01','4-6 Eisenbahnstr','D-54311','Trierweiler','+49 (0)6332 33 45',
                '6 Lucie-Bolte-Strasse','D-54316','Schöndorf','+49 (0)6332 35 91','49 Hauptstr','D-54317','Osburg','+49 (0)6332 39 54',
                '4 A Philipp-Reis-Strasse','D-54317','Gusterath','+49 (0)6332 4 00 48','Industriegebiet','D-54317','Gusterath','+49 (0)6332 4 05 64',
                '67 Dillinger Strasse','D-54317','Morscheid','+49 (0)6332 4 14 15','12 Fussbachstrasse','D-54311','Trierweiler','+49 (0)6332 4 17 80',
                '17 A Falscheider Strasse','D-54314','Zerf','+49 (0)6332 4 47 82','Industriegebiet','D-54317','Osburg','+49 (0)6332 4 52 61',
                '50 Hauptstr','D-54311','Trierweiler','+49 (0)6332 48 01-0','145 Schaumbergstr','D-54311','Trierweiler','+49 (0)6332 48 70',
                '25 Hospitalstr','D-54317','Thomm','+49 (0)6332 56 01 26','Flugplatz Düren','D-54317','Farschweiler','+49 (0)6332 56 03 00',
                '65 Dillinger Strasse','D-54317','Korlingen','+49 (0)6332 56 60 09','Felsberger Strasse','D-54317','Farschweiler','+49 (0)6332 7 33 66',
                '16 Alfred-Nobel-Strasse','D-54314','Zerf','+49 (0)6332 7 38 92','54 Schlossbergstrasse - Alte Schule','D-54317','Farschweiler','+49 (0)6332 7 39 35',
                '119 Bahnhofstr','D-54316','Pluwig','+49 (0)6332 7 50 43','2 Lucie-Bolte-Strasse','D-54317','Osburg','+49 (0)6332 7 52 13',
                '99 Zum Rotwäldchen','D-54316','Pluwig','+49 (0)6332 7 52 74','Gewerbegebiet John','D-54316','Schöndorf','+49 (0)6332 7 53 59',
                '2 Werner-von-Siemens-Str','D-54313','Zemmer','+49 (0)6332 7 60 05','44 Saarwellinger Strasse','D-54313','Rodt','+49 (0)6332 7 60 85',
                '26 Schwalbacher Strasse','D-54314','Zerf','+49 (0)6332 7 67 55','11 Gewerbegebiet John','D-54317','Gusterath','+49 (0)6332 7 75 99',
                '6 Carl-Friedrich-Gauss-Strasse','D-54314','Zerf','+49 (0)6332 8 02-0','2 Im Schmalzgarten','D-54317','Osburg','+49 (0)6332 8 08 0',
                '4 Lucie-Bolte-Strasse','D-54316','Lampaden','+49 (0)6332 8 18 100','8 Alfred-Nobel-Str. - Industriegebiet','D-54316','Pluwig','+49 (0)6332 8 30',
                '7 Bachstrasse','D-54311','Trierweiler','+49 (0)6332 80 00 0','15a Werner-von-Siemens-Str','D-54317','Osburg','+49 (0)6332 80 63 0',
                '8 A Nassauerstrasse','D-54311','Trierweiler','+49 (0)6332 89 0','8 Lucie-Bolte-Strasse','D-54314','Baldringen','+49 (0)6332 89 29 26',
                'Gewerbegebiet Langwies','D-54317','Gusterath','+49 (0)6332 9 21 50','19-21 Hessbachstrasse','D-54317','Gusterath','+49 (0)6332 9 21 90',
                '18 Hasbornerstr','D-54311','Trierweiler','+49 (0)6332 9 22 90','10 Alfred-Nobel-Strasse','D-54314','Greimerath','+49 (0)6332 9 24 80',
                '99 Lebacher Strasse','D-54313','Zemmer','+49 (0)6332 9 25 70','18-20 Weinbachstrasse','D-54317','Kasel','+49 (0)6332 9 62 20',
                '24 Matzenberg','D-54316','Schöndorf','+49 (0)6332 90 44 02','2 Saarwellingerstrasse','D-54311','Trierweiler','+49 (0)6332 916 0',
                '31 Werner-von-Siemens-Str','D-54314','Paschel','+49 (0)6332 92 21 0','1 Schulze-Kathrin-Strasse','D-54317','Herl','+49 (0)6332 92 60 0',
                '5 Brunnenstrasse','D-54313','Zemmer','+49 (0)6332 96 16 0','9 Alfred-Nobel-Strasse','D-54313','Zemmer','+49 (0)6332 98 13 50',
                '12 Amselweg','D-54311','Trierweiler','+49 (0)6332 99 39 0','4 A Alfred-Nobel-Strasse - Industriegebi','D-54317','Gutweiler','+49 (0)6337 13 73',
                'Gewerbegebiet John','D-54316','Franzenheim','+49 (0)6337 82 10','101 Bahnhofstr','D-54311','Trierweiler','+49 (0)6337 87 14',
                '80 Josefstr','D-54311','Trierweiler','+49 (0)6337 92 11 0','An der B 51','D-54411','Hermeskeil','+49 (0)68 55 63 65',
                '','D-54427','Kell','+49 (0)68 66 19 12 2','2 Werner-von-Siemens-Str','D-54296','Trier','+49 (0)6803 23 97',
                'Gewerbegebiet Langwies','D-54295','Trier','+49 (0)6803 27 11','8 A Nassauerstrasse','D-54296','Trier','+49 (0)6803 37 45',
                '18 Hasbornerstr','D-54296','Trier','+49 (0)6803 4 61','Gustav-Stresemann-Strasse','D-54296','Trier','+49 (0)6803 4 69',
                '2 Im Schmalzgarten','D-54295','Trier','+49 (0)6803 6 38','18-20 Weinbachstrasse','D-54295','Trier','+49 (0)6803 98 17 92',
                '18 Schlossstr','D-54295','Trier','+49 (0)6803 98 18 01','67 Dillinger Strasse','D-54295','Trier','+49 (0)6803 99 48-0',
                'An der B 51','D-54296','Trier','+49 (0)6803 99 55 0','15a Werner-von-Siemens-Str','D-54295','Trier','+49 (0)6804 91 06-7',
                'Felsberger Strasse','D-54295','Trier','+49 (0)6804 91 47 45','252 Provinzialstr','D-54338','Schweich','+49 (0)6806 8 48 55',
                '5 Gustav-Stresemann-Strasse','D-54338','Schweich','+49 (0)6806 8 61 64','8 Marsiliusstrasse','D-54338','Schweich','+49 (0)6806 9 80-0',
                'Industriegelände','D-54329','Konz','+49 (0)6821 1 05 0','5 Gustav-Stresemann-Strasse','D-54329','Konz','+49 (0)6821 1 30 65',
                '252 Provinzialstr','D-54329','Konz','+49 (0)6821 15 78','208 Provinzialsrt','D-54329','Konz','+49 (0)6821 17 95 95',
                'Walter von Rathenau Strasse','D-54329','Konz','+49 (0)6821 18 30','44 Bonifatiusstrasse','D-54317','Gusterath','+49 (0)6821 2 12 25',
                'Linslerhof','D-54317','Gusterath','+49 (0)6821 2 20 96','1 Thomas-Dachser-Str','D-54329','Konz','+49 (0)6821 2 25 10',
                '80 Josefstr','D-54329','Konz','+49 (0)6821 2 27 48','23 Comotorstrasse','D-54329','Konz','+49 (0)6821 2 33 26',
                '5 Brunnenstrasse','D-54329','Konz','+49 (0)6821 2 40 50','55 Felsberger Strasse','D-54317','Gutweiler','+49 (0)6821 2 41 81',
                'Gustav-Stresemannstr. - Industriegebiet','D-54329','Konz','+49 (0)6821 2 50 44','5 Nauwies','D-54317','Gusterath','+49 (0)6821 2 50 61',
                '2 Am Kapellengraben','D-54317','Kasel','+49 (0)6821 2 75 78','33 Langwies','D-54317','Gusterath','+49 (0)6821 2 77 55',
                '14 Industriestr','D-54317','Osburg','+49 (0)6821 20 00','30 Differter Strasse','D-54318','Mertesdorf','+49 (0)6821 24 03-0',
                '104 Industriestr','D-54317','Gusterath','+49 (0)6821 24 04-0','4 Industriegebiet Häsfeld - Erzkaul','D-54329','Konz','+49 (0)6821 240 240',
                '50 Hauptstr','D-54329','Konz','+49 (0)6821 4 00 0','An der B 51','D-54329','Konz','+49 (0)6821 4 03-0',
                '122 Stöckerweg','D-54329','Konz','+49 (0)6821 4 04 0','12 Fussbachstrasse','D-54329','Konz','+49 (0)6821 4 06-0',
                '7 Bachstrasse','D-54329','Konz','+49 (0)6821 4 07 0','Gustav-Stresemann-Strasse','D-54329','Konz','+49 (0)6821 4 10 31',
                '2 Saarwellingerstrasse','D-54329','Konz','+49 (0)6821 4 78 85','27 Langwies','D-54329','Konz','+49 (0)6821 40 11-0',
                '145 Schaumbergstr','D-54329','Konz','+49 (0)6821 40 13-0','99 Lebacher Strasse','D-54329','Konz','+49 (0)6821 5 25 04',
                '6 Carl-Friedrich-Gauss-Strasse','D-54329','Konz','+49 (0)6821 5 33 77','10 Alfred-Nobel-Strasse','D-54329','Konz','+49 (0)6821 55 53',
                '12 Fussbachstrasse','D-54340','Longuich','+49 (0)6821 6 40 66','8 A Nassauerstrasse','D-54340','Longuich','+49 (0)6821 6 46 18',
                '122 Stöckerweg','D-54338','Schweich','+49 (0)6821 6 94 53','50 Hauptstr','D-54340','Klüsserath','+49 (0)6821 6 99 93',
                '101 Bahnhofstr','D-54340','Longuich','+49 (0)6821 60 21','Gustav-Stresemann-Strasse','D-54338','Schweich','+49 (0)6821 63 41 31',
                '136 Provinzialstr','D-54338','Schweich','+49 (0)6821 63 48 00','4-6 Eisenbahnstr','D-54340','Detzem','+49 (0)6821 69 23 39',
                '16 Alfred-Nobel-Strasse','D-54340','Köwerich','+49 (0)6821 7 13 07','99 Lebacher Strasse','D-54340','Bekond','+49 (0)6821 7 14 83',
                'Gewerbegebiet John','D-54329','Konz','+49 (0)6821 7 28 84','26 Schwalbacher Strasse','D-54329','Konz','+49 (0)6821 7 88 86',
                '119 Bahnhofstr','D-54329','Konz','+49 (0)6821 7 92-0','9 Alfred-Nobel-Strasse','D-54340','Longuich','+49 (0)6821 7 95-0',
                '145 Schaumbergstr','D-54340','Ensch','+49 (0)6821 72 38 9','18 Hasbornerstr','D-54340','Bekond','+49 (0)6821 73 08 93',
                '6 Carl-Friedrich-Gauss-Strasse','D-54340','Longuich','+49 (0)6821 74 02 53','2 Werner-von-Siemens-Str','D-54340','Riol','+49 (0)6821 74 02 80',
                '17 A Falscheider Strasse','D-54340','Detzem','+49 (0)6821 74 08 66','26 Schwalbacher Strasse','D-54340','Bekond','+49 (0)6821 74 94 10',
                '10 Alfred-Nobel-Strasse','D-54340','Longuich','+49 (0)6821 79 30','300 Provinzialsrt','D-54329','Konz','+49 (0)6821 8 60 40',
                '19 Bonifatiusstrasse','D-54329','Konz','+49 (0)6821 8 66-0','16 Alfred-Nobel-Strasse','D-54329','Konz','+49 (0)6821 8 69 20 ',
                '2 Siercker Weg','D-54317','Farschweiler','+49 (0)6821 8 70 80','Walter von Rathenau Strasse','D-54329','Konz','+49 (0)6821 8 84 03',
                '26 Hospitalstr','D-54317','Osburg','+49 (0)6821 8 89 96','28 Differter Strasse','D-54329','Konz','+49 (0)6821 8 89 96',
                '8 Marsiliusstrasse','D-54329','Konz','+49 (0)6821 8 90 81','4 - 6 Walter von Rathenau Strasse','D-54329','Konz','+49 (0)6821 80 01',
                '3 Comotorstrasse','D-54329','Konz','+49 (0)6821 80 47','94 Industriestr','D-54317','Osburg','+49 (0)6821 80 84',
                '62 Hauptstr','D-54317','Riveris','+49 (0)6821 86 08-0','94 Saarlouiser Strasse','D-54317','Osburg','+49 (0)6821 86 99 12',
                '110 Provinzialstr','D-54329','Konz','+49 (0)6821 9 04 0','4 Comotorstrasse','D-54317','Osburg','+49 (0)6821 9 07 10',
                '17 Comotorstrasse','D-54317','Morscheid','+49 (0)6821 9 20 09','12 Comotorstrasse','D-54317','Gusterath','+49 (0)6821 9 20 82-',
                '8 A Nassauerstrasse','D-54329','Konz','+49 (0)6821 9 40 20','136 Provinzialstr','D-54329','Konz','+49 (0)6821 9 41 10',
                '17 A Falscheider Strasse','D-54329','Konz','+49 (0)6821 9 51 61-','2 Saarwellingerstrasse','D-54340','Longuich','+49 (0)6821 9 60 50',
                '12 Amselweg','D-54340','Longuich','+49 (0)6821 9 64 85-','7 Bachstrasse','D-54340','Longuich','+49 (0)6821 9 64 87-',
                'An der B 51','D-54338','Schweich','+49 (0)6821 9 64 93 ','12 Im Sand','D-54318','Mertesdorf','+49 (0)6821 9 82 81 ',
                '94 Industriestr','D-54317','Gusterath','+49 (0)6821 9 82 88-','1 Hauptstr','D-54329','Konz','+49 (0)6821 90 60-0',
                '252 Provinzialstr','D-54329','Konz','+49 (0)6821 90 62 0','2 Industriestr','D-54320','Waldrach','+49 (0)6821 90 62-0',
                '2 Kunzelfelderhuf - Industriegebiet','D-54318','Mertesdorf','+49 (0)6821 90 67-0','12 Hauptstr','D-54317','Gusterath','+49 (0)6821 908 0',
                '54 Schlossbergstr. - Alte Schule','D-54317','Kasel','+49 (0)6821 91 45 64','','D-54317','Lorscheid','+49 (0)6821 91 99 28',
                '5 Kurt-Schumacher-Str','D-54329','Konz','+49 (0)6821 92 24 0','101 Bahnhofstr','D-54329','Konz','+49 (0)6821 94 07-0',
                '12 Amselweg','D-54329','Konz','+49 (0)6821 94 13-0','4-6 Eisenbahnstr','D-54329','Konz','+49 (0)6821 94 14-0',
                '31 Werner-von-Siemens-Str','D-54329','Konz','+49 (0)6821 95 21 21','2 Werner-von-Siemens-Str','D-54329','Konz','+49 (0)6821 97 06 0',
                '8 Lucie-Bolte-Strasse','D-54329','Konz','+49 (0)6821 97 09 0','5 Brunnenstrasse','D-54340','Longuich','+49 (0)6821 97 18-0',
                '44 Saarwellinger Strasse','D-54340','Riol','+49 (0)6821 97 33-06','6-9 Clasenweg','D-54329','Konz','+49 (0)6821 98 18-0',
                '38 Alleestr','D-54320','Waldrach','+49 (0)6821 98 28 20','94 Industriestr','D-54338','Schweich','+49 (0)6824 13 42',
                '80 Josefstr','D-54340','Longuich','+49 (0)6824 20 95','30 Differter Strasse','D-54338','Schweich','+49 (0)6824 24 04',
                '33 Langwies','D-54338','Schweich','+49 (0)6824 26 91','12 Im Sand','D-54338','Schweich','+49 (0)6824 3 08-0',
                '38 Alleestr','D-54338','Schweich','+49 (0)6824 30 30','12 Comotorstrasse','D-54338','Schweich','+49 (0)6824 37 34',
                'Linslerhof','D-54338','Schweich','+49 (0)6824 38 68','94 Industriestr','D-54338','Schweich','+49 (0)6824 44 39',
                '44 Bonifatiusstrasse','D-54338','Schweich','+49 (0)6824 70 14 68','19 Bonifatiusstrasse','D-54338','Schweich','+49 (0)6824 70 97 03',
                'Industriegelände','D-54338','Schweich','+49 (0)6824 74 83','2 Kunzelfelderhuf - Industriegebiet','D-54338','Schweich','+49 (0)6824 90 01-0',
                '23 Comotorstrasse','D-54338','Schweich','+49 (0)6824 93 10-0','Gewerbegebiet John','D-54329','Konz','+49 (0)6825 17 96',
                '25 Hospitalstr','D-54332','Wasserliesch','+49 (0)6825 20 37','24 Matzenberg','D-54329','Konz','+49 (0)6825 21 43',
                'Gewerbegebiet Langwies','D-54329','Konz','+49 (0)6825 27 59','37 Werner-von-Siemens-Str','D-54329','Konz','+49 (0)6825 27 63',
                'Gewerbegebiet John','D-54340','Longuich','+49 (0)6825 27 93','Felsberger Strasse','D-54331','Oberbillig ','+49 (0)6825 31 98',
                '12 Hauptstr','D-54332','Wasserliesch','+49 (0)6825 33 88','6 A Philipp-Reis-Strasse','D-54329','Konz','+49 (0)6825 37 00',
                '5 Nauwies','D-54338','Schweich','+49 (0)6825 37 27','2 Am Kapellengraben','D-54338','Schweich','+49 (0)6825 4 08 0',
                '99 Zum Rotwäldchen','D-54329','Konz','+49 (0)6825 4 12 93','6 Aemilianusstrasse','D-54332','Wasserliesch','+49 (0)6825 4 20 11'
            ];
        }
        $r = mt_rand(0, floor(count($a)/4)-1)*4;
        return array($a[$r], $a[$r+1], $a[$r+2], $a[$r+3]);
    }
    
    static function getAddressF() {
        
        static $a;
        if (!isset($a)) {
            $a = [ 
                'Rue du Grand Moulin','F-55600','Chauvency-Saint-Hubert','+0033 63 79 71 17 2','Zone Industrielle','F-55300','Chauvoncourt','+32 (0)479 214 443',
                '36 Rue Jean Mermoz','F-54500','Vandoeuvre-lès-Nancy','+33 (0)3 29 70 21 09','19 Rue Edmond Goudchaux','F-57000','Metz','+33 (0)3 29 70 54 78',
                'rue Des Eurantes','F-55230','Arrancy-sur-Crusnes','+33 (0)3 29 75 49 37','71 Avenue Lafayette','F-54800','Jarny','+33 (0)3 29 75 60 00',
                '3 Allée des Paquis','F-54180','Houdemont','+33 (0)3 29 75 63 65','31 Rue Ampère - Zone Industrielle Est','F-54710','Ludres','+33 (0)3 29 76 61 00',
                '10 Boulevard Barthou','F-54500','Vandoeuvre-lès-Nancy','+33 (0)3 29 76 76 76','7 Rue Mangin','F-57000','Metz','+33 (0)3 29 76 85 00',
                '7 Rue Jacquard','F-54500','Vandoeuvre-lès-Nancy','+33 (0)3 29 78 01 61','73 Rue Roger Salengro','F-54230','Neuves-Maisons','+33 (0)3 29 78 05 44',
                '12 Avenue de la Gare','F-55500','Nançois-sur-Ornain','+33 (0)3 29 78 36 73','615 Rue du Jardin Botanique','F-54600','Villers-lès-Nancy','+33 (0)3 29 78 40 10',
                '20 Rue Isabey','F-54000','Nancy','+33 (0)3 29 78 41 61','4 rue De Savonnières','F-55170','Juvigny-en-Perthois','+33 (0)3 29 78 42 32',
                'route De Varennes','F-55100','Charny-sur-Meuse','+33 (0)3 29 78 81 43','Avenue Général de Gaulle','F-54140','Jarville-la-Malgrange','+33 (0)3 29 78 84 11',
                'Zone Industrielle de Rhovyl','F-55310','Tronville-en-Barrois','+33 (0)3 29 78 84 19','Rue Jean Jaurès Z.I du Nord-Est','F-54640','Tucquegnieux','+33 (0)3 29 78 84 33',
                '523 Avenue André Malraux','F-54600','Villers-lès-Nancy','+33 (0)3 29 78 88 88','2 Rue Lavoisier - Zone Industrielle','F-54300','Moncel-lès-Lunéville','+33 (0)3 29 79 55 55',
                '4 Rue Moulin de Boudonville','F-54000','Nancy','+33 (0)3 29 80 13 32','29 Rue de Château-Salins','F-54000','Nancy','+33 (0)3 29 80 30 09',
                '3 Rue Marcel Brot','F-54000','Nancy','+33 (0)3 29 80 30 10','35 Rue des Cheminots','F-55840','Thierville-sur-Meuse','+33 (0)3 29 80 30 26',
                'Chemin de Montrichard','F-54700','Pont-à-Mousson','+33 (0)3 29 80 40 95','Zone Industrielle','F-54150','Briey','+33 (0)3 29 80 48 02',
                '45 Rue des Ponts','F-54000','Nancy','+33 (0)3 29 83 22 50','8 Rue de Versigny','F-54600','Villers-lès-Nancy','+33 (0)3 29 83 23 23',
                '3 Allée de la Forêt de la Reine','F-54500','Vandoeuvre-lès-Nancy','+33 (0)3 29 83 44 55','13 Rue Sainte-Libaire','F-54330','Hammeville','+33 (0)3 29 83 45 90',
                '5 A Rue de Lorraine','F-54400','Cosnes-et-Romain','+33 (0)3 29 84 17 09','Route Nationale 4','F-54520','Laxou','+33 (0)3 29 84 18 60',
                '4 Rue des Magnolias','F-54220','Malzéville','+33 (0)3 29 84 23 16','Haute Saule','F-55210','Vigneulles-lès-Hattonchâtel','+33 (0)3 29 84 24 51',
                '14 Rue Haute-Seille','F-57000','Metz','+33 (0)3 29 84 34 00','28 Avenue du 69ème R.I','F-54270','Essey-lès-Nancy','+33 (0)3 29 84 76 76',
                '6 Rue Saint-Dizier','F-54000','Nancy','+33 (0)3 29 86 54 51','16-24 Rue Marcel Brot','F-54000','Nancy','+33 (0)3 29 86 62 70',
                'Route de Mussey - Zone d\'Activité Varney','F-55000','Val-d\'Ornain','+33 (0)3 29 87 06 15','Zone d\'Activité Ardant du Picq','F-54260','Longuyon','+33 (0)3 29 87 13 01',
                '13 Rue Héré Pl. Stanislas','F-54000','Nancy','+33 (0)3 29 87 20 57','99 Rue du 155ème R.I','F-55200','Commercy','+33 (0)3 29 87 22 71',
                '110 Rue Bertholet','F-54710','Ludres','+33 (0)3 29 87 86 85','9 Route de Verdun','F-55700','Stenay','+33 (0)3 29 87 88 22',
                '2 Ter Boulevard des Essarts - Centre G.-','F-54600','Villers-lès-Nancy','+33 (0)3 29 87 89 01','22 Avenue de Paris','F-55100','Verdun','+33 (0)3 29 88 10 55',
                '4 Rue Alfred Mézières','F-54400','Longwy','+33 (0)3 29 88 10 62','75 Avenue de la Gare','F-54350','Mont-Saint-Martin','+33 (0)3 29 88 13 13',
                '649 Rue Pierre et Marie Curie','F-54710','Ludres','+33 (0)3 29 88 33 47','rue Gambetta','F-54190','Villerupt','+33 (0)3 29 88 33 60',
                '29 Rue Albert Einstein','F-54320','Maxéville','+33 (0)3 29 90 23 20','26 Route de Frouard','F-54250','Champigneulles','+33 (0)3 87 17 17 17',
                '22 Rue de Lorraine','F-54720','Lexy','+33 (0)3 87 18 03 03','136 Boulevard de Finlande','F-54340','Pompey','+33 (0)3 87 18 42 69',
                '48 Rue Saint-Jean','F-54000','Nancy','+33 (0)3 87 18 85 54','Carreau de la Mine d\'Amermont','F-55240','Bouligny','+33 (0)3 87 20 03 03',
                '1 rue De l\'Usine Z.I.','F-54650','Saulnes','+33 (0)3 87 21 12 21','1 Rue Grandville','F-54000','Nancy','+33 (0)3 87 21 35 25',
                '12 Rue des Carmes','F-54000','Nancy','+33 (0)3 87 21 36 47','Rue Henri Moissan','F-54710','Ludres','+33 (0)3 87 30 34 14',
                '10 Rue du Saulnois','F-54520','Laxou','+33 (0)3 87 31 56 38','63 Rue Raymond Poincaré','F-54000','Nancy','+33 (0)3 87 32 12 18',
                '18 rue Du Fort des Romains','F-54700','Blénod-lès-Pont-à-Mousson','+33 (0)3 87 32 43 12','Les Saussis Lambert','F-54700','Atton','+33 (0)3 87 32 53 26',
                '4 Rue Piroux - immeuble Thiers','F-54000','Nancy','+33 (0)3 87 34 44 44','Route d\'Hussigny - Zone Industrielle','F-54920','Villers-la-Montagne','+33 (0)3 87 36 10 91',
                '20 Rue du Jury','F-54470','Flirey','+33 (0)3 87 36 16 40','Centre Jean Monnet - Longlaville','F-54400','Longwy','+33 (0)3 87 36 16 52',
                '11 Place Stanislas','F-54000','Nancy','+33 (0)3 87 36 16 64','1 Allée de Longchamp','F-54600','Villers-lès-Nancy','+33 (0)3 87 36 17 33',
                '15 Rue Maurice Barrès','F-54000','Nancy','+33 (0)3 87 36 18 88','3 Quai Félix Maréchal','F-57000','Metz','+33 (0)3 87 36 19 71',
                '96 Impasse Pierre et Marie Curie - Zone ','F-54710','Ludres','+33 (0)3 87 36 35 31','Pré à Varois','F-54670','Custines','+33 (0)3 87 36 40 14',
                '6 Allée Pelletier Doisy - Parc tech. de ','F-54603','Villers-lès-Nancy Cedex','+33 (0)3 87 36 45 19','3 Chemin Des Hauts Sablons','F-54280','Laneuvelotte','+33 (0)3 87 36 48 71',
                'Chemin de Popey - Zone Industrielle','F-55000','Bar-le-Duc','+33 (0)3 87 36 50 99','28 Rue Miss Sibley','F-55100','Verdun','+33 (0)3 87 36 57 91',
                '20 Rue du Pilan','F-54700','Montauville','+33 (0)3 87 36 71 21','14 Rue Jeanne d\'Arc','F-54310','Homécourt','+33 (0)3 87 36 81 26',
                '13-15 Avenue de la Garenne','F-54000','Nancy','+33 (0)3 87 36 93 67','7 Rue des Maillys - Zone d\'Activité & Co','F-54270','Essey-lès-Nancy','+33 (0)3 87 37 03 87',
                '17 bis Rue de Girardet','F-54300','Lunéville','+33 (0)3 87 37 03 87','Palais Episcopal','F-55100','Verdun','+33 (0)3 87 37 06 44',
                '52 bis Route de Metz','F-54320','Maxéville','+33 (0)3 87 37 70 65','664 Route de Toul','F-54200','Chaudeney-sur-Moselle','+33 (0)3 87 37 90 90',
                '45 Rue Henri Poincaré','F-54000','Nancy','+33 (0)3 87 38 03 71','218 Avenue Champagne','F-54700','Pont-à-Mousson','+33 (0)3 87 38 05 54',
                '12-16 Rue de la Douane','F-54000','Nancy','+33 (0)3 87 38 60 60','43B Rue du Dauphiné','F-54400','Cosnes-et-Romain','+33 (0)3 87 38 96 96',
                'Boulevard de Finlande - Zone Industriell','F-54340','Pompey','+33 (0)3 87 39 55 19','13 Rue Clémenceau','F-54660','Moutiers','+33 (0)3 87 39 70 05',
                '590 Rue Franclos','F-54710','Ludres','+33 (0)3 87 50 30 00','Zone d\'Activité & Commerciale de la Croi','F-54210','Saint-Nicolas-de-Port','+33 (0)3 87 50 33 08',
                '1 Avenue Camille Cavallier','F-54700','Pont-à-Mousson','+33 (0)3 87 50 37 42','2 Avenue de la Grande-Duchesse Charlotte','F-54400','Longwy','+33 (0)3 87 50 81 58',
                '15 Route d\'Hussigny - Zone Industrielle','F-54920','Villers-la-Montagne','+33 (0)3 87 50 86 01','Route de Saint-Nicolas-de-Port - Zone d\'','F-54210','Ville-en-Vermois','+33 (0)3 87 52 28 14',
                '2 route De Custines','F-54670','Millery','+33 (0)3 87 52 31 32','Rue des Tanneries','F-55140','Vaucouleurs','+33 (0)3 87 521 212',
                'Avenue Raymond Pinchard','F-54000','Nancy','+33 (0)3 87 55 53 76','Route de Giraumont','F-54800','Jarny','+33 (0)3 87 55 94 95',
                '39 Rue Albert Einstein - Parc Saint Jacq','F-54320','Maxéville','+33 (0)3 87 55 98 99','8 bis Rue Jules Renard','F-54190','Tiercelet','+33 (0)3 87 56 02 02',
                '16 rue De la Tour Blanche','F-54300','Lunéville','+33 (0)3 87 56 17 58','133 Rue du Général de Gaulle','F-55500','Ligny-en-Barrois','+33 (0)3 87 56 99 19',
                'Grande Rue - Lieu-Dit Chaufontaine','F-54300','Hériménil','+33 (0)3 87 61 89 70','18/32 Rue de Metz','F-54800','Jarny','+33 (0)3 87 62 06 00',
                '31 Avenue de Saintignon ','F-54400','Longwy ','+33 (0)3 87 62 12 89','12 Rue Raymond Poincaré','F-55430','Belleville-sur-Meuse','+33 (0)3 87 62 15 24',
                '8 Avenue du Luxembourg','F-54810','Longlaville','+33 (0)3 87 62 93 60','1 Rue Charles de Gaulle','F-54425','Pulnoy','+33 (0)3 87 63 07 24',
                '1 Place Joliot Curie','F-54190','Villerupt','+33 (0)3 87 63 10 21','Zone Industrielle de Popey','F-55000','Bar-le-Duc','+33 (0)3 87 63 14 15',
                '6 Rue Lucie Aubrac','F-54880','Thil','+33 (0)3 87 63 17 10','Zone Industrielle des Poutôts','F-55000','Savonnières-devant-Bar','+33 (0)3 87 63 41 38',
                '28 Avenue des Erables','F-54180','Heillecourt','+33 (0)3 87 65 00 00','2 route De Custines','F-54670','Millery','+33 (0)3 87 65 04 40',
                '50 Rue de la Commanderie','F-54000','Nancy','+33 (0)3 87 65 35 29','Abbaye des Prémontrés 9,r. St Martin','F-54700','Pont-à-Mousson','+33 (0)3 87 65 58 09',
                'Lieu-Dit Aux Fours à Chaux','F-55100','Belrupt-en-Verdunois','+33 (0)3 87 65 65 66','34 Rue de Remenauville','F-54000','Nancy','+33 (0)3 87 65 99 02',
                '2 Route de Bayon','F-54410','Laneuveville-Devant-Nancy','+33 (0)3 87 66 16 76','19 Rue Blaise Pascal - Parc Saint-Jacque','F-54320','Maxéville ','+33 (0)3 87 66 27 44',
                '54 Rue d\'Embanie','F-54220','Malzéville','+33 (0)3 87 66 35 60','6 Avenue du Général de Gaulle - Zone d\'A','F-54320','Maxéville','+33 (0)3 87 66 57 26',
                'Rue Saint-Exupéry','F-55100','Verdun','+33 (0)3 87 66 72 86','Zone d\'Activité & Commerciale du Chanois','F-54280','Seichamps','+33 (0)3 87 66 86 37',
                '19 Avenue de la Meurthe - Zone Industrie','F-54320','Maxéville','+33 (0)3 87 66 95 97','2 Rue de la Taille','F-55140','Rigny-la-Salle','+33 (0)3 87 66 98 21',
                'Corvée Moutarde','F-54210','Ville-en-Vermois','+33 (0)3 87 68 37 74','route Nationale','F-54620','Pierrepont','+33 (0)3 87 74 01 14',
                '3 Rue des Emaux','F-54400','Longwy','+33 (0)3 87 74 11 04','Maison de la Formation - Centre Jean Mon','F-54400','Longwy','+33 (0)3 87 74 11 76',
                '96 Grand-Rue','F-54180','Heillecourt','+33 (0)3 87 74 39 79','98 rue De la Grande Corvée','F-54600','Villers-lès-Nancy','+33 (0)3 87 74 42 54',
                '17 En Chaplerue','F-57000','Metz','+33 (0)3 87 74 66 60','28 Avenue des Erables','F-54180','Heillecourt','+33 (0)3 87 75 02 58',
                '34 bis Rue de la Division Leclerc','F-54120','Baccarat','+33 (0)3 87 75 10 69','25 Route de Bosserville','F-54420','Saulxures-lès-Nancy','+33 (0)3 87 75 15 25',
                '450 Rue du Champ Moyen - Zone Industriel','F-54710','Fléville-devant-Nancy','+33 (0)3 87 75 20 20','8 Parc Bradfer','F-55000','Bar-le-Duc','+33 (0)3 87 75 26 40',
                '153 Avenue Général Leclerc','F-54220','Malzéville','+33 (0)3 87 75 39 35','5 bis Rue André Fruchard - Zone Industri','F-54320','Maxéville','+33 (0)3 87 75 40 10',
                '27 Rue Saint-Pierre','F-55100','Verdun','+33 (0)3 87 75 64 72','50 rue De la Chapelle','F-57000','Metz','+33 (0)3 87 75 92 60',
                'Route d\'Hussigny - Zone Industrielle','F-54920','Villers-la-Montagne','+33 (0)3 87 76 03 33','649 Rue Pierre et Marie Curie','F-54710','Ludres','+33 (0)3 87 76 13 30',
                '772 Rue Lavoisier','F-54710','Ludres','+33 (0)3 87 76 41 41','82 Quai Claude-le-Lorrain','F-54000','Nancy','+33 (0)3 87 76 87 51'
            ];
        }
        $r = mt_rand(0, floor(count($a)/4)-1)*4;
        return array($a[$r], $a[$r+1], $a[$r+2], $a[$r+3]);
    }
 
    static function getAddressB() {
        
        static $a;
        if (!isset($a)) {
            $a = [ 
                '103 rue Des Vieux Prés','B-6860','Léglise','+32 (0)6 34 33 96 1','50 Rue du Pré au Bois','B-6860','Léglise','+32 (0)477 458 477',
                '113 Rue Saint Martin','B-6860','Ebly','+32 (0)61 25 60 10','6 rue Pré Saint-Michel','B-6860','Assenois','+32 (0)63 43 33 64',
                '53 rue Viatour','B-6860','Vlessart','+32 (0)498 16 45 04','60 rue De Luxembourg','B-6860','Léglise','+32 (0)63 67 78 63',
                '38 A Sentier de Vaux','B-6860','Ebly','+32 (0)61 25 60 71','1 A Rue Saint-Martin','B-6860','Léglise','+32 (0)63 42 29 66',
                '13 rue Des Eglantines','B-6860','Behême','+32 (0)474 80 85 94','34 rue Des Tilleuls','B-6860','Léglise','+32 (0)477 43 17 42',
                '12 rue Du Moustier','B-6860','Léglise','+32 (0)478 26 61 76','166 rue Du Boquillon','B-6860','Mellier','+32 (0)494 78 93 05',
                '36 rue Du Manchot','B-6860','Mellier','+32 (0)495 26 90 47','40 Rue de la Chapelle Behême','B-6860','Léglise','+32 (0)63 42 21 88',
                '162 Rue de Boquillon','B-6860','Léglise','+32 (0)495 25 02 44','6 Rue Dufet','B-6860','Bernimont','+32 (0)63 43 39 15',
                '53 rue Des Combattants','B-6860','Assenois','+32 (0)478 71 53 45','68 rue De la Tannerie','B-6860','Léglise','+32 (0)63 23 76 22',
                'Aérodrome de et à Saint Hubert','B-6870','Saint-Hubert','+32 (0)61 23 95 11','44 Rue de la Converserie','B-6870','Saint-Hubert','+32 (0)61 29 30 70',
                '1 Rue Moulin d\'en haut','B-6870','Saint-Hubert','+32 (0) 495 69 07 64','1 Avenue Des Chasseurs Ardennais','B-6870','Saint-Hubert','+32 (0)61 26 75 61',
                '3 Parc Industriel','B-6870','Saint-Hubert','+32 (0)61 61 23 02','20 Rue Saint-Gilles','B-6870','Saint-Hubert','+32 (0)61 61 17 51',
                '12 Rue Saint-Gilles','B-6870','Saint-Hubert','+32 (0)61 61 30 10','17 Place du Marché','B-6870','Saint-Hubert','+32 (0)61 61 37 08',
                '7 rue Haye Pierson','B-6870','Saint-Hubert','+32 (0)497 99 81 59','58 Clos Des Sorbiers','B-6870','Saint-Hubert','+32 (0)61 61 16 77',
                '17 Rue de la Converserie','B-6870','Saint-Hubert','+32 (0)61 61 16 55','11 Rue Joseph Calozet','B-6870','Awenne','+32 (0)84 36 02 00',
                '7 Pont de Libin','B-6870','Hatrival','+32 (0)61 61 13 23','12 Rue Mayavaux','B-6870','Vesqueville','+32 (0)61 61 11 29',
                '46 Avenue Nestor Martin','B-6870','Saint-Hubert','+32 (0)61 61 11 07','7 Rue de la Rochette','B-6870','Arville','+32 (0)61 61 13 27',
                '11 Rue Joseph Calozet','B-6870','Awenne','+32 (0)84 36 62 03','1 Clos Des Sorbiers','B-6870','Saint-Hubert','+32 (0)61 23 11 11',
                '125 Rue des Rogations','B-6870','Saint-Hubert','+32 495 33 60 55','61 Rue des Corettes','B-6880','Bertrix','+32 (0)61 41 44 89',
                '31 rue De Renaumont','B-6880','Bertrix','+32 (0)61 41 11 24','118 Rue de la Gare','B-6880','Bertrix','+32 (0)61 61 25 36',
                '6 Rue d\'Outrouge','B-6880','Bertrix','+32 (0)61 23 36 86','27 Rue de Nouvely','B-6880','Bertrix','+32 (0)61 53 32 77',
                '94 La Géripont','B-6880','Bertrix','+32 (0)61 41 37 82','1 B Rue du Babinay','B-6880','Bertrix','+32 (0)61 41 57 86',
                '3 Rue de la Fêche','B-6880','Bertrix','+32 61 28 78 52','48 Rue de la Fêche','B-6880','Bertrix','+32 (0)477 241 979',
                '1 Route de Lonnoux','B-6880','Bertrix','+32 (0)497 471 942','49 Rue du Centre','B-6880','Bertrix','+32 (0)61 414381',
                '39 Rue du Bois Bollé','B-6880','Bertrix','+32 (0)493 07 83 77','48A Rue de la Gare','B-6880','Bertrix','+32 (0)61 655 002',
                '1 rue De la Victoire','B-6880','Bertrix','+32 (0)61 41 40 00','78 Rue des Corettes','B-6880','Bertrix','+32 (0)61 41 10 73',
                '194 Rue de la Gare','B-6880','Bertrix','+32 (0)473 78 69 45','47 Rue des Corettes','B-6880','Bertrix','+32 (0)61 41 03 10',
                '210 Rue de la Fêche','B-6880','Bertrix','+32 (0)494 99 13 61','2 Rue du Babinay','B-6880','Bertrix','+32 (0)61 41 65 41',
                '4 B Route des Gohineaux','B-6880','Bertrix','+32 (0)61 28 77 62','18 Rue de la Gare','B-6880','Bertrix','+32 (0)61 22 21 94',
                '1 rue De la Virée','B-6880','Bertrix','+32 (0)61 41 15 04','77 Rue Champs Morais','B-6880','Bertrix','+32 (0)498 46 91 38',
                '8 rue Du Bois Bollé','B-6880','Jehonville','+32 (0)61 53 53 67','133-135 Rue de la Gare','B-6880','Bertrix','+32 (0)61 41 12 74',
                '137 B Rue de la Gare','B-6880','Bertrix','+32 (0)61 41 25 19','73 rue De Saupont','B-6880','Bertrix','+32 (0)495 36 46 21',
                '107 rue De Saupont','B-6880','Bertrix','+32 (0)61 41 63 50','2 rue De Finay','B-6880','Jehonville','+32 (0)61 53 46 83',
                '140 Rue de Burhaimont','B-6880','Bertrix','+32 (0)61 41 34 45','36 Rue des Corettes','B-6880','Bertrix','+32 (0)61 41 11 90',
                'Les Corettes','B-6880','Bertrix','+32 (0)61 58 08 00','132 A Rue de la Gare','B-6880','Bertrix','+32 (0)61 41 17 05',
                '30 Rue des Ardoisières','B-6880','Bertrix','+32 (0)61 41 23 63','39 Rue Blezy','B-6880','Orgéo','+32 (0)61 41 16 96',
                '1 Rue des Ardoisières','B-6880','Bertrix','+32 (0)61 41 12 14','76 Rue L. Nouvely','B-6880','Jehonville','+32 (0)61 53 31 86',
                '36 Rue des Alouettes','B-6880','Bertrix','+32 (0)61 41 26 23','34 Rue des Ruelles','B-6880','Auby-Sur-Semois','+32 (0)61 41 37 17',
                'Zone Industrielle','B-6880','Bertrix','+32 (0)61 41 19 88','34 Rue du Grand Enclos','B-6880','Nevraumont','+32 61 41 16 57',
                '7 A Rue du Riage','B-6880','Auby-Sur-Semois','+32 (0)61 41 41 46','82 Rue de la Bawette','B-6880','Bertrix','+32 (0)61 41 26 50',
                '184 Rue de la Gare','B-6880','Bertrix','+32 (0)61 41 51 71','1 Route du Nouveau Ban','B-6880','Bertrix','+32 (0)496 26 44 30',
                '1 Rue des Prés la Mercire - Zone Industr','B-6880','Bertrix','+32 (0)61 27 59 10','162 Rue de Burhaimont','B-6880','Bertrix','+32 (0)61 41 12 82',
                '68 Rue de Nouvely','B-6880','Jehonville','+32 (0)61 29 29 39','10 Rue de la Gare','B-6880','Bertrix','+32 (0)61 41 53 88',
                'Les Fourches','B-6887','Herbeumont','+32 (0)61 41 00 30','16 Rue des Ponts','B-6887','Herbeumont','+32 (0)61 29 29 97',
                '4 Place de Gribomont','B-6887','Saint-Médard','+32 (0)61 41 53 50','2 A Menugoutte','B-6887','Straimont','+0032 61 31 52 16',
                '12 rue De la Hulette','B-6887','Herbeumont','+32 (0)61 28 79 60','5 Aux Roches','B-6887','Straimont','+32 (0)61 27 89 59',
                '8-9 Grand\'Place','B-6887','Herbeumont','+32 (0)61 41 14 22','129 rue De Glaireuse','B-6890','Libin','+32 (0)61 65 59 00',
                '44 rue De Recogne','B-6890','Libin','+32 (0)496 86 97 19','12 Zone Le Cerisier','B-6890','Transinne','+32 (0)61 65 63 76',
                '9 Zone Industrielle Le Cerisier','B-6890','Transinne','+32 (0)61 65 65 60','Château de Roumont','B-6890','Ochamps','+32 (0)61 22 26 08',
                '40 Rue de Bertrix','B-6890','Ochamps','+32 (0)61 32 00 73','62 Rue de Chenois','B-6890','Ochamps','+32 (0)495 240 672',
                '127 rue Du Général Molitor','B-6890','Villance','+32 (0)496 52 04 20','15 rue Du Grand Vivier','B-6890','Ochamps','+32 (0)61 23 35 31',
                '49 rue De la Rochette','B-6890','Anloy','+32 (0)61 51 38 30','58 Place de l\'Esro','B-6890','Redu','+32 (0)61 65 56 32',
                '16 A rue Du Curé','B-6890','Libin','+32 (0)61 65 61 88','21 rue De Roumont','B-6890','Villance','+32 (0)61 65 51 39',
                '58 rue De la Colline','B-6890','Transinne','+32 (0)61 65 68 48','62 rue Du Chenois','B-6890','Libin','+32 (0)61 22 45 43',
                '119 rue Fond des Vaux','B-6890','Libin','+32 (0)49 94 66 1','71 Rue Paul Dubois','B-6890','Libin','+32 (0)61 65 54 83',
                '24 B Gare d\'Arloy','B-6890','Villance','+32 (0)61 65 50 24','10 Zone Industrielle Le Cerisier','B-6890','Transinne','+32 (0)61 65 50 10',
                '1 Rue Devant les Hêtres','B-6890','Transinne','+32 (0)61 65 01 35','9 Zone Industrielle Le Cerisier','B-6890','Transinne','+32 (0)61 65 65 60',
                '20 Rue du Commerce','B-6900','Marche-en-Famenne','+32 (0)84 31 43 50','1 Vieille Route de Marloie','B-6900','Marche-en-Famenne','+32 (0)84 31 17 56',
                '38 Avenue De la Toison d\'Or','B-6900','Marche-en-Famenne','+32 (0)84 31 56 26','3 Aux Minières','B-6900','Marloie','+32 (0)84 32 10 51',
                '3A Vielle Route de Liège','B-6900','Marche-en-Famenne','+32 (0)84 32 71 71','25 Rue du Parc Industriel','B-6900','Marche-en-Famenne','+32 (0)84 31 39 04',
                '22 rue Devant le Bois','B-6900','Marche-en-Famenne','+32 (0)84 31 57 87','rue Du Château','B-6900','Waha','+32 (0)84 31 66 28',
                '6 Rue Jean de Bohème','B-6900','Marche-en-Famenne','+32 (0)84 32 25 73','6 rue Du Bondeau Waha','B-6900','Marche-en-Famenne','+32 (0)496 32 34 15',
                '29 Rue Porte-Basse','B-6900','Marche-en-Famenne','+32 (0)84 34 51 88','9 Place Aux Foires','B-6900','Marche-en-Famenne','+32 (0)84 31 40 60',
                '29 Chaussée de Rochefort','B-6900','Marloie','+32 (0)84 31 05 57','18 B route De Bastogne','B-6900','Aye','+32 (0)84 31 10 65',
                '2 Rue du Château','B-6900','Marche-en-Famenne','+32 (0)63 37 17 51','21 Rue Porte-Basse','B-6900','Marche-en-Famenne','+32 (0)84 43 35 88',
                '6 rue Trinchevaux','B-6900','Waha','+32 (0)84 31 31 45','5 Allée du Monument','B-6900','Marche-en-Famenne','+32 (0)84 31 30 00',
                '25 Rue du Parc Industriel','B-6900','Marche-en-Famenne','+32 (0)84 38 00 00','Rue Fernand André','B-6900','Marche-en-Famenne','+32 (0)63 23 19 67',
                '22 Rue Porte-Basse','B-6900','Marche-en-Famenne','+32 (0)84 31 40 73','160 C Chaussée de Liège','B-6900','Marche-en-Famenne','+32 (0)84 32 33 86',
                '50 A Avenue De la Toison d\'Or','B-6900','Marche-en-Famenne','+32 (0)496 24 55 38','10 Rue Porte-Haute','B-6900','Marche-en-Famenne','+32 (0)474 37 65 76',
                '11 route De Waillet','B-6900','Marche-en-Famenne','+32 (0)497 30 83 88','121 Rue Notre Dame de Grâces','B-6900','Marche-en-Famenne','+32 (0)477 257 170',
                '9A Rue des Tanneurs','B-6900','Marche-en-Famenne','+32 (0)84 22 16 96','15 Rue de France','B-6900','Marche-en-Famenne','+32 (0)84 37 95 50',
                '61 Rue Al Basse','B-6900','Lignières','+32 (0)84 34 40 04','14 Rue Dupont','B-6900','Marche-en-Famenne','+32 (0)42 22 11 99',
                '8 Boucle de la Famenne','B-6900','Marche-en-Famenne','+32 (0)49 45 32 16 3','18 route De Waillet','B-6900','Marche-en-Famenne','+32 (0)84 31 54 85',
                '27 Parc Industriel','B-6900','Marche-en-Famenne','+32 (0)84 31 36 36','9 A Rue des Tanneurs','B-6900','Marche-en-Famenne','+32 (0)4 91 36 36 33',
                '16 rue De la Plaine','B-6900','Marche-en-Famenne','+32 (0)47 32 45 19 0','18 Avenue De la Toison d\'Or','B-6900','Marche-en-Famenne','+32 (0)84 31 23 76',
                '6 Rue du Vivier','B-6900','Aye','+32 (0)84 377 301','21 Rue des Déportés','B-6900','Marche-en-Famenne','+32 (0)84 47 89 29',
                '7 C Chaussée de Liège','B-6900','Marche-en-Famenne','+32 (0)84 46 80 66','86 Chaussée de Rochefort','B-6900','Marche-en-Famenne','+32 (0)84 36 74 74'
            ];
        }
        $r = mt_rand(0, floor(count($a)/4)-1)*4;
        return array($a[$r], $a[$r+1], $a[$r+2], $a[$r+3]);
    }
        
    static function log_diff(&$model, $category, $name, &$clog) {

        $Attributes = $model->getDirtyAttributes();
        $clogl = '';
        foreach ($Attributes as $a => $v) {
            if ($v != $model->getOldAttribute($a) && $a != 'Valid') {
                $clogl .= $a . ' (' . $model->getOldAttribute($a) . '->' . $v . '),   ';
            }
        }
        array_push($clog, ($clogl == '' ? '' : '<b>') .
            $category . ' ' . ($model->getisNewRecord() ? 'New' : 'Update') . ' ' . $name .
            ($clogl == '' ? ', no change.' : ', changes ' . $clogl) .
            ($clogl == '' ? '' : '</b>'));
    }
}
