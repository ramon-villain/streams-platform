<?php namespace Anomaly\Streams\Platform\Addon\FieldType;

use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Assignment\AssignmentModel;
use Anomaly\Streams\Platform\Contract\PresentableInterface;

class FieldType extends Addon implements PresentableInterface
{

    protected $rules = [];

    protected $field = null;

    protected $value = null;

    protected $label = null;

    protected $locale = null;

    protected $placeholder = null;

    protected $instructions = null;

    protected $translatable = false;

    protected $required = false;

    protected $hidden = false;

    protected $prefix = '';

    protected $suffix = '';

    protected $columnType = 'string';

    protected $view = 'ui/form/partials/element';

    public function input()
    {
        $options = [
            'class'       => 'form-control',
            'placeholder' => $this->getPlaceholder(),
        ];

        return app('form')->text($this->getFieldName(), $this->getValue(), $options);
    }

    public function filter()
    {
        return $this->input();
    }

    public function element()
    {
        $locale = $this->getLocale();

        $hidden       = ($this->hidden);
        $required     = $this->getRequired();
        $translatable = $this->getTranslatable();
        $class        = $this->getSlug() . '-field-type';

        $language     = trans("language.{$locale}");
        $label        = trans($this->label, [], null, $locale);
        $instructions = trans($this->instructions, [], null, $locale);

        $input = $this->input();

        $data = compact(
            'label',
            'language',
            'instructions',
            'input',
            'locale',
            'hidden',
            'class',
            'translatable',
            'required'
        );

        return view($this->view, $data);
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;

        return $this;
    }

    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    protected function getPlaceholder()
    {
        return $this->placeholder;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    private function getLocale()
    {
        if (!$this->locale) {

            $this->locale = setting('module.settings::default_locale', config('app.locale', 'en'));
        }

        return $this->locale;
    }

    public function setTranslatable($translatable)
    {
        $this->translatable = $translatable;

        return $this;
    }

    public function getTranslatable()
    {
        return $this->translatable;
    }

    public function setPrefix($prefix = null)
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function getPrefix()
    {
        if ($this->prefix) {

            return $this->prefix . (ends_with($this->prefix, '_') ? null : '_');
        }

        return $this->prefix;
    }

    public function setSuffix($suffix = null)
    {
        $this->suffix = $suffix;

        return $this;
    }

    public function getSuffix()
    {
        if ($this->suffix) {

            return (starts_with($this->suffix, '_') ? null : '_') . $this->suffix;
        }

        return null;
    }

    public function setHidden($hidden)
    {
        $this->hidden = ($hidden);

        return $this;
    }

    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    public function getRequired()
    {
        return $this->required;
    }

    public function getFieldName()
    {
        return "{$this->getPrefix()}{$this->field}{$this->getSuffix()}";
    }

    public function getColumnName()
    {
        return $this->field;
    }

    public function getColumnType()
    {
        return $this->columnType;
    }

    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    public function decorate()
    {
        if ($presenter = app('streams.transformer')->toPresenter($this)) {

            return new $presenter($this);
        }

        return new FieldTypePresenter($this);
    }

    public function toFilter()
    {
        if ($filter = app('streams.transformer')->toFilter($this)) {

            return new $filter();
        }

        return new FieldTypeFilter($this);
    }

    protected function onSet($value)
    {
        return $value;
    }

    protected function onAfterSet($entry)
    {
        //
    }

    protected function onGet($value)
    {
        return $value;
    }

    protected function onAssignmentCreated(AssignmentModel $assignment)
    {
        // Run after an assignment is created.
    }

    protected function onAssignmentDeleted(AssignmentModel $assignment)
    {
        // Run after an assignment is deleted.
    }
}