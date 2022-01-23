{extends file="navbarView.tpl"}

{block name=main}
    <a href="{url action="userCreate"}" class="btn btn-link">Dodaj</a>
	<div class="container d-flex justify-content-center credit-calc-form flex-column">
        <ul>
            {foreach $userList as $user}
                <li>{$user["id"]} {$user["login"]} {$user["first_name"]} {$user["last_name"]}</li>
            {/foreach} 
        </ul>
        {include file="messages.tpl"}
    </div>
{/block}