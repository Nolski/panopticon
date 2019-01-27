<?php namespace App\Http\Cards;

use Illuminate\Http\Request;
use App\Models\PropertyMetaData;
use Illuminate\Database\Eloquent\Builder;

abstract class Card
{
    protected $name;

    protected $label;

    protected $authorizationCallback;

    protected $meta = [];

    abstract public function apply();

    abstract public function component();

    public function toArray()
    {
        return array_merge([
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'value' => $this->apply(),
            'component' => $this->component(),
        ], $this->meta);
    }


    public function getName()
    {
        return $this->name;
    }


    public function setName($name)
    {
        $this->name = $name;
    }


    public function getLabel()
    {
        return $this->label;
    }


    public function label($label)
    {
        $this->label = $label;

        $this->setName($label);

        return $this;
    }

    /**
     * @param $label
     * @return self
     */
    public static function make($label): self
    {
        $object = app(static::class);

        $object->label($label);

        return $object;
    }


    /**
     * @param \Closure $callback
     * @return $this
     */
    public function authorize(\Closure $callback)
    {
        $this->authorizationCallback = $callback;

        return $this;
    }

    public function isAuthorized()
    {
        $authorizationCallback = $this->getAuthorizationCallback();

        return $authorizationCallback();
    }

    /**
     * @return mixed
     */
    public function getAuthorizationCallback()
    {
        if (!auth()->user()->hasRole(config('roles.administrator_id')) and $this->authorizationCallback) {
            return $this->authorizationCallback;
        }
        return function () {
            return true;
        };
    }


    public function with($key, $value)
    {
        $this->meta[$key] = $value;

        return $this;
    }


}