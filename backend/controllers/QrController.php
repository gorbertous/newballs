<?php

namespace backend\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use backend\models\Qr;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class QrController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actionView($hashcode)
    {
        $result = Qr::findOne(['hash_code' => $hashcode]);

        if (!empty($result)) {

            if (!empty($result->ID_Authorization)) {

                $content = Yii::$app->controller->renderPartial('@backend/views/qr/authorizations-view', [
                    'modelMedvisits'      => new ArrayDataProvider([ 'allModels' => $result->authorizations->contact->medvisits ]),
                    'modelTrainings'      => new ArrayDataProvider([ 'allModels' => $result->authorizations->contact->traininghistories ]),
                    'modelAuthorizations' => new ArrayDataProvider([ 'allModels' => $result->authorizations->contact->authorizations ]),
                    'modelEmpContracts'   => new ArrayDataProvider([ 'allModels' => $result->authorizations->contact->empcontracts ]),
                    'model' => $result
                ]);

                $pdf = new Mpdf([
                    'mode'          => 'utf-8',
                    'format'        => 'A4'
                ]);

                // load kartik css pdf style
                $stylesheet = file_get_contents(__DIR__ . '/../../common/printers/pdf-style.css');
                $pdf->WriteHTML($stylesheet, 1);

                // write html content on the pdf
                $pdf->WriteHTML('
                <style>
                body, html {margin: 0; padding: 0; width: 100%; height: 100%; font-family: "sans-serif", serif, arial, verdana;}
                
                table, tbody, thead, { margin: 0; padding: 0; font-size: 13px; }
                td, th { padding: 10px; }
                thead tr th, tbody tr td { text-align: left; }
                tbody tr td { font-size: 12px; v-align: top; }
                            
                h1 { padding: 0; margin: 0; text-align: center; color: #fff; }
                h3 { font-weight: normal; font-size: 14px; color: #fff; text-style: normal; }
                
                #header { 
                position: absolute; 
                top: 0;
                left: 0;
                width: 100%; 
                padding-top: 30px;
                padding-bottom: 30px;
                
                text-align: center;
                
                background: #67B26F;
                background: -webkit-linear-gradient(to right, #4ca2cd, #67B26F);
                background: linear-gradient(to right, #4ca2cd, #67B26F);
                }
                
                span.header { color: #fff; font-size: 12px; }
                
                .text { width: 600px; margin: 0 auto; }
                .text table tr th { text-align: left; }
                .text table th { width: 300px; border-bottom: 1px solid #eee; }
                .text table td { width: 300px; border-bottom: 1px solid #eee; }
                .text table td { padding: 10px; }
                
                .height { height: 100px; }
                </style>
                ');

                $pdf->WriteHTML($content, 2);

                // return the pdf output as per the destination setting
                $pdf->Output(null, Destination::INLINE);
                $pdf->Close();

                return false;

            } else if (!empty($result->ID_Registeritem)) {

                $content = Yii::$app->controller->renderPartial('@backend/views/qr/registeritem-view', [
                    'modelRegisterinspects' => new ArrayDataProvider([ 'allModels' => $result->registeritem->registerinspects ]),
                    'modelRegisterassigns'  => new ArrayDataProvider([ 'allModels' => $result->registeritem->registerminus ]),
                    'model' => $result
                ]);

                $pdf = new Mpdf([
                    'mode'          => 'utf-8',
                    'format'        => 'A4'
                ]);

                // load kartik css pdf style
                $stylesheet = file_get_contents(__DIR__ . '/../../common/printers/pdf-style.css');
                $pdf->WriteHTML($stylesheet, 1);

                // write html content on the pdf
                $pdf->WriteHTML('
                <style>
                body, html {margin: 0; padding: 0; width: 100%; height: 100%; font-family: "sans-serif", serif, arial, verdana;}
                
                table, tbody, thead, { margin: 0; padding: 0; font-size: 13px; }
                td, th { padding: 10px; }
                thead tr th, tbody tr td { text-align: left; }
                tbody tr td { font-size: 12px; v-align: top; }
                            
                h1 { padding: 0; margin: 0; text-align: center; color: #fff; }
                h3 { font-weight: normal; font-size: 14px; color: #fff; text-style: normal; }
                
                #header { 
                position: absolute; 
                top: 0;
                left: 0;
                width: 100%; 
                padding-top: 30px;
                padding-bottom: 30px;
                
                text-align: center;
                
                background: #67B26F;
                background: -webkit-linear-gradient(to right, #4ca2cd, #67B26F);
                background: linear-gradient(to right, #4ca2cd, #67B26F);
                }
                
                span.header { color: #fff; font-size: 12px; }
                
                .text { width: 600px; margin: 0 auto; }
                .text table tr th { text-align: left; }
                .text table th { width: 300px; border-bottom: 1px solid #eee; }
                .text table td { width: 300px; border-bottom: 1px solid #eee; }
                .text table td { padding: 10px; }
                
                .height { height: 100px; }
                </style>
                ');

                $pdf->WriteHTML($content, 2);

                // return the pdf output as per the destination setting
                $pdf->Output(null, Destination::INLINE);
                $pdf->Close();

                return false;
            }

            return false;
        }

        return Yii::$app->response->redirect('/');
    }
}
