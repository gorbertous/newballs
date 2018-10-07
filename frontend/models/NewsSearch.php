<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\News;

/**
 * backend\models\NewsSearch represents the model behind the search form about `backend\models\News`.
 * @package frontend\models
 * @property string $title [varchar(64)]
 * @property string $content
 */
class NewsSearch extends News
{
    public $news_tags;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return self::ContLangRules([
            [['id', 'c_id', 'is_public', 'is_valid', 'to_newsletter', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['category', 'title', 'content','news_tags', 'featured_img', 'featured_img_orig', 'content_imgs', 'content_imgs_orig'], 'safe'],
        ]);
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
        $query = News::find()->where(['c_id' => Yii::$app->session->get('c_id')]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);
        
        if (!empty($this->news_tags)) {
            $query->joinWith('tags');
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'         => $this->id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'category'   => $this->category
        ]);
        
        $query->andFilterWhere(['in', 'tags.tag_id', $this->news_tags]);

        if ($this->is_valid != -1) {
            $query->andFilterWhere([
                'is_valid' => $this->is_valid,
            ]);
        }

        if ($this->is_public != -1) {
            $query->andFilterWhere([
                'is_public' => $this->is_public,
            ]);
        }
        if ($this->to_newsletter != -1) {
            $query->andFilterWhere([
                'to_newsletter' => $this->to_newsletter,
            ]);
        }

        $fnsT = News::ContLangFieldName('title');
        $query->andFilterWhere(['like', News::ContLangConcat('title'), $this->$fnsT]);

        $fnsC = News::ContLangFieldName('content');
        $query->andFilterWhere(['like', News::ContLangConcat('content'), $this->$fnsC]);

        return $dataProvider;
    }
}
