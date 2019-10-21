<?php

namespace App\Http\Controllers\Users;

use App\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Validation\Rule;
use App\Role;
use DB;
use Gate;



class PermissionController extends Controller
{
    /**
     * Display a listing of the resource. / Отображение списка ресурсов.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Проверка права пользователя на доступ к разделу. Первый аргумент это название действия, второй название/я доступа/ов которое мы передаем в AuthServiceProvider
        $code_access = serialize(['View_admin','View_users']);
        if (Gate::denies('Access_check',$code_access)) { // метод denies() возвращает true, если пользователю запрещено действие указанное в скобках
            return redirect('/admin')->with(['status-error'=>'У вас нет на это прав, обратитесь к администратору.']);
        }

        return view('admin.users.permissions.index', [
            'permissions' => Permission::all() // Записывает в переменную все записи из БД
        ]);
    }

    /**
     * Show the form for creating a new resource. / Показать форму для создания нового ресурса.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Проверка права пользователя на доступ к разделу. Первый аргумент это название действия, второй название/я доступа/ов которое мы передаем в AuthServiceProvider
        $code_access = serialize(['View_admin','View_users','Add_users']);
        if (Gate::denies('Access_check',$code_access)) { // метод denies() возвращает true, если пользователю запрещено действие указанное в скобках
            return redirect('/admin/user-management/permissions')->with(['status-error'=>'У вас нет на это прав, обратитесь к администратору.']);
        }

        return view('admin.users.permissions.create', [
            //'services' => []
        ]);
    }

    /**
     * Store a newly created resource in storage. / Сохраните вновь созданный ресурс в хранилище.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Проверка права пользователя на доступ к разделу. Первый аргумент это название действия, второй название/я доступа/ов которое мы передаем в AuthServiceProvider
        $code_access = serialize(['View_admin','View_permissions','Add_permissions']);
        if (Gate::denies('Access_check',$code_access)) { // метод denies() возвращает true, если пользователю запрещено действие указанное в скобках
            return redirect('/admin/user-management/permissions')->with(['status-error'=>'У вас нет на это прав, обратитесь к администратору.']);
        }

        // Выполняем проверку полученных данных из $request
        $validator = $request->validate([
            'name'=>'required|max:100|unique:permissions',               
            'code'=>'required|max:100|unique:permissions',               
        ]);

        // Выполняем запись в БД
        Permission::create([
            'name' => $request['name'],
            'code' => $request['code'],
        ]);

        return redirect()->route('permissions.index')->with('status','Запись добавлена');
    }

    /**
     * Display the specified resource. / Показать указанный ресурс.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource. / Показать форму для редактирования указанного ресурса.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        // Проверка права пользователя на доступ к разделу. Первый аргумент это название действия, второй название/я доступа/ов которое мы передаем в AuthServiceProvider
        $code_access = serialize(['View_admin','View_permissions','Edit_permissions']);
        if (Gate::denies('Access_check',$code_access)) { // метод denies() возвращает true, если пользователю запрещено действие указанное в скобках
            return redirect('/admin/user-management/permissions')->with(['status-error'=>'У вас нет на это прав, обратитесь к администратору.']);
        }

        return view('admin.users.permissions.edit', [
            //'user' => $user,
            'value' => $permission, // Объект записывается в переменную $value, которую мы передаем во view
        ]);
    }

    /**
     * Update the specified resource in storage. / Обновление указанного ресурса в хранилище.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        // Проверка права пользователя на доступ к разделу. Первый аргумент это название действия, второй название/я доступа/ов которое мы передаем в AuthServiceProvider
        $code_access = serialize(['View_admin','View_permissions','Edit_permissions']);
        if (Gate::denies('Access_check',$code_access)) { // метод denies() возвращает true, если пользователю запрещено действие указанное в скобках
            return redirect('/admin/user-management/permissions')->with(['status-error'=>'У вас нет на это прав, обратитесь к администратору.']);
        }

        // Выполняем проверку полученных данных из $request
        $validator = $request->validate([
            'name'=>'required|max:100', 
            Rule::unique('permissions')->ignore($permission->id),           
            'code'=>'required|max:100',
            Rule::unique('permissions')->ignore($permission->id),         
        ]);

        // Выполняем запись в БД
        $permission->name = $request['name'];
        $permission->code = $request['code'];
        $permission->save();

        return redirect()->route('permissions.index')->with('status','Запись обновлена');
    }

    /**
     * Remove the specified resource from storage. / Удалить указаный ресурс из хранилища
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        // Проверка права пользователя на доступ к разделу. Первый аргумент это название действия, второй название/я доступа/ов которое мы передаем в AuthServiceProvider
        $code_access = serialize(['View_admin','View_permissions','Del_permissions']);
        if (Gate::denies('Access_check',$code_access)) { // метод denies() возвращает true, если пользователю запрещено действие указанное в скобках
            return redirect('/admin/user-management/permissions')->with(['status-error'=>'У вас нет на это прав, обратитесь к администратору.']);
        }

        // Узнаем, используют ли роли данный доступ
        $idCurrentPermission = $permission->id;
        $coincidence = 0;
        $roles = Role::all();
        foreach ($roles as $key => $role) {
            $permissions = $role->permissions;
            foreach ($permissions as $key => $rolePermission) {
                if ($idCurrentPermission == $rolePermission->id) {
                    $coincidence++;
                    $roleName[] = $role->name;
                }
            }
        }

        // Удаляем данный доступ, если он не используется ролями
        if ($coincidence == 0) {
            $permission->delete();
            return redirect()->route('permissions.index')->with('status','Запись удалена');
        }

        // Выводим сообщение, что данный доступ нельзя удалить
        $roleName = implode(", ", $roleName);
        return redirect()->route('permissions.index')->with('status-error','Данный доступ нельзя удалить, так-как он используется ролью/ролями: '.$roleName);
    }
}
