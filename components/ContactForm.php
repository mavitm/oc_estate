<?php namespace Mavitm\Estate\Components;
/**
*@Author Mavitm
*@url http://www.mavitm.com
*/

use Lang;
use Session;
use Validator;
use Cms\Classes\ComponentBase;
use Mavitm\Estate\Models\Message;
use Mavitm\Estate\Models\Realty;
use Mavitm\Estate\Models\Settings;

class ContactForm extends ComponentBase
{
    public $item, $contactMessage;

    public function componentDetails()
    {
        return [
            'name' => 'mavitm.estate::lang.components.contactForm',
            'description' => 'mavitm.estate::lang.components.contactFormDesc'
        ];
    }
    
    public function defineProperties()
    {
        $settings = Settings::instance();
        return [
            'slug' => [
                'title' => 'mavitm.estate::lang.realty.slug',
                'default' => '{{ :slug }}',
                'type' => 'string'
            ]
        ];
    }

    public function onRun()
    {
        $this->item = $this->page['item'] = $this->loadItem();
        $this->contactMessage = Lang::get('mavitm.estate::lang.contactForm.message', [ 'realty' => "ID: {$this->item->id}" ]);
    }

    protected function loadItem()
    {
        $slug = $this->property('slug');
        $item = Realty::isPublished()->where('slug', $slug)->first();

        if(!empty($item->category)) {
            $item->category->setUrl($this->categoryPage, $this->controller);
        }

        return $item;
    }

    public function onHandleForm()
    {
        $item = $this->loadItem();

        $input = post();

        $validator = Validator::make($input, [
            'email' => 'required|email|max:255',
            'phone' => 'required|alpha_num|max:255',
            'name' => 'required|string|max:255',
            'message' => 'required|string|max:999'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        
        Session::flash('formSuccess', Lang::get('mavitm.estate::lang.contactForm.success'));

        $item->messages()->create([
            'email' => $input['email'],
            'phone' => $input['phone'],
            'name' => $input['name'],
            'message' => $input['message']
        ]);
        
        return redirect()->back();
    }
}