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


use Illuminate\Support\HtmlString;
use Illuminate\Contracts\View\Factory;
use App\Exceptions\GeneralException;
use Illuminate\Http\Request;


/**
 * Class Datatables
 */
class Datatables
{    

    protected $type = null;
    protected $view;
    protected $data;
    protected $types;
    protected $types_text;
	protected $request;
	protected $models;
	protected $attributes;
    protected $finalView;
	protected $tempColumn;
  
    public function __construct (Factory $view) {
      $this->view = $view;
      $this->type = null;
      $this->data = ['name' => null,
                    'id' => null,
                    'helper_text' => null,
                    'value' => null,
                    'attributes' => null,
                    'data' => null];
      
      $this->types = ['box', 'label', 'text', 'password', 'email', 'hidden', 'textarea', 'date', 'time', 'dateTime', 'toggle', 'select', 'mulitple', 'slider', 'summernote', 'cropper']; 
      $this->singleAttributes = ['required', 'disabled', 'checked', 'autofocus', 'multiple', 'readonly'];
    }
  
  public function __call($name, $value)
    {
      
          $value = $value[0];
          $method = substr($name, 0, 4);
          $parameter = strtolower(substr($name, 4, 1)) . substr($name, 5);
      
    
          if($value == null){
              if($method == 'attr'){
                  if(!is_null($this->data['attributes'])){
                      $this->data['attributes'] =  array_merge($this->data['attributes'], [$parameter]);
                  } else {
                      $this->data['attributes'] =  [$parameter];
                  }
              } elseif($method == 'data') {
                 if(!is_null($this->data['data'])){
                      $this->data['data'] =  array_merge($this->data['data'], [$parameter]);
                  } else {
                      $this->data['data'] =  [$parameter];
                  }
              } 
          } else {
              if( $method == 'with'){
                  if(!in_array($parameter, array_keys($this->data))){
                      $this->data[$parameter] = $value;
                  }
              } elseif($method == 'attr') {
                  if(!is_null($this->data['attributes'])) {
                        $this->data['attributes'] =  array_merge($this->data['attributes'], [$parameter => $value]);
                  } else {
                        $this->data['attributes'] =  [$parameter => $value];
                  }
              } elseif($method == 'data') {
                  if(!is_null($this->data['data'])) {
                        $this->data['data'] =  array_merge($this->data['data'], [$parameter => $value]);
                  } else {
                        $this->data['data'] =  [$parameter => $value];
                  }
              }
          }
          return $this; // Continue The Chain
    }
  
  
    public function __get($value)
    {
        $method = substr($value, 0, 4);
        $parameter = substr($value, 4);
      
        if($method == 'attr'){ // Add to attributes array
				if(is_null($this->data['attributes'])){
				 $this->data['attributes'] =  [$parameter];
				} else {
				  $this->data['attributes'] =  array_merge($this->data['attributes'], [$parameter]);
				}
        } elseif($method == 'data') { // Add to data array
				if(is_null($this->data['data'])){
					$this->data['data'] =  [$parameter];                
				} else {
					$this->data['data'] =  array_merge($this->data['data'], [$parameter]);
				} 
        } elseif(in_array($value, $this->singleAttributes)){ //Single Attributes
			  if(is_null($this->data['attributes'])){
				 $this->data['attributes'] =  [$value];
			  } else {
				  $this->data['attributes'] =  array_merge($this->data['attributes'], [$value]);
			  }
        } elseif(in_array($value, $this->types)){ //for build()->text type methods
           $this->{$value}();
        }
		
        return $this;
    }
	
  public function datatable()
  {
    $this->type = 'datatables';
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

	
	public function ajaxResponse($models, $attributes)
	{
		$response = array();
		if($models == null || ($models instanceof \Illuminate\Database\Eloquent\Collection  && $models->count() == 0) )
		{
			$response['data'] = "";
			return json_encode($response);
		}
			
		foreach($models as $key=>$model)
		{
			foreach($attributes as $attribute)
			{
				$response['data'][$key][$attribute['data']] = $model->getAttribute($attribute['data']);
			}
			
		}
		return json_encode($response);
	}
	

  public function ajax(String $input)
  {
    $this->data['ajax'] = $input;
    return $this;
  }
  
  public function id(String $input)
  {
    $this->data['id']= $input;
    return $this;
  }
  
  public function data($input)
  {
  	if(is_null($this->data['data'])){
      $this->data['data'] = $input;
    } else {
      $this->data['data'] = array_merge($this->data['data'], $input);
    }
    return $this;
    $this->data['data'] = $input;
    return $this;
  }
  
  public function compile()
  {
  }
	
	public function create()
	{
    	return $this;
	}
	
	public function models($models)
	{
		$this->data['models'] = $models;
    	return $this;
	}
	
	public function addColumn($input)
	{
		$this->data['attributes'][] = $input;
    	return $this;
	}
	
	public function attributes( $attributes)
	{
		$this->data['attributes'] = $attributes;
    	return $this;
	}
	
	public function view($view)
	{
		$this->finalView = $view;
    	return $this;
	}
	
  
  public function render(Request $request)
  {
        $this->compile();
	  
	    if($request->ajax() )
		{
		    return( $this->ajaxResponse($this->data['models'], $this->data['attributes']) );
		}
        $type = $this->type;
        $data = $this->data;
	     $this->tempColumn = null;
        $this->type = null;
      //reset for next render call.
        $this->data = ['name' => null,
                    'id' => null,
                    'helper_text' => null,
                    'value' => null,
                    'attributes' => null,
                    'data' => null];
    
	  	return( view($this->finalView)
			   ->withData($data['data'])
			   ->withDatatable($this->renderComponent('html', $data))
			   ->withDatatablejs($this->renderComponent('js', $data)));
	  
	  
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