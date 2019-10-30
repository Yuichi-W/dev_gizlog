<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUsers;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AdminUsersRequest;

class AdminUserController extends Controller
{
  protected $AdminUsers;

  public function __construct(AdminUsers $AdminUsers)
  {
    $this->$AdminUsers = $AdminUsers;
  }

}

