<?php

namespace App\Api\Rules;

use App\Api\Http\Controllers\BookController;
use App\Api\Services\TypesGenerator;
use Illuminate\Contracts\Validation\Rule;
use App\Models\BookCompilation;

class CheckBookToCompilationRule implements Rule
{


    public array $typesModel;
    private string $type;
    private int $compilationId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type, $compilationId,)
    {
        $typesGenerator = new TypesGenerator();
        $this->typesModel = $typesGenerator->getCompilationsBookTypes();
        $this->type = (string)$type;
        $this->compilationId = (int)$compilationId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!is_string($this->type)) {
            return false;
        }
        if (!array_key_exists($this->type, $this->typesModel)) {
            return false;
        }
        if (!$this->typesModel[$this->type]::where('id', $value)->exists()) {
            return false;
        }
        $existingCompilationRelation = BookCompilation::where('compilationable_id', $value)
            ->where('compilation_id', $this->compilationId)
            ->where('compilationable_type', $this->type)
            ->exists();


        if ($existingCompilationRelation) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Некорректные данные';
    }
}
