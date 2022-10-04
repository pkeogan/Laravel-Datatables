<?php

/*
|----------------------------------------------------------------------------------------------------
|      __                               __   __  __________  _____       _______  ____________  ___
|     / /  ____ __________ __   _____  / /  / / / /_  __/  |/  / /      / ____| |/ /_  __/ __ \/   |
|    / /  / __ `/ ___/ __ `| | / / _ \/ /  / /_/ / / / / /|_/ / /      / __/  |   / / / / /_/ / /| |
|   / /__/ /_/ / /  / /_/ /| |/ /  __/ /  / __  / / / / /  / / /___   / /___ /   | / / / _, _/ ___ |
|  /_____\__,_/_/   \__,_/ |___/\___/_/  /_/ /_/ /_/ /_/  /_/_____/  /_____//_/|_|/_/ /_/ |_/_/  |_|
|----------------------------------------------------------------------------------------------------
| Laravel HTML Extra - By Peter Keogan - Link:https://github.com/pkeogan/laravel-html-extra
|----------------------------------------------------------------------------------------------------
|   Title : Datatables Class
|   Desc  : Builder class to make components
|   Useage: Please Refer to readme.md
|
|
 */

namespace Pkeogan\LaravelDatatables;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

/**
 * Class Datatables
 */
class Datatables
{

    protected $with;
    protected $type = null;
    protected $view;
    protected $data;
    protected $types;
    protected $types_text;
    protected $request;
    protected $response;
    protected $models;
    protected $attributes;
    protected $finalView;
    protected $tempColumn;
    protected $buttons;
    protected $alpacaCreate;
    protected $alpacaEdit;
    protected $removeButtons;

    public function __construct(Factory $view)
    {
        $this->view = $view;
        $this->response = null;
        $this->models = null;
        $this->with = null;
        $this->type = null;
        $this->buttons = null;
        $this->data = ['name' => null,
                       'json' => null,
                        'id' => null,
                        'helper_text' => null,
                        'value' => null,
                        'attributes' => null,
                        'data' => null];
    }
  
    public function __call($call, $value)
    {
      
      $method = substr($call, 0, 4);
      $name = strtolower(substr($call, 4, 1)) . substr($call, 5);
      
        if( $method == 'with' && strlen($name) > 0 ){
            $this->with[$name] = $value[0];
        }
        return $this; // Continue The Chain
    }

    public function alpacaEdit($input)
    {
        $this->alpacaCreate = $input;
        return $this;
    }

    public function alpacaIncludeIf($key, $value)
    {
        $this->buttons['alpacaIncludeIf'][$key] = $value;
        return $this;
    }

    public function alpacaCreate($input)
    {
        $this->alpacaEdit = $input;
        return $this;
    }

    public function datatables()
    {
        $this->type = 'datatables';
        return $this;
    }

    public function build()
    {
        return $this;
    }

    public function ajaxResponse($models, $attributes, $response)
    {
        $r = array();
      if( is_null($response) )
      {
        if ($models == null || ($models instanceof \Illuminate\Database\Eloquent\Collection && $models->count() == 0)) {
            $r['data'] = "";
            return json_encode($r);
        }

        foreach ($models as $key => $model) {
            foreach ($attributes as $attribute) {
                $r['data'][$key][$attribute['data']] = $model->getAttribute($attribute['data']);
            }

        }
        return json_encode($r);
      }
      else{
        return json_encode([ 'data' => $response]);
      }
    }

    public function ajax($input)
    {
      if(is_array($input)){
         $this->data['ajax'] = $input;
        return $this;
      } else {
        $this->data['ajax'] = $input;
        return $this;
      }
    }

    public function id(String $input)
    {
        $this->data['id'] = $input;
        return $this;
    }
  

    public function data($input)
    {
        if (!isset($this->data['data'])) {
            $this->data['data'] = $input;
        } else {
            $this->data['data'] = array_merge($this->data['data'], $input);
        }
        return $this;
        $this->data['data'] = $input;
        return $this;
    }


    public function alpacaButtons($input)
    {

		$this->data['buttons'] = config('datatables.buttons');

        return $this;
    }

    public function create()
    {
      //Load the defaults so we can overwrite them if needed.
        $this->data = config('datatables.config');
        return $this;
    }

    public function models($models)
    {
        $this->data['models'] = $models;
      $this->data['response'] = null;
        return $this;
    }
  
      public function response($response)
    {
        $this->data['response'] = $response;
        $this->data['models'] = null;
        return $this;
    }
  
      public function addSetting($name, $value)
    {
        $this->data[$name] = $value;
        return $this;
    }

    public function addColumn($input)
    {
        $this->data['columns'][] = $input;
        return $this;
    }

    public function attributes($attributes)
    {
        $this->data['attributes'] = $attributes;
        return $this;
    }

    public function view($view)
    {
        $this->finalView = $view;
        return $this;
    }

    public function removeElementWithValue($array, $key, $value)
    {
        foreach ($array as $subKey => $subArray) {
            if ($subArray[$key] == $value) {
                unset($array[$subKey]);
            }
        }
        return $array;
    }




    public function compile()
    {
      $temp = $this->data;
      unset($temp['models']);
       unset($temp['response']);
        $this->data['json'] = Encoder::encode($temp);
        return $this;
    }

    public function render(Request $request)
    {
      

      
		$this->data['buttons'] = config('datatables.buttons');
        $this->compile();
        if ($request->ajax()) {
            return ($this->ajaxResponse($this->data['models'], $this->data['columns'], $this->data['response']));
        }
		
        $type = $this->type;
        $data = $this->data;		    
        $this->tempColumn = null;
        $this->type = null;
        if($this->with){
        return (view($this->finalView, $this->with)
                ->withData($data)
                ->withDatatable($this->renderComponent('html', $data))
                ->withDatatablejs($this->renderComponent('js', $data)));
        } else {
              return (view($this->finalView)
                ->withData($data)
                ->withDatatable($this->renderComponent('html', $data))
                ->withDatatablejs($this->renderComponent('js', $data)));
        }

    }

    /**
     * Transform the string to an Html serializable object
     *
     * @param $html
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function toHtmlString($html)
    {
        return new HtmlString($html);
    }

    /**
     * Render Component
     *
     * @param        $name
     * @param  array $arguments
     *
     * @return \Illuminate\Contracts\View\View
     */
    protected function renderComponent($type, $data)
    {

        return new HtmlString(
            $this->view->make('datatables::' . $type, $data)->render()
        );
    }

}
