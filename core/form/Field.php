<?php

namespace app\core\form;

use app\models\Model;

class Field
{

    const  TEXT = 'text';
    const  EMAIL = 'email';
    const  PASSWORD = 'password';
    const NUMBER = 'number';
    public Model $model;
    public string $attribute = "";

    public string $label = "";

    public string $type = "";

    public string $placeholder = "";


    function __construct(Model $model, string $attribute)
    {
        $this->type = self::TEXT;
        $this->model = $model;
        $this->attribute = $attribute;
    }

    function __toString()
    {
        return sprintf(
            '
        <div class="mb-3">
            <label class="form-label">%s</label>
            <input type="%s" class="form-control %s" value= "%s" name="%s" placeholder = "%s">
            <div class= "invalid-feedback">
                %s
            </div>
        </div>',
            $this->label,
            $this->type,
            $this->model->hasError($this->attribute) ? "is-invalid" : '',
            $this->model->{$this->attribute},
            $this->attribute,
            $this->placeholder,
            $this->model->getFirstError($this->attribute)

        );
    }

    function setFieldType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    function setFieldPlaceHolder(string $placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }
}
