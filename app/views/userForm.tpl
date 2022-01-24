{extends file="navbarView.tpl"}

{block name=main}
	<div class="container d-flex justify-content-center credit-calc-form flex-column">
<h4 class="border-bottom mb-3 mt-3">{if empty($form->id)}Dodawanie{else}Edycja{/if} użytkownika</h4>
        <form action="{url action="userSave"}"  method="post" class="mb-3">
            <input type="hidden" name="id" value="{$form->id}">
            <div class="mb-3">
            <label for="login_id" class="form-label">Login</label>
                <input type="text" class="form-control" id="login_id" name="login" value="{$form->login}">
            </div>
            <div class="mb-3">
                <label for="first_name_id" class="form-label">Imię</label>
                <input type="text" class="form-control" id="first_name_id" name="first_name" value="{$form->firstName}">
            </div>
            <div class="mb-3">
                <label for="last_name_id" class="form-label">Nazwisko</label>
                <input type="text" class="form-control" id="last_name_id" name="last_name" value="{$form->lastName}">
            </div>
            <div class="mb-3">
                <label for="password_id" class="form-label" aria-describedby="passwordHelp">Hasło</label>
                <input type="password" class="form-control" id="password_id" name="password" value="{$form->password}">
            </div>
            <div class="mb-4">
            <div class="mb-1">Rola:</div>
            {foreach $roles as $role}
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{$role}" id="{$role}" name="role_{$role}" {if in_array($role, $form->roles)}checked{/if}>
                    <label class="form-check-label" for="{$role}">{$role}</label>
                </div>
            {/foreach}
            </div>
        <input type="submit" value="{if empty($form->id)}Dodaj{else}Zapisz{/if} użytkownika" class="btn btn-success">
            <a href="{url action="userList"}" class="btn btn-outline-danger">Anuluj</a>
        </form>
        {include file="messages.tpl"}
    </div>
{/block}