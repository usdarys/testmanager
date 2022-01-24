{extends file="navbarView.tpl"}

{block name=main}
	<div class="container d-flex justify-content-center credit-calc-form flex-column">
        <h4 class="border-bottom mb-3 mt-3">Użytkownicy</h4>
        {include file="messages.tpl"}
        <ul class="nav mb-3 mt-3">
            <li class="nav-item">
                <a href="{url action="userSave"}" class="btn btn-success">Dodaj</a>
            </li>
        </ul>
        <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Login</th>
                <th scope="col">Imię</th>
                <th scope="col">Nazwisko</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        {foreach $userList as $user}
            <tr>
                <th scope="row">{$user["id"]}</th>
                <td>{$user["login"]}</td>
                <td>{$user["first_name"]}</td>
                <td>{$user["last_name"]}</td>
                <td class="d-flex justify-content-end">
                    <a href="{url action="userUpdate" id=$user["id"]}" class="btn btn-sm btn-outline-secondary me-2">Edytuj</a>
                    <a href="{url action="userDelete" id=$user["id"]}" class="btn btn-sm btn-outline-danger">Usuń</a>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    </div>
{/block}