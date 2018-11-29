<?php

namespace common\helpers;

use backend\models\Contacts;
use Yii;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use yii\httpclient\Client;
use yii\imagine\Image;
use Imagine\Image\Box;
use backend\models\Mandants;
use common\helpers\Language as Lx;

class Impex
{

    public static function ExportMandant($id)
    {

        $ilog = [];
        $schema = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();
        $ignoretables = ['Dashboard', 'article', 'auth_assignment', 'auth_item', 'auth_item_child', 'auth_rule',
            'profile', 'session', 'social_account', 'token', 'migration', 'user',
            'Rss',
            'Diagnostics', 'DiagnosticsPreco', 'ref_commodo_class', 'ref_commodo_nomen', 'ref_comp_add_country', 'ref_comp_convcoll',
            'ref_comp_nace', 'ref_pack_branche', 'ref_sst_nom'];
        $filters['Mandants.ID_Mandant'] = array($id);
        $executionplan = [];

        // *******************************************
        // setup the folder where we will save the data
        // *******************************************

        $uploadsPath = Yii::getAlias('@uploads') . DIRECTORY_SEPARATOR;
        $uploadsUrl = Yii::getAlias('@uploadsURL') . '/';

        $exportPath = $uploadsPath . $id . 'export/';
        //delete existing directory and contents
        Impex::deleteDirectory($exportPath);
        //create directory
        Helpers::createPath($exportPath);
        array_push($ilog, 'Export to ' . $exportPath);
        // remove existing zip
        @unlink($uploadsPath . 'ExportMandant' . $id . '.zip');

        $connection = Yii::$app->getDb();

        // *******************************************
        // INVENTORY OF ALL THE TABLES TO PROCESS
        // *******************************************

        $tables2go = [];
        array_push($ilog, '<b>Inventory of all the tables</b>');
        array_push($tables2go, 'Mandants');
        array_push($ilog, 'Mandants');
        $command = $connection->createCommand("
                SELECT table_name 
                FROM information_schema.tables
                WHERE TABLE_SCHEMA = '" . $schema . "' AND 
                    table_name NOT IN ('Mandants', '" . implode("','", $ignoretables) . "');");
        $result = $command->queryAll();
        foreach ($result as $row) {
            // save table name to array
            array_push($tables2go, $row['table_name']);
            array_push($ilog, $row['table_name']);
        }

        // *******************************************
        // INVENTORY OF ALL THE FOREIGN KEYS TO PROCESS
        // *******************************************

        $fk = [];
        array_push($ilog, '<b>Inventory of all foreign keys</b>');
        $command = $connection->createCommand("
                SELECT DISTINCT
                    concat(referenced_table_name, '.', referenced_column_name) AS 'fk'
                FROM
                    information_schema.key_column_usage
                WHERE
                    TABLE_SCHEMA = '" . $schema . "' AND 
                    referenced_table_name IS NOT NULL AND 
                    referenced_table_name NOT IN ('" . implode("','", $ignoretables) . "') AND 
                    table_name NOT IN ('" . implode("','", $ignoretables) . "');");
        $result = $command->queryAll();
        foreach ($result as $row) {
            // save table name to array
            array_push($fk, $row['fk']);
            array_push($ilog, $row['fk']);
        }

        // *******************************************
        // EXPORTING ALL THE TABLES AS SOON THAT WE HAVE ALL THE FOREIGN KEYS
        // *******************************************

        array_push($ilog, '<b>Iterating all the tables</b>');
        while (count($tables2go) > 0) {
            foreach ($tables2go as $table) {
                array_push($ilog, count($tables2go) . '. testing ' . $table);
                // check if this table has foreign keys
                $references = [];
                $command = $connection->createCommand("
                        SELECT
                            column_name AS 'foreign_key',
                            referenced_table_name,
                            concat(referenced_table_name, '.', referenced_column_name) AS 'references'
                        FROM 
                            information_schema.key_column_usage
                        WHERE 
                            table_name = '" . $table . "';");
                $result = $command->queryAll();
                $instructions = [];
                $pk = '';
                $process = true;
                $where = '';
                foreach ($result as $row) {
                    if ($row['references'] == '') {
                        // primary key
                        // array_push($instructions, 'SavePK: ' . $table.'.'.$row['foreign_key']);
                        $pk = $table . '.' . $row['foreign_key'];
                        if (in_array($pk, $fk)) {
                            array_push($instructions, 'SaveREF: ' . $pk);
                        }
                        array_push($instructions, 'RemINDEX: ' . $pk);
                    } elseif (!in_array($row['referenced_table_name'], $ignoretables)) {
                        array_push($instructions, 'LoadREF: ' . $row['references'] . ' intoFK: ' . $table . '.' . $row['foreign_key']);
                        if (array_key_exists($row['references'], $filters)) {
                            array_push($ilog, 'has following references already collected: ' . $table . '.' . $row['foreign_key'] . ' -> ' . $row['references']);
                            $where .= $row['foreign_key'] . ' IN (' . implode(',', $filters[$row['references']]) . ') AND ';
                        } else {
                            // at least one reference has not yet been collected, skip
                            $process = false;
                            array_push($ilog, 'has following references not yet collected: ' . $table . '.' . $row['foreign_key'] . ' -> ' . $row['references']);
                        }
                    }
                }
                if ($process) {
                    // array_push($ilog, var_export($pk, true));
                    // array_push($ilog, var_export($filters, true));
                    if (array_key_exists($pk, $filters)) {
                        // we have a filter on the primary key (e.g. Mandants table)
                        $where .= $pk . " IN (" . implode(",", $filters[$pk]) . ") AND ";
                    }
                    $command = $connection->createCommand("
                        SELECT * 
                        FROM {$table} 
                        " . ($where != '' ? " WHERE " . substr($where, 0, -5) . ";" : ";"));
                    $result = $command->queryAll();
                    array_push($ilog, 'Dumping ' . $table . ' ' . $command->Sql);
                    $expfile = fopen($exportPath . $table . '.exp', 'w') or die("Unable to open file!");
                    if (count($instructions) != 0) {
                        fwrite($expfile, implode(PHP_EOL, $instructions) . PHP_EOL);
                    }
                    $fkv = [];
                    foreach ($result as $row) {
                        fwrite($expfile, 'Row: ' . json_encode($row) . PHP_EOL);
                        if (in_array($pk, $fk)) {
                            // collect the foreign key values
                            array_push($fkv, $row[substr($pk, strpos($pk, ".") + 1)]);
                        }
                    }
                    fclose($expfile);
                    // add the collected fk to our filter
                    if (in_array($pk, $fk)) {
                        $filters[$pk] = $fkv;
                    }
                    // removing table from list                    
                    $tables2go = array_diff($tables2go, array($table));
                    // add actions to our execution plan
                    $executionplan = array_merge($executionplan, array('Table: ' . $table), $instructions);
                } else {
                    array_push($ilog, 'skipped for now.');
                }
            }
            array_push($ilog, count($tables2go) . ' tables remain.');
        }

        // write our execution plan
        $expfile = fopen($exportPath . '_plan.exp', 'w') or die("Unable to open file!");
        foreach ($executionplan as $i) {
            fwrite($expfile, $i . PHP_EOL);
        }
        fclose($expfile);

        // zip our folders
        $rootPath = realpath($uploadsPath . $id);
        $zip = new \ZipArchive();
        $zip->open($uploadsPath . 'ExportMandant' . $id . '.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        // Create recursive directory iterator
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($rootPath), \RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();

        return $ilog;
    }

    public static function ImportMandant($id, $zipfile)
    {

        $ilog = [];
        $schema = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();
        $filters['Mandants.ID_Mandant'] = array($id);
        $executionplan = [];

        // *******************************************
        // setup the folder where we will save the data
        // *******************************************

        $uploadsPath = Yii::getAlias('@uploads') . DIRECTORY_SEPARATOR;
        $uploadsUrl = Yii::getAlias('@uploadsURL') . '/';

        $unzipPath = $uploadsPath . $id . '/';
        if ($id == -1) {
            // remove existing data and files
            Mandants::findModel($id)->deleteWithRelated();
            array_push($ilog, 'Old mandant ' . $id . ' deleted from database.');
            //delete existing directory and contents
            Impex::deleteDirectory($unzipPath);
            array_push($ilog, 'Old folder ' . $unzipPath . ' removed.');
        }
        // we have to create a new model
        $mandant = new Mandants();
        $mandant->Name = '$$$';
        $mandant->save();
        $id = $mandant->ID_Mandant;
        array_push($ilog, 'New mandant with id ' . $id . ' created.');
        $unzipPath = $uploadsPath . $id . '/';
        //create directory and unzip
        Helpers::createPath($unzipPath);
        array_push($ilog, 'Folder created ' . $unzipPath);
        $zip = new \ZipArchive;
        $zip->open($zipfile);
        $zip->extractTo($unzipPath);
        $zip->close();
        array_push($ilog, 'Unzipped to ' . $unzipPath);

        $connection = Yii::$app->getDb();
        $transaction = $connection->beginTransaction();
        $connection->createCommand('SET foreign_key_checks = 0;')->execute();

        try {
            // read the execution plan
            $execplan = file($unzipPath . '/export/_plan.exp');
            array_push($ilog, 'Execution plan read.');
            $oldFK2newFK = [];
            foreach ($execplan as $execstep) {
                if (substr($execstep, 0, 6) === 'Table:') {
                    $table = trim(substr($execstep, 7));
                    array_push($ilog, 'Loading table ' . $table);
                    $tablelines = file($unzipPath . 'export/' . $table . '.exp');
                    $SaveREF = '';
                    $RemINDEX = '';
                    $LoadREF = [];
                    foreach ($tablelines as $row) {
                        if (substr($row, 0, 8) === 'SaveREF:') {
                            // name of index to save without table name
                            $SaveREF = explode('.', trim(substr($row, 9)))[1];
                            array_push($ilog, 'SaveREF ' . $SaveREF);
                        } elseif (substr($row, 0, 9) === 'RemINDEX:') {
                            $RemINDEX = explode('.', trim(substr($row, 10)))[1];
                            array_push($ilog, 'RemINDEX ' . $RemINDEX);
                        } elseif (substr($row, 0, 8) === 'LoadREF:') {
                            $tmp = explode('intoFK:', trim(substr($row, 9)));
                            $REF = trim($tmp[0]);
                            $FK = trim($tmp[1]);
                            // remove table name
                            $FK = explode('.', $FK)[1];
                            $LoadREF[$FK] = $REF;
                            array_push($ilog, 'LoadREF ' . $FK . ' => ' . $REF);
                        } elseif (substr($row, 0, 4) === 'Row:') {
                            $fields = json_decode(trim(substr($row, 5)), true);
                            // remove because we don't set the INDEX, this field is a auto_increment
                            if ($SaveREF != '') {
                                $oldREF = $fields[$SaveREF];
                            } else {
                                $oldREF = null;
                            }
                            if ($RemINDEX != '') { // && (count($LoadREF) >= 0 || $SaveREF != '')
                                $oldINDEX = $fields[$RemINDEX];
                                unset($fields[$RemINDEX]);
                                array_push($ilog, 'Remove INDEX field ' . $RemINDEX);
                            } else {
                                $oldINDEX = null;
                            }
                            if ($table == 'Mandants') {
                                // save this index for further reference as foreign key
                                array_push($ilog, 'save FK ' . $table . '.' . $SaveREF . '.' . $oldREF . ' = ' . $id);
                                $oldFK2newFK[$table . '.' . $SaveREF . '.' . $oldREF] = $id;
                                $mandant = Mandants::findOne($id);
                                $mandant->attributes = $fields;
                                $mandant->ID_Mandant = $id;
                                $mandant->save();
                            } else {
                                // check if we have to replace a FK key
                                foreach ($LoadREF as $key => $value) {
                                    array_push($ilog, 'replace FK ' . $fields[$key] . ' = ' . $oldFK2newFK[$value . '.' . $fields[$key]]);
                                    $fields[$key] = $oldFK2newFK[$value . '.' . $fields[$key]];
                                }
                                if (count($LoadREF) == 0 && $SaveREF == '') {
                                    // this table is generic, not depending a a particular mandant, there may be clashes
                                    $exists = $connection->createCommand('SELECT COUNT(*) FROM ' . $table . ' WHERE ' . $RemINDEX . '="' . $oldINDEX . '"')
                                        ->queryOne();
                                    if ($exists > 0) {
                                        $update = $connection->createCommand()->update($table, $fields, $RemINDEX . '="' . $oldINDEX . '"');
                                        $update->execute();
                                        array_push($ilog, $update->Sql);
                                    } else {
                                        $insert = $connection->createCommand()->insert($table, $fields);
                                        $insert->execute();
                                        array_push($ilog, $insert->Sql);
                                    }
                                } else {
                                    $insert = $connection->createCommand()->insert($table, $fields);
                                    $insert->execute();
                                    array_push($ilog, $insert->Sql);
                                }
                                if ($SaveREF != '') {
                                    // save this index for further reference as foreign key
                                    $oldFK2newFK[$table . '.' . $SaveREF . '.' . $oldREF] = Yii::$app->db->getLastInsertID();
                                    array_push($ilog, 'save FK ' . $table . '.' . $SaveREF . '.' . $oldREF . ' = ' . Yii::$app->db->getLastInsertID());
                                }
                            }
                        }
                    }
                }
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            array_push($ilog, 'ERROR ' . $e);
        }
        $connection->createCommand('SET foreign_key_checks = 1;')->execute();

        return $ilog;
    }

    public static function ExportDB()
    {

        $ilog = [];
        $schema = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();
        $ignoretables = ['Dashboard', 'article',
            'auth_item_child',
            'profile', 'session', 'social_account', 'token'];
        $ignoretables = ['Dashboard', 'article',
            'profile', 'session', 'social_account', 'token', 'user'];
        $executionplan = [];

        // *******************************************
        // setup the folder where we will save the data
        // *******************************************
        $uploadsPath = Yii::getAlias('@uploads') . DIRECTORY_SEPARATOR;
        $uploadsUrl = Yii::getAlias('@uploadsURL') . '/';

        $exportPath = $uploadsPath . 'export/';
        //delete existing directory and contents
        Impex::deleteDirectory($exportPath);
        //create directory
        Helpers::createPath($exportPath);
        array_push($ilog, 'Export to ' . $exportPath);
        // remove existing zip
        @unlink($uploadsPath . 'ExportDB.zip');

        $connection = Yii::$app->getDb();

        // *******************************************
        // INVENTORY OF ALL THE TABLES TO PROCESS
        // *******************************************

        $tables2go = [];
        array_push($ilog, '<b>Inventory of all the tables</b>');
        array_push($tables2go, 'Mandants');
        array_push($ilog, 'Mandants');
        $command = $connection->createCommand("
                SELECT table_name 
                FROM information_schema.tables
                WHERE TABLE_SCHEMA = '" . $schema . "' AND 
                    table_name NOT IN ('Mandants', '" . implode("','", $ignoretables) . "');");
        $result = $command->queryAll();
        foreach ($result as $row) {
            // save table name to array
            array_push($tables2go, $row['table_name']);
            array_push($ilog, $row['table_name']);
        }

        // *******************************************
        // INVENTORY OF ALL THE FOREIGN KEYS TO PROCESS
        // *******************************************

        $fk = [];
        array_push($ilog, '<b>Inventory of all foreign keys</b>');
        $command = $connection->createCommand("
                SELECT DISTINCT
                    concat(referenced_table_name, '.', referenced_column_name) AS 'fk'
                FROM
                    information_schema.key_column_usage
                WHERE
                    TABLE_SCHEMA = '" . $schema . "' AND 
                    referenced_table_name IS NOT NULL AND 
                    referenced_table_name NOT IN ('" . implode("','", $ignoretables) . "') AND 
                    table_name NOT IN ('" . implode("','", $ignoretables) . "');");
        $result = $command->queryAll();
        foreach ($result as $row) {
            // save table name to array
            array_push($fk, $row['fk']);
            array_push($ilog, $row['fk']);
        }

        // *******************************************
        // EXPORTING ALL THE TABLES AS SOON THAT WE HAVE ALL THE FOREIGN KEYS
        // *******************************************

        array_push($ilog, '<b>Iterating all the tables</b>');
        while (count($tables2go) > 0) {
            foreach ($tables2go as $table) {
                array_push($ilog, count($tables2go) . '. testing ' . $table);
                // check if this table has foreign keys
                $references = [];
                $command = $connection->createCommand("
                        SELECT
                            column_name AS 'foreign_key',
                            referenced_table_name,
                            concat(referenced_table_name, '.', referenced_column_name) AS 'references'
                        FROM 
                            information_schema.key_column_usage
                        WHERE 
                            table_name = '" . $table . "';");
                $result = $command->queryAll();
                $instructions = [];
                $pk = '';
                $process = true;
                $where = '';
                foreach ($result as $row) {
                    if ($row['references'] == '') {
                        // primary key
                        // array_push($instructions, 'SavePK: ' . $table.'.'.$row['foreign_key']);
                        $pk = $table . '.' . $row['foreign_key'];
                        if (in_array($pk, $fk)) {
                            array_push($instructions, 'SaveREF: ' . $pk);
                        }
                        array_push($instructions, 'RemINDEX: ' . $pk);
                    } elseif (!in_array($row['referenced_table_name'], $ignoretables)) {
                        array_push($instructions, 'LoadREF: ' . $row['references'] . ' intoFK: ' . $table . '.' . $row['foreign_key']);
                        if (!array_key_exists($row['references'], $filters)) {
                            // at least one reference has not yet been collected, skip
                            $process = false;
                            array_push($ilog, 'has following references not yet collected: ' . $table . '.' . $row['foreign_key'] . ' -> ' . $row['references']);
                        }
                    }
                }
                if ($process) {
                    $command = $connection->createCommand("
                        SELECT * 
                        FROM {$table} 
                        " . ($where != '' ? " WHERE " . substr($where, 0, -5) . ";" : ";"));
                    $result = $command->queryAll();
                    array_push($ilog, 'Dumping ' . $table . ' ' . $command->Sql);
                    $expfile = fopen($exportPath . $table . '.exp', 'w') or die("Unable to open file!");
                    if (count($instructions) != 0) {
                        fwrite($expfile, implode(PHP_EOL, $instructions) . PHP_EOL);
                    }
                    $fkv = [];
                    foreach ($result as $row) {
                        fwrite($expfile, 'Row: ' . json_encode($row) . PHP_EOL);
                        if (in_array($pk, $fk)) {
                            // collect the foreign key values
                            array_push($fkv, $row[substr($pk, strpos($pk, ".") + 1)]);
                        }
                    }
                    fclose($expfile);
                    // add the collected fk to our filter
                    if (in_array($pk, $fk)) {
                        $filters[$pk] = $fkv;
                    }
                    // removing table from list                    
                    $tables2go = array_diff($tables2go, array($table));
                    // add actions to our execution plan
                    $executionplan = array_merge($executionplan, array('Table: ' . $table), $instructions);
                } else {
                    array_push($ilog, 'skipped for now.');
                }
            }
            array_push($ilog, count($tables2go) . ' tables remain.');
        }

        // last but not least, export our user table
        $table = "user";
        $instructions = ['RemINDEX: user.id'];
        $command = $connection->createCommand("
            SELECT * 
            FROM {$table}");
        $result = $command->queryAll();
        array_push($ilog, 'Dumping ' . $table . ' ' . $command->Sql);
        $expfile = fopen($exportPath . $table . '.exp', 'w') or die("Unable to open file!");
        if (count($instructions) != 0) {
            fwrite($expfile, implode(PHP_EOL, $instructions) . PHP_EOL);
        }
        foreach ($result as $row) {
            fwrite($expfile, 'Row: ' . json_encode($row) . PHP_EOL);
        }
        fclose($expfile);
        // add actions to our execution plan
        $executionplan = array_merge($executionplan, array('Table: ' . $table), $instructions);

        // write our execution plan
        $expfile = fopen($exportPath . '_plan.exp', 'w') or die("Unable to open file!");
        foreach ($executionplan as $i) {
            fwrite($expfile, $i . PHP_EOL);
        }
        fclose($expfile);

        // zip our folders
        $rootPath = realpath($uploadsPath);
        $zip = new \ZipArchive();
        $zip->open($uploadsPath . 'ExportDB.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        // Create recursive directory iterator
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($rootPath), \RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();

        return $ilog;
    }

    public static function ImportDB($zipfile)
    {

        $ilog = [];
        $schema = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();
        $executionplan = [];

        // *******************************************
        // setup the folder where we will save the data
        // *******************************************

        $uploadsPath = Yii::getAlias('@uploads') . DIRECTORY_SEPARATOR . 'sqlbackups' . DIRECTORY_SEPARATOR;
        $uploadsUrl = Yii::getAlias('@uploadsURL') . DIRECTORY_SEPARATOR . 'sqlbackups' . DIRECTORY_SEPARATOR;

        $unzipPath = $uploadsPath;
        //delete existing directory and contents
        Impex::deleteDirectory($unzipPath);
        array_push($ilog, 'Old folder ' . $unzipPath . ' removed.');
        //create directory and unzip
        $zip = new \ZipArchive;
        $zip->open($zipfile);
        $zip->extractTo($unzipPath);
        $zip->close();
        array_push($ilog, 'Unzipped to ' . $unzipPath);

        $connection = Yii::$app->getDb();
        $transaction = $connection->beginTransaction();
        $connection->createCommand('SET foreign_key_checks = 0;')->execute();

        try {
            // read the execution plan
            $execplan = file($unzipPath . '/export/_plan.exp');
            array_push($ilog, 'Execution plan read.');
            $oldFK2newFK = [];
            foreach ($execplan as $execstep) {
                if (substr($execstep, 0, 6) === 'Table:') {
                    $table = trim(substr($execstep, 7));
                    array_push($ilog, 'Loading table ' . $table);
                    $delete = $connection->createCommand()->delete($table);
                    $delete->execute();
                    $tablelines = file($unzipPath . 'export/' . $table . '.exp');
                    $RemINDEX = '';
                    foreach ($tablelines as $row) {
                        if (substr($row, 0, 9) === 'RemINDEX:') {
                            $RemINDEX = explode('.', trim(substr($row, 10)))[1];
                            array_push($ilog, 'RemINDEX ' . $RemINDEX);
                        } elseif (substr($row, 0, 4) === 'Row:') {
                            $fields = json_decode(trim(substr($row, 5)), true);
                            $insert = $connection->createCommand()->insert($table, $fields);
                            $insert->execute();
                            array_push($ilog, $insert->rawSql);
                            if ($RemINDEX != '' && $fields[$RemINDEX] == 0) { // && (count($LoadREF) >= 0 || $SaveREF != '')
                                $update = $connection->createCommand()->update($table, [$RemINDEX => $fields[$RemINDEX]], $RemINDEX . '="' . Yii::$app->db->getLastInsertID() . '"');
                                $update->execute();
                                array_push($ilog, 'Remove INDEX field ' . $RemINDEX);
                            }
                        }
                    }
                }
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            array_push($ilog, 'ERROR ' . $e);
        }
        $connection->createCommand('SET foreign_key_checks = 1;')->execute();
        return $ilog;
    }

    public static function ImportSqldump($sqldumpfile)
    {

        $ilog = [];
        $zipfile = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . $sqldumpfile;

        @unlink($zipfile);
        // download the dump
        $Url = 'https://' . Lx::getMaster() . '/api/web/sqlbackups/' . $sqldumpfile;
        array_push($ilog, 'Downloading ' . $Url);
        $html = file_get_contents($Url);
        $file = fopen($zipfile, "w") or die("Unable to open file!");
        fwrite($file, $html);
        fclose($file);

        $uploadsPath = Yii::getAlias('@backups') . DIRECTORY_SEPARATOR;
        $uploadsUrl = Yii::getAlias('@backupsURL') . '/';

        //create directory and unzip
        array_push($ilog, 'Opening to ' . $zipfile);
        $zip = new \ZipArchive;
        $zip->open($zipfile);
        array_push($ilog, 'Unzipping to ' . $uploadsPath);
        $zip->extractTo($uploadsPath);
        $zip->close();

        $unzipfile = $uploadsPath . basename($zipfile, ".zip");

        $connection = Yii::$app->getDb();
        $transaction = $connection->beginTransaction();
        $connection->createCommand('SET foreign_key_checks = 0;')->execute();

        try {
            // set execution time to 5 minutes
            set_time_limit(5 * 60);
            array_push($ilog, 'Processing ' . $unzipfile);
            if ($file = fopen($unzipfile, "r")) {
                $line = '';
                while (!feof($file)) {
                    $line .= fgets($file);
                    if (substr($line, 0, 3) == '-- ') {
                        array_push($ilog, $line);
                        $line = '';
                        continue;
                    } elseif (substr($line, 0, 2) == '--') {
                        $line = '';
                        continue;
                    } elseif (trim($line) == '') {
                        $line = '';
                        continue;
                    } elseif (substr(trim($line), -1, 1) == ';') {
                        // If it has a semicolon at the end, it's the end of the query
                        $connection->createCommand($line)->execute();
                        $line = '';
                    }
                }
                fclose($file);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            array_push($ilog, 'ERROR ' . $e);
        }
        $connection->createCommand('SET foreign_key_checks = 1;')->execute();
        return $ilog;
    }

    public static function ImportFiles($auth_key)
    {

        $ilog = [];

        $uploadsPath = Yii::getAlias('@uploads') . DIRECTORY_SEPARATOR;
        $uploadsUrl = Yii::getAlias('@uploadsURL') . '/';

        // get current list of files from our master
        $client = new Client();
        /** @noinspection MissedFieldInspection */
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('https://www.' . Lx::getMaster() . '/api/v1/master/getfileslist')
            ->setHeaders([
                'cache-control' => 'no-cache',
                'content-type'  => 'application/x-www-form-urlencoded',
                'authorization' => 'Basic ' . base64_encode($auth_key)
            ])
            ->send();
        $files = [];

        if ($response->isOk) {
            $data = json_decode($response->data, true);
            // decode the response data
            $files = $data['files'];
            foreach ($files as $file) {
                $Url = 'https://' . Lx::getMaster() . '/uploads/' . $file;
                if (strpos($file, " ")) {
                    array_push($ilog, 'Skipping ' . $Url . ' -> whitespace');
                    continue;
                } elseif (file_exists($uploadsPath . $file)) {
//                    array_push($ilog, 'Skipping ' . $Url . ' -> exists');
                    continue;
                } else {
                    array_push($ilog, 'Downloading ' . $Url);
                    FileHelper::createDirectory(dirname($uploadsPath . $file));
                    $html = file_get_contents($Url);
                    $file = fopen($uploadsPath . $file, "w") or die("Unable to open file!");
                    fwrite($file, $html);
                    fclose($file);
                }
            }
        } else {
            array_push($ilog, 'Error, could not download files list from master.');
        }
        return $ilog;
    }

    public static function CleanupFiles($dryrun)
    {

        set_time_limit(0);

        $ilog = [];

        $uploadsPath = Yii::getAlias('@uploads') . DIRECTORY_SEPARATOR;
        $uploadsUrl = Yii::getAlias('@uploadsURL') . '/';

        $modelnamelist = [];
        foreach (glob(Yii::getAlias('@backend') . DIRECTORY_SEPARATOR .
            'models' . DIRECTORY_SEPARATOR .
            'base' . DIRECTORY_SEPARATOR . '*.php') as $filename) {
            $modelname = '\backend\models\\' . basename($filename, ".php");
            if ($modelname !== '\backend\models\TraitBlameableTimestamp' &&
                $modelname !== '\backend\models\TraitFileUploads' &&
                $modelname !== '\backend\models\TraitContLang' &&
                $modelname !== '\backend\models\backend') {
                $model = new $modelname();
                if (method_exists($model, 'getAjaxfileinputs')) {
                    array_push($modelnamelist, $modelname);
                }
            }
        }

        $files = FileHelper::findFiles($uploadsPath, [
            'except'    => ['thumbs/',
                'temp/',
                'mpdf/',
                'export/',
                'forms/'
            ],
            'recursive' => true,
        ]);
        $l = strlen($uploadsPath);
        foreach ($files as &$file) {
            $file = substr($file, $l);
        }
        $thumbs = [];

        // cleanup one model after the other
        foreach ($modelnamelist as $modelname) {
            array_push($ilog, '-- Processing ' . $modelname);
            /* @var $rows Contacts */
            $rows = $modelname::find()->all();
            /* @var $model Contacts */
            $model = new $modelname();
            $tablename = $model->tableName();
            $FFNlist = $model->getAjaxfileinputs();
            // cleanup one FFN after the other
            foreach ($FFNlist as $i => $FFN) {
                array_push($ilog, 'FFN: ' . $i);
                // set default values
                $resizeimagestosize = $model->IMGRESIZE_L_2048;
                $resizeimagestoquality = $model->IMGQUALITY_H_90;
                // get the $FFN configuration from the model
                // extract the AjaxUploadFields configuration, e.g.
                // 'ajaxfileinputPhoto' => [
                //    'optionsmultiple' => false,
                //    'resizeimagestosize' => $this->IMGRESIZE_S_512,
                //    'resizeimagestoquality' => $this->IMGQUALITY_H_90
                //    ...
                // ];
                /* @var $Storefield string */
                /* @var $Origifield string */
                /* @var $optionsmultiple bool */
                /* @var $optionsaccept string */
                /* @var $allowedfileextensions array */
                /* @var $maxuploadfilesize int */
                /* @var $resizeimagestosize int */
                /* @var $resizeimagestoquality int */
                extract($FFN);
                foreach ($rows as $row) {
                    $StoreFn = preg_split('#//#', $row[$Storefield], null, PREG_SPLIT_NO_EMPTY);
                    $OrigiFn = preg_split('#//#', $row[$Origifield], null, PREG_SPLIT_NO_EMPTY);
                    foreach ($StoreFn as $k => $f) {
                        $searchf = $row->ID_Mandant . DIRECTORY_SEPARATOR . strtolower($tablename) . DIRECTORY_SEPARATOR . $f;
                        if (($key = array_search($searchf, $files)) !== false) {
                            // we found the file, remove the key
                            unset($files[$key]);
                            // DO SOME PROCESSING ON THE FILES
                            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                            // check if we must test the file extension uploaded
                            if (!empty($allowedfileextensions)) {
                                if (is_array($allowedfileextensions)) {
                                    if (!in_array($ext, $allowedfileextensions)) {
                                        array_push($ilog, 'Error on ' . $searchf);
                                        array_push($ilog, '-> File type not allowed, only ' . join(', ', $allowedfileextensions) . '.');
                                    }
                                } else {
                                    if ($ext != $allowedfileextensions) {
                                        array_push($ilog, 'Error on ' . $searchf);
                                        array_push($ilog, '-> File type not allowed, only ' . $allowedfileextensions . '.');
                                    }
                                }
                            }
                            // if we uploaded an image file, check if we should resize it
                            if ($ext == 'jpg' || $ext == 'gif' || $ext == 'png') {
                                $img = Image::getImagine()->open($uploadsPath . $searchf);
                                // fix image orientation for iphone photos
                                $orientation = 0;
                                if ($ext == 'jpg') {
                                    if (method_exists($img, 'getImageProperty')) {
                                        $orientation = $img->getImageProperty('exif:Orientation');
                                    } else {
                                        $exif = @exif_read_data($uploadsPath . $searchf);
                                        $orientation = isset($exif['Orientation']) ? $exif['Orientation'] : 0;
                                        unset($exif);
                                    }
                                    switch ($orientation) {
                                        case 3:
                                            $img->rotate(180);
                                            break;
                                        case 6:
                                            $img->rotate(90);
                                            break;
                                        case 8:
                                            $img->rotate(-90);
                                            break;
                                    }
                                }
                                // do the resizing if necessary
                                $img_h = $img->getSize()->getHeight();
                                $img_w = $img->getSize()->getWidth();
                                $img_max_hw = ($img_h > $img_w ? $img_h : $img_w);
                                if ($img_max_hw > $resizeimagestosize) {
                                    array_push($ilog, 'Warning on ' . $searchf);
                                    array_push($ilog, '-> Size is ' . $img->getSize()->getHeight() . 'x' . $img->getSize()->getWidth() . ', expected max ' . $resizeimagestosize . 'x' . $resizeimagestosize . '.');
                                    if (!$dryrun) {
                                        $fn = $uploadsPath . $searchf;
                                        $newfn = substr($fn, 0, -4) . '_new.' . $ext;
                                        $origfn = substr($fn, 0, -4) . '_orig.' . $ext;
                                        // we have no idea which compression the original picture has
                                        $newimg = $img->thumbnail(new Box($resizeimagestosize, $resizeimagestosize));
                                        // compute targetfilesize
                                        $targetfilesize = (int)(filesize($fn) / $img_max_hw * $resizeimagestosize);
                                        $targetquality = $resizeimagestoquality;
                                        while (true) {
                                            // ($ext=='png' ? (int) $targetquality * 0.09 : $targetquality)
                                            $newimg->save($newfn, ['quality' => $targetquality]);
                                            $newfilesize = filesize($newfn);
                                            if ($newfilesize < $targetfilesize || $targetquality < 33) {
                                                // we have reached the targetsize or lowest acceptable quality
                                                rename($fn, $origfn);
                                                rename($newfn, $fn);
                                                break;
                                            } else {
                                                unlink($newfn);
                                                $targetquality -= 20;
                                            }
                                        }
                                    }
                                }
                                unset($newimg);
                                unset($img);
                            }
                        } else {
                            // we did not find the key, alert!
                            array_push($ilog, 'Missing file ' . $searchf);
                            // remove the element
                            $StoreFn[$k] = '';
                            $OrigiFn[$k] = '';
                        }
                        // mark thumbs folder for deletion
                        $f = $uploadsPath . $row->ID_Mandant . DIRECTORY_SEPARATOR . strtolower($tablename) . DIRECTORY_SEPARATOR . 'thumbs' . DIRECTORY_SEPARATOR;
                        if (!in_array($f, $thumbs)) {
                            array_push($thumbs, $f);
                        }
                    }
                    $newStoreFn = str_replace('////', '//', trim(join('//', $StoreFn), '/'));
                    $newOrigFn = str_replace('////', '//', trim(join('//', $OrigiFn), '/'));
                    if ($row[$Storefield] != $newStoreFn || $row[$Origifield] != $newOrigFn) {
                        // if we removed an element, save the model
                        $row[$Storefield] = $newStoreFn;
                        $row[$Origifield] = $newOrigFn;
                        if (!$dryrun) {
                            $row->save(false);
                        }
                    }
                }

            }
            foreach ($files as $f) {
                if (StringHelper::endsWith(dirname($f), $tablename)) {
                    array_push($ilog, 'Deleting ' . $f);
                    array_push($ilog, '-> not used and will be deleted.');
                    if (!$dryrun) {
                        @unlink($f);
                    }
                }
            }
        }
        array_push($ilog, '-- Processing general folders');
        $f = $uploadsPath . 'temp' . DIRECTORY_SEPARATOR;
        array_push($thumbs, $f);
        $f = $uploadsPath . 'mpdf' . DIRECTORY_SEPARATOR;
        array_push($thumbs, $f);
        array_map('unlink', glob($uploadsPath . DIRECTORY_SEPARATOR . '*.log'));
        array_map('unlink', glob($uploadsPath . DIRECTORY_SEPARATOR . '*.json'));
        foreach ($thumbs as $f) {
            array_push($ilog, 'Removing ' . $f);
            if (!$dryrun) {
                FileHelper::removeDirectory($f);
            }
        }

        $temp = $uploadsPath . 'temp' . DIRECTORY_SEPARATOR;
        FileHelper::createDirectory($temp);
        if ($dryrun) {
            array_push($ilog, '!!! DRY RUN -> nothing changed !!!');
        }

        return $ilog;
    }

    static function deleteDirectory($dir)
    {

        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir) || is_link($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!self::deleteDirectory($dir . "/" . $item)) {
                chmod($dir . "/" . $item, 0777);
                if (!self::deleteDirectory($dir . "/" . $item))
                    return false;
            };
        }
        return rmdir($dir);
    }

}
