<?php
/**
 * Created by PhpStorm.
 * User: mohammedsehweil
 * Date: 12/12/18
 * Time: 2:38 PM
 */


if (!function_exists('case_type')) {
    function case_type($class)
    {
        return kebab_case(class_basename($class));
    }
}


if (!function_exists('get_case_type_model')) {
    function get_case_type_model($caseType)
    {
        $class = studly_case($caseType);

        $class = "App\\Models\\$class";

        if (!class_exists($class)) {
            abort(500, "Class not found");
        }

        $model = app($class);

        if (!$model instanceof \App\Models\SyncableInterface) {
            abort(500, "API Model should implement SyncableInterface");
        }

        return $model;
    }
}

if (!function_exists('case_resource_collection')) {
    /**
     * @param $caseType
     * @param mixed ...$args
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    function case_resource_collection($caseType, ...$args)
    {
        $class = studly_case($caseType);

        $class = "App\\Http\\Resources\\CaseResources\\{$class}ResourceCollection";

        if (!class_exists($class)) {
            $class = "App\\Http\\Resources\\CaseDataResource";
        }

        return new $class(...$args);
    }
}

if (!function_exists('case_resource')) {
    /**
     * @param $caseType
     * @param mixed ...$args
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    function case_resource($caseType, ...$args)
    {
        $class = studly_case($caseType);

        $class = "App\\Http\\Resources\\CaseResources\\{$class}Resource";

        if (!class_exists($class)) {
            $class = "App\\Http\\Resources\\CaseResource";
        }

        return new $class(...$args);
    }
}


if (!function_exists('base_commcare_field_name')) {
    function base_commcare_field_name($field)
    {
        $field = explode('/', $field);
        return end($field);
    }
}


if (!function_exists('withCount')) {
    function withCount($model)
    {
        $withCountArray = $model->withCount ?? [];
        return array_map(function ($value) {
            return snake_case($value) . '_count';
        }, $withCountArray);
    }
}

if (!function_exists('trans_commcare')) {
    function trans_commcare($options, $default = null, $locale = null)
    {
        if (!$locale) {
            $locale = \App::getLocale();
        }

        if (array_has($options, $locale)) {
            return $options[$locale];
        }

        $alias = config('laravellocalization.supportedLocales.' . $locale . '.' . 'alias');

        if (array_has($options, $alias)) {
            return $options[$alias];
        }

        $fallbackLocale = config('app.fallback_locale');

        if (array_has($options, $fallbackLocale)) {
            return $options[$fallbackLocale];
        }

        return $default;
    }
}

if (!function_exists('switch_url')) {
    function switch_url($html = false)
    {

        $locale = \App::getLocale();

        $locales = [];

        foreach (config('laravellocalization.supportedLocales') as $key => $value) {
            if ($locale !== $key) {
                $locales[] = array_merge($value, ['locale_key' => $key]);
            }
        }

        if (!count($locales)) {
            return;
        }

        $first = reset($locales);

        $url = \LaravelLocalization::getLocalizedURL($first['locale_key']);

        if (!$html) {
            return $url;
        }

        return "<a href='{$url}' class='text-white'>{$first['native']}</a>";
    }
}

if (!function_exists('export')) {
    function export($class, $title, $data)
    {
        $class = app($class);

        if (!$class instanceof \App\Export\ExportInterface) {
            abort(500, "Exports should implement ExportInterface");
        }

        $class
            ->title($title)
            ->data($data);

        return $class->apply();
    }
}


if (!function_exists('map_options')) {
    function map_options($data, $model)
    {
        $fields = app(\App\Repositories\PropertyMetaData\PropertyMetaDataRepositoryInterface::class)->typeIs(case_type($model));

        $optionsRepository = app(\App\Repositories\PropertyOption\PropertyOptionRepositoryInterface::class);

        foreach ($data as $key => $value) {

            if (is_array($value) || (empty($value) && (string)$value !== "0")) {
                continue;
            }

            $field = $fields->where('column_name', $key)->first();


            if (!$field) {
                continue;
            }


            if ($field->type() !== 'select') {
                continue;
            }

            $data[$key . '_key'] = $value;

            $option = $optionsRepository->CommCareAndPropertyIs($value, $field->id);

            if (!$option) {
                continue;
            }

            $name = $option->name();

            if (empty(trim($name))) {
                continue;
            }

            $data[$key] = $name;
        }

        return $data;
    }
}


if (!function_exists('array_insert_after')) {
    /**
     * @url https://gist.github.com/wpscholar/0deadce1bbfa4adb4e4c
     *
     * @param array $array
     * @param $key
     * @param $value
     * @return array
     */
    function array_insert_after(array $array, $key, $value)
    {
        $keys = array_keys($array);
        $index = array_search($key, $keys, true);
        $pos = false === $index ? count($array) : $index + 1;
        if (!is_array($value)) {
            $value = [$value];
        }
        return array_merge(array_slice($array, 0, $pos), $value, array_slice($array, $pos));
    }
}


if (!function_exists('array_search_key_by_value')) {
    /**
     * @url https://stackoverflow.com/questions/6661530/php-multidimensional-array-search-by-value
     *
     * @param array $array
     * @param $key
     * @param $value
     * @return array
     */
    function array_search_key_by_value(array $array, $key, $value)
    {
        foreach ($array as $k => $val) {
            if (array_get($val, $key) === $value) {
                return $k;
            }
        }

        return null;
    }
}

/**
 * Force url to switch it's language to current lang
 */
if (!function_exists('force_url_lang')) {
    function force_url_lang($url, $locale = null)
    {
        if (!$locale) {
            $locale = \App::getLocale();
        }

        return \LaravelLocalization::getLocalizedURL($locale, $url);
    }
}


if (!function_exists('array_only_sorted_by_keys')) {
    function array_only_sorted_by_keys($array, $keys)
    {
        $newArray = [];
        foreach ($keys as $index => $key) {
            $newArray[$key] = $array[$key];
        }
        return $newArray;
    }
}
