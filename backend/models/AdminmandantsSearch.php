<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * backend\models\AdminmandantsSearch represents the model behind the search form about `backend\models\Mandants`.
 */
class AdminmandantsSearch extends Mandants
{

    public $shortAddress;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID_Mandant', 'IsPublic', 'Nace', 'Comp_convcoll', 'Comp_etsclasse', 'Comp_classmutu', 'BMAAval', 'BMAAcat', 'MedvisitsForm', 'Multiemployer', 'Fieldpermissions', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['Name', 'Address', 'Zip', 'City', 'Co_Code', 'ContLanguages', 'StartDate', 'EndDate', 'Activity', 'Tel', 'Fax', 'Matricule', 'JPG_Logo', 'Orig_File', 'ImportID', 'shortAddress', 'multiemployer'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Mandants::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID_Mandant'     => $this->ID_Mandant,
            'StartDate'      => $this->StartDate,
            'EndDate'        => $this->EndDate,
            'Nace'           => $this->Nace,
            'Comp_convcoll'  => $this->Comp_convcoll,
            'Comp_etsclasse' => $this->Comp_etsclasse,
            'Comp_classmutu' => $this->Comp_classmutu,
            'BMAAval'        => $this->BMAAval,
            'BMAAcat'        => $this->BMAAcat,
            'MedvisitsForm'  => $this->MedvisitsForm,
            'created_by'     => $this->created_by,
            'updated_by'     => $this->updated_by,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ]);


        if ($this->IsPublic != -1) {
            $query->andFilterWhere([
                'IsPublic' => $this->IsPublic
            ]);
        }

        if ($this->Multiemployer != -1) {
            $query->andFilterWhere([
                'Multiemployer' => $this->Multiemployer,
            ]);
        }
        if ($this->Fieldpermissions != -1) {
            $query->andFilterWhere([
                'Fieldpermissions' => $this->Fieldpermissions,
            ]);
        }

        $query->andFilterWhere(['like', 'Name', $this->Name])
            ->andFilterWhere(['like', 'Address', $this->Address])
            ->andFilterWhere(['like', 'Zip', $this->Zip])
            ->andFilterWhere(['like', 'City', $this->City])
            ->andFilterWhere(['like', 'Co_Code', $this->Co_Code])
            ->andFilterWhere(['like', 'ContLanguages', $this->ContLanguages])
            ->andFilterWhere(['like', 'Activity', $this->Activity])
            ->andFilterWhere(['like', 'Tel', $this->Tel])
            ->andFilterWhere(['like', 'Fax', $this->Fax])
            ->andFilterWhere(['like', 'Matricule', $this->Matricule])
            ->andFilterWhere(['like', 'JPG_Logo', $this->JPG_Logo])
            ->andFilterWhere(['like', 'Orig_File', $this->Orig_File])
            ->andFilterWhere(['like', 'CONCAT(Mandants.Address, Mandants.Zip, Mandants.City)', $this->shortAddress])
            ->andFilterWhere(['like', 'ImportID', $this->ImportID]);

        return $dataProvider;
    }

}
