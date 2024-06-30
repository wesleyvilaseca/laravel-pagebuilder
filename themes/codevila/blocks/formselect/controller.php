<?php

namespace Themes\Codevila\Blocks\Formselect;

use Illuminate\Support\Facades\Request;
use PHPageBuilder\Modules\GrapesJS\Block\BaseController;

class Controller extends BaseController
{
    /**
     * Handle the current request.
     */
    protected $data;

    public function __construct()
    {
        $this->set_data('teste', 'oi');
    }

    public function handleRequest()
    {
        $this->set_data('request', Request::all());
    }

    public function set_data($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function get_data($key = null)
    {
        if ($key) return $this->data[$key];

        return $this->data;
    }
}
