<?php
/**
 * Created by PhpStorm.
 * User: Cheezzy Tenorz
 * Date: 10/18/2015
 * Time: 10:43 PM.
 */
namespace Fce\Transformers;

use Fce\Models\Evaluation;
use League\Fractal\TransformerAbstract;

class EvaluationTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [
        'question',
    ];

    public function transform(Evaluation $evaluation)
    {
        return [
            'id' => (int) $evaluation->id,
            'section_id' => (int) $evaluation->section_id,
            'question_id' => (int) $evaluation->question_id,
            'question_set_id' => (int) $evaluation->question_set_id,
            'one' => (int) $evaluation->one,
            'two' => (int) $evaluation->two,
            'three' => (int) $evaluation->three,
            'four' => (int) $evaluation->four,
            'five' => (int) $evaluation->five,
        ];
    }

    /**
     * @param Evaluation $evaluation
     * @return \League\Fractal\Resource\Item
     */
    public function includeQuestion(Evaluation $evaluation)
    {
        return $this->item($evaluation->question, new QuestionTransformer);
    }
}
