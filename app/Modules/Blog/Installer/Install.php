<?php

namespace App\Modules\Blog\Installer;


use Library\InstallManager\Manager;


class Install extends Manager
{

    protected $requirements = array(
        'Pagination/Paging' => '1.0',
        'Validation/Validate' => '1.0',
    );

    protected $tables = array('blogs');


    protected function tableBlogs($table)
    {
        $table->increments('id');
        $table->string('email');
    }


    public function __construct($module)
    {
        // $this->install($module);
    }

} 