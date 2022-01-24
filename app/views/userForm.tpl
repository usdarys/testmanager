{extends file="navbarView.tpl"}

{block name=main}
	<div class="container d-flex justify-content-center credit-calc-form flex-column">
        <h5 class="border-bottom mb-3 mt-3">Dodawanie użytkownika</h5>
        <form action="{url action="userCreate"}"  method="post" class="mb-3">
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
                <input type="password" class="form-control" id="password_id" name="password">
            </div>
            <div class="mb-4">
            <div class="mb-1">Role:</div>
            {foreach $roles as $role}
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{$role}" id="{$role}" name="role_{$role}">
                    <label class="form-check-label" for="{$role}">{$role}</label>
                </div>
            {/foreach}
            </div>
            <input type="submit" value="Dodaj użytkownika" class="btn btn-success">
            <a href="{url action="userList"}" class="btn btn-outline-danger">Anuluj</a>
        </form>
        {include file="messages.tpl"}
    </div>
{/block}